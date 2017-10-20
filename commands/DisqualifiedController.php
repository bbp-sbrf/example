<?php

namespace app\commands;

use MongoDB\BSON\UTCDateTime;
use yii\console\Controller;

/**
 * Class DisqualifiedController
 * @package app\commands
 */
class DisqualifiedController extends Controller
{
    public $columns = [
        'number' => 'Номер записи из реестра дисквалифицированных лиц',
        'fio' => 'ФИО',
        'birthday' => 'Дата рождения ФЛ',
        'birthplace' => 'Место рождения ФЛ',
        'organization' => 'Наименование организации, где ФЛ работало во время совершения правонарушения',
        'organization_inn' => 'ИНН организации',
        'position' => 'Должность, в которой ФЛ работало во время совершения правонарушения',
        'offense' => 'Cтатья КоАП РФ',
        'offense_organization' => 'Наименование органа, составившего протокол об административном правонарушении',
        'judge_fio' => 'ФИО судьи, вынесшего постановление о дисквалификации',
        'judge_position' => 'Должность судьи',
        'period' => 'Cрок дисквалификации',
        'date_start' => 'Дата начала',
        'date_end' => 'Дата окончания',
    ];

    /**
     * Загрузка справочника дисквалифицированных лиц
     *
     * db.disqualified.find({date_end: {$lte: new ISODate('2017-10-20T00:00:00Z')}}).limit(10).pretty()
     *
     * @return int
     */
    public function actionIndex()
    {
        $disqualified = \Yii::$app->register->api->disqualified();
        $temp = sys_get_temp_dir() . '/' . \Yii::$app->security->generateRandomString();
        $collection = \Yii::$app->mongodb->getCollection('disqualified');
        $collection->remove(); // Очистим старые данные
        if ($disqualified->download($temp)) {
            if (($handle = fopen($temp, "r")) !== FALSE) {
                $row = 0;
                while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                    $row++;
                    if ($row == 1) continue;
                    $insert = array_map(function ($data, $key) {
                        if (in_array($key, ['date_start', 'date_end']))
                            return new UTCDateTime(new \DateTime($data));
                        else return $data;
                    }, $data, array_keys($this->columns));

                    $insert = array_combine(array_keys($this->columns), $insert);
                    $collection->insert($insert);
                }
                fclose($handle);
                unlink($temp);

                \Yii::info('Загружен новый справочник дисквалифицированных лиц');

                return Controller::EXIT_CODE_NORMAL;
            } else return Controller::EXIT_CODE_ERROR;
        }
        \Yii::error('Не найден реестр на сайте налоговой');
        return Controller::EXIT_CODE_ERROR;
    }

    /**
     * Поиск человека по ФИО в загруженном справочнике
     * @param $fio
     */
    public function actionCheck($fio)
    {
        $rows = \Yii::$app->register->findByFio($fio);
        print_r($rows);
    }
}
