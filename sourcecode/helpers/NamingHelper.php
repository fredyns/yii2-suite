<?php

namespace fredyns\suites\helpers;

/**
 * Description of NamingHelper
 *
 * @author fredy
 */
class NamingHelper
{

    public static function trim($name)
    {
        return trim($name, ", \t\n\r\0\x0B");
    }

    public static function alternate($prefix = '', $name = '', $suffix = '')
    {
        $name = static::trim($name.', '.$suffix);

        if (strlen($prefix) > 0)
        {
            $name .= ' ('.$prefix.')';
        }

        return $name;
    }

    public static function official($prefix = '', $name = '', $suffix = '')
    {
        return static::trim($prefix.' '.$name.', '.$suffix);
    }

}