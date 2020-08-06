

var $addCriterionNameMsg = (lg == "fr") ? 'Ajouter un critère' : 'Add criterion';
var $addCriterionNameLink = $('<div class="button-field center-button"><a class="btn-large waves-effect waves-light insert-btn center-button"><i class="fa fa-plus-circle"></i><span>' + $addCriterionNameMsg + '</span></a></div>');
//var $newLinkLi = $('<li></li>').append($addCriterionNameLink);
let criterionNameId;
let criterionNameArray = [];
let criterionNameIdArray = [];



$(function(){

    $('.modal').modal();
    $collectionHolder = $('ul.criterionNames');
    $totalTargets = $collectionHolder.find('.criterionName').length;
    $collectionHolder.data('total', $totalTargets);
    if($totalTargets == 0){
        addTargetForm($collectionHolder);
    }
    $('form').find('i').eq(0).hide();


    criterionNameIdArray = $(document).find('.criterionNameId');
    criterionNameArray = $(document).find('input[name*=criterionNames]');
    $.each(criterionNameIdArray, function() {
        let value = $(this).val();
        let valueId = $(this).attr('id');
        $.each(criterionNameArray, function() {
            // console.log(valueId);
            if($(this).val() == value){
                $(this).attr("data-did", valueId);
            }
        });
    });


    // Get the ul that holds the collection of users

    // add the "add a user" anchor and li to the users ul
    $collectionHolder.after($addCriterionNameLink);

    //Setup rules when modifying existing stages

    $(document).on('click', '.remove-criterionName, .insert-btn', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();
        $collectionHolder = $('ul.criterionNames');
        var total = $collectionHolder.data('total');
        var selectedIndex = ($(this).hasClass('insert-btn')) ? $collectionHolder.children().length : $collectionHolder.children().index($(this).closest('li'))+1;

        if($(this).hasClass('remove-criterionName')){

            //$collectionHolder.data('total', $collectionHolder.data('total') - 1);
            if($collectionHolder.children().length == 1){
                $('.create-criterionNames').attr('disabled',true);
            }


            if(selectedIndex < $collectionHolder.children().length){
                for(i = selectedIndex+1;i <= $collectionHolder.children().length;i++){

                    $collectionHolder.find('h4:eq('+ (i-1) +')').text("User "+ (i-1));
                }
            }

            $(this).closest('li').remove();
            $collectionHolder.data('total',total-1);


        } else if ($(this).hasClass('insert-btn')){
            addTargetForm($collectionHolder, $(this), selectedIndex);
            /*if($collectionHolder.data('total') == 1){
                $('.create-users').attr('disabled',false);
            }*/
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
            .replace(/__DeleteButton__/g, '<i class="small remove-criterionName material-icons" style="color: red">cancel</i>');

        // increase the index with one for the next item
        $collectionHolder.data('total', total + 1);

        // Display the form in the page in an li, before the "Add a user" link li
        var $newFormLi = $('<li class="criterionName"></li>').append(newForm);
        $newFormLi.find('.tooltipped').tooltip();


        //Remove temporarily
        $('.fixed-action-btn').addClass('floating-add').removeClass('fixed-action-btn');

        $collectionHolder.append($newFormLi);
        $('.floating-add').addClass('fixed-action-btn').removeClass('floating-add');
    }

    $('[href$=deleteCriterionName]').on('click', function(e){
        criterionNameId = $(this).closest('.criterionName').find('input[name*=criterionNames]').attr("data-did");
        // console.log(criterionNameId);
        return criterionNameId;
    });

    $('.delete-button').on('click',function(e){
        e.preventDefault();
        $.post(window.location.pathname)
            .done(function(data){
                console.log(window.location.pathname);
                urlToPieces = window.location.pathname.split('/');
                urlToPieces[urlToPieces.length - 3] = 'criterionName';
                urlToPieces[urlToPieces.length-2] = criterionNameId;
                urlToPieces[urlToPieces.length-1] = "delete";
                console.log(urlToPieces.slice(0,urlToPieces.length).join('/'));
                window.location = urlToPieces.slice(0,urlToPieces.length).join('/');
            })
            .fail(function(data){
                console.log(data)
            });
    });

});







