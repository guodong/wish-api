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

        if (isAppleMobile) {
            downloadBtn.attr('href', 'https://itunes.apple.com/cn/app/wishes/id1033182526?l=en&mt=8').text('苹果下载');
        } else if (isAndroid) {
            downloadBtn.attr('href', '/wishes.apk').text('安卓下载');
        }

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