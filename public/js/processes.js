$(function () {
    $('.modal').modal();
    $('select').material_select();
    $collectionHolder = $('ul.processes');
    $totalTargets = $collectionHolder.find('.element').length;
    $collectionHolder.data('total', $totalTargets);
    if ($totalTargets == 0) {
        addProcessForm($collectionHolder);
    }

    $(document).on('click', '.fa-pencil-alt', function (e) {
        $(this).closest('.process-row').find('.element-data').hide();
        $(this).closest('.process-row').find('.element-input').show().find('input');
        $(this).closest('.process-row').find('.fa-pencil-alt').removeClass('fa-pencil-alt').addClass('fa-check').closest('a').addClass('blue darken-2 white-text btn').removeClass('btn-flat');
    });

    $(document).on('click', 'a:has(.fa-check)', function (e) {
        document.body.style.pointerEvents = 'none';

        const $submitBtn = $(this);
        e.preventDefault();
        $curRow = $(this).closest('.process-row');
        $curRow.find('.red-text').remove();
        $inputName = $curRow.find('input[name*="name"]');
        name = $inputName.val();
        gradable = $curRow.find('[name*="gradable"]').is(':checked') ? 1 : 0;
        masterUser = $curRow.find('select[name*="masterUser"]').val();
        parent = $curRow.find('select[name*="[parent]"]').val();
        process = $curRow.find('select[name*="[process]"]').val();
        /*$form.find('select[name*="masterUser"]').val($curRow.find('select[name*="masterUser"]').val());
        $form.find('select[name*="parent"]').val($curRow.find('select[name*="[parent]"]').val());
        $form.find('select[name*="process"]').val($curRow.find('select[name*="[process]"]').val())*/
        //$('form[name^="add"]').find('input[name*="name"]').val($inputName.val());
        const params = {name: name, gradable: gradable, masterUser: masterUser, process: process, parent: parent}
        eid = $(this).closest('.process-row').find('.fa-trash').closest('a').data('eid');
        urlToPieces = vpurl.split('/');
        urlToPieces[urlToPieces.length - 1] = eid;
        url = urlToPieces.join('/');
        const $post = $.post(url, params);
        $post.done(data => {
            location.reload();
            return;

            $curRow.find('.element-input').hide();
            $curRow.find('.element-name').text($inputName.val());
            $curRow.find('.element-master-user').text(masterUserName);
            $curRow.find('.element-parent').text(parentName);
            $curRow.find('.element-process').text(processName);
            $curRow.find('.element-data').show();
            $submitBtn.find('.fa-check').removeClass('fa-check').addClass('fa-pencil-alt').closest('a').removeClass('blue darken-2 white-text btn').addClass('btn-flat');
            if (eid == 0) {
                $curRow.find('.fa-trash').closest('a').removeClass('remove-element').addClass('modal-trigger').attr('data-eid', data.eid).attr('href', '#deleteElement');
            }
        }).fail(function (data) {
            document.body.style.pointerEvents = '';
            $inputName.after('<div class="red-text"><strong>' + data.responseJSON.errorMsg + '</strong></div>');
        });
    });

    // const $elementIdArray = $('.elementId');
    // const $elementArray = $('input[name*=processes]');
    // $elementIdArray.each(function () {
    //     let value = $(this).val();
    //     let valueId = $(this).attr('id');
    //     $elementArray.each(function () {
    //         if ($(this).val() == value) {
    //             $(this).attr("data-did", valueId);
    //         }
    //     });
    // });


    //Setup rules when modifying existing stages

    $(document).on('click', '.remove-element, .insert-btn', e => {
        const $this = $(e.currentTarget);

        // prevent the link from creating a "#" on the URL
        e.preventDefault();
        $collectionHolder = $('ul.processes');
        const total = $collectionHolder.data('total');
        const selectedIndex = $this.hasClass('insert-btn')
            ? $collectionHolder.children().length
            : $collectionHolder.children().index($this.closest('li')) + 1;

        if ($this.hasClass('remove-element')) {
            $this.closest('.process-row').remove();//permet de supprimer
        } else if ($this.hasClass('insert-btn')) {
            addProcessForm($collectionHolder, $this, selectedIndex);
        }
    });

    function addProcessForm($collectionHolder) {
        // Get the data-prototype
        const prototype = $collectionHolder.data('prototype');
        const total = $collectionHolder.data('total');
        const index = total + 1;

        // Replacing prototype constants

        var newForm = prototype
            .replace(/__nb__/g, index)
            .replace(/__name__/g, $('select.parent-select').length)
            .replace(/__DeleteButton__/g, '<i class="small remove-element material-icons" style="color: red">cancel</i>');

        // increase the index with one for the next item
        $collectionHolder.data('total', index);

        // Display the form in the page in an li, before the "Add a user" link li
        var $newFormLi = $('<li class="element"></li>').append(newForm);
        $newFormLi.find('.tooltipped').tooltip();

        //Remove temporarily
        $('.fixed-action-btn').addClass('floating-add').removeClass('fixed-action-btn');

        $collectionHolder.append($newFormLi);
        $newFormLi.find('select').material_select();
        onAddParentClick($('select.parent-select').length - 1);
        onAddProcessClick($('select.parent-select').length - 1);
        $('.floating-add').addClass('fixed-action-btn').removeClass('floating-add');
    }

    $(document).on('click', '[href="#deleteElement"]', function () {
        $('.delete-button').data('eid', $(this).attr('data-eid'));
    });

    $('.delete-button').on('click', e => {
        e.preventDefault();

        const $this = $(e.currentTarget);
        eid = $this.data('eid');
        const params = {id: eid};
        const $post = $.post(durl,params);
        $post.done(data => {
            $('[data-eid=' + eid + ']').closest('.process-row').remove();
            console.log(data);
        }).fail(data => console.log(data));
    });

    $('#requestNewProcess .process-request').on('click',function(e){
        e.preventDefault();
        headingName = $('#requestNewProcess form input').val();
        $.post(cpurl,$('#requestNewProcess form').serialize())
            .done(function(data){
                $('#addUserProcessActivitySuccess').modal('open');
                $('#processSelect').append(`<option value="${data.id}">${headingName}</option>`);
                $('#processSelect').val(data.id);
                $('#processSelect').material_select();
                console.log(data);
            })
            .fail(function(data){
                console.log(data)
            });
    });



    $('#reject').on('change',function(){
        if($(this).is(':checked')){
            $('#validateProcess form').hide();
            $('#validate').prop('checked',false);
        }
    })

    $('#validate').on('change',function(){
        if($(this).is(':checked')){
            $('#validateProcess form').show();
            $('#reject').prop('checked',false);
        }
    })

    $('#validate').on('change',function(){
        if($(this).is(':checked')){
            $('#validateProcess form').show();
            $('#reject').prop('checked',false);
        }
    })

    $('[href="#validateProcess"]').on('click',function(){
        $('#validateProcess input[name*="name"]').val($(this).closest('.process-row').find('.pending-process-name').text());
        $('#validateProcess select[name*="parent"]').val($(this).data('pid'));
        $('#validateProcess .process-request').attr('data-id',$(this).data('id'));
        $('#validateProcess .process-request').attr('data-type',$(this).data('type'));
    })

    $('#validateProcess .process-request').on('click',function(e){
        e.preventDefault();
        const id = $(this).data('id');
        const type = $(this).data('type');

        const params = {id: id,type: type};
        if($('#reject').is(':checked')){
            $.post(dpurl,params)
                .done(function(data){
                    $(`[href="#validateProcess"][data-id="${id}"][data-type="${type}"]`).closest('.element').remove();
                })
                .fail(function(data){
                    console.log(data);
                })
        } else {
            data = $('#validateProcess form').serialize() + '&' + $.param(params);
            $.post(vrurl,data)
                .done(function(data){
                    location.reload();
                })
                .fail(function(data){
                    console.log(data);
                })
        }
    })


});
