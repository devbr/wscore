<?php
/**
 * Config\Contract
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


interface ConfigContract
{

    function __construct();

    public static function this();

    public function get($item);

    public function set($item);
}
