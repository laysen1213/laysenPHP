<?php
namespace system\model;
use system\config;
/*mongdb常规操作*/
// use system\model\mongo;
// mongo::set('runoob',['cate'=>1 , 'type' => 1 , 'uid' => 0 , 'ip' => getonlineip()]);
class mongo
{
    private static $handler;
    private function __construct(){}

    public static function conn(){
        if(!self::$handler){
            $conf = config::get('mongo');
    	   // self::$conn = new \MongoClient("mongodb://".env('MONGDB_USER').":".env('MONGDB_PASS')."@".env('MONGDB_HOST').":".env('MONGDB_PORT'));
    		self::$handler = new \MongoClient();
    		self::$handler = self::$handler->$conf['db'];
    	}
    	return self::$handler;
    }

    // 创建集合
    public function createCollection($table = ''){
    	self::conn()->createCollection($table);
    	return true;
    }

    // 删除集合
    public function dropCollection($table = ''){
    	self::conn()->dropCollection($table);
    	return true;
    }

    // 创建
    public function insert($table = '', $params = []){
        self::conn()->$table->insert($params);
    	return true;
    }

    //更新
    public function update($table = '', $params = [], $where_arr = []){
    	self::conn()->$table->update($params, $where_arr);
        return true;
    }

    //删除
    public function remove($table = '', $where_arr = []){
        self::conn()->$table->remove($where_arr);
        return true;
    }

    //查找
    public function find($table = '', $where_arr = [], $fields = [] , $page = 0 , $perpage = '' , $order = [])
    {
        $cursor = self::conn()->$table->find($where_arr, $fields);
        if($page){
            $cursor->skip($page);
        }
        if($order){
            $cursor->sort($order);
        }
        if($perpage){
            $cursor->limit($limit);
        }
        $result = [];
        while ($cursor->hasNext())
        {
            $result[] = $cursor->getNext();
        }
        return $result;
    }

    //查找一条记录
    public function findOne($table = '', $condition = [], $fields=[])
    {
        return self::conn()->$table->findOne($condition, $fields);
    }

    //返回表的记录数
    public function count($table = '')
    {
        return self::conn()->$table->count();
    }
}


