$(function(){

	var Time = new Date();

	$(".w_header_target a:even").css({"color":"#f50"});
	//导航下拉
	var t = "";
	$(".w_header_nav_top").hover(function(){
		var _this = $(this);
		t = setTimeout(function(){
			_this.find(".w_header_nav_ul").slideDown();
		},200);
	},function(){
		clearTimeout(t);
		$(this).find(".w_header_nav_ul").slideUp(300);
	})
	//下拉结束

	//幻灯片
	var _index = 0;
	var size = $(".w_top_banner ul li").size();
	var speed = 4000;//速度
	var play = null;
	var btn = "";
	var btnSpan = $("div.w_index_btn");

	for(var i = 0;i < size; i++){
		btn += "<span></span>";
	}

	btnSpan.html(btn);

	//点击按钮
	btnSpan.find("span").click(function(){
		if(new Date() - Time > 500){
			_index = $(this).index();
			bannerBtn(); 
		}
	});
	//下一个 与 上一个
	$("div.w_index_cur").click(function(){
		if(new Date() - Time > 500){
			_index += parseInt($(this).attr("data-pl"));
			bannerBtn();
		}
	});
	//停顿时间
	function bannerBtn(){
		Time = new Date();
		clearInterval(play);
		bannerTime();
		bannerPlay();
	}
	// 自动播放
	function bannerTime(){
		play = setInterval(function(){
			_index++;
			bannerPlay();
		},speed);
	}
	//改变状态
	function bannerPlay(){
		if(_index > (size-1)) _index = 0;
		if(_index < 0) _index = (size-1);
		btnSpan.find("span").eq(_index).addClass("w_index_active").siblings().removeClass("w_index_active");
		$(".w_top_banner ul li").eq(_index).fadeIn(800).siblings().fadeOut(800);
	}
	bannerTime();
	bannerPlay();
	//幻灯片结束

	// 我的订单
	$(".w_order_ul").each(function(){
		var height = $(this).find(".w_order_li").height();
		$(this).find(".w_order_li2").css({"height":height,"line-height":height+"px"});
	});

	//获取一个随机的6位数的cookie
	$(".w_insert_cookie").click(function(){
		countdown($(this));
	});

	var curCount = 60;//间隔函数,当前剩余秒数
	var countTime = null;
	//倒计时
	function countdown(_this){
		_this.attr("disabled", "true"); 
		_this.val("在" + curCount + "秒内输入验证码");
		countTime = setInterval(function(){
			if(curCount == 0){
				clearInterval(countTime);
				_this.removeAttr("disabled");
				_this.val("重新获取验证码");
			}else{
				curCount--;
				_this.val("剩余" + curCount + "秒");
			}
		},1000);
		ajaxCodeSend();
	}

	//发送cookie
	function ajaxCodeSend(){
		$.ajax({
			'type':'post',
			'dataType':"json",
			'url':'index.php?m=Home&c=Index&a=randCookie',
			success: function(data){
				alert(data);
			}
		})
	}

});
