<?php


namespace Config;

class Test extends \ConfigAbstract
{
    private $parametro = 'null';
    public $public = 'public';
    protected $protected = 'protected';


    function __construct($params = '12345', $segundo = '890', $item = "vaca")
    {
        parent::__construct();

        $this->parametro = $params;

        echo '<hr><b>'.__CLASS__.__METHOD__.'</b> - <br><b>Item 1: </b><pre>'.print_r($params, true).'</pre><br><b>Item 2: </b><pre>'.print_r($segundo, true).'</pre><br><b>Item 3: </b><pre>'.print_r($item, true).'</pre><br><b>Itens chamados: </b><pre>'.print_r(func_get_args(), true).'</pre><br><b>Node: </b><pre>'.print_r(static::$node, true).'</pre>';
    }



    public function getPar()
    {
        echo $this->parametro;
        return $this->parametro;
    }
}
