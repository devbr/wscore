<?php
/**
 * Config\Application
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

return new App;

class App
{

    private static $node =   false;
    private $autoloadPath =  __DIR__.'/Composer/autoload.php';

    private $phpPath =       false;
    private $configPath =    false;
    private $webPath =       false;
    private $htmlPath =      false;

    private $mode =          0;
    private $isPhar =        false;

    private $psr4 =          [];

    private $error_reporting = 'E_ALL ^ E_STRICT ^ E_WARNING';
    private $setlocale = ['LC_ALL', 'pt_BR'];
    private $date_default_timezone_set = 'America/Sao_Paulo';

    // Development only...
    private $display_errors = 1;
    private $display_startup_errors = 1;
    private $track_errors = 1;

    
    function __construct()
    {
        //Set defaults
        $this->setDefaults();

        if (!is_object(static::$node)) {
            static::$node = $this;
        }
    }
    /**
     * Singleton instance
     *
     */
    public static function this()
    {
        if (is_object(static::$node)) {
            return static::$node;
        }
        //else...
        return static::$node = new static;
    }

   

    public function start($autoloadPath = null, $webPath = null)
    {
        global $argv;
        
        // Web
        if ($this->webPath === false) {
            $base = $webPath != null && is_dir($webPath) ? $webPath : dirname(__DIR__);

            $this->webPath = str_replace('\\', '/', strpos($webPath, 'phar://') !== false
                        ? dirname(str_replace('phar://', '', $webPath)).'/'
                        : $webPath.'/');
        }

        // Is Phar?
        if ($this->isPhar === false) {
            $this->isPhar = strpos($this->webPath, 'phar://') !== false ? $this->webPath : false;
        }

        // Composer autoloader
        $autoload = $autoloadPath != null && file_exists($autoloadPath) ? $autoloadPath : $this->autoloadPath;

        if (file_exists($autoload)) {
            $this->getComposerConfig(include $autoload);

            // HTML's template directory
            if ($this->htmlPath === false) {
                $this->htmlPath = $this->configPath.'Html/';
            }

            //Onm Cli mode
            if (php_sapi_name() === 'cli') {
                return new Devbr\Cli\Main($argv);
            }
        } else {
            throw new Exception("ERROR: Can't find autoloader!", 1);
            exit();
        }

        return $this;
    }


     /**
     * Switchover back
     *
     * @return void send HTML to browser
     */
    public function welcomePage()
    {
        if (!file_exists($this->htmlPath.'welcome.html')) {
            exit('<html><head><style>h1{font-size:2em;margin:0;padding:5px 0}div{position:absolute;top:50%;left:50%;width:50%;margin:-145px 0 0 -25%;text-align:center}</style></head><body><a href="https://github.com/devbr/website"><div><h1>Hello World!</h1>More info in Github.</div></a></body></html>');
        }
        //\Devbr\App::e($this->htmlPath);
        //call html ...end send
        (new Devbr\Html)->sendPage('welcome', ['title'=>'Hello World'], null, 'main.min', 'main.min');
    }


    public function pageNotFound()
    {
        if (!file_exists($this->htmlPath.'nopage.html')) {
            exit('<html><head><style>h1{font-size:2em;margin:0;padding:5px 0}div{position:absolute;top:50%;left:50%;width:50%;margin:-145px 0 0 -25%;text-align:center}</style></head><body><a href="'._URL.'"><div><h1>Page not found!</h1>Click to homepage.</div></a></body></html>');
        }

        //Else...
        (new Devbr\Html) //->sendCache()
        ->header(false)
        ->body('nopage')
        ->footer(false)

        ->render()
        ->send();
    }



    // ---------------- PRIVATES --------------------

    /**
     * [setDefaults description]
     */
    private function setDefaults()
    {
        // Defaults
        if (isset($this->error_reporting) && $this->error_reporting !== false) {
            error_reporting($this->error_reporting);
        }
        if (isset($this->setlocale) && count($this->setlocale) == 2) {
            setlocale($this->setlocale[0], $this->setlocale[1]);
        }
        if (isset($this->date_default_timezone_set) && date_default_timezone_set !== false) {
            date_default_timezone_set($this->date_default_timezone_set);
        }

        // Development only...
        if (isset($this->display_errors)) {
            ini_set('display_errors', $this->display_errors);
        }
        if (isset($this->display_startup_errors)) {
            ini_set('display_startup_errors', $this->display_startup_errors);
        }
        if (isset($this->track_errors)) {
            ini_set('track_errors', $this->track_errors);
        }
    }


    /**
     * Get Composer Configurations
     *
     * @param  Composer\Autoload\ClassLoader $composer Composer object
     *
     * @return void                                    Void
     */
    private function getComposerConfig(Composer\Autoload\ClassLoader $composer)
    {

        $composerPsr4 = $composer->getPrefixesPsr4();

        if (isset($composerPsr4['Devbr\\'])) {
            foreach ($composerPsr4['Devbr\\'] as $dir) {
                $this->psr4[] = realpath($dir).'/';
            }
        }

        $appPhp = $composer->getFallbackDirsPsr4();
        $this->phpPath = realpath(isset($appPhp[0])?$appPhp[0]:__DIR__).'/';

        $this->configPath = realpath(isset($composerPsr4['Config\\'][0])?$composerPsr4['Config\\'][0]:__DIR__.'/Config').'/';
    }


    // PRS4 list (array)
    public function getPsr4()
    {
        return $this->psr4;
    }

    // PHP path
    public function getPhp()
    {
        return $this->phpPath;
    }

    // CONFIG path
    public function getConfig()
    {
        return $this->configPath;
    }

    // WEB
    public function setWeb($dir)
    {
        if (!is_string($dir)) {
            return false;
        }
        $dir = realpath($dir);
        $this->webPath = $dir !== '' ? $dir : false;

        return $this;
    }

    public function getWeb()
    {
        return $this->webPath;
    }

    
    // HTML path
    public function setHtml($dir)
    {
        if (!is_string($dir)) {
            return false;
        }
        $dir = realpath($dir);
        $this->htmlPath = $dir !== '' ? $dir : false;

        return $this;
    }

    public function getHtml()
    {
        return $this->htmlPath;
    }


    // IS PHAR
    public function setIsPhar($phar)
    {
        if (!is_bool($phar)) {
            return false;
        }
        $this->isPhar = $phar;

        return $this;
    }

    public function getIsPhar()
    {
        return $this->isPhar;
    }
    
    // MODE
    public function setMode($mode)
    {
        if (!is_numeric($mode)) {
            return false;
        }
        $this->mode = $mode == 0 ? 0 : 1;

        return $this;
    }

    public function getMode()
    {
        return $this->mode;
    }

    // --- STATIC ACCESS Fnctions -------------------- (get only)
    
    // PHP path
    public static function Php()
    {
        return static::$node->phpPath;
    }

    // CONFIG path
    public static function Config()
    {
        return static::$node->configPath;
    }

    // WEB
    public static function Web()
    {
        return static::$node->webPath;
    }
    
    // HTML path
    public static function Html()
    {
        return static::$node->htmlPath;
    }


    // IS PHAR
    public static function IsPhar()
    {
        return static::$node->isPhar;
    }
    
    // MODE
    public static function Mode()
    {
        return static::$node->mode;
    }
}
