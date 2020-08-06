$(function() {

    $('.modal').modal();
    
    $('[href="#deleteUser"]').on('click',function(){
        $('#deleteUser').find('.udelete-button').data('uid',$(this).data('uid'));
    });

    $('.udelete-button').on('click',function(e){
        e.preventDefault();
        var id = $(this).data("uid");
        var removableElmt = $('[data-uid="'+ id +'"]').closest('.user');
        var urlToPieces = duurl.split('/');
        urlToPieces[urlToPieces.length-1] = id;
        duurl = urlToPieces.join('/');
        $.ajax({
            url : duurl,
            type : 'DELETE',
            success: function(){
                removableElmt.remove();
            },
            fail: function(data){
                console.log(data);
            },
        })
    });

    $('[href="#deleteActivity"]').on('click',function(){
        $('#deleteActivity').find('.adelete-button').data('aid',$(this).data('aid'));
    });

    $('.adelete-button').on('click',function(e){
        e.preventDefault();
        var id = $(this).data("aid");
        var removableElmt = $('[data-aid="'+ id +'"]').closest('.activity');
        var urlToPieces = daurl.split('/');
        urlToPieces[urlToPieces.length-1] = id;
        daurl = urlToPieces.join('/');
        $.ajax({
            url : daurl,
            type : 'DELETE',
            success: function(){
                removableElmt.remove();
            },
            fail: function(data){
                console.log(data);
            },
        })
    });

    $('.odelete-button').on('click',function(e){
        urlToPieces = dourl.split('/');
        urlToPieces[urlToPieces.length-2] =  $(this).data('oid');
        dourl = urlToPieces.join('/');
        $.post(dourl,null)
            .done(function(data){
                window.location = ourl;
            })
            .fail(function (data) {
                console.log(data)
            });
    })
});
