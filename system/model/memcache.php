<?php
namespace system\model;
use system\config;
/*mongdb常规操作*/
// use system\model\memcache;
// memcache::set('key'); 
class memcache
{
    private static $handler;
    private function __construct(){}

    public static function conn(){
        if(!self::$handler){
            $conf = config::get('memcache');
    	    self::$handler = new \Memcache;
            self::$handler->connect('localhost', 11211) or die ("Could not connect");
    	}
    	return self::$handler;
    }

    // 创建
    public function add($key = '' , $val = '' , $exp = 0){
        return self::conn()->add($key , $val , false , $exp);
    }

    //更新
    public function set($key = '' , $val = '' , $exp = 0){
    	return self::conn()->set($key , $val , false , $exp);
    }

     //获取
    public function get($key = ''){
        return self::conn()->get($key);
    }

    //删除
    public function delete($key = '', $timeout = 0){
        return self::conn()->delete($key , $timeout);
    }
}


