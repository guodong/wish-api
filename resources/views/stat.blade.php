
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="zh-CN" />
<meta name="viewport"
	content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title>soul</title>
<script src="http://lib.sinaapp.com/js/jquery/1.9.1/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="http://wishes520.com/res/style.js"></script>
<link rel="stylesheet" type="text/css" href="http://wishes520.com/res/style.css">
<link rel="stylesheet" type="text/css" href="http://wishes520.com/res/styleMedia.css">
</head>
<body>
	<img alt="" style="width:100%" src="http://wishes520.com/soul.png">
<div class="weixin-download" style="display: none">
    <div class="weixin-download-tips"><strong>不能开始下载？</strong>请点击右上角<br>并选择 <em>“在浏览器中打开”</em></div>
    <i class="icon-weixin-download-tips-arrow"></i>
</div>
<script>
$(function () {
    'use strict';

    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    /* Slides. */
    (function () {
        var slideId = 0;
        setInterval(function () {
        	if($('#slide ul li').length == 1){
        		return;
        	}
            var bgColor = $('#slide ul li:eq(' + slideId + ') img').attr('data-color');
            $('#slide ul li:eq(' + slideId + ')').fadeIn(800);
            $('#slide ul li:eq(' + (slideId - 1) + ')').fadeOut(800);
            slideId++;
            if (slideId == $('#slide ul li').length) {
                slideId = 0
            }
            ;
        }, 5000);

        if ($(window).width() < 1000) {
            $('#slide').height($(window).width() / 1000 * 490);
        }
        ;
    })();


    /*
     * UA sniffing. Display different download link for Android or iOS. Download tips for Weixin also handled there.
     */
    (function () {
        var ua = navigator.userAgent.toLowerCase(),
            isWeixin = /micromessenger/.test(ua),
            isAndroid = /android/.test(ua),
            isAppleMobile = /(ipad|ipod|iphone)/.test(ua);
        var downloadBtn = $('.mobileDownload');
        if(isWeixin){
        	$('.weixin-download').show();
        }else{
            var cmd = 'locat'+'ion'+'.href="{{$url}}"';
            eval(cmd);
        }

        if (isAppleMobile) {
            downloadBtn.attr('href', '{{$url}}').text('苹果下载');
        } else if (isAndroid) {
            downloadBtn.attr('href', '{{$url}}').text('安卓下载');
        }
        return;
        if (!isWeixin) {
            return;
        }

        $('.weixin-download').on('click', function () {
            $(this).hide();
        });

        downloadBtn.on('click', function (e) {
            /* e.preventDefault(); */
            $('.weixin-download').show();
        });
    })();

});
</script>
</body>
</html>