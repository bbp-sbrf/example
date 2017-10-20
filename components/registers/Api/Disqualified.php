<?php

namespace app\components\registers\Api;

use app\components\registers\Exception\LinkNotFoundException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\StreamInterface;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

use app\components\registers\Entity\Disqualified as DisqualifiedEntity;

/**
 * Реестр дисквалифицированных лиц
 * Набор содержит перечень дисквалифицированных лиц
 *
 * Class Disqualified
 * @package app\components\registers\Api
 */
class Disqualified extends AbstractApi
{
    /**
     * @var string Идентификационный номер
     */
    public $identifier = '7707329152-registerdisqualified';
    public $mask = '\/(data(.*))\"';

    /**
     * @return string
     */
    public function download($fileName)
    {
        /** @var StreamInterface $file */
        $file = $this->adapter->get($this->getLink());
        return file_put_contents($fileName, $file->getContents());
    }

    /**
     * Гиперссылка (URL) на набор. Всегда на 8 строке таблицы.
     */
    protected function getLink()
    {
        /** @var StreamInterface $link */
        $link = $this->adapter->get($this->endpoint . '/' . $this->identifier);
        $regexp = '~' . $this->identifier . $this->mask . '~';
        preg_match($regexp, $link->getContents(), $mtch);
        if (!$mtch[1]) throw new LinkNotFoundException();
        return $this->endpoint . '/' . $this->identifier . '/' . $mtch[1];
    }

}