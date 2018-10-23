$(function(){
	$("#formSubmit").submit(function(){
		var url = $(this).attr("data-url");
		$.post($(this).attr("action") , $(this).serialize() , function(rs){
			alert(rs.msg);
			if(rs.ret == 1){
				window.location.href = url;
			}else{
				changecode();
			}
		},'json');
		return false;
	});

	//验证码更新
	function changecode(){
		var code = $("#code").attr("src");
		$("#code").attr("src",code);
	}
	$(".w_login_code img").click(function(){
		var _this = $(this);
		if(_this.hasClass("codeVer")){
			_this.css('-webkit-animation', 'rotateKey 1s');
			setTimeout(function(){
				_this.css('-webkit-animation', '');
			},1000);
		}
		changecode();
	});

	//修改地址ajax
	function ajax_address(id){
		var url = $(".w_confirm_address").attr("data-url");
		$.ajax({
	        type: "post",
	        dataType: "JSON",
	        url: url,  
	        data:{id:id}, 
		    success: function(data){
		    	$(".w_address_tk input[name='id']").val(data.id);
		    	$(".w_address_tk input[name='label']").val(data.label);
		    	$(".w_address_tk input[name='name']").val(data.name);
		    	$(".w_address_tk input[name='phone']").val(data.phone);
		    	$(".w_address_tk input[name='address']").val(data.address);
		    }
	    });
	}

	// confrim add address
	var label = $(".w_form_label");
	var point_label = $(".w_formts_label");

	var shperson = $(".w_form_shperson");
	var point_shperson = $(".w_formts_shperson");

	var phone = $(".w_form_phone");
	var point_phone = $(".w_formts_phone");

	var address = $(".w_form_address");
	var point_address = $(".w_formts_address");

	$("#w_address_submit").click(function(){
		if(!check_label(label,point_label)){
			return false;
		}
		if(!check_shperson(shperson,point_shperson)){
			return false;
		}
		if(!check_phone(phone,point_phone)){
			return false;
		}
		if(!check_address(address,point_address)){
			return false;
		}
		$("#w_address_form").submit();
	})

	// confrim
	$(".w_confirm_address li").click(function(){
		$(this).addClass("w_confirm_cs").siblings().removeClass("w_confirm_cs");
		var address_id = $(this).attr("data-id");
		$("#address_id").val(address_id);
	})
	$(".xzdz_btn").click(function(){
		$(".w_black").show();
		$(".w_address_tk").fadeToggle(300);
		$(".w_formts").fadeOut();
		var id = $(this).attr("data-id");
		ajax_address(id);
	});
	$(".address_tk_hide,.w_black").click(function(){
		$(".w_address_tk").hide();
		$(".w_formts").fadeOut();
		$(".w_black").fadeOut(300);		
	})
	/*拖动div*/
	var _move = false;//移动标记
	var _x,_y;//鼠标离控件左上角的相对位置
	$(".tuodong").click(function(){
		//alert("click");//点击（松开后触发）
	}).mousedown(function(e){
		_move = true;
		_x = e.pageX - parseInt($(".w_address_tk").css("left"));
		_y = e.pageY - parseInt($(".w_address_tk").css("top"));
	});
	$(document).mousemove(function(e){
		if(_move){
			var x = e.pageX-_x;//移动时根据鼠标位置计算控件左上角的绝对位置
			var y = e.pageY-_y;
			$(".w_address_tk").css({top:y,left:x});//控件新位置
		}
	}).mouseup(function(){
		_move = false;
	//松开鼠标后停止移动并恢复成不透明
	});
})
