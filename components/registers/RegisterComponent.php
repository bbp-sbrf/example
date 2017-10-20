<?php

namespace app\components\registers;

use Yii;
use yii\base\Component;

use app\components\registers\Adapter\GuzzleHttpAdapter;
use yii\mongodb\Query;


class RegisterComponent extends Component
{
    /**
     * @var array
     */
    public $defaultOptions = [
        'timeout' => 30.0,
    ];

    /**
     * @var Register
     */
    public $api;

    public function init()
    {
        parent::init();
        $adapter = new GuzzleHttpAdapter();
        $this->api = new Register($adapter);
    }

    /**
     * Поиск дисквалифицированных лиц по ФИО.
     *
     * @param $fio
     * @return array
     */
    public function findByFio($fio)
    {
        $query = new Query();
        $query->select([])
            ->from('disqualified')
            ->where(['like', 'fio', $fio]);
        return $query->all();
    }

}