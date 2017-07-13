<?php
/**
 * Config\Abstract
 * PHP version 7
 *
 * @category  Config
 * @package   App
 * @author    Bill Rocha <prbr@ymail.com>
 * @copyright 2016 Bill Rocha <http://google.com/+BillRocha>
 * @license   <https://opensource.org/licenses/MIT> MIT
 * @version   0.0.2
 * @link      http://dbrasil.tk/devbr
 */


abstract class ConfigAbstract implements ConfigContract
{
    public static $node = [];

    function __construct()
    {
        $class = get_called_class();

        if (!isset(static::$node[$class])) {
            echo '<br><b>Entrou :(</b><br>';
            static::$node[$class] = $this;
        }
    }
    /**
     * Singleton instanceof
     *
     */
    public static function this()
    {
        $class = get_called_class();

        if (isset(static::$node[$class]) && is_object(static::$node[$class])) {
            return static::$node[$class];
        }
        
        //else...
        return static::$node[$class] = new $class(...func_get_args());
    }


    /**
     * Get Parameters
     *
     * @param  null|string|array $item Responds in three input modes:
     *                                 string -> return "item" (if exists)
     *                                 array  -> returns an array of items (if any)
     *                                 null   -> returns all public and protected itens (array)
     *
     * @return mixed  One or more items (as requested)
     */
    public function get($item = null)
    {
        if ($item === null) {
            return get_object_vars($this);
        }

        if (!is_array($item)) {
            if (!isset($this->$item)) {
                return false;
            }
            return $this->$item;
        }

        $out = [];
        foreach ($item as $i) {
            if (isset($this->$i)) {
                $out[$i] = $this->$i;
            }
        }
        return $out;
    }


    /**
     * Set Parameters
     *
     * @param string|array|object $item Index name of parameter
     *                                  or array of key => value
     *                                  or object public parameters
     *
     * @return boll|object  $this or false
     */
    public function set($item)
    {
        $value = func_get_arg(1);

        if (is_string($item) && $value !== false) {
            $item = [$item=>$value];
        }

        if (is_object($item)) {
            $item = get_object_vars($item);
        }

        if (is_array($item)) {
            foreach ($item as $key => $val) {
                if (isset($this->$key)) {
                    $this->$key = $val;
                }
            }
        } else {
            return false;
        }
        
        return $this;
    }
}
