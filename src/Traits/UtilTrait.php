<?php

namespace Unisoft\Traits;

use Unisoft\Util\Util;

trait UtilTrait
{

    public function parseXmlToJson($xml)
    {
        return Util::parseXmlToJson($xml);
    }

    public function stripAccents($string)
    {
        return Util::stripAccents($string);
    }

    public function formatBytes($size, $precision = 2)
    {
        return Util::formatBytes($size, $precision);
    }
}
