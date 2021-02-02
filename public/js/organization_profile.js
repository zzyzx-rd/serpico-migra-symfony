
$(function(){

    var clickedOnce = false;
    $('.clickable-image, .add-photo-btn').on('click', e => {
        clickedOnce = false;
        if (!clickedOnce) {
            $('input[type="file"]').click();
        }
    });

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
            $('.user-picture').attr('src', `/lib/img/org/${data.filename}`);
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

    $('.organization-credentials input').on('keyup',function(){
        var isDiff = false;
        $('.organization-credentials input').each(function(i,e){
            if($(e).val() != $(e).attr('value')){
                isDiff = true;
                return false;
            }
        })
        isDiff ? $('.save-btn').removeClass('disabled-btn') : $('.save-btn').addClass('disabled-btn');
    })

    $('.save-btn').on('click',function(e){
        e.preventDefault();
        $.post(sourl,$(this).closest('form').serialize())
            .done(function(data){
                $('.organization-credentials .element-input').each(function(i,e){
                    if(!$(e).find('input').is('[type="radio"]')){
                        el = $(e).find('input').val();
                        if($(e).prev().text() != el){
                            $(e).prev().empty().append(el);
                        }
                    } else {
                        if($(e).find('input').is(':checked')){
                            if($(e).find('input[type="radio"]:checked + label').text() != $(e).prev().text()){
                                $(e).closest('.element-input').prev().empty().append($(e).find('input[type="radio"]:checked + label').text());
                            }
                        }
                    }
                })
                $('.element-data, .modify-credentials-btn').show();
                $('.element-input, .save-btn').hide();
                $('.cancel-btn').css('visibility','hidden');
            })
    })

})