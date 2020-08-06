$(function(){

    $("#update_worker_firm_form_firmParent").each(function(){
        var firmParentId = $(this).val();
        if(firmParentId.length > 0){
            urlToPieces = gurl.split('/');
            urlToPieces[urlToPieces.length - 1] = firmParentId;
            gurl = urlToPieces.join('/');
            $.post(gurl,null)
                .done(function(data){
                    $(this).val(data.firmName);
                    $(this).attr("value",firmParentId);
                })
                .fail(function(data){
                   console.log(data);
                })
        }
    })

    $("#update_worker_firm_form_firmParent").on("keyup",function(event){
        if($(this).val().length >= 3 && event.keyCode != 8){
            urlToPieces = surl.split('/');
            urlToPieces[urlToPieces.length - 1] = $(this).val();
            surl = urlToPieces.join('/');
            $.post(surl,null)
                .done(function(data){
                    $('#parentSelector').empty();
                    $.each(data.workerFirms,function(key,value){
                        $('#parentSelector').append('<option value="'+value+'">'+key+'</option>');
                    })
                    $("#update_worker_firm_form_firmParent").val($('#parentSelector option:selected').text());
                    $("#update_worker_firm_form_firmParent").attr("value",$('#parentSelector option:selected').val());
                    $('#parentSelector').show();
                })
                .fail(function(data){
                   console.log(data);
                })
        }
    })

    /*$("#update_worker_firm_form_firmParent").on("input",function(){
        $('#parentSelector').hide();
    })*/


    $('#parentSelector').on('change',function(){
        $("#update_worker_firm_form_firmParent").val($('#parentSelector option:selected').text());
        $("#update_worker_firm_form_firmParent").attr("value",$(this).val());
        $('#parentSelector').hide();
    });

    $('form').on("submit",function(e){
        $("#update_worker_firm_form_firmParent").val($("#update_worker_firm_form_firmParent").attr("value"));
    })


});