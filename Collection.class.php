<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * Date: 2018/1/4
 * Time: 15:51
 */

namespace Extend\Base;


use ArrayAccess;
use Iterator;
use Countable;
use JsonSerializable;
use Think\Page;

class Collection implements ArrayAccess, Countable, JsonSerializable, Iterator
{
    protected $items;
    private $index = 0;

    public function __construct($items = [])
    {
        $this->items = $items;
    }

    public function toArray($source = false)
    {
        return array_map(function ($value) use ($source) {
            return ($value instanceof BaseModel || $value instanceof self) ?
                $value->toArray($source) :
                $value;
        }, $this->items);
    }

    /**
     * @param $field
     * @param $value
     * @return Collection
     */
    public function getByColumn($field, $value)
    {
        $result = new Collection();
        foreach ($this->items as $item) {
            if ($item->$field == $value) {
                $result->push($item);
            }
        }
        return $result;
    }

    public function getKeyByColumn($field, $value)
    {
        foreach ($this->items as $key => $item){
            if($item->$field == $value){
                return $key;
            }
        }
        return null;
    }

    public function sortByColumn($field, $sort = SORT_ASC)
    {
        array_multisort($this->column($field), $sort, $this->items);
        return $this;
    }

    public function column($field, $source = true)
    {
        $arr = [];
        foreach ($this->items as $key => $value) {
            $arr[] = $value->getAttr($field, $source);
        }
        return $arr;
    }

    public function reduce(\Closure $closure, $is_collection = false)
    {
        $item = array_reduce($this->items, $closure);
        return $is_collection ? new Collection($item) : $item;
    }

    public function isEmpty()
    {
        return $this->items ? false : true;
    }

    public function merge($list)
    {
        if ($list instanceof self) {
            $this->items = array_merge($this->items, $list->toArray(true));
        } else {
            $this->items = array_merge($this->items, $list);
        }
        return $this;
    }

    public function slice($offset, $length = 0)
    {
        $items = $length ? array_slice($this->items, $offset, $length) : array_slice($this->items, $offset);
        $collection = clone $this;
        $collection->items = $items;
        return $collection;
    }

    public function shuffle()
    {
        shuffle($this->items);
        return $this;
    }

    public function isFirst()
    {
        return $this->index === 0;
    }

    public function first()
    {
        return reset($this->items);
    }

    public function end()
    {
        return end($this->items);
    }

    public function eq($index)
    {
        if(isset($this->items[$index])){
            return $this->items[$index];
        }
        return null;
    }

    public function isEnd()
    {
        return $this->index + 1 >= $this->count();
    }

    public function push($item)
    {
        array_push($this->items, $item);
    }

    public function pop()
    {
        return array_pop($this->items);
    }

    public function rewind()
    {
        $this->index = 0;
    }

    public function valid()
    {
        return isset($this->items[$this->index]);
    }

    public function current()
    {
        return $this->items[$this->index];
    }

    public function next()
    {
        return $this->index++;
    }

    public function key()
    {
        return $this->index;
    }

    //    public function getIterator()
    //    {
    //        return new ArrayIterator($this->items);
    //    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->items);
    }

    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    public function count()
    {
        return count($this->items);
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}