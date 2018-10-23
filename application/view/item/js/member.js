$(function(){
	// 每日签到
	$(".w_user_sign").click(function(){
		var _this = $(this);
		var url = _this.attr("data-url");
		$.ajax({
			type : "post",
			datatype : "json",
			url : url,
			success : function(data){

				_this.text("今日已签到");

			}

		});

	});

});