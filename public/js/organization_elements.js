
//var $newLinkLi = $('<li></li>').append($addelementLink);
let elementId;
let elementArray = [];
let elementIdArray = [];



$(function(){

    $('.modal').modal();
    $collectionHolder = $('ul.elements');
    $totalTargets = $collectionHolder.find('.element').length;
    $collectionHolder.data('total', $totalTargets);
    if($totalTargets == 0){
        addTargetForm($collectionHolder);
    }

    $(document).on('click','.fa-pencil-alt',function(e){
        $(this).closest('.row').find('.element-data').hide();
        $(this).closest('.row').find('.element-input').show().addClass('no-margin').find('input').addClass('no-margin');
        //$(this).hide();
        //$(this).closest('.row').find('.individual-btn').closest('li').show();
        $(this).closest('.row').find('.fa-pencil-alt').removeClass('fa-pencil-alt').addClass('fa-check').closest('a').addClass('blue darken-2 white-text btn').removeClass('btn-flat');
    });

    $(document).on('click','a:has(.fa-check)',function(e){

        $submitBtn = $(this);
        e.preventDefault();
        $curRow = $(this).closest('.row');
        $curRow.find('.red-text').remove();
        $clonedRow = $curRow.clone();
        $clonedRow.find('input[name*="name"]').attr('name','name');
        $clonedRow.find('select[name*="masterUser"]').attr('name','masterUser');
        $form = $('<form method="post" name="individual-element"></form>').append($clonedRow);
        eid = $(this).closest('.col').find('.fa-trash').closest('a').data('eid');
        $inputName = $curRow.find('input[name*="name"]');
        masterUserName = $curRow.find('select[name*="masterUser"] option:selected').text();
        $form.find('select').val($curRow.find('select[name*="masterUser"]').val())
        //$('form[name^="add"]').find('input[name*="name"]').val($inputName.val());
        urlToPieces = window.location.pathname.split('/');
        urlToPieces[urlToPieces.length - 1] = urlToPieces[urlToPieces.length - 1].slice(0,-1);

        urlToPieces.push('validate',eid);

        vurl = urlToPieces.join('/');

        $.post(vurl,$form.serialize())
            .done(function(data){
                $curRow.find('.element-input').hide();
                $curRow.find('.element-name').text($inputName.val());
                $curRow.find('.element-master-user').text(masterUserName);
                $curRow.find('.element-data').show();
                $submitBtn.removeClass('fa-check').addClass('fa-pencil-alt').closest('a').removeClass('blue darken-2 white-text btn').addClass('btn-flat');
                if(eid == 0){
                    $curRow.find('.fa-users').closest('li').show();
                    $curRow.find('.fa-bullseye').closest('li').show();
                    $curRow.find('.fa-trash').closest('a').removeClass('remove-element').addClass('modal-trigger').attr('data-eid',data.eid).attr('href','#deleteElement');
                }
            })
            .fail(function(data){
                $inputName.after('<div class="red-text"><strong>'+data.responseJSON.errorMsg+'</strong></div>');
            });
    })

    elementIdArray = $(document).find('.elementId');
    elementArray = $(document).find('input[name*=elements]');
    $.each(elementIdArray, function() {
        let value = $(this).val();
        let valueId = $(this).attr('id');
        $.each(elementArray, function() {
            // console.log(valueId);
            if($(this).val() == value){
                $(this).attr("data-did", valueId);
            }
        });
    });


    //Setup rules when modifying existing stages

    $(document).on('click', '.remove-element, .insert-btn', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();
        $collectionHolder = $('ul.elements');
        var total = $collectionHolder.data('total');
        var selectedIndex = ($(this).hasClass('insert-btn')) ? $collectionHolder.children().length : $collectionHolder.children().index($(this).closest('li'))+1;

        if($(this).hasClass('remove-element')){
            
            $(this).closest('.row').remove();

        } else if ($(this).hasClass('insert-btn')){
           
            addTargetForm($collectionHolder, $(this), selectedIndex);
           
        }
    });


    function addTargetForm($collectionHolder) {
        // Get the data-prototype
        var prototype = $collectionHolder.data('prototype');
        var total = $collectionHolder.data('total');

        // Replacing prototype constants

        var newForm = prototype
            .replace(/__nb__/g, total+1)
            .replace(/__name__/g, total+1)
            .replace(/__DeleteButton__/g, '<i class="small remove-element material-icons" style="color: red">cancel</i>');

        // increase the index with one for the next item
        $collectionHolder.data('total', total + 1);

        // Display the form in the page in an li, before the "Add a user" link li
        var $newFormLi = $('<li class="element"></li>').append(newForm);
        $newFormLi.find('.tooltipped').tooltip();


        //Remove temporarily
        $('.fixed-action-btn').addClass('floating-add').removeClass('fixed-action-btn');

        $collectionHolder.append($newFormLi);
        $('.floating-add').addClass('fixed-action-btn').removeClass('floating-add');
    }

    $(document).on('click','[href="#deleteElement"]',function(){
        $('.delete-button').data('eid',$(this).attr('data-eid'));
    });

    $('.delete-button').on('click',function(e){
        e.preventDefault();
        eid = $(this).data('eid');
        urlToPieces = window.location.pathname.split('/');
        urlToPieces[urlToPieces.length - 1] = urlToPieces[urlToPieces.length - 1].slice(0,-1);
        urlToPieces.push('delete',eid);
        durl = urlToPieces.join('/');
        $.post(durl)
            .done(function(data){
                $('[data-eid='+eid+']').closest('.row').remove();
                console.log(data);
            })
            .fail(function(data){
                console.log(data)
            });
    });

});







