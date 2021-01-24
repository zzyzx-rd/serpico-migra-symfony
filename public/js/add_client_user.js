/*const { options } = require("dropzone");*/

$(function(){

    onActivityPage = window.location.href.indexOf('myactivities') > -1;
    onClientPage = window.location.href.indexOf('client') > -1;
    onAllUsersPage = window.location.href.indexOf('users-and-partners') > -1 || window.location.href.indexOf('colleagues-teams') > -1;
    onPwdPage = window.location.href.indexOf('password') > -1;
    $modal = $('#addUserClient');

    if(onClientPage){
        $('[href="#addUserClient"]').on('click',function(){
            $('.ext-org .icon-holder').attr('src',$('.client-logo-zone').attr('src'));
        })
    }

    
    $(document).on('click','.username-part .go-next:not(.go-to-email), .account-add-btn, .btn-add-user',function(){

        const $this = $(this);
        if($this.hasClass('btn-add-user') && $('.email-part input[name="email"]').val() != "" && !isEmail($('.email-part input[name="email"]').val())){
            $('.error-email').css('visibility','');
            return false;
        } else {
            $('.error-email').css('visibility','hidden');
        }

        if(onActivityPage){
            if($this.hasClass('.account-add-btn')){
                $partImg = $('[name="userSelector"]').prev().find('li').eq($('[name="userSelector"]').find('option').index('[name="userSelector"] option:selected')).find('.s-user-option-logo');
            } else {
                $partImg = $('.input-u-img') ? $('.input-u-img') : $('.input-f-img');
            }
            proto = $partHolder.data('prototype');
            if($stageModal.data('id')){
                $partElmt = $(proto);
                urlToPieces = apurl.split('/');
                urlToPieces[urlToPieces.length - 3] = $("#createStage").attr('data-id');
                url = urlToPieces.join('/');
                $.post(url,$(this).closest('form').serialize())
                .done(function(data){
                    //$partHolder = $modal.find('.participants-list');
                    $partElmt.find('img').attr('src', $partImg.attr('src'))
                    $partElmt.addClass('existing deletable').append(`<div class="p-delete-overlay modal-trigger flex-center" href="#deleteParticipant" style="display:none;" data-pid="${data.pid}"><i class="fa fa-trash"></i></div>`);
                    $partElmt.attr('data-tooltip',$('.username-part input[name="username"]').val()).tooltip();
                    $partHolder.find('.btn-participant-add').before($partElmt);
                    $('.nb-participants').empty().append(`(${$('.participant-btn').length})`);
                })
            } else {
                proto = proto.replace(/__name__/g, $partHolder.children().length - 1);
                $partElmt = $(proto);
                $partElmt.find('.selected-participant-logo').attr('src', $('[name*="userSelector"]').prev().find('li').eq($('[name*="userSelector"] option').index($('[name*="userSelector"] option:selected'))).find('img').attr('src'));
                $partElmt.attr('data-tooltip',$('[name*="userSelector"] option:selected').text()).tooltip();
                //visibleEl = $this.is('.account-add-btn') ? '.account-part' : '.username-part';
                $partElmt.find('.u').val($('.account-part input[name="iuid"]').val() ? $('.account-part input[name="iuid"]').val() : $('.username-part input[name="iuid"]').val());
                $partElmt.find('.eu').val($('.account-part input[name="iuid"]').val() ? $('.account-part input[name="euid"]').val() : $('.username-part input[name="euid"]').val());
                $partElmt.find('.t').val($('.account-part input[name="tid"]').val() ? $('.account-part input[name="tid"]').val() : $('.username-part input[name="tid"]').val());
                if($('.email-part input[name="email"]').val()){
                    $partElmt.find('.em').val($('.email-part input[name="email"]').val());
                }
                $partHolder.find('.btn-participant-add').before($partElmt);
                $('#addUserClient').find('input[name="email"], input[name="username"], input[name="firmname"]').val("");
                $('#addUserClient').find('.input-u-img, .input-f-img').remove();
                $('#addUserClient').find('label[for="firm"]').removeClass('active');
                if($this.is('.account-add-btn')){
                    $('.account-part').hide();
                    $('.username-part').show();
                }
            }
            $('#addUserClient').modal('close');

        } else if (onClientPage){

            addClientUser();

        } else if(onAllUsersPage && $this.hasClass('btn-add-user')){
            
            $isIndpt = $('input[name*="gen-type"][value="i"]').is(':checked');
            $isIndpt ? addClient() : addClientUser();

        }
        
    });
    



    $('input[name="gen-type"]').on("change",function(){
        $('.firmname-part, .username-part').find('small').css('visibility','hidden');
        if($('input[name="firmname"]').val() != ""){
            $('input[name="firmname"]').removeAttr('data-nf');
            searchDyn('firm'); 
        }
        if($('input[name="username"]').val() != ""){
            if($('input[name="gen-type"][type="f"').is(':checked')){
                $('input[name="username"]').removeAttr('data-nf');
                searchDyn('user'); 
            }
        }
        if($(this).val() == "f" || $(this).val() == "i"){
            $('.user-choices').css('visibility','hidden');
            $('input[name="user-type"]').prop({
                'checked':false,
                'disabled': true,
            });
        } else {
            $('input[name="user-type"]').prop('disabled',false);
            $('input[name="user-type"][value="ext"]').prop('checked',true);
            $('.user-choices').css('visibility','');
        }
    });
    
    $('.type-part .go-next').on('click',function(){
        if($('input[name="gen-type"][value="f"]').is(':checked') || $('input[name="user-type"][value="ext"]').is(':checked')){
            $('.firmname-part').show();
                if($('input[name="gen-type"][value="f"]').is(':checked')){
            $('.firm-btn').empty().text($('.firm-btn').data('btn-final'));
            } else {
                $('.firm-btn').empty().text($('.firm-btn').data('btn-interm'));
                $('.title .ext-org').hide();
                $('.title .int-org').hide();
                $('.title .independant-org').hide();
            }
        } else {
            $('.firm-btn').empty().text($('.firm-btn').data('btn-interm'));
            $('.title .ext-org').hide();
            if($('input[name="gen-type"][value="i"]').is(':checked')){
                $('.username-part .int-org').hide();
                $('.username-part .independant-org').show();
            } else {
                $('.username-part .int-org').show();
                $('.username-part .independant-org').hide();
            }
            $('.username-part').show();
        }
        $('.type-part').hide();
    })
    
    $('.firmname-part .go-next').on('click',function(){
        if($('input[name="user-type"][value="ext"]').is(':checked')){
            $('.firmname-part').hide();
            $('.username-part .ext-org').show();
            $('.username-part').show();
        } else {
            var type = $('input[name="gen-type"][value="i"]').is(':checked') ? 'i' : 'f';
            addClient();
        }
    });
    
    $('.go-prev').on('click',function(){
        if($(this).closest('.firmname-part').length){
            $appearingPart = $('.type-part');
            $disappearingPart = $('.firmname-part');
            
        } else if($(this).closest('.username-part').length){
            if(onActivityPage){
                $('#addUserClient').find('.input-u-img, .input-f-img').remove();
                $('#addUserClient').find('label[for="user-fullname"]').removeClass('active');
                $('#addUserClient')
                    .modal('close')
                    .find('input[name="username"]').removeClass('part-feeded').val("");
                    return false;
            } else {
                if($('input[name="user-type"][value="int"]').is(':checked') || $('input[name="gen-type"][value="i"]').is(':checked')){
                    $appearingPart = $('.type-part');
                } else {
                    $appearingPart = $('.firmname-part');
                }
                $disappearingPart = $('.username-part');
            }
            
        } else if($(this).closest('.email-part').length || $(this).closest('.account-part').length){

            if($(this).closest('.account-part').length){
                $('#addUserClient').find('.input-u-img, .input-f-img').remove();
                $('#addUserClient').find('label[for="user-fullname"]').removeClass('active');
                $('#addUserClient').find('input[name="username"]').removeClass('part-feeded').val("");
            }

            $appearingPart = $('.username-part');
            $disappearingPart = $(this).closest('.email-part').length ? $('.email-part') : $('.account-part');
        }
        $disappearingPart.hide();
        $appearingPart.show();
    })
    
    $('[name="username"]').on('keyup',function(){
        $('.error-fullname').css('visibility','hidden');
    })
    
    $('[name="email"]').on('keyup',function(){
        $('.error-email').css('visibility','hidden');
    })
    

    $(document).on('click','.go-to-email',function(){
        if($('[name="username"]').val().split(' ').length < 2){
        $('.error-fullname').css('visibility','');
        } else {
        $('.username-part').hide();
        $('.email-part').find('label[for="user-email"]').empty().append(`${$('.email-part').find('label[for="user-email"]').data('lval')} ${$('[name="username"]').val()}`);
        $('.email-part').show();
        }
    });
    
    var typingTimer;
    var doneTypingInterval = 600;
    
    $(document).on('keyup','input[name="firmname"], input[name="username"]',function(e){
        var $this = $(this);
        var element = $this.is('input[name="firmname"]') ? 'firm' : 'user';
        searchDyn(element); 
    });
    
    $('[name*="firmSelector"], [name*="userSelector"]').on('change',function(){    
        var $this = $(this);
        var element = $this.is('[name*="firmSelector"]') ? 'firm' : 
            $this.is('[name*="userSelector"]') ? 'user' : 'account';
        var isIndpt = $('[name="gen-type"][value="i"]').is(':checked');
        var $selectedOpt = $this.find(":selected");
        const $inputZone = $this.closest(`.${element}-field-zone`);

        if ($this.prev().is(':visible')){
            $this.prev().attr('style','display:none!important');
        }

        //if(onActivityPage){

            if(element == 'user'){

                if($selectedOpt.hasClass('multiple')){

                    $('.selected-user').empty().append($selectedOpt.text());

                    uIds = $relatedOpt.attr('data-uid') ? $relatedOpt.attr('data-uid').split(',') : [];
                    euIds = $relatedOpt.attr('data-euid') ? $relatedOpt.attr('data-euid').split(',') : [];
                    wIds = $relatedOpt.attr('data-wid') ? $relatedOpt.attr('data-wid').split(',') : [];
                    oIds = $relatedOpt.attr('data-oid') ? $relatedOpt.attr('data-oid').split(',') : [];
                    cIds = $relatedOpt.attr('data-cid') ? $relatedOpt.attr('data-cid').split(',') : [];
                    oLogos = $relatedOpt.attr('data-org-logo') ? $relatedOpt.attr('data-org-logo').split(',') : [];
                    oNames = $relatedOpt.attr('data-org-name') ? $relatedOpt.attr('data-org-name').split(',') : [];
                    wLogos = $relatedOpt.attr('data-wf-logo') ? $relatedOpt.attr('data-wf-logo').split(',') : [];
                    $selector = $('[name="partAccountSelector"]');
                    $selector.empty();
                    triggerFirstOrg = false;
                    $.each(uIds,function(i,e){
                        if(oNames[i] != '' && !triggerFirstOrg){
                            $accountSelectedOpt = true;
                            triggerFirstOrg = true;
                        } else {
                            $accountSelectedOpt = false;
                        }
                        $option = $(`<option value="${uIds[i]}" ${oNames[i] == '' ? 'class="private"' : ''} ${$accountSelectedOpt ? 'selected' :''}>${oNames[i] == '' ? privateMsg : oNames[i]}</option>`);
                        euIds ? $option.attr('data-euid',euIds[i]) : '';
                        wIds ? $option.attr('data-wid',wIds[i]) : ''; 
                        oIds ? $option.attr('data-oid',oIds[i]) : ''; 
                        cIds ? $option.attr('data-cid',cIds[i]) : ''; 
                        oLogos ? $option.attr('data-org-logo',oLogos[i]) : ''; 
                        wLogos ? $option.attr('data-wf-logo',wLogos[i]) : '';
                        if($accountSelectedOpt){
                            $('.account-part').find('input[name="iuid"]').val(uIds[i]);
                            $('.account-part').find('input[name="euid"]').val(euIds[i]);
                        }
                        $selector.append($option);
                    })
                    $selector.material_select();
                    $selector.prev().find('li').each(function(i,e){
                        $clonedImgElmt = $('[name="userSelector"]').prev().find('li').eq($('[name="userSelector"]').find('option').index($selectedOpt)).find('.org-img-holder').children().eq(i).clone();
                        $(e).prepend($clonedImgElmt).addClass('flex-center');
                    })
                    $('.username-part').hide();
                    $('.account-part').show();

                } else {

                    if(($selectedOpt.attr('data-uid') != "" || $selectedOpt.attr('data-euid') != "")){
                        if(onActivityPage){
                            $inputZone.find('input[name="iuid"]').val($selectedOpt.attr('data-uid'));
                            $inputZone.find('input[name="euid"]').val($selectedOpt.attr('data-euid'));
                            $inputZone.find('input[name="tid"]').val($selectedOpt.attr('data-tid'));
                        } else {
                            $inputZone.find('input[name="uid"]').val($selectedOpt.attr('data-uid'));
                        }
                        parseInt($selectedOpt.attr('data-em')) ? $('.username-part .go-next').removeClass('go-to-email') : $('.username-part .go-next').addClass('go-to-email');
                    }
                }
            }
        //}

        /*if(onClientPage){
            
        }*/

        if(element == 'firm' /*&& !$this.closest('#firstConnectionModal').length*/){
            $inputZone.find('input[name="wid"]').val($selectedOpt.attr('data-wid'));
            $inputZone.find('input[name="oid"]').val($selectedOpt.attr('data-oid'));
            $inputZone.find('input[name="cid"]').val($selectedOpt.attr('data-cid'));
        }
        if(isIndpt){
            $inputZone.find('input[name="ioid"]').val($selectedOpt.attr('data-oid'));
            $inputZone.find('input[name="icid"]').val($selectedOpt.attr('data-cid'));
        }

        if(!$selectedOpt.hasClass('multiple')){

            $inputZone.find(`input[name="${element}name"]`).val($selectedOpt.text());
            var images = $this.prev().find('li').eq($this.find('option').index($this.find('option:selected'))).find('img');
            var isUser = images.has('s-user-option-logo');
            if(!$inputZone.find('.input-f-img').length){
                $.each(images,function(_i,e){
                    $img = $(e);
                    var $inputImg = $img.clone();
                    $inputImg.attr('class','');
                    $inputImg.addClass($(e).hasClass('s-user-option-logo') ? 'input-u-img' : 'input-f-img');
                    $inputImg.css({
                    'position': 'absolute',
                    'top': '10%',
                    'height': '30px',
                    'border-radius': '50%',
                    })
                    if(onActivityPage && isUser && $(e).hasClass('s-firm-option-logo')){
                        $inputImg.css('right','0.75rem');
                    }
                    $inputZone.append($inputImg);
                });
                $inputZone.find(`input[name="${element}name"]`).addClass('part-feeded');
                //inputElmt.css({'padding-left':'3rem!important'});
                $(`.${element}name-part .go-next`).removeClass('disabled-btn');
            }
        
        }

    });

    $('[name="partAccountSelector"]').on('change',function(){
        var $this = $(this);
        var $selectedOpt = $this.find(":selected");
        $('.account-part').find('input[name="iuid"]').val($selectedOpt.attr('value'));
        $('.account-part').find('input[name="euid"]').val($selectedOpt.attr('data-euid'));
        $('.account-part').find('input[name="tid"]').val($selectedOpt.attr('data-tid'));
    })
    
    $(document).on('mouseover','.firms-list--item header',function(){
        $(this).find('.user-actions .firm-modify-btn').css('visibility','');
    }).on('mouseleave','.firms-list--item header',function(){
        $(this).find('.user-actions .firm-modify-btn').css('visibility','hidden');
    })

    $(document).on('mouseover','.client-individuals .users-list--item header',function(){
        $(this).find('.user-actions a').css('visibility','');
    }).on('mouseleave','.client-individuals .users-list--item header',function(){
        $(this).find('.user-actions a').css('visibility','hidden');
    })

    function addClient(){
        $.post(acurl,$('#userClientForm').serialize())
        .done(function(data){
            if(onAllUsersPage){
                $isIndpt = $('input[name*="gen-type"][value="i"]').is(':checked');
                $clientProto = $isIndpt ? $($('.client-individuals').data('prototype-client')) : $($('.client-employees').data('prototype-client'));
                if($isIndpt){

                    $clientProto.attr({
                        'data-id' : data.id,
                        'data-email' : $('.email-part input[name="email"]').val(),
                        'data-position' : ''
                    }).addClass('new-added-user');
                    nbIndivIndeps = parseInt($('.nb-individuals-indep').text());
                    if(!nbIndivIndeps){
                        $('.client-individuals .no-items-placeholder').empty();
                    }
                    $('.nb-individuals-indep').empty().text(nbIndivIndeps + 1);
                    $('.client-individuals').find('.user-list').append($userProto);
                    newClientPos = $('.new-added-user').offset();

                } else {
                    
                    $clientProto.attr('data-id',data.cid);
                    $clientProto.find('.account-logo').attr('src',$('.input-f-img').attr('src'));
                    $clientProto.find('.firm-name').append($('input[name="firmname"]').val());
                    var modifyLink = $clientProto.find('.firm-modify-btn').attr('href');
                    urlToPieces = modifyLink.split('/');
                    urlToPieces[urlToPieces.length - 1] = data.cid;
                    url = urlToPieces.join('/');
                    $clientProto.find('.firm-modify-btn').attr('href',url);
                    $userProto = $($('.client-employees').data('prototype-user'));
                    $userProto.find('.user-name').addClass('synth').append($userProto.find('.user-name').data('synth-msg'));
                    $userProto.find('.user-picture').attr('src',$userProto.find('.user-picture').data('synth-pic'));
                    $userProto.find('.virtual-badge').parent().remove();
                    $clientProto.find('.user-list').append($userProto);
                    $('.firms-list').append($clientProto);
                    nbFirms = parseInt($('.nb-client-firms').text());
                    if(!nbFirms){
                        $('.client-employees .no-items-placeholder').empty();
                    }
                    $('.nb-client-firms').empty().text(nbFirms + 1);
                    newClientPos = $(`.firms-list--item[data-id="${data.cid}"]`).offset();
                }
                $([document.documentElement, document.body]).animate({
                    scrollTop: newClientPos.top
                }, 700);
                $('#addUserClient').find('input[name="email"], input[name="username"]').val("");
                $('#addUserClient').find('.input-u-img, .input-f-img').remove();
                $('#addUserClient').find('label[for="firm"]').removeClass('active');
                $('#addUserClient').find('input[name="firmname"]').removeClass('part-feeded').val("");
                $('.firmname-part').hide();
                $('.type-part').show();
                $('#addUserClient').modal('close');
            }
        })
    }

    function addClientUser(){
        if(!onClientPage){
            urlToPieces = aeuurl.split('/');
            id = $('.firmname-part input[name="cid"]').val() == "" ? 0 : $('.firmname-part input[name="cid"]').val();
            urlToPieces[urlToPieces.length - 3] = id;
            url = urlToPieces.join('/');
            $userProto = $($('.client-employees').data('prototype-user'));
        } else {
            url = aeuurl;
            $userProto = $($('.individuals').data('prototype'));
        }
        un =  $('input[name="username"]').val();
        $.post(url,$('#userClientForm').serialize())
            .done(function(data){

                if(onAllUsersPage){
                    $userProto.addClass('new-added-user');
                    $userProto.find('.user-name').append(un);
                    if($('.email-part input[name="email"]').val() != ""){
                        $userProto.find('.virtual-badge').parent().remove();
                    }
                    $(`.firms-list--item[data-id="${id}"]`).find('.user-list').append($userProto);
                    newUserPos = $('.new-added-user').offset();
                } else if(onClientPage){
                    $('.no-user-holder').remove();
                    $userProto.find('.user-ext-fullname').append($modal.find('input[name="username"]').val())
                    $userProto.attr('data-id',data.id);
                    switch(data.status){
                        case 'a':
                            $badge = $(`<i class="fa fa-check-circle dd-text is-active tooltipped"></i>`);
                        case 'nc':
                        case 'v':
                            $badge = $(`
                                <div class="inactive-user-badge flex tooltipped">
                                    <span class="white-text">V</span>
                                </div>
                            `);
                    }
                    $badge.attr('data-tooltip',data.msg).tooltip();
                    $userProto.find('.user-ext-email').append($badge);   
                    $('.individuals').append($userProto);
                    newUserPos = $(`.individual[data-id=${data.id}]`).offset();
                }
                
                $([document.documentElement, document.body]).animate({
                    scrollTop: newUserPos.top
                }, 700);

                $('#addUserClient').find('input[name="email"], input[name="username"], input[name="firmname"]').val("");
                $('#addUserClient').find('.input-u-img, .input-f-img').remove();
                $('#addUserClient').find('label[for="firm"], label[for="user-fullname"]').removeClass('active');
                $('.email-part').hide();
                onAllUsersPage ? $('.type-part').show() : $('.username-part').show();
                $('#addUserClient').modal('close');
                //location.reload();
            })
            .fail(function(data){
                if(data.msg == 'existingUser'){
                    $('.error-email').empty().append(`{{ 'create_user.modal.error_duplicate'|trans }}`);
                    $('.error-email').css('visibility','');
                }
            })
    }

    /*
    function addUserClient(clientToCreate = userToCreate = false){
        $.post(acurl,$('#userClientForm').serialize())
        .done(function(data){
            if(userToCreate){
                urlToPieces = accurl.split('/');
                urlToPieces[urlToPieces.length - 3] = $('.firmname-part input[name="cid"]');
                url = urlToPieces.join('/');
                $.post(url, $('#userClientForm').serialize())
                    .done(function(){
                        location.reload();
                    })
            } else {
                location.reload();
            }
        })
        .fail(function(data){
            if(data.msg == 'existingUser'){
                $('.error-email').empty().append(`{{ 'create_user.modal.error_duplicate'|trans }}`);
                $('.error-email').css('visibility','');
            }
        })
    }
    */

    function searchDyn(element){
        clearTimeout(typingTimer);
        $inputElmt = element == 'firm' ? $('input[name="firmname"]') : $('input[name="username"]');
        var qt = onPwdPage ? 'wf' : (
            onActivityPage ? 'p' : (
                element == 'firm' ? 
                (
                    !onAllUsersPage ? 'f' : (
                        $('input[name*="gen-type"][value="f"]').is(':checked') ? 'nc' : 'c'
                    )
                ) : (
                    onClientPage ? 'eu' : (
                        $('input[name*="gen-type"][value="i"]').is(':checked') ? 'i' : (
                            $('input[name*="user-type"][value="ext"]').is(':checked') ? 'eu' : 'u'
                        )
                    )
                )
            )
        );
        
        var qid = onActivityPage ? $('#createStage').data('id') : (
            onClientPage ? 
                window.location.href.split('/')[window.location.href.split('/').length - 1] : (
                    $('.firmname-part input[name="cid"]').val() ? $('.firmname-part input[name="cid"]').val() : 0
                )
        )
        var $selector = $(`[name="${element}Selector"]`);

        if($inputElmt.val().length < Math.max(3, $inputElmt.attr('data-nf') ? $inputElmt.attr('data-nf').length : 0)){
            $(`.${element}name-part .new-${element}`).css('visibility','hidden');
        }

        if($inputElmt.val().length < 3){

            $selector.closest('.select-wrapper').hide();
            $(`.${element}name-part .go-next`).addClass('disabled-btn');

        } else {

            /*if($selector.find('option').length){
                $(`.${element}name-part .go-next`).addClass('disabled-btn');
            } else {
                if(qt != 'c'){
                    $(`.${element}name-part .go-next`).removeClass('disabled-btn');
                }
            }*/
        }

        if($inputElmt.attr('data-nf') && $inputElmt.attr('data-nf') != $inputElmt.val().slice(0,$inputElmt.attr('data-nf').length)){
            $inputElmt.removeAttr('data-nf');
            $(`.new-${element}`).css('visibility','hidden');
        }
        if($selector.find('option:selected').length && $inputElmt.val() != $selector.find('option:selected').text()){
            $inputZone = $inputElmt.parent();
            $inputZone.find('input').not($inputElmt).each(function(i,e){
                $(e).removeAttr('value');
            })
            $inputZone.find('.input-f-img').remove();
            $inputElmt.removeClass('part-feeded');
        }
    
        typingTimer = setTimeout(function(){
    
            if($inputElmt.val().length >= 3 && !$inputElmt.attr('data-nf')){
                const params = {name: $inputElmt.val(), qt: qt, qid: qid};
                $.post(surl,params)
                    .done(function(data){
    
                        if(!data.qParts.length){
                            $inputElmt.removeAttr('value').attr('data-nf',$inputElmt.val());
                            $selector.empty();
                            $selector.material_select();
                            if(qt != 'c'){
                                $(`.new-${element}`).css('visibility','');
                                $(`.${element}name-part .go-next`).removeClass('disabled-btn');
                            } else {
                                $('.unexisting-client').css('visibility','');
                            }
                            return false;
                        } else {
                            $(`.${element}name-part .go-next`).addClass('disabled-btn');
                        }
                        
                        $selector.closest('.select-wrapper').find('img').remove();
                        $selector.empty();
                        $.each(data.qParts,function(key,el){
                            let elName = el.username ? el.username : el.orgName;
                            var isOrg = el.orgName && !el.username;
                            let $option = $(`<option value="${isOrg ? 'wf' : 'gu'}-${isOrg ? el.wfiId : el.id}">${elName}</option>`);
                            let isDataArray = Array.isArray(el.usrId);
                            if(isDataArray && el.usrId.length > 1){
                                $option.addClass('multiple');
                            }
                            if(el.ex){
                                $option.prop('disabled',true);
                            }
                            if(el.synth){
                                if(isDataArray){
                                    if(!isArrayNull(el.synth)){
                                        $option.attr('data-synth',el.synth.join());
                                    } 
                                } else {
                                    $option.attr('data-synth',el.synth);
                                }
                            }
                            if(el.usrId){
                                if(isDataArray){
                                    if(!isArrayNull(el.usrId)){
                                        $option.attr('data-uid',el.usrId.join());
                                    } 
                                } else {
                                    $option.attr('data-uid',el.usrId);
                                }
                            }
                            if(el.extUsrId){
                                if(isDataArray){
                                    if(!isArrayNull(el.extUsrId)){
                                        $option.attr('data-euid',el.extUsrId.join());
                                    } 
                                } else {
                                    $option.attr('data-euid',el.extUsrId);
                                }
                            }
                            if(el.wfiId){
                                if(isDataArray){
                                    if(!isArrayNull(el.wfiId)){
                                        $option.attr('data-wid',el.wfiId.join());
                                    } 
                                } else {
                                    $option.attr('data-wid',el.wfiId);
                                }
                            }
                            if(el.orgId){
                                if(isDataArray){
                                    if(!isArrayNull(el.orgId)){
                                        $option.attr('data-oid',el.orgId.join());
                                    } 
                                } else {
                                    $option.attr('data-oid',el.orgId);
                                }
                            }
                            if(el.cliId){
                                if(isDataArray){
                                    if(!isArrayNull(el.cliId)){
                                        $option.attr('data-cid',el.cliId.join());
                                    } 
                                } else {
                                    $option.attr('data-cid',el.cliId);
                                }
                            }
                            if(el.orgName){
                                if(isDataArray){
                                    if(!isArrayNull(el.orgName)){
                                        $option.attr('data-org-name',el.orgName.join());
                                    } 
                                } else {
                                    $option.attr('data-org-name',el.orgName);
                                }
                            }
                            if(el.orgLogo){
                                if(isDataArray){
                                    if(!isArrayNull(el.orgLogo)){
                                        $option.attr('data-org-logo',el.orgLogo.join());
                                    } 
                                } else {
                                    $option.attr('data-org-logo',el.orgLogo);
                                }
                            }
                            if(el.hasEm){
                                if(isDataArray){
                                    if(!isArrayNull(el.hasEm)){
                                        $option.attr('data-em',el.hasEm.join());
                                    } 
                                } else {
                                    $option.attr('data-em',el.hasEm);
                                }
                            }  
                            if(!$option.attr('data-org-logo') && el.wfiLogo){
                                if(el.wfiLogo){
                                    if(isDataArray){
                                        if(!isArrayNull(el.wfiLogo)){
                                            $option.attr('data-wf-logo',el.wfiLogo.join());
                                        } 
                                    } else {
                                        $option.attr('data-wf-logo',el.wfiLogo);
                                    }
                                }
                            }
                            if(el.hasOwnProperty('usrPicture')){
                                $option.attr('data-usr-picture',el.usrPicture ? el.usrPicture : "");
                            }
                            $selector.append($option);
                        })
                        $selector.material_select();
                        //$selectorMElmts = $selector.closest('.select-wrapper')
                        $selector.prev().find('li').each(function(i,e){

                            $relatedOpt = $selector.find('option').eq(i);
                            if($relatedOpt.is(':disabled')){
                                $(e).find('span').append(`<small class="dd-orange-text sm-left">- ${alreadyExistingMsg}</small>`);
                            }
                            
                            if(element != 'firm'){
                                uIds = $relatedOpt.attr('data-uid').split(',');
                                euIds = $relatedOpt.attr('data-euid') ? $relatedOpt.attr('data-euid').split(',') : [];
                                hasEms = $relatedOpt.attr('data-em') ? $relatedOpt.attr('data-em').split(',') : [];
                            }
                            wIds = $relatedOpt.attr('data-wid') ? $relatedOpt.attr('data-wid').split(',') : null;
                            oIds = $relatedOpt.attr('data-oid') ? $relatedOpt.attr('data-oid').split(',') : null;
                            cIds = $relatedOpt.attr('data-cid') ? $relatedOpt.attr('data-cid').split(',') : null;
                            oLogos = $relatedOpt.attr('data-org-logo') ? $relatedOpt.attr('data-org-logo').split(',') : null;
                            oNames = $relatedOpt.attr('data-org-name') ? $relatedOpt.attr('data-org-name').split(',') : null;
                            wLogos = $relatedOpt.attr('data-wf-logo') ? $relatedOpt.attr('data-wf-logo').split(',') : null;
                            synth = $relatedOpt.attr('data-synth') ? $relatedOpt.attr('data-synth').split(',') : null;
                            
                            if(qt == 'p' || qt == 'eu' || qt == 'u' || qt == 'i'){
                                if(typeof $selector.find('option').eq(i).attr('data-usr-picture') != "undefined"){
                                    picture = $selector.find('option').eq(i).attr('data-usr-picture');
                                    $(e).prepend(`<img class="s-user-option-logo" src="/lib/img/user/${picture ? picture : 'no-picture.png'}">`);
                                }

                                $orgImagesHolder = $('<div class="org-img-holder flex-center"></div>');
                                $.each(uIds, function(j,u){
                                    hasOrgLogo = oLogos != null && oLogos[j] != null;
                                    hasWfLogo = wLogos != null && wLogos[j] != null;
                                    logo = hasOrgLogo ? oLogos[j] :
                                        hasWfLogo ? wLogos[j] : 'no-picture.png';
                                    folder = hasOrgLogo ? 'org' : 'wf';
                                    if(wIds && wIds[j].length){
                                        if(typeof $selector.find('option').eq(i).attr('data-usr-picture') != "undefined"){
                                            $orgImage = $(`<img class="s-firm-option-logo tooltipped" src="/lib/img/${folder}/${logo}" data-tooltip="${oNames[j]}" data-position="top">`).tooltip();
                                            $orgImagesHolder.append($orgImage);
                                        } else {
                                            $(e).prepend(`<img class="s-user-firm-option-logo" src="/lib/img/${folder}/${logo ? logo : 'no-picture.png'}">`);
                                        }
                                    } else {
                                        /*
                                        $orgImagesHolder.append(`
                                        <div class="icon-holder flex-center">
                                            <span class="independent-letter white-text">I</span>
                                        </div>
                                       `);
                                        */
                                       if(qt != 'i' && !(qt == 'p' && synth[j] == 1)){
                                           $orgImage = $('<img class="s-firm-option-logo tooltipped" src="/lib/img/user/no-picture-i.png" data-tooltip="Private account" data-position="top">').tooltip();
                                       } else {
                                        $orgImage = $(`
                                            <div class="icon-holder flex-center tooltipped" data-tooltip="${independentMsg}" data-position="top">
                                                <span class="independent-letter white-text">I</span>
                                            </div>
                                        `).tooltip();
                                       }
                                       $orgImagesHolder.append($orgImage);
                                    }
                                })
                                $(e).append($orgImagesHolder);
                            } else {
                                logo = oLogos != null ? oLogos[0] :
                                    wLogos != null ? wLogos[0] : 'no-picture.png';
                                folder = oIds ? 'org' : 'wf';
                                $(e).prepend(`<img class="s-firm-option-logo" src="/lib/img/${folder}/${logo}">`);
                            }                        
                            $(e).addClass('flex-center');
                        });
                        
                    })
                    .fail(function(data){
                        console.log(data);
                    })
            }
        },doneTypingInterval,$inputElmt,$selector);

        $(document).on('click',function(e){
            var $this = $(e.target);
            var element = $('.firmname-part').is(':visible') ? 'firm' : 'user';
            var $visibleSel = $(`.${element}name-part`).find('.dropdown-content:visible');    
            if($visibleSel.closest(`.${element}-field-zone`).length || $this.hasClass(`${element}-name`)) {
                if (!$this.hasClass(`${element}-name`) && !$this.is($visibleSel) && $visibleSel.length){
                    $visibleSel.attr('style','display:none!important');
                } else if($this.is(`input[name="${element}name"]`)){
                    $sel = $(`.${element}name-part`).find('.dropdown-content');
                    if($sel.find('li').length){
                        $sel.removeAttr('style');
                    }
                }
            }
        })
    }

    $('.change-username').on('click',function(){
        $('#updateClientUser .client-username-data').hide();
        $('.client-username-input input[name="eu-username"]').val($('#updateClientUser .client-user-name').text().trim())
        $('#updateClientUser .client-username-input').show();
    })
})