




$(function(){

    var $addOTPLink = $('<div class="button-field center-button"><a class="btn waves-effect waves-light insert-btn center-button"><i class="fa fa-plus-circle"></i><span>'+btnAddOTPText+'</span></a></div>');
    $collectionHolder = $('ul.externalUsers');
    $collectionHolder.each(function(){
        $(this).data('total',$(this).find('.oneTimeParticipant').length);
    })
    $collectionHolder.after($addOTPLink);

    $(document).on('click','.insert-btn',function(){
        $currentCollectionHolder = $(this).parent().parent().find('ul.externalUsers');
        // Replacing prototype constants
        var prototype = $currentCollectionHolder.data('prototype');
        var total = ($currentCollectionHolder.data('total') != null) ? $currentCollectionHolder.data('total') : 1;
        var newForm = prototype
            .replace(/__nb__/g,total+1)
            .replace(/__DeleteButton__/g, '<i class="small remove-stage material-icons" style="color: red; margin-left: auto">cancel</i>');
        //.replace(/__InsertButton__/g, '<a href="#" class="waves-effect waves-light btn insert-btn">Insert a stage</a>');



        // increase the index with one for the next item
        $collectionHolder.data('total', total + 1);

        if($('.stage').length == 1){
            $('.weight').prev().removeClass('s12').addClass('s8');
            $('.weight').show();
        }

        // Display the form in the page in an li, before the "Add a tag" link li
        var $newFormLi = $('<li class="oneTimeParticipant"></li>').append(newForm);

        //Insertion in between
        $(this).parent().before($newFormLi);
    })



    $('.modal').modal({
       dismissible:false,
       complete:function(){
            var textCommentArea = $(this).attr('$el').find('textarea').val();
            var addCommentButton = $('a[href="#' + $(this).attr("id") + '"], i[href="#' + $(this).attr("id") + '"]');
            if (textCommentArea != "" && ((!addCommentButton.hasClass('lime darken-3') && $(window).width() >= 800) || addCommentButton.hasClass('fa-map')) || textCommentArea == "" && (addCommentButton.hasClass('lime darken-3') || addCommentButton.hasClass('fa-map'))) {
                updateButton(addCommentButton);
            }
        }
    });




    //Remove page head title if mobile view
    if($(window).width() < 700){

        var partNameWidthPct = Math.round(23+($(window).width()-350)/5)+'%';

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
        $('.action-buttons a').eq(0).empty().append('<i class="fa fa-chevron-circle-left">');
        $('.action-buttons a').eq(1).empty().append('<i class="fa fa-briefcase">');
        $('.action-buttons a').eq(2).empty().append('<i class="fa fa-check-circle">');

        $('input+label').css('margin','0px');

        $('.fa-window-close, .fa-plus, i[class*="fa-map"]').show();
        $('.action-button, .precomment-button').hide();
        $('.participant-desktop').css('flex-direction','column');
        $('.participant-details').css({'width': partNameWidthPct,'margin-left':'10px'});

        $('[type="checkbox"] + label').css({'padding-left' : '25px', 'height' : '24px', 'line-height' : '24px', 'font-size' : '0.7rem', 'color' : 'white' });
        $('[id^="allParticipants"]+label').css({'color': 'black','font-size':'1rem','margin':'10px 10px 10px 5px'});
    } else {
        $('.fa-window-close, .fa-plus, i[class*="fa-map"]').hide(), $('.action-button, .precomment-button').show();
        $('.fixed-action-btn a').css({'height':'60px', 'width':'60px'});
        $('.btn-floating i').css({'line-height':'60px', 'font-size':'2.5rem'});
    }

    $('.tooltipped').tooltip();

    //update = $('#update').val();
    //console.log('update : ' + update);
    var modalButtonTitle ="";
    var modalTitle ="";
    var saveButton="";
    var clearButton="";
    var graded="";
    var leaderTooltip="";
    var gradedTooltip="";
    var simplyPassiveTooltip="";
    switch(lg){
        case 'fr' :
            graded = 'Evalué';
            leaderTooltip = "En cochant cette case, vous permettez à l'utilisateur de modifier les paramètres de l'activité";
            gradedTooltip = "A : actif (évalue et est évalué), T : tiers (évalue sans être évalué), P : passif (est évalué sans prendre part à l'évaluation)";
            simplyPassiveTooltip = "Est évalué sans prendre part à l'évaluation. S'il doit y prendre part, renseignez son adresse email.";
            break;
        case 'en' :
            graded = 'Graded';
            leaderTooltip = "By ticking this box, you enable this user to modify activity parameters";
            gradedTooltip = "A : active (grader who can be graded), T : third-party (grader who is not graded), P : passive (is graded but do not take part in the evaluation)";
            simplyPassiveTooltip = "Is graded but do not take part in the evaluation. If (s)he needs to grade, please insert his / her email adress.";
            break;
        case 'es' :
            graded =  'Graded';
            leaderTooltip = "By ticking this box, you enable this user to modify activity parameters";
            gradedTooltip = "A : active (grader who can be graded), T : third-party (grader who is not graded), P : passive (is graded but do not take part in the evaluation)";
            simplyPassiveTooltip = "Est évalué sans prendre part à l'évaluation. S'il doit y prendre part, renseignez son adresse email.";
            break;
        case 'pt' :
            graded = 'Graded';
            leaderTooltip = "By ticking this box, you enable this user to modify activity parameters";
            gradedTooltip = "A : active (grader who can be graded), T : third-party (grader who is not graded), P : passive (is graded but do not take part in the evaluation)";
            simplyPassiveTooltip = "Est évalué sans prendre part à l'évaluation. S'il doit y prendre part, renseignez son adresse email.";
            break;
        default :
            break;

    }

    $('#duplicitySelection').modal({
        dismissible: true,
        complete: function() {
            $('#duplicitySelection').find('span').empty();
        }
    });

    $('#waitingSpinner').modal({
        dismissible: true
    });

    var opts = {
        lines: 13, // The number of lines to draw
        length: 38, // The length of each line
        width: 17, // The line thickness
        radius: 45, // The radius of the inner circle
        scale: 1, // Scales overall size of the spinner
        corners: 1, // Corner roundness (0..1)
        color: '#26a69a', // CSS color or array of colors
        fadeColor: 'transparent', // CSS color or array of colors
        speed: 1, // Rounds per second
        rotate: 0, // The rotation offset
        animation: 'spinner-line-fade-quick', // The CSS animation name for the lines
        direction: 1, // 1: clockwise, -1: counterclockwise
        zIndex: 2e9, // The z-index (defaults to 2000000000)
        className: 'spinner', // The CSS class to assign to the spinner
        top: '50%', // Top position relative to parent
        left: '50%', // Left position relative to parent
        shadow: '0 0 1px transparent', // Box-shadow for the lines
        position: 'absolute' // Element positioning
        };

    (lg == 'fr') ?
        (
            modalButtonTitle = 'Précommenter',
            modalTitle = 'Précommentaire / Objectifs de ',
            saveButton="OK",
            clearButton="Effacer") :
        (
            modalButtonTitle = 'Precomment',
            modalTitle = 'Precomment / Goals of ',
            saveButton="Save",
            clearButton="Erase"
        );

    /*** Events ***/

    /* Select/unselect all participants or teams */

    $('[id^="allParticipants-"],[id^="allTeams-"], [id^="allExtParticipants-"]').on('click',function(){
        if($(window).width() < 800) {
            $(this).is(':checked') ? $(this).parent().find('.fa-plus').click() : $(this).parent().find('.fa-window-close').click();
        } else {
            $(this).parent().find('.action-button').click();
        }
    });

    /* For mobile, avoids collapsible padding restricting good display (sufficient width) */
    if($(window).width() < 800){
        $('.collapsible-body').find('.collapsible-body').addClass('no-padding');
    };


    /* Action on selecting participants */

    $('.action-button, .fa-plus, .fa-window-close').on("click", function(e){


        e.preventDefault();
        var nb_selected_participants = $('.added, .fa-window-close').length;
        if($(window).width() >= 800){
            if($(this).text() == "Add" || $(this).text() == "Ajouter"){
                ($(this).text()=="Add") ? $(this).text("Remove") : $(this).text("Retirer");
                if($(this).closest('.users-set').length > 0 || $(this).closest('#externalParticipants').length > 0){
                    setParticipantConfig($(this));
                } else if($(this).closest('.teams-set').length > 0) {
                    setTeamConfig($(this));
                }
            } else if ($(this).text() == "Remove" || $(this).text() == "Retirer"){
                /*Removing precomment button, checkbox and label */
                $(this).nextAll().remove();
                //$(this).next().remove();
                //$(this).next().remove();
                //$('#precomment-' + $(this).data('uid')).remove();
                ($(this).text()== "Remove") ? $(this).text("Add") : $(this).text("Ajouter");
                $(this).removeClass('red added');
                $(this).closest('ul').removeClass('blue cyan darken-4');
                $(this).closest('li').css('color','');
            }
        } else {
            if ($(this).hasClass('fa-plus')){
                $(this).removeClass('fa-plus').addClass('fa-window-close').css({'color':'red','font-size': '1.3rem','margin-left': '10px'}).append('<div class="white-panel"></div>');
                $(this).insertBefore($(this).closest('.user-content').find('li').first());  
                if($(this).closest('.users-set').length > 0 || $(this).closest('#externalParticipants').length > 0){
                    setParticipantConfig($(this));
                } else if($(this).closest('.teams-set').length > 0) {
                    setTeamConfig($(this));
                }
            } else if ($(this).hasClass('fa-window-close')){

                var plusElmt = $('<li class="participant-buttons"></li>');
                /*Removing swiper and comment bubble icons*/
                $(this)
                    .removeClass('added').removeClass('fa-window-close').addClass('fa-2x fa-plus').removeAttr('style').css('color','black')
                    .closest('.user-content').removeClass('blue cyan darken-4')
                    .find('.participant-desktop, .fa-map, .fa-map').remove()
                    .find('.participant-details').after(plusElmt);
                $(this).appendTo($(this).closest('.user-content').find('.participant-details').next());

                //$(this).next().remove();
                //$(this).next().remove();
                $(this).removeClass('added');
                $(this).closest('ul').removeClass('blue cyan darken-4');
                //$('#precomment-' + $(this).data('uid')).remove();
                $(this).children().remove();
                $(this).removeClass('fa-window-close').addClass('fa-plus').css('color','black');
            }
        }

        /*$('.attending-participants>span').each(function(){
            ($(this).closest('.users-set').length > 0) ? $(this).empty().append('('+$(this).closest('li').find('.user-content.blue.darken-4, .user-content.cyan.darken-4').length+')') : $(this).empty().append('('+$(this).closest('li').find('.team-content.blue.darken-4, .team-content.cyan.darken-4').length+')');
        });

        $('.attending-participants>span').each(function(){
            if($(this).closest('.users-set').length > 0){$(this).append(' ('+$(this).closest('li').find('.user-content.blue.darken-4, .user-content.cyan.darken-4').length+') ')};
            if($(this).closest('.teams-set').length > 0){$(this).append('('+$(this).closest('li').find('.team-content.blue.darken-4, .team-content.cyan.darken-4').length+') ')};
            if($(this).find('.fa').length > 0){
                if($(this).closest('li').find('.users-set, .teams-set').length){
                    $(this).append(' ('+$(this).closest('li').find('.users-set, .teams-set').find('.user-content.blue.darken-4, .user-content.cyan.darken-4').length+') ');
                } else {
                    $(this).append(' ('+$(this).closest('li').find('.user-content.blue.darken-4, .user-content.cyan.darken-4').length+') ');
                }
            };
        });
        */
        $(this).closest('.collapsible-body').prev().find('.attending-participants>span').empty().append(' ('+$(this).closest('.collapsible-body').find('.precomment-button').length+')');
        $(this).closest('.collapsible-body').prev().closest('.collapsible-body').prev().find('.attending-participants>span').empty().append(' ('+$(this).closest('.collapsible-body').prev().closest('.collapsible-body').find('.precomment-button').length+')');
        $(this).closest('.collapsible-body').prev().closest('.collapsible-body').prev().closest('.collapsible-body').prev().find('.attending-participants>span').empty().append(' ('+$(this).closest('.collapsible-body').prev().closest('.collapsible-body').prev().closest('.collapsible-body').find('.precomment-button').length+')');

    });

    //Check if there is at least a stage leader

    $(document).on('change', 'input[id^="uleader"]','input[id^="tleader"]', function() {
        //var nbChecked = 0;
        if ($(this).is(':checked')) {
            var thisElmt = $(this);

            $(this).closest('.stage').find('input[id^="uleader"], input[id^="tleader"]').each(function () {
                if (($(this).data('uid') != thisElmt.data('uid') || $(this).data('tid') != thisElmt.data('tid')) && $(this).prop('checked')) {
                    $(this).prop('checked', false);
                    $(this).closest('.user-content').removeClass('cyan').addClass('blue');
                    return false;
                }
            });
            $(this).closest('.user-content').removeClass('blue').addClass('cyan');
        } else {
            $(this).closest('.user-content').removeClass('cyan').addClass('blue');
        }
    });



    $(document).on('click', '.btn-clear', function() {
        $(this).parent().prev().find('textarea').val("");
    });

    /* Ajax subs */

    $('.prev-button,.next-button,.save-button,.enrich-button').on('click',function(e) {

        e.preventDefault();
        urlToPieces = url.split('/');

        if($(this).hasClass('prev-button')){
            urlToPieces[urlToPieces.length-1] = 'prev';
        } else if ($(this).hasClass('back-button')){
            urlToPieces[urlToPieces.length-1] = 'back';
        } else if ($(this).hasClass('save-button')){
            urlToPieces[urlToPieces.length-1] = 'save';
        } else if ($(this).hasClass('next-button')){
            urlToPieces[urlToPieces.length-1] = 'next';
        } else if ($(this).hasClass('enrich-button')){
            urlToPieces[urlToPieces.length-1] = 'enrich';
        }

        url = urlToPieces.join('/');

        var activity = {};
        var replicate = false;
        /*var isIncomplete = false;
        if($(this).hasClass('next-button'){
            $.each($('.stage'), function(){

                if($(this).find('.added, .fa-window-close').length < 2){
                    var isIncomplete = true;
                }

            })
        }*/

        if($(this).hasClass('save-button') || $(this).hasClass('next-button') || $(this).hasClass('enrich-button')){

            //if(isIncomplete == false) {

                //Creating Json
                var actElmts = {};
                var stages = [];
                $('.stage').each(function () {
                    var stgId = $(this).data("sid");
                    var stgElmts = {};
                    var participants = [];
                    //var parElmts = {};
                    var teams = [];
                    var users = [];
                    $(this).find('.added').each(function () {
                        if ($(this).data("uid")) {
                            var usrElmts = {};
                            usrElmts.usrId = $(this).data("uid");
                            usrElmts.uprecomment = $('textarea[name="uprecomment-' + stgId + '-' + $(this).data("uid") + '"]').val();
                            usrElmts.uleader = $(this).parent().find('input[id^="uleader"]').is(':checked') ? true : false;
                            usrElmts.utype = $(this).parent().find('select[id^="utype"]').val();
                            users.push(JSON.stringify(usrElmts));
                        } else {
                            var teaElmts = {};
                            teaElmts.teaId = $(this).data("tid");
                            teaElmts.tprecomment = $('textarea[name="tprecomment-' + stgId + '-' + $(this).data("tid") + '"]').val();
                            teaElmts.tleader = $(this).parent().find('input[id^="tleader"]').is(':checked') ? true : false;
                            teaElmts.ttype = $(this).parent().find('select[id^="ttype"]').val();
                            teams.push(JSON.stringify(teaElmts));
                        }
                    });
                    participants.push(users);
                    participants.push(teams);
                    stgElmts.stgId = stgId;
                    stgElmts.participants = participants;
                    stgElmts.mode = $(this).data("mode");
                    stages.push(JSON.stringify(stgElmts));
                });

                actElmts.stages = stages;
                activity = JSON.stringify(actElmts);
                replicate = $('[name="replicate"]').is(':checked');
            //}

        }

        $.ajax({
            url : url,
            data : {activity:activity, replicate:replicate, name: $('[name*="name"]').val()},
            method : 'POST',
            //dataType : 'json',
            success: function(data){

                if (data.message == 'missingLeader'){
                    $('#missingLeader').modal('open');
                } else if(data.message == 'missingParticipants'){

                    $('#missingParticipants').find('span').empty();
                    $('#missingParticipants').find('span').append(data.stageName);
                    $('#missingParticipants').modal('open');

                } else if(data.message == 'missingGradingParticipants'){
                    
                    $('#missingGradingParticipants').find('span').empty();
                    $('#missingGradingParticipants').find('span').append(data.stageName);
                    $('#missingGradingParticipants').modal('open');

                } else if (data.message == 'duplicityError') {

                    $('#duplicitySelection').find('span').empty();
                    $('#duplicitySelection').find('#sname').append(data.stageName);
                    $('#duplicitySelection').find('#dname').append(data.firstname+' '+data.lastname);
                    $('#duplicitySelection').modal('open');

                } else if (data.message == 'duplicityTeamError') {
                
                    $('#duplicityTeamSelection').find('span').empty();
                    $('#duplicityTeamSelection').find('#dname').append(data.firstname+' '+data.lastname);
                    $('#duplicityTeamSelection').find('#t1').append(data.team1);
                    $('#duplicityTeamSelection').find('#t2').append(data.team2);
                    $('#duplicityTeamSelection').find('#sname').append(data.stageName);
                    $('#duplicityTeamSelection').modal('open');
                
                } 

                var timeLag = 0;

                if(data.savedAsIncomplete == true){
                    $('#savedAsIncomplete').modal('open');
                    //var timeLag = 4500;
                }
                
                if(data.message == 'validate' || data.message == 'goBack') {
                    //window.setTimeout(function(){window.location = '../../myactivities';},timeLag);   
                    window.location = (window.location.pathname.indexOf('activity') > 0) ? '../../myactivities' : '../../settings/templates/manage';   
                } else if(data.message == 'goPrev'){
                    window.location = ($('.activity-elements>a').length > 3) ? './criteria' : './parameters';
                } else if(data.message == 'enrich'){
                    window.location = './stages';
                }
            },
        });
    });

    /*** Functions ***/


    function updateButton(button){
        if($(window).width() < 700){
            (button.hasClass('fa-map')) ? button.removeClass('fa-map lime-text text-darken-3').css('margin','0px').addClass('fa-map') : button.removeClass('fa-map').addClass('fa-map lime-text text-darken-3').css('margin','0px 2px');

        } else {
            (button.hasClass('lime darken-3')) ? button.removeClass('lime darken-3') : button.addClass('lime darken-3');
            if (lg == 'fr') {
                (button.text() == 'Précommenter') ? button.text('Précommenté') : button.text('Précommenter');
            } else if (lg == 'en') {
                (button.text() == 'Precomment') ? button.text('Precommented') : button.text('Precomment');
            }
        }
    }

    function setParticipantConfig(btn){

        var stgId = btn.closest('.stage').children(":first").data("stage-id");

        if($(window).width() < 700){
            btn.addClass('added');
            var participantOptionsContainer = $('<div class="participant-desktop" style="flex-direction: column"></div>');
            var leaderInput = (!btn.closest('.stage').find('input[id*="leader"]').is(':checked')) ? $('<input id="uleader-'+  stgId +'-'+ btn.data('uid') +'" type="checkbox" class="filled-in" checked="checked"/>') : $('<input id="uleader-'+ stgId +'-'+ btn.data('uid')+'" type="checkbox" class="filled-in"/>');
            var participantOptionsFixedContent = $(
                '<label for="uleader-'+ stgId +'-'+ btn.data('uid') +'" style="padding-left: 25px;height: 24px;line-height: 24px;font-size: 0.7rem;color: white;margin: 0px">Leader <i class="fa fa-question-circle tooltipped white-text" data-position="top" data-tooltip="'+leaderTooltip+'"></i></label>' +
                
                ((btn.closest('.stage').data('mode') != 0) ?
                    '<label for="utype-'+stgId +'-'+ btn.data('uid')+'" style="font-size:1rem;position:relative;display:inline-block;margin-right:5px;margin-top:auto;margin-bottom:auto;height:min-content"> Type'+
                    '    <i class="fa fa-question-circle tooltipped white-text" data-position="top" data-tooltip="'+(btn.data('email') === 1 ? gradedTooltip : simplyPassiveTooltip)+'"></i>'+
                    '</label>'+
                    '<select id="utype-'+stgId +'-'+ btn.data('uid')+'" class="black-text" style="display:block;position:relative;height:2rem;width:inherit;margin:auto;">'+
                    ((btn.data('email') == 1) ?
                        '    <option value="1" selected="selected">A</option>'+
                        '    <option value="0">T</option>' : '')
                    +'    <option value="-1">P</option>'
                    +'</select>'
                : '')
            );
            participantOptionsFixedContent.find('.tooltipped').tooltip();
            var addCommentButton = (btn.closest('.stage').data('mode') != 0) ? $('<i class="far fa-2x fa-map modal-trigger" href="#uprecomment-' +  stgId +'-'+ btn.data('uid') + '"></i>') : null;
            addElements = participantOptionsContainer.append(leaderInput).append(participantOptionsFixedContent).add(addCommentButton);

        } else {

            btn.addClass('red added');
            var participantOptionsContainer = $('<div class="participant-desktop"></div>');
            var leaderInput = (!btn.closest('.stage').find('input[id*="leader"]').is(':checked')) ? $('<input id="uleader-'+  stgId +'-'+ btn.data('uid') +'" type="checkbox" class="filled-in" checked="checked"/>') : $('<input id="uleader-'+ stgId +'-'+ btn.data('uid')+'" type="checkbox" class="filled-in"/>');
            var participantOptionsFixedContent = $(
                '<label for="uleader-'+ stgId +'-'+ btn.data('uid') +'">Leader <i class="fa fa-question-circle tooltipped white-text" data-position="top" data-tooltip="'+leaderTooltip+'"></i></label>' +
                
                ((btn.closest('.stage').data('mode') != 0) ?
                    '<label for="utype-'+stgId +'-'+ btn.data('uid')+'" style="font-size:1rem;position:relative;display:inline-block;margin-right:5px;margin-top:auto;margin-bottom:auto;height:min-content"> Type'+
                    '    <i class="fa fa-question-circle tooltipped white-text" data-position="top" data-tooltip="'+(btn.data('email') === 1 ? gradedTooltip : simplyPassiveTooltip)+'"></i>'+
                    '</label>'+
                    '<select id="utype-'+stgId +'-'+ btn.data('uid')+'" class="black-text" style="display:block;position:relative;height:2rem;width:inherit;margin:auto;">'+
                    ((btn.data('email') == 1 && btn.data('weight') != "") ?
                        '    <option value="1" selected="selected">A</option>'+
                        '    <option value="0">T</option>' : '')
                        +'    <option value="-1">P</option>'
                        +'</select>'
                : '')
                
            );
            participantOptionsFixedContent.find('.tooltipped').tooltip();
            var addCommentButton = (btn.closest('.stage').data('mode') != 0) ? $('<a class="precomment-button waves-effect waves-light btn modal-trigger smoothened-right-borders" href="#uprecomment-' +  stgId +'-'+ btn.data('uid') + '">' + modalButtonTitle + '</a>') : null;
            addElements = participantOptionsContainer.append(leaderInput).append(participantOptionsFixedContent).add(addCommentButton);

        }
        var addCommentModal = $('<div id="uprecomment-'+ stgId +'-'+btn.data('uid')+'" class="modal">\n' +
            '                <div class="modal-title">\n' +
            '                    <h5>'+ modalTitle + btn.closest('li').prev().find('.user-title').text().split(' ')[0] +'</h5>\n' +
            '                </div>\n' +
            '                <div class="modal-content">\n' +
            '                    <textarea class="textarea-broaden" name="uprecomment-'+ stgId +'-'+ btn.data('uid')+'"></textarea>\n' +
            '                </div>\n' +
            '                <div class="modal-footer">\n' +
            '                    <a class="btn btn-clear red waves-effect waves-light">'+ clearButton +'</a>\n' +
            '                    <a class="btn waves-effect waves-light modal-close">'+ saveButton +'</a>\n' +
            '                </div>\n' +
            '            </div>');
        addCommentModal.modal({
            dismissible: true,
            complete: function () {
                var textCommentArea = addCommentModal.find('textarea').val();
                if (textCommentArea != "" && ((!addCommentButton.hasClass('lime darken-3') && $(window).width() >= 700) || addCommentButton.hasClass('fa-map')) || textCommentArea == "" && (addCommentButton.hasClass('lime darken-3') || addCommentButton.hasClass('fa-map'))) {
                    updateButton(addCommentButton);
                }
            }
        });

        if($(window).width() < 700) {
            btn.closest('.user-content').find('.button-field').append(addElements);
        } else {
            btn.after(addElements);
            btn.closest('li').css('color','white');
        }

        $('.next-button').after(addCommentModal);

        /* If currentuser is participant, then is master user by default with appropriate bg color */
        (btn.data('uid') == usrId && btn.parent().find('input[id*="leader"]').is(':checked')) ? btn.closest('ul').addClass('cyan darken-4') : btn.closest('ul').addClass('blue darken-4');

    }

    function setTeamConfig(btn){

        var stgId = btn.closest('.stage').children(":first").data("stage-id");

        if($(window).width() < 700){
            btn.addClass('added');
            var participantOptionsContainer = $('<div class="participant-desktop" style="flex-direction: column"></div>');
            var leaderInput = (!btn.closest('.stage').find('input[id*="leader"]').is(':checked')) ? $('<input id="tleader-'+  stgId +'-'+ btn.data('tid') +'" type="checkbox" class="filled-in" checked="checked"/>') : $('<input id="tleader-'+ stgId +'-'+ btn.data('tid')+'" type="checkbox" class="filled-in"/>');
            var participantOptionsFixedContent = $(
                '<label for="tleader-'+ stgId +'-'+ btn.data('tid') +'" style="padding-left: 25px;height: 24px;line-height: 24px;font-size: 0.7rem;color: white;margin: 0px">Leader <i class="fa fa-question-circle tooltipped white-text" data-position="top" data-tooltip="'+leaderTooltip+'"></i></label>' +
                
                ((btn.closest('.stage').data('mode') != 0) ?
                    '<label for="ttype-'+stgId +'-'+ btn.data('tid')+'" style="font-size:1rem;position:relative;display:inline-block;margin-right:5px;margin-top:auto;margin-bottom:auto;height:min-content"> Type'+
                    '    <i class="fa fa-question-circle tooltipped white-text" data-position="top" data-tooltip="'+gradedTooltip+'"></i>'+
                    '</label>'+
                    '<select id="ttype-'+stgId +'-'+ btn.data('tid')+'" class="black-text" style="display:block;position:relative;height:2rem;width:inherit;margin:auto;">'+
                    '    <option value="1" selected="selected">A</option>'+
                    '    <option value="0">T</option>'+
                    '    <option value="-1">P</option>'+
                    '</select>'
                : '')
            );
            participantOptionsFixedContent.find('.tooltipped').tooltip();
            var addCommentButton = (btn.closest('.stage').data('mode') != 0) ? $('<i class="far fa-2x fa-map modal-trigger" href="#uprecomment-' +  stgId +'-'+ btn.data('tid') + '"></i>') : null;
            addElements = participantOptionsContainer.append(leaderInput).append(participantOptionsFixedContent).add(addCommentButton);

        } else {

            btn.addClass('red added');
            var participantOptionsContainer = $('<div class="participant-desktop"></div>');
            var leaderInput = (!btn.closest('.stage').find('input[id*="leader"]').is(':checked')) ? $('<input id="tleader-'+  stgId +'-'+ btn.data('tid') +'" type="checkbox" class="filled-in" checked="checked"/>') : $('<input id="tleader-'+ stgId +'-'+ btn.data('tid')+'" type="checkbox" class="filled-in"/>');
            var participantOptionsFixedContent = $(
                '<label for="tleader-'+ stgId +'-'+ btn.data('tid') +'">Leader <i class="fa fa-question-circle tooltipped white-text" data-position="top" data-tooltip="'+leaderTooltip+'"></i></label>' +
                ((btn.closest('.stage').data('mode') != 0) ?
                    '<label for="ttype-'+stgId +'-'+ btn.data('tid')+'" style="font-size:1rem;position:relative;display:inline-block;margin-right:5px;margin-top:auto;margin-bottom:auto;height:min-content"> Type'+
                    '    <i class="fa fa-question-circle tooltipped white-text" data-position="top" data-tooltip="'+gradedTooltip+'"></i>'+
                    '</label>'+
                    '<select id="ttype-'+stgId +'-'+ btn.data('tid')+'" class="black-text" style="display:block;position:relative;height:2rem;width:inherit;margin:auto;">'+
                    '    <option value="1" selected="selected">A</option>'+
                    '    <option value="0">T</option>'+
                    '    <option value="-1">P</option>'+
                    '</select>'
                : '')
            );
            participantOptionsFixedContent.find('.tooltipped').tooltip();
            var addCommentButton = (btn.closest('.stage').data('mode') != 0) ? $('<a class="precomment-button waves-effect waves-light btn modal-trigger smoothened-right-borders" href="#tprecomment-' +  stgId +'-'+ btn.data('tid') + '">' + modalButtonTitle + '</a>') : null;
            addElements = participantOptionsContainer.append(leaderInput).append(participantOptionsFixedContent).add(addCommentButton);

        }
        var addCommentModal = $('<div id="tprecomment-'+ stgId +'-'+btn.data('tid')+'" class="modal">\n' +
            '                <div class="modal-title">\n' +
            '                    <h5>'+ modalTitle + btn.closest('li').prev().find('.user-title').text().split(' ')[0] +'</h5>\n' +
            '                </div>\n' +
            '                <div class="modal-content">\n' +
            '                    <textarea class="textarea-broaden" name="tprecomment-'+ stgId +'-'+ btn.data('tid')+'"></textarea>\n' +
            '                </div>\n' +
            '                <div class="modal-footer">\n' +
            '                    <a class="btn btn-clear red waves-effect waves-light">'+ clearButton +'</a>\n' +
            '                    <a class="btn waves-effect waves-light modal-close">'+ saveButton +'</a>\n' +
            '                </div>\n' +
            '            </div>');
        addCommentModal.modal({
            dismissible: true,
            complete: function () {
                var textCommentArea = addCommentModal.find('textarea').val();
                if (textCommentArea != "" && ((!addCommentButton.hasClass('lime darken-3') && $(window).width() >= 700) || addCommentButton.hasClass('fa-map')) || textCommentArea == "" && (addCommentButton.hasClass('lime darken-3') || addCommentButton.hasClass('fa-map'))) {
                    updateButton(addCommentButton);
                }
            }
        });

        if($(window).width() < 700) {
            btn.closest('.user-content').find('.button-field').append(addElements);
        } else {
            btn.after(addElements);
            btn.closest('li').css('color','white');
        }

        $('.next-button').after(addCommentModal);

        /* If currentuser is participant, then is master user by default with appropriate bg color */
        (btn.data('tid') == usrId && btn.parent().find('input[id^="tleader"]').is(':checked')) ? btn.closest('ul').addClass('cyan darken-4') : btn.closest('ul').addClass('blue darken-4');

    }







});