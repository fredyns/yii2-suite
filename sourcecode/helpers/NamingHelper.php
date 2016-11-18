<?php

namespace fredyns\suites\helpers;

/**
 * Description of NamingHelper
 *
 * @author fredy
 */
class NamingHelper
{

    /**
     * trim unuser character
     *
     * @param string $name
     * @return string
     */
    public static function trim($name)
    {
        return trim($name, ", \t\n\r\0\x0B");
    }

    /**
     * alternate name format
     *
     * @param string $prefix
     * @param string $name
     * @param string $suffix
     * @return string
     */
    public static function alternate($prefix = '', $name = '', $suffix = '')
    {
        $name = static::trim($name.', '.$suffix);

        if (strlen($prefix) > 0)
        {
            $name .= ' ('.$prefix.')';
        }

        return $name;
    }

    /**
     * official name format
     *
     * @param string $prefix
     * @param string $name
     * @param string $suffix
     * @return string
     */
    public static function official($prefix = '', $name = '', $suffix = '')
    {
        return static::trim($prefix.' '.$name.', '.$suffix);
    }

}