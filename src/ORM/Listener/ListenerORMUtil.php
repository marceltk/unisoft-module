<?php
/**
 * Created by PhpStorm.
 * User: marcel
 * Date: 07/01/18
 * Time: 23:12
 */

namespace Unisoft\ORM\Listener;

class ListenerORMUtil
{
    private static $empresaId;

    public static function setEmpresaId($empresaId)
    {
        self::$empresaId = (int) $empresaId;
    }

    public static function getEmpresaId()
    {
        return self::$empresaId;
    }
}
