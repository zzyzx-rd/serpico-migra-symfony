$(function(){

    $('.modal').modal();

    if ($('.modal').hasClass('error')){
        $('#login').modal('open');
    };

    $('select').material_select();

})