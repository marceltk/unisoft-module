<?php

namespace Unisoft;

use Zend\EventManager\EventInterface;

class Module
{

    public function onBootstrap(EventInterface $e)
    {
        return null;
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . "/src/",
                ],
            ],
        ];
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [

            ],
        ];
    }

    public function getConsoleUsage(Console $console)
    {
        return [
            'crawler index [--verbose|-v]' => 'Execução do painel de crawlers.',
            ['--verbose|-v', '(optional) turn on verbose mode'],
        ];
    }
}
