$(function() {

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

    // (window.innerWidth > 950) ? ($("#header").hide(), $("#nav").show()) : ($("#header").show(), $("#nav").hide());
    // if($('.fa-sign-out')){
    //     (window.innerWidth > 1050) ? ($(".firm>.fa-users+span, .firm>.fa-briefcase+span, .firm>.fa-cog+span").show(),$(".firm-options").hide()) : ($(".firm>.fa-users+span, .firm>.fa-briefcase+span, .firm>.fa-cog+span").hide(),$(".firm-options").show().css('margin-right','20px'));
    // }

    // $(window).resize(function() {
    //         (window.innerWidth > 950) ? ($("#header").hide(), $("#nav").show()) : ($("#header").show(), $("#nav").hide());
    //         if($('.fa-sign-out')){
    //             (window.innerWidth > 1050) ? ($(".firm>.fa-users+span, .firm>.fa-briefcase+span, .firm>.fa-cog+span").show(),$(".firm-options").hide()) : ($(".firm>.fa-users+span, .firm>.fa-briefcase+span, .firm>.fa-cog+span").hide(),$(".firm-options").show().css('margin-right','20px'));
    //         }
    // });
});
