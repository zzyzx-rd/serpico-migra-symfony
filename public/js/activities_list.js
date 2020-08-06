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

        $('[href^="#delete"]').each(function(){
            var deleteElmt = $(this);
            $.each(this.attributes, function(){
                var deleteBtn = $('<i class="small material-icons modal-trigger" style="color: red">cancel</i>')
                if(this.name == 'data-sid'){
                    deleteBtn.attr('data-sid',this.value);
                    deleteElmt.replaceWith(deleteBtn);
                    return false;
                } else if(this.name == 'data-aid'){
                    deleteBtn.attr('data-aid',this.value);
                    deleteElmt.replaceWith(deleteBtn);
                    return false;
                }
            })
        });

        if($('.tab').length > 2){
            $('.activity-status').hide();
        }
        $('.collection-item').css('padding','0px');
        $('.results-btn').prepend('<i class="fa fa-bullhorn" style="margin-right: 5px"></i>');

        $.each($('.grade-btn'),function(){
            if($(this).closest('.action-buttons').find('.button-field').length > 1){
                $(this).empty().append('<i class="fa fa-pencil-alt" style="transform: rotate(270deg)"></i>')
            } else {
                $(this).prepend('<i class="fa fa-pencil-alt" style="margin-right: 5px; transform: rotate(270deg)"></i>');
            }
        });

        $.each($('.modify-btn'),function(){
            if($(this).closest('.action-buttons').find('.button-field').length > 1){
                $(this).empty().append('<i class="fa fa-wrench"></i>')
            } else {
                $(this).prepend('<i class="fa fa-wrench" style="margin-right: 5px"></i>');
            }
        });

    } else {
        $('.mobile-dates').hide();
        $('.fixed-action-btn a').css({'height':'60px', 'width':'60px'});
        $('.btn-floating i').not('.template-pencil').css({'line-height':'60px', 'font-size':'2.5rem'});
        $('.template-pencil').css({'line-height':'60px', 'font-size':'1.5rem'});
    }

    $( window ).resize(function() {
        (window.innerWidth > 800) ? $('.activity-status').show() : (($('.tab').length > 2) ? $('.activity-status').hide() : $('.activity-status').show());
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

    $(".modal-close").on('click',(function(){
        $("#deleteactivity").modal();
        })
    );

    $('[href="#deleteASModal"]').on('click',function(){
        $('#deleteASModal').find('.delete-button').data('sid',$(this).data('sid'));
    });

    $(document).on('click', '[href="#deleteAModal"]', function() {
        $('#deleteAModal').find('.delete-button').data('aid',$(this).data('aid'));
    });

    $('[href="#deleteRModal"]').on('click',function(){
        $('.rdelete-button').data('rid',$(this).data('rid'));
        $('.rdelete-button').data('sid',$(this).data('sid'));
        $('.adelete-button').data('sid',$(this).data('sid'));
    });

    $('[href="#discardModal"]').on('click',function(){
        $('.discard-button').data('aid',$(this).data('aid'));
    });

    $('[href="#cancelModal"]').on('click',function(){
        $('.cancel-button').data('aid',$(this).data('aid'));
    });

    $('[href="#validateModal"]').on('click',function(){
        $('.validate-button').data('aid',$(this).data('aid'));
        $('#validateModal [name="delegate_activity_form[activityName]"]').val($(this).data('aname'));
        $('#validateModal [name="delegate_activity_form[activityDescription]"]').val($(this).data('adescription'));
    });

    // Modal button to delete either stage/activity
    $('.delete-button,.adelete-button').on('click',function(e){

        var stageToDelete = ($(this).data("sid")) ? true : false;
        var id = (stageToDelete) ? $(this).data("sid") : $(this).data("aid");
        var clickedBtn = (stageToDelete) ? $('[data-sid="'+ id +'"]') : $('[data-aid="'+ id +'"]');
        var nbStages = clickedBtn.closest('.collection').find('.collection-item').length;
        var removableElmt = nbStages > 1 ? clickedBtn.closest('.collection-item') : clickedBtn.closest('.collapsible');
        var urlToPieces = (stageToDelete) ? deleteUrl.split('/') : adeleteUrl.split('/');
        urlToPieces[urlToPieces.length-1] = id;

        var url = urlToPieces.join('/');
        e.preventDefault();
        $.ajax({
            url : url,
            type : 'POST',
            success: function(jsonData){

                /*if(!stageToDelete){
                    $('[data-aid="'+id+'"]').remove()
                }*/

                if(removableElmt.hasClass('collection-item')){
                    if(stageToDelete){
                        var remainingStagesElmts = clickedBtn.closest('.collapsible').find('.collection-item');
                        if(remainingStagesElmts.length == 2){
                            $.each(remainingStagesElmts.find('[href="#deleteASModal"]'),function(){
                                $(this).removeAttr('data-sid').attr('data-aid', jsonData.aid).attr('href','#deleteAModal');
                            });
                        }
                        var txt = removableElmt.find('.determinate').attr('style');
                        var pos1 = txt.lastIndexOf('width: ');
                        var pos2 = txt.indexOf('%');
                        var removableElmtProgress = txt.substr(pos1,pos2-pos1);
                        progressElmt = removableElmt.closest('.collapsible-body').prev().find('.determinate');
                        progressElmt.css('width',(Math.floor(100 * parseInt(progressElmt.css('width'),10) / parseInt(progressElmt.parent().css('width'),10) - removableElmtProgress) * nbStages / (nbStages - 1)).toString()+'%');
                    }

                } else {
                    
                    //Deleting a unique current activity needs to reshape all tab menu, thus preferable to reload webpage
                    if($('#current .collapsible>li').length == 1){
                        window.location = location.href;
                    }
                }

                removableElmt.remove();
                if(!stageToDelete){
                    var nbElements = Number($('a.active').find('span').eq(-1).text()) - 1;
                    $('a.active').find('span').eq(-1).empty().append(nbElements);
                }

            },
            fail: function(data){
                console.log(data);
            },
        })
    });

    $('.rdelete-button').on('click',function(e){
        var urlToPieces = rdeleteUrl.split('/');
        var sid = $(this).data('sid');
        urlToPieces[urlToPieces.length-1] = $(this).data('rid');
        var url = urlToPieces.join('/');
        e.preventDefault();
        $.ajax({
            url : url,
            type : 'POST',
            success: function(){
                $('[data-sid='+sid+']').closest('ul').remove();
                //$("#activityList"+sid).remove();
            },
            fail: function(data){
                console.log(data);
            },
        })
    });

    $('.cancel-button, .discard-button, .validate-button, .delegate-button').on('click',function(e){
        e.preventDefault();
        var aid = $(this).data('aid');
        if($(this).closest('.modal').length > 0 || $(this).hasClass('validate-button')){
            var modalId = $(this).closest('.modal').attr('id');
            var url = (modalId == 'requestActivity') ? requestUrl :  (modalId == 'delegateActivity') ? delegateUrl : resolveRequestUrl;
            if(url == resolveRequestUrl){
                var urlToPieces = url.split('/');
                var validateUrl = '';
                var discardUrl = '';
                var cancelUrl = '';
                urlToPieces[urlToPieces.length-2] = aid;

                switch(modalId){
                    case 'cancelModal' :
                        urlToPieces[urlToPieces.length-1] = 'cancel';
                        cancelUrl = urlToPieces.join('/');
                        url = cancelUrl;
                        break;
                    case 'discardModal' :
                        urlToPieces[urlToPieces.length-1] = 'discard';
                        discardUrl = urlToPieces.join('/');
                        url = discardUrl;
                        break;
                    default :
                        urlToPieces[urlToPieces.length-1] = 'validate';
                        validateUrl = urlToPieces.join('/');
                        url = validateUrl;
                        break;
                }
            }
        } else {
            var urlToPieces = archiveUrl.split('/');
            urlToPieces[urlToPieces.length-1] = $(this).data('id');
            url = urlToPieces.join('/');
        }


        $.post(url, $(this).closest('form').serialize())
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
                        $('.modal').modal('close');
                        $('[data-aid='+aid+']').closest('ul').remove();
                        switch(url){
                            case delegateUrl:
                                $('#delegateSuccess').modal('open');
                                break;
                            case requestUrl:
                                $('#requestSuccess').modal('open');
                                break;
                            case cancelUrl:
                                $('#cancelSuccess').modal('open');
                                break;
                            case discardUrl:
                                $('#discardSuccess').modal('open');
                                break;
                            case  validateUrl:
                                $('#validateSuccess').modal('open');
                                break;
                        }
                        /*$('#requestSuccess').modal({
                            complete: function(){location.reload();}
                        })*/
                    }
                }

                if(data.message == 'archived'){
                    $('#archiveSuccess').modal('open');
                    //$('.archive[data-id="'+data.aid+'"]').closest('li').remove();
                } else if (data.message == 'restored'){
                    $('#restoreSuccess').modal('open');
                }
            })
            .fail(function (data) {
                console.log(data)
            });
    });

    $('#requestSuccess, #delegateSuccess, #cancelSuccess, #discardSuccess, #validateSuccess, #restoreSuccess, #archiveSuccess').modal({
        dismissible:true,
        complete:function(){
            window.location = location.href;
        }
    });




}); // CAUTION leave this at the end of the script CAUTION
