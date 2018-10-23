<?php
namespace application\controller;
use application\core\Home_controller;
use system\lib\cache;

class Weixin extends Home_controller{
	public $toUser;
	public $fromUser;
	public $appid = 0;
	public $appsecret = 0;
	public function __construct(){
		parent::__construct();
		$this->appid = 'wx86a1e54a92e07fb2';
		$this->appsecret = '414f9a0730f00dfde74c7b20f54cd9a7';
	}
	public function index(){
		//获得参数 signature nonce token timestamp echostr
		$nonce     = Iget('nonce');
		$token     = 'laysen';
		$timestamp = Iget('timestamp');
		$echostr   = Iget('echostr');
		$signature = Iget('signature');
		//形成数组，然后按字典序排序
		$array = array($timestamp,$nonce,$token);
		sort($array);
		//拼接成字符串,sha1加密 ，然后与signature进行校验
		$str = sha1(implode($array));
		if( $str  == $signature && $echostr){
			//第一次接入weixin api接口的时候
			echo $echostr;
			exit;
		}else{
			$this->reponseMsg();
		}
	}

	public function reponseMsg(){
		//1.获取到微信推送过来post数据（xml格式）
		$postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
		/*$postArr = "<xml><ToUserName><![CDATA[gh_2c60860320b9]]></ToUserName>
<FromUserName><![CDATA[o38PawCqVt2JyWfzi4j2RiEC6_sI]]></FromUserName>
<CreateTime>1529303972</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[subscribe]]></Event>
<EventKey><![CDATA[]]></EventKey>
</xml>";*/
		//2.处理消息类型，并设置回复类型和内容
		$postObj = simplexml_load_string( $postArr );

		$this->toUser   = $postObj->ToUserName;
		$this->fromUser = $postObj->FromUserName;

		//判断该数据包是否是订阅的事件推送
		if( strtolower( $postObj->MsgType) == 'event'){
			//如果是关注 subscribe 事件
			if( strtolower($postObj->Event =='subscribe')){
				$this->msgText('你好啊，欢迎关注我们的微信公众账号');
			}
		}

		// 发送关键字，回复消息
		if(strtolower($postObj->MsgType) == 'text'){
			// 回复图文
			if(trim($postObj->Content) == '图文'){
				$arr = array(
					array(
						'title'=>'imooc',
						'description'=>"imooc is very cool",
						'picUrl'=>'http://www.imooc.com/static/img/common/logo.png',
						'url'=>'http://www.imooc.com',
					),
					array(
						'title'=>'hao123',
						'description'=>"hao123 is very cool",
						'picUrl'=>'https://www.baidu.com/img/bdlogo.png',
						'url'=>'http://www.hao123.com',
					),
					array(
						'title'=>'qq',
						'description'=>"qq is very cool",
						'picUrl'=>'http://www.imooc.com/static/img/common/logo.png',
						'url'=>'http://www.qq.com',
					),
				);
				$this->msgArticle($arr);
			}else{
				switch( trim($postObj->Content) ){
					case 1:
						$content = '您输入的数字是1';
						break;
					case 2:
						$content = '您输入的数字是2';
						break;
					case 3:
						$content = '您输入的数字是3';
						break;
					case 4:
						$content = "<a href='http://www.imooc.com'>慕课</a>";
						break;
					default:
						$content = '你好';
						break;
				}	
				$this->msgText($content);
			}
		}
	}

	//回复用户消息(纯文本格式)
	public function msgText($content = ''){
		$msgType  =  'text';
		$template = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			</xml>";
		$info = sprintf($template, $this->toUser, $this->fromUser, time() , $msgType, $content);
		echo $info;
	}

	// 回复图文消息
	public function msgArticle($arr = array()){
		$msgType = 'news';
		$template = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<ArticleCount>".count($arr)."</ArticleCount>
			<Articles>";
		foreach($arr as $k => $v){
			$template .="<item>
				<Title><![CDATA[".$v['title']."]]></Title> 
				<Description><![CDATA[".$v['description']."]]></Description>
				<PicUrl><![CDATA[".$v['picUrl']."]]></PicUrl>
				<Url><![CDATA[".$v['url']."]]></Url>
				</item>";
		}
		$template .="</Articles>
			</xml> ";
		echo sprintf($template, $this->toUser, $this->fromUser, time(), $msgType);
	}

	public function weixin(){
		// $this->assign('timestamp' , $timestamp);
		// $this->assign('noncestr' , $noncestr);
		// $this->assign('signature' , $signature);
		$this->display('index/weixin');
	}

	/*public function weixin(){
		$res = $this->jssdk();
		$this->assign('appid' , $res['appId']);
		$this->assign('timestamp' , $res['timestamp']);
		$this->assign('noncestr' , $res['nonceStr']);
		$this->assign('signature' , $res['signature']);
		$this->display('index/weixin2');
	}*/

	public function getRandCode(){
		$array = array('A','B','C','D','E','F','G','H','I','J');
		$tmpstr = '';
		$max = count($array);
		for($i = 1; $i <= 16 ; $i++){
			$key = rand(0,$max-1);
			$tmpstr .= $array[$key];
		}
		return $tmpstr;
	}

	public function jssdk(){
		$jsapi_ticket = $this->getJsApiTicket();
		$timestamp = time();
		$noncestr = $this->getRandCode();
		$url = post('url');
		$signature = 'jsapi_ticket='.$jsapi_ticket.'&noncestr='.$noncestr.'&timestamp='.$timestamp.'&url='.$url;
		$signature = sha1($signature);
		json_return(array('ret'=>1,'wx'=>array('timestamp'=>$timestamp,'nonceStr'=>$noncestr,'signature'=>$signature,'appId'=>$this->appid)));
	}

	public function getJsApiTicket(){
		if($_SESSION['ticket_time'] > time() && isset($_SESSION['ticket'])){
			$jsapi_ticket = $_SESSION['ticket'];
		}else{
			$token = $this->access_token();
			$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$token.'&type=jsapi';
			$res = weixin_api($url);
			$jsapi_ticket = $res['ticket'];
			$_SESSION['ticket'] = $jsapi_ticket;
			$_SESSION['ticket_time'] = time() + 7000;
		}
		return $jsapi_ticket;
	}

	public function access_token(){
		if($_SESSION['access_token_time'] > time() && isset($_SESSION['access_token'])){
			$access_token = $_SESSION['access_token'];
		}else{
			$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->appsecret;
			$res = weixin_api($url);
			$access_token = $res['access_token'];
			$_SESSION['access_token'] = $access_token;
			$_SESSION['access_token_time'] = time() + 7000;
		}
		return $access_token;
	}
}