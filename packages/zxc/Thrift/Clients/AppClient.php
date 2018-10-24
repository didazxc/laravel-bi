<?php

namespace Zxc\Thrift\Clients;

use Zxc\Thrift\Client;
use Zxc\Thrift\Hbase\THBaseServiceClient;

class AppClient extends Client
{
    protected $host = 'zeppelin.zxc.yarn.zw.ted';

    protected $port = '9090';

    protected $service = 'Hbase';

    protected $clientName = THBaseServiceClient::class;

    protected $recvTimeoutMilliseconds = 50;

    protected $sendTimeoutMilliseconds = 1000;

    /**
     * @desc
     * @author limx
     * @param array $config
     * @return AppServiceClient
     */
    public static function getInstance($config = [])
    {
        return parent::getInstance($config);
    }


}

