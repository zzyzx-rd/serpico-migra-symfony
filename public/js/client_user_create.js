$(function(){
    var proto =$('ul.clients');
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

    $(document).on('click','.add-external-org-group',function(){
           $('#addClient').find('button').data('selectedNb', $(this).closest('li').index());
    });

    $(document).on('change','[id$="type"]',function(){
        if ($(this).val() == 'I') {
            $(this).closest('li').find('[id$="orgId"]').attr('disabled', true).parent().hide().next().hide();
        } else{
            $(this).closest('li').find('[id$="orgId"]').removeAttr('disabled').parent().show().next().show();
        }
    });

    $(document).on('click','.client-type .fa-pencil-alt',function(){
        $('.client-info-type').hide();
        $('.client-select-type .select-dropdown').addClass('no-margin');
        $('.client-select-type').show();
        $('.client-select-type select').material_select();
    });

   $(document).on('click',function(e){
        var $this = $(e.target);
        if(
            $this.is('.btn:not(.user-btn,.client-btn)') ||
            $this.parent().is('.btn:not(.user-btn,.client-btn)') ||
            $this.hasClass('insert-individual-btn') || 
            $this.parent().hasClass('insert-individual-btn')
        ) {return ;}
        var $targetedUserEl = $this.closest('.user-elmt');
        var $targetedUserInput = $targetedUserEl ? $targetedUserEl.find('.user-input') : null;

        $('.user-input:visible:not(.user-owner)').not($targetedUserInput).each(function(i,e){
            
            if($(e).hasClass('user-select-fullname') && (!$(e).find('[name*="firstname"]').val().length || !$(e).find('[name*="lastname"]').val().length)){
                return;
            }
            /*$userEl.find('a').is(':visible')){
                return;
            }*/
    

            var $userEl = $(e).closest('.user-elmt');    
            const $input = $userEl.find('input.select-dropdown').length ? $userEl.find('select') : $userEl.find('input');
            
            if($userEl.find('input.select-dropdown').length){
                    inputVal = $userEl.find('select option:selected').text();
            } else {
                if($(e).hasClass('user-select-fullname')){
                    inputVal = $userEl.find('[name*="firstname"]').val() + ' ' + $userEl.find('[name*="lastname"]').val();
                } else {
                    inputVal = $userEl.find('input').val();
                }
            }

            //const inputVal = $userEl.find('input.select-dropdown').length ? $userEl.find('select option:selected').text() : $userEl.find('input').val();
            $userEl.find('.user-input').hide();
            $userEl.find('a>span').empty().append($input.val().length ? $userEl.data('prefix-w')+''+ inputVal : $userEl.data('prefix-wo'));
            $userEl.find('a').show();
        })

        var $targetedClientEl = $this.closest('.client-elmt');
        var $targetedClientInput = $targetedClientEl ? $targetedClientEl.find('.client-input') : null;

        $('.client-input:visible').not($targetedClientInput).each(function(i,e){
            var $clientEl = $(e).closest('.client-elmt');
            const $input = $clientEl.find('input.select-dropdown').length ? ($clientEl.find('.client-input-name') ? $clientEl.find('input').eq(0) : $clientEl.find('select')) : $clientEl.find('input').eq(0);
            inputVal = $clientEl.find('input.select-dropdown').length ? ($clientEl.find('.client-input-name') ? $clientEl.find('input').eq(0).val()  : $clientEl.find('select option:selected').text()) : $clientEl.find('input').val();
            $clientEl.find('.client-input').hide();
            $clientEl.find('a>span').empty().append($input.val().length ? $clientEl.data('prefix-w')+''+ inputVal : $clientEl.data('prefix-wo'));
            $clientEl.find('a').show();
        })
        
    });

    $(document).on('keyup','.client-input-name',function(event){
        var $this = $(this);
        var index = $('.client-input-name').index($(this));
        var $selector = $('[name*="firmSelector"]').eq(index);
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
                    $selector.prepend(`<option value>(${noFirm})</option>`);
                    //$this.attr("value",$selector.find(":selected").val());
                    $selector.material_select();
                    $selectorMElmts = $selector.closest('.select-wrapper')
                    $selector.prev().find('li').each(function(i,e){
                        $selector.prev().find('li').index($(e)) == 0 ? ($(e).find('span').css('color','black'), $(e).prepend(`<img class="firm-option-logo" src="/lib/img/org/new-firm.png">`)) :
                        $(e).prepend(`<img class="firm-option-logo" src="/lib/img/org/${data.workerFirms[i-1].logo ? data.workerFirms[i-1].logo : 'no-picture.png'}">`);
                        //$option.append(`<span>${firm.name}</span>`);
                        //selector.append($option);
                    });
                    //$selectorMElmts.prepend(`<img class="firm-input-logo" src="/lib/img/org/${data.workerFirms[0].logo ? data.workerFirms[0].logo : 'no-picture.png'}">`);


                    //$('#firmSelector').eq(index).show();
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

    $(document).on('click','.client-btn',function(){
        const $this = $(this);
        $this.hide();
        $this.next().show();
    })

    $(document).on('change','[name*="firmSelector"]',function(){
        var $this = $(this);
        var index = $('[name*="firmSelector"]').index($this);
        if($this.val() != ""){
            $('.client-input-name').eq(index).val($this.find(":selected").text());
            $('[name*="workerFirm"]').eq(index).val($this.val());
        } else {
            $('[name*="workerFirm"]').eq(index).val("");
        }
        $('.selected-client-logo').eq(index).attr('src', $this.prev().find('li').eq($this.find('option').index($this.find('option:selected'))).find('img').attr('src'));
        if($this.val() != ""){
            $('.commname-value').eq(index).empty().text($('.client-commname').eq(index).data('prefix-w') + '' + $this.find('option:selected').text());
            $('.client-info-commname').show();
            $('.w-firm').hide();
        } else {
            $(this).parent().hide();
        }
    });
        

    $collectionHolder = $('ul.clients');
    if(multCreation == "1" && !$('#errors').length){
        addClientForm();
    }

    $(document).on('click','.modify-client-btn, .modify-ext-user-btn',function(){
        $(this).closest('.element-data').next().show().prev().hide();
    });

    $.delete = function(url, data, callback, type){
 
        if ( $.isFunction(data) ){
          type = type || callback,
              callback = data,
              data = {}
        }
       
        return $.ajax({
          url: url,
          type: 'DELETE',
          success: callback,
          data: data,
          contentType: type
        });
    }

    $(document).on('click','.modify-client-btn',function(){
        const $dataZone = $(this).closest('.client-data-zone');
        $dataZone.hide();
        $dataZone.next().show();
    });

    $('.remove-client').on('click',function(){

        urlToPieces = dcurl.split('/');
        cid = $(this).data('cid');
        urlToPieces[urlToPieces.length - 2] = cid;
        dcurl = urlToPieces.join('/');
        $.delete(dcurl,null)
            .done(function(){
                urlToPieces = window.location.pathname.split('/');
                urlToPieces[urlToPieces.length - 4] = urlToPieces[urlToPieces.length - 2];
                location.pathname = urlToPieces.slice(0,-3).join('/');
            })
            .fail(function(data){
                console.log(data);
            })
    })

    $(document).on('click','.remove-client-user',function(){
        
        if($(this).data('eid') == 0){
            $(this).closest('.individual').remove();
            return false;
        }

        urlToPieces = diurl.split('/');
        eid = $(this).data('eid');
        urlToPieces[urlToPieces.length - 2] = eid;
        diurl = urlToPieces.join('/');
        $.delete(diurl,null)
            .done(function(){
                $(`[data-eid="${eid}"]`).closest('.individual').remove();
            })
            .fail(function(data){
                console.log(data);
            })
    })

    $(document).on('click','[href="#deleteExternalUser"]',function(){
        $('.remove-client-user').data('eid',$(this).data('eid'));
    });

    $(document).on('click','[href="#deleteClient"]',function(){
        $('.remove-client').data('cid',$(this).data('cid'));
    });
    $(document).on('click','.new-partner',function(e){
        client = $(this).closest('main').find('.client');
        helpText = $(this).closest('main').find('.help');
        help = helpText.children()[0];
        help.style.display = 'block';
        client.remove();
        addClientForm();
        $(this).css('display','none');

    })
    $(document).on('click','.validate-client-btn',function(e){
        
        e.preventDefault();
        btn = $(this);
        $curRow = $(this).closest('.client');
        $curRow.find('.red-text').remove();
        inputName = $curRow.find('.client-input-name').val();
        workerFirm = $curRow.find('input[name*="workerFirm"]').val();
        inputType = $curRow.find('select[name*="type"] option:selected').val();
        inputId = $curRow.find('.client-input-zone input[name*="id"]').val();
        helpText = $curRow.closest('.client-zone').find('.help');
        newPartner = $curRow.closest('main').find('.new-partner');
        addUser = $curRow.find('.add-user-zone');
        help = helpText.children()[0];


        if($('.c-form').length){
            $form = $('.c-form form');
            $form.find('.client-input-name').val(inputName);
            $form.find('input[name*="workerFirm"]').val(workerFirm);
            $form.find('select[name*="type"]').val(inputType);
            $form.find('input[name*="id"]').val(inputId);
        } else {
            $form = $('form[name*="client"]');
        }
       
        urlToPieces = vcurl.split('/');
        urlToPieces[urlToPieces.length - 1] = $(this).attr('data-cid');
        vcurl = urlToPieces.join('/');

        $.post(vcurl,$form.serialize())
            .done(function(data){
                newPartner.css('display','inline-block');
                addUser.css('display','block');
                help.style.display = 'none';
                btn.attr('data-cid',data.cliId);
                btn.prev().attr('data-cid',data.cliId);
                $curRow.find('input[name*="id"]').val(data.cliId);
                $curRow.find('input[name*="workerFirm"]').val(data.wfiId);
                $('.add-partner-zone').show();
                $curRow.find('.insert-individual-btn').attr('data-cid',data.cliId);
                $curRow.find('[href="#deleteClient"]').attr('data-cid',data.cliId);
                $curRow.find('.client-logo-zone').attr('src',$curRow.find('.selected-client-logo').attr('src'));
                $curRow.find('.client-name').empty().append(inputName);
                $curRow.find('.client-data-zone').show();
                $curRow.find('.client-input-zone').hide();

            })
            .fail(function(data){
                $.each(data.responseJSON, function (key, value) {
                    if(key == "#"){
                        $('#dynErrror p').empty().append(value);
                        $('#dynErrror').modal('open');
                    } else {
                        $.each($curRow.find('input, select').not('.select-dropdown'),function(i,e){
                            if($(e).attr('name').indexOf(key) != -1){
                                $(e).after(/*html*/`
                                <div class="red-text">
                                    <strong>${value}</strong>
                                </div>
                                `);
                                $(e).closest('.client-input').prev().hide();
                                $(e).closest('.client-input').show();
                            }
                        })
                    }
                })
            })
    })

    $(document).on('click','.user-btn',function(){
        const $this = $(this);
        const $content = $(this).parent();
        const $mZone = $content.children().eq(-1);
        $this.hide();
        $mZone.find('select').material_select();
        $mZone.show();
    });

    $(document).on('click','.validate-ext-user-btn',function(e){
        e.preventDefault();
        btn = $(this);
        $curRow = $(this).closest('.individual');
        $curRow.find('.red-text').remove();
        eid = btn.attr('data-eid');
        cid = btn.attr('data-cid');
        inputFName = $curRow.find('input[name*="firstname"]').val();
        inputLName = $curRow.find('input[name*="lastname"]').val();
        inputEmail = $curRow.find('input[name*="email"]').val();
        inputPosition = $curRow.find('input[name*="positionName"]').val();
        inputWeight = $curRow.find('input[name*="weight"]').val();
        inputId = $curRow.find('input[name*="id"]').val();
        const $form = $('.i-form form');
        $form.find('[name*="firstname"]').val(inputFName);
        $form.find('[name*="lastname"]').val(inputLName);
        $form.find('[name*="email"]').val(inputEmail);
        $form.find('[name*="positionName"]').val(inputPosition);
        $form.find('[name*="weight"]').val(inputWeight);
        $form.find('[name*="id"]').val(inputId);
        urlToPieces = viurl.split('/');
        urlToPieces[urlToPieces.length - 4] = cid;
        urlToPieces[urlToPieces.length - 1] = eid;
        viurl = urlToPieces.join('/');

        $.post(viurl,$form.serialize())
        .done(function(data){
            if(data.extId){
                btn.attr('data-eid',data.extId);
                btn.prev().attr('data-eid',data.extId);
                if(btn.prev().hasClass('remove-client-user')){
                    btn.prev().removeClass('remove-client-user').addClass('modal-trigger').attr('href','#deleteExternalUser');
                }
                $curRow.find('input[name*="id"]').val(data.extId);
            }
            let elmtData = btn.closest('.element-input').prev();
            elmtData.find('.user-ext-fullname').empty().append(inputFName+' '+inputLName);
            elmtData.find('.user-ext-position-name').empty().append(inputPosition);
            elmtData.find('.user-ext-weight').empty().append(inputWeight);
            if(inputEmail.length){
                elmtData.find('.user-ext-email i').attr('data-tooltip',inputEmail).tooltip();
                if(!elmtData.find('.user-ext-email i').hasClass('lime-text')){
                    elmtData.find('.user-ext-email i').addClass('lime-text text-darken-3');
                }
            }
            btn.closest('.element-input').prev().show().next().hide();

        })
        .fail(function(data){
            $.each(data.responseJSON, function (key, value) {
                $.each($curRow.find('input, select'),function(){
                    if($(this).attr('name').indexOf(key) != -1){
                        $(this).after(/*html*/`
                        <div class="red-text">
                            <strong>${value}</strong>
                        </div>
                        `);
                        $(this).closest('.user-input').prev().hide();
                        $(this).closest('.user-input').show();

                    }
                })
            })
        })


    })

    function addClientForm(){
        // Get the data-prototype
        var prototype = proto.data('prototype');
        var total = $('.client').length;
        // Replacing prototype constants
        var newForm = prototype
            .replace(/__clientNb__/g, total)
            .replace(/__name__/g, total);
        $newForm = $(newForm);
       

        $newForm.find('.tooltipped').tooltip();
        $collectionHolder.append($newForm);
        $newForm.find('select[name*="type"]').material_select();
    }


    function addIndividualForm(index) {

        var $individualList = $('.client').eq(index).find('ul.individuals');
        // Get the data-prototype
        var prototype = $individualList.data('prototype');
        var total = $individualList.find('.individual').length;
        // Replacing prototype constants
        var newForm = prototype
            .replace(/__indivNb__/g, total)
            .replace(/__indIndex__/g, total)
            .replace(/__DeleteIndivButton__/g, '<i class="remove-individual small material-icons" style="color: red">cancel</i>');
        
        $newForm = $(newForm);
        if($newForm.find('.validate-ext-user-btn').attr('data-cid') == 0){
            $newForm.find('.validate-ext-user-btn').attr('data-cid',$('.client').eq(index).find('.insert-individual-btn').attr('data-cid'));
        }
        $newForm.find('.user-select-weight input').val(100);
        $newForm.find('.tooltipped').tooltip();
        if(!$individualList.is(':visible')){$individualList.show();}
        $individualList.append($newForm);
    }

    $(document).on('click','.insert-individual-btn', function(e) {
        addIndividualForm($('.client').index($(this).closest('.client')));
    })

    $('.insert-client-btn').on('click', function(e) {
        addClientForm();
    })

    function unvalidatedClients(){
        if(!$('.validate-client-btn[data-cid="0"]').length){
            clearInterval(interval);
            $('form[name="add_client_form"]').submit();
        }
    }

    $('.create-external-users').on('click', function(e) {
        e.preventDefault();
        $('.validate-client-btn:visible[data-cid="0"]').each(function(i,e){
            $(e).click();
        })
        interval = setInterval(unvalidatedClients,100);
    });
    /*

    $('.create-external-users').on('click', function(e) {
        e.preventDefault();
        const $redText = $('.red-text');
        $redText.remove();

        $.post(url, $(this).closest('form').serialize())
        .done(function() {
            if ($redText.length == 0) {
                url = window.location.pathname;
                urlToPieces = url.split('/');
                urlToPieces[urlToPieces.length-2] = 'users';
                window.location = urlToPieces.slice(0,urlToPieces.length-1).join('/');
            }
        })
        .fail(function({ responseJSON }) {
            if (!responseJSON) return;
    */
    
            /** @type {JQuery<HTMLInputElement|HTMLSelectElement|HTMLButtonElement>} */
    /*
            const $inputs = $('[name="add_client_user_form"] :input');
            const $redText = $('.red-text');

            for (const fieldKey in responseJSON) {
                const field = responseJSON[fieldKey];
                for (const userKey in field) {
                    const user = field[userKey];
                    for (const propKey in user) {
                        const prop = user[propKey];
                        const $filtered = $inputs.filter(
                            (i, e) => e.name.includes(userKey) && e.name.includes(propKey)
                        );
                        $filtered.after(`
                            <div class="red-text"><strong>${prop}</strong></div>
                        `);
                    }
                }
            }
        });
    })
    */
});
