

var msgBtn = '';
switch (lg){
    case 'fr':
        msgBtn = 'Ajouter une phase';
        break;
    case 'en' :
        msgBtn = 'Add a stage';
        break;
    case 'pt' :
        msgBtn = 'Acrescentar un etapa';
        break;
    case 'es' :
        msgBtn = 'Añadir un etapa';
        break;
}

var $addStageLink = $('<a href="#" class="waves-effect waves-light btn insert-btn"><i class="fa fa-plus" style="margin-right:5px;"></i>'+ msgBtn +'</a>');
var $newLinkLi = $('<li></li>').append($addStageLink);

function parseDdmmyyyy(str)
{
    var parts = str.split('/');
    return new Date(parts[2], parts[1] - 1, parts[0]);
}


$(function() {
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

    // User has to chose whether to discard the criterion or go back
    $('.modal').modal({
        dismissible:false,
    });

    //Remove page head title if mobile view
    if($(window).width() < 700){
        $('.activity-title').hide();
        $('[class^="dp-"]').css('font-size','13px');
        $('h4').each(function(){
            $(this).replaceWith(function (){return '<h5>'+$(this).text()+'</h5>'})
        });
        $('[href^="#deleteStage"]').each(function(){
            var deleteElmt = $(this);
            $.each(this.attributes, function(){
                var deleteBtn = $('<i class="small material-icons modal-trigger" style="color: red">cancel</i>')
                if(this.name == 'data-sid'){
                    deleteBtn.attr('data-sid',this.value);
                    deleteElmt.replaceWith(deleteBtn);
                    return false;
                }
            })
        });


        if ($('.activity-elements>a').length > 2){
            $('.activity-elements>a').eq(0).empty().append('<i class="fa fa-cube">');
            $('.activity-elements>a').eq(1).empty().append('<i class="fa fa-cube" style="position: relative;top: -6%;left: 0%;font-size: 0.85rem;"></i><i class="fa fa-cube" style="position: relative;top: 11%;left: -10%;font-size: 0.85rem;"></i>');
            $('.activity-elements>a').eq(2).empty().append('<i class="fa fa-cubes">');
            $('.activity-elements>a').eq(3).empty().append('<i class="fa fa-users">');
        }

        $('.action-buttons button').eq(0).empty().append('<i class="fa fa-chevron-circle-left">');
        $('.action-buttons button').eq(1).empty().append('<i class="fa fa-briefcase">');
        $('.action-buttons button').eq(2).empty().append('<i class="fa fa-chevron-circle-right">');

    } else {

        $('.fixed-action-btn a').css({'height':'60px', 'width':'60px'});
        $('.btn-floating i').css({'line-height':'60px', 'font-size':'2.5rem'});
    }

    $('.grade-slider').next().next().hide();


    if($('.stage').length == 1){
        $('.weight').prev().removeClass('s8').addClass('s12');
        $('.weight').hide();
    }

    //Remove the % after PercentType
    for(var k=0;k<$('.weight').length;k++){
        $('.weight')[k].removeChild($('.weight')[k].lastChild);
    }

    $('[href="#deleteStage"]').on('click',function(){
        $('.remove-stage').data('sid',$(this).data('sid'));
    });


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
    });


    $('.dp-start, .dp-end, .dp-gstart, .dp-gend').each(function() {
        $(this).pickadate();
    });


    $.each($('.grade-slider'),function() {
        noUiSlider.create($(this)[0], {
            start: $(this).closest('.weight').find('[type="text"]').val(),
            step: 1,
            connect: [true, false],
            range: {
                'min': 0,
                'max': 100,
            },
        })
    });

    $('.grade-slider').each(function(key,value){
        var sliderValue = Number(value.noUiSlider.get());
        //if(sliderValue != 100){
            value.nextElementSibling.innerHTML = ($('.fa-calendar-check').length == 0) ? Number(sliderValue)+' %' : Number(sliderValue)+' % <span style="font-size: 15px">(<i class="fa fa-cubes red-text"></i> : '+ Math.round(Number(sliderValue) * (1 - Number($('#totalCompletedWeight').text()) / 100)) +' %)</span>';;
            value.nextElementSibling.nextElementSibling.value = sliderValue;
        //}
    });

    $('.grade-slider').each(function(key,value) {

        value.noUiSlider.on('slide', function(values,handle) {

            var modifiedSlider = $(this);
            var newValue = Number(values[handle]);
            var oldValue = Number(value.nextElementSibling.nextElementSibling.value);

            value.nextElementSibling.innerHTML = ($('.fa-calendar-check').length == 0) ? Number(values[handle])+' %' : Number(values[handle])+' % <span style="font-size: 15px">(<i class="fa fa-cubes red-text"></i> : '+ Math.round(Number(values[handle]) * (1 - Number($('#totalCompletedWeight').text()) / 100)) +' %)</span>';
            value.nextElementSibling.nextElementSibling.value = values[handle];

            var sumVal = 0;
            var k = 0;
            var sliders = $('.stage').find('.grade-slider');
            var selectedSliderIndex = sliders.index($(value));

            $.each(sliders, function(key,value){
                if(key != selectedSliderIndex && oldValue != newValue){
                    //$(this).off();
                    var nv = Math.round(Number(Number($(this)[0].noUiSlider.get()) * (100 - newValue) / (100 - oldValue)));

                    if(k == sliders.length - 2 && sumVal + nv + newValue != 100){
                        nv = 100 - sumVal - newValue;
                    }

                    $(this)[0].nextElementSibling.innerHTML = ($('.fa-calendar-check').length == 0) ? nv + ' %' : nv + ' % <span style="font-size: 15px">(<i class="fa fa-cubes red-text"></i> : '+ Math.round(nv * (1 - Number($('#totalCompletedWeight').text()) / 100)) +' %)</span>';
                    $(this)[0].nextElementSibling.nextElementSibling.value = nv;
                    $(this)[0].noUiSlider.set(nv);
                    sumVal += nv;
                    k++;
                }
            })

            $('.total-percentage>span').empty();
            var totalPct = 0;
            $('input[type="text"]').each(function(){
                totalPct += Number($(this).val());
            });
            (totalPct > 100) ? $('.total-percentage>span').addClass('red-text') : $('.total-percentage>span').removeClass('red-text');
            $('.total-percentage>span').append(totalPct + ' %');

        })
    });


    /*
    $('.dp-start, .dp-end, .dp-gstart, .dp-gend').each(function(){
        $(this).pickadate({
            selectMonths: true,
            selectYears: 5,
            clear: 'Clear',
            today: false,
            close: false,
            today: false,
            yearend: '31/12/2018',
            closeOnSelect: true
        })
    });*/

    var endDates = $('.dp-end');
    endDates.data('previous', endDates.val());

    // Get the ul that holds the collection of stages
        $collectionHolder = $('ul.stages');

    // count the current stage form inputs we have (excluding li button add stage)
    $collectionHolder.data('total', $collectionHolder.find('.stage').length);

    //Set datepickers boundaries on loading
    updateDatepickers(0,0);



    // add the "add a stage" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);


    /* ********************** EVENTS ******************** */
    /*
     //Fixed Chrome bug of discarding a just-opened datepicker
     $(document).on('mousedown', '.dp-start, .dp-end, .dp-gstart, .dp-gend', function(e) {
         setTimeout(function (){
         $('.picker').one('mouseup',function(){
             var elmt = $(this);
             setTimeout(function (){
                 elmt.click();
                 elmt.focus();
                 },100)
             })
         }, 20)
     })
     */

    //Setup rules when modifying existing stages

    $(document).on('click', '.remove-stage, .insert-btn', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();
        // get the index


        if($(this).hasClass('remove-stage')){

            var removableElmt = ($(this).data('sid')) ? $('[data-sid="'+$(this).data('sid')+'"]').closest('.stage') : $(this).closest('.stage');
            var index = $('.stage').index(removableElmt);

            var slider = removableElmt.find('.grade-slider');
            var oldValue = Number(slider[0].noUiSlider.get());
            var sliders = $('.stage').find('.grade-slider');
            var selectedSliderIndex = sliders.index(slider);
            var sumVal = 0;
            var k = 0;
            var newValue = 0;

            $.each(sliders, function(key,value){
                if(key != selectedSliderIndex){
                    //$(this).off();
                    var nv = Math.round(Number(Number($(this)[0].noUiSlider.get()) * (100 - newValue) / (100 - oldValue)));

                    if(k == sliders.length - 2 && sumVal + nv + newValue != 100){
                        nv = 100 - sumVal - newValue;
                    }

                    $(this)[0].nextElementSibling.innerHTML = ($('.fa-calendar-check').length == 0) ? nv +' %' : nv+' % <span style="font-size: 15px">(<i class="fa fa-cubes red-text"></i> : '+ Math.round(nv * (1 - Number($('#totalCompletedWeight').text()) / 100)) +' %)</span>';;
                    $(this)[0].nextElementSibling.nextElementSibling.value = nv;
                    $(this)[0].noUiSlider.set(nv);
                    sumVal += nv;
                    k++;
                }
            })

            if($(this).data('sid')){
                urlToPieces = durl.split('/');
                urlToPieces[urlToPieces.length-1] = $(this).data('sid');
                tempUrl = urlToPieces.join('/');

                $.post(tempUrl,null)
                    .done(function(data) {
                    })
                    .fail(function (data){
                        console.log(data)
                    })
            }

            updateDatepickers(-1,index);
            // get stage weight and remove the element
            var stgWeight = Number(removableElmt.find('.grade-slider')[0].noUiSlider.get());
            var prevWSlider = removableElmt.prev().find('.grade-slider')[0].noUiSlider;
            prevWSlider.set(parseInt(Number(prevWSlider.get())+stgWeight));
            if($('.stage').length == 2){
                $('.weight').eq(0).prev().removeClass('s8').addClass('s12');
                $('.weight').eq(0).hide();
                $('a[href="#deleteStage"]').remove();
            }
            removableElmt.remove();


        } else if ($(this).hasClass('insert-btn')){

            var index = $('ul.stages>li').index($(this).closest('li')) - 1;

            // add a new stage form
            //$newLinkLi=$(this);
            addStageForm($collectionHolder,$(this), index + Number($('#nbCompletedStages').text()));
            //Handle new possible calendar dates
            updateDatepickers(1,index);
        }



    });

    $(document).on('change', '.dp-start, .dp-end, .dp-gstart', function() {
        // prevent the link from creating a "#" on the URL
        if ($(this).hasClass('dp-start') || $(this).hasClass('dp-gstart')) {

            var startDate = new Date(parseDdmmyyyy($(this).val()));
            var classPrefix = $(this).attr('class').split(' ')[0].slice(0, -5);

            //Shifting enddates values (for grading and stage)
            var $relatedEndCal = $(this).closest('li').find('.' + classPrefix + 'end');
            $relatedEndCal.pickadate('picker').set('min', $(this).pickadate('picker').get('select'));
            if (startDate > parseDdmmyyyy($relatedEndCal.val())) {
                $relatedEndCal.val($(this).val());
            }

            //Putting grading start date at least superior or equal to updated stage start date if necessary
            if($(this).hasClass('dp-start')) {
                $(this).closest('li').find('.dp-gstart').pickadate('picker').set('min',$(this).pickadate('picker').get('select'));
            }
        }
    });

    $('.modal-close').on('click', function (e) {
        $("#errorModal").modal("close");
    });

    const $stagesHaveInvalidGenddateModal = $('#stages-have-invalid-genddate-modal');
    $('.prev-button,.back-button,.save-button,.next-button,.parameter-button,.stage-button,.criterion-button,.participant-button').on('click', function (e) {
        e.preventDefault();

        const gEndDatesCheck = checkGEndDates();

        if (!gEndDatesCheck.valid) {
            $stagesHaveInvalidGenddateModal.find('.invalid-genddate-stages').html(
                gEndDatesCheck.stages.map(e => /*html*/`
                    <li>
                        <strong>${e.stageName}</strong> (${e.date.toLocaleDateString()})
                    </li>
                `).join('')
            );
            $stagesHaveInvalidGenddateModal.modal('open');
            return false;
        }

        urlToPieces = url.split('/');

        if ($(this).hasClass('prev-button')) {
            urlToPieces[urlToPieces.length - 1] = 'prev';
        } else if ($(this).hasClass('back-button')) {
            urlToPieces[urlToPieces.length - 1] = 'back';
        } else if ($(this).hasClass('save-button')) {
            urlToPieces[urlToPieces.length - 1] = 'save';
        } else if ($(this).hasClass('next-button')) {
            urlToPieces[urlToPieces.length - 1] = 'next';
        } else if ($(this).hasClass('stage-button')) {
            urlToPieces[urlToPieces.length - 1] = 'stage';
        } else if ($(this).hasClass('criterion-button')) {
            urlToPieces[urlToPieces.length - 1] = 'criterion';
        } else if ($(this).hasClass('parameter-button')) {
            urlToPieces[urlToPieces.length - 1] = 'parameter';
        } else if ($(this).hasClass('participant-button')) {
            urlToPieces[urlToPieces.length - 1] = 'participant';
        }

        url = urlToPieces.join('/');

        $(':disabled').each(function(){
            $(this).removeAttr('disabled');
        });
        var tmp = $('form[name="add_stage_form"]').serialize().split('&');
        var nbChamps = 0;
        var stage = $(document).find('#add_stage_form_activeModifiableStages_0_name').closest('.stage');
        var stageFields = stage.find('.input-field');
        var stageMode = stage.find('.mode');

        stageFields.each(function() {
            nbChamps++;
        });

        stageMode.each(function() {
            nbChamps++;
        });

        j = 0;
        for (i = 0; i < tmp.length; i++) {
            if(tmp[i].indexOf('startdate') != -1 && tmp[i].indexOf('gstartdate') == -1){
                tmp[i] = tmp[i].split('=');
                tmp[i+1] = tmp[i+1].split('=');
                tmp[i+2] = tmp[i+2].split('=');
                tmp[i+3] = tmp[i+3].split('=');
                tmp[i][1] = $($("#" + $('.dp-start')[j].id)).pickadate('picker').get('select', 'dd/mm/yyyy');
                tmp[i+1][1] = $($("#" + $('.dp-end')[j].id)).pickadate('picker').get('select', 'dd/mm/yyyy');
                tmp[i+2][1] = $($("#" + $('.dp-gstart')[j].id)).pickadate('picker').get('select', 'dd/mm/yyyy');
                tmp[i+3][1] = $($("#" + $('.dp-gend')[j].id)).pickadate('picker').get('select', 'dd/mm/yyyy');
                tmp[i] = tmp[i].join('=');
                tmp[i+1] = tmp[i+1].join('=');
                tmp[i+2] = tmp[i+2].join('=');
                tmp[i+3] = tmp[i+3].join('=');
            }
            if ((i-3) % nbChamps == 0) {
                j++;
            }
        }

        tmp = tmp.join('&');
        $.post(url, tmp)
            .done(function(data){
                $.each($('.red-text'),function(){
                    $(this).remove();
                });

                url = window.location.pathname;
                urlToPieces = url.split('/');

                if(data.message == 'goBack'){
                    if(url.indexOf('template') != -1){
                        urlToPieces[urlToPieces.length-3] = 'settings';
                        urlToPieces[urlToPieces.length-2] = 'templates';
                        urlToPieces[urlToPieces.length-1] = 'manage';
                        window.location = urlToPieces.join('/');
                    } else {
                        urlToPieces[urlToPieces.length-3] = 'myactivities';
                        console.log(urlToPieces.slice(0,urlToPieces.length-2).join('/'));
                        window.location = urlToPieces.slice(0,urlToPieces.length-2).join('/');
                    }
                }

                else if ($('.red-text').length == 0 && (data.message == 'goNext' || data.message == 'goBack')){
                    urlToPieces[urlToPieces.length-1] = (data.message == 'goNext') ? 'criteria' : 'parameters';
                    window.location = urlToPieces.join('/');
                } else {
                    urlToPieces[urlToPieces.length - 1] = data.message;
                    window.location = urlToPieces.join('/');
                }
            })
            .fail(function (data) {
                console.log(data)
                $.each(data, function(key, value){
                    $.each($('.red-text'),function(){
                        $(this).remove();
                    });
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
                });
                $.each(data.responseJSON, function(key, value){
                    //Specific stage
                    if(key != '#'){
                        $('.red-text').remove();
                        $.each(value, function(key, value){
                            var stageKey = key;
                            $.each(value, function(key, value) {
                                $.each($('input'), function () {
                                    if ($(this).attr('name').indexOf(stageKey) != -1 && $(this).attr('name').indexOf(key) != -1) {
                                        $(this).after('<div class="red-text"><strong>' + value + '</strong></div>');
                                        return false;
                                    }
                                })
                            })
                        })
                    } else {
                        $('.error-msg').append(value);
                        $('#errorModal').modal('open');
                    }
                })
            });
    });
});


    function addStageForm($collectionHolder, $newLinkLi, index) {
        // Get the data-prototype explained earlier
        var prototype = $collectionHolder.data('prototype');
        var total = $collectionHolder.data('total');
        // Get the last index
        //var lastIndex = $collectionHolder.find('li').length - 1;

        // Replacing prototype constants

        var newForm = prototype
            .replace(/__nb__/g,index+2)
            .replace(/__name__/g, total)
            .replace(/__DeleteButton__/g, '<i class="small remove-stage material-icons" style="color: red">cancel</i>');
            //.replace(/__InsertButton__/g, '<a href="#" class="waves-effect waves-light btn insert-btn">Insert a stage</a>');




        // increase the index with one for the next item
        $collectionHolder.data('total', total + 1);

        if($('.stage').length == 1){
            $('.weight').prev().removeClass('s12').addClass('s8');
            $('.weight').show();

        }



        // Display the form in the page in an li, before the "Add a tag" link li
        var $newFormLi = $('<li class="stage"></li>').append(newForm);
        $newFormLi.find('[id*="_mode"]')[2].checked = true;

        var slider = $newFormLi.find('.grade-slider');
        var weight = $newFormLi.find('.weight');

        //Removing '%' text added by PercentType
        weight[0].removeChild(weight[0].lastChild);


        //Insertion in between
        $newLinkLi.parent().before($newFormLi);


        //Get new criteria objects after insertion
        var relatedStages = $('.stage');

        var creationVal = Math.round(100 / relatedStages.length);

        var sliders = relatedStages.find('.grade-slider');
        var sumVal = 0;

        $.each(sliders, function(key,value){
            if(key < relatedStages.length - 1){
                var nv = Math.round(Number($(this)[0].noUiSlider.get()) * (relatedStages.length - 1) / relatedStages.length);
                if(nv == 21) {
                    nv = 20;
                }
                if(nv == 26) {
                    nv = 25;
                }
                $(this)[0].noUiSlider.set(nv);
                $(this)[0].nextElementSibling.innerHTML = ($('.fa-calendar-check').length == 0) ? nv +' %' : nv+' % <span style="font-size: 15px">(<i class="fa fa-cubes red-text"></i> : '+ Math.round(nv * (1 - Number($('#totalCompletedWeight').text()) / 100)) +' %)</span>';
                $(this)[0].nextElementSibling.nextElementSibling.value = nv;
                sumVal += nv;
            }

        });

        if(Math.round(100/relatedStages.length) != 100 / relatedStages.length){
            creationVal = 100 - sumVal;
        }

        //var newVal = parseInt($('.stage').eq(-2).find('.weight-input').val()) / 2;
        noUiSlider.create(slider[0], {
            start: creationVal,
            step: 1,
            connect: [true, false],
            range: {
                'min': 0,
                'max': 100,
            },
        });

        slider[0].nextElementSibling.innerHTML = ($('.fa-calendar-check').length == 0) ? creationVal+' %' : creationVal+' % <span style="font-size: 15px">(<i class="fa fa-cubes red-text"></i> : '+ Math.round(creationVal * (1 - Number($('#totalCompletedWeight').text()) / 100)) +' %)</span>';
        slider[0].nextElementSibling.nextElementSibling.value = creationVal;

        slider[0].noUiSlider.on('slide', function(values,handle) {

            var sliders = $('.stage').find('.grade-slider');
            var newValue = Number(values[handle]);
            var oldValue = Number(slider[0].nextElementSibling.nextElementSibling.value);
            slider[0].nextElementSibling.innerHTML = ($('.fa-calendar-check').length == 0) ? Number(values[handle])+' %' : Number(values[handle])+' % <span style="font-size: 15px">(<i class="fa fa-cubes red-text"></i> : '+ Math.round(Number(values[handle]) * (1 - Number($('#totalCompletedWeight').text()) / 100)) +' %)</span>';
            slider[0].nextElementSibling.nextElementSibling.value = values[handle];
            var sumVal = 0;
            var k = 0;
            var selectedSliderIndex = sliders.index(slider);

            $.each(sliders, function(key,value){
                if(key != selectedSliderIndex && oldValue != newValue){

                    var nv = Math.round(Number(Number($(this)[0].noUiSlider.get()) * (100 - newValue) / (100 - oldValue)));

                    if(k == sliders.length - 2 && sumVal + nv + newValue != 100){
                        nv = 100 - sumVal - newValue;
                    }

                    $(this)[0].nextElementSibling.innerHTML = ($('.fa-calendar-check').length == 0) ? nv +' %' : nv+' % <span style="font-size: 15px">(<i class="fa fa-cubes red-text"></i> : '+ Math.round(nv * (1 - Number($('#totalCompletedWeight').text()) / 100)) +' %)</span>';
                    $(this)[0].nextElementSibling.nextElementSibling.value = nv;
                    $(this)[0].noUiSlider.set(nv);
                    k++;
                    sumVal += nv;
                }
            })

            if($('.weight').length == 1){
                $('.grade-slider')[0].noUiSlider.set(parseInt(100 - Number(slider[0].noUiSlider.get())));
                //$('.grade-slider').eq(0)[0].nextElementSibling.innerHTML = 100 - Number(values[handle])+' %';
            } else if ($('.weight').length == 2){
                if(parseInt(Number($('.grade-slider').eq(0)[0].noUiSlider.get())) != 100 - parseInt(Number(slider[0].noUiSlider.get()))){
                    $('.grade-slider').eq(0)[0].noUiSlider.set(parseInt(100 - Number(slider[0].noUiSlider.get())))
                }
            }


            $('.total-percentage>span').empty();
            var totalPct = 0;
            $('input[type="text"]').each(function(){
                totalPct += Number($(this).val());
            });
            (totalPct > 100) ? $('.total-percentage>span').addClass('red-text') : $('.total-percentage>span').removeClass('red-text');
            $('.total-percentage>span').append(totalPct + ' %');

        });

        slider.next().next().hide();






       /* if (index == total){
            $newLinkLi.before($newFormLi);
        } else {
        */


        //    $('.grade-slider').eq(-2)[0].noUiSlider.set(creationVal);

            //Change of stage number for successors
            for(j=index;j < total;j++){
                $('.s4>h4:eq('+j+')').text("Stage "+(j+2));
            }



    }

    $('.dp-start, .dp-end, .dp-gstart').on('change',function() {

        var selectedDate = $(this).pickadate('picker').get('select');
        var $GStartCal = $(this).closest('.stage').find('.dp-gstart');
        var $GEndCal = $(this).closest('.stage').find('.dp-gend');


        if ($(this).hasClass('dp-start') || $(this).hasClass('dp-gstart')) {

            var classPrefix = $(this).attr('class').split(' ')[0].slice(0, -5);

            //Shifting enddates values (for grading and stage)
            var $relatedEndCal = $(this).closest('.row').find('.' + classPrefix + 'end');
            if ($relatedEndCal.pickadate('picker').get('select').pick < selectedDate.pick) {
                $relatedEndCal.pickadate('picker').set('select', new Date(selectedDate.pick /*+ 14 * 24 * 60 * 60 * 1000*/));
            }
            $relatedEndCal.pickadate('picker').set('min', selectedDate);
            $GStartCal.pickadate('picker').set('min', new Date($('.dp-start').pickadate('picker').get('select').pick));


        } else if ($(this).hasClass('dp-end') && $(this).closest('.recurring').length == 0) {

            if ($GStartCal.pickadate('picker').get('select').pick < selectedDate.pick){
                $GStartCal.pickadate('picker').set('select', new Date(selectedDate.pick + 1 * 24 * 60 * 60 * 1000));
            }

            var GStartDate = $GStartCal.pickadate('picker').get('select');
            if ($GEndCal.pickadate('picker').get('select').pick < GStartDate.pick){
                $GEndCal.pickadate('picker').set('select', new Date(GStartDate.pick + 7 * 24 * 60 * 60 * 1000));
            }
            $GEndCal.pickadate('picker').set('min', GStartDate);

        }
    });

    function updateDatepickers(k, index){

        var nbStages =  $('.stage').length;

        var replaceVars = {
            "janvier":"January","enero":"January","janeiro":"January",
            "février":"February","febrero":"February","fevereiro":"February",
            "mars":"March","marzo":"March","março":"March",
            "avril":"April","abril":"April","abril":"April",
            "mai":"May","mayo":"May","maio":"May",
            "juin":"June","junio":"June","junho":"June",
            "juillet":"July","julio":"July","julho":"July",
            "août":"August","agosto":"August","agosto":"August",
            "septembre":"September","septiembre":"September","setembro":"September",
            "octobre":"October","octubre":"October","outubro":"October",
            "novembre":"November","noviembre":"November","novembro":"November",
            "décembre":"December","diciembre":"December","dezembro":"December",
        };
        var regex = /janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre|enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre|janeiro|fevereiro|março|abril|maio|junho|julho|agosto|setembro|outubro|novembro|dezembro/g ;

        //Three possible cases : loading(0), addition of one stage(1), update(2) or removal (-1)

        if(k==0) {

            //Set datepickers boundaries of grading dates for all stages
            var current = 0;
            var $terminalStageEndCal = $('.dp-end:eq(-1)');

            $('ul.stages>li').each(function(){

                var startCal = $(this).find('.dp-start');
                var endCal = $(this).find('.dp-end');
                var gStartCal = $(this).find('.dp-gstart');
                var gEndCal = $(this).find('.dp-gend');
                var startDateTS = (startCal.val() == "") ? Date.now() : parseDdmmyyyy(startCal.val().replace(regex,function(match){return replaceVars[match];}));
                var endDateTS = (endCal.val() == "") ? startDateTS : parseDdmmyyyy(endCal.val().replace(regex,function(match){return replaceVars[match];}));
                var gStartDateTS = (gStartCal.val() == "") ? startDateTS : parseDdmmyyyy(gStartCal.val().replace(regex,function(match){return replaceVars[match];}));
                var gEndDateTS = (gEndCal.val() == "") ? startDateTS : parseDdmmyyyy(gEndCal.val().replace(regex,function(match){return replaceVars[match];}));
                var startDate = new Date(startDateTS);
                var endDate = new Date(endDateTS);
                var gStartDate = new Date(gStartDateTS);
                var gEndDate = new Date(gEndDateTS);

                /*if(current >= 1){
                    $(this).find('.dp-start').pickadate('picker').set('min',new Date(Date.parse($('.dp-end:eq('+(current-1)+')').val())+1*24*60*60*1000));
                }

                $(this).find('.dp-end').pickadate('picker').set('max',new Date($terminalStageEndCal.pickadate('picker').get('select').pick - (nbStages - 1 - current)*24*60*60*1000));*/
                startCal.pickadate('picker').set('select',startDate);
                endCal.pickadate('picker').set('select',endDate).set('min',startDate);
                gStartCal.pickadate('picker').set('select',gStartDate).set('min',startDate);
                gEndCal.pickadate('picker').set('select',gEndDate).set('min',gStartDate);
                //current++;
            });


            if(nbStages == 1){
                $('.weight-input').attr('disabled',true);
            }

            /*$('.dp-start').first().attr('disabled',true);*/
            /*$('.dp-end').last().attr('disabled',true);*/

        } else if(k==1) {

            //var $lastStageStartCal = $('.dp-start:eq('+(index-1)+')');
            var $lastStageEndCal = $('.dp-end:eq('+(index)+')');
            var $addedStageStartCal = $('.dp-start:eq('+(index+1)+')');
            var $addedStageEndCal = $('.dp-end:eq('+(index+1)+')');
            var $lastStageWeight = $('.weight-input:eq('+index+')');
            var $addedStageWeight = $('.weight-input:eq('+(index+1)+')');
            //var $lastStageGStartCal = $('.dp-gstart:eq('+(index-1)+')');
            //var $lastStageGEndCal = $('.dp-gend:eq('+(index-1)+')');
            var $addedStageGStartCal = $('.dp-gstart:eq('+(index+1)+')');
            var $addedStageGEndCal = $('.dp-gend:eq('+(index+1)+')');

            //Biding setup pickadate config to new stage dates
            $addedStageStartCal.add($addedStageEndCal).add($addedStageGStartCal).add($addedStageGEndCal).pickadate();
            /*
                selectMonths: true,
                selectYears: 5,
                clear: 'Clear',
                today:false,
                close:false,
                today:false,
                yearend : '31/12/2018',
                closeOnSelect: true
            });*/

            //Changing dates
            //var lastStageStartdate = new Date($lastStageStartCal.pickadate('picker').get('select').pick);
            //var lastStageOldEnddate = new Date($lastStageEndCal.pickadate('picker').get('select').pick);
            //var lastStageOldGStartdate = new Date($lastStageGStartCal.pickadate('picker').get('select').pick);
            //var lastStageOldGEnddate = new Date($lastStageGEndCal.pickadate('picker').get('select').pick);
            //var IntermediateTimeStamp = (Date.parse(lastStageStartdate) + Date.parse(lastStageOldEnddate))/2
            //var lastStageNewEnddate = new Date(IntermediateTimeStamp);

            var addedStageStartdate = new Date($lastStageEndCal.pickadate('picker').get('select').pick);
            var addedStageEnddate = new Date($lastStageEndCal.pickadate('picker').get('select').pick);
            var addedStageGStartdate = new Date($lastStageEndCal.pickadate('picker').get('select').pick);
            var addedStageGEnddate = new Date($lastStageEndCal.pickadate('picker').get('select').pick);

            $addedStageStartCal.pickadate('picker').set('select',addedStageStartdate);
            $addedStageEndCal.pickadate('picker').set('select',addedStageEnddate).set('min',$addedStageStartCal.pickadate('picker').get('select'));
            $addedStageGStartCal.pickadate('picker').set('select',addedStageGStartdate);
            $addedStageGEndCal.pickadate('picker').set('select',addedStageGEnddate).set('min',$addedStageGStartCal.pickadate('picker').get('select'));

            /*
            var lastStageNewGStartdate = new Date(IntermediateTimeStamp);
            var lastStageNewGEnddate = new Date(IntermediateTimeStamp+7*24*60*60*1000);*/

            /*
            $lastStageEndCal.pickadate('picker').set('select',lastStageNewEnddate).set('min',new Date(lastStageStartdate));
            $lastStageEndCal.removeAttr('disabled');
            $lastStageGStartCal.pickadate('picker').set('select',lastStageNewEnddate);
            $lastStageGEndCal.pickadate('picker').set('select',lastStageNewGEnddate);

            //$addedStageEndCal.prev().addClass("active");
            $addedStageEndCal.pickadate('picker').set('select',lastStageOldEnddate).set('min',addedStageStartdate);
            $addedStageGStartCal.pickadate('picker').set('select',lastStageOldEnddate).set('min',addedStageStartdate);
            $addedStageGEndCal.pickadate('picker').set('select',lastStageOldGEnddate).set('min',new Date(lastStageOldEnddate));
            $addedStageStartCal.pickadate('picker').set('select',addedStageStartdate).set('min',addedStageStartdate);

            //We bind a variable previous to end date
            $addedStageEndCal.data('previous',$addedStageEndCal.val());

            //Preventing created stages to have an out of bounds enddate, provided that a stage is at least one day long
            var $terminalStageEndCal = $('.dp-end:eq(-1)');

            for(i=1;i<=index+1;i++){
                $('.dp-end:eq('+(i-1)+')').pickadate('picker').set('max',new Date($terminalStageEndCal.pickadate('picker').get('select').pick -(nbStages-i)*24*60*60*1000));
            }

            //Datepicker enddate disabled only in case of addition of terminal stage
            if(index == nbStages-1){
                $addedStageEndCal.attr('disabled',true);
            }*/

            //Changing weights
            if(nbStages > 1) {
                $('.weight:eq(0)').removeAttr('disabled');
            }
            $addedStageWeight.prev().addClass("active");
            $addedStageWeight.attr('value',$lastStageWeight.val()/2);
            $lastStageWeight.val($addedStageWeight.val());


            //Setup rules when modifying new stages
            /*
            $addedStageStartCal.on("change",function(){
                var startDate = new Date(Date.parse($(this).val()));
                //var $relatedEndCal =$(this).parent().parent().find('.dp-end');
                $addedStageEndCal.pickadate('picker').set('min',$(this).pickadate('picker').get('select'));
                if(startDate > Date.parse($addedStageEndCal.val()))
                {
                    $addedStageEndCal.val($(this).val());
                }
            });

            $addedStageGStartCal.on("change",function(){
                var startDate = new Date(Date.parse($(this).val()));
                //var $relatedEndCal =$(this).parent().parent().find('.dp-gend');
                $addedStageGEndCal.pickadate('picker').set('min',$(this).pickadate('picker').get('select'));
                if(startDate > Date.parse($addedStageGEndCal.val()))
                {
                    $addedStageGEndCal.val($(this).val());
                }
            });
            */



            /*
            $addedStageEndCal.on("change",function(){
                var index = $('ul.stages>li').index($(this).closest('li'));
                var newStageEndDate = Date.parse($(this).val());
                var newNextStageStartDate = new Date(newStageEndDate+1*24*60*60*1000);
                var $nextStageStartCal = $('.dp-start:eq('+(index + 1)+')');
                //var $nextStageGStartCal = $('.dp-gstart:eq('+(index + 1)+')');
                //We need to create the datepicker as the event is triggered before datepicker creation in the second date
                //TODO : check if by making pickadate we unbind constraints on it

                //$nextStageStartCal.pickadate();
                $nextStageStartCal.pickadate('picker').set('min',newNextStageStartDate);

                //Next stage startdate is changed only if is was consecutive to previous stage enddate (this is why we store
                //the value before change here
                if(Date.parse($nextStageStartCal.val())-Date.parse($(this).data('previous'))<=1*24*60*60*1000){
                    $nextStageStartCal.pickadate('picker').set('select',newNextStageStartDate);

                }

                $(this).data('previous',$(this).val());

            });
            */




        } else if (k==-1){

            /*
            var $upstreamStageEndCal = $('.dp-end:eq('+(index-1)+')');
            var $removedStageEndCal = $('.dp-end:eq('+index+')');*/
            var $upstreamStageWeight = $('.weight:eq('+index+')');
            var $removedStageWeight = $('.weight:eq('+(index+1)+')');
            /*var $upstreamStageGStartCal = $('.dp-gstart:eq('+(index-1)+')');
            var $upstreamStageGEndCal = $('.dp-gend:eq('+(index-1)+')');
            var $removedStageGStartCal = $('.dp-gstart:eq('+index+')');
            var $removedStageGEndCal = $('.dp-gend:eq('+index+')');

            //Changing max boundaries
            if(index>1){
                for(i=1;i<=index-1;i++){
                    $('.dp-end:eq('+(i-1)+')').pickadate('picker').set('max',new Date(Date.parse($('.dp-end:eq('+(i-1)+')').pickadate('picker').get('max'))+1*24*60*60*1000));
                }
            }
            $('.dp-end:eq('+(index-1)+')').pickadate('picker').set('max',$('.dp-end:eq('+(index)+')').pickadate('picker').get('max'));

            $upstreamStageEndCal.pickadate('picker').set('select', $removedStageEndCal.val());
            $upstreamStageGStartCal.pickadate('picker').set('select',$removedStageGStartCal.val());
            $upstreamStageGEndCal.pickadate('picker').set('min',$removedStageGStartCal.val());
            $upstreamStageGEndCal.pickadate('picker').set('select',$removedStageGEndCal.val());*/
            $upstreamStageWeight.val(parseFloat($upstreamStageWeight.val())+parseFloat($removedStageWeight.val()));
            /*
            //Decreasing the number by 1 of stages following the removed stage
            if(index<nbStages-1){
                for(i=index+1;i<nbStages;i++){
                    $('.stage-title:eq('+ i +')>h4').text("Stage "+ i);
                }
            }*/

            /*
            if(nbStages==2){
                //$upstreamStageEndCal.attr('disabled',true);
                $('.weight:eq(0)').attr('disabled',true);

            }*/

        }

        $('.dp-start, .dp-end, .dp-gstart').on('change',function() {

            var selectedDate = $(this).pickadate('picker').get('select');
            var $GStartCal = $(this).closest('.stage').find('.dp-gstart');
            var $GEndCal = $(this).closest('.stage').find('.dp-gend');

            if ($(this).hasClass('dp-start') || $(this).hasClass('dp-gstart')) {

                var classPrefix = $(this).attr('class').split(' ')[0].slice(0, -5);

                //Shifting enddates values (for grading and stage)
                var $relatedEndCal = $(this).closest('.row').find('.' + classPrefix + 'end');
                if ($relatedEndCal.pickadate('picker').get('select').pick < selectedDate.pick) {
                    $relatedEndCal.pickadate('picker').set('select', new Date(selectedDate.pick /*+ 14 * 24 * 60 * 60 * 1000*/));
                }
                $relatedEndCal.pickadate('picker').set('min', selectedDate);
                $GStartCal.pickadate('picker').set('min', new Date($('.dp-start').pickadate('picker').get('select').pick));


            } else if ($(this).hasClass('dp-end') && $(this).closest('.recurring').length == 0) {

                if ($GStartCal.pickadate('picker').get('select').pick < selectedDate.pick){
                    $GStartCal.pickadate('picker').set('select', new Date(selectedDate.pick + 1 * 24 * 60 * 60 * 1000));
                }

                var GStartDate = $GStartCal.pickadate('picker').get('select');
                if ($GEndCal.pickadate('picker').get('select').pick < GStartDate.pick){
                    $GEndCal.pickadate('picker').set('select', new Date(GStartDate.pick + 7 * 24 * 60 * 60 * 1000));
                }
                $GEndCal.pickadate('picker').set('min', GStartDate);

            }
        });


    }








