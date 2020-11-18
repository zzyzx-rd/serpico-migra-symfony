//$(function() {
    $('.modal').modal();

    const $upgradeModal = $('#upgradeAccount');
    if($upgradeModal.length){
        $upgradeModal.modal({
            dismissible: false,
        })
        if($upgradeModal.hasClass('tbo')){
            $upgradeModal.modal('open');
        }
    }

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

    function checkAccount(){
        if(!$('#upgradeAccount').length || !$('#upgradeAccount .modal-content').length){
            window.location.href = '/logout';
        }
        const $furls = ['/','/pricing','/terms-and-conditions', '/terms/conditions/cookies', '/login'];
        if ($furls.indexOf(window.location.pathname) == -1){
            $.get(caurl,null)
            .done(function(data){
                if(data.la){
                    $('#upgradeAccount').modal('open');
                }
            })
        }
    }

    function checkNotifMails(){
        const $furls = ['/','/pricing','/terms-and-conditions', '/terms/conditions/cookies', '/login'];
        if ($furls.indexOf(window.location.pathname) == -1){
            $.get(cnurl,null)
        }
    }

    function retrieveUpdates(modifyDashboard = false){
        const $furls = ['/','/pricing','/terms-and-conditions', '/terms/conditions/cookies', '/login'];
        if ($furls.indexOf(window.location.pathname) == -1){

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
                    
                    $(data.rEIds).each(function(i,e){
                        const $eveElmt = $(document).find(`.event[data-id="${e}"]`);
                        $eveElmt.remove();
                    })

                    $(data.rSIds).each(function(i,e){
                        const $stgElmt = $(document).find(`.stage-element[data-id="${e}"]`);
                        const $actElmt = $stgElmt.closest('.activity-component');
                        $actElmt.find('.stage-element').length == 1 ? $actElmt.closest('.activity-holder').remove() : $stgElmt.remove();
                    })

                    $(data.stages).each(function(i,s){
                        if(!s.asd){

                        } else {

                            $actElmt = $($actHolder.data('prototype'));
                            $actElmt.attr('data-id',s.aid).removeClass('dummy-activity').addClass(s.apr).addClass('tbd').find('.activity-component').attr({
                                'data-sd' : s.asd,
                                'data-p' : s.ap
                            }).find('.stage-element').attr({
                                'data-sd' : s.sd,
                                'data-p' : s.p,
                                'data-id' : s.id,
                                'data-name' : s.n
                            });
                            $actElmt.find('.act-info-name').append(s.an);
                            if(s.as && s.as == -1){
                                $actElmt.find('[href="#deleteActivity"]').remove();
                            } else {
                                $actElmt.find('[href="#deleteActivity"]').attr({
                                    'data-aid' : s.aid
                                })
                            }
                            $actElmt.find('[href="#updateStageProgressStatus"]').attr({
                                'data-eid' : s.aid,
                                'data-sid' : s.id,
                                'data-progress' : s.apr,
                            });
                            $actElmt.find('[href="#updateEvent"]').attr({
                                'data-aid' : s.aid,
                                'data-sid' : s.id,
                            });


                            $stageTooltip = $($actElmt.find('.stage-element').attr('data-tooltip'));

                            $stageTooltip.find('.t-stage-name').append(s.n);
                            $stageTooltip.find('.t-stage-dates span').append(s.ssed);
                            $tooltipPartHolder = $stageTooltip.find('.participants-t-holder');
                            if(!s.participants){
                                $noPartProto = $($tooltipPartHolder.data('prototype-no-participants'));
                                $tooltipPartHolder.append($noPartProto);
                            } else {
                                $.each(s.participants,function(_i,p){
                                    $partProto = $($tooltipPartHolder.data('prototype'));
                                    $partProto.find('.user-picture').attr('src', p.picture).next().text(p.fullname);
                                    $tooltipPartHolder.append($partProto);
                                })
                            }
                            
                            $actElmt.find('.stage-element').attr('data-tooltip',$stageTooltip.html());

                            $clientZone = $actElmt.find('.activity-clients');
                            if(!s.clients){
                                $clientZone.empty();
                            } else {
                                $.each(s.clients,function(i,c){
                                    $clientProto = $($clientZone.data('prototype'));
                                    $clientProto.attr('data-tooltip',c.name).find('.client-logo').attr('src',c.logo);
                                    $clientZone.append($clientProto);
                                })
                            }
                            $actElmt.find('.tooltipped').tooltip();
                            $actElmt.hide();
                            //$actHolder.find('.activity-list').prepend($actElmt);
                        }
                    });

                    $(data.events).each(function(i,e){
                        $evtElmt = $($actHolder.data('prototype-evt'));
                        $evtElmt.attr({
                            'data-id' : e.id,
                            'data-od' : e.od,
                            'data-p' : e.p,  
                        }).css('visibility','hidden');
                        
                        if(e.it.includes('fa')){
                            $evtElmt.find('.event-logo-container i').addClass(`fa fa-${e.in} evg-${e.gg}`);
                        } else {
                            $evtElmt.find('.event-logo-container i').addClass(`material-icons evg-${e.gg}`).append(e.in);
                        }
                        $eventTooltip = $($evtElmt.attr('data-tooltip'));
                        $eventTooltip.find('.evg').append(e.gt).addClass(`evg-${e.gn}`);
                        $eventTooltip.find('.t-od').append(new Date(e.od * 1000).toLocaleDateString(lg+'-'+lg.toUpperCase(),{ month: 'numeric', day: 'numeric' }));
                        if(e.rd != ""){
                            $eventTooltip.find('.t-rd').append(new Date(e.rd * 1000).toLocaleDateString(lg+'-'+lg.toUpperCase(),{ month: 'numeric', day: 'numeric' }));
                        } else {
                            $eventTooltip.find('.t-rd, .fa-calendar-check').remove();
                        }
                        if(e.it.includes('fa')){
                            $eventTooltip.find('i:not(.fa)').addClass(`fa fa-${e.in} evg-${e.gn}`);
                        } else {
                            $eventTooltip.find('i:not(.fa)').addClass(`material-icons evg-${e.gn}`).append(e.in);
                        }
                        $eventTooltip.find('.evt').append(e.tt);
                        if(!e.nbd){
                            $eventTooltip.find('.event-documents').remove();
                        } else {
                            $eventTooltip.find('.event-documents span').append(`${e.nbd} ${$eventTooltip.data('document')}${e.nbd > 1 ? 's' : ''}`)
                        }   
                        if(!e.nbc){
                            $eventTooltip.find('.event-comments').remove();
                        } else {
                            $eventTooltip.find('.event-comments span').append(`${e.nbc} ${$eventTooltip.data('comment')}${e.nbc > 1 ? 's' : ''}`)
                        }

                        $evtElmt.attr('data-tooltip',$eventTooltip.html()).tooltip();
                        $indivActHolder = $(document).find(`.stage-element[data-id="${e.sid}"]`).closest('.activity-holder');
                        $evtElmt.addClass('tbd');
                        $indivActHolder.find('.activity-component').append($evtElmt);
                    });


                    $(data.rUIds).each(function(i,e){
                        $(document).find(`.notif-elmt[data-id="${e}"]`).remove();
                    })


                    $(data.notifs).each(function(i,n){
                        $notifHolder.find('.no-update-msg').remove();
                        $notifElmt = $($notifHolder.data('prototype'));
                        $notifElmt.attr('data-id',n.id).find('.notif-user-picture').append(`<img class="user-profile-picture" src="/lib/img/user/${n.picture}">`);
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
    }

    interval = setInterval(function() {retrieveUpdates(window.location.pathname == '/myactivities')}, 15000);
    
    if(typeof caurl != "undefined"){
        newInterval = setInterval(function() {checkAccount()}, 60*60*1000);
    }

    if(typeof cnurl != "undefined"){
        newInterval = setTimeout(() => setInterval(function() {checkNotifMails()}, 20*60*1000) , 1*60*1000);
    }
    
    $(document).on('click',function(e){
        var $this = $(e.target);
        if(!$this.closest('.profile-notif-zone').length){
            $('.profile-notif-elmts').hide();
        } /*else if ($this.closest('.notif-zone').length) {
            $('.notif-list:visible') ? $('.notif-list').hide() : $('.notif-list').show();
        }*/
    });

    $('.user-notif-link').on('click',function(){

        if($(document).find('.profile-notif-elmts:visible').length){
            $(document).find('.profile-notif-elmts').hide();
            $(document).find('.notif-elmt:not(.seen)').addClass('seen');
        } else {
            $(document).find('.profile-notif-elmts').show(); 
        }

        if($(document).find('.nb-updates-new')){
            $.get(vuurl,null)
                .done(function(){
                    $('.nb-updates-new').remove();
                })
        }
    });

    $('.cookies-banner .btn').on('click', function(){
        $('.cookies-banner').remove();
        setCookie('cb',1,365);
    })


//});
