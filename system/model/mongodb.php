<?php
namespace system\model;
use system\config;
/*mongdb常规操作*/
// use system\model\mongodb;
// $res = mongodb::insert(['_id'=>4,'cate'=>2,'od'=>3]);
// mongodb::writes('runoob');
class mongodb
{
    private static $manager;
    private static $bulk;
    private static $writeConcern;
    private static $query;
    private static $db;

    // 创建链接
    public static function conn(){
        if(!self::$manager){
            $conf = config::get('mongo');
            self::$db = $conf['db'];
    		self::$manager = new \MongoDB\Driver\Manager('mongodb://localhost:27017');
        }
    }

    public function bluk_fun(){
        self::conn();
        if(!self::$bulk){
            self::$bulk = new \MongoDB\Driver\BulkWrite();
        }

        if(!self::$writeConcern){
            self::$writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        }
    }

    public function query_fun($condition , $options){
        self::conn();
        self::$query = new \MongoDB\Driver\Query($condition, $options);
    }

    // 写入数据
    public function writes($table = ''){
        self::$manager->executeBulkWrite(self::$db.'.'.$table , self::$bulk , self::$writeConcern);
    }

    // 创建集合
    /*public function createCollection($table = ''){
    	self::conn()->createCollection($table);
    	return true;
    }

    // 删除集合
    public function dropCollection($table = ''){
    	self::conn()->dropCollection($table);
    	return true;
    }*/

    // 创建
    public function insert($params = []){
        self::bluk_fun();
        return self::$bulk->insert($params);
    }

    //更新
    public function update($where_arr = [], $params = []){
    	self::bluk_fun();
        return self::$bulk->update($where_arr , ['$set' => $params] , ['multi' => true, 'upsert' => false]);
    }

    //删除
    public function remove($where_arr = [] , $limit = 0){
        self::bluk_fun();
        return self::$bulk->delete($where_arr , ['limit' => $limit]);
    }

    //查找
    public function find($table = '', $condition = [], $fields=[] , $order = []){
        $options = [
            'projection' => ['_id' => 0],
        ];
        if($order){
            $options['sort'] = $order;
        }
        self::query_fun($condition , $options);
        $cursor = self::$manager->executeQuery(self::$db.'.'.$table, self::$query);
        $result = [];
        foreach ($cursor as $v) {
            $result[] = $v;
        }
        return $result;
    }

    //返回表的记录数
    /*public function count($where = []){
        self::conn();
        return self::$bulk->count($where_arr);
    }*/
}


