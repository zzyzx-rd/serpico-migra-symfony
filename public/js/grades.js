let checkComments = [];
$(function () {

    setTimeout(function (){
        if($('#errors').length > 0){
            $('#errors').modal('open');
            $('#errors .modal-content ul').css('display','inline-block').addClass('no-margin');
        }
    },200)
    

    $('.comment-text').each(function(){
        $(this).html($(this).text().replace(/\r\n?|\n/g,'<br />'));
    });

    if(areSubmittedGrades){
        $('.grade-slider').each(function(key,slider){
            slider.setAttribute('disabled', true);
        })
    }


    var countDecimals = function (value) {
        if ((value % 1) != 0)
            return value.toString().split(".")[1].length;
        return 0;
    };

    $('.grade-input').hide();

    // Mananing binary grades

    $('.switch').next().find('input').each(function(key,input){
        if (input.value == 1){
            $(input).parent().prev().find('input').prop("checked",true);
        }
    })

    $('.switch input').each(function(key,value) {
        if($(this).is(':checked')){
            $(this).closest('.switch').next().find('input').val(1);
        } else {
            $(this).closest('.switch').next().find('input').val(0);
        }
    })

    $('.switch input').on('change',function(){
        if($(this).is(':checked')){
            $(this).closest('.switch').next().find('input').val(1);
        } else {
            $(this).closest('.switch').next().find('input').val(0);
        }
    })

    $('.grade-slider').each(function(key,value) {
        noUiSlider.create(value, {
            start: value.dataset.value,
            step: ($('.contributive').length == 0) ? Number(value.dataset.step) : 1,
            connect: [true, false],
            range: {
                'min': ($('.contributive').length == 0) ? Number(value.dataset.lb) : 0,
                'max': ($('.contributive').length == 0) ? Number(value.dataset.ub) : 100,
            },
        })
    });

    var update = false;

    $('.grade-slider').each(function(key,value) {

        value.noUiSlider.on('update', function(values,handle) {

            value.nextElementSibling.innerHTML = ($('.contributive').length == 0) ? Number(values[handle]).toFixed(countDecimals(Number(value.dataset.step))) : Number(values[handle])+' %';
            $(value).next().next().val(values[handle]);
            if($('.contributive').length > 0){
                $('.total-percentage>span').empty();
                var totalPct = 0;
                $('input[type="text"]').each(function(){
                    totalPct += Number($(this).val());
                });
                (totalPct > 100) ? $('.total-percentage>span').addClass('red-text') : $('.total-percentage>span').removeClass('red-text');
                $('.total-percentage>span').append(totalPct + ' %');
            }
        })
    });


    $('.modal').modal({
        dismissible:true,
        complete: function (){
            var textCommentArea = $('#'+$(this).attr("id")+' textarea').val();
            var relatedCommentButton = ($(window).width()<800) ? $('i[href="#'+ $(this).attr("id")+'"]') : $('a[href="#'+ $(this).attr("id")+'"]');

            if (textCommentArea != "" && (relatedCommentButton.hasClass('lighten-2') || relatedCommentButton.find('.fa-comment').length > 0) || textCommentArea == "" && (relatedCommentButton.hasClass('darken-4') || relatedCommentButton.find('.fa-comment-dots').length > 0)) {
                updateButton(relatedCommentButton);
            }
        }
    });

    if($(window).width() < 700) {
        $('.mobile-element').show();
        $('.comment-button, .precomment-button').hide();
        if($('.action-buttons a').length == 3){
            $('.action-buttons a').eq(0).empty().append('<i class="fa fa-save">');
            $('.action-buttons a').eq(1).empty().append('<i class="fa fa-briefcase">');
            $('.action-buttons a').eq(2).empty().append('<i class="fa fa-gavel">');
        } else {
            $('.action-buttons a').eq(1).empty().append('<i class="fa fa-briefcase">');
        }

    } else {
        $('.mobile-element').hide();
        $('.comment-button, .precomment-button').show();

    }


    $(window).resize(function() {
        ($(window).width() < 900) ? $('.row').find('span:not(:first-child)').hide() : $('.row').find('span:not(:first-child)').show();
    });

    if(status != 3){

        function roundToFour(num) {
            return +(Math.round(num + "e+4")  + "e-4");
        }

        $('input[type="number"]').on("change paste keyup",function(){


            if(!(Number($(this).val()) || $(this).val()=="0")){
                $(this).val("");
            } else {
                var min = Number($(this).attr('min'));
                var max = Number($(this).attr('max'));
                var step = Number($(this).attr('step'));
                var typedValue = Number($(this).val());
                (typedValue > max) ? $(this).val(max) : (typedValue < min && $(this).val() != "") ? $(this).val(min) : $(this).val($(this).val());
                /* check if step multiple */
                var ratio = roundToFour((typedValue * 100)/(step * 100));
                var spread = (ratio - parseInt(ratio)) * step;
                (parseInt(ratio) != roundToFour(ratio)) ? $(this).val(typedValue - spread) : $(this).val($(this).val());
            }
            canValidate = true,
                $.each($('[type=number]'), function(){
                    if($(this).val()==""){
                        canValidate=false;
                        return false;
                    }
                });
            if (canValidate){
                $('.validate-button').removeClass('disabled');
            } else {
                $('.validate-button').addClass('disabled');
            }
        });


        function updateButton(button){

            if($(window).width() < 800){
                (button.hasClass('fa-comment-dots')) ? (button.children().remove(), button.removeClass('fas fa-comment-dots').addClass('far fa-comment'), button.append('<i class="fa fa-plus"></i>')) : (button.children().remove(), button.removeClass('far fa-comment').addClass('fas fa-comment-dots').append('<div class="white-panel"></div>')) ;

            } else {
                (button.hasClass('lime darken-4')) ? button.removeClass('lime darken-4') : button.addClass('lime darken-4');
                (button.find('i').hasClass('fa-comment')) ? button.find('i').removeClass('far fa-comment').addClass('fa fa-comment-dots') : button.find('i').removeClass('fa fa-comment-dots').addClass('far fa-comment');


                /* if(lg == 'fr'){
                    (button.find('span').text() == 'Commenter') ? button.find('span').text('Modifier') : button.find('span').text('Commenter');
                } else if (lg == 'en'){
                    (button.find('span').text() == 'Comment') ? button.find('span').text('Modify') : button.find('span').text('Comment');
                } */
            }
        }
    }

    $('.number-input button').on('click', function(e){
        e.preventDefault();
    });

    $('.save-button, .submit-button').on('click',function(e){
        // if a comment button is red the user cannot send or save the data
  /*       $('input[type="text"]').each(function (key) {
            if($(this).parent().next().next().children("a").hasClass("red")) {
                // write a proper notification watch out for a red comment button, green ones are ok
                window.alert("please comment");
                isFormValid = false;
                return false;
            }
        }); */
        e.preventDefault();
        $('.red-text').remove();
        var elmt = $(this);
        var url = elmt.hasClass('save-button') ? '../../myactivities/save' : '../../myactivities/confirm';
        if($('.contributive').length > 0){

            // TODO : update this coding part

            switch(lg){
                case 'fr' :
                    var msg = 'La somme des contributions individuelles des participants doit être égale à 100%';
                    break;
                case 'en' :
                    var msg = 'Sum of individual contributions should be equal to 100%';
                    break;
                case 'es' :
                    var msg = 'Sum of individual contributions should be equal to 100%';
                    break;
                case 'pt' :
                    var msg = 'Sum of individual contributions should be equal to 100%';
                    break;
                default :
                    break;
            }
            var totalPct = 0;
            $('input[type="text"]').each(function(key,value){
                totalPct += Number(value.value);
                console.log(totalPct);
            });
            
            if(totalPct != 100){
                if(elmt.hasClass('submit-button')){
                    $('#validateGrades').modal('close');
                }
                $('.save-button').after('<div class="red-text" style="margin-top: 15px;">'+msg+'</div>');
            } else {
                    $('#gradeForm').attr('action',url);
                    $('#gradeForm').submit();
            }
        } else {
            //$('#gradeForm').attr('action',url);
            if(checkComments.length > 0) {
                $(".validate-button").addClass("disabled");
            }
            else if(checkComments.length == 0){
                $(".validate-button").removeClass("disabled");
            }
            //$('#gradeForm').submit();
            $.post(url, $('#gradeForm').serialize())
                .done(function(data){
                    window.location = data.redirect
                })
                .fail(function(data){
                    $('.crtName').empty();
                    $('.crtThreshold').empty();
                    $('.crtSign').empty();
                    if(data.responseJSON.message == 'missingComment'){
                        $('.crtName').append('"'+data.responseJSON.crtName+'"');
                        $('.crtSign').append(data.responseJSON.crtSign);
                        $('.crtThreshold').append(data.responseJSON.crtThreshold);
                        $('#notifyUncompleteForm').modal('open')
                    }
                })
        }
    });


    /*
    $('.notifyModalClose').on('click', function(e) {

        $("#notifyUncompleteForm").modal('close');
    });

    

    $('.submit-comment').on('click', function(e) {

        switch (lg) {
            case 'fr':
                var msg = 'Commenter';
                var msg2 = "modifier"
                break;
            case 'en':
                var msg = 'Comment';
                var msg2 = "modify"
                break;
            default:
                break;
        }

        let save = $(this).parent().parent().children(".modal-content").children(".textarea-broaden").val();
        let name = $(this).parent().parent().children(".modal-content").children(".textarea-broaden").attr("name");
        let nameArray = name.split("_");
        let criteriaId = nameArray[0];

        if(save.match(/[a-z]/i)) {
            $("[href='" + "#" + name + "']").hasClass("red") ?   $("[href='" + "#" + name + "']").removeClass("red") : null;
            $("[href='" + "#" + name + "']").addClass("isCommented");
            $("[href='" + "#" + name + "']").addClass("darken-4");
            $("[href='" + "#" + name + "']").children(".fa").removeClass("fa-comment");
            $("[href='" + "#" + name + "']").children(".fa").addClass("fa-comment-dots");
            $("[href='" + "#" + name + "']").children("span").text(msg2);
        }
        else{
            $("[href='" + "#" + name + "']").removeClass("isCommented");
            $("[href='" + "#" + name + "']").removeClass("darken-4");
            $("[href='" + "#" + name + "']").addClass("lighten-2");
            $("[href='" + "#" + name + "']").children(".fa").removeClass("fa-comment-dots");
            $("[href='" + "#" + name + "']").children(".fa").addClass("fa-comment");
            $("[href='" + "#" + name + "']").children("span").text(msg);
        }
        
        $("#"+"slider_"+name).children(".noUi-base").children(".noUi-origin").children(".noUi-handle").trigger('DOMSubtreeModified');
    });


    $(".grade-slider").on("DOMSubtreeModified", function (e) {
         let gradeValue;
        let graderInputName;
        let graderInputArray ;
        gradeValue = $(this).parent().children(".grade-input").val();
        graderInputName = $(this).parent().children(".grade-input").attr("name");
        graderInputArray = graderInputName.split("_");
         let criteriaId = graderInputArray[0];
         let commentId = graderInputArray[3];
         let isCommented = $(this).parent().parent().children(".comment-element").children("a").hasClass("isCommented");

        let isBelow = isGradeBelowForceCommentValue(gradeValue, criteriaId, commentId, isCommented);
        if(!isBelow) {

            $(this).parent().parent().children(".comment-element").children("a").removeClass("red");
            $(this).parent().parent().children(".comment-element").children("a").addClass("blue");
        } else if ($(this).parent().parent().children(".comment-element").children("a").hasClass("isCommented")) {
            $(this).parent().parent().children(".comment-element").children("a").removeClass("red");

            $(this).parent().parent().children(".comment-element").children("a").addClass("blue");
            
        }  else if(isBelow){
            $(this).parent().parent().children(".comment-element").children("a").removeClass("blue");
            $(this).parent().parent().children(".comment-element").children("a").addClass("red");
        }
    });

    function isGradeBelowForceCommentValue(gradeValue, criteriaId, commentId, isCommented) {

        let returnValue;
        let arrayValue = criteriaId + "_" + commentId;

        forceCommentObject = forceCommentValuesByCriteria.find(o => o.id ==criteriaId);
        forceCommentValueExists = forceCommentObject.id;
        forceCommentSign = forceCommentObject.forceCommentSign;
        forceCommentValue = forceCommentObject.forceCommentValue;
       
        
        if (forceCommentValueExists && forceCommentValue !== null){
             if(forceCommentSign == "smaller")  {
                 if (gradeValue <= forceCommentValue  && isCommented == false) {
                     if(!checkComments.includes(arrayValue)) {
                        checkComments.push(arrayValue);
                    }
                    returnValue = true;
                }
                else {
                    let position = checkComments.indexOf(arrayValue);
                    if(position > -1) {

                        checkComments.splice(position, 1);
                    }
                    returnValue =  false;
                }
            }
            else if(forceCommentSign == "smallerEqual"){
                if (gradeValue < forceCommentValue  && isCommented == false) {
                    if(!checkComments.includes(arrayValue)) {
                        checkComments.push(arrayValue);
                    }
                    returnValue = true;
                }
                else {
                    let position = checkComments.indexOf(arrayValue);
                    checkComments.splice(position, 1);
                    returnValue =  false;
                }
            }

            
        }
         if(checkComments.length > 0) {
                $(".validate-button").attr("href", "#notifyUncompleteForm");
        } 
        else if(checkComments.length == 0){
            $(".validate-button").attr("href", "#validateGrades");
        }
        return returnValue;
    }

        $("textarea").each(function (key, value) {


        switch (lg) {
            case "fr":
                var msg = "Commenter";
                var msg2 = "modifier";
                break;
            case "en":
                var msg = "Comment";
                var msg2 = "modify";
                break;
            default:
                break;
        }

        let textareaName = $(this).attr("name");
        textareaName = "#" + textareaName;

        if ($(this).val().match(/[a-z]/i)) {
           // $("[href='" + textareaName + "']").hasClass("red") ? $(this).removeClass("red") : null;
            $("[href='" + textareaName + "']").addClass("isCommented");
            $("[href='" + textareaName + "']").addClass("darken-4");
            $("[href='" + textareaName + "']").children(".fa").removeClass("fa-comment");
            $("[href='" + textareaName + "']").children(".fa").addClass("fa-comment-dots");
            $("[href='" + textareaName + "']").children("span").text(msg2);
        }
    });

    $('input[type="text"]').each(function (key, value) {

        let graderInputName = $(this).attr("name");
        let gradeValue = $(this).val();
        let graderInputArray = graderInputName.split("_");
        let criteriaId = graderInputArray[0];
        let commentId = graderInputArray[3];
        graderInputArray.splice(2, 1,"comment");
        graderInputName = graderInputArray.join("_");
        graderInputName = "#" + graderInputName;
        let isCommented = $(this).parent().parent().children(".comment-element").children("a").hasClass("isCommented");
        
        
        let isBelow = isGradeBelowForceCommentValue(gradeValue, criteriaId, commentId, isCommented);

        if (!isBelow) {
            $(this).parent().parent().children(".comment-element").children("a").removeClass("red");
            $(this).parent().parent().children(".comment-element").children("a").addClass("blue");
        } else if(isBelow  && !isCommented) {
            $(this).parent().parent().children(".comment-element").children("a").removeClass("blue");

            $(this).parent().parent().children(".comment-element").children("a").addClass("red");
        } 
        else {
            $(this).parent().parent().children(".comment-element").children("a").removeClass("red");
            $(this).parent().parent().children(".comment-element").children("a").addClass("blue");
        }
        
    });


    $()
    */



});