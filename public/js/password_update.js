$(function() {

    $('#firstConnectionModal').modal({
        dismissible: false,
    });

    $('select').material_select();

    $('#definePwdSuccess').modal({
        dismissible: true,
        complete: function() {
            window.location = landingPageUrl;
        }
    });

    $('.fa-eye').on('mousedown', function() {  
        $pwdElmt = $(this).closest('.password-field').find('input');
        $pwdElmt.attr('type') == 'password' ? $pwdElmt.attr('type', 'text') : '';
    }).on('mouseup',function(){
        $pwdElmt = $(this).closest('.password-field').find('input');
        $pwdElmt.attr('type') == 'text' ? $pwdElmt.attr('type', 'password') : '';
    })

    $('input').on('cut copy paste selectstart drag drop', e => e.preventDefault());

    /*
    $('form').submit(function(e) {
        e.preventDefault();
        $('.red-text').empty();
        const params = {prior_check: 1};
        $.post(window.location.pathname, $(this).serialize())
        .done(function(data) {

            if(data.needToSetOrg){
                $('.set-usr-org-btn').attr('data-id',data.id);
                $('#firstConnectionModal').modal('open');
            } else {
                setTimeout(() => window.location = landingPageUrl, 2000);
            }
            
        })
        .fail(function(_data) {
            const data = _data.responseJSON;
            const errorMessages = data.password;
            for (const field in errorMessages) {
                if (!errorMessages.hasOwnProperty(field)) continue;
                $(`[name$="[password][${field}]"]`).closest('li').children('.errors').html(
                    errorMessages[field]
                );
            }
        });
    });
    */

    $('.update-pwd-btn').on('click',function(e){
        
        e.preventDefault();
        const params = {prior_check: 1};
        $.post(window.location.pathname, $(this).closest('form').serialize()  + '&' + $.param(params))
            .fail(function(_data) {
                const data = _data.responseJSON;
                /**
                 * @type {{}}
                 */
                const errorMessages = data.password;
                for (const field in errorMessages) {
                    if (!errorMessages.hasOwnProperty(field)) continue;
                    $(`[name$="[password][${field}]"]`).closest('li').children('.errors').html(
                        errorMessages[field]
                    );
                }
            })
            .done(function(data){
                $('.set-usr-org-btn').attr('data-id',data.id);
                if(data.needToSetOrg){
                    $('#firstConnectionModal').modal('open');
                } else {
                    setTimeout(() => window.location = landingPageUrl, 1000);
                }
            })
    })

    /*
    $(document).on('keyup','input[name*="firm"]',function(e){

        var $this = $(this);
        var $selector = $('[name="firmSelector"]');
        if($selector.find('option:selected').length && $this.val() != $selector.find('option:selected').text()){
            $inputZone = $this.parent();
            $inputZone.find('input').not($this).each(function(i,e){
            $(e).removeAttr('value');
            })
            $inputZone.find('.input-f-img').remove();
            $this.removeClass('part-feeded');
        }
        $selectorMElmts = $selector.closest('.select-wrapper');
    
        if($this.val().length >= 3){
            
            const params = {name: $this.val(), type: 'f'};
            $.post(surl,params)
                .done(function(data){
    
                    if(!data.qParts.length){
                        $this.removeAttr('value');
                        $selector.empty();
                        $selector.material_select();
                        //$selectorMElmts.hide();
                        return false;
                    }
                    
                    $selector.closest('.select-wrapper').find('img').remove();
                    $selector.empty();
                    $.each(data.qParts,function(key,el){
                        let elName = el.orgName;
                        let elPic = el.logo;
                        $selector.append(`<option value="${el.wfiId}" data-wid="${el.wfiId}" data-oid="${el.orgId ? el.orgId : ''}" data-cid="${el.cliId ? el.cliId : ''}" data-pic="${elPic ? elPic : ""}">${elName}</option`);
                    })
                    $selector.material_select();
                    $selectorMElmts = $selector.closest('.select-wrapper')
                    $selector.prev().find('li').each(function(i,e){
                        logo = $selector.find('option').eq(i).attr('data-pic');
                        folder = $selector.find('option').eq(i).attr('data-oid') ? 'org' : 'wf';
                        $(e).prepend(`<img class="s-firm-option-logo" src="/lib/img/${folder}/${logo ? logo : 'no-picture.png'}">`);
                        $(e).addClass('flex-center');
                    });
                    
                })
                .fail(function(data){
                    console.log(data);
                })
        } else {
            //if($selectorMElmts){
                $selectorMElmts.hide();
            //}
        }
    
        });

        $('[name*="firmSelector').on('change',function(){
        var $this = $(this);
        var $selectedOpt = $this.find(":selected");
        const $usrElmts = $this.closest('.user-inputs');
        $usrElmts.find('input[name="wid"]').val($selectedOpt.attr('data-wid'));
        $usrElmts.find('.firm-name').val($selectedOpt.text());
        var img = $this.prev().find('li').eq($this.find('option').index($this.find('option:selected'))).find('img');
        if(!$usrElmts.find('.input-f-img').length){
            var inputImg = img.clone();
            inputImg.attr('class','');
            inputImg.addClass('input-f-img');
            inputImg.css({
            'position': 'absolute',
            'left': '0.75rem',
            'top': '15%',
            'height': '30px',
            });
            $usrElmts.find('.firm-name').addClass('part-feeded');
            //inputElmt.css({'padding-left':'3rem!important'});
            $usrElmts.find('.firm-field-zone').append(inputImg);
        }
        if($this.val() != ""){
            $this.prev().attr('style','display:none!important');
        }
    });
    */

    $('.set-usr-org-btn').on('click',function(e){
        e.preventDefault();
        var $this = $(this);
        const pwdParams = {prior_check: 0};

        $.post(window.location.pathname, $('.update-pwd-btn').closest('form').serialize()  + '&' + $.param(pwdParams))
            .done(function(){
                var params = {id: $this.data('id'), assoc: !$('#noOrgAssoc').is(':checked') ? 1 : 0}
                $.post(suourl, $this.closest('form').serialize() + '&' + $.param(params))
                    .done(function(data){
                    $('#firstConnectionModal').modal('close');
                        setTimeout(() => window.location = landingPageUrl, 1000);
                    })
                    .fail(function(data){
                    console.log(data);
                    });
            })
    });
});