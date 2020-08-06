$(function() {

    $('.modal').modal();
    $('#addPosition').find('button').data('did',$('[id$="department"]').val());
    $('#addWeight').find('button').data('pid',$('[id$="position"]').val());

    if($(window).width() < 700){
        $('.action-buttons button').removeClass('btn-large').addClass('btn-flat white-text');
    }

    //At beginning, replace all options by relevant ones
    var pos = $('[id$="position"]').val();
    var wgt = $('[id$="user_weightIni"]').val();

    if($('[id$="type"]').val() == 'I'){
        $('[id$="type"]').closest('.container').find('[id$="orgId"]').attr('disabled',true).parent().hide().next().hide();
    }

    /*$('[id$="position"]').empty();
    urlToPieces = rpurl.split('/');
    urlToPieces[urlToPieces.length-2] =  $('[id$="department"]').val();
    rpurl = urlToPieces.join('/');
    $.post(rpurl,null)
        .done(function(data){
            positions = JSON.parse(data);
            $.each(positions, function(key,value){
                $('[id$="position"]').append('<option value="'+key+'">'+value+'</option>')
            });
            $('[id$="position"]').val(pos);
            $('[id$="user_weightIni"]').empty();
            urlToPieces = rwurl.split('/');
            urlToPieces[urlToPieces.length-2] =  $('[id$="position"]').val();
            rwurl = urlToPieces.join('/');
            $.post(rwurl,null)
                .done(function(data){
                    weights = JSON.parse(data);
                    $.each(weights, function(key,value){
                        $('[id$="user_weightIni"]').append('<option value="'+key+'">'+value+' ('+$('[id$="position"] option:selected').text()+')'+'</option>')
                    });
                    $('[id$="user_weightIni"]').val(wgt);
                })
                .fail(function (data) {
                    console.log(data)
                });

            })
        .fail(function (data) {
            console.log(data)
            });*/

    $(document).on('click','.add-orgGroup',function(){
        if($(this).hasClass('add-department')) {
            $('#addClient').find('button').data('selectedNb', $(this).closest('li').index());
        }
        });

    $(document).on('change','[id$="type"]',function(){
        if($(this).val() == 'I'){
            $(this).closest('.container').find('[id$="orgId"]').attr('disabled',true).parent().hide().next().hide();
        } else{
            $(this).closest('.container').find('[id$="orgId"]').removeAttr('disabled').parent().show().next().show();
        }
    });

    $('.client-submit').on('click',function(e){
        e.preventDefault();
        $.post(eorurl,$(this).closest('form').serialize())
            .done(function(data){
                $.each($('.red-text'),function(){
                    $(this).remove();
                });
                data = JSON.parse(data);
                if(data.hasOwnProperty("commname")){
                    $('#addClient').find('input[type="text"]').after('<div class="red-text"><strong>' + data.commonName + '</strong></div>');
                    return false;
                } else {
                    //$('[id$="department"]:eq('+$("#addDepartment").find("button").data("selectedNb")+')').append('<option value="'+data.dptId+'">'+data.dptName+'</option>');

                    $('[id$="orgId"]').append('<option value="'+data.orgId+'">'+data.orgName+'</option>');

                }
                if($('.red-text').length == 0) {
                    $('.modal').modal('close');

                    $('#addClientSuccess').modal('open');
                }
            })
            .fail(function (data) {
                console.log(data)
            });
    });



    $('.delete-button').on('click',function(e){
        e.preventDefault();
        $.ajax({
            url : deleteUrl,
            type : 'DELETE',
            success: function(){
                url = window.location.pathname;
                urlToPieces = url.split('/');
                urlToPieces[urlToPieces.length-2] = 'users';
                window.location = urlToPieces.slice(0,urlToPieces.length-1).join('/');
            },
            fail: function(data){
                console.log(data);
            },
        })
    });

    $('.user-submit').on('click',function(e) {
        e.preventDefault();
        $('.red-text').remove();
        $.post(document.location.href, $(this).closest('form').serialize())
            .done(function(data){
                url = window.location.pathname;
                urlToPieces = url.split('/');
                urlToPieces[urlToPieces.length-2] = 'users';
                window.location = urlToPieces.slice(0,urlToPieces.length-1).join('/');
            })
            .fail(function (err) {
                $.each(err.responseJSON, function(key, value){
                    $('form[name="user"]').find('input, select').each( function () {
                        if ($(this).attr('name').indexOf(key) != -1) {
                            $(this).after('<div class="red-text"><strong>' + value + '</strong></div>');
                            return false;
                        }
                    })
                })
                console.log(err)
            });
    });

    $('.department-submit').on('click',function(e){
        e.preventDefault();
        $.post(durl,$(this).closest('form').serialize())
            .done(function(data){

                $.each($('.red-text'),function(){
                    $(this).remove();
                });
                data = JSON.parse(data);
                if(data.hasOwnProperty("name")){
                    $('#addDepartment').find('input[type="text"]').after('<div class="red-text"><strong>' + data.name + '</strong></div>');
                    return false;
                } else {
                    $('[id$="department"]').append('<option value="'+data.dptId+'">'+data.dptName+'</option>');
                    $('[id$="department"]').val(data.dptId);
                    //$('[id$="position"]').empty();
                    //$('[id$="weightIni"]').empty();
                }
                if($('.red-text').length == 0) {
                    $('.modal').modal('close');
                    $('#addDepartmentSuccess').modal('open');
                }
            })
            .fail(function (data) {
                console.log(data)
            });
    });

    $('.position-submit').on('click',function(e){

        e.preventDefault();
        urlToPieces = purl.split('/');
        urlToPieces[urlToPieces.length-3] =  $('[id$="department"]').val();
        purl = urlToPieces.join('/');
        $.post(purl,$(this).closest('form').serialize())
            .done(function(data){

                $.each($('.red-text'),function(){
                    $(this).remove();
                });

                var data = JSON.parse(data);
                if(data.hasOwnProperty("name")){
                    $('#addPosition').find('input[type="text"]').after('<div class="red-text"><strong>' + data.name + '</strong></div>');
                    return false;
                } else {
                    $('[id$="position"]').append('<option value="'+data.posId+'">'+data.posName+'</option>')
                    $('[id$="position"]').val(data.posId);
                    //$('[id$="weightIni"]').empty();
                }

                if($('.red-text').length == 0){
                    $('.modal').modal('close');
                    $('#addPositionSuccess').modal('open');
                }

            })
            .fail(function (data) {
                console.log(data)
            });
    });

    $('.weight-submit').on('click',function(e){

        e.preventDefault();
        urlToPieces = wurl.split('/');
        urlToPieces[urlToPieces.length-4] =  $('[id$="position"]').val();
        curlToPieces = window.location.href.split('/');
        urlToPieces[urlToPieces.length-2] =  curlToPieces[curlToPieces.length-1];
        wurl = urlToPieces.join('/');
        $('.red-text').remove();
        $.post(wurl,$(this).closest('form').serialize())
            .done(function(data){
                var data = JSON.parse(data);
                $('[id$="user_weightIni"]').append('<option value="' + data.wgtId + '">' + data.wgtValue + '</option>');
                $('[id$="user_weightIni"]').val(data.wgtId);
                $('.modal').modal('close');
                $('#addWeightSuccess').modal('open');
            })
            .fail(function (err) {
                $('#addWeight').find('input[type="number"]').after('<div class="red-text"><strong>' + err.responseJSON.weight + '</strong></div>');
                console.log(err);
            });
    });

    /*
    $(document).on('change','[id$="department"]',function(){
        $('#addPosition').find('button').data('did',$(this).val());


            $('[id$="position"]').empty();
            urlToPieces = rpurl.split('/');
            urlToPieces[urlToPieces.length-2] =  $(this).val();
            rpurl = urlToPieces.join('/');
            $.post(rpurl,null)
                .done(function(data){
                    positions = JSON.parse(data);
                    $.each(positions, function(key,value){
                        $('[id$="position"]').append('<option value="'+key+'">'+value+'</option>')
                    })
                    $('#addWeight').find('button').data('pid',$('[id$="position"]').val());
                    if($('[id$="position"]').val() !== null){
                        $('[id$="user_weightIni"]').empty();
                        urlToPieces = rwurl.split('/');
                        urlToPieces[urlToPieces.length-2] =  $('[id$="position"]').val();
                        rwurl = urlToPieces.join('/');
                        $.post(rwurl,null)
                            .done(function(data){
                                weights = JSON.parse(data);
                                $.each(weights, function(key,value){
                                    $('[id$="user_weightIni"]').append('<option value="'+key+'">'+value+'</option>')
                                })

                            })
                            .fail(function (data) {
                                console.log(data)
                            });
                    } else {
                        $('[id$="user_weightIni"]').empty();
                    }

                })
                .fail(function (data) {
                    console.log(data)
                });



    });

    $(document).on('change','[id$="position"]',function(){
        $('#addWeight').find('button').data('pid',$(this).val());
        $('[id$="user_weightIni"]').empty();
        urlToPieces = rwurl.split('/');
        urlToPieces[urlToPieces.length-2] =  $(this).val();
        rwurl = urlToPieces.join('/');
        $.post(rwurl,null)
            .done(function(data){
                weights = JSON.parse(data);
                $.each(weights, function(key,value){
                    $('[id$="user_weightIni"]').append('<option value="'+key+'">'+value+'</option>')
                })

            })
            .fail(function (data) {
                console.log(data)
            });
    });*/


});
