$(function(){
    //购物车添加数量
    $(".w_content_mid_nums p").click(function(){
        var size = parseInt($(this).attr("data-size"));
        var input_nums = $(this).siblings(".w_content_nums");
        var nums = parseInt(input_nums.val());
        var r = (nums + size) < 1 ? 1 : nums + size;
        input_nums.val(r);
    });

    // 加入购物车
    $(".w_content_mid_gwc").click(function(){
        var nums = parseInt($(".w_content_nums").val());
        var $this = $(this);
        var url = $this.attr("data-url");
        var data = {goods_id : $this.attr("data-goods-id") , nums : nums};
        $.post(url , data , function(rs){
            alert(rs.msg);
        },'json');
    });

    // 添加减少数量
    $(".car_click").click(function(){
        var _this = $(this);
        var _nums = parseInt(_this.attr("data-size"));
        var pt = _this.siblings("input");
        var n = parseInt(pt.val()) + _nums;
        if(n < 1){
            n = 1;
            return false;
        }
        var goods_id = pt.attr("data-goods-id");
        var url = pt.attr("data-url");
        $.post(url,{goods_id:goods_id,nums:_nums},function(rs){
            if(rs.ret == 1){
                pt.val(n);
                count_price();
            }
        },'json');
    });

    //删除商品
    $('.car_del').click(function(){
        if(!confirm('确认删除')){
            return false;
        }
        var _this = $(this);
        var id = parseInt(_this.attr('data-id'));
        var url = _this.attr('data-url');
        $.post(url , {id : id} , function(rs){
            if(rs.ret == 1){
                _this.parents('.w_car_mid').remove();
                count_price();
                if($('.w_car_mid').length <= 0){
                    location.reload();
                    return false;
                }
            }
        },'json');
    });

    //结算提交
    $('.w_car_bot_js').click(function(){
        if($('.w_car_check:checked').length <= 0){
            alert('请选择商品');
            return false;
        }
        var id = [];
        $('.w_car_check:checked').each(function(){
            id.push($(this).val());
        });
        var url = $(this).attr("data-url");
        var href = $(this).attr("data-href");
        $.post(url , {id : id , type : 1} , function(rs){
            if(rs.ret == 1){
                href = href.replace('underfine' , rs.id)
                window.location.href = href;
            }
        },'json');
    });

    // 单选
    $('.w_car_check').click(function(){
        var l = $('.w_car_check').length;
        var _l = $('.w_car_check:checked').length;
        if(l == _l){
            $(".w_checkall").prop("checked" , true);
        }
        if(_l == 0){
            $(".w_checkall").prop("checked" , false);
        }
        count_price();
    });

    // 全选
    $('.w_checkall').click(function(){
        $(".w_car_check").prop("checked" , $(this).is(":checked"));
        count_price();
    });

    // 计算总价格
    function count_price(){
        var total_price = 0;
        var total_nums = 0;

        $('.w_car_mid').each(function(){
            if($(this).find(".w_car_check").is(':checked')){
                var nums = parseInt($(this).find('.w_car_nums').val());
                var price = parseInt($(this).find(".w_car_li_four").attr("data-price"));
                total_price += (nums * price);
                total_nums += nums;
            }
        });
        $(".w_del_nums font").text(total_nums);
        $('.w_del_price font').text(total_price);
    }
    count_price();
});

