<?php
namespace controller;
class music extends \core\laysen\laysen{
	public function __construct(){
		// parent::__construct();
	}

	public function index(){
		$this->assign('plug' , PLUG);
		$this->assign('static' , '/public/music/');
		$this->display('music/index');
	}

	// 音乐分类
	public function cate(){
		$res = api_connect('Music/musicCate');
		json_return($res);
	}

    public function test(){
        echo post('id') . '----';
        $res = api_connect('Music/musicCate');
        json_return($res);
    }

	// 歌手列表
    public function singer(){
    	$params = array('cid'=>post('cid'));
    	$res = api_connect('Music/musicSinger',$params);
    	json_return($res);
    }

    // 歌曲列表
    public function song(){
    	$params = array('sid'=>post('sid'));
    	$res = api_connect('Music/musicSong',$params);
    	json_return($res);
    }

    // 歌曲列表
    public function songRec(){
    	$res = api_connect('Music/musicSongRec');
    	json_return($res);
    }

    // 歌曲列表
    public function search(){
    	$params = array('word'=>post('word'));
    	$res = api_connect('Music/songSearch',$params);
    	json_return($res);
    }

    // 歌曲列表
    public function songFind(){
    	$params = array('id'=>post('id'));
    	$res = api_connect('Music/musicSongFind',$params);
    	json_return($res);
    }
}