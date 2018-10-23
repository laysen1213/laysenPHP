<?php
//生成验证码资料
namespace system\lib;
class verify{
    private static $source;
    private static $width;
    private static $height;
    private static $codenum  = 4;
    private static $code     = '';
    private static $session  = 'verify';
    private static $fontsize = 25;
    private static $fontfile = 'arial.ttf';
    private static $instance = FALSE;

    function __construct( $config ){
        
    }

    public static function generatecode(){
        $code = explode(',', self::$source);
        if(empty($code)){
            $code = array('0-9','a-z');
        }
        $range = array();
        foreach($code as $v){
            $range[] = explode('-', $v);
        }

        while(strlen(self::$code) < self::$codenum){
            $random = array_rand($range);
            $random = $range[$random];
            $codenum= rand(ord($random[0]), ord($random[1]));
            self::$code .= chr($codenum);
        }
        return;
    }

    function create($config = []){
        self::$source   = isset($config['source'])? $config['source']:'2-9,A-H,P-Y';
        self::$width    = isset($config['width'])? $config['width']:80;
        self::$height   = isset($config['height'])? $config['height']:27;
        self::$codenum  = isset($config['codenum'])? $config['codenum']:4;
        self::$fontsize = isset($config['fontsize'])? $config['fontsize']:'14';
        
        $fontfile = dirname(__FILE__).DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR.self::$fontfile;
        $img = @imagecreatetruecolor(self::$width, self::$height) or die('GD库不支持');
        // 设置图像背景
        /*
        srand(time());
        $bg_r = rand(0,180);
        $bg_g = rand(0,180);
        $bg_b = rand(0,180);
        */
        $bg_color = @imagecolorallocate($img, 218, 218, 218);
        @imagefilledrectangle($img, 0, 0, self::$width, self::$height, $bg_color);

        //设置前景颜色
        $fg_r = 255 - 218;
        $fg_g = 255 - 200;
        $fg_b = 255 - 185;
        self::generatecode();

        for($i=0; $i < self::$codenum; $i++){
            $eachwsize    = intval((self::$width-5) / self::$codenum);
            $current_left = ($eachwsize * $i) + intval(($eachwsize) / 2) + rand(-2, 2);

            $current_top  = intval(self::$height / 1.1) + rand(-3, 3);
            $current_top  = ($current_top < 0)? 0:$current_top;

            $font_color = @imagecolorallocate($img, $fg_r, $fg_g, $fg_b);
            //for($j=0; $j<15; $j++){
            //    $x = rand($current_left, $current_left + $eachwsize);
            //    $y = rand(0, $this->height);
             //   @imagesetpixel($img, $x, $y, $font_color);
            //}
            $angle = rand(10,30);
            @imagettftext($img, self::$fontsize, $angle, $current_left, $current_top, $font_color, $fontfile, self::$code{$i});
        }

        if(session_id() == ""){
            session_start();
        }

        $_SESSION[self::$session]   =   md5(strtolower(self::$code));

        header("Cache-Control: no-cache, must-revalidate");
        header("Content-type: image/png");
        imagepng($img);
        imagedestroy($img);
    }

    function verify($code){
        if(session_id() == ""){
            session_start();
        }

        if(!isset($_SESSION[self::$session]))
            return FALSE;
        return (strcmp($_SESSION[self::$session], md5(strtolower($code))) == 0);
    }
}
?>