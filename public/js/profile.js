$(function() {
    var clickedOnce = false;
    $('.clickable-image, .add-photo-btn').on('click', e => {
        clickedOnce = false;
        if (!clickedOnce) {
            $('input').click();
        }
    });

  $('.modal').modal();

  if($('#tutoModal')) {
    setTimeout(function () {
      $('#tutoModal').modal('open');
      $('#tutoModal .modal-content ul').css('display', 'inline-block').addClass('no-margin');
    }, 200)
  }

  id = 0;

  $(document).find('.page-modal').each(function () {
    $(this).css('display', 'none');
  })

  $('.page-modal').first().show();

  $(document).find('.btn-previous-page').on('click', function () {
    currentPageModal = $('.page-modal')[id];
    $(currentPageModal).hide();
    id = id-1;
    pagemodal = $('.page-modal')[id];
    $(pagemodal).show();
    $(document).find('.roger').hide();
    if(id == 0 ){
      $(this).hide();
    }
    $(document).find('.btn-next-page').show();
    console.log(id);
  })

  $(document).find('.btn-next-page').on('click', function () {
    currentPageModal = $('.page-modal')[id];
    $(currentPageModal).hide();
    id = id+1;
    pagemodal = $('.page-modal')[id];
    $(pagemodal).show();
    if(id == $('.page-modal').length-1 ){
      $(this).hide();
      $(document).find('.btn-previous-page').show();
      $(document).find('.roger').show();
    }
    $(document).find('.btn-previous-page').show();
    console.log(id);
  })

    $('.dropify').dropify({
        messages:
            (lg == 'fr') ? {
            'default': 'Insérer une photo de profil (max. 2 Mo)',
            'replace': 'Glissez un document ou cliquez pour changer la photo',
            'remove': 'Supprimer',
            'error': 'Ooops, une erreur est survenue'
            } : {
            'default': 'Click or drag and drop an image file (max. 2 Mo)',
            'replace': 'Drag and drop or click to replace',
            'remove': 'Remove',
            'error': 'Ooops, something wrong happened'
                }
    });

    $('.dropify').on('change',function(e) {
        if ($('.dropify-clear').length > 0) {
            $('form button').attr('disabled',false);
            $('.dropify-preview').css('display','flex');
        } else {
            $('.dropify-preview').css('display','none');
        }
    });

    if ($(window).width() < 800) {
        $('.participant-column').empty().append($('<i class="fa fa-users"></i>'));
        var resElmt = $('.background-container a');
        $('.background-container a').remove()
        $('.data-container > ul').append(resElmt.removeAttr('style').css('width','100%'));
    }

    if ($(window).width() < 700) {
        var scrollTop = $(window).scrollTop();
        $('.team-results-btn .fa-chart-bar').css('margin','0px');
        $('.team-results-btn span').hide();
        $('.container > ul').removeClass('flex-center').addClass('row').removeAttr('justify-content').css('top','70px');
        $('.container > ul > div').removeAttr('style').css('text-align','center');
        $('.stats-elmts').css('justify-content','center');
        $('.col').css('margin-top','5px').css('margin-bottom','5px');
        $('.menu').css('padding','250px 0px');
        $('.container .row').css('margin-left','0px').css('margin-right','0px');
        $('.action-buttons').removeAttr('style');
        $('h5').css({'font-size':'1.28rem','text-align':'center'});
        $('table').css('font-size','0.85rem');
        $('.fa-paper-plane').css('margin-right','5px');
        $('.fa-paper-plane').closest('a').css({'font-size':'0.9rem','padding-left':'1rem','padding-right':'1rem'})
            .removeClass('btn-large').addClass('btn-flat');
        $('.participant-column').empty().append('<i class="fa fa-users"></i>');
        $('.deadline-column').empty().append('<i class="far fa-clock"></i>');
        $('.criterion-column').empty().append('<i class="fa fa-cube"></i>');
        $('.contribution').empty().append('<i class="fa fa-pie-chart"></i>');
        $('.evaluation').empty().append('<i class="fas fa-tachometer-alt"></i>');
        $('.pure-feedback').empty().append('<i class="fas fa-comment-dots"></i>');

        $('header').bind('cssClassChanged',function() {
            if ($(this).hasClass('sticky')) {
                $('.menu').css('padding','250px 0px 310px 0px');
            } else {
                $('.menu').css('padding','250px 0px');
            }
        })
        $('.btn-floating').css({'top':'30%','left':'44%'});
    }


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
            url: document.location.href,
            method: 'post',
            contentType: false,
            cache: false,
            processData: false,
            data
        })
        .done(data => {
            $('#successUploadedPicture').modal('open');
            $('.user-profile-picture').attr('src', `/lib/img/${data.filename}`);
            $('.add-photo-btn').html('<i class="fa fa-pencil-alt"></i>');
        })
        .fail(err => console.log(err));
    });

    $('#tutoModal').modal({
        dismissible: false,
    });

    $('#other_admin').on('change',function(){
        if($(this).is(':checked')){
            if(!$('#userSelector option').length){
                $('#addAdmin').css('z-index',2000);
                $('#addAdmin').modal('open');
            }
            $('#userSelector option').eq(0).prop('selected',true);
            $('#userSelector').material_select();
            $('.admin-select-zone').show();
        } else {
            $('.admin-select-zone').hide();
        }
    })

    $('#addAdmin .modal-close').on('click',function(){
        if(!$('#userSelector option').length){
            $('.admin-select-zone').hide();
        }
    })

    $('[href="#addAdmin"]').on('click',function(){
        $('#addAdmin').css('z-index',2000);
    })

    $('.admin-submit').on('click',function(e){
        e.preventDefault();
        $('.red-text').remove();
        const $btn = $(this);
        $.post(iaurl, $btn.closest('form').serialize())
            .done(function(data){
                $('#addAdmin').modal('close');
                $('#userSelector').append(
                    `<option value=${data.id}>${data.fullname}</option>`
                ).material_select();
            })
            .fail(function(data){
                errorHtmlMsg = '';
                Object.keys(data.responseJSON).forEach(function(key){
                    $btn.closest('.modal').find(`.modal-content input[name*="${key}"]`).after(`<strong class="red-text">${data.responseJSON[key]}</strong>`);
                })
            })

    })

    $('.roger').on('click',function(e){
        e.preventDefault();
        $('.red-text').remove();
        $btn = $(this);
        activeOpts = [];
        $MOpts = $('#userSelector').prev().find('li');
        const selectedAdmins = {users: []};
        if($('.admin-select-zone').is(':visible')){
            $('#userSelector').prev().find('li.active').each((i,e) => activeOpts.push($MOpts.index($(e))));
            Object.keys(activeOpts).forEach(function(index){
                selectedUser = {id: $('#userSelector option').eq(activeOpts[index]).val()};
                selectedAdmins.users.push(selectedUser);
            });
        }
        
        if($('#self_admin').is(':checked')){
            selectedAdmins.users.push({id: $('#self_admin').data('id')});
        }

        $.post(vaurl,selectedAdmins)
            .done(function(data){
                console.log(data);
                location.reload();
            })
            .fail(function(data){
                errorHtmlMsg = '';
                Object.keys(data.responseJSON).forEach(function(key){
                    $btn.closest('.modal').find('.modal-content').append(`<strong class="red-text">${data.responseJSON[key]}</strong>`);
                });
                
            })

    })

});
