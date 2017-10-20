<?php

namespace app\components\registers\Adapter;

use app\components\registers\Exception\HttpException;

interface AdapterInterface
{
    /**
     * @param string $url
     * @throws HttpException
     * @return string
     */
    public function get($url);

    /**
     * @param string $url
     * @param array|string $content
     * @throws HttpException
     * @return string
     */
    public function post($url, $content = '');
}
