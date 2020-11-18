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

    function saveFile(blob, filename) {
        if (window.navigator.msSaveOrOpenBlob) {
            window.navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            const a = document.createElement('a');
            document.body.appendChild(a);
            const url = window.URL.createObjectURL(blob);
            a.href = url;
            a.download = filename;
            a.click();
            setTimeout(() => {
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            }, 0)
        }
    }

    function isEmpty(obj) {
        for(var key in obj) {
            if(obj.hasOwnProperty(key))
                return false;
        }
        return true;
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

    function retrieveUpdates(modifyDashboard = false){

        $unvisitedNotifier = $(document).find('.nb-updates-new');
        $notifHolder = $(document).find('.notif-list');
        newUIds = [];
        existingUIds = [];
        $notifHolder.find('.notif-elmt').each(function(_i,e){
            $(e).hasClass('seen') ? newUIds.push($(e).data(('id'))) : existingUIds.push($(e).data(('id')));
        });
        if(modifyDashboard){
            $actHolder = $(document).find('.process-list').eq(0);
            aIds = [];
            sIds = [];
            eIds = [];
            $(document).find('.activity-holder:not(.dummy-activity)').each(function(_i,e){
                aIds.push($(e).data(('id'))); 
            });
            $(document).find('.activity-holder:not(.dummy-activity) .stage-element').each(function(_i,e){
                sIds.push($(e).data(('id'))); 
            });
            $(document).find('.activity-holder:not(.dummy-activity) .event').each(function(_i,e){
                eIds.push($(e).data(('id'))); 
            });
        }
        nbExistingNewNotifs = $unvisitedNotifier.length ? parseInt($unvisitedNotifier.text()) : 0;
        $params = {newUIds: newUIds, existingUIds: existingUIds, md: modifyDashboard};
        if(modifyDashboard){
            $params['aIds'] = aIds;
            $params['sIds'] = sIds;
            $params['eIds'] = eIds;
        }
        $.post(ruurl,$params)
            .done(function(data){
                
                if(data.ntu){
                    return false;
                } else {
                    
                    $(data.rSIds).each(function(i,e){
                        const $stgElmt = $(document).find(`.stage-element[data-id="${e}"]`);
                        const $actElmt = $stgElmt.closest('.activity-component');
                        $actElmt.find('.stage-element').length == 1 ? $actElmt.closest('.activity-holder').remove() : $stgElmt.remove();
                    })

                    $(data.stages).each(function(i,s){
                        if(!s.asd){

                        } else {

                            $actElmt = $($actHolder.data('prototype'));
                            $actElmt.attr('data-id',s.aid).removeClass('dummy-activity').addClass(s.apr).addClass('tbd').css('style','display:none').find('.activity-component').attr({
                                'data-sd' : s.asd,
                                'data-p' : s.ap
                            }).find('.stage-element').attr({
                                'data-sd' : s.sd,
                                'data-p' : s.p,
                                'data-id' : s.id
                            });
                            $actElmt.find('.act-info-name').append(s.an);
                            $actHolder.find('.activity-list').prepend($actElmt);
                        }
                    })

                    $(data.rUIds).each(function(i,e){
                        $(document).find(`.notif-elmt[data-id="${e}"]`).remove();
                    })


                    $(data.notifs).each(function(i,n){
                        $notifHolder.find('.no-update-msg').remove();
                        $notifElmt = $($notifHolder.data('prototype'));
                        $notifElmt.attr('data-id',n.id).find('.notif-user-picture').append(`<img src="/lib/img/user/${n.picture}">`);
                        $notifElmt.find('.notif-time').append(`${n.inserted}`);
                        $notifElmt.find('.notif-msg').append(`${n.msg}`);
                        $notifHolder.append($notifElmt);
                    })
                    
                    var nbNewNotifs = data.nbNew;
                    if(nbExistingNewNotifs){
                        nbExistingNewNotifs + nbNewNotifs == 0 ? $('.nb-updates-new').remove() : $unvisitedNotifier.empty().append(nbExistingNewNotifs + nbNewNotifs);
                    } else {
                        if(nbNewNotifs > 0){
                            $('nav .user-profile-picture').closest('a').append(`<span class="nb-updates-new">${nbNewNotifs}</span>`);
                        }
                    }

                    if(data.noUpdatesMsg){
                        $notifHolder.append(`<li class="no-update-msg">${data.noUpdatesMsg}</li>`);                   
                    }
                }
            })
            .fail(function(data){
                console.log(data);
            })           
    }

    interval = setInterval(function() {retrieveUpdates(window.location.pathname == '/myactivities')}, 15000);
    
    $(document).on('click',function(e){
        var $this = $(e.target);
        if($('.notif-list').length && !$this.closest('.notif-zone').length && $('.notif-list:visible').length){
            $('.notif-list').hide();
        } /*else if ($this.closest('.notif-zone').length) {
            $('.notif-list:visible') ? $('.notif-list').hide() : $('.notif-list').show();
        }*/
    });

    $('.user-notif-link').on('click',function(){

        if($(document).find('.notif-list:visible').length){
            $(document).find('.notif-list').hide();
            $(document).find('.notif-elmt:not(.seen)').addClass('seen');
        } else {
            $(document).find('.notif-list').show(); 
        }

        if($(document).find('.nb-updates-new')){
            $.get(vuurl,null)
                .done(function(){
                    $('.nb-updates-new').remove();
                })
        }
    });


//});
