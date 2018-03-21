<?php

namespace Unisoft\Crawler\Interfaces;

use Zend\ServiceManager\ServiceManager;

interface CrawlerRunInterface
{
    public function __construct(ServiceManager $serviceManagerAware);

    public function run();
}
