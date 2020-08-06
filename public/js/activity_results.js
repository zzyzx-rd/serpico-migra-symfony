
var resizeTimeout;
$('.result-chart').data('width', $(window).width());

$(window).resize(function () {
    clearTimeout(resizeTimeout);
    if ($(window).width() < 0.85 * $('.result-chart').data('width') || $(window).width() > 1.15 * $('.result-chart').data('width')) {
        $('.result-chart').data('width', $(window).width());
        resizeTimeout = setTimeout(function () {
            location.reload();
        }, 500);
    }
});



$(function () {
    $('.modal').modal();
    $('.tooltipped').tooltip();



    // Hide irrelecriteria tabs
    if ($('[class*="selected-tab"]').length > 0) {
        var graphDivId = $('[class*="selected-tab"]').attr('href').substr(1);
        $.each($('[id^="s"]'), function () {
            if ($(this).attr('id') != "serpicoNav" && $(this).attr('id') != "serpicoapp" && $(this).attr('id') != graphDivId) {
                $(this).hide();
            }
        });
    }


    if ($('.indicator:visible').length == 3) {
        $('.indicator:visible').eq(-2).css('background-color', '#2196F3!important');
        $('.indicator:visible').eq(-1).css('background-color', '#4CAF50!important');
    } else if ($('.indicator:visible').length == 2) {
        if ($('.green-text').length > 0) {
            $('.indicator:visible').eq(-1).css('background-color', '#4CAF50!important');
        } else {
            $('.indicator:visible').eq(-1).css('background-color', '#2196F3!important');
        }

    }

    $(document).on('click', '.modal-publish', function () {
        if ($(this).data("aid")) {
            $('.publish-btn').data("aid", $(this).data("aid"));
        } else {
            $('.publish-btn').data("sid", $(this).data("sid"));
        }

    });



    function generateReport() { }
    $('.generate-report-btn').on('click', function (e) {

        e.preventDefault();
        console.log($(this).data('aid'));
        urlToPieces = $(this).attr('href').split('/');
        urlToPieces[urlToPieces.length - 5] = $(this).data('aid');
        urlToPieces[urlToPieces.length - 4] = $(this).data('sindex');
        urlToPieces[urlToPieces.length - 3] = $(this).data('cindex');
        urlToPieces[urlToPieces.length - 2] = 1;
        if (document.getElementById('changeReportData').checked) {
            equalVal = 1;
        } else {
            equalVal = 0;
        }
        urlToPieces[urlToPieces.length - 1] = equalVal;
        $(this).attr('href', urlToPieces.join('/'));
        window.location = this.href;

        $(this).removeData('aid');
        $(this).removeData('sindex');
        $(this).removeData('cindex');
        /*
        $.post(url, null)
            .done(function (data) {
            })
            .fail(function (data) {
                console.log(data)
            });
        */
    });

    $('.tab').on('click', function () {

        var clickedTab = $(this);

        if (!$(this).hasClass('disabled')) {

            // Change crt tab

            if ($('.tabs').length > 2 && $('.tabs').index($(this).closest('.tabs')) == 1) {
                var stageNb = $(this).find('a').attr('href').split('_')[0].substr(2);
                var crtTab = null;
                //Find displayable crt tab (may not exist if no criteria have been defined)
                $('.tabs').each(function () {
                    if ($(this).find('a').attr('href').split('_')[0].substr(2) == stageNb && $(this).find('a').attr('href').split('_')[1].substr(1) != "") {
                        crtTab = $(this);
                        return false;
                    }
                });
                // We remove last visible tab only if there was already a visible crt tab
                if ($('.tabs:visible').length > 2) { $('.tabs:visible').eq(-1).closest('div').hide(); }
                if (crtTab) { crtTab.closest('div').show() };
            }

            // Remove color to previously selected tab
            $.each($('.tab').not(clickedTab), function () {
                if ($(this).find('a').hasClass('blue-selected-tab')) {
                    $(this).find('a').removeClass('blue-selected-tab').addClass('blue-text');
                    return false;
                } else if ($(this).find('a').hasClass('green-selected-tab')) {
                    $(this).find('a').removeClass('green-selected-tab').addClass('green-text');
                    return false;
                } else if ($(this).find('a').hasClass('red-selected-tab')) {
                    $(this).find('a').removeClass('red-selected-tab');
                    return false;
                }
            });


            // set color to selected tab


            if ($('.tabs:visible').length == 3) {
                if ($('.tabs').index($(this).closest('.tabs')) == 0) {
                    $(this).find('a').addClass('red-selected-tab').removeClass('red-text');
                } else if ($('.tabs').index($(this).closest('.tabs')) == 1) {
                    $(this).find('a').addClass('blue-selected-tab').removeClass('blue-text');
                } else {
                    $(this).find('a').addClass('green-selected-tab').removeClass('green-text');
                }
            } else if ($('.tabs:visible').length == 2) {
                if ($('.tabs').index($(this).closest('.tabs')) == 0) {
                    $(this).find('a').addClass('red-selected-tab').removeClass('red-text');
                } else if ($('.tabs').index($(this).closest('.tabs')) == 1) {
                    ($('.blue-text').length > 0) ? $(this).find('a').addClass('blue-selected-tab').removeClass('blue-text') : $(this).find('a').addClass('green-selected-tab').removeClass('green-text')
                }
            }

        }

    });

    $('form input[type="checkbox"]').not($('input[name^="settings"]')).on('change',function(){

        var checkboxesElmts = $(this).closest('form').find('input[type="checkbox"]').not($("#print_-2_-2")).not($('input[name^="settings"]'));

        if($(this).is($("#print_-2_-2"))){

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
                $("#print_-2_-2").prop('checked',true);
            } else {
                $("#print_-2_-2").prop('checked',false);
            }
        }
    })

    /*
    $('.create-report').on('click',function(e){
        e.preventDefault();
        //var data = $(this).closest('form').serialize();
        //data = data + '&aid=' + $(this).data('aid');
        
        $.post(gurl,data)
            .done(function(data){
                window.location = data.message;
            })
            .fail(function(data){
                console.log(data);
            })
        
    })
    */
    

});