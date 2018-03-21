<?php

namespace Unisoft\Module;

use Doctrine\ORM\EntityManager;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManager;
use Zend\Mvc\ApplicationInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;
use Zend\Session\Container;

abstract class ModuleImpl
{

    private static $serviceManager;
    private static $entityManager;
    private static $authManager;
    private static $eventManager;
    private static $applicationManager;
    private static $sessionEmpresa;
    private static $sessionUsuario;
    private static $mvcEvent;

    const SESSION_EMPRESA             = "Msfw_Empresa";
    const SESSION_USUARIO             = "Msfw_Usuario";
    const SESSION_CONFIG              = "Msfw_Config";
    const ENTITY_MANAGER_NAME_DEFAULT = "doctrine.entitymanager.orm_default";
    const ENTITY_MANAGER_AUTH_NAME    = "doctrine.authenticationservice.orm_default";

    public function onBootstrap(MvcEvent $mvcEvent)
    {
        self::setMvcEvent($mvcEvent);
        self::setApplicationManager(self::getMvcEvent()->getApplication());
        self::setServiceManager(self::getApplicationManager()->getServiceManager());
        self::setEntityManager(self::getServiceManager()->get(self::ENTITY_MANAGER_NAME_DEFAULT));
        self::setAuthManager(self::getServiceManager()->get(self::ENTITY_MANAGER_AUTH_NAME));
        self::setEventManager(self::getApplicationManager()->getEventManager());

        self::setSessionEmpresa(new Container(self::SESSION_EMPRESA));
        self::setSessionUsuario(new Container(self::SESSION_USUARIO));
    }

    public static function getAuthManager()
    {
        return self::$authManager;
    }

    public static function setAuthManager($authManager)
    {
        self::$authManager = $authManager;
    }

    public static function getServiceManager()
    {
        return self::$serviceManager;
    }

    public static function setServiceManager(ServiceManager $serviceManager)
    {
        self::$serviceManager = $serviceManager;
    }

    public static function getEntityManager()
    {
        return self::$entityManager;
    }

    public static function setEntityManager(EntityManager $entityManager)
    {
        self::$entityManager = $entityManager;
    }

    public static function getEventManager()
    {
        return self::$eventManager;
    }

    public static function setEventManager(EventManager $eventManager)
    {
        self::$eventManager = $eventManager;
    }

    public static function getApplicationManager()
    {
        return self::$applicationManager;
    }

    public static function setApplicationManager(ApplicationInterface $applicationManager)
    {
        self::$applicationManager = $applicationManager;
    }

    public static function getSessionEmpresa()
    {
        return self::$sessionEmpresa;
    }

    public static function setSessionEmpresa(Container $sessionEmpresa)
    {
        self::$sessionEmpresa = $sessionEmpresa;
    }

    public static function getMvcEvent()
    {
        return self::$mvcEvent;
    }

    public static function setMvcEvent(EventInterface $mvcEvent)
    {
        self::$mvcEvent = $mvcEvent;
    }

    public static function getSessionUsuario()
    {
        return self::$sessionUsuario;
    }

    public static function setSessionUsuario(Container $sessionUsuario)
    {
        self::$sessionUsuario = $sessionUsuario;
    }
}
