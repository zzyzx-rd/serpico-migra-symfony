

$(function(){

    $('select').material_select();

    $('select').on('change',function(){
        const $userElmt = $(this).closest('.user-elmt');
        const $this = $(this);
        const $input = $userElmt;
        const inputVal = $this.find('option:selected').text();
        //$userElmt.find('.user-input').hide();
        $userElmt.find('a>span').empty().append($this.val() ? $userElmt.data('prefix-w')+''+ inputVal : $userElmt.data('prefix-wo'));
        //$userElmt.find('a').show();
        //$(this).val()
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

        $('.user-input:visible').not($targetedUserInput).each(function(i,e){
            if($(e).hasClass('user-select-fullname') && (!$(e).find('[name*="firstname"]').val().length || !$(e).find('[name*="lastname"]').val().length )
            || $(e).hasClass('user-select-email') && !$(e).find('[name*="email"]').val().length){
                return;
            }
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
        
    });

    $(document).on('click','.user-modify',function(e){
        $(this).closest('.element-data').hide();
        $(this).closest('.element-data').next().show();
    });

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

    $(document).on('click','.user-btn',function(){
        const $this = $(this);
        const $content = $(this).parent();
        const $mZone = $content.children().eq(-1);
        $this.hide();
        $mZone.find('select').material_select();
        $mZone.show();

        //$('.client-select-type .select-dropdown').addClass('no-margin');
        //$('.client-select-type').show();
        //$('.client-select-type select').material_select();
    });


    $("#add_user_form_submit").on('click', function(){
        var $mail = $("input[id *= 'email']").val(function(){
            return this.value.toLowerCase();
        });

    });

    const $collectionHolder = $('.users');

    // If user clicks in "Add activity" from one organization, we pass the org id in the modal request button
    $(document).on('click','[href="#addElement"]',function(){
        property = Object.keys($(this).data())[0];
        value = Object.values($(this).data())[0];
        if(property == 'wid'){
            $('#addElement').find('input').eq(0).attr({
                'id' : 'organization_element_value',
                'name' : 'organization_element[value]'
            })
            .prev().attr('for','organization_element_value')
            .empty().append(wgtLabel)
        } else {
            $('#addElement').find('input').eq(0).attr({
                'id' : 'organization_element_name',
                'name' : 'organization_element[name]'
            })
            .prev().attr('for','organization_element_name')
            .empty().append(nonWgtLabel)
        }
        if(property == 'did'){
            titleMsg = dptTitleMsg; 
            contentMsg = dptContentMsg; 
            successMsg = dptSuccessMsg;} 
        else if(property == 'pid'){
            titleMsg = posContentMsg; 
            contentMsg = posContentMsg; 
            successMsg = posSuccessMsg;
        }else if(property == 'tid'){
            titleMsg = titTitleMsg; 
            contentMsg = titContentMsg; 
            successMsg = titSuccessMsg;
        } else if(property == 'wid'){
            titleMsg = wgtTitleMsg; 
            contentMsg = wgtContentMsg; 
            successMsg = wgtSuccessMsg;
        }
        $('#addElementSuccess p').empty().append(successMsg);
        $('#addElement input[type="text"]').empty();
        $('#addElement h5').empty().append(titleMsg);
        $('#addElement p').empty().append(contentMsg);
        $('#addElement .element-submit').removeData().data(property,value);
    })


    $('.element-submit').on('click',function(e){
        e.preventDefault();
        if($(this).data('did') != null){
            e = "department";
            trig = $('[data-did="'+$(this).data("did")+'"]');
        } else if ($(this).data('pid') != null) {
            e = "position";
            trig = $('[data-pid="'+$(this).data("pid")+'"]');
        } else if ($(this).data('tid') != null) {
            e = "title";
            trig = $('[data-tid="'+$(this).data("tid")+'"]');
        } else if ($(this).data('wid') != null) {
            e = "weight";
            trig = $('[data-wid="'+$(this).data("wid")+'"]');
        }
        s = trig.closest('.col').find('select');
        const params = {e: e, id: 0};
        $.post(eurl,$(this).closest('form').serialize() + '&' + $.param(params))
            .done(function(data) {
                $.each($('.red-text'),function(){
                    $(this).remove();
                });
                s.append('<option value="'+data.id+'">'+data.name+'</option>');
                s.val(data.id);
                $('.modal').modal('close');
                $('#addElementSuccess').modal('open');
            })
            .fail(function (data) {
                $('#addElement').find('input[type="text"]').after('<div class="red-text"><strong>' + data.responseJSON + '</strong></div>');
                console.log(data)
            });
    });

    $('.delete-button').on('click',function(){
        $.ajax({
            url: window.location.pathname,
            method: 'DELETE',
        }).done(function(data){
            location.href = usersUrl;
        }).fail(function(data){
            console.log(data);
        })
    })


    if(creationPage != ""){

        //$collectionHolder = $('ul.users');
        $collectionHolder.data('total', 1);
    
        $('.csv-zone').hide();
        if($('#errors').length == 0){
            addUserForm($collectionHolder);
        }
    
        $('input[name="adding_type"]').on("change",function(){
            $(this).val() == 1 ? ($('.csv-zone').show() , $('.users').hide(), $('[name*="users"]').not($('[name*="usersCSV"]')).attr('disabled',true),$('.insert-btn').hide()) : ($('.csv-zone').hide(),$('.users').show(),$('[name*="users"]').not($('[name*="usersCSV"]')).attr('disabled',false),$('.insert-btn').show());
        });
        //$('form').find('i').eq(0).hide();
        //$('#addPosition').find('button').data('did',$('#add_user_form_users_1_department').val());
    
        $( "#add_organization_form_isFirm" ).on( "mouseup", function() {
            var isFirm =$("input:checked").val();
            console.log(isFirm);
            if (isFirm == 1){
                $("#add_organization_form_department").parent().hide();
            }else if(isFirm == 0){
                $("#add_organization_form_department").parent().show();
            }
        });
    
        // Get the ul that holds the collection of users
        //Setup rules when modifying existing stages
    
        $(document).on('click', '.remove-user, .insert-btn', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();
            var total = $collectionHolder.data('total');
            var selectedIndex = ($(this).hasClass('insert-btn')) ? $collectionHolder.children().length : $collectionHolder.children().index($(this).closest('li'))+1;
    
            if($(this).hasClass('remove-user')){
        
                if(selectedIndex < $collectionHolder.children().length){
                    for(i = selectedIndex+1;i <= $collectionHolder.children().length;i++){
                        $collectionHolder.find('.user-number').eq(i-1).empty().append(i-1);
                    }
                }
                $(this).closest('li').remove();
                $collectionHolder.data('total',total-1); 
    
            } else if ($(this).hasClass('insert-btn')){
                addUserForm($collectionHolder, $(this), selectedIndex);
            }
        });
    
    
        function addUserForm() {
            // Get the data-prototype   
            var prototype = $collectionHolder.data('prototype');
            var total = $collectionHolder.find('.user').length;
    
            // Replacing prototype constants
    
            var newForm = prototype
                .replace(/__nb__/g, total + 1)
                .replace(/__name__/g, total);
    
            // increase the index with one for the next item
            //$collectionHolder.data('total', total + 1);
    
            // Display the form in the page in an li, before the "Add a user" link li
            var $newFormLi = $(newForm);
            $newFormLi.find('.tooltipped').tooltip();

            //$('.insert-btn').add($newFormLi);
            //var posElmt = $newFormLi.find('[id$="position"]');
    
            var $selectedDptElmt = $newFormLi.find('[id$="department"]');
    
    
            if($collectionHolder.data('total') != 1){
                $selectedDptElmt.empty();
                var biggestLengthDptElement = 0;
                $('[id$="department"]').each(function(key, value) {
                    biggestLengthDptElement = ($(this).find('option').length > biggestLengthDptElement) ? key : biggestLengthDptElement;
                });
    
                var optionsToCopy = $('[id$="department"]').eq(biggestLengthDptElement).find('option').clone();
                $selectedDptElmt.append(optionsToCopy);
            }
    
            //Remove temporarily
            $('.fixed-action-btn').addClass('floating-add').removeClass('fixed-action-btn');        

            $collectionHolder.append($newFormLi);
            $newFormLi.find('select[name*="role"]').val(2);
            $newFormLi.find('select').material_select();
            $('.floating-add').addClass('fixed-action-btn').removeClass('floating-add');
        }
    
        $(document).on('change','.header-entry',function(){
    
            var selectedVal = $(this).val();
            $.each($('.header-entry').not($(this)),function(value){
                if($(this).val() == selectedVal){
                    $(this).val(-1);
                }
            });
    
            var selectIndex = $('.header-entry').index($(this));
    
            var headerEntries = $('.validate-csv-btn').data('headerOrderEntries');
            headerArray = headerEntries.split('-');
            headerArray[selectIndex] = $(this).val();
            headerEntries= headerArray.join('-');
            $('.validate-csv-btn').data('headerOrderEntries',headerEntries);
        })
    }
});







