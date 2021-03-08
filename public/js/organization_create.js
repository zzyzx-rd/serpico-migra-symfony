


$(function(){

    $('.modal').modal();

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

    $('#deleteOrganization .delete-button').on('click',function(e){
        urlToPieces = durl.split('/');
        urlToPieces[urlToPieces.length-2] =  $(this).data('oid');
        durl = urlToPieces.join('/');
        $.post(durl,null)
            .done(function(data){
                window.location = ourl;
            })
            .fail(function (data) {
                console.log(data)
            });
    })

    $(document).on('change',"#firmSelector",function(){
        var $this = $(this);
        if($this.val() != ""){
            $('[name*="commname"]').val($this.find(":selected").text());
            $('[name="wfiId"]').attr("value",$this.val());
        } else {
            $('[name="wfiId"]').removeAttr('value');
        }
        $('.selected-firm-logo').attr('src', $('#firmSelector').prev().find('li').eq($('#firmSelector option').index($('#firmSelector option:selected'))).find('img').attr('src'));
        $('.commname-value').empty().text($('#firmSelector option:selected').text());
        if($this.val() != ""){
            $('.organization-info-commname').show();
            $('.w-firm').hide();
        } else {
            $(this).parent().hide();
        }

    });

    $(document).on('keyup','input[name*="commname"]',function(event){
        var $this = $(this);
        var $selector = $('#firmSelector');
        $selectorMElmts = $selector.closest('.select-wrapper');

        if($this.val().length >= 3 /*&& event.keyCode != 8*/){
            //urlToPieces = surl.split('/');
            //urlToPieces[urlToPieces.length - 1] = $(this).val();
            //surl = urlToPieces.join('/');
            const params = {name: $this.val()};
            $.post(surl,params)
                .done(function(data){

                    if(!data.workerFirms.length){
                        $this.removeAttr('value');
                        $selectorMElmts.hide();
                        return false;
                    }
                    
                    $selector.closest('.select-wrapper').find('img').remove();
                    $selector.empty();
                    $.each(data.workerFirms,function(key,firm){
                        //$option = $(`<option class="flex-center" value=${firm.id}></option>`);
                        //$option.append(`<img class="firm-option-logo" src="/lib/img/org/${firm.logo ? firm.logo : 'no-picture.png'}">`)
                        //$option.append(`<span>${firm.name}</span>`);
                        $selector.append(`<option value="${firm.id}">${firm.name}</option`);
                    })
                    //el.val(selector.find(":selected").text());
                    //$selector.prepend(`<option value>(${noFirm})</option>`);
                    //$this.attr("value",$selector.find(":selected").val());
                    $selector.material_select();
                    $selectorMElmts = $selector.closest('.select-wrapper')
                    $selector.prev().find('li').each(function(i,e){
                        //$selector.prev().find('li').index($(e)) == 0 ? ($(e).find('span').css('color','black'), $(e).prepend(`<img class="firm-option-logo" src="/lib/img/org/new-firm.png">`)) :
                        $(e).prepend(`<img class="firm-option-logo" src="/lib/img/org/${data.workerFirms[i].logo ? data.workerFirms[i].logo : 'no-picture.png'}">`);
                        //$option.append(`<span>${firm.name}</span>`);
                        //selector.append($option);
                    });
                    //$selectorMElmts.prepend(`<img class="firm-input-logo" src="/lib/img/org/${data.workerFirms[0].logo ? data.workerFirms[0].logo : 'no-picture.png'}">`);


                    //$('select[name="firmSelector"]').eq(index).show();
                })
                .fail(function(data){
                   console.log(data);
                })
        } else {
            //if($selectorMElmts){
                $selectorMElmts.hide();
            //}
        }
    })

    $(document).on('click',function(e){
        var $target = $(e.target);
        if(!$target.hasClass('organization-info-commname') && !$target.closest('.organization-commname').length){
            const $this = $("#firmSelector");
            /*if($this.val() != ""){
                $('[name*="commname"]').val($this.find(":selected").text());
                $('[name="wfiId"]').attr("value",$this.val());
            } else {
                $('[name="wfiId"]').removeAttr('value');
            }*/
            if($('[name*="commname"]').val() != $('#firmSelector option:selected').text()){

                $('.selected-firm-logo').attr('src', '/lib/img/org/no-picture.png');
                $('[name="wfiId"]').removeAttr('value');
            } else {
                $('[name="wfiId"]').attr("value",$('#firmSelector option:selected').attr('value'));
                $('.selected-firm-logo').attr('src', $('#firmSelector').prev().find('li').eq($('#firmSelector option').index($('#firmSelector option:selected'))).find('img').attr('src'));
            }
            $('.commname-value').text($('[name*="commname"]').val());


            if($this.val() != ""){
                $('.organization-info-commname').show();
                $('.w-firm').hide();
            } else {
                $this.parent().hide();
            }
        } else if($target.closest('.organization-btn').length){
            $target.closest('.organization-btn').hide();
            $target.closest('.organization-btn').next().show();
        }
    });

    $('.organization-btn').on('click',function(){
        const $this = $(this);
        $this.hide();
        $this.next().show();
    })

});







