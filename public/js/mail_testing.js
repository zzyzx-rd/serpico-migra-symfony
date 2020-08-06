var $addUserMsg = (lg == "fr") ? 'Ajouter un paramètre' : 'Add user';
var $addUserLink = $('<div class="button-field center-button"><a class="btn-large waves-effect waves-light insert-btn center-button"><i class="fa fa-plus-circle"></i><span>' + $addUserMsg + '</span></a></div>');
//var $newLinkLi = $('<li></li>').append($addUserLink);



$(function(){

    $collectionHolder = $('ul.parameters');
    $collectionHolder.data('total', 0);
    //addParameterForm($collectionHolder);

    // Get the ul that holds the collection of users

    // add the "add a user" anchor and li to the users ul
    $collectionHolder.after($addUserLink);

    //Setup rules when modifying existing stages

    $(document).on('click', '.remove-parameter, .insert-btn', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();
        $collectionHolder = $('ul.parameters');
        var total = $collectionHolder.data('total');
        var selectedIndex = ($(this).hasClass('insert-btn')) ? $collectionHolder.children().length : $collectionHolder.children().index($(this).closest('li'))+1;

        if($(this).hasClass('remove-parameter')){

            //$collectionHolder.data('total', $collectionHolder.data('total') - 1);
            if($collectionHolder.children().length == 1){
                $('.create-users').attr('disabled',true);
            }


            if(selectedIndex < $collectionHolder.children().length){
                for(i = selectedIndex+1;i <= $collectionHolder.children().length;i++){

                    $collectionHolder.find('h4:eq('+ (i-1) +')').text("User "+ (i-1));
                }
            }

            $(this).closest('li').remove();
            $collectionHolder.data('total',total-1);


        } else if ($(this).hasClass('insert-btn')){
            addParameterForm($collectionHolder, $(this), selectedIndex);
        }
    });


    function addParameterForm($collectionHolder) {
        // Get the data-prototype
        var prototype = $collectionHolder.data('prototype');
        var total = $collectionHolder.data('total');

        // Replacing prototype constants

        var newForm = prototype
            .replace(/__nb__/g, total+1)
            .replace(/__name__/g, total+1)
            .replace(/__DeleteButton__/g, '<i class="small remove-user material-icons" style="color: red">cancel</i>');

        // increase the index with one for the next item
        $collectionHolder.data('total', total + 1);

        // Display the form in the page in an li, before the "Add a user" link li
        var $newFormLi = $('<li></li>').append(newForm);


        $collectionHolder.append($newFormLi);

    }


    $('form').on('submit',function(e){
        e.preventDefault();

        /*if(typeof $('.dropify')[0].files[0] != 'undefined'){
            var formData = new FormData($('.dropify')[0].files[0]);
            $.ajax({
                url : (lg =='fr') ? '/fr/ajax/users' : '/en/ajax/users',
                data: formData,
                type: 'POST',
                processData: false,
                contentType: false,
                success: function(response){
                    console.log("success")
                },

            })
        } else {*/

        //var formSerialize = $('form').serialize();
        $.post(url, $(this).closest('form').serialize())
            .done(function(data){
                $.each($('.red-text'),function(){
                    $(this).remove();
                })
                try {
                    var data = JSON.parse(data);
                    //field level
                    $.each(data, function(key, value){
                        //user level
                        $.each(value, function(key,value){
                            var userKey = key;
                            //prop level
                            $.each(value, function(key, value) {
                                $.each($('input, select'), function () {
                                    if ($(this).attr('name').indexOf(userKey) != -1 && $(this).attr('name').indexOf(key) != -1) {
                                        $(this).after('<div class="red-text"><strong>' + value + '</strong></div>');
                                        return false;
                                    }
                                })
                            })
                        })
                    })
                }
                catch(e){
                    if($('.red-text').length == 0){
                        /*url = window.location.pathname;
                        urlToPieces = url.split('/');
                        //urlToPieces.slice(-1)[urlToPieces.length-1] = 'participants';
                        window.location = urlToPieces.slice(0,urlToPieces.length-1).join('/')*/;
                    }
                }
            })
            .fail(function (data) {
                console.log(data)
            });


    })
})