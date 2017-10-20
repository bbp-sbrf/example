<?php


namespace app\components\registers;

use app\components\registers\Adapter\AdapterInterface;
use app\components\registers\Api\Disqualified;

class Register
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }


    /**
     * @return Disqualified
     */
    public function disqualified()
    {
        return new Disqualified($this->adapter);
    }

}