//$(function() {

    $('.modal').modal();

    $(".not-collapse").on("click", function(e) {
        e.stopPropagation();
        if($(this).hasClass("modal-trigger")){
            $($(this).attr('href')).modal("open");
        }
    });
    /*
    var scrollTop = $(window).scrollTop();
    $(window).bind('scroll',function(){
        ($(window).scrollTop() < scrollTop) ? (($(window).width()>700) ? ($('.nav-space').css('height',$('#nav').height()+'px'),$('#nav').addClass('sticky')) : ($('.nav-space').css('height',$('#header').height()+'px'),$('#header').addClass('sticky'))) : (($(window).width()>700) ? ($('#nav').removeClass('sticky'),$('.nav-space').css('height','0px')) : ($('#header').removeClass('sticky'),$('.nav-space').css('height','0px')));
          scrollTop = $(window).scrollTop();
    });*/

// Logout settings
    var displaySpinner;
    var displaySpinner_2 = null;
    var k = 0;
    $.ajaxSetup({
        beforeSend: function() {
            var spinner = $(
                '<div class="preloader-wrapper active">'+
                '   <div class="spinner-layer">'+
                '      <div class="circle-clipper left">'+
                '        <div class="circle"></div>'+
                '      </div>'+
                '      <div class="gap-patch">'+
                '        <div class="circle"></div>'+
                '      </div>'+
                '      <div class="circle-clipper right">'+
                '        <div class="circle"></div>'+
                '      </div>'+
                '    </div>'+
                '  </div>');
            k++;
            if(k==1){
                displaySpinner = setTimeout(function(){
                    if($('.spinner-layer').length == 0){
                        $("#waitingSpinner .spinninAround").append(spinner);
                    }
                    $('#waitingSpinner').modal("open");
                },1300)
            } else if (k==2) {
                displaySpinner_2 = setTimeout(function(){
                    if($('.spinner-layer').length == 0){
                        $("#waitingSpinner .spinninAround").append(spinner);
                    }
                    $('#waitingSpinner').modal("open");
                },1300)
            }

        },
        complete: function(){
            k--;
            clearTimeout(displaySpinner);
            if(displaySpinner_2 != null){clearTimeout(displaySpinner_2);}
            $('#waitingSpinner').modal("close");
        },
    });

    $('#waitingSpinner').modal({
        onCloseEnd: $(".spinninAround").empty(),
    });

    $('.button-collapse').sideNav();

    $.delete = function(url, data, callback, type){
 
        if ( $.isFunction(data) ){
            type = type || callback,
                callback = data,
                data = {}
        }
        
        return $.ajax({
            url: url,
            type: 'DELETE',
            success: callback,
            data: data,
            contentType: type
        });
    }

    function setCookie(key, value, expiry) {
        var expires = new Date();
        expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
        document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
    }

    function getCookie(key) {
        var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
        return keyValue ? keyValue[2] : null;
    }

    function eraseCookie(key) {
        var keyValue = getCookie(key);
        setCookie(key, keyValue, '-1');
    }

    /*
    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                c.substring(name.length, c.length);
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }*/

//});
