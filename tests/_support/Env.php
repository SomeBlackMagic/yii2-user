<?php
/**
 * Created by PhpStorm.
 * User: wir_wolf
 * Date: 13.01.19
 * Time: 14:00
 */

namespace tests\_support;

class Env
{

    /**
     * Gets the value of an environment variable. Supports boolean, empty and null.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) { return self::value($default);
        }

        switch (strtolower($value))
        {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;

            case 'null':
            case '(null)':
                return null;

            case 'empty':
            case '(empty)':
                return '';
        }

        if (self::strStartsWith($value, '"') && self::strEndsWith($value, '"'))
        {
            return substr($value, 1, -1);
        }

        return $value;
    }

    /**
     * Return the default value of the given value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public static function value($value)
    {
        return $value instanceof \Closure ? $value() : $value;
    }

    /**
     * Determine if a given string ends with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    public static function strEndsWith($haystack, $needles)
    {
        foreach ((array)$needles as $needle)
        {
            if ((string)$needle === substr($haystack, -strlen($needle))) { return true;
            }
        }

        return false;
    }

    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    public static function strStartsWith($haystack, $needles)
    {
        foreach ((array)$needles as $needle)
        {
            if ($needle != '' && strpos($haystack, $needle) === 0) { return true;
            }
        }

        return false;
    }

}