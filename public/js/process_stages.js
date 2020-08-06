$(function () {

    setTimeout(function (){
        if($('#errors').length > 0){
            $('#errors').modal('open');
            $('#errors').find('label+span').each(function(){
                $(this).text($(this).prev().text()+' :');
                $(this).prev().remove();
            })
            $('#errors .modal-content ul').css('display','inline-block').addClass('no-margin');
        }
    },200)

    lg='fr';
    let $stageCollectionHolder = $('.stages');

    function parseDdmmyyyy(str)
    {
        var parts = str.split('/');
        return new Date(parts[2], parts[1] - 1, parts[0]);
    }

    // Updates calendar datepickers

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

                startCal.pickadate('picker').set('select',startDate);
                endCal.pickadate('picker').set('select',endDate).set('min',startDate);
                gStartCal.pickadate('picker').set('select',gStartDate).set('min',startDate);
                gEndCal.pickadate('picker').set('select',gEndDate).set('min',gStartDate);
            });

        } else if(k==1) {

            var $lastStageEndCal = $('.dp-end:eq('+(index)+')');
            var $addedStageStartCal = $('.dp-start:eq('+(index+1)+')');
            var $addedStageEndCal = $('.dp-end:eq('+(index+1)+')');
            var $lastStageWeight = $('.weight-input:eq('+index+')');
            var $addedStageWeight = $('.weight-input:eq('+(index+1)+')');
            var $addedStageGStartCal = $('.dp-gstart:eq('+(index+1)+')');
            var $addedStageGEndCal = $('.dp-gend:eq('+(index+1)+')');

            $addedStageStartCal.add($addedStageEndCal).add($addedStageGStartCal).add($addedStageGEndCal).pickadate();

            var addedStageStartdate = new Date($lastStageEndCal.pickadate('picker').get('select').pick);
            var addedStageEnddate = new Date($lastStageEndCal.pickadate('picker').get('select').pick);
            var addedStageGStartdate = new Date($lastStageEndCal.pickadate('picker').get('select').pick);
            var addedStageGEnddate = new Date($lastStageEndCal.pickadate('picker').get('select').pick);

            $addedStageStartCal.pickadate('picker').set('select',addedStageStartdate);
            $addedStageEndCal.pickadate('picker').set('select',addedStageEnddate).set('min',$addedStageStartCal.pickadate('picker').get('select'));
            $addedStageGStartCal.pickadate('picker').set('select',addedStageGStartdate);
            $addedStageGEndCal.pickadate('picker').set('select',addedStageGEnddate).set('min',$addedStageGStartCal.pickadate('picker').get('select'));

        } else if (k==-1){

            var $upstreamStageWeight = $('.weight:eq('+index+')');
            var $removedStageWeight = $('.weight:eq('+(index+1)+')');
            $upstreamStageWeight.val(parseFloat($upstreamStageWeight.val())+parseFloat($removedStageWeight.val()));
        }

    }

    /**
     * Disables options in criterion name selects as appropriate
     * @param {JQuery|HTMLElement} target
     */
    function handleCNSelectElems(target) {
        const isCName = (_i, e) => /_criteria_\d+_cname/gi.test(e.id);

        const $crtElems = target
                        ? $(target).closest('.criteria')
                        : $('.criteria');
        const $selects = $crtElems.find('select').filter(isCName);

        $selects.find('option').prop('disabled', false);

        for (const crtElem of $crtElems) {
            const $crtElem = $(crtElem);
            const $options = $crtElem.find('select').filter(isCName).find('option');
            const inUse = $options.filter(':selected').get().map(e => e.value);
            const $optionsToDisable = $options.filter((_i, e) => inUse.includes(e.value) && !e.selected);

            $optionsToDisable.prop('disabled', true);
        }

        initCNIcons();
    }

    function initCNIcons() {
        const $stylizableSelects = $('.input-field select');
        $stylizableSelects.find('option').each(function(_i, e) {
            e.innerHTML = e.innerHTML.trim()
        });
        $stylizableSelects.material_select();
        const regExp = /~(.+)~/;
        $('.select-with-fa .select-dropdown:not(.stylized)').each(function(_i, e) {
            const $this = $(e);
            if ($this.is('input')) {
                const match = $this.val().match(regExp);
                if (!match) return;
                let icon = match[1];
                icon = String.fromCodePoint
                       ? String.fromCodePoint('0x'+icon)
                       : '';
                $this.val($this.val().replace(regExp, icon));
            } else if ($this.is('ul')) {
                $this.find('li > span').each(function(_i, e) {
                    e.innerHTML = e.innerHTML.trim().replace(
                    regExp,
                    '<span class="cn-icon">&#x$1;</span>'
                    );
                });
            }

            $this.addClass('stylized');
        });
    }
    handleCNSelectElems();

    $('ul.criteria').on('change', 'select[id$="cName"]', e => handleCNSelectElems(e.target));

    $('.gradetype').css({
        'display' : 'flex',
        'justify-content' : 'space-between'
    })

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
        yearend: '31/12/2020',
        closeOnSelect: true,
        clear: false,
        //format : 'dd MMMM, yyyy',
        //formatSubmit: 'yyyy/mm/dd'
    });

    $('.dp-start, .dp-end, .dp-gstart, .dp-gend').each(function() {
        $(this).pickadate();
    });

    var endDates = $('.dp-end');
    endDates.data('previous', endDates.val());


    //Set datepickers boundaries on loading
    updateDatepickers(0,0);

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

    $(document).on('click', '.add-criterion', function () {
        $('#addCriterion').find('button').data('selectedNb', $(this).closest('li').index());
    });

    //$(document).on('click', '.remove-user, .insert-btn', function(e) {

    $('.criterion-submit').on('click', function (e) {
        e.preventDefault();
        var iconName = null;
        var serializedForm = $(this).closest('form').serialize();
        var iconClasses = $('.criterion-icons .blue-text').attr('class').split(' ');
        $.each(iconClasses, function (index, item) {
            if (item.indexOf("fa-") == 0) {
                iconName = item;
                return;
            }
        })
        serializedForm += "&icon=" + iconName;
        $.post(curl, serializedForm)
            .done(function (data) {
                $.each($('.red-text'), function () {
                    $(this).remove();
                });
                if ($('.red-text').length == 0) {
                    $('.modal').modal('close');
                    $('#addCriterionNameSuccess').modal('open');
                    setTimeout(function () {
                        // console.log('on est bons');
                        window.location.reload(true);
                    }, 3000);
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
                });
            });
    });

    $('#addCriterionNameSuccess').modal({
        complete: function () { // Callback for Modal close
            location.reload();
        }
    });

    //Remove page head title if mobile view
    if ($(window).width() < 700) {
        $('.activity-title').hide();
        if ($('.activity-elements>a').length > 2) {
            $('.activity-elements>a').eq(0).empty().append('<i class="fa fa-cube">');
            $('.activity-elements>a').eq(1).empty().append('<i class="fa fa-cube" style="position: relative;top: -6%;left: 0%;font-size: 0.85rem;"></i><i class="fa fa-cube" style="position: relative;top: 11%;left: -10%;font-size: 0.85rem;"></i>');
            $('.activity-elements>a').eq(2).empty().append('<i class="fa fa-cubes">');
            $('.activity-elements>a').eq(3).empty().append('<i class="fa fa-users">');
        }
        $('.action-buttons button').eq(0).empty().append('<i class="fa fa-chevron-circle-left">');
        $('.action-buttons button').eq(1).empty().append('<i class="fa fa-briefcase">');
        $('.action-buttons button').eq(2).empty().append('<i class="fa fa-chevron-circle-right">');
    } else {

        $('.fixed-action-btn a').css({ 'height': '60px', 'width': '60px' });
        $('.btn-floating i').css({ 'line-height': '60px', 'font-size': '2.5rem' });
    }


    $('.criterion-fill').on("change", function () {
        if ($('#add_criterion_form_diffCriteria_0').is(':checked')) {
            $('#diffToSimilar').modal('open');
            $('.collapsible').collapsible('close', 0);
            $('form>.stage:eq(0)').nextAll().not('.action-buttons').hide();
            $('form>.stage:eq(0)').find('.collapsible-header').hide();
            $('.collapsible').collapsible('open', 0);

        } else {

            $('form>.stage:eq(0)').nextAll().show();
            $('form>.stage:eq(0)').find('.collapsible-header').show();
            $('.collapsible').collapsible('close', 0);
        }

    });

    $('.cancel-btn').on('click', function () {
        $('[type="radio"]').eq(1)[0].checked = true;
        $('form>.stage:eq(0)').nextAll().show();
        $('form>.stage:eq(0)').find('.collapsible-header').show();
        $('.collapsible').collapsible('close', 0);
    });

    if($('.stage').length == 1){$('.stage-banner .weight').hide();}

    $('.stage').each(function () {
        var stageCrit = $(this).find('.criterion');
        if (stageCrit.length == 1) {
            stageCrit.find('.scale .input-field').not(':last').removeClass('m3').addClass('m4');
            stageCrit.find('.weight').hide();
        }
        /*if(!($('.stage').length == 1 && stageCrit.length == 1)){
            stageCrit.hide();
        }*/
    });

    $(document).on('change','.switch input',function(){
        if($(this).closest('li').hasClass('date-switch')){
            $(this).is(':checked') ?  ($(this).closest('.row').find('.period-freq-input').hide(), $(this).closest('.row').find('.dates-input').show()) : ($(this).closest('.row').find('.period-freq-input').show(), $(this).closest('.row').find('.dates-input').hide());
        } else {
            $(this).is(':checked') ?  ($(this).closest('.col').find('.survey-element').hide(), $(this).closest('.col').find('.criteria-elements').show()) : ($(this).closest('.col').find('.survey-element').show(), $(this).closest('.col').find('.criteria-elements').hide());
        }
    });

    $(document).on('click','.output-switch',function(){
        if($(this).find('input').is(':disabled')){
            $('#changeOutputType').modal('open');
            $('.change-output-btn').data('sid',$(this).closest('.stage').find('[href*="#deleteStage"]').data('sid'));
        }
    });

    $(document).on('click','.change-output-btn',function(){
        const urlToPieces = courl.split('/');
        let sid = $(this).data('sid');
        urlToPieces[urlToPieces.length-2] = sid;
        url = urlToPieces.join('/');
        $.post(url, null)
            .done(function(data) {
                let outputCheckbox = $(`[data-sid="${sid}"]`).closest('.stage').find('.output-switch input');
                if(!outputCheckbox.is(':checked')){
                    outputCheckbox.closest('.col').find('.survey-element').hide();
                    outputCheckbox.closest('.col').find('.criteria-elements').show();
                } else {
                    outputCheckbox.closest('.col').find('.survey-element').show();
                    $.each(outputCheckbox.closest('.col').find('.criteria-elements .criterion'),function(){
                        $(this).remove();
                    })
                    outputCheckbox.closest('.col').find('.criteria-elements').hide();
                }
                outputCheckbox.prop('checked',!outputCheckbox.prop('checked'));
                outputCheckbox.prop('disabled',false);
            })
            .fail(function(data) {

            });
    });

    //$('.collapsible-header .element-input > ul > :not(:last-child)').addClass('not-collapse');

    $(document).on('click','a.s-validate', function(e) {

        e.preventDefault();
        $curRow = $(this).closest('.element-input');
        $curRow.find('.red-text').remove();
        eid = $(this).data('sid');

        inputName = $curRow.find('input[name*="name"]').val();
        weightVal = $curRow.find('input[name*="weight"]').val();
        isDefiniteDates = $curRow.find('[name*="definiteDates"]').is(':checked');
        startdate = $curRow.find('[name*="startdate"]').val();
        enddate = $curRow.find('[name*="enddate"]').val();
        gstartdate = $curRow.find('[name*="gstardate"]').val();
        genddate = $curRow.find('[name*="genddate"]').val();
        dPeriod = $curRow.find('[name*="dPeriod"]').val();
        dFrequency = $curRow.find('select[name*="dFrequency"] option:selected').val();
        dOrigin = $curRow.find('select[name*="dOrigin"] option:selected').val();
        fPeriod = $curRow.find('[name*="fPeriod"]').val();
        fFrequency = $curRow.find('select[name*="fFrequency"] option:selected').val();
        fOrigin = $curRow.find('select[name*="fOrigin"] option:selected').val();
        visibility = $curRow.find('select[name*="visibility"] option:selected').val();
        mode = $curRow.find('[name*="mode"]:checked').val();

        const $form = $('.s-form form');
        $form.find('input, select').removeAttr('disabled');

        $form.find('[name*="weight"]').val(weightVal);
        $form.find('[name*="name"]').val(inputName);
        $form.find('[name*="definiteDates"]').prop('checked', isDefiniteDates);
        $form.find('[name*="[gstartdate]"]').val(gstartdate);
        $form.find('[name*="[genddate]"]').val(genddate);
        $form.find('[name*="[startdate]"]').val(startdate);
        $form.find('[name*="[enddate]"]').val(enddate);
        $form.find('[name*="dPeriod"]').val(dPeriod);
        $form.find('select[name*="dFrequency"]').val(dFrequency);
        $form.find('select[name*="dOrigin"]').val(dOrigin);
        $form.find('[name*="fPeriod"]').val(fPeriod);
        $form.find('select[name*="fFrequency"]').val(fFrequency);
        $form.find('select[name*="fOrigin"]').val(fOrigin);
        $form.find('select[name*="visibility"]').val(visibility);
        $form.find('[name*="mode"]').eq(mode).prop('checked',true);

        const urlToPieces = window.location.pathname.split('/');
        urlToPieces.push('stage', 'validate', eid);
        const vurl = urlToPieces.join('/');
        var tmp = $form.serialize().split('&');

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
                $increment = true;
            } else {
                $increment = false;
            }
            if ($increment) {
                j++;
            }
        }

        mSerializedForm = tmp.join('&');
      $li = $(this).closest('li.stage').find('a.insert-survey-btn')
        $.post(vurl, mSerializedForm)
        .done(function(data) {
            $closestStageBanner = $curRow.closest('.stage-banner');
            const href=$li.attr('href').replace('0', data['sid']);
            $li.attr('href',href);
            $closestStageBanner.find('.stage-visibility').empty();
            $closestStageBanner.find('.stage-name').empty().append(inputName);
            $closestStageBanner.find('.stage-dates span').text(startdate +' - '+ enddate);
            $closestStageBanner.find('.stage-period-freq span').text(dPeriod +' '+ dFrequency);
            isDefiniteDates ? ($closestStageBanner.find('.stage-dates').show(), $closestStageBanner.find('.stage-period-freq').hide()) : ($closestStageBanner.find('.stage-dates').hide(), $closestStageBanner.find('.stage-period-freq').show());
            visibility == 1 ? $closestStageBanner.find('.stage-visibility').append('<i class="fa fa-lock-open"></i>') : visibility == 2 ? $closestStageBanner.find('.stage-visibility').append('<i class="fa fa-unlock"></i>') : $closestStageBanner.find('.stage-visibility').append('<i class="fa fa-lock"></i>');
            $closestStageBanner.find('.element-data').show();
            $removeBtn = $closestStageBanner.find('.fa-trash').closest('a');
            if($removeBtn.hasClass('remove-stage')){
                $closestStageBanner.find('.fa-trash').closest('a')
                    .attr('data-sid',data.sid)
                    .addClass('modal-trigger')
                    .removeClass('remove-stage')
                    .attr('href','#deleteStage');
            }

        })
        .fail(function(data) {
            $curRow.after(/*html*/`
                <div class="red-text">
                    <strong>${data.responseJSON.errorMsg}</strong>
                </div>
            `);
        });

    })

    $(document).on('click','a.c-validate', function(e) {

        e.preventDefault();
        $curRow = $(this).closest('.element-input');
        crtElmt = $(this).closest('.criterion');
        $curRow.find('.red-text').remove();
        sid = $(this).closest('.stage').find('.s-validate').data('sid');
        cid = $(this).data('cid');
        crtVal = $curRow.find('select[name*="cName"] option:selected').val();
        typeVal = $curRow.find('[name*="type"]:checked').val();
        isRequiredComment = $curRow.find('[type*="forceCommentCompare"]:checked').val();
        commentSign = $curRow.find('select[name*="forceCommentSign"] option:selected').val();
        commentValue = $curRow.find('[name*="forceCommentValue"]').val();
        lowerbound = $curRow.find('[name*="lowerbound"]').val();
        upperbound = $curRow.find('[name*="upperbound"]').val();
        step = $curRow.find('[name*="step"]').val();


        const $form = $('.c-form form');

        const urlToPieces = window.location.pathname.split('/');
        urlToPieces.push('stage', sid, 'criterion', 'validate', cid);
        const vurl = urlToPieces.join('/');

        $form.find('[name*="cName"]').val(crtVal);
        $form.find('[name*="type"]').eq(typeVal - 1).prop('checked',true);
        $form.find('[name*="forceCommentCompare"]').prop('checked', isRequiredComment);
        $form.find('[name*="forceCommentSign"]').val(commentSign);
        $form.find('[name*="forceCommentValue"]').val(commentValue);
        $form.find('[name*="lowerbound"]').val(lowerbound);
        $form.find('[name*="upperbound"]').val(upperbound);
        $form.find('[name*="step"]').val(step);

        $.post(vurl, $form.serialize())
        .done(function(data) {
            fillCriterionDataContent(crtElmt);
            $curRow.closest('.col').find('.output-switch input').prop('disabled',true);
        })
        .fail(function(data) {
            $curRow.after(/*html*/`
                <div class="red-text">
                    <strong>${data.responseJSON.errorMsg}</strong>
                </div>
            `);
        });


    });

    function fillCriterionDataContent(crtElmt){
        let elmtInput = crtElmt.find('.element-input');
        let elmtData = crtElmt.find('.element-data');
        elmtData.find('.cw').text(Number(elmtInput.find('input.weight-input').val()));
        //let potentialIcon =
        //if(elmtInput.find('.criterion-name input').val().split(' ')[0])
        elmtData.find('.cn').text(elmtInput.find('.criterion-name input').val().split(' ').slice(1).join(' '));

        // Changing icon
        elmtData.find('.ci').empty();
        let optionIconClass = elmtInput.find('.criterion-name select option:selected').attr('class');
        if(!optionIconClass){
            elmtData.find('.ci').append($('<i class="fa fa-cube"></i>'));
        } else {
            if(optionIconClass.split(' ')[0][0] == 'f'){
                elmtData.find('.ci').append($('<i class="'+optionIconClass+'"></i>'));
            } else {
                elmtData.find('.ci').append('<i class="material-icons">'+optionIconClass+'</i>');
            }
        }

        if(elmtInput.find('.gradetype').find(':checked').val() == 1){
            elmtData.find('.csc, .cst').closest('li').show();
            elmtData.find('.csc').text('[' + +elmtInput.find('.lowerbound').val().replace(',','.') +'-'+ +elmtInput.find('.upperbound').val().replace(',','.') + ']');
            elmtData.find('.cst').text(+elmtInput.find('.step').val().replace(',','.'));
        } else {
            elmtData.find('.csc, .cst').closest('li').hide();
        }

        if(elmtInput.find('.force-choice input').is(':checked') && elmtInput.find('.force-value input').val() != ""){
            elmtData.find('.ccsi').empty();
            elmtData.find('.ccv').empty();

            if(elmtInput.find('.gradetype').find(':checked').val() == 1){
                let signSelectElmt = elmtInput.find('.force-sign .select-wrapper input');
                let signSelectValue = (signSelectElmt.val() != "") ? signSelectElmt.val() : signSelectElmt.attr('value');
                let sign = (signSelectValue == elmtInput.find('.force-sign select option:eq(0)').text()) ? '<' : '≤';
                elmtData.find('.ccsi').append(sign);
                elmtData.find('.ccv').append(elmtInput.find('.force-value input').val());
            }
            elmtData.find('.fa-comment').closest('li').show();
        } else {
            elmtData.find('.fa-comment').closest('li').hide();
        }
    }

    $(document).on('click','.activity-title a:has(.fa-check)',function(){
        let actValue = $(this).closest('li').prev().find('input').val();
        $('.activity-title .element-data h4').empty().text(actValue);
        $(this).closest('.element-input').hide().prev().show();
    });

    $(document).on('click','a:has(.fa-pencil-alt)',function(){
        if(!$(this).closest('.criteria').length > 0){
            $(this).closest('.element-data').hide().next().show();
        }
    })

    /*$('.not-collapse').on('click',function(){
        if($(this).hasClass('blue')){
            stgName = $(this).closest('.stage').find('input[name*="name"]').val();
            $(this).closest('.stage').find('.stage-name').empty().text(stgName);
            $(this).closest('.element-input').hide().prev().show();

        } else {
            $(this).closest('.element-data').hide().next().show();
        }
    });
    */

    $(document).on('click','[href="#deleteStage"]', function () {
        $('.remove-stage').data('sid', $(this).data('sid'));
        $('#deleteStage').css('z-index',9999);
    });

    $(document).on('click','[href="#deleteCriterion"]', function () {
        $('.remove-criterion').data('cid', $(this).data('cid'));
        $('#deleteCriterion').css('z-index',9999);
    });

    $.each($('[id*="criterionTarget"]'),function(value){
        if(!$(this).find('input[type="checkbox"]').is(':checked')){
            $(this).find('.target').hide();
            $(this).find('[type="text"]').attr('disabled',true)
        }

        $(this).find('input[type="checkbox"]').on('change',function(){
            $(this).is(':checked') ? ($(this).closest('[id*="criterionTarget"]').find('.target').show(),$(this).closest('[id*="criterionTarget"]').find('[type="text"]').attr('disabled',false)) : ($(this).closest('[id*="criterionTarget"]').find('.target').hide(),$(this).closest('[id*="criterionTarget"]').find('[type="text"]').attr('disabled',true));
        })


        $(this).find('.target-slider').next().next().hide();

        //Removing '%' text added by Symfony PercentType
        $(this).find('.target')[0].removeChild($(this).find('.target')[0].lastChild);
        let initValue = $(this).find('.target').find('[type="text"]').val();

        noUiSlider.create($(this).find('.target-slider')[0], {
            start: initValue,
            step: 1,
            connect: [true, false],
            range: {
                'min': 0,
                'max': 100,
            },
        });

        $(this).find('.target-slider')[0].nextElementSibling.innerHTML = initValue + ' %';
        $(this).find('.target-slider')[0].nextElementSibling.nextElementSibling.value = initValue;

        $(this).find('.target-slider')[0].noUiSlider.on('slide', function (values, handle) {

            $($(this)[0].target.closest('[id*="criterionTarget"]')).find('.target-slider')[0].nextElementSibling.innerHTML = Number(values[handle]) + ' %';
            $($(this)[0].target.closest('[id*="criterionTarget"]')).find('.target-slider')[0].nextElementSibling.nextElementSibling.value = values[handle];

        });
    });

    $.each($('.weight-stage-slider'), function () {
        noUiSlider.create($(this)[0], {
            start: $(this).closest('.stage-banner').find('input.weight-input').val(),
            step: 1,
            connect: [true, false],
            range: {
                'min': 0,
                'max': 100,
            },
        })
    });

    $.each($('.weight-criterion-slider'), function () {
        noUiSlider.create($(this)[0], {
            start: $(this).closest('.criterion').find('input.weight-input').val(),
            step: 1,
            connect: [true, false],
            range: {
                'min': 0,
                'max': 100,
            },
        })
    });

    $('.weight-stage-slider').each(function (key, value) {

        var sliderValue = Number(value.noUiSlider.get());
        if (sliderValue != 100) {
            value.nextElementSibling.innerHTML = sliderValue + ' %';
            value.nextElementSibling.nextElementSibling.value = sliderValue;
        }

        value.noUiSlider.on('slide', function (values, handle) {

            value.nextElementSibling.innerHTML = Number(values[handle]) + ' %';
            value.nextElementSibling.nextElementSibling.value = values[handle];
            $(value).closest('.element-input').prev().find('.stage-weight').empty().append('('+Number(values[handle]) + ' %)');

        })
    });

    $('.weight-criterion-slider').each(function (key, value) {

        var sliderValue = Number(value.noUiSlider.get());
        if (sliderValue != 100) {
            value.nextElementSibling.innerHTML = sliderValue + ' %';
            value.nextElementSibling.nextElementSibling.value = sliderValue;
        }

        value.noUiSlider.on('slide', function (values, handle) {

            value.nextElementSibling.innerHTML = Number(values[handle]) + ' %';
            value.nextElementSibling.nextElementSibling.value = values[handle];
            $(value).closest('.element-input').prev().find('.cw').empty().append(Number(values[handle]));
        })
    });

    $('.weight-criterion-slider, .weight-stage-slider, .target-slider').next().next().hide();

    //Remove the % after PercentType
    for (var k = 0; k < $('.weight').length; k++) {
        $('.weight')[k].removeChild($('.weight')[k].lastChild);
    }

    // Change binary labels
    $(document).ready(function() {
        $.each($('.gradetype'),function(){
            var crtElmt = $(this).closest('.criterion');
            if ($(this).find('input[type="radio"]').eq(2).is(':checked')) {
                crtElmt.find('.force-choice label').text(forceCommentMsg_2);
                crtElmt.find('.force-sign, .force-value').hide();
            } else if ($(this).find('input[type="radio"]').eq(1).is(':checked')) {
                crtElmt.find('.force-choice label').text(forceCommentMsg_1);
                crtElmt.find('.force-sign, .force-value').hide();
            } else {
                crtElmt.find('.force-choice label').text(forceCommentMsg_0);
                crtElmt.find('.force-sign, .force-value').show();
            }

            // Hidding scale and step if non evaluation criterion
            if(crtElmt.find('input[type="radio"]:checked').val() != 1){
                crtElmt.find('.scale .input-field').hide();
            }

            // Hide or display weight input, depending on number of non-pure-comments elements
            var k = 0;
            $(this).closest('.stage').find('.criterion input[type="radio"]:checked').each(function(key,selectedRadioBtn){
                if (selectedRadioBtn.value != 2){
                    k++;
                }
                if(k > 1){
                    return false;
                }
            })

            if (k > 1 && crtElmt.find('input[type="radio"]:checked').val() != 2) {
                crtElmt.find('.weight').show();
            } else {
                crtElmt.find('.weight').hide();
            }


        })
    });

    $(document).on('change', '.gradetype', function () {
        var k = 0;
        var crtElmt = $(this).closest('.criterion');
        var sliders = crtElmt.closest('.stage').find('.weight-criterion-slider');
        var crtIndex = $(this).closest('.stage').find('.criterion').index(crtElmt);

        var oldValue = Number(crtElmt.find('.weight-criterion-slider')[0].noUiSlider.get());

        if (crtElmt.find('.gradetype :checked').val() != 1) {
            crtElmt.find('.scale :text:not(.weight-input)').attr('disabled', 'disabled');
        } else {
            crtElmt.find('.scale :text:not(.weight-input)').removeAttr('disabled');
        }

        // Hide or display weight input, depending on number of non-pure-comments elements
        var k = 0;
        $(this).closest('.stage').find('.criterion input[type="radio"]:checked').each(function(key,selectedRadioBtn){
            if (selectedRadioBtn.value != 2){
                k++;
            }
            if(k > 1){
                return false;
            }
        })

        if (k > 1 && crtElmt.find('input[type="radio"]:checked').val() != 2) {
            crtElmt.find('.weight').show();
        } else {
            crtElmt.find('.weight').hide();
        }


        if (crtElmt.find('input[type="radio"]').eq(1).is(':checked')) {


            var slider = crtElmt.find('.weight-criterion-slider');

            var selectedSliderIndex = sliders.index(slider);




            crtElmt.find('.weight-criterion-slider')[0].nextElementSibling.innerHTML = '0 %';
            crtElmt.find('.weight-criterion-slider')[0].nextElementSibling.nextElementSibling.value = 0;
            crtElmt.find('.weight-criterion-slider')[0].noUiSlider.set(0);

            var sumVal = 0;
            var k = 0;
            var newValue = 0;

            $.each(sliders, function (key, value) {
                if (key != selectedSliderIndex) {
                    //$(this).off();
                    var nv = Math.round(Number(Number($(this)[0].noUiSlider.get()) * (100 - newValue) / (100 - oldValue)));

                    if (k == sliders.length - 2 && sumVal + nv + newValue != 100) {
                        nv = 100 - sumVal - newValue;
                    }

                    $(this)[0].nextElementSibling.innerHTML = nv + ' %';
                    $(this)[0].nextElementSibling.nextElementSibling.value = nv;
                    $(this)[0].noUiSlider.set(nv);
                    sumVal += nv;
                    k++;
                }
            })

            $.each($(this).closest('.stage').find('.criterion').not(crtElmt), function () {
                var newValue = Math.round(Number($(this).find('.weight-criterion-slider').eq(0)[0].noUiSlider.get()));

                // Hide weight when only one criterion has 100 percent
                if (newValue == 100) {
                    $(this).find('.scale .input-field').eq(-1).hide();
                    $(this).find('.scale .input-field').not(':last').addClass('m4').removeClass('m3');
                }
            });

            // Hide scale

            crtElmt.find('.scale .input-field').hide();
            crtElmt.find('.force-comments').hide();


        } else {

            var oldValue = Math.round(crtElmt.find('.weight-criterion-slider')[0].noUiSlider.get());

            if (oldValue == 0) {

                //Get new criteria objects after insertion
                var relatedCriteria = crtElmt.closest('.stage').find('.criterion');

                var creationVal = Math.round(100 / relatedCriteria.length);

                var sliders = relatedCriteria.find('.weight-criterion-slider');
                var sumVal = 0;

                $.each(sliders, function (key, value) {
                    if (key != crtIndex) {
                        var nv = Math.round(Number($(this)[0].noUiSlider.get()) * (relatedCriteria.length - 1) / relatedCriteria.length);
                        $(this)[0].noUiSlider.set(nv);
                        $(this)[0].nextElementSibling.innerHTML = nv + ' %';
                        $(this)[0].nextElementSibling.nextElementSibling.value = nv;
                        sumVal += nv;
                    }
                })

                if (Math.round(100 / relatedCriteria.length) != 100 / relatedCriteria.length) {
                    creationVal = 100 - sumVal;
                }

                crtElmt.find('.weight-criterion-slider')[0].nextElementSibling.innerHTML = creationVal + ' %';
                crtElmt.find('.weight-criterion-slider')[0].nextElementSibling.nextElementSibling.value = creationVal;
                crtElmt.find('.weight-criterion-slider')[0].noUiSlider.set(creationVal);

            }

        }

        //var nbCrt = $(this).closest('.stage').find('.criterion').length;
        $.each($(this).closest('.stage').find('.criterion'), function () {
            if (!$(this).find('input[type="radio"]').eq(1).is(':checked')) {
                k++;
            }
        });


        if ($(this).find('input[type="radio"]').eq(0).is(':checked')) {
            crtElmt.find('.force-choice label').text(forceCommentMsg_0);
            crtElmt.find('.input-field').not('.criterion-name, .weight').show();
            crtElmt.find('.weight').removeAttr('style');
            crtElmt.find('.force-sign, .force-value').show();

        } else if ($(this).find('input[type="radio"]').eq(2).is(':checked')) {

            crtElmt.find('.force-choice label').text(forceCommentMsg_2);
            crtElmt.find('.input-field').not('.criterion-name, .weight').hide();
            crtElmt.find('.force-sign, .force-value').hide();

        } else {
            crtElmt.find('.force-choice label').text(forceCommentMsg_1);
            crtElmt.find('.weight').removeAttr('style').hide();
            crtElmt.find('.force-comments').show();
            crtElmt.find('.force-sign, .force-value').hide();
        }

        if(crtElmt.find('input[type="radio"]:checked').val() != 1){
            crtElmt.find('.scale .input-field').hide();
        }

        if (!$(this).find('input[type="radio"]').eq(2).is(':checked') && crtElmt.find('.force-comments .col').eq(0).hasClass('m12')) {
            crtElmt.find('.force-comments .col').eq(0).removeClass('m12').addClass('m5')
        }

        if (crtElmt.find('input[type="radio"]').eq(2).is(':checked')) {
            crtElmt.find('input[name*="lowerbound"]').val(0);
            crtElmt.find('input[name*="upperbound"]').val(1);
            crtElmt.find('input[name*="step"]').val(1);
        }

    });


    $(document).ready(function () {

        $.each($('.gradetype'), function () {
            var crtElmt = $(this).closest('.criterion');

            if (!$(this).find('input[type="radio"]').eq(2).is(':checked') && crtElmt.find('.force-comments .col').eq(0).hasClass('m12')) {
                crtElmt.find('.force-comments .col').eq(0).removeClass('m12').addClass('m5')
            }

            if ($(this).find('input[type="radio"]').eq(1).is(':checked')) {
                crtElmt.find('.force-comments .col').eq(0).removeClass('m12').addClass('m5')
            }

        });
    });




    $('.prev-button,.back-button,.save-button,.next-button,.parameter-button,.stage-button,.criterion-button,.participant-button').on('click', function (e) {

        $('.weight-error').parent().empty();

        e.preventDefault();
        $.each($('.red-text'), function () {
            $(this).remove();
        });

        //FE validation for collection fields (BE will come after as it is not that easy)
        /*
        $.each($('ul.criteria'),function(){
            var sumWeights = 0;
            var selectedElmt = $(this);
            selectedElmt.find('.weight-input').each(function(){
                        sumWeights += Number($(this).val());
                })
            if(sumWeights != 100) {
                var error = $('<li style="list-style:none;text-align: center;padding: 0 0 10px 0;""></li>').append($('<div class="red-text weight-error"><strong>' + 'La somme des poids des critères doit valoir 100 % (total : '+sumWeights+' %)'+'</strong></div>'));
                ($('.stage').eq(0).prev().find('[type="radio"]:checked').val() == 0) ? $('.stage').eq(0).after(error) : $('.stage').eq(-1).after(error);
                te = 1;
                return false;
            }
        });
        */



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

        $('[disabled="disabled"]').not('[id*="targetValue"]').each(function () {
            $(this).removeAttr('disabled');
        })

        $.post(url, $('form').serialize())
            .done(function (data) {

                currentUrl = window.location.pathname;
                urlToPieces = currentUrl.split('/');

                if (data.message == 'supHundredPct') {
                    $('#supHundredPct').find('span').empty();
                    $('#supHundredPct').find('span').eq(0).append(data.stageName);
                    $('#supHundredPct').find('span').eq(1).append(data.totalWeights);
                    $('#supHundredPct').modal('open');
                } else if (data.message == 'goBack') {
                    if(url.indexOf('template') != -1){
                        urlToPieces[urlToPieces.length-3] = 'settings';
                        urlToPieces[urlToPieces.length-2] = 'templates';
                        urlToPieces[urlToPieces.length-1] = 'manage';
                        window.location = urlToPieces.join('/');
                    } else {
                        urlToPieces = currentUrl.split('/');
                        urlToPieces[urlToPieces.length - 3] = 'myactivities';
                        window.location = urlToPieces.slice(0, urlToPieces.length - 2).join('/');
                    }
                } else if(data.message == 'goNext' || data.message == 'goBack') {
                    try {
                        var data = JSON.parse(data);
                        $.each(data, function (key, value) {
                            //Specific stage
                            $.each(value, function (key, value) {
                                var stageKey = key;
                                $.each(value, function (key, value) {
                                    $.each(value, function (key, value) {
                                        var criterionKey = key;
                                        $.each(value, function (key, value) {
                                            $.each($('input'), function () {
                                                if ($(this).attr('name').indexOf('[activeStages][' + stageKey) != -1 && $(this).attr('name').indexOf('[criteria][' + criterionKey) != -1 && $(this).attr('name').indexOf(key) != -1) {
                                                    $(this).after('<div class="red-text"><strong>' + value + '</strong></div>');
                                                    return false;
                                                }
                                            })
                                        })
                                    })
                                })
                            })
                        })
                    }
                    catch (e) {
                        if ($('.red-text').length == 0) {
                            urlToPieces[urlToPieces.length - 1] = (data.message == 'goNext') ? 'participants' : 'stages';
                            window.location = urlToPieces.join('/');
                        }
                    }
                } else {
                    urlToPieces[urlToPieces.length - 1] = data.message;
                    window.location = urlToPieces.join('/');
                }



            })
            .fail(function (data) {
                console.log(data)
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
                });
            });



    });

    /*
    $('[disabled="disabled"]').each(function(){
        $(this).removeAttr('disabled');
    })*/



    // Get the ul that holds the collection of criteria
    $allCollectionsHolder = $('ul.criteria');

    // add the "add a stage" anchor and li to the tags ul

    /*
    $allCollectionsHolder.each(function(){
        //$(this).data('total',$(this).length);
        if($(this).children().length - 1 == 1){
            $(this).find('.weight').attr('disabled',true);
        }
    });
    */

    //var nbElmtschild = $('ul.criteria:eq(0)').data('total');

    // count the current stage form inputs we have (excluding li button add stage)
    //$collectionHolder.data('total', $collectionHolder.length);

    //Setup rules when modifying existing stages

    $(document).on('click', '.remove-criterion, .insert-criterion-btn', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        var crtElmts = $(this).closest('.stage').find('.criterion');
        var crtModal = $(this).closest('.modal');

        if ($(this).hasClass('insert-btn')) {
            if (crtElmts.length == 1) {
                crtElmts.find('.scale .input-field').removeClass('m4').addClass('m3');
            };
        } else {
            if (crtElmts.length == 2) {
                crtElmts.find('.scale .input-field').removeClass('m3').addClass('m4');
            };
        }

        // Get criteria collection
        $collectionHolder = $(this).prev();

        if ($(this).hasClass('remove-criterion')) {


            var removableElmt = ($(this).data('cid')) ? $('[data-cid="' + $(this).data('cid') + '"]') : $(this);
            var crtElmt = removableElmt.closest('.criterion');
            var stgElmt = removableElmt.closest('.stage');
            var crtIndex = stgElmt.find('.criterion').index(crtElmt);

            if (crtElmt.find('.weight-criterion-slider').length > 0) {

                var slider = crtElmt.find('.weight-criterion-slider');
                var oldValue = Number(slider[0].noUiSlider.get());
                var sliders = stgElmt.find('.weight-criterion-slider');
                var selectedSliderIndex = sliders.index(slider);
                var sumVal = 0;
                var k = 0;
                var newValue = 0;

                $.each(sliders, function (key, value) {
                    if (key != selectedSliderIndex) {
                        //$(this).off();
                        var nv = Math.round(Number(Number($(this)[0].noUiSlider.get()) * (100 - newValue) / (100 - oldValue)));

                        if (k == sliders.length - 2 && sumVal + nv + newValue != 100) {
                            nv = 100 - sumVal - newValue;
                        }

                        $(this)[0].nextElementSibling.innerHTML = nv + ' %';
                        $(this)[0].nextElementSibling.nextElementSibling.value = nv;
                        $(this)[0].noUiSlider.set(nv);
                        sumVal += nv;
                        k++;

                        let elmtInput = $(value).closest('.element-input');
                        let elmtData = elmtInput.prev();
                        elmtData.find('.cw').text(nv);
                    }
                })

            }

            if ($(this).data('cid')) {

                urlToPieces = dcurl.split('/');
                urlToPieces[urlToPieces.length - 1] = $(this).data('cid');
                url = urlToPieces.join('/');

                $.post(url, null)
                    .done(function (data) {
                        $('.modal').modal('close');
                        $('.modal-overlay').remove();
                        removableElmt.remove();
                    })
                    .fail(function (data) {
                        console.log(data)
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
                                        return false;
                                     }
                                });

                            });
                            }
                        });
                    });
            }

            /*
            var critWeight = Number(removableElmt.closest('.criterion').find('.grade-slider')[0].noUiSlider.get());
            stgElmt.find('.criterion').each(function(){
                $(this).find('.grade-slider')[0].noUiSlider.set(Number($(this).find('.grade-slider')[0].noUiSlider.get())/((100 - critWeight)/100));
            });
            */

            $collectionHolder.data('total', $collectionHolder.children().length - 2);
            var stageCrt = removableElmt.closest('.stage').find('.criterion');
            if (stageCrt.length == 2) {
                stageCrt.find('.weight').eq(0).prev().removeClass('m3 s6').addClass('m4 s12');
                stageCrt.find('.weight').eq(0).hide();
            }


            // Actualizing crtNb in current stage if removed crt is in between other criteria
            removableElmt.closest('.criterion').remove();

            if (crtIndex < $collectionHolder.data('total') - 1) {
                for (i = crtIndex; i < $collectionHolder.data('total') - 1; i++) {
                    $textArray = $collectionHolder.find('.criterion-title:eq(' + i + ')>h4').text().split(' ');
                    $textArray[$textArray.length - 1] = i + 1;
                    $collectionHolder.find('.criterion-title:eq(' + i + ')>h4').text($textArray.join(' '));
                }
            }



        } else if ($(this).hasClass('insert-criterion-btn')) {

            var totalNbCriteria = $collectionHolder.find('.criterion').length;
            /*
            if(!$collectionHolder.data('index')) {
                $collectionHolder.data('index', $(this).closest('ul.criteria').children().length - 1);
            }
            */
            // get the index
            //var index = $collectionHolder.data('index');
            // add a new criterion form
            addCriterionForm($collectionHolder, $(this), $('.stage').index($(this).closest('.stage'))/*selectedIndex*/);
            handleCNSelectElems();
            if (totalNbCriteria == 1) {
                $collectionHolder.find('.weight').show();
            }

            $newInsertedCrt = $('.stage').eq($('.stage').index($(this).closest('.stage'))).find('.criterion').eq(-1);

            $newInsertedCrt.find('.modal').not('[id*="criterionTarget"]').modal('open');

            // Copying values of preceding criterion into new one
            var $addedStageWeight = $collectionHolder.find('.weight-input:eq(' + totalNbCriteria + ')');
            $addedStageWeight.prev().addClass("active");
            $collectionHolder.find('.step:eq(' + totalNbCriteria + ')').prev().addClass("active");
            $collectionHolder.find('.lowerbound:eq(' + totalNbCriteria + ')').prev().addClass("active");
            $collectionHolder.find('.upperbound:eq(' + totalNbCriteria + ')').prev().addClass("active");
            $collectionHolder.find('.gradetype:eq(' + totalNbCriteria + ')').prev().addClass("active");
            $collectionHolder.find('.step:eq(' + totalNbCriteria + ')').val($collectionHolder.find('.step:eq(' + (totalNbCriteria - 1) + ')').val());
            $collectionHolder.find('.lowerbound:eq(' + totalNbCriteria + ')').val($collectionHolder.find('.lowerbound:eq(' + (totalNbCriteria - 1) + ')').val());
            $collectionHolder.find('.upperbound:eq(' + totalNbCriteria + ')').val($collectionHolder.find('.upperbound:eq(' + (totalNbCriteria - 1) + ')').val());
            $collectionHolder.find('.gradetype:eq(' + totalNbCriteria + ')').val($collectionHolder.find('.gradetype:eq(' + (totalNbCriteria - 1) + ')').val());
            $collectionHolder.find('.forceCommentValue:eq(' + totalNbCriteria + ')').val($collectionHolder.find('.forceCommentValue:eq(' + (totalNbCriteria - 1) + ')').val());
            $collectionHolder.find('.forceCommentSign:eq(' + totalNbCriteria + ')').val($collectionHolder.find('.forceCommentSign:eq(' + (totalNbCriteria - 1) + ')').val());
            $collectionHolder.find('.forceCommentCompare:eq(' + totalNbCriteria + ')')[0].checked = $collectionHolder.find('.forceCommentCompare:eq(' + (totalNbCriteria - 1) + ')')[0].checked;

            fillCriterionDataContent($newInsertedCrt);
        }
    });

    $(document).on('click', '.remove-stage, .insert-stage-btn', function (e) {

        $stageModal = $(this).closest('.modal');

        if ($(this).hasClass('remove-stage')) {

            $('.modal').modal('close');

            var removableElmt = ($(this).data('sid')) ?
                $('[data-sid="' + $(this).data('sid') + '"]').closest('.stage') :
                $(this).closest('.stage');

            if (removableElmt.find('.weight-stage-slider').length > 0) {

                var slider = removableElmt.find('.weight-stage-slider');
                var oldValue = Number(slider[0].noUiSlider.get());
                var sliders = $('.stage').find('.weight-stage-slider');
                var selectedSliderIndex = sliders.index(slider);
                var sumVal = 0;
                var k = 0;
                var newValue = 0;

                $.each(sliders, function (key, value) {
                    if (key != selectedSliderIndex) {
                        //$(this).off();
                        var nv = Math.round(Number(Number($(this)[0].noUiSlider.get()) * (100 - newValue) / (100 - oldValue)));

                        if (k == sliders.length - 2 && sumVal + nv + newValue != 100) {
                            nv = 100 - sumVal - newValue;
                        }

                        $(this)[0].nextElementSibling.innerHTML = nv + ' %';
                        $(this)[0].nextElementSibling.nextElementSibling.value = nv;
                        $(this)[0].noUiSlider.set(nv);
                        sumVal += nv;
                        k++;
                        $(this).closest('.stage-banner').find('.stage-weight').empty().text(nv +' %');
                    }
                })

            }

            if($(this).data('sid')){
                urlToPieces = dsurl.split('/');
                urlToPieces[urlToPieces.length-1] = $(this).data('sid');
                tempUrl = urlToPieces.join('/');

                $.post(tempUrl,null)
                    .done(function(data) {

                    })
                    .fail(function (data){
                        console.log(data)
                    })
            }

            //updateDatepickers(-1,index);
            // get stage weight and remove the element
            //var stgWeight = Number(removableElmt.find('.weight-stage-slider')[0].noUiSlider.get());
            //var prevWSlider = removableElmt.prev().find('.weight-stage-slider')[0].noUiSlider;
            //prevWSlider.set(parseInt(Number(prevWSlider.get())+stgWeight));
            if($('.stage').length == 2){
                $('.stage').find('.weight').hide();
                $('.stage').find('.weight').hide();
                $('.stage').find('a[href="#deleteStage"]').remove();
            }
            removableElmt.remove();


        } else {
            addStageForm();
            handleCNSelectElems();
            $('.stage').eq(-1).find('.modal').modal('open');
        }
    });

    function addStageForm() {
        var prototype =  $stageCollectionHolder.data('prototype');
        var newForm = prototype
            .replace(/__name__/g, $('.stage').length + 1);

        var $newFormLi = $(newForm);
        $newFormLi.find('.collapsible').collapsible();
        $newFormLi.find('.tooltipped').tooltip();
        $newFormLi.find('.not-collapse').on('click',function(){
            if($(this).hasClass('blue')){
                stgName = $(this).closest('.stage').find('input[name*="name"]').val();
                $(this).closest('.stage').find('.stage-name').empty().text(stgName);
                $(this).closest('.element-input').hide().prev().show();

            } else {
                $(this).closest('.element-data').hide().next().show();
            }
        });

        $('.insert-stage-btn').before($newFormLi);

        $stages = $('.stage');

        if ($stages.length > 1) {
            $stages.find('.stage-banner .weight').show();
            $stages.find('.stage-banner .weight-input').hide();
            $stages.find('input[name*="name"]').val('Phase '+ $stages.length)
        } else {
            $stages.find('.stage-banner .weight').hide();
            $stages.find('input[name*="name"]').val($('.activity-name').text())
            $stages.find('input[name*="name"]').prev().addClass('active');
            /*setTimeout(function (){
                $stages.find('input[name*="name"]').click();
            },2000)*/

        }

        var slider = $newFormLi.find('.weight-stage-slider');
        var weight = $newFormLi.find('.weight');

        //Removing '%' text added by PercentType
        weight[0].removeChild(weight[0].lastChild);

        var creationVal = Math.round(100 / $stages.length);

        var sliders = $stages.find('.weight-stage-slider.noUi-target');
        var sumVal = 0;


        // Initializing stage default values


        var startDate = new Date(Date.now());
        var endDate = new Date(Date.now() + 15 * 24 * 60 * 60 * 1000);
        var gStartDate = new Date(Date.now());
        var gEndDate = new Date(Date.now() + 30 * 24 * 60 * 60 * 1000);

        $newFormLi.find('.dp-start, .dp-end, .dp-gstart, .dp-gend').each(function() {
            $(this).pickadate();
        });

        $newFormLi.find('.dp-start').pickadate('picker').set('select',startDate);
        $newFormLi.find('.dp-end').pickadate('picker').set('select',endDate).set('min',startDate);
        $newFormLi.find('.dp-gstart').pickadate('picker').set('select',gStartDate).set('min',startDate);
        $newFormLi.find('.dp-gend').pickadate('picker').set('select',gEndDate).set('min',gStartDate);

        $newFormLi.find('input[name*="period"]').val(15);
        $newFormLi.find('select[name*="frequency"]').val('D');
        $newFormLi.find('[name*="mode"]').eq(0).prop('checked',true);

        $.each(sliders, function (key, value) {

            var nv = Math.round(Number($(this)[0].noUiSlider.get()) * ($stages.length - 1) / $stages.length);
            if (nv == 21) {
                nv = 20;
            }
            if (nv == 26) {
                nv = 25;
            }
            $(this)[0].noUiSlider.set(nv);
            $(this)[0].nextElementSibling.innerHTML = nv + ' %';
            $(this)[0].nextElementSibling.nextElementSibling.value = nv;
            sumVal += nv;

            $(this).closest('.element-input').prev().find('.cw').empty().append(nv);
            $(this).closest('.stage-banner').find('.stage-weight').empty().text(nv +' %');

        });

        if (Math.round(100 / $stages.length) != 100 / $stages.length) {
            creationVal = 100 - sumVal;
        }

        noUiSlider.create(slider[0], {
            start: creationVal,
            step: 1,
            connect: [true, false],
            range: {
                'min': 0,
                'max': 100,
            },
        });

        slider[0].nextElementSibling.innerHTML = creationVal + ' %';
        slider[0].nextElementSibling.nextElementSibling.value = creationVal;



        slider[0].noUiSlider.on('slide', function (values, handle) {

            var sliders = $('.weight-stage-slider');

            var newValue = Number(values[handle]);
            var oldValue = Number(slider[0].nextElementSibling.nextElementSibling.value);
            slider[0].nextElementSibling.innerHTML = Number(values[handle]) + ' %';
            slider[0].nextElementSibling.nextElementSibling.value = values[handle];

            var sumVal = 0;
            var k = 0;
            var selectedSliderIndex = sliders.index(slider);

            $.each(sliders, function (key, value) {
                if (key != selectedSliderIndex && oldValue != newValue) {
                    //$(this).off();
                    var nv = Math.round(Number(Number($(this)[0].noUiSlider.get()) * (100 - newValue) / (100 - oldValue)));


                    if (k == sliders.length - 2 && sumVal + nv + newValue != 100) {
                        nv = 100 - sumVal - newValue;
                    }

                    $(this)[0].nextElementSibling.innerHTML = nv + ' %';
                    $(this)[0].nextElementSibling.nextElementSibling.value = nv;
                    $(this)[0].noUiSlider.set(nv);
                    k++;
                    sumVal += nv;
                }
            })

            $('.total-percentage>span').empty();
            var totalPct = 0;
            $('input[type="text"]').each(function () {
                totalPct += Number($(this).val());
            });
            (totalPct > 100) ? $('.total-percentage>span').addClass('red-text') : $('.total-percentage>span').removeClass('red-text');
            $('.total-percentage>span').append(totalPct + ' %');
        });

        slider.next().next().hide();
        $newFormLi.find('.modal').modal({
            complete: function(){
                inputName = $newFormLi.find('input[name*="name"]').val();
                weightVal = $newFormLi.find('input[name*="weight"]').val();
                isDefiniteDates = $newFormLi.find('[name*="definiteDates"]').is(':checked');
                startdate = $newFormLi.find('[name*="startdate"]').val();
                enddate = $newFormLi.find('[name*="enddate"]').val();
                gstartdate = $newFormLi.find('[name*="gstardate"]').val();
                genddate = $newFormLi.find('[name*="genddate"]').val();
                period = $newFormLi.find('[name*="period"]').val();
                frequency = $newFormLi.find('select[name*="frequency"] option:selected').val();
                visibility = $newFormLi.find('select[name*="visibility"] option:selected').val();
                $closestStageBanner = $newFormLi.find('.stage-banner');
                $closestStageBanner.find('.stage-visibility').empty();
                $closestStageBanner.find('.stage-name').empty().append(inputName);
                $closestStageBanner.find('.stage-dates span').text(startdate +' - '+ enddate);
                $closestStageBanner.find('.stage-period-freq span').text(period +' '+ frequency);
                isDefiniteDates ? ($closestStageBanner.find('.stage-dates').show(), $closestStageBanner.find('.stage-period-freq').hide()) : ($closestStageBanner.find('.stage-dates').hide(), $closestStageBanner.find('.stage-period-freq').show());
                visibility == 1 ? $closestStageBanner.find('.stage-visibility').append('<i class="fa fa-lock-open"></i>') : visibility == 2 ? $closestStageBanner.find('.stage-visibility').append('<i class="fa fa-unlock"></i>') : $closestStageBanner.find('.stage-visibility').append('<i class="fa fa-lock"></i>');
                $closestStageBanner.find('.element-data').show();
                }
        });

    }

    function addCriterionForm($collectionHolder, $newLinkLi, index) {
        // Get the data-prototype explained earlier

        var prototype = $newLinkLi.prev().data('prototype');
        var selectedStageCrit = $('.stage').eq(index).find('.criterion');
        var total = $('.stage').eq(index).find('ul.criteria').length;
        //var total = Math.min(1, $collectionHolder.data('total'));
        //count++;
        // Replacing prototype constants

        var newForm = prototype
            .replace(/__nb__/g, selectedStageCrit.length + 1)
            .replace(/__name__/g, selectedStageCrit.length)
            .replace(/__DeleteButton__/g, '<i class="small remove-criterion material-icons" style="color: red">cancel</i>')
            .replace(/__stgNb__/g, index)
            .replace(/__crtNb__/g, selectedStageCrit.length);


        var $newFormLi = $(newForm);

        if (selectedStageCrit.length > 0) {
            $newFormLi.find('.weight').prev().removeClass('m6 s12').addClass('m3 s6');
            $newFormLi.find('.weight').show();
        } else {
            $newFormLi.find('.weight').hide();
        }

        $newFormLi.find('.gradetype').css({
            'display' : 'flex',
            'justify-content' : 'space-between'
        });

        $newFormLi.find('.tooltipped').tooltip();
        $newFormLi.find('.modal').modal();

        // Setting default grading values
        $newFormLi.find('input[name*="lowerbound"]').val(0);
        $newFormLi.find('input[name*="upperbound"]').val(5);
        $newFormLi.find('input[name*="step"]').val(0.5);

        // Setting target inputs

        $newFormLi.find('[id*="criterionTarget"]').modal({
            complete: function(){
                let a = $(this).attr('id').split('_');
                let s = a[1];
                let c = a[2];
                let modC = $(this)[0].$el;
                if(modC.find('input[type="checkbox"]').is(':checked') || modC.find('textarea').val().trim() != ""){
                    $('[href="#criterionTarget_'+s+'_'+c+'"]').addClass('lime darken-3').empty().append($('<ul class="flex-center no-margin"><i class="far fa-dot-circle" style="margin-right:10px"></i>'+ modalModifyMsg+'<i class="fas fa-comment-dots" style="margin-left:10px"></i></ul>'));
                } else {
                    $('[href="#criterionTarget_'+s+'_'+c+'"]').removeClass('lime darken-3').empty().append($('<ul class="flex-center no-margin"><i class="far fa-dot-circle" style="margin-right:10px"></i>'+ modalSetMsg+' <i class="far fa-comment" style="margin-left:10px"></i></ul>'));
                }
            }
        });

        $newFormLi.find('.target').hide().find('[type="text"]').val(70).attr('disabled',true);
        $newFormLi.find('.target').parent().find('input[type="checkbox"]').on('change',function(){
            $(this).is(':checked') ? ($newFormLi.find('.target').show(),$newFormLi.find('[type="text"]').attr('disabled',false)) : ($newFormLi.find('.target').hide(),$newFormLi.find('[type="text"]').attr('disabled',true));
        })

        // Check new criterion as being an evaluation one (by default)
        $newFormLi.find('[id*="_type"]').eq(1)[0].checked = true;

        var $selectedCrtNameElmt = $newFormLi.find('select[id$="cnaId"]');
        if ($collectionHolder.data('total') != 1) {
            $selectedCrtNameElmt.empty();
            var biggestLengthCrtNameElement = 0;
            $('select[id$="cnaId"]').each(function (key, value) {
                biggestLengthCrtNameElement = ($(this).find('optgroup').length > biggestLengthCrtNameElement) ? key : biggestLengthCrtNameElement;
            });

            var optionsToCopy = $('select[id$="cnaId"]').eq(biggestLengthCrtNameElement).find('optgroup').clone();
            $selectedCrtNameElmt.append(optionsToCopy);
        }

        var targetSlider = $newFormLi.find('.target-slider');

        $newFormLi.find('.target-slider').next().next().hide();

        //Removing '%' text added by Symfony PercentType
        $newFormLi.find('.target')[0].removeChild($newFormLi.find('.target')[0].lastChild);
        let initValue = $newFormLi.find('.target').find('[type="text"]').val();

        noUiSlider.create(targetSlider[0], {
            start: initValue,
            step: 1,
            connect: [true, false],
            range: {
                'min': 0,
                'max': 100,
            },
        });

        targetSlider[0].nextElementSibling.innerHTML = initValue + ' %';
        targetSlider[0].nextElementSibling.nextElementSibling.value = initValue;

        targetSlider[0].noUiSlider.on('slide', function (values, handle) {

            targetSlider[0].nextElementSibling.innerHTML = Number(values[handle]) + ' %';
            targetSlider[0].nextElementSibling.nextElementSibling.value = values[handle];

        });

        var slider = $newFormLi.find('.weight-criterion-slider');
        var weight = $newFormLi.find('.weight');

        //Removing '%' text added by PercentType
        weight[0].removeChild(weight[0].lastChild);

        $collectionHolder.append($newFormLi);

        //Get new criteria objects after insertion
        var relatedCriteria = $('.stage').eq(index).find('.criterion');

        var creationVal = Math.round(100 / relatedCriteria.length);

        var sliders = relatedCriteria.find('.weight-criterion-slider.noUi-target');
        var sumVal = 0;

        $.each(sliders, function (key, value) {

            var nv = Math.round(Number($(this)[0].noUiSlider.get()) * (relatedCriteria.length - 1) / relatedCriteria.length);
            if (nv == 21) {
                nv = 20;
            }
            if (nv == 26) {
                nv = 25;
            }
            $(this)[0].noUiSlider.set(nv);
            $(this)[0].nextElementSibling.innerHTML = nv + ' %';
            $(this)[0].nextElementSibling.nextElementSibling.value = nv;
            sumVal += nv;

            $(this).closest('.element-input').prev().find('.cw').empty().append(nv);

        });

        if (Math.round(100 / relatedCriteria.length) != 100 / relatedCriteria.length) {
            creationVal = 100 - sumVal;
        }

        noUiSlider.create(slider[0], {
            start: creationVal,
            step: 1,
            connect: [true, false],
            range: {
                'min': 0,
                'max': 100,
            },
        });

        slider[0].nextElementSibling.innerHTML = creationVal + ' %';
        slider[0].nextElementSibling.nextElementSibling.value = creationVal;

        slider[0].noUiSlider.on('slide', function (values, handle) {

            var sliders = slider.closest('.stage').find('.weight-criterion-slider');
            //Remove the error msg
            $('.weight-error').parent().empty();
            var newValue = Number(values[handle]);
            var oldValue = Number(slider[0].nextElementSibling.nextElementSibling.value);
            slider[0].nextElementSibling.innerHTML = Number(values[handle]) + ' %';
            slider[0].nextElementSibling.nextElementSibling.value = values[handle];

            var sumVal = 0;
            var k = 0;
            var selectedSliderIndex = sliders.index(slider);

            $.each(sliders, function (key, value) {
                if (key != selectedSliderIndex && oldValue != newValue) {
                    //$(this).off();
                    var nv = Math.round(Number(Number($(this)[0].noUiSlider.get()) * (100 - newValue) / (100 - oldValue)));


                    if (k == sliders.length - 2 && sumVal + nv + newValue != 100) {
                        nv = 100 - sumVal - newValue;
                    }

                    $(this)[0].nextElementSibling.innerHTML = nv + ' %';
                    $(this)[0].nextElementSibling.nextElementSibling.value = nv;
                    $(this)[0].noUiSlider.set(nv);
                    k++;
                    sumVal += nv;
                }
            })

            $('.total-percentage>span').empty();
            var totalPct = 0;
            $('input[type="text"]').each(function () {
                totalPct += Number($(this).val());
            });
            (totalPct > 100) ? $('.total-percentage>span').addClass('red-text') : $('.total-percentage>span').removeClass('red-text');
            $('.total-percentage>span').append(totalPct + ' %');
        });

        slider.next().next().hide();
    }

    $('.activity-element-save, .activity-element-update').on('click',function(e){
        e.preventDefault();
        $('.element-input').show();
        wgtElmts = [];
        $('.element-input').each(function(){
            if($(this).find('.weight .weight-input').is(':disabled')){
                wgtHiddenElmt = $(this).find('.weight .weight-input');
                wgtElmts.push(wgtHiddenElmt);
                wgtHiddenElmt.prop('disabled',false);
            }
        });

        $('[class*="dp-"]').each(function(){
            $(this).val($(this).pickadate('picker').get('select', 'dd/mm/yyyy'));
        });

        $('input[name="clicked-btn"]').attr("value", $(this).hasClass('activity-element-save') ? 'save' : 'update');
        $('[name="activity_element_form"]').submit();
        $('.element-input').hide();
        $.each(wgtElmts, function(){
            wgtHiddenElmt.prop('disabled',true);
        })
    })

/*
    $('.insert-survey-btn').on('click',function(e){
      e.preventDefault();
      id = spliturl(window.location.href);
      $.ajax({
        method: "POST",
        url: cpurl,
        data: {id: id},
        success: function(){
          alert(cpurl);
        }
      });
    });

    function spliturl(url) {
      console.log(url);
      var place;
      for (i=0;i<url.length;i++){
        if(url[i]=='/'){
          place=i;
        }
      }
      console.log(place);
      table=url.split("/");
      console.log(table);
      console.log(table.length-1);
      return table[table.lenght-1];
    }
 */
});
