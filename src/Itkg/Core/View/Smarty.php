<?php

namespace Itkg\Core\View;

use Smarty as BaseSmarty;

class Smarty extends BaseSmarty
{
    /**
     * @access public
     * @var __TYPE__ __DESC__
     */
    public $default;

    /**
     * Singleton instance
     *
     * @var \Pelican_View
     */
    protected static $_instance = null;

    /**
     * __DESC__
     *
     * @access protected
     * @var __TYPE__
     */
    protected static $head;

    /**
     * __DESC__
     *
     * @access protected
     * @var __TYPE__
     */
    protected $escape;

    /**
     * @access private
     * @var __TYPE__ __DESC__
     */
    public $current_file;

    public $config;

    /**
     * Assign a var to the view template
     *
     * @param string $tpl_var
     * @param mixed $value
     * @param bool $doEscape
     * @return $this
     */
    public function assign($tpl_var, $value = null, $doEscape = true)
    {
        if ($value && ! is_array($value) && $doEscape && is_string($value)) {
            parent::assign($tpl_var, $this->escape($value, $tpl_var));
        } else {
            parent::assign($tpl_var, $value);
        }

        return $this;
    }

    /**
     * Escape var if needed
     *
     * @access public
     * @param string $var
     * @param mixed $tpl_var
     * @return mixed
     */
    public function escape($var, $tpl_var)
    {
        if (empty($this->escape)) {
            //XSS basic cleanup
            $return = \Pelican_Security::escapeXSS($var);
            if ($return != $var) {
                return '';
            }
            return $return;
        } else {
            if (in_array($this->escape, array(
                'htmlspecialchars' ,
                'htmlentities'))) {
                return call_user_func($this->escape, $var, ENT_COMPAT, $this->_encoding);
            }
            if (1 == func_num_args()) {
                return call_user_func($this->escape, $var);
            }
            $args = func_get_args();
            return call_user_func_array($this->escape, $args);
        }
    }

    /**
     * Sets the escape() callback.
     *
     * @access public
     * @param mixed $spec The callback for escape() to use.
     * @return \Zend_View_Abstract
     */
    public function setEscape($spec)
    {
        $this->escape = $spec;
        return $this;
    }

    /**
     * @param \Pelican_Index $head
     * @return $this
     */
    public function setHead(\Pelican_Index $head)
    {
        if (null == self::$head) {
            self::$head = $head;
        }
        return $this;
    }

    /**
     * Constructor
     */
    public function __construct()
    {

        $this->config['caching'] = false;
        $this->config['compile_check'] = true;
        $this->config['debugging'] = false;
        $this->config['auto_literal'] = false;
        $this->config['cache_lifetime'] = 0;
        $this->config['template_dir'] = '';
        $this->config['compile_dir'] = \Pelican::$config["VAR_VIEW_COMPILES_ROOT"];
        $this->config['config_dir'] = '';
        $this->config['cache_dir'] = \Pelican::$config["VAR_CACHE_VIEWS"];
        $this->config['left_delimiter'] = '{';
        $this->config['right_delimiter'] = '}';

        parent::__construct();
        //$this->compile_check = (\Pelican::$config["TYPE_ENVIRONNEMENT"]?true:false);
        $this->caching = $this->config['caching'];
        $this->cache_lifetime = $this->config['cache_lifetime'];
        $this->template_dir = $this->config['template_dir'];
        $this->compile_dir = $this->config['compile_dir'];
        $this->config_dir = $this->config['config_dir'];
        $this->cache_dir = $this->config['cache_dir'];
        $this->left_delimiter = $this->config['left_delimiter'];
        $this->right_delimiter = $this->config['right_delimiter'];

        $this->auto_literal = $this->config['auto_literal'];
        $this->compile_check = $this->config['compile_check'];
        $this->debugging = $this->config['debugging'];

        if (!empty( \Pelican::$config['SMARTY_PLUGINS_DIR']))
            $this->addPluginsDir( \Pelican::$config['SMARTY_PLUGINS_DIR'] );

        $this->use_sub_dirs = true;
        $this->force_compile = false;

        /** config */
        //$this->assign("\Pelicanconfig", &\Pelican::$config);
        $reducedConst['CNT_EMPTY'] = \Pelican::$config['CNT_EMPTY'];
        $reducedConst['DESIGN_HTTP'] = \Pelican::$config['DESIGN_HTTP'];
        $reducedConst['IMAGE_FRONT_HTTP'] = \Pelican::$config['IMAGE_FRONT_HTTP'];
        $reducedConst['MEDIA_HTTP'] = \Pelican::$config['MEDIA_HTTP'];
        if (isset(\Pelican::$config['SKIN_HTTP'])) {
            $reducedConst['SKIN_HTTP'] = \Pelican::$config['SKIN_HTTP'];
        }
        if (isset(\Pelican::$config['CLASS_POPIN_AUTH_ILEX'])) {
            $reducedConst['CLASS_POPIN_AUTH_ILEX'] = \Pelican::$config['CLASS_POPIN_AUTH_ILEX'];
        }
        if (isset(\Pelican::$config['HTTPS_MEDIA'])) {
            $reducedConst['HTTPS_MEDIA'] = \Pelican::$config['HTTPS_MEDIA'];
        }
        if (isset(\Pelican::$config['HTTPS_FRONTEND'])) {
            $reducedConst['HTTPS_FRONTEND'] = \Pelican::$config['HTTPS_FRONTEND'];
        }
        if (isset(\Pelican::$config['CLASS_CONNECTION_ILEX'])) {
            $reducedConst['CLASS_CONNECTION_ILEX'] = \Pelican::$config['CLASS_CONNECTION_ILEX'];
        }
        if (isset(\Pelican::$config['REPLACE_IMG_16_9'])) {
            $reducedConst['REPLACE_IMG_16_9'] = \Pelican::$config['REPLACE_IMG_16_9'];
        }
        $this->assign("pelican_config", $reducedConst);

    }

    /**
     * __DESC__
     *
     * @access public
     * @return \Pelican_Index
     */
    public function getHead()
    {
        return self::$head;
    }

    /**
     * Génération de l'id de \Pelican_Cache de la vue
     *
     * @static
     * @access public
     * @param string $idcache (option) Id de Cache
     * @param array $addon (option) __DESC__
     * @return string
     */
    static function getCacheId($idcache = "", $addon = array())
    {

        $return = $idcache;
        if (is_array($return)) {
            ksort($return);
            $return = implode("|", $return);
        }
        $return .= "|" . implode('|', $addon) . "|" . ($_GET ? serialize($_GET) : "");
        $return = str_replace(array(
            "||" ,
            "{" ,
            "}" ,
            ";" ,
            ":" ,
            "\""), array(
            "|" ,
            "" ,
            "" ,
            "" ,
            "" ,
            ""), $return);
        return $return;
    }
}
