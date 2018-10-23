$(function(){
	// 弹性跟随
	$("#nav").nav();
	
	// 画册
	var section1_li = $(".section1_ul li");
	var section1_box = $(".section1 .section1_box");
	var $height = $(".section1_ul li").height();//230
	var $width = $(".section1_ul li").width();//230
	// 鼠标跟随
	$(".section1_ul li").hover(function(e){
		section1_box.css({
			"left" : this.offsetLeft ,
			"top" : this.offsetTop
		});
	});

	/*var header = $("#header");
	$(window).scroll(function(){
		if($(window).scrollTop() > 100){
			header.addClass("on")
		}else{
			header.removeClass("on")
		}
	})*/

});

