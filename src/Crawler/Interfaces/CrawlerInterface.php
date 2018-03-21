<?php

namespace Unisoft\Crawler\Interfaces;

use Zend\ServiceManager\ServiceManager;

interface CrawlerInterface
{
    public function __construct(ServiceManager $serviceManager);

    public function getName();

    public function setName($name);

    public function setToken($token);

    public function getToken();

    public function setApprobo(\Application\Entity\Approbo $approbo);

    public function getApprobo();
}
