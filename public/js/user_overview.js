

if($(window).width() < 700){
    $('table').css('font-size','0.7rem');
}

var resizeTimeout;
$('.result-chart').data('width',$(window).width());

$(window).resize(function(){
    clearTimeout(resizeTimeout);
    if($(window).width() < 0.85*$('.result-chart').data('width') || $(window).width() > 1.15*$('.result-chart').data('width')){
        $('.result-chart').data('width',$(window).width());
        resizeTimeout = setTimeout(function(){
            location.reload();
        }, 500);
    }
});

$(function(){

    /*$.each($('.tab>a'),function(){
        $(this).click();
    })*/

    //$('.tab:first').click();

    $('.tooltipped').tooltip();
    $('#criterionSelector').hide();

    $.each($('.collection-item'),function(){
        if($(this).find('.criterion-comment').length == 0){
            $(this).hide();
        }
    })
    

    $('.indicator').css('background-color','#31b0b7');

    $('.generate-report-btn').on('click',function(e){

        //urlToPieces = gurl.split('/');
        //urlToPieces[urlToPieces.length-1] = $(this).data('uid');
        //url = urlToPieces.join('/');
        var graphElmts = {};
        graphElmts.crtGraph = $('#chart_input_crt').val();
        graphElmts.stgGraph = $('#chart_input_stg').val();
        graphElmts.actGraph = $('#chart_input_act').val();
        var graphURIs = JSON.stringify(graphElmts);

        e.preventDefault();
        $.post(gurl, {graphURIs:graphURIs})
            .done(function(data){
                var blob=new Blob([data]);
                var link=document.createElement('a');
                link.href=window.URL.createObjectURL(blob);
                link.download="User_report.pdf";
                link.click();
            })
            .fail(function (data) {
                console.log(data)
            });
    })

    $('form input[type="checkbox"]').not($('input[name^="settings"]')).on('change',function(){

        var checkboxesElmts = $(this).closest('form').find('input[type="checkbox"]').not($("#print_-1")).not($('input[name^="settings"]'));

        if($(this).is($("#print_-1"))){

            if($(this).is(':checked') == true){
                $.each(checkboxesElmts,function(){
                    $(this).prop('checked',false);
                })
            }
            
        } else {

            var nbChecked = 0;
            $.each(checkboxesElmts,function(){
                if($(this).is(':checked') == true){
                    nbChecked++;
                }
            })

            if(checkboxesElmts.length == nbChecked){
                $.each(checkboxesElmts,function(){
                    $(this).prop('checked',false);
                })
                $("#print_-1").prop('checked',true);
            } else {
                $("#print_-1").prop('checked',false);
            }
        }
    })


});

