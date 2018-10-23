<?php
namespace system\model;
use system\config;
// 使用
// use system\model\redis;
// redis::setArr('ds',1);
class redis{
    private static $handler;
    private function conn(){
        if(!self::$handler){
            $conf = config::get('redis');
            self::$handler = new \redis();
            self::$handler->connect($conf['host'] , $conf['port']);
            self::$handler->auth($conf['password']);
        }
        return self::$handler;
    }

    // 描述：设置key和value的值
    // 参数：Key Value
    // 返回值：BOOL 成功返回：TRUE;失败返回：FALSE
    public function setArr($key , $value = array()){
        $value = json_encode($value);
        $result = self::conn()->set($key , $value);
        return $result;
    }

    public function getArr($key){
        $result = self::conn()->get($key);
        $result = json_decode($result,true);
        return $result;
    }

    // 描述：设置key和value的值
    // 参数：Key Value
    // 返回值：BOOL 成功返回：TRUE;失败返回：FALSE
    public function set($key , $value = ''){

        $result = self::conn()->set($key , $value);
        return $result;
    }

    // 描述：获取有关指定键的值
    // 参数：key
    // 返回值：string或BOOL 如果键不存在，则返回 FALSE。否则，返回指定键对应的value值。
    public function get($key){
        $result = self::conn()->get($key);
        return $result;
    }

    // 描述：删除指定的键
    // 参数：一个键，或不确定数目的参数，每一个关键的数组：key1 key2 key3 … keyN
    // 返回值：删除的项数
    public function delete($key){
        $result = self::conn()->delete($key);
    }

    // 描述：如果在数据库中不存在该键，设置关键值参数
    // 参数：key value
    // 返回值：BOOL 成功返回：TRUE;失败返回：FALSE
    public function setnx($key , $value){
        $result = self::conn()->setnx($key , $value);
        return $result;
    }

    // 描述：验证指定的键是否存在
    // 参数key
    // 返回值：Bool 成功返回：TRUE;失败返回：FALSE
    public function exists($key){
        $result = self::conn()->exists($key);
        return $result;
    }

    // 描述：数字递增存储键值键.
    // 参数：key value：将被添加到键的值
    // 返回值：INT the new value
    public function incr($key){
        $result = self::conn()->incr($key);
        return $result;
    }

    // 描述：数字递减存储键值。
    // 参数：key value：将被添加到键的值
    // 返回值：INT the new value
    public function decr($key){
        $result = self::conn()->decr($key);
        return $result;
    }

    // 描述：取得所有指定键的值。如果一个或多个键不存在，该数组中该键的值为假
    // 参数：其中包含键值的列表数组
    // 返回值：返回包含所有键的值的数组
    // $result = $redis->getMultiple(array('test1','test2'));  
    public function getMultiple($arr){
        $result = self::conn()->getMultiple($arr);
        return $result;
    }

    // 描述：由列表头部添加字符串值。如果不存在该键则创建该列表。如果该键存在，而且不是一个列表，返回FALSE。
    // 参数：key,value
    // 返回值：成功返回数组长度，失败false
    // $result = $redis->lpush(array('test1','111'));  
    public function lpush($key , $value){
        $result = self::conn()->lpush($key , $value);
        return $result;
    }

    // 描述：由列表尾部添加字符串值。如果不存在该键则创建该列表。如果该键存在，而且不是一个列表，返回FALSE。
    // 参数：key,value
    // 返回值：成功返回数组长度，失败false
    // $result = $redis->rpush(array('test1','111'));  
    public function rpush($key , $value){
        $result = self::conn()->rpush($key , $value);
        return $result;
    }

    // 描述：返回和移除列表的第一个元素
    // 参数：key
    // 返回值：成功返回第一个元素的值 ，失败返回false
    // $result = $redis->lpop('test');  
    public function lpop($key){
        $result = self::conn()->lpop($key);
        return $result;
    }

    // 描述：返回的列表的长度。如果列表不存在或为空，该命令返回0。如果该键不是列表，该命令返回FALSE。
    // 参数：Key
    // 返回值：成功返回数组长度，失败false
    // $result = $redis->lsize('test');  
    public function lsize($key){
        $result = self::conn()->lsize($key);
        return $result;
    }

    // 描述：返回指定键存储在列表中指定的元素。 0第一个元素，1第二个… -1最后一个元素，-2的倒数第二…错误的索引或键不指向列表则返回FALSE。
    // 参数：key index
    // 返回值：成功返回指定元素的值，失败false
    // $result = $redis->lget('test' , 3);  
    public function lget($key , $index){
        $result = self::conn()->lget($key , $index);
        return $result;
    }

    // 描述：为列表指定的索引赋新的值,若不存在该索引返回false.
    // 参数：key index value
    // 返回值：成功返回true,失败false
    // $result = $redis->lset('test' , 3 , '111');  
    public function lset($key , $index , $value){
        $result = self::conn()->lset($key , $index , $value);
        return $result;
    }
}