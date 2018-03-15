<<<<<<< HEAD
<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Register.class.php
 * Date: 2018/1/13
 * Time: 18:11
 */

namespace Extend\Base;

class Register
{
    const DOT = '·';

    private static $map;

    /**
     * @param      $name
     * @param null $obj
     * @return null
     */
    public static function instance($name, $obj = null){
        if(static::$map[$name]){
            return static::$map[$name];
        }
//        if(!$obj) throw new Exception('对象不存在');
        return static::$map[$name] = $obj;
    }
=======
<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Register.class.php
 * Date: 2018/1/13
 * Time: 18:11
 */

namespace Extend\Base;

class Register
{
    private static $map;

    /**
     * @param      $name
     * @param null $obj
     * @return null
     */
    public static function instance($name, $obj = null){
        if(static::$map[$name]){
            return static::$map[$name];
        }
//        if(!$obj) throw new Exception('对象不存在');
        return static::$map[$name] = $obj;
    }
>>>>>>> b522ac97b08422ac7e11e11a6e9717a24695d45a
}