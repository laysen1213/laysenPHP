$(function(){

    //当前BUG  未选中商品，添加数量，数据有误

    //单选删除 
	$(".w_car_mid .w_car_li_seven a").click(function(){

        var cf = confirm("确定是否删除");

        if(cf == false){

           return false;

        }

    	var id = $(this).attr("data-id");

    	del_car(id);

	});

    //全选
  	$(".w_checkall").click(function(){

  		  $('input[type="checkbox"]').attr("checked",this.checked);

  	});

    //全选删除
    $(".w_car_bot_two a").click(function(){

        var cf = confirm("确定是否全部删除");

        if(cf == false){

            return false;

        }

        var size = $(this).attr("data-size");

        //1为可选择删除  0为全部删除
        if(size == '1'){

            var ck=$('.w_car_mid input[type="checkbox"]:checked');

        }else{

            var ck=$('.w_car_mid input[type="checkbox"]');

        } 

        //id转换为数组
        var id = arrId(ck);
        
        del_car(id);

    });

    //购物车复选框点击事件
    $(".w_car_check,.w_checkall").click(function(){

        var ck=$('.w_car_mid input[type="checkbox"]:checked');

        //id转换为数组
        var id = arrId(ck);

        find_car(id);

    });

    //购物车数量
    $(".w_car_mid .car_click").click(function(){

        var size = parseInt($(this).attr("data-size"));

        var pt = $(this).siblings("input.w_car_nums");

        pt.val(parseInt(pt.val())+size);

        var id = pt.attr("data-id");

        var url = pt.attr("data-url");

        var nums = pt.val();

        car_nums(id,nums,url);

    });

    //购物车数量触发keyup事件
    $(".w_car_mid input.w_car_nums").keyup(function(){

        var nums = $(this).val();

        var id = $(this).attr("data-id");

        var url = $(this).attr("data-url");

        car_nums(id,nums,url);

    });

});


//购物车删除ajax
function del_car(id){

    var url = $("#w_car_all_del").attr("data-url");

	$.ajax({

        type : "post",

        dataType : "JSON",

        url : url,  

        data : { id : id + "" }, 

      	success : function(data){ 

            for (var i = 0; i < data.id.length; i++) {

                $("#car_"+data.id[i]+"").remove(); 

            }

            //当购物车没有商品的时候
    		if(data.total.number == 0){

                var html = "<div class='w_car_bot_four w_car_bot_nof'>";

                html += "<img src='/Public/home/img/shopcar.png'>";

                html += "<a href='index.php?m=Home&c=Item&a=lists' class='w_car_bot_gg'>继续逛逛</a></div>"; 

                $(".w_car_bot").remove();

                $(".w_car_content").append(html);   

    		}else{

                numsChange(data.total);
      			
    		}	

        }

    }); 

}

//购物车数量
function car_nums(id,nums,url){

    $.ajax({

        type : "post",

        dataType :"JSON",

        url : url,  

        data : { id : id , nums : nums , }, 

        success : function(data){ 

            $("#car_"+data.id+" .w_car_nums").val(data.single.number);

            $("#car_"+data.id+" .w_car_li_six").text("￥"+data.single.price);

            numsChange(data.total);

        }

    }); 

}

//购物车选择商品ajax
function find_car(id){

    var url = $(".w_checkall").attr("data-url");

    $.ajax({

        type : "post",

        dataType : "JSON",

        url : url,  

        data : { id : id + "" }, 

        success: function(data){ 

            numsChange(data.total);

        }

    }); 

}

//将被选择的id转换为数组
function arrId(ck){

    var id =[]; 

    ck.each(function(){

        id.push($(this).val());   

    });

    return id;

}

//更改购物车总价格、总数量
function numsChange(msg){

    $(".w_del_price font").text("￥" + msg.price);

    $(".w_del_nums font").text(msg.number);

}
