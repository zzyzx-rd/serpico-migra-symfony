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

    /*
    $(document).on('focusout','input[name*="commname"]',function(event){
        if(!$('select[name="firmSelector"]').closest('.select-wrapper').is(':visible')){
            setTimeout(function(){
                $this = $('input[name*="commname"]');
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
    */

    $(document).on('change','select[name="firmSelector"]',function(){
        const $this = $(this);
        const clientNb = $('select[name="firmSelector"]').index($this);
        $('input[name*="commname"]').eq(clientNb).attr('value',$this.val());
    });

    $(document).on('keyup','input[name*="commname"]',function(event){
        var $this = $(this);
        var index = $('input[name*="commname"]').index($(this));
        var $selector = $('select[name="firmSelector"]').eq(index);
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

    $(document).on('change','select[name="firmSelector"]',function(){
        var $this = $(this);
        var index = $('select[name="firmSelector"]').index($this);
        if($this.val() != ""){
            $('input[name*="commname"]').eq(index).val($this.find(":selected").text());
            $('input[name*="commname"]').eq(index).attr("value",$this.val());
        } else {
            $('input[name*="commname"]').eq(index).removeAttr('value');
        }
        $(this).prev().css('visibility','hidden');
    });
    
    /*
    $('.client-submit').on('click', function(e){
        e.preventDefault();

        const $redText = $('.red-text');
        $redText.remove();

        $.post(eorurl, $(this).closest('form').serialize())
        .done(function(_json) {
            const json = JSON.parse(_json);

            $('[name="add_client_user_form"] select[name$="[orgId]"]').append(`
                <option value="${json.orgId}">${json.orgName}</option>
            `).prop('value', json.orgId);
            $('.modal').modal('close');
            $('#addClientSuccess').modal('open');
        })
        .fail(function({ responseJSON }) {
            if (responseJSON.hasOwnProperty("commname")) {
                $('#addClient').find('input[type="text"]').after('<div class="red-text"><strong>' + responseJSON.commonName + '</strong></div>');
                return false;
            } else {
                $('[id$="orgId"]').append('<option value="'+responseJSON.orgId+'">'+responseJSON.orgName+'</option>');
                $('[id$="orgId"]:eq('+$("#addClient").find("button").data("selectedNb")+')').val(responseJSON.orgId);
            }
        });
    });
    */
    

    $collectionHolder = $('ul.clients');
    if(multCreation == "1" && !$('#errors').length){
        addClientForm();
    }

    // Get the ul that holds the collection of users

    $('.dropify').dropify({

        messages:
            (lg == 'fr') ? {
                'default': 'Cliquez ou glissez-déposez un fichier CSV (max. 1 Mo) contenant :<br> <br>' +
                'Champs nécessaires : Prenom, Nom, Mail & Position (ex : Senior Officer) <br>' +
                'Champs optionnels : Role (dans la solution, par défault "Collaborateur", autres choix "Admin" ou "Activity_Manager") & Poids (par défaut, cela est géré dans la liste des utilisateurs))',
                'replace': 'Cliquez ou glissez-déposer un fichier pour le remplacer',
                'remove': 'Supprimer',
                'error': 'Ooops, une erreur est survenue'
            } : {
                'default': 'Click or drag and drop a CSV file (max. 1 Mo) containing : <br> <br>' +
                'Mandatory fields : First_Name, Last_Name, Mail & Position (ex : Senior Officer) <br>' +
                'Optional fields : Role (by default "Collaborator", but it can also be "Admin" or "Activity_Manager") & Weight (by default, managed in user positions)',
                'replace': 'Drag and drop or click to replace',
                'remove': 'Remove',
                'error': 'Ooops, something wrong happened.'
            }

    });

    $('.dropify').on('change',function(e){
        if($('.dropify-clear').length>0){
            $('.create-users').attr('disabled',false);
        }
    })

    $(document).on('click', '.dropify-clear', function(){
        if($collectionHolder.children().length == 0){
            $('.create-users').attr('disabled',true);
        }
    })

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

    $('.remove-client-user').on('click',function(){
        
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

    $(document).on('click','.validate-client-btn',function(e){
        
        e.preventDefault();
        btn = $(this);
        $curRow = $(this).closest('.element-input');
        $curRow.find('.red-text').remove();
        eid = $(this).data('sid');

        inputName = $curRow.find('input[name*="commname"]').val();
        wfiId = $curRow.find('input[name*="commname"]').attr('value') ?? "";
        inputType = $curRow.find('select[name*="type"] option:selected').val();
        inputEmail = $curRow.find('input[name*="email"]').val();

        const $form = $('.c-form form');
        $form.find('[name*="commname"]').val(inputName);
        $form.find('[name*="email"]').val(inputEmail);
        $form.find('select[name*="type"]').val(inputType);

        urlToPieces = vcurl.split('/');
        urlToPieces[urlToPieces.length - 1] = $(this).data('cid');
        vcurl = urlToPieces.join('/');
        addParams = `&wfiId=${wfiId}`;

        $.post(vcurl,$form.serialize().concat(addParams))
            .done(function(data){
                if(data.cliId){
                    btn.attr('data-cid',data.cliId);
                    btn.prev().attr('data-cid',data.cliId);
                    btn.closest('.client').find('.insert-individual-btn').removeAttr('disabled');
                }
                let elmtData = btn.closest('.element-input').prev();
                elmtData.find('.client-name').empty().append(inputName);
                elmtData.find('.client-email').empty().append(inputEmail);
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
                        }
                    })
                })
            })
    })

    $(document).on('click','.validate-ext-user-btn',function(e){
        e.preventDefault();
        btn = $(this);
        $curRow = $(this).closest('.element-input');
        $curRow.find('.red-text').remove();
        eid = btn.data('eid');
        cid = btn.data('cid');
        inputFName = $curRow.find('input[name*="firstname"]').val();
        inputLName = $curRow.find('input[name*="lastname"]').val();
        inputEmail = $curRow.find('input[name*="email"]').val();
        inputPosition = $curRow.find('input[name*="positionName"]').val();
        inputWeight = $curRow.find('input[name*="weight"]').val();
        const $form = $('.i-form form');
        $form.find('[name*="firstname"]').val(inputFName);
        $form.find('[name*="lastname"]').val(inputLName);
        $form.find('[name*="email"]').val(inputEmail);
        $form.find('[name*="positionName"]').val(inputPosition);
        $form.find('[name*="weight"]').val(inputWeight);
        urlToPieces = viurl.split('/');
        urlToPieces[urlToPieces.length - 4] = cid;
        urlToPieces[urlToPieces.length - 1] = eid;
        viurl = urlToPieces.join('/');

        $.post(viurl,$form.serialize())
        .done(function(data){
            if(data.extId){
                btn.attr('data-eid',data.extId);
                btn.prev().attr('data-eid',data.extId);
            }
            let elmtData = btn.closest('.element-input').prev();
            elmtData.find('.user-ext-fullname').empty().append(inputFName+' '+inputLName);
            elmtData.find('.user-ext-position-name').empty().append(inputPosition);
            elmtData.find('.user-ext-weight').empty().append(inputWeight);
            elmtData.find('.user-ext-email').empty().append(inputEmail);
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
                    }
                })
            })
        })


    })

    //Setup rules when modifying existing stages
    /*
    $(document).on('click', '.remove-external-user, .insert-btn', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();
        $collectionHolder = $('ul.clients');
        var total = $collectionHolder.data('total');
        var selectedIndex = ($(this).hasClass('insert-btn')) ? $collectionHolder.children().length : $collectionHolder.children().index($(this).closest('li'))+1;

        if($(this).hasClass('remove-external-user')){

            //$collectionHolder.data('total', $collectionHolder.data('total') - 1);
            if($collectionHolder.children().length == 1){
                $('.create-external-users').attr('disabled',true);
            }


            if(selectedIndex < $collectionHolder.children().length){
                for(i = selectedIndex+1;i <= $collectionHolder.children().length;i++){

                    $collectionHolder.find('h4:eq('+ (i-1) +')').text("User "+ (i-1));
                }
            }

            $(this).closest('li').remove();
            $collectionHolder.data('total',total-1);


        } else if ($(this).hasClass('insert-btn')){
            addClientUserForm($collectionHolder, $(this), selectedIndex);
        }
    });
    */

    $(document).on('click', '.remove-individual, .remove-orgteam', function(e) {
            
            var selectedClassSelector = '.' + $(this).attr('class').split(' ')[0].split('-')[1];
            index = $(selectedClassSelector).index($(this).closest(selectedClassSelector)) + 1;
            
            for(i = index+1;i <= $(selectedClassSelector).length;i++){
                titleElmt = $(selectedClassSelector).find('h4').eq(i-1);
                oldTextArray = titleElmt.text().split(' ');
                oldTextArray[oldTextArray.length - 1] = i - 1;
                newText = oldTextArray.join(' ');
                titleElmt.empty().append(newText);
            }

            $(this).closest(selectedClassSelector).remove();
    })

    function addClientForm(){
        // Get the data-prototype
        var prototype = $('ul.clients').data('prototype');
        var total = $('.client').length;
        // Replacing prototype constants
        var newForm = prototype
            .replace(/__clientNb__/g, total+1)
            .replace(/__name__/g, total+1);
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
            .replace(/__indivNb__/g, total+1)
            .replace(/__name__/g, total+1)
            .replace(/__DeleteIndivButton__/g, '<i class="remove-individual small material-icons" style="color: red">cancel</i>');
        
        $newForm = $(newForm);
        if($newForm.find('.delete-ext-user-btn').attr('data-cid') == 0){
            $newForm.find('.delete-ext-user-btn').attr('data-cid',$('.client').eq(index).find('.validate-client-btn').data('cid'));
            $newForm.find('.validate-ext-user-btn').attr('data-cid',$('.client').eq(index).find('.validate-client-btn').data('cid'))
        }
        $newForm.find('.tooltipped').tooltip();
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
