$(function() {
    //$('.collapsible').collapsible();
    $('.tooltipped').tooltip();
    $('.modal').modal();

    $('#defineSuperior').modal({
        dismissible: true,
        complete: function() {
            let selectUid = $('.define-sup-btn').data('uid');
            let optionNb = $('.define-sup-btn').data('optionNb');
            let userName = $('.missing-superior-badge[data-uid="180"]').parent().text().trim().toLowerCase();
            $("#superiorSelect").find('option').eq(optionNb-1).after($('<option value="'+$(this).data('uid')+'"]>'+userName+'</option>'));
        } 
      }
    );

    $('#defineSuperiorSuccess').modal({
        dismissible: true,
        complete: function() { location.reload();} 
      }
    );

    $('.define-sup-btn').on('click',function(e){
        e.preventDefault();
        var urlToPieces = dsurl.split('/');
        urlToPieces[urlToPieces.length-3] = $(this).data('uid');
        urlToPieces[urlToPieces.length-1] = $("#superiorSelect").val();
        dsurl = urlToPieces.join('/');
        $.post(dsurl,null)
            .done(function(){
                $('#defineSuperiorSuccess').modal('open');
            })
            .fail(function(error){
                alert("error");
            })
    })

    $('.collapsible-header i').css('margin', '0');

    $('[href="#defineSuperior"]').on('click',function(){
        $(".define-sup-btn").data('uid', $(this).data('uid'));
        $(".define-sup-btn").data('optionNb', $('#superiorSelect option').index($('#superiorSelect option[value="'+$(this).data('uid')+'"]'))); 
        $('#superiorSelect option[value="'+$(this).data('uid')+'"]').remove();
    });

    /*$('.cancel-btn,define-sup-btn').on('click',function(){
        let selectUid = $('.define-sup-btn').data('uid');
        let optionNb = $('.define-sup-btn').data('optionNb');
        let userName = $('.missing-superior-badge[data-uid="180"]').parent().text().trim();
        $("#superiorSelect").find('option').eq(optionNb-1).after($('<option value="'+$(this).data('uid')+'"]>'+userName+'</option>'));
    });*/


    $('.delete-button').on('click',function(e){
        var urlToPieces = ddurl.split('/');
        var did = $("#deleteDepartment").data('did');
        urlToPieces[urlToPieces.length-2] = did;
        var url = urlToPieces.join('/');

        $.post(url,null)
            .done(function(data){
                $('[data-did='+did+']').closest('ul').remove();
                data.message = 'success';
            })
            .fail(function (data){
                console.log(data)
            })
    });
    // Make user info more visible
    if( $(window).width() < 700){
        $('.user-single-element').css({'padding-left' : '0px', 'padding-right' : '4px'});
        $('#ListButtons .btn-large').empty();
        for(var k = 0;k < Math.floor($('#ListButtons .btn-large').length / 2);k++){
            $('#ListButtons .btn-large').eq(2*k).append('<i class="fa fa-edit"></i>');
            $('#ListButtons .btn-large').eq(2*k+1).append(/*'<i class="fa fa-search"></i>'*/'<img src="https://image.flaticon.com/icons/svg/123/123377.svg" width="20" height="20" alt="Binoculars free icon" title="Binoculars free icon">').addClass('blue lighten-2').css({'display' : 'flex', 'align-items' : 'center'});
        }
        $('.user-content').each(function(){
            $(this).find('li').eq(2).hide();
        });

        $('.user-stats').css({'flex-direction' : 'column'});
        //$('.user-stats .flex-center').css('flex-direction', 'column');
        //$('.user-stats .flex-center li:last').css('font-size', '10px');
        $('.evaluated-activities').removeAttr('style');
        $('.spread/*, .evaluated-activities, .fa-line-chart*/').hide();
        $('i:not(.fa-star)').css('font-size','1.3rem');
        //$('i').css('font-size','10px');
    } else {
        $('.user-content').css('justify-content','space-between');
        $('.fixed-action-btn a').css({'height':'60px', 'width':'60px'});
        $('.btn-floating i').css({'line-height':'60px', 'font-size':'2.5rem'});
    }

});
