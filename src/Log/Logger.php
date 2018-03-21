<?php

namespace Unisoft\Log;

class Logger extends \Zend\Log\Logger
{

    protected $priorities = [
        self::EMERG => 'EMG',
        self::ALERT => 'ALT',
        self::CRIT => 'CRT',
        self::ERR => 'ERR',
        self::WARN => 'WRN',
        self::NOTICE => 'NOT',
        self::INFO => 'INF',
        self::DEBUG => 'DBG',
    ];

    public function __construct($options = null)
    {
        parent::__construct($options);
    }
}
