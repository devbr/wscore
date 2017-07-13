<?php
/**
 * Front Controller
 * PHP version 7
 *
 * @category  Controller
 * @package   Front Controller
 * @author    Bill Rocha <prbr@ymail.com>
 * @copyright 2016 Bill Rocha <http://google.com/+BillRocha>
 * @license   <https://opensource.org/licenses/MIT> MIT
 * @version   0.0.1
 * @link      http://dbrasil.tk/devbr
 */

//Setup and mount Application
//include '.php/App.php';
//$test = App::this()->start();


(include '.php/App.php')->start();


//$teste = new Config\Test('item 1', 'item 2');

echo '<br><b>GetPar: </b>'.Config\Test::this('teste---')->getPar();

echo '<br><b>GetParams: </b><pre>'.print_r(Config\Test::this('teste---')->get(), true).'</pre>';

echo '<br><b>GetParams(public): </b><pre>'.print_r(Config\Test::this('teste---')->get('public'), true).'</pre>';

Config\Test::this()->set('public', 'novo valor');

echo '<br><b>GetParams(array): </b><pre>'.print_r(Config\Test::this('teste---')->get(['parametro', 'public','protected','node']), true).'</pre>';

Config\Test::this()->set(['public'=>'Array public', 'protected'=>'Array protected']);

echo '<br><b>GetParams(array): </b><pre>'.print_r(Config\Test::this('teste---')->get(['parametro', 'public','protected','node']), true).'</pre>';

// ------------------------------- Test2

Config\Test2::this()->set('public', 'Test2');

echo '<hr><pre>'.print_r(Config\Test2::$node, true).'</pre>';

echo '<hr><pre>'.print_r(Config\Test2::$node['Config\Test2']->public, true).'</pre>';

Config\Test2::this()->set(['public'=>'Test2-123', 'protected'=>'Test2-protegido']);

echo '<br><b>GetParams: </b><pre>'.print_r(Config\Test2::this()->get(), true).'</pre>';


class XXX
{
    protected $protected = 'Class - protected';
    public $public = 'Class - public';
    private $private = 'Class - private';


    function tClass()
    {
        echo '<br><b>CLASS ---- </b><br>';
    }
}


Config\Test2::this()->set(new xxx);
echo '<br><b>GetParams: </b><pre>'.print_r(Config\Test2::this()->get(), true).'</pre>';


exit('<hr>Finished!<pre>'.print_r($teste, true));

//Route detection and controller call 
//Devbr\Router::this()->run();

/*
  Optimize your application before putting it into production mode with the following command:
  "composer dump-autoload --optimize"
*/
