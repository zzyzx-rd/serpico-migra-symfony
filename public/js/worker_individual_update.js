$(function(){

    $('select').material_select();
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('aa')){$('#addAccount').modal('open');}

    $(document).on('click','.modify-firm-btn',function(e){
        e.preventDefault();
        $(this).prev().attr("disabled",false);
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

    document.getElementById('add_picture_form_pictureFile').addEventListener('change', e => {
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
        $('.account-delete-name').empty().append($(this).closest('.account').find('.account-name').text());
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