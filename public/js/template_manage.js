$(function() {

    //$('select').parent().hide();
    $('.validators').hide();

    if ($(window).width() < 700){

        $('.user-picture').css('max-width','initial');

        $('.desktop-dates').hide();
        $('.btn-large').removeClass('btn-large').addClass('btn-flat').addClass('white-text');
        $('.activity-banner').css({'max-width':'85%','font-size':'16px'}).removeClass('activity-banner');
        //$('.activity-title').css('max-width','85%');
        $('.activity-description').css({'font-size':'11px','padding-top':'0px'});
        $('.c-align>h4').replaceWith(function(){return '<h5>'+$(this).text()+'</h5>'});

        if($('.tab').length > 2){
            $('.activity-status').hide();
        }
        $('.collection-item').css('padding','0px');

    } else {
        $('.mobile-dates').hide();
        $('.fixed-action-btn a').css({'height':'60px', 'width':'60px'});
        $('.btn-floating i').css({'line-height':'60px', 'font-size':'2.5rem'});
    }

    $(window).resize(function() {
        (window.innerWidth > 800) ? $('.activity-status').show() : (($('.tab').length > 2) ? $('.activity-status').hide() : $('.activity-status').show());
    });

    $('[href="#deleteTemplate"]').on('click',function(){
        $('#deleteTemplate').find('.delete-button').data('tid',$(this).data('tid'));
    });


    // Activity validation mgt
    $('#validateModal .modal-content').show();
    $('#validateModal select+label, #validateModal select').hide();
    $('#validateModal [name="delegate_activity_form[ownCreation]"]').change(function(){
        ($(this).is(':checked')) ? ($('#validateModal select+label, #validateModal select').hide(), $('#validateModal select').attr('disabled',true)) : ($('#validateModal select+label, #validateModal select').show(), $('#validateModal select').removeAttr('disabled'));
    });



    $(".expandMore").on("click", (function (){
        $(this).find("#expand").html("<i id=\"expand\" class=\"material-icons\">expand_less</i>")
        })
    );


    $(".modal").modal();
    $(".tooltipped").tooltip();

    $('[name="request_activity_form[requestType]"]').change(function(){
        ($(this).val() == 2) ? $('.validators').removeAttr('disabled').show() : $('.validators').attr('disabled',true).hide() ;
    });

    // Modal button to delete either stage/activity
    $('.delete-button,.adelete-button').on('click',function(e){

        var id = $(this).data("tid");
        var clickedBtn = $('[data-tid="'+ id +'"]');
        var removableElmt = clickedBtn.closest('.collapsible');
        var urlToPieces = deleteUrl.split('/');
        urlToPieces[urlToPieces.length-1] =  id;

        var url = urlToPieces.join('/');
        e.preventDefault();
        $.ajax({
            url : url,
            type : 'DELETE',
            success: function(jsonData){
                removableElmt.remove();
            },
            fail: function(data){
                console.log(data);
            },
        })
    });

}); // CAUTION leave this at the end of the script CAUTION
