//$(function() {
    
    function md5cycle(x, k) {
        var a = x[0], b = x[1], c = x[2], d = x[3];
        
        a = ff(a, b, c, d, k[0], 7, -680876936);
        d = ff(d, a, b, c, k[1], 12, -389564586);
        c = ff(c, d, a, b, k[2], 17,  606105819);
        b = ff(b, c, d, a, k[3], 22, -1044525330);
        a = ff(a, b, c, d, k[4], 7, -176418897);
        d = ff(d, a, b, c, k[5], 12,  1200080426);
        c = ff(c, d, a, b, k[6], 17, -1473231341);
        b = ff(b, c, d, a, k[7], 22, -45705983);
        a = ff(a, b, c, d, k[8], 7,  1770035416);
        d = ff(d, a, b, c, k[9], 12, -1958414417);
        c = ff(c, d, a, b, k[10], 17, -42063);
        b = ff(b, c, d, a, k[11], 22, -1990404162);
        a = ff(a, b, c, d, k[12], 7,  1804603682);
        d = ff(d, a, b, c, k[13], 12, -40341101);
        c = ff(c, d, a, b, k[14], 17, -1502002290);
        b = ff(b, c, d, a, k[15], 22,  1236535329);
        
        a = gg(a, b, c, d, k[1], 5, -165796510);
        d = gg(d, a, b, c, k[6], 9, -1069501632);
        c = gg(c, d, a, b, k[11], 14,  643717713);
        b = gg(b, c, d, a, k[0], 20, -373897302);
        a = gg(a, b, c, d, k[5], 5, -701558691);
        d = gg(d, a, b, c, k[10], 9,  38016083);
        c = gg(c, d, a, b, k[15], 14, -660478335);
        b = gg(b, c, d, a, k[4], 20, -405537848);
        a = gg(a, b, c, d, k[9], 5,  568446438);
        d = gg(d, a, b, c, k[14], 9, -1019803690);
        c = gg(c, d, a, b, k[3], 14, -187363961);
        b = gg(b, c, d, a, k[8], 20,  1163531501);
        a = gg(a, b, c, d, k[13], 5, -1444681467);
        d = gg(d, a, b, c, k[2], 9, -51403784);
        c = gg(c, d, a, b, k[7], 14,  1735328473);
        b = gg(b, c, d, a, k[12], 20, -1926607734);
        
        a = hh(a, b, c, d, k[5], 4, -378558);
        d = hh(d, a, b, c, k[8], 11, -2022574463);
        c = hh(c, d, a, b, k[11], 16,  1839030562);
        b = hh(b, c, d, a, k[14], 23, -35309556);
        a = hh(a, b, c, d, k[1], 4, -1530992060);
        d = hh(d, a, b, c, k[4], 11,  1272893353);
        c = hh(c, d, a, b, k[7], 16, -155497632);
        b = hh(b, c, d, a, k[10], 23, -1094730640);
        a = hh(a, b, c, d, k[13], 4,  681279174);
        d = hh(d, a, b, c, k[0], 11, -358537222);
        c = hh(c, d, a, b, k[3], 16, -722521979);
        b = hh(b, c, d, a, k[6], 23,  76029189);
        a = hh(a, b, c, d, k[9], 4, -640364487);
        d = hh(d, a, b, c, k[12], 11, -421815835);
        c = hh(c, d, a, b, k[15], 16,  530742520);
        b = hh(b, c, d, a, k[2], 23, -995338651);
        
        a = ii(a, b, c, d, k[0], 6, -198630844);
        d = ii(d, a, b, c, k[7], 10,  1126891415);
        c = ii(c, d, a, b, k[14], 15, -1416354905);
        b = ii(b, c, d, a, k[5], 21, -57434055);
        a = ii(a, b, c, d, k[12], 6,  1700485571);
        d = ii(d, a, b, c, k[3], 10, -1894986606);
        c = ii(c, d, a, b, k[10], 15, -1051523);
        b = ii(b, c, d, a, k[1], 21, -2054922799);
        a = ii(a, b, c, d, k[8], 6,  1873313359);
        d = ii(d, a, b, c, k[15], 10, -30611744);
        c = ii(c, d, a, b, k[6], 15, -1560198380);
        b = ii(b, c, d, a, k[13], 21,  1309151649);
        a = ii(a, b, c, d, k[4], 6, -145523070);
        d = ii(d, a, b, c, k[11], 10, -1120210379);
        c = ii(c, d, a, b, k[2], 15,  718787259);
        b = ii(b, c, d, a, k[9], 21, -343485551);
        
        x[0] = add32(a, x[0]);
        x[1] = add32(b, x[1]);
        x[2] = add32(c, x[2]);
        x[3] = add32(d, x[3]);
        
        }
        
        function cmn(q, a, b, x, s, t) {
        a = add32(add32(a, q), add32(x, t));
        return add32((a << s) | (a >>> (32 - s)), b);
        }
        
        function ff(a, b, c, d, x, s, t) {
        return cmn((b & c) | ((~b) & d), a, b, x, s, t);
        }
        
        function gg(a, b, c, d, x, s, t) {
        return cmn((b & d) | (c & (~d)), a, b, x, s, t);
        }
        
        function hh(a, b, c, d, x, s, t) {
        return cmn(b ^ c ^ d, a, b, x, s, t);
        }
        
        function ii(a, b, c, d, x, s, t) {
        return cmn(c ^ (b | (~d)), a, b, x, s, t);
        }
        
        function md51(s) {
        txt = '';
        var n = s.length,
        state = [1732584193, -271733879, -1732584194, 271733878], i;
        for (i=64; i<=s.length; i+=64) {
        md5cycle(state, md5blk(s.substring(i-64, i)));
        }
        s = s.substring(i-64);
        var tail = [0,0,0,0, 0,0,0,0, 0,0,0,0, 0,0,0,0];
        for (i=0; i<s.length; i++)
        tail[i>>2] |= s.charCodeAt(i) << ((i%4) << 3);
        tail[i>>2] |= 0x80 << ((i%4) << 3);
        if (i > 55) {
        md5cycle(state, tail);
        for (i=0; i<16; i++) tail[i] = 0;
        }
        tail[14] = n*8;
        md5cycle(state, tail);
        return state;
        }
        
        /* there needs to be support for Unicode here,
         * unless we pretend that we can redefine the MD-5
         * algorithm for multi-byte characters (perhaps
         * by adding every four 16-bit characters and
         * shortening the sum to 32 bits). Otherwise
         * I suggest performing MD-5 as if every character
         * was two bytes--e.g., 0040 0025 = @%--but then
         * how will an ordinary MD-5 sum be matched?
         * There is no way to standardize text to something
         * like UTF-8 before transformation; speed cost is
         * utterly prohibitive. The JavaScript standard
         * itself needs to look at this: it should start
         * providing access to strings as preformed UTF-8
         * 8-bit unsigned value arrays.
         */
        function md5blk(s) { /* I figured global was faster.   */
        var md5blks = [], i; /* Andy King said do it this way. */
        for (i=0; i<64; i+=4) {
        md5blks[i>>2] = s.charCodeAt(i)
        + (s.charCodeAt(i+1) << 8)
        + (s.charCodeAt(i+2) << 16)
        + (s.charCodeAt(i+3) << 24);
        }
        return md5blks;
        }
        
        var hex_chr = '0123456789abcdef'.split('');
        
        function rhex(n)
        {
        var s='', j=0;
        for(; j<4; j++)
        s += hex_chr[(n >> (j * 8 + 4)) & 0x0F]
        + hex_chr[(n >> (j * 8)) & 0x0F];
        return s;
        }
        
        function hex(x) {
        for (var i=0; i<x.length; i++)
        x[i] = rhex(x[i]);
        return x.join('');
        }
        
        function md5(s) {
        return hex(md51(s));
        }
        
        /* this function is much faster,
        so if possible we use it. Some IEs
        are the only ones I know of that
        need the idiotic second function,
        generated by an if clause.  */
        
        function add32(a, b) {
        return (a + b) & 0xFFFFFFFF;
        }
        
        if (md5('hello') != '5d41402abc4b2a76b9719d911017c592') {
            function add32(x, y) {
            var lsw = (x & 0xFFFF) + (y & 0xFFFF),
            msw = (x >> 16) + (y >> 16) + (lsw >> 16);
            return (msw << 16) | (lsw & 0xFFFF);
        }
    }

    function rand(min, max) {
        var min = min || 0,
            max = max || Number.MAX_SAFE_INTEGER;
      
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    $('.modal').modal();

    //const isMac = /macintosh/i.test(navigator.userAgent);

    //if (isMac) {
    $(document).on('mousedown', '.dp-start, .dp-end, .dp-gstart, .dp-gend', e => {
        setTimeout(() => {
            const $elmt = $(e.currentTarget);
            setTimeout(() => {
            $elmt.click();
            $elmt.focus();
            }, 100);
        }, 20);
    });


    $(document).on('mousedown', '.select-dropdown', e => {
    const $selectDropdown = $(e.currentTarget);
        setTimeout(function () {
            if (!$selectDropdown.hasClass('active')) {
            $selectDropdown.click();
            }
        }, 400);
    });
    //}

    var $upgradeModal = $('#upgradeAccount');
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
                    $('#waitingSpinner').css('z-index',9998).modal("open");
                },1300)
            } else if (k==2) {
                displaySpinner_2 = setTimeout(function(){
                    if($('.spinner-layer').length == 0){
                        $("#waitingSpinner .spinninAround").append(spinner);
                    }
                    $('#waitingSpinner').css('z-index',9999).modal("open");
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

    Array.prototype.unique = function() {
        return this.filter(function (value, index, self) { 
            return self.indexOf(value) === index;
        });
    }

    function isArrayNull(inputArray) {
        for (var i = 0, len = inputArray.length; i < len; i += 1)
          if (inputArray[i] !== null)
            return false;
        return true;
      }

    function isEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    function setCookie(key, value, expiry) {
        var expires = new Date();
        expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
        document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
    }

    function setMCookie(key, value, expiry) {
        var date = new Date();
        date.setTime(date.getTime() + ( 1000 * 60 * 60 * 24 * parseInt(expiry)));
        
        parts = getCookie(key);
        if (!parts || parts == 'undefined') {
            setCookie(key, value, expiry);
            return;
        }
        
        parts = parts.split(",");
        if (parts.length > 0) {
            // search same item and delete
            // if already have , delete it
            for (var i=0; i<parts.length; i++) {
                if (value == parts[i]) {
                    parts.splice(i, 1);
                }
            }
            parts.push(value);
        
            // if over 50, just do 50
            newparts = [];
            if (parts.length > 50) {
                for (var i=parts.length - 50; i<parts.length; i++) {
                    newparts.push(parts[i]);
                }
            }
            else {
                newparts = parts;
            }
            setCookie(key, newparts.join(","), expiry);
        }
        else {
            setCookie(key, $(this).attr("id"), expiry);
        }
    };

    function eraseMCookie(key, value, expiry){
        parts = getCookie(key);
        if (!parts || parts == 'undefined') {
            return;
        }
        parts = parts.split(",");
        if(parts.length == 1){
            eraseCookie(key);
        }
        // delete searched value
        for (var i=0; i<parts.length; i++) {
            if (value == parts[i]) {
                parts.splice(i, 1);
                setCookie(key, parts.join(","), expiry);    
                return;
            }
        }
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

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
    }

    function generateToken() {
        return Math.floor(1000000000000000 + Math.random() * 9000000000000000)
              .toString(36).substr(0, 15)
    }

    function isEmpty(obj) {
        for(var key in obj) {
            if(obj.hasOwnProperty(key))
                return false;
        }
        return true;
    }

    function copyToClipboard(txtToCopy) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(txtToCopy).select();
        document.execCommand("copy");
        $temp.remove();
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

    function getTimeZoneOffset(date, timeZone) {
        // Abuse the Intl API to get a local ISO 8601 string for a given time zone.
        let iso = date.toLocaleString('en-CA', { timeZone, hour12: false }).replace(', ', 'T');
      
        // Include the milliseconds from the original timestamp
        iso += '.' + date.getMilliseconds().toString().padStart(3, '0');
      
        // Lie to the Date object constructor that it's a UTC time.
        const lie = new Date(iso + 'Z');
      
        // Return the difference in timestamps, as minutes
        // Positive values are West of GMT, opposite of ISO 8601
        // this matches the output of `Date.getTimeZoneOffset`
        return -(lie - date) / 60 / 1000;
    }

    function checkPlan(){
        if(!$('#upgradeAccount').length || !$('#upgradeAccount .modal-content').length){
            window.location.href = '/logout';
        }
        const $furls = ['/','/pricing','/terms-and-conditions', '/terms/conditions/cookies', '/login'];
        if ($furls.indexOf(window.location.pathname) == -1){
            $.get(cpurl,null)
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
        const $furls = ['/','/pricing','/terms-and-conditions', '/terms/conditions/cookies', '/login','/password/define/'];
        console.log(window.location.pathname);
        var url = window.location.pathname.includes("/password/define/") ? false : true;
        url = window.location.pathname.includes("/user/email/confirm/") ? false : true;
        if (($furls.indexOf(window.location.pathname) == -1) && url){

            $unvisitedNotifier = $(document).find('.nb-updates-new');
            $notifHolder = $(document).find('.notif-list');
            newUIds = [];
            existingUIds = [];
            $notifHolder.find('.notif-elmt').each(function(_i,e){
                $(e).hasClass('seen') ? existingUIds.push($(e).data(('id'))) : newUIds.push($(e).data(('id')));
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
            $params = {newUIds: newUIds, existingUIds: existingUIds.unique(), md: modifyDashboard};
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
                        
                        if($('.no-activity-overlay:visible').length){
                            location.reload();
                        }

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
                            $actHolder.find('.activity-list').prepend($actElmt);
                        }
                    });

                    $(data.events).each(function(i,e){
                        $(`.e-selectable[data-id="${e.id}"]`).remove();
                        $evtElmt = $($actHolder.data('prototype-evt'));
                        $evtElmt.attr({
                            'data-id' : e.id,
                            'data-od' : e.od,
                            'data-p' : e.p,  
                        }).css('visibility','hidden');
                    
                        $evtElmt.find('.event-logo-container i').addClass(`${e.it.includes('fa') ? 'fa fa-' : 'mi '}${e.in} evg-${e.gg}`);
                        
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
                        $notifHolder.prepend($notifElmt);
                    })
                    
                    var nbNewNotifs = data.nbNew;
                    if(nbExistingNewNotifs){
                        nbExistingNewNotifs + nbNewNotifs == 0 ? $('.nb-updates-new').remove() : $unvisitedNotifier.empty().append(nbExistingNewNotifs + nbNewNotifs);
                    } else {
                        if(nbNewNotifs > 0){
                            $('nav .user-nav-picture').closest('a').append(`<span class="nb-updates-new">${nbNewNotifs}</span>`);
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
    
    if(typeof cpurl != "undefined"){
        newInterval = setInterval(function() {checkPlan()}, 60*60*1000);
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

    $('.change-account').on('click',function(){
        $.get(gaurl)
        .done(function(data){
            if(data.changed){
                location.reload();
            } else {
                const $accountSelector = $('[name="accountSelector"]');
                $.each(data,function(i,e){
                    $accountSelector.append(`<option value="${e.id}">${e.name}</option>`);
                })
                $accountSelector.material_select();
                $('#changeAccount').modal('open');
            }
        })
    })

    $('.select-account-btn').on('click',function(){
        $.post(caurl,{id: $('[name="accountSelector"]').val()})
            .done(() => location.reload());
    })

    $('[name="super_admin_choice"]').on('change',function(){
        if($('#otherSU').is(':checked')){
            $('#addUserClient').attr('data-qt','iu');
            $('#addUserClient').find('.username-part .go-prev, .username-part .go-next').addClass('go-to-sam');
            $('#addUserClient').find('.username-part .title').find('.independent-org, .ext-org, .int-org').hide();
            $('#addUserClient').find('.username-part .title').find('.super-admin').show();
            $('#addUserClient').find('.username-part').show();
            $('#addUserClient').find('.type-part').hide();
            $('#addUserClient').modal({dismissible:false})
            $('#addUserClient').css('z-index', +$(this).closest('.modal').css('z-index') + 10).modal('open');
            $('.modal-overlay.velocity-animating').css('z-index', +$(this).closest('.modal').css('z-index') + 9);
        } else {
            $('.chosen-su-user').remove();
            $('#defineSuperAdmin').find('input[name="uid"]').val($('#defineSuperAdmin').data('id'));
        }
    })

    $(document).on('click','.go-prev.go-to-sam, .go-next.go-to-sam',function(){
        const $this = $(this);
        var $modal = $this.closest('.modal');
        if($this.hasClass('go-prev')){
            $('#selfSU').prop('checked',true);
        } else {
            $('#defineSuperAdmin').find('input[name="uid"]').val($modal.find('input[name="uid"]').val());
            isThereSUHolder = $('.chosen-su-user').length;
            $suHolder = isThereSUHolder ? $('.chosen-su-user') : $(`
                <div class="flex-center chosen-su-user">
                    <span class="su-name m-left"></span>
                    <i class="mi create dd-text change-su-user" style="display:none"></i>
                </div>
            `);
            $suHolder.find('.input-u-img').remove();
            $suHolder.find('.su-name').empty().append($modal.find('input[name="username"]').val());
            $suPic = $modal.find('.input-u-img').clone();
            $suPic.css('position','');
            $suHolder.prepend($suPic);
            if(!isThereSUHolder){
                $('.su-choice-zone').append($suHolder);
            }
        } 
            
        $modal.modal('close');
    })

    $(document).on('click','.add-su-btn',function(){
        $.post(usuurl,$('#defineSuperAdmin form').serialize())
            .done(function(data){
                if(!data.self){
                    $('#defineSuperAdmin').modal('close');
                    $('#superAdminAppointmentSuccess').modal({dismissible:false}).modal('open');
                } else {
                    window.location.href = data.path;
                }
            })
    });

    $(document).on('mouseover','.chosen-su-user',function(){
        $('.change-su-user').show();
    }).on('mouseleave','.chosen-su-user',function(){
        $('.change-su-user').hide();
    })

    $(document).on('click','.change-su-user',function(){
        $('#addUserClient').find('.username-part').show();
        $('#addUserClient').modal('open');
    });



//});
