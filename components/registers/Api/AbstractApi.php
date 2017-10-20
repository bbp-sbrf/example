<?php

namespace app\components\registers\Api;

use app\components\registers\Adapter\AdapterInterface;

abstract class AbstractApi
{
    const STATUS_ERROR_NONE = 0;

    /**
     * @var string
     */
    const ENDPOINT = 'https://www.nalog.ru/opendata';

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @param AdapterInterface $adapter
     * @param string|null $endpoint
     */
    public function __construct(AdapterInterface $adapter, $endpoint = null)
    {
        $this->adapter = $adapter;
        $this->endpoint = $endpoint ?: static::ENDPOINT;
    }


    public function __toString()
    {
        return get_called_class();
    }
}
