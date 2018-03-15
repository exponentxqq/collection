<<<<<<< HEAD
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
use JsonSerializable;

class BaseModel extends Model implements ArrayAccess, JsonSerializable
{
    use Query;

    protected $time_format = '';

    public function __get($name)
    {
        return $this->getAttr($name);
    }

    /**
     * 属性过滤器
     *
     * @param string $name [属性名称]
     * @param boolean $source [是否获取原始数据，默认获取过滤后的数据]
     *
     * @return mixed 属性值
     */
    public function getAttr($name, $source = false){
        $value = isset($this->data[$name]) ? $this->data[$name] : null;
        if(!is_null($value)) {
            if (!$source) {
                $method = 'get' . implode('', array_reduce(explode('_', $name), function ($carry, $item) {
                        $carry[] = ucwords($item);
                        return $carry;
                    })) . 'Attr';
                if (method_exists($this, $method)) {
                    $value = $this->$method($value);
                }
                if ($this->time_format && in_array($this->fields['_type'][$name], ['date', 'datetime', 'time'])) {
                    $value = date($this->time_format, strtotime($value));
                }
            }
        }else{
            if(method_exists($this, $name)){
                $value = $this->$name()->where([strtolower($this->trueTableName . '_' . $this->getPk())=>$this->data[$this->getPk()]])->select();
            }
        }

        return $value;
    }

    public function setAttr($name, $value){
        $this->data[$name] = $value;
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

    public function toArray($source = false){
        $data = [];
        foreach($this->data as $key => $item){
            $data[$key] = $this->getAttr($key, $source);
        }
        return $data;
    }

    /**
     * @param array         $options
     * @return Collection|mixed
     */
    public function select($options=[]){
        $data = parent::select($options);
        $collection = new Collection();
        $model = new static();
        foreach($data as $value){
//            $model = new static();
            $model = clone $model;
            $model->data = $value;
            $collection->push($model);
        }
        return $collection;
    }

    /**
     * @param array $options
     * @return $this|mixed
     */
    public function find($options = [])
    {
        $data = parent::find($options);
        return $this;
    }

    /**
     * @param array $options
     * @return $this
     * @throws ModelException
     */
    public function findOrError($options = []){
        if(is_null($options) || empty($options)){
            throw new ModelException('数据不存在');
        }
        $data = parent::find($options);
        if(!$data)
            throw new ModelException('数据不存在！');
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

    public function jsonSerialize()
    {
        return $this->data;
    }

    public function __sleep()
    {
        return ['data'];
    }
=======
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

    public function toArray($source = false){
        $data = [];
        foreach($this->data as $key => $item){
            $data[$key] = $this->getAttr($key, $source);
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
>>>>>>> b522ac97b08422ac7e11e11a6e9717a24695d45a
}