<?php

namespace Unisoft\Crawler\Traits;

use Unisoft\Crawler\Util\Util;

trait CrawlerOutputTrait
{

    public static $STR_CHAR_INFO = ".";
    public static $STR_CHAR_DOT = ".";
    public static $STR_CHAR_ALERT = "#";
    public static $STR_CHAR_LINE = "-";

    public static $SIZE_REPEAT_STRING = 100;

    public function strPad($text, $direction = STR_PAD_RIGHT, $sizeRepeatString = null)
    {
        $strPadString = $this->mbStrPad($text, $sizeRepeatString ? $sizeRepeatString : self::$SIZE_REPEAT_STRING, self::$STR_CHAR_DOT, $direction);

        return Util::stripAccents($strPadString);
    }

    public function alert($text, $sizeRepeatString = 100)
    {
        $strPadString = $this->mbStrPad(" " . $text . " ", $sizeRepeatString ? $sizeRepeatString : self::$SIZE_REPEAT_STRING, self::$STR_CHAR_ALERT, STR_PAD_BOTH) . PHP_EOL;

        return Util::stripAccents($strPadString);
    }

    public function dotLeft($text, $pad_length = 100)
    {
        return $this->mbStrPad($text, $pad_length, self::$STR_CHAR_INFO, STR_PAD_LEFT);
    }


    public function dotRight($text, $pad_length = 100)
    {
        return $this->mbStrPad($text, $pad_length, self::$STR_CHAR_INFO, STR_PAD_RIGHT);
    }

    public function line($text, $sizeRepeatString = 100)
    {
        return $this->mbStrPad(self::$STR_CHAR_LINE, $sizeRepeatString ? $sizeRepeatString : self::$SIZE_REPEAT_STRING, self::$STR_CHAR_LINE) . PHP_EOL;
    }

    public function mbStrPad($input, $pad_length, $pad_string = ' ', $pad_type = STR_PAD_RIGHT, $encoding = null)
    {
        if (!$encoding) {
            $diff = strlen($input) - mb_strlen($input);
        } else {
            $diff = strlen($input) - mb_strlen($input, $encoding);
        }

        return str_pad($input, $pad_length + $diff, $pad_string, $pad_type);
    }
}
