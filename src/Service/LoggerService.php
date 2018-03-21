<?php

namespace Unisoft\Service;

use Zend\ServiceManager\ServiceLocatorInterface;

class LoggerService
{

    private static $Logger;

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        self::$Logger = new \Zend\Log\Logger;
        $writer = new \Zend\Log\Writer\Stream('php://output');

        \Zend\Log\Logger::registerErrorHandler(self::$Logger);

        self::$Logger->addWriter($writer);
    }

    public function getLogger()
    {
        return self::$Logger;
    }
}
