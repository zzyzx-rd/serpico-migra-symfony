$(function(){

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

    $(document).on('keyup','input[name*="firm"]',function(event){
        var el = $(this);
        var index = $("input[name*='firm']").index($(this));
        var $selector = $('select[name="firmSelector"]').eq(index);
        $selectorMElmts = $selector.closest('.select-wrapper');

        if($(this).val().length >= 3 /*&& event.keyCode != 8*/){
            //urlToPieces = surl.split('/');
            //urlToPieces[urlToPieces.length - 1] = $(this).val();
            //surl = urlToPieces.join('/');
            const params = {name: $(this).val()};
            $.post(surl,params)
                .done(function(data){

                    if(!data.workerFirms.length){
                        $selectorMElmts.prev().find('input').removeAttr('value');
                        $selectorMElmts.hide();
                        return false;
                    }

                    $(`<select name="firmSelector"></select>`)
                    
                    $selector.closest('.select-wrapper').find('img').remove();
                    $selector.empty();
                    $.each(data.workerFirms,function(key,firm){
                        //$option = $(`<option class="flex-center" value=${firm.id}></option>`);
                        //$option.append(`<img class="firm-option-logo" src="/lib/img/org/${firm.logo ? firm.logo : 'no-picture.png'}">`)
                        //$option.append(`<span>${firm.name}</span>`);
                        $selector.append(`<option value="${firm.id}">${firm.name}</option`);
                    })
                    //el.val(selector.find(":selected").text());
                    el.attr("value",$selector.find(":selected").val());
                    $selector.material_select();
                    $selectorMElmts = $selector.closest('.select-wrapper')
                    $selector.prev().find('li').each(function(i,e){
                        $(e).prepend(`<img class="firm-option-logo" src="/lib/img/org/${data.workerFirms[i].logo ? data.workerFirms[i].logo : 'no-picture.png'}">`)
                        //$option.append(`<span>${firm.name}</span>`);
                        //selector.append($option);
                    });
                    //$selectorMElmts.prepend(`<img class="firm-input-logo" src="/lib/img/org/${data.workerFirms[0].logo ? data.workerFirms[0].logo : 'no-picture.png'}">`);


                    //$('select[name="firmSelector"]').eq(index).show();
                })
                .fail(function(data){
                   console.log(data);
                })
        } else {
            //if($selectorMElmts){
                $selectorMElmts.hide();
            //}
        }
    })

    /*$(document).on('click','.w-firm .dropdown-content li', function(){
        //var $this = $(this);
        var index = $('select[name="firmSelector"]').index($(this));
        $("input[name*='firm']").eq(index).val($(this).find(":selected").text());
        $("input[name*='firm']").eq(index).attr("value",$(this).val());
        $(this).hide();

    })*/

    /*$("#update_worker_firm_form_firmParent").on("input",function(){
        $('#parentSelector').hide();
    })*/


    $(document).on('change','select[name="firmSelector"]',function(){
        var index = $('select[name="firmSelector"]').index($(this));
        $("input[name*='firm']").eq(index).val($(this).find(":selected").text());
        $("input[name*='firm']").eq(index).attr("value",$(this).val());
        $(this).prev().css('visibility','hidden');
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

    $('.modify-experience-btn, .modify-btn').on('click',function(){
        $('.element-data, .modify-btn').hide();
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
        $('.element-data, .modify-btn').show();
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
                $('.element-data, .modify-btn').show();
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


});