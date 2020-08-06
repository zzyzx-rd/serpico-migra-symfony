
//var $newLinkLi = $('<li></li>').append($addUserLink);
let targetsId;
let targetsArray = [];
let targetsArrayId = [];
let focusedElmt;


$(function(){

    $('.modal').modal();
    $collectionHolder = $('ul.targets');
    $totalTargets = $collectionHolder.find('.target').length;
    $collectionHolder.data('total', $totalTargets);

    targetsArrayId = $(document).find('.targetsId');
    targetsArray = $(document).find('select*[name*="[cName]"]');
    //console.log(targetsArrayId);
    $.each(targetsArrayId, function() {
        let value = $(this).val();
        let valueId = $(this).attr('id');
        //console.log(value);
        //console.log(valueId);
        $.each(targetsArray, function() {
            if ($(this).val() == value) {
                $(this).attr("data-tid", valueId);
                //console.log($(this));
            }
        });
    });

    //Setup rules when modifying existing stages

    $(document).on('click', '.insert-btn, .delete-button, .delete-target, .remove-target, .delete-btn', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();
        $collectionHolder = $('ul.targets');
        var total = $collectionHolder.data('total');
        var selectedIndex = ($(this).hasClass('insert-btn')) ? $collectionHolder.children().length : $collectionHolder.children().index($(this).closest('li'))+1;
        
        if($(this).hasClass('remove-target')){

            //$collectionHolder.data('total', $collectionHolder.data('total') - 1);
            if($collectionHolder.children().length == 1){
                $('.create-targets').attr('disabled',true);
            }


            if(selectedIndex < $collectionHolder.children().length){
                for(i = selectedIndex;i <= $collectionHolder.children().length-1;i++){

                    $collectionHolder.find('h4:eq('+ (i) +')').text("Target "+ (i));
                }
            }

            $(this).closest('li').remove();
            $collectionHolder.data('total',total-1);


        } else if ($(this).hasClass('insert-btn')){
            addTargetForm($collectionHolder, $(this), selectedIndex);
            if($('.no-targets')){
                $('.no-targets').hide();
            }
            /*if($collectionHolder.data('total') == 1){
                $('.create-users').attr('disabled',false);
            }*/
        }
    });

    $('[href="#deleteTarget"]').on('click', function() {
        targetsId = $(this).closest('.target').find('select*[name*="[cName]"]').attr("data-tid");
        //console.log(targetsId);
        return targetsId;
    })

    $(document).on('click','.delete-button', function(e) {
        e.preventDefault();
        if($(this).data('tid') != 0){
            $.post(window.location.pathname)
                .done(function(data) {
                    urlToPieces = window.location.pathname.split('/');
                    urlToPieces[urlToPieces.length-1] = targetsId;
                    urlToPieces[urlToPieces.length-2] = "targets";
                    urlToPieces[urlToPieces.length-3] = "organization";
                    urlToPieces[urlToPieces.length-4] = "settings";
                    console.log(urlToPieces.slice(0, urlToPieces.length).join('/') + "/delete");
                    window.location = urlToPieces.slice(0, urlToPieces.length).join('/') + "/delete";
                })
                .fail(function(data) {
                    console.log(data);
                })
        }
        $(this).closest('.target').remove();
        if($('.target').length == 0){
            if($('.no-targets').length > 0){
                $('.no-targets').show();
            } else {
                $noTargets = $(/* html */`
                <div class="center no-targets" style="margin-top: 100px">
                    <i class="fa fa-bullseye fa-3x grey-text"></i>
                    <h4>{% trans %}targets.no_targets_title{% endtrans %}</h4>
                    <p style="text-align: center;">{% trans %}targets.no_targets_msg{% endtrans %}</p>
                </div>
                `);
            }
        }

    });
        
    $('[href="#deleteTarget"]').on('click',function(){
        $('#deleteTarget').find('.delete-button').data('tid',$(this).data('tid'));
    });


    function addTargetForm($collectionHolder) {
        // Get the data-prototype
        var prototype = $collectionHolder.data('prototype');
        var total = $collectionHolder.data('total');

        // Replacing prototype constants

        var newForm = prototype
            .replace(/__nb__/g, total+1)
            .replace(/__name__/g, total+1)

        // increase the index with one for the next item
        $collectionHolder.data('total', total + 1);

        // Display the form in the page in an li, before the "Add a user" link li
        var $newFormLi = $('<li class="target"></li>').append(newForm);
        $newFormLi.find('.tooltipped').tooltip();


        //Remove temporarily
        $('.fixed-action-btn').addClass('floating-add').removeClass('fixed-action-btn');

        $collectionHolder.append($newFormLi);
        $('.floating-add').addClass('fixed-action-btn').removeClass('floating-add');
    }

    // $(document).find('select*[name*="[cName]"]').on('mouseover', function() {
    //     focusedElmt = this.value;
    // })
    // $(document).find('select*[name*="[cName]"]').on('mouseover', function() {
    //     let selectedElmt = $(this).closest('.target');
    //     selectedElmt.find('option[value="' + focusedElmt + '"]').prop('disabled', false);
    //     selectedElmt.not($(this)).find('option[value="' + $(this).val() + '"]').prop('disabled', true);
    // }); 
});







