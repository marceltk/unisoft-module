<?php

namespace Unisoft\Util;

class Util
{

    public static function getDoctrineFieldName($name)
    {
        $name = ucWords(str_replace("_", " ", $name));
        $name = str_replace(" ", "", $name);

        return $name;
    }

    public static function uniqIdv5($namespace, $name)
    {
        //        if (!self::is_valid($namespace)) {
        //            return false;
        //        }
        $nhex = str_replace(['-', '{', '}'], '', $namespace);
        $nstr = '';
        for ($i = 0; $i < strlen($nhex); $i += 2) {
            $nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
        }

        $hash = sha1($nstr . $name);

        return sprintf(
            '%08s-%04s-%04x-%04x-%12s',
            substr($hash, 0, 8),
            substr($hash, 8, 4),
            (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,
            (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,
            substr($hash, 20, 12)
        );
    }

    public static function uniqIdv4()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    public static function getDoctrineFieldName2($name)
    {
        $name = (str_replace("_", " ", $name));
        $arr = explode(" ", $name);
        for ($i = 0; $i < count($arr); $i++) {
            if ($i > 0) {
                $name .= ucfirst($arr[$i]);
            } else {
                $name = strtolower($arr[$i]);
            }
        }
        $name = str_replace(" ", "", $name);

        return $name;
    }

    public static function strToUpper($string)
    {
        return mb_strtoupper($string, 'UTF-8');
    }

    public static function strToLowerr($string)
    {
        return mb_strtolower($string, 'UTF-8');
    }

    public static function parseXmlToJson($xml)
    {
        $xml = file_get_contents($xml);
        $xml = str_replace(["\n", "\r", "\t"], '', $xml);

        $xml = trim(str_replace('"', "'", $xml));
        $simpleXml = simplexml_load_string($xml);

        return stripslashes(json_encode($simpleXml));
    }

    public static function stripAccents($string)
    {
        $acentos = [
            'A' => '/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;/',
            'a' => '/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/',
            'C' => '/&Ccedil;/',
            'c' => '/&ccedil;/',
            'E' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
            'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
            'I' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
            'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
            'N' => '/&Ntilde;/',
            'n' => '/&ntilde;/',
            'O' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/',
            'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
            'U' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
            'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
            'Y' => '/&Yacute;/',
            'y' => '/&yacute;|&yuml;/',
            'a.' => '/&ordf;/',
            'o.' => '/&ordm;/',
        ];
        $texto = preg_replace($acentos, array_keys($acentos), htmlentities($string, ENT_NOQUOTES, "UTF-8"));

        return $texto;
    }

    public static function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = ['', 'KB', 'MB', 'GB', 'TB'];

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }

    public static function onlyNumber($str)
    {
        $str = (string) $str;
        if ($str[0] == "-") {
            $minus = "-";
        }
        $newStr = "";
        $j = 0;
        for ($i = 0; $i < strlen($str); $i++) {
            $char = $str[$i];
            if ($char >= "0" && $char <= "9") {
                $newStr .= $char;
            }
        }

        return $minus . $newStr;
    }
}
