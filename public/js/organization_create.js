


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

    $(document).on('keyup','input[name*="workerFirm"]',function(event){
        var el = $(this);
        var $selector = $('select[name="firmSelector"]');
        $selectorMElmts = $selector.closest('.select-wrapper');

        if($(this).val().length >= 3 /*&& event.keyCode != 8*/){

            const params = {name: $(this).val()};
            $.post(surl,params)
                .done(function(data){

                    if(!data.workerFirms.length){
                        $selectorMElmts.prev().find('input').removeAttr('value');
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
                    //el.attr("value",$selector.find(":selected").val());
                    $selector.material_select();
                    $selectorMElmts = $selector.closest('.select-wrapper')
                    $selector.prev().find('li').each(function(i,e){
                        $(e).prepend(`<img class="firm-option-logo" src="/lib/img/org/${data.workerFirms[i].logo ? data.workerFirms[i].logo : 'no-picture.png'}">`)
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

    $(document).on('focusout','input[name*="workerFirm"]',function(event){
        if(!$('select[name="firmSelector"]').closest('.select-wrapper').is(':visible')){
            setTimeout(function(){
                $this = $('input[name*="workerFirm"]');
                if(!$this.attr('value') && $this.val() != ""){
                    const params = {name: $this.val()};
                    $.post(curl,params)
                        .done(function(data){
                            $this.attr('value',data.wfId);
                        })
                        .fail(function(data){
                            console.log(data);
                        });
    
                }
            },2000)
        }
    })

    $(document).on('change','select[name="firmSelector"]',function(){
        $("input[name*='workerFirm']").val($(this).find(":selected").text());
        $("input[name*='workerFirm']").attr("value",$(this).val());
        $(this).prev().css('visibility','hidden');
    });

    $('form[name="add_organization_form"]').on("submit",function(e){
        $("input[name*='workerFirm']").val($("input[name*='workerFirm']").attr("value"));
    });

});







