var msgBtn = '';
switch (lg) {
    case 'fr':
        msgBtn = 'Ajouter un critère';
        break;
    case 'en':
        msgBtn = 'Add a criterion';
        break;
    case 'pt':
        msgBtn = 'Acrescentar um critério';
        break;
    case 'es':
        msgBtn = 'Añadir un criterio';
        break;
}

var $addCriterionLink = $('<a href="#" class="waves-effect waves-light btn insert-btn">' + msgBtn + '</a>');
var $newLinkLi = $('<li></li>').append($addCriterionLink);

$(function () {
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

    // User has to chose whether to discard the criterion or go back
    $('.modal').modal({
        dismissible: false,
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

    $('.stage').each(function () {
        var stageCrit = $(this).find('.criterion');
        if (stageCrit.length == 1) {
            stageCrit.find('.scale .input-field').not(':last').removeClass('m3').addClass('m4');
            stageCrit.find('.weight').hide();
        } 
        if(!($('.stage').length == 1 && stageCrit.length == 1)){
            stageCrit.hide();
        }
    });

    $(document).on('click','a:has(.fa-check)',function(){
        let elmtInput = $(this).closest('.element-input');
        let elmtData = elmtInput.prev(); 
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

        elmtInput.hide();
        elmtData.show();
    }),

    $(document).on('click','a:has(.fa-pencil-alt)',function(){
        $(this).closest('.element-data').hide().next().show();
    }),

    $('[href="#deleteCriterion"]').on('click', function () {
        $('.remove-criterion').data('cid', $(this).data('cid'));
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



    $('.weight-criterion-slider').each(function (key, value) {
        var sliderValue = Number(value.noUiSlider.get());
        if (sliderValue != 100) {
            value.nextElementSibling.innerHTML = sliderValue + ' %';
            value.nextElementSibling.nextElementSibling.value = sliderValue;
        }
    });

    /*

   $('.noUi-handle').on('update',function(values,handle){

       if(Number(values[handle]) != 100){
           var modifiedSlider = $(this);
           var oldValue = Number($(this).nextElementSibling.nextElementSibling.value);

           value.nextElementSibling.innerHTML = Number(values[handle])+' %';
           value.nextElementSibling.nextElementSibling.value = values[handle];

           var relatedSliders = $(this).closest('.stage').find('.grade-slider');
           $.each(relatedSliders, function(){
               if($(this)[0].noUiSlider != modifiedSlider){
                   //$(this).off();
                   $(this)[0].noUiSlider.set(Number($(this)[0].noUiSlider.get()) * (oldValue / Number(values[handle]))).off();
               }
           })
       }

   })
   */




    $('.weight-criterion-slider').each(function (key, value) {




        value.noUiSlider.on('slide', function (values, handle) {


            var modifiedSlider = $(this);
            var newValue = Number(values[handle]);
            var oldValue = Number(value.nextElementSibling.nextElementSibling.value);

            //Remove the error msg
            $('.weight-error').parent().empty();
            var relatedCriteria = $(value.closest('.stage')).find('.criterion');

            value.nextElementSibling.innerHTML = Number(values[handle]) + ' %';
            value.nextElementSibling.nextElementSibling.value = values[handle];

            /*
            if(relatedCriteria.length == 2){
                var sliders = relatedCriteria.find('.grade-slider');
                if(sliders.eq(0)[0].noUiSlider.get() != 100 - parseInt(Number(sliders.eq(1)[0].noUiSlider.get()))){
                    if (key == 0){
                        sliders.eq(1)[0].noUiSlider.set(parseInt(100 - Number(sliders.eq(0)[0].noUiSlider.get())));
                    } else {
                        sliders.eq(0)[0].noUiSlider.set(parseInt(100 - Number(sliders.eq(1)[0].noUiSlider.get())));
                    }
                }
            }*/


            /*
            var sumVal = 0;
            var k = 0;
            var sliders = relatedCriteria.find('.weight-criterion-slider');
            var selectedSliderIndex = sliders.index($(value));

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
                    sumVal += nv;
                    k++;
                }
            })
            */



            $('.total-percentage>span').empty();
            var totalPct = 0;
            $('input[type="text"]').each(function () {
                totalPct += Number($(this).val());
            });
            (totalPct > 100) ? $('.total-percentage>span').addClass('red-text') : $('.total-percentage>span').removeClass('red-text');
            $('.total-percentage>span').append(totalPct + ' %');

        })
    });


    $('.weight-criterion-slider, .target-slider').next().next().hide();

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

            var crtElmt = $(this).closest('.criterion');

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
    $allCollectionsHolder.append($newLinkLi);

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

    $(document).on('click', '.remove-criterion, .insert-btn', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        var crtElmts = $(this).closest('.stage').find('.criterion');

        if ($(this).hasClass('insert-btn')) {
            if (crtElmts.length == 1) {
                crtElmts.find('.scale .input-field').removeClass('m4').addClass('m3');
            };
        } else {
            if (crtElmts.length == 2) {
                crtElmts.find('.scale .input-field').removeClass('m3').addClass('m4');
            };
        }

        $collectionHolder = $(this).closest('ul.criteria');

        if ($(this).hasClass('remove-criterion')) {



            /*
            if(!$collectionHolder.data('index')) {
                $collectionHolder.data('index', $(this).closest('ul.criteria').children().length - 1);
                var nbCriteria = $collectionHolder.data('index');
            }*/


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
                    }
                })

            }

            if ($(this).data('cid')) {

                var diffCrt = ($('#add_criterion_form_diffCriteria_0').is(':checked')) ? 0 : 1;
                urlToPieces = durl.split('/');
                urlToPieces[urlToPieces.length - 1] = diffCrt;
                urlToPieces[urlToPieces.length - 2] = $(this).data('cid');
                url = urlToPieces.join('/');

                $.post(url, null)
                    .done(function (data) {
                        $('.stage').each(function () {
                            $(this).find('.criterion').eq(crtIndex).remove()
                        })
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



        } else if ($(this).hasClass('insert-btn')) {

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
            if ($collectionHolder.data('total') == 2) {
                $collectionHolder.find('.weight-input:eq(0)').attr('disabled', false);
            }
            
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
        }



    });

    function addCriterionForm($collectionHolder, $newLinkLi, index) {
        // Get the data-prototype explained earlier

        var prototype = $collectionHolder.data('prototype');
        var selectedStageCrit = $('.stage').eq(index).find('.criterion');
        var total = $('.stage').eq(index).find('ul.criteria').length;
        //var total = Math.min(1, $collectionHolder.data('total'));
        count++;
        // Replacing prototype constants

        var newForm = prototype
            .replace(/__nb__/g, selectedStageCrit.length + 1)
            .replace(/__name__/g, selectedStageCrit.length)
            .replace(/__DeleteButton__/g, '<i class="small remove-criterion material-icons" style="color: red">cancel</i>')
            .replace(/__stgNb__/g, index)
            .replace(/__crtNb__/g, selectedStageCrit.length);
        //.replace(/__InsertButton__/g, '<a href="#" class="waves-effect waves-light btn insert-btn">Insert a criterion</a>');

        // increase the index with one for the next item
        //$collectionHolder.data('total', total + 1);


        if (selectedStageCrit.length == 1) {
            selectedStageCrit.find('.weight').prev().removeClass('m6 s12').addClass('m3 s6');
            selectedStageCrit.find('.weight').show();
        }

        var $newFormLi = $(newForm);

        $newFormLi.find('.gradetype').css({
            'display' : 'flex',
            'justify-content' : 'space-between'
        });

        $newFormLi.find('.tooltipped').tooltip();

        $newFormLi.find('.modal').modal({
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

        $newLinkLi.parent().before($newFormLi);

        //Get new criteria objects after insertion
        var relatedCriteria = $('.stage').eq(index).find('.criterion');

        var creationVal = Math.round(100 / relatedCriteria.length);

        var sliders = relatedCriteria.find('.weight-criterion-slider');
        var sumVal = 0;

        
        $.each(sliders, function (key, value) {



            if (key < relatedCriteria.length - 1) {
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
            }

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

        /*
        var totalPct = 0;
        selectedStageCrit.each(function(){
            totalPct += parseInt(Number($(this).find('.weight-input').val()));
        });


        var precedingStageVal = parseInt(Number(selectedStageCrit.find('.grade-slider').eq(-2)[0].noUiSlider.get()));

        selectedStageCrit.find('.grade-slider').eq(-2)[0].noUiSlider.set(Math.max(newVal,precedingStageVal-newVal));

        */
        /*let focusedElmt;
        $(document).find('select*[name*="[cName]"]').on('focus', function() {
            focusedElmt = $(this);
        }).change( function() {
            let selectElmts = $(this).closest('.stage').find('select*[name*="[cName]"]');
            selectElmts.find('option[disabled="disabled"]').removeAttr('disabled');
            selectElmts.find('option[value="'+focusedElmt.val()+'"]').not(focusedElmt).prop('disabled', true);
            console.log(selectElmts.find('option[value="'+focusedElmt.val()+'"]'));
        }); */



    }

});
