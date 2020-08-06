$(function(){
    
    update = $('#update').val();
    console.log('update : ' + update);

    
    $('.btn-large').css('background-color','grey');
    $('.btn-large').on("click",function(e){

        e.preventDefault();

        var nb_selected_participants = $('.red').length;

        if((nb_selected_participants>=2) || (update==true && nb_selected_participants>=1)){

            var usrId = [];
            $('.red').each(function(){
                usrId.push($(this).data("participant-id"));
            });
            //parId=parId.serializeArray();
            if (update==true) {
                actId = $('#actId').val();
                console.log(actId);
                $.ajax(
                        {
                            url : '../../ajax/activity/' + actId,
                            data : {usrId:usrId},
                            method : 'POST',
                            //dataType : 'json',
                            success: function(){
                                console.log("Success");
                                window.location = '../../myactivities';
                            }
                           }
                );
            } else {
                var curUrl = $(location).attr('href');
                var actId = curUrl.substr(curUrl.lastIndexOf('/') + 1);
                console.log(actId);
                $.ajax(
                        {
                            url : '../../../ajax/activity/' + actId,
                            data : {usrId:usrId},
                            method : 'POST',
                            //dataType : 'json',
                            success: function(){
                                console.log("Success");
                                window.location = '../../../myactivities';
                            }
                           }
                );
            }
        }
        
    });


    $('.action-button').on("click", function(){
        var nb_selected_participants = $('.red').length;
        if(!$(this).hasClass('red') && (nb_selected_participants == 1 || update==true && nb_selected_participants==0)){
            $('.btn-large').css('background-color','');
        } else if($(this).hasClass('red') && (nb_selected_participants == 2 || update==true && nb_selected_participants==1)){
            $('.btn-large').css('background-color','grey')
        }


        if($(this).text()=="ADD"){
            $(this).text("REMOVE");
            $(this).addClass('red')
            $(this).closest('ul').css('background-color','green');
            $(this).closest('li').css('background-color','green');
            $(this).closest('li').css('color','white');
        } else if ($(this).text()=="REMOVE"){
            $(this).text("ADD");
            $(this).removeClass('red');
            $(this).closest('ul').css('background-color','');
            $(this).closest('li').css('background-color','');
            $(this).closest('li').css('color','');
        }

    })

});