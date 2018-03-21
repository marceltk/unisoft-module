<?php

namespace Unisoft\Controller\Interfaces;

use Zend\ServiceManager\ServiceLocatorInterface;

interface ControllerInterface
{

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator);

    public function getServiceLocator();
}
