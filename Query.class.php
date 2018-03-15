<<<<<<< HEAD
<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Query.class.php
 * Date: 2018/1/12
 * Time: 11:26
 */

namespace Extend\Base;


use Think\Model;
use Think\Page;

trait Query
{
    protected static $query = null;

    /**
     * 查询总记录数据集
     *
     * @param null|BaseModel|array $query
     * @return Collection
     */
    public static function all($query = null)
    {
        if (is_object($query) && $query instanceof BaseModel) {
            $obj = $query;
        }else{
            $obj = new static();
            if(is_array($query) || is_string($query)){
                $obj->where($query);
            }
        }

        $data = $obj->select();
        return $data;
    }

    /**
     * 查询分页列表数据集
     *
     * @param BaseModel|array [$query]  查询条件或限制，传递数据时就是where条件，为对象时即为BaseModel对象，可指定field，order等
     * @param int [$page]               页数，省略query时，第一个参数就是page
     * @param int [$list_row]           每页记录数
     * @return mixed|Collection
     * @throws ModelException
     */
    public static function lists()
    {
        $varArray = func_get_args();
        if(is_object($varArray[0]) && !($varArray[0] instanceof BaseModel)){
            throw new ModelException('参数错误！');
        }
        /** @var BaseModel $query */
        if(is_object($varArray[0]) && ($varArray[0] instanceof BaseModel)){
            $query = $varArray[0];
            $page = $varArray[1] ?: 1;
            $list_row = $varArray[2] ?: 15;
            static::$query = clone $query;
        }elseif(!is_numeric($varArray[0])){
            $where = $varArray[0];
            $query = new static();
            $query->where($where);
            static::$query = $where;
            $page = $varArray[1] ?: 1;
            $list_row = $varArray[2] ?: 15;
        } else{
            $query = new static();
            $page = $varArray[0] ?: 1;
            $list_row = $varArray[1] ?: 15;
        }
        $list = $query->page($page, $list_row)->select();
        return $list;
    }

    /**
     * 分页数据
     *
     * @param int $list_row
     * @return string
     */
    public static function paginate($list_row = 15)
    {
        if (is_object(static::$query) && static::$query instanceof BaseModel) {
            $query = static::$query;
        } elseif(!is_null(static::$query)) {
            $query = new static();
            $query->where(static::$query);
        }else{
            $query = new static();
        }
        $count = $query->count();
        $page = new Page($count, $list_row);
        $show = $page->show();
        return $show;
    }

    public static function nextPage($page)
    {
        $page = $page ?: 1;
        $next_page = [
            "method" => 'GET',
            "href"   => C('TP_URL') . ltrim(__ACTION__, '/') . '/page/' . ++$page,
        ];
        return $next_page;
    }

    /**
     * 获取单个记录对象，该方法在查询不到数据时会抛出异常
     *  可直接使用主键id
     *  可使用where限定条件，可以是string也可以是array
     *  可以使用BaseModel对象限定，指定order，field等信息
     *
     * @param static|array|string|int $query
     * @return BaseModel|Query|static|Model
     * @throws ModelException
     */
    public static function get($query = null)
    {
        if (is_object($query) && $query instanceof BaseModel) {
            $obj = $query;
        } else {
            $obj = new static();
            if (is_numeric($query)) {
                return $obj->findOrError($query);
            }
            if(is_string($query)){
                return $obj->where($query)->findOrError();
            }
            if(is_array($query)){
                $where = [];
                foreach ($query as $key => $value){
                    if(strpos($key, '|')){
                        $keys = explode('|', $key);
                        foreach ($keys as $k => $field){
                            $where['_complex'][$field] = $value;
                        }
                        $where['_complex']['_logic'] = 'or';
                    }else{
                        $where[$key] = $value;
                    }
                }
                return $obj->where($where)->findOrError();
            }
        }

        return $obj->findOrError();
    }

    /**
     * 排序后获取单个记录对象
     *
     * @param        $field
     * @param array  $where
     * @param string $sort
     * @return $this|mixed
     */
    public static function getByOrder($field, $where = [], $sort = 'desc')
    {
        $obj = new static();
        if($where){
            $obj->where($where);
        }
        if(strpos(strtolower($field), 'asc') || strpos(strtolower($field), 'desc')){
            $obj->order("{$field}");
        }else{
            $obj->order("{$field} {$sort}");
        }
        return $obj->find();
    }

    /**
     * 获取总记录数
     *
     * @param null $query
     * @return int
     */
    public static function amount($query = null){
        if(is_object($query) && $query instanceof BaseModel){
            $obj = $query;
        }else{
            $obj = new static();
            if($query){
                $obj->where($query);
            }
        }
        return (int)$obj->count() ?: 0;
    }

    /**
     * 获取某列的总和
     *
     * @param       $column
     * @param array $where
     * @return int
     */
    public static function total($column, $where = []){
        $obj = new static();
        if($where){
            $obj->where($where);
        }
        return (int)($obj->sum($column) ?: 0);
    }

    /**
     * 获取某列的平均值
     *
     * @param       $column
     * @param array $where
     * @return mixed
     */
    public static function average($column, $where = [])
    {
        $obj = new static();
        if($where){
            $obj->where($where);
        }
        return $obj->avg($column);
    }
=======
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
>>>>>>> b522ac97b08422ac7e11e11a6e9717a24695d45a
}