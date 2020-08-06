
var replaceVars = {
    "janvier":"January","février":"February","mars":"March","avril":"April","mai":"May","juin":"June","juillet":"July","août":"August","septembre":"September","octobre":"October","novembre":"November","décembre":"December",
    "enero":"January","febrero":"February","marzo":"March","abril":"April","mayo":"May","junio":"June","julio":"July","agosto":"August","septiembre":"September","octubre":"October","noviembre":"November","diciembre":"December",
    "Janeiro":"January","Fevereiro":"February","Março":"March","Abril":"April","Maio":"May","Junho":"June","Julho":"July","Agosto":"August","Setembro":"September","Outubro":"October","Novembro":"November","Dezembro":"December",
};

var engRegex =  /January|February|March|April|May|June|July|August|September|October|November|December'/g ;
var regex = /janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre|enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre|Janeiro|Fevereiro|Março|Abril|Maio|Junho|Julho|Agosto|Setembro|Outubro|Novembro|Dezembro'/g ;


function parseDdmmyyyy(str)
{
    var parts = str.split('/');
    return new Date(parts[2], parts[1] - 1, parts[0]);
}

$(function(){
    /** @param {boolean} inParametersPage */
    function checkGEndDates(inParametersPage) {
        /**
         * @type {{ date: Date; stageName: string; }[]}
         */
        const fields = Array.from(document.querySelectorAll('input[name$="[genddate]"]')).map(
            e => {
                const $e = $(e);
                /** @type {Date} */
                const date = $e.pickadate('picker').get('select').obj;
                const stageName = inParametersPage ? null : $e.closest('.stage').find('[name]').val();
                return { date, stageName };
            }
        );

        const tomorrow = new Date;
        tomorrow.setDate(tomorrow.getDate() + 1);
        tomorrow.setHours(0, 0, 0, 0);

        const invalidStages = fields.filter(e => e.date - tomorrow < 0);

        return {
            valid: invalidStages.length == 0,
            stages: invalidStages
        };
    }

    $('.visibility').hide();

    if ($('[name="add_activity_criteria_form[type]"]').filter(':checked').val() != 1){
        $('.scale').hide();
    }

    $('.modal').modal();
    $('.modal[id*="criterionTarget"]').modal({
        dismissible: false,
        complete: function(){
            let modC = $(this)[0].$el;
            if(modC.find('input[type="checkbox"]').is(':checked') || modC.find('textarea').val().trim() != ""){
                $('[href="#criterionTarget"]').addClass('lime darken-3').empty().append($('<ul class="flex-center no-margin"><i class="far fa-dot-circle" style="margin-right:10px"></i>'+modalModifyMsg+'<i class="fas fa-comment-dots" style="margin-left:10px"></i></ul>'));
            } else {
                $('[href="#criterionTarget"]').removeClass('lime darken-3').empty().append($('<ul class="flex-center no-margin"><i class="far fa-dot-circle" style="margin-right:10px"></i>'+modalSetMsg+'<i class="far fa-comment" style="margin-left:10px"></i></ul>'));
            }
        }
    });

    if($('[name="add_activity_criteria_form[recurringChoice]"]').length > 0){
        ($('[name="add_activity_criteria_form[recurringChoice]"]').filter(':checked').val() == 0) ? $('.recurring').hide() : $('.activity-calendar, .grading-calendar').hide() ;
    } else {
        /*$('.activity-calendar, .grading-calendar').find('input').attr('disabled',true)*/;
        $('.recurring-elmts').hide();
    }


    switch(lg){

        case 'fr':
            $.extend($.fn.pickadate.defaults, {
                monthsFull: ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'],
                monthsShort: [ 'Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec' ],
                weekdaysFull: [ 'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi' ],
                weekdaysShort: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ],
                today: 'Aujourd\'hui',
                clear: 'Effacer',
                close: 'Fermer',
                firstDay: 1,
                //format: 'dd mmmm yyyy',
            });
            break;
        case 'es':
            $.extend($.fn.pickadate.defaults, {
                monthsFull: [ 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre' ],
                monthsShort: [ 'ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic' ],
                weekdaysFull: [ 'domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado' ],
                weekdaysShort: [ 'dom', 'lun', 'mar', 'mié', 'jue', 'vie', 'sáb' ],
                today: 'hoy',
                clear: 'borrar',
                close: 'cerrar',
                firstDay: 1,
                //format: 'dddd d !de mmmm !de yyyy',
            });
            break;
        case 'pt':
            $.extend($.fn.pickadate.defaults, {
                monthsFull: [ 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro' ],
                monthsShort: [ 'jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez' ],
                weekdaysFull: [ 'Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado' ],
                weekdaysShort: [ 'dom', 'seg', 'ter', 'qua', 'qui', 'sex', 'sab' ],
                today: 'Hoje',
                clear: 'Limpar',
                close: 'Fechar',
                firstDay: 1,
                //format: 'd !de mmmm !de yyyy',
            });
            break;
        default:
            break;

    }



    $.extend($.fn.pickadate.defaults, {
        selectMonths: true,
        selectYears: 5,
        yearend: '31/12/2018',
        closeOnSelect: true,
        clear: false,
        //format : 'dd MMMM, yyyy',
        //formatSubmit: 'yyyy/mm/dd'
        //formatSubmit: 'yyyy/mm/dd'
    });



    $('.dp-start, .dp-end, .dp-gstart, .dp-gend, .open-end, .dp-rstart').each(function() {
        $(this).pickadate();
    });

    // Fixed Chrome bug of discarding a just-opened datepicker
    // $(document).on('mousedown', '.dp-start, .dp-end, .dp-gstart, .dp-gend', function(e) {
    //     setTimeout(function (){
    //     $('.picker').one('mouseup',function(){
    //         var elmt = $(this);
    //         setTimeout(function (){
    //             elmt.click();
    //             elmt.focus();
    //             },100)
    //         })
    //     }, 50)
    // })

    $('.dp-end:not(".open-end")').pickadate({clear:'Erase'});


    //Set datepickers boundaries on loading (if activity is not complex, otherwise dates set in stages)
    if($('.dp-start').length>0){
        updateDatepickers();
    }

    $('.grade-slider').next().next().hide();

    if($('.target').length > 0){

        if(!$('#criterionTarget input[type="checkbox"]').is(':checked')){
            $('#criterionTarget').find('.target').hide();
            $('#criterionTarget').find('[type="text"]').attr('disabled',true)
        }

        $('#criterionTarget input[type="checkbox"]').on('change',function(){
            $(this).is(':checked') ? ($('#criterionTarget').find('.target').show(),$('#criterionTarget').find('[type="text"]').attr('disabled',false)) : ($('#criterionTarget').find('.target').hide(),$('#criterionTarget').find('[type="text"]').attr('disabled',true));
        })

        //Removing '%' text added by Symfony PercentType
        $('.target')[0].removeChild($('.target')[0].lastChild);


        // Warning : different sliders need to be initalized (target and magnitude, thus needed to make different initialization !!)
        let initValue = $('.target').find('[type="text"]').val();

        let weightSlider = $('.grade-slider:not(.magnitude-slider)');

        noUiSlider.create(weightSlider[0], {
            start: initValue,
            step: 1,
            connect: [true, false],
            range: {
                'min': 0,
                'max': 100,
            },
        });

        weightSlider[0].nextElementSibling.innerHTML = initValue + ' %';
        weightSlider[0].nextElementSibling.nextElementSibling.value = initValue;

        weightSlider[0].noUiSlider.on('slide', function (values, handle) {

            weightSlider[0].nextElementSibling.innerHTML = Number(values[handle]) + ' %';
            weightSlider[0].nextElementSibling.nextElementSibling.value = values[handle];

        });
    }


    $(document).on('change', '.dp-start, .dp-end, .dp-gstart', function() {

        var selectedDate = $(this).pickadate('picker').get('select');

        if ($(this).hasClass('dp-start') || $(this).hasClass('dp-gstart')) {

            var classPrefix = $(this).attr('class').split(' ')[0].slice(0, -5);

            //Shifting enddates values (for grading and stage)
            var $relatedEndCal = $(this).closest('.row').find('.' + classPrefix + 'end');
            if ($relatedEndCal.pickadate('picker').get('select').pick < selectedDate.pick) {
                $relatedEndCal.pickadate('picker').set('select', new Date(selectedDate.pick /*+ 14 * 24 * 60 * 60 * 1000*/));
            }
            $relatedEndCal.pickadate('picker').set('min', selectedDate);
            var $GStartCal = $('.dp-gstart');
            $GStartCal.pickadate('picker').set('min', new Date($('.dp-start').pickadate('picker').get('select').pick));


        } else if ($(this).hasClass('dp-end') && $(this).closest('.recurring').length == 0) {

            var $GStartCal = $('.dp-gstart');
            if ($GStartCal.pickadate('picker').get('select').pick < selectedDate.pick){
                $GStartCal.pickadate('picker').set('select', new Date(selectedDate.pick + 1 * 24 * 60 * 60 * 1000));
            }

            var GStartDate = $GStartCal.pickadate('picker').get('select');
            var $GEndCal = $('.dp-gend');
            if ($GEndCal.pickadate('picker').get('select').pick < GStartDate.pick){
                $GEndCal.pickadate('picker').set('select', new Date(GStartDate.pick + 7 * 24 * 60 * 60 * 1000));
            }
            $GEndCal.pickadate('picker').set('min', GStartDate);

        }
    });

    initValue = $('.magnitude input').val();


    $('.magnitude-slider').each(function(key,value) {
        noUiSlider.create(value, {
            start: initValue,
            snap : true,
            connect: [true, false],
            range: {
                'min': +value.dataset.lb,
                '12.5%' : 2,
                '25%' : 3,
                '37.5%' : 5,
                '50%' : 8,
                '62.5%' : 13,
                '75%' : 20,
                '87.5%' : 40,
                'max':  +value.dataset.ub,
            },
        })
    });



    var magnitudeSliderElmt = $('.magnitude-slider')[0];
    magnitudeSliderElmt.nextElementSibling.innerHTML = initValue;
    magnitudeSliderElmt.nextElementSibling.nextElementSibling.value = initValue;

    magnitudeSliderElmt.noUiSlider.on('slide', function (values, handle) {

        magnitudeSliderElmt.nextElementSibling.innerHTML = +values[handle];
        magnitudeSliderElmt.nextElementSibling.nextElementSibling.value = values[handle];

    });


    $('[name="add_activity_criteria_form[type]"]').change(function(){



        ($(this).filter(':checked').val() == 1) ? $('.scale').show() : $('.scale').hide();

        if($(this).filter(':checked').val() == 1){
            $('.force-choice label').text(forceCommentMsg_0);
            $('.force-sign, .force-value').show();
        } else if ($(this).filter(':checked').val() == 2) {
            $('.force-choice label').text(forceCommentMsg_1);
            $('.force-sign, .force-value').hide();
        } else {
            $('.force-choice label').text(forceCommentMsg_2);
            $('.force-sign, .force-value').hide();
        }
    });

    $(document).ready(function() {
        ($('[name="add_activity_criteria_form[type]"]').filter(':checked').val() == 1) ? $('.scale').show() : $('.scale').hide();
    });

    $('[name="add_activity_criteria_form[recurringType]"]').change(function(){
        ($(this).filter(':checked').val() == 1) ? $('.rscale').show() : $('.rscale').hide();
    });

    $('[name="add_activity_criteria_form[recurringChoice]"]').change(function(){
        ($(this).val() == 0) ? ($('.recurring').hide(),/*$('.activity-calendar, .grading-calendar').find('input').attr('disabled',false),*/ $('.activity-calendar, .grading-calendar').show()) : ($('.recurring').show(),/*$('.activity-calendar, .grading-calendar').find('input').attr('disabled',true),*/ $('.activity-calendar, .grading-calendar').hide());
    });
    /*
    $('.click-down, .click-up').on('click',function(e){
        e.preventDefault();
        $(this).hasClass('click-down') ? $(this).next().val(Number($(this).next().val())-1) : $(this).prev().val(Number($(this).prev().val())+1);
    })*/

    $('.modify-recurring').on('click',function(){
        $('.modify-recurring').hide();
        $('.recurring-elmts').show();
    });

    $('.criterion-icons li').on('click',function(){
        $('.criterion-icons .blue-text').removeClass('blue-text');
        $(this).find('i').addClass('blue-text');
    })

    //Remove page head title if mobile view
        if($(window).width() < 700){
            $('.comment-row').css('margin-bottom','70px');
            $('.activity-title').hide();
            if ($('.activity-elements>a').length > 3){
                $('.activity-elements>a').eq(0).empty().append('<i class="fa fa-cube">');
                $('.activity-elements>a').eq(1).empty().append('<i class="fa fa-cube" style="position: relative;top: -6%;left: 0%;font-size: 0.85rem;"></i><i class="fa fa-cube" style="position: relative;top: 11%;left: -10%;font-size: 0.85rem;"></i>');
                $('.activity-elements>a').eq(2).empty().append('<i class="fa fa-cubes">');
                $('.activity-elements>a').eq(3).empty().append('<i class="fa fa-users">');
            } else {
                $('.activity-elements>a').eq(0).empty().append('<i class="fa fa-cubes">');
                $('.activity-elements>a').eq(2).empty().append('<i class="fa fa-users">');
            }
            $('.action-buttons button').eq(0).empty().append('<i class="fa fa-briefcase">');
            $('.action-buttons button').eq(1).empty().append('<i class="fa fa-chevron-circle-right">');


        } else {

            $('.fixed-action-btn a').css({'height':'60px', 'width':'60px'});
            $('.btn-floating i').css({'line-height':'60px', 'font-size':'2.5rem'});
        }

    const $stagesHaveInvalidGenddateModal = $('#stages-have-invalid-genddate-modal');
    $('.next-button, .back-button, .save-button, .enrich-button, .stage-button, .criterion-button, .participant-button').on('click',function(e) {
        e.preventDefault();

        const gEndDatesCheck = checkGEndDates(true);

        if (!gEndDatesCheck.valid) {
            $stagesHaveInvalidGenddateModal.modal('open');
            return false;
        }

        urlToPieces = url.split('/');

        if($(this).hasClass('next-button')){
            urlToPieces[urlToPieces.length-1] = 'next';
        } else if ($(this).hasClass('back-button')){
            urlToPieces[urlToPieces.length-1] = 'back';
        } else if ($(this).hasClass('save-button')){
            urlToPieces[urlToPieces.length-1] = 'save';
        } else if ($(this).hasClass('enrich-button')){
            urlToPieces[urlToPieces.length-1] = 'enrich';
        } else if ($(this).hasClass('stage-button')){
            urlToPieces[urlToPieces.length-1] = 'stage';
        } else if ($(this).hasClass('criterion-button')){
            urlToPieces[urlToPieces.length-1] = 'criterion';
        } else if ($(this).hasClass('participant-button')){
            urlToPieces[urlToPieces.length-1] = 'participant';
        }

        url = urlToPieces.join('/');
        var tmp = $('form[name="add_activity_criteria_form"]').serialize().split('&');
        for(i = 0; i<tmp.length; i++){
            if(tmp[i].indexOf('startdate') != -1 && tmp[i].indexOf('gstartdate') == -1){
                tmp[i] = tmp[i].split('=');
                tmp[i+1] = tmp[i+1].split('=');
                tmp[i+2] = tmp[i+2].split('=');
                tmp[i+3] = tmp[i+3].split('=');
                tmp[i][1] = $('.dp-start').pickadate('picker').get('select', 'dd/mm/yyyy');
                tmp[i+1][1] = $('.dp-end').pickadate('picker').get('select', 'dd/mm/yyyy');
                tmp[i+2][1] = $('.dp-gstart').pickadate('picker').get('select', 'dd/mm/yyyy');
                tmp[i+3][1] = $('.dp-gend').pickadate('picker').get('select', 'dd/mm/yyyy');
                tmp[i] = tmp[i].join('=');
                tmp[i+1] = tmp[i+1].join('=');
                tmp[i+2] = tmp[i+2].join('=');
                tmp[i+3] = tmp[i+3].join('=');
                break;
            }
        }
        tmp = tmp.join('&');

        $.post(url,tmp)
            .done(function(data){

                $.each($('.red-text'),function(){
                    $(this).remove();
                })

                if(data.message !== null){
                    if(data.message == 'goBack'){
                        url = window.location.pathname;
                        urlToPieces = url.split('/');
                        if(url.indexOf('template') != -1){
                            urlToPieces[urlToPieces.length-3] = 'settings';
                            urlToPieces[urlToPieces.length-2] = 'templates';
                            urlToPieces[urlToPieces.length-1] = 'manage';
                            window.location = urlToPieces.join('/');
                        } else {
                            urlToPieces[urlToPieces.length-3] = 'myactivities';
                            window.location = urlToPieces.slice(0,urlToPieces.length-2).join('/');
                        }
                    }// else {
                    //     try {
                    //         var data = JSON.parse(data);
                    //         $.each(data, function(key, value){
                    //             $.each($('input'),function(){
                    //                 if($(this).attr('name').indexOf(key) != -1){
                    //                     $(this).after('<div class="red-text"><strong>'+value+'</strong></div>');
                    //                     return false;
                    //                 }
                    //             })
                    //         })
                    //     }
                    //     catch(e){
                    if($('.red-text').length == 0 && data.message != 'goBack'){
                        url = window.location.pathname;
                        urlToPieces = url.split('/');
                        urlToPieces[urlToPieces.length-1] = data.message;
                        window.location = urlToPieces.join('/');
                    }
                }
            })
            .fail(function (data) {
                console.log(data);
                $.each($('.red-text'),function(){
                    $(this).remove();
                });
                $.each(data, function(key, value){
                    if(key == "responseJSON"){
                    console.log(key);
                    console.log(value);
                    $.each(value, function(cle, valeur){
                        $.each($('input, select'),function(){
                            if($(this).is('[name]') && $(this).attr('name').indexOf(cle) != -1){
                                $(this).after('<div class="red-text"><strong>'+valeur+'</strong></div>');
                            }
                        });
                    });
                    }
                });
            });
        });

    $('.save-recurring').on('click',function(){
        $.post(rurl, $('form').serialize())

        .done(function(data){

            $.each($('.red-text'),function(){
                $(this).remove();
            })


            try {
                var data = JSON.parse(data);
                $.each(data, function(key, value){
                    $.each($('input'),function(){
                        if($(this).attr('name').indexOf(key) != -1){
                            $(this).after('<div class="red-text"><strong>'+value+'</strong></div>');
                            return false;
                        }
                    })
                })
            }
            catch(e){
                if($('.red-text').length == 0){
                    //url = window.location.pathname;
                    //urlToPieces = url.split('/');
                    //urlToPieces[urlToPieces.length-1] = (data.message == 1) ? 'participants' : 'stages';
                    //window.location = urlToPieces.join('/');
                    window.location = window.location.pathname;
                }
            }
        })
        .fail(function (data) {
            console.log(data);
            $.each($('.red-text'),function(){
                $(this).remove();
            });
            $.each(data, function(key, value){
                if(key == "responseJSON"){
                console.log(key);
                console.log(value);
                $.each(value, function(cle, valeur){
                    $.each($('input'),function(){
                        if($(this).attr('name').indexOf(cle) != -1){
                            $(this).after('<div class="red-text"><strong>'+valeur+'</strong></div>');
                            return false;
                            }
                    });

                });
                }
            })
        });

    })


    function updateDatepickers(){

        //Set datepickers boundaries of grading dates for all stages

        //var revReplaceVars = _.invert(replaceVars);
        if ($('.recurring-msg').length > 0){
            var array_flipped={};
            var frVars={};var esVars={};var ptVars={};
            $k=0;
            $.each(replaceVars, function(i, el) {
                ($k<12) ? frVars[el] = i : ($k<24 ? esVars[el]=i : ptVars[el]=i)
                $k++;
            });
            switch(lg){
                case 'fr' :
                    $('.recurring-msg').text($('.recurring-msg').text().replace(engRegex,function(match){return frVars[match];}));
                    break;
                case 'es' :
                    $('.recurring-msg').text($('.recurring-msg').text().replace(engRegex,function(match){return esVars[match];}));
                    break;
                case 'pt' :
                    $('.recurring-msg').text($('.recurring-msg').text().replace(engRegex,function(match){return ptVars[match];}));
                    break;
            }
        }



        var startDateTS = ($('.dp-start').val() == "") ? Date.now() : parseDdmmyyyy($('.dp-start').val().replace(regex,function(match){return replaceVars[match];}));
        //var rStartDateTS = ($('.dp-rstart').val() == "") ? Date.now() : Date.parse($('.dp-rstart').val().replace(regex,function(match){return replaceVars[match];}));
        var endDateTS = ($('.dp-end').val() == "") ? startDateTS + 30 * 24 * 60 * 60 * 1000 : parseDdmmyyyy($('.dp-end').val().replace(regex,function(match){return replaceVars[match];}));
        var gStartDateTS = ($('.dp-gstart').val() == "") ? startDateTS + 31 * 24 * 60 * 60 * 1000 : parseDdmmyyyy($('.dp-gstart').val().replace(regex,function(match){return replaceVars[match];}));
        var gEndDateTS = ($('.dp-gend').val() == "") ? startDateTS + 38 * 24 * 60 * 60 * 1000 : parseDdmmyyyy($('.dp-gend').val().replace(regex,function(match){return replaceVars[match];}));
        //var rEndDateTS = ($('.open-end').val() == "") ? startDateTS + 365 * 24 * 60 * 60 * 1000 : Date.parse($('.open-end').val().replace(regex,function(match){return replaceVars[match];}));
        var startDate = new Date(startDateTS);
        var endDate = new Date(endDateTS);
        var gStartDate = new Date(gStartDateTS);
        var gEndDate = new Date(gEndDateTS);
        //var rEndDate = new Date(rEndDateTS);
        //var rStartDate = new Date(rStartDateTS);
/*
        switch($('[name="add_activity_criteria_form[recurringTimeFrame]"]').val()){
            case 'Y':
                var rMinEndDate = new Date(rStartDate.setMonth(rStartDate.getMonth() + $('.frequency').val() * 12));
                break;
            case 'M':
                var rMinEndDate = new Date(rStartDate.setMonth(rStartDate.getMonth() + $('.frequency').val() * 1));
                break;
            case 'W':
                var rMinEndDate = new Date(rStartDateTS + $('.frequency').val() * 7 * 24 * 60 * 60 * 1000);
                break;
            default:
                var rMinEndDate = new Date(rStartDateTS + $('.frequency').val() * 24 * 60 * 60 * 1000);
                break;
        }
*/

        $('.dp-start').each(function(){$(this).pickadate('picker').set('select',startDate)});
        $('.dp-rstart').each(function(){$(this).pickadate('picker').set('select',startDate)});
        $('.dp-end').each(function(){$(this).pickadate('picker').set('select',endDate).set('min',$(this).closest('.row').find('.dp-start').pickadate('picker').get('select'))});
        $('.dp-gstart').pickadate('picker').set('select',gStartDate).set('min',$('.dp-gstart').closest('.row').prev().find('.dp-start').pickadate('picker').get('select'));
        $('.dp-gend').pickadate('picker').set('select',gEndDate).set('min',$('.dp-gstart').pickadate('picker').get('select'));
//        $('.open-end').pickadate('picker').set('select',rEndDate).set('min',rMinEndDate);
            /*

            if ($('.dp-start, .dp-gstart, .dp-end, dp-gend').val() == "") {
                startDateTS = Date.now();
                endDateTS = startDateTS + 30 * 24 * 60 * 60 * 1000;
                gStartDateTS = startDateTS + 31 * 24 * 60 * 60 * 1000;
                gEndDateTS = startDateTS + 38 * 24 * 60 * 60 * 1000;

            } else {
                var displayedStartDate = $('.dp-start').val();
                var displayedGStartDate = $('.dp-gstart').val();

                if (lg == 'fr'){

                }
                var regex = /janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre|enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre/g ;
                startDateTS = Date.parse(displayedStartDate.replace(regex,function(match){return replaceVars[match];}));
                gStartDateTS = Date.parse(displayedGStartDate.replace(regex,function(match){return replaceVars[match];}));


            }

            var startDate = new Date(startDateTS);
            var gStartDate = new Date(gStartDateTS);


            //if ($('.dp-start').val() == "") {
                $('.dp-start').pickadate('picker').set('select', new Date(startDateTS));
            //}

            if ($('.dp-end').val() == "") {
                $('.dp-end').pickadate('picker').set('min',startDate).set('select', new Date(startDateTS + 30 * 24 * 60 * 60 * 1000));
            } else {
                $('.dp-end').pickadate('picker').set('min',startDate);
            }
            if ($('.dp-gstart').val() == "") {
                $('.dp-gstart').pickadate('picker').set('min',new Date(startDateTS)).set('select', new Date(startDateTS + 31 * 24 * 60 * 60 * 1000));
            } else {
                $('.dp-gstart').pickadate('picker').set('min',new Date(startDateTS));
            }
            if ($('.dp-gend').val() == "") {
                $('.dp-gend').pickadate('picker').set('min',gStartDate).set('select', new Date(startDateTS + 38 * 24 * 60 * 60 * 1000));
            } else {
                $('.dp-gend').pickadate('picker').set('min',gStartDate);
            }
            */

    }

    $("#addCriterionNameSuccess > div > div > a.modal-action").on("click", function(){
        setTimeout(function(){
            window.location.reload(true);
        }, 1500);
    });
    $("create_criterion_form_submit").on("click", function(){
        setTimeout(function(){
            window.location.reload(true);
        }, 5000)
    });

    // $("#addCriterionNameSuccess").modal("close", function(){
    //     setTimeout(function(){
    //         window.location.reload(true);
    //     }, 2000);
    // });
});







