<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Request.class.php
 * Date: 2018/1/17
 * Time: 10:13
 */

namespace Extend\Base;


class Request
{
    private $server = [];

    private $device = [];

    private static $instance = null;

    private function __construct()
    {

    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    private function __clone()
    {
    }

    public function url($option = [])
    {
        if(is_array($option)){
            $domain = $option['domain'] ?: $this->domain();
            $module = $option['module'] ?: $this->module();
            $controller = $option['controller'] ?: $this->controller();
            $action = $option['action'] ?: $this->action();
            $param = $option['param'] ?: '';
            if ($option['param'] && is_array($param)) {
                $tmp = '/';
                foreach ($param as $key => $value) {
                    $tmp .= $key . '/' . $value;
                }
                $param = rtrim($tmp, '/');
            }
            return $domain . '/index.php/' . $module . '/' . $controller . '/' . $action . $param;
        }else{
            return $this->domain().'/index.php/'.$option;
        }
    }

    public function isSsl()
    {
        $server = array_merge($_SERVER, $this->server);
        if (isset($server['HTTPS']) && ('1' == $server['HTTPS'] || 'on' == strtolower($server['HTTPS']))) {
            return true;
        } elseif (isset($server['REQUEST_SCHEME']) && 'https' == $server['REQUEST_SCHEME']) {
            return true;
        } elseif (isset($server['SERVER_PORT']) && ('443' == $server['SERVER_PORT'])) {
            return true;
        } elseif (isset($server['HTTP_X_FORWARDED_PROTO']) && 'https' == $server['HTTP_X_FORWARDED_PROTO']) {
            return true;
        }
        return false;
    }

    public function server($name = '')
    {
        if (empty($this->server)) {
            $this->server = $_SERVER;
        }
        if (is_array($name)) {
            return $this->server = array_merge($this->server, $name);
        }

        return $name ? $this->server[$name] : $this->server;
    }

    public function input($data, $source = false)
    {
        return $source ? $_REQUEST : I($data);
    }

    public function domain()
    {
        return ($this->isSsl() ? 'https' : 'http') . '://' . $this->host();
    }

    public function host()
    {
        if (isset($_SERVER['HTTP_X_REAL_HOST'])) {
            return $_SERVER['HTTP_X_REAL_HOST'];
        }
        return $this->server('HTTP_HOST');
    }

    public function module()
    {
        return MODULE_NAME;
    }

    public function controller()
    {
        return CONTROLLER_NAME;
    }

    public function action()
    {
        return ACTION_NAME;
    }

    public function device($name = '')
    {
        if (empty($this->device)) {
            $this->device = json_decode($this->server('HTTP_X_DEVICE_INFO'), true);
        }
        return $name ? $this->device[$name] : $this->device;
    }

    public function isIos()
    {
        return $this->device('num') ? true : false;
    }

    public function isAndroid()
    {
        return $this->device('arnum') ? true : false;
    }

    public function isWechat()
    {
        if ( (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false) ) {
            return true;
        }
        return false;
    }

    public function getDeviceVersion()
    {
        if ($this->isIos()) {
            return $this->device('num');
        } elseif ($this->isAndroid()) {
            return $this->device('arnum');
        } else {
            return null;
        }
    }

    public function referer()
    {
        dump($this->server());
    }
}