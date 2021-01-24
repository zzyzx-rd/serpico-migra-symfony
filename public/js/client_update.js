$(function(){

    $(document).on('mouseover','.individual',function(){
        $(this).find('.user-action-btns').css('visibility','');
    }).on('mouseleave','.individual',function(){
        $(this).find('.user-action-btns').css('visibility','hidden');
    })

    $('.client-data-zone').on('mouseover',function(){
        $(this).find('.client-action-btns').css('visibility','');
    }).on('mouseleave',function(){
        $(this).find('.client-action-btns').css('visibility','hidden');
    })

    $('.remove-client').on('click',function(){
        urlToPieces = dcurl.split('/');
        id = $(this).data('id');
        urlToPieces[urlToPieces.length - 2] = id;
        dcurl = urlToPieces.join('/');
        $.delete(dcurl,null)
            .done(function(){
                location.href = $('.back-btn').attr('href');
            })
            .fail(function(data){
                console.log(data);
            })
    })

    $(document).on('click','.update-ext-user-btn',function(){
        const $userElmt = $(this).closest('.individual');
        const $modal = $('#updateClientUser');
        $modal.find('input').val("");
        $modal.find('.save-user-updates').attr('data-id',$userElmt.data('id'));
        $modal.find('.client-user-name').empty().append($userElmt.find('.user-ext-fullname').text());
        $modal.find('.client-profile-picture').attr('src',$userElmt.find('.client-profile-picture').attr('src'));
        
        $userElmt.find('.is-active').length ? $modal.find('.email-update').hide() : $modal.find('.email-update').show();
        
        $modal.find('input[name="email"]').val($userElmt.find('.is-active').data('tooltip'));
        if($userElmt.find('.user-ext-position-name').hasClass('has-position')){
            $modal.find('input[name="position"]').val($userElmt.find('.user-ext-position-name').text());
        }
        $modal.modal('open');
    })

    $('.change-username').on('click',function(){
        $('#updateClientUser .client-username-data').hide();
        $('.client-username-input input[name="eu-username"]').val($('#updateClientUser .client-user-name').text().trim())
        $('#updateClientUser .client-username-input').show();
    })

    $('.save-user-updates').on('click',function(){
        const $this = $(this);
        const $modal = $(this).closest('.modal');
        id = $this.data('id');
        const $userElmts = $(`.individual[data-id="${id}"]`);
        urlToPieces = ucuurl.split('/');
        urlToPieces[urlToPieces.length - 2] = id;
        url = urlToPieces.join('/');
        const isEmailFieldVisible = $modal.find('.email-update').is(':visible');
        const emailVal = $modal.find('input[name="email"]').val();
        const userNameVal = $modal.find('input[name="eu-username"]').val();
        const posNameVal = $modal.find('input[name="position"]').val();

        if(isEmailFieldVisible && emailVal != "" && !isEmail(emailVal)){
            $('.error-email').show();
            return false;
        } else {
            $('.error-email').hide();
        }

        $.post(url,$this.closest('form').serialize())
            .done(function(data){
                $('#updateClientUser .client-username-input').hide();
                $('#updateClientUser .client-username-data').show();
                
                $('#updateClientUser').modal('close');
                if(userNameVal != ""){
                    $userElmts.find('.user-ext-fullname').empty().append(userNameVal);
                }
                if(posNameVal != ""){
                    $userElmts.find('.user-ext-position-name').removeClass('grey-text text-lighten-1').addClass('has-position').empty().append(posNameVal);
                } else {
                    $userElmts.find('.user-ext-position-name').addClass('grey-text text-lighten-1').removeClass('has-position').empty().append($userElmts.find('.user-ext-position-name').data('wo'));
                }
                switch(data.status){
                    case 'a':
                        $badge = $(`<i class="fa fa-check-circle dd-text is-active tooltipped"></i>`);
                        break;
                    case 'nc':
                    case 'v':
                        $badge = $(`
                            <div class="inactive-user-badge flex tooltipped">
                                <span class="white-text">V</span>
                            </div>
                        `);
                        break;
                }
                $badge.attr('data-tooltip',data.msg).tooltip();
                $userElmts.find('.user-ext-email').empty().append($badge);
                if(data.status != 'v'){
                    $userElmts.find('.user-ext-fullname').removeClass('grey-text text-lighten-1');
                    if($userElmts.find('.client-profile-picture').attr('src').split('.')[0].split('/').slice(-1)[0] == 'virtual-user'){
                        srcToPieces = $userElmts.find('.client-profile-picture').attr('src').split('.')[0].split('/');
                        srcToPieces[srcToPieces.length - 1] = 'no-picture.png';
                        src = srcToPieces.join('/');
                        $userElmts.find('.client-profile-picture').attr('src',src);
                    }
                }
            })
    })

    $(document).on('click','[href="#deleteExternalUser"]',function(){
        $('.remove-client-user').data('id',$(this).closest('.individual').data('id'));
    });

    $(document).on('click','.remove-client-user',function(){
        id = $(this).data('id');
        urlToPieces = diurl.split('/');
        urlToPieces[urlToPieces.length - 2] = id;
        diurl = urlToPieces.join('/');
        $.delete(diurl,null)
            .done(function(){
                $(`.individual[data-id="${id}"]`).remove();
                if(!$('.individual').length){
                    location.reload();
                }
            })
            .fail(function(data){
                console.log(data);
            })
    })

});
