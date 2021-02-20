$(function(){

    $('.admin-rights').on('mouseover',function(){
        $('.user-section-act-btns').css('visibility','');
    }).on('mouseleave',function(){
        $('.user-section-act-btns').css('visibility','hidden');
    })

    $(document).on('mouseover','.firm-users li',function(){
        $(this).find('[href="#updateUserRole"]').css('visibility','');
    }).on('mouseleave','.firm-users li',function(){
        $(this).find('[href="#updateUserRole"]').css('visibility','hidden');
    })

    $('.update-user-role-btn, .new-super-admin-btn').on('click',function(){
      const r = $('#roleSelector').val();
      const id = $(this).data('id');
        if(r == 1 && !$('#newSuperAdminWarning').is(':visible')){
            $('.new-super-admin-btn').attr('data-id',id);
            $('#newSuperAdminWarning').modal('open');
            return false;
        }

      const params = {id: id, r: r}
      $.post(uururl, params)
        .done(function(data){
            if(typeof data.cp !== "undefined"){
                window.location.href = location.origin + '/myactivities';
            }
            if(r > 2){
              $(`[href="#updateUserRole"][data-id="${id}"]`).closest('.account').remove();
              var nbAdmins = +$('.admin-section-title').find('.nb-users').text();
              $('.admin-section-title').find('.nb-users').empty().append(nbAdmins - 1);
            }

            $('.modal').modal('close');
          //$(`.delete-user-btn[data-id="${uid}"]`).closest('.account').remove();
        })
    });

    $('[href="#updateUserRole"]').on('click',function(){

        const $this = $(this);
        usrRole = $this.closest('.account').data('r');
        $('.user-fullname-value').empty().append($(this).closest('.account').find('.user-name').text());
        $('.update-user-role-btn').attr('data-id',$(this).data('id'));
        $(`#roleSelector option[value="${usrRole}"]`).prop('disabled',true);
        $('#roleSelector').material_select();
    })

    $('[href="#addUserClient"]').on('click',function(){

        $('#addUserClient').attr('data-qt','iua');
    });

})