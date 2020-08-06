$(function(){

    $('[href="#validateMail"]').on('click',function(){
        $('.validate-btn').data('mid',$(this).data('mid'));
    });

    $('[href="#deleteMail"]').on('click',function(){
        $('.delete-btn').data('mid',$(this).data('mid'));
    });

    $('[href="#deactivateMail"]').on('click',function(){
        $('.deactivate-btn').data('mid',$(this).data('mid'));
    });

    $('.validate-btn').on("click",function(e){
        e.preventDefault();
        urlToPieces = vmurl.split('/');
        urlToPieces[urlToPieces.length - 1] = $(this).data('mid');

        vmurl = urlToPieces.join('/');
        $.post(vmurl,null)
            .done(function(data){
                console.log(data);
                $("#validateMail").modal('close');
                $("#validateMailSuccess").modal('open');
            })
            .fail(function(data){
                console.log(data)
            })
    })

    $('.delete-btn').on("click",function(e){
        e.preventDefault();
        urlToPieces = rmurl.split('/');
        var mid = $(this).data('mid');
        urlToPieces[urlToPieces.length - 1] = mid;

        rmurl = urlToPieces.join('/');
        $.post(rmurl,null)
            .done(function(data){
                $("#deleteMail").modal('close');
                console.log(data);
                $('a[data-mid="'+ mid +'"]').closest('tr').remove();
            })
            .fail(function(data){
                console.log(data)
            })
    })

    $('.deactivate-btn').on("click",function(e){
        e.preventDefault();
        urlToPieces = dmurl.split('/');
        var mid = $(this).data('mid');
        urlToPieces[urlToPieces.length - 1] = mid;

        dmurl = urlToPieces.join('/');
        $.post(dmurl,null)
            .done(function(data){
                $("#deactivateMail").modal('close');
                $("#deactivateMailSuccess").modal('open');
                $('a[data-mid="'+ mid +'"]').closest('tr').addClass('orange lighten-3');
            })
            .fail(function(data){
                console.log(data)
            })
    })


});