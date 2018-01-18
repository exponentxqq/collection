<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Query.class.php
 * Date: 2018/1/12
 * Time: 11:26
 */

namespace Extend\Base;


use Think\Page;

trait Query
{
    protected static $query = null;
    /**
     * @param null|BaseModel $query
     * @return Collection
     */
    public static function all($query = null){
        if(!$query){
            $query = new static();
        }

        $data = $query->select();
        return $data;
    }

    /**
     * @param BaseModel [$query]
     * @param int [$page]
     * @param int [$list_row]
     * @return mixed|Collection
     * @throws ModelException
     */
    public static function lists(){
        $varArray = func_get_args();
        if(is_object($varArray[0]) && !($varArray[0] instanceof BaseModel)){
            throw new ModelException('参数错误！');
        }
        /** @var BaseModel $query */
        if(is_object($varArray[0]) && ($varArray[0] instanceof BaseModel)){
            $query = $varArray[0];
            $page = $varArray[1] ?: 1;
            $list_row = $varArray[2];
            static::$query = $query;
        }else{
            $query = new static();
            $page = $varArray[0] ?: 1;
            $list_row = $varArray[1] ?: 15;
        }
        $list = $query->page($page, $list_row)->select();
        return $list;
    }

    public static function paginate($list_row = 15)
    {
        if(is_object(static::$query) && static::$query instanceof BaseModel){
            $query = static::$query;
        }else{
            $query = new static();
        }
        $count = $query->count();
        $page = new Page($count, $list_row);
        $show = $page->show();
        return $show;
    }

    public static function nextPage($page){
        $page = $page ?: 1;
        $next_page = [
            "method" => 'GET',
            "href" => C('TP_URL').ltrim(__ACTION__,'/'). '/page/'.++$page,
        ];
        return $next_page;
    }

    /**
     * @param null $query
     * @return BaseModel|Query|static
     * @throws ModelException
     */
    public static function get($query = null)
    {
        if(is_object($query) && $query instanceof BaseModel) {
            $obj = $query;
        }else{
            $obj = new static();
            if(!is_numeric($query)) return $obj;
            return $obj->find($query);
        }

        return $obj->find();
    }
}