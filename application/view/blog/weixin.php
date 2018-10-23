<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
<script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script type="text/javascript" src="<?=$plug?>js/jquery-1.8.3.min.js"></script>
<script>
$(function(){
    $.post("<?=U('index/jssdk')?>",{url:'index/weixin'},function(rs){
        wx.config({
            debug: true, // 
            appId: rs.wx.appId,
            timestamp: rs.wx.timestamp,
            nonceStr: rs.wx.nonceStr,
            signature: rs.wx.signature,
            jsApiList: [
                // 所有要调用的 API 都要加到这个列表中
                'checkJsApi',
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
                'onMenuShareQQ',
                'onMenuShareWeibo',
            ]
        });



function share(title, link, imgUrl, desc) {
    wx.ready(function () {
        wx.onMenuShareTimeline({
            title: title, // 分享标题
            link: link, // 分享链接
            imgUrl: imgUrl, // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
                count_share();
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareAppMessage({
            title: title, // 分享标题
            desc: desc, // 分享描述
            link: link, // 分享链接
            imgUrl: imgUrl, // 分享图标
            type: 'link', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
                // 用户确认分享后执行的回调函数
                count_share();
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareQQ({
            title: title, // 分享标题
            desc: desc, // 分享描述
            link: link, // 分享链接
            imgUrl: imgUrl, // 分享图标
            success: function () {
               // 用户确认分享后执行的回调函数
               count_share();
            },
            cancel: function () {
               // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareWeibo({
            title: title, // 分享标题
            desc: desc, // 分享描述
            link: link, // 分享链接
            imgUrl: imgUrl, // 分享图标
            success: function () {
               // 用户确认分享后执行的回调函数
               count_share();
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
    });
}
//统计分享链接
function count_share() {
}

    var share_title = "标题>";
    var share_desc = "描述";
    var share_logo = "http://api.91laysen.cn/upload/2017/1007/1507369824147.jpg";
    var share_url = "http://91laysen.cn";
    share(share_title , share_url , share_logo , share_desc);
    },'json');
})
</script>
</head>
<body>
    <h1>1</h1>
</body>
</html>