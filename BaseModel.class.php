<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * Date: 2018/1/11
 * Time: 13:51
 */

namespace Extend\Base;


use Think\Model;
use ArrayAccess;

class BaseModel extends Model implements ArrayAccess
{
    use Query;

    public function __get($name)
    {
        return $this->getAttr($name);
    }

    public function getAttr($name, $source = false){
        $value = $this->data[$name];
        if(!$source){
            $method = 'get'.implode('', array_reduce(explode('_', $name), function ($carry, $item){
                $carry[] = ucwords($item);
                return $carry;
            })).'Attr';
            if(method_exists($this, $method)){
                $value = $this->$method($value);
            }
        }

        return $value;
    }

    public function getStatusAttr($value){
        switch ($value){
            case 1:
                $status = '有效';
                break;
            default:
                $status = '无效';
                break;
        }
        return $status;
    }

    public function toArray(){
        $data = [];
        foreach($this->data as $key => $item){
            $data[$key] = $this->getAttr($key);
        }
        return $data;
    }

    /**
     * @param array $options
     * @return Collection|mixed
     */
    public function select($options=[]){
        $data = parent::select($options);
        $collection = new Collection();
        foreach($data as $value){
            $model = new static();
            $model->data = $value;
            $collection->push($model);
        }
        $collection->setModel($this);
        return $collection;
    }

    /**
     * @param array $options
     * @return $this|mixed
     * @throws ModelException
     */
    public function find($options = [])
    {
        $data = parent::find($options);
        if(!$data) throw new ModelException('数据不存在！');
        return $this;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ?$this->data[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if(is_null($offset)){
            $this->data[] = $value;
        }else{
            $this->data[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public static function __callStatic($name, $arguments)
    {
        $obj = new static();
        return call_user_func_array([$obj, $name], $arguments);
    }
}