$(function(){

    $('select').material_select();
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('aa')){$('#addAccount').modal('open');}
    

    $("input[name*='firm']").each(function(){
        var el = $(this);
        var firmParentId = el.val();
        if(firmParentId.length > 0){
            urlToPieces = gurl.split('/');
            urlToPieces[urlToPieces.length - 1] = firmParentId;
            gurl = urlToPieces.join('/');
            $.post(gurl,null)
                .done(function(data){
                    el.val(data.firmName);
                    el.attr("value",firmParentId).attr("disabled",true);
                    el.css("width","50%");
                    el.after($('<a class="waves-effect waves-light btn modify-firm-btn" href="" style="margin-left:20px"><i class="fa fa-pencil-alt"></i></a>'));
                })
                .fail(function(data){
                   console.log(data);
                })
        }
    })

    $(document).on('click','.modify-firm-btn',function(e){
        e.preventDefault();
        $(this).prev().attr("disabled",false);
    })

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
            
            const params = {name: $this.val(), type:'firm'};
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

    $('form[name="update_worker_individual_form"]').on("submit",function(e){
        $("input[name*='firm']").each(function(){
            $(this).attr("disabled",false);
            $(this).val($(this).attr("value"));
        })
    })

    $('.mail-submit').on("click",function(e){
        e.preventDefault();
        urlToPieces = smurl.split('/');
        urlToPieces[urlToPieces.length - 5] = $('select[name*="language"] option:selected').text().toLowerCase();
        urlToPieces[urlToPieces.length - 1] = $("#writeMailModal").data("wid");

        smurl = urlToPieces.join('/');
        $.post(smurl,$('form[name="send_mail_prospect_form"]').serialize())
            .done(function(data){
                console.log(data)
            })
            .fail(function(data){
                console.log(data)
            })
    })

    $collectionHolder = $('ul.experiences');

    /*
    if($collectionHolder.find('.experience').length == 0){
        addExperienceForm(); 
    }
    */

    $('.insert-experience-btn').on('click',function(){
        addExperienceForm();
    })

    function addExperienceForm() {
        var prototype =  $('ul.experiences').data('prototype');
        var newForm = prototype
            .replace(/__name__/g, $('.experience').length + 1)
            .replace(/__nb__/g, $('.experience').length + 1);
        var $newFormLi = $(newForm);        
        $newFormLi.find('.collapsible').collapsible();
        $newFormLi.find('.tooltipped').tooltip(); 
        $('.insert-experience-btn').before($newFormLi);

    }

    var clickedOnce = false;
    $('.clickable-image, .add-photo-btn').on('click', e => {
        clickedOnce = false;
        if (!clickedOnce) {
            $('input').click();
        }
    });

    document.getElementById('add_user_picture_form_pictureFile').addEventListener('change', e => {
        /** @type {HTMLInputElement} */
        const fileInput = e.target;
        const files = fileInput.files;
        const file = files[0];
        const types = [ 'image/png', 'image/bmp', 'image/jpeg' ];

        if (!file) {
            return;
        }
        if (!types.includes(file.type)) {
            $('#errorFileType').modal('open');
            return;
        }
        if (file.size > 1024000) {
            $('#errorFileSize').modal('open');
            return;
        }

        const data = new FormData();
        data.append('profile-pic', file);

        $.ajax({
            url: upurl,
            method: 'post',
            contentType: false,
            cache: false,
            processData: false,
            data
        })
        .done(data => {
            $('#successUploadedPicture').modal('open');
            $('.user-picture').attr('src', `/lib/img/user/${data.filename}`);
            $('.add-photo-btn').html('<i class="fa fa-pen"></i>');
        })
        .fail(err => console.log(err));
    });

    $('.modify-experience-btn, .modify-credentials-btn').on('click',function(){
        $('.element-data, .modify-credentials-btn').hide();
        $('.element-input, .save-btn').show();
        $('.cancel-btn').css('visibility','');
    });

    $('.validate-experience-btn, .validate-profile-info-btn').on('click',function(){
        $(this).closest('.element-input').prev().show().next().hide();
    });

    $('.cancel-btn').on('click',function(){
        $('.user-credential input').each(function(i,e){
            $(e).val($(e).attr('value'));
        })
        $('.element-data, .modify-credentials-btn').show();
        $('.element-input, .save-btn').hide();
        $('.cancel-btn').css('visibility','hidden');
    })

    $('.user-credentials input').on('keyup',function(){
        var isDiff = false;
        $('.user-credentials input').each(function(i,e){
            if($(e).val() != $(e).attr('value')){
                isDiff = true;
                return false;
            }
        })
        isDiff ? $('.save-btn').removeClass('disabled-btn') : $('.save-btn').addClass('disabled-btn');
    })

    $('.save-btn').on('click',function(e){
        e.preventDefault();
        $.post(suurl,$(this).closest('form').serialize())
            .done(function(data){
                $('.user-credentials .element-input').each(function(i,e){
                    if($(e).find('input').length > 1){
                        el = '';
                        $(e).find('input').each(function(i,f){
                            el = el.concat(' ',$(f).val());
                        })
                        el.trim();
                    } else if($(e).find('input').length == 1) {
                        el = $(e).find('input').val();
                    }
                    if($(e).prev().text() != el){
                        $(e).prev().empty().append(el);
                    }
                })
                $('.element-data, .modify-credentials-btn').show();
                $('.element-input, .save-btn').hide();
                $('.cancel-btn').css('visibility','hidden');
            })
    })

    $('.pwd-modify-btn').on('click',function(e){
        e.preventDefault();
        $('#modifyPassword .red-text').remove();
        $.post(pmurl,$(this).closest('form').serialize())
            .done(function(data){
                $('#modifyPassword').modal('close');
                $('#successPasswordChange').modal('open');
            })
            .fail(function(data){
                $.each(data.responseJSON, function(key, value){
                    $.each($('#modifyPassword input'),function(){
                        if($(this).attr('name').indexOf(key) != -1){
                            $(this).after('<div class="red-text"><strong>'+value+'</strong></div>');
                            return false;
                        }
                    });
                })
            })
    })

    $('.set-usr-org-btn').on('click',function(e){
        e.preventDefault();
        var $this = $(this);
        $.post(suourl, $this.closest('form').serialize())
            .done(function(data){
            $('#addAccount').modal('close');
            location.reload();
            //setTimeout(() => window.location = landingPageUrl, 1000);
            })
            .fail(function(data){
            console.log(data);
            });
    });

    $('.firm-accounts li').on('mouseover',function(){
        $(this).find('.delete-account-btn').css('visibility','');
    }).on('mouseleave',function(){
        $(this).find('.delete-account-btn').css('visibility','hidden');
    })

    $('.delete-account-btn').on('click',function(){
        $('.account-delete-name').empty().append($(this).closest('.account').find('.account-name'));
        $('#deleteAccount .delete-btn').attr('data-id',$(this).data('id'));
    });

    $('#deleteAccount .delete-btn').on('click',function(){
        const id = $(this).data('id');
        const params = {id: id}
        $.post(daurl,params)
            .done(function(){
                $(`.delete-account-btn[data-id="${id}"]`).closest('.account').remove();
                $('#deleteAccount').modal('close');
            })
        
    });




});