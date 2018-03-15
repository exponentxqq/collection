<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Response.class.php
 * Date: 2018/3/8
 * Time: 19:03
 */

namespace Extend\Base;


class Response
{
    public $code = 0;
    public $msg = '';
    public $data = [];

    private static $instance = null;

    private function __construct($code = 0, $msg = '', $data = [])
    {
        $this->code = $code;
        $this->msg = $msg;
        $this->data = $data;
    }

    public static function getInstance($code = 0, $msg = '', $data = [])
    {
        if(!self::$instance instanceof self){
            self::$instance = new self($code , $msg, $data);
        }
        return self::$instance;
    }
    
    public function toJson()
    {
        header('Content-Type:application/json; charset=utf-8');
        return print json_encode([
            'code'=>$this->code,
            'msg'=>$this->msg,
            'data'=>$this->data
        ]);
    }
}