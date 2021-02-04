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
    /*
    $(document).on('click','.update-ext-user-btn',function(){
        const $userElmt = $(this).closest('.individual');
        const $modal = $('#updateUser');
        $modal.find('input').val("");
        $modal.find('.save-user-updates').attr('data-id',$userElmt.data('id'));
        $modal.find('.user-name').empty().append($userElmt.find('.user-ext-fullname').text());
        $modal.find('.profile-picture').attr('src',$userElmt.find('.profile-picture').attr('src'));
        
        $userElmt.find('.is-active').length ? $modal.find('.email-update').hide() : $modal.find('.email-update').show();
        
        $modal.find('input[name="email"]').val($userElmt.find('.is-active').data('tooltip'));
        if($userElmt.find('.user-ext-position-name').hasClass('has-position')){
            $modal.find('input[name="position"]').val($userElmt.find('.user-ext-position-name').text());
        }
        $modal.modal('open');
    })
    */

    $('.add-user-btn').on('click',function(){
        $('#addUserClient').attr('data-qt','eu');
        setTimeout(function(){
            $('#addUserClient').find('[class*="-part"]:visible').addClass('initial-part');
        },200);
    });

});
