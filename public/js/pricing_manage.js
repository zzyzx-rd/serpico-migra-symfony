
console.log($('.user-slider')[0]);
$(document).ready(function () {

    $(this).find('.user-slider')[0].noUiSlider.on('slide', function (values, handle) {

        $('.user-slider')[0].nextElementSibling.innerHTML = Number(values[handle]);

        var price_1 = $('.switch input').is(':checked') ? 70 : 7;
        var price_2 = $('.switch input').is(':checked') ? 60 : 6;
        var price_3 = $('.switch input').is(':checked') ? 50 : 5;
        $('.price-value')[0].innerHTML =
            Math.min(100,Number(values[handle])) * price_1 + Math.max(0, Math.min(150, Number(values[handle]) - 100)) * price_2 + Math.max(0,Math.min(1000,Number(values[handle]) - 250)) * price_3;
        console.log(Math.min(100,Number(values[handle])) * price_1 + Math.max(0, Math.min(150, Number(values[handle]) - 100)) * price_2 + Math.max(0,Math.min(1000,Number(values[handle]) - 250)) * price_3);

       $userElmt = $('.user-nb');

        if(Number(values[handle]) == 1){
            $userElmt.text($userElmt.text().slice(0,-1));
        }
        if(Number(values[handle]) > 1 && $userElmt.text().charAt($userElmt.text().length - 1) != 's'){
            $userElmt.text($userElmt.text() + 's');
        }
    });
    $('.switch input').on('change',function(){
        const $card = $(this).closest('.card');
        if($(this).is(':checked')){
            $('.period-year').css('display','contents');
            $('.period-month').hide();
            } else {
            $('.period-year').hide();
            $('.period-month').show();
           }
    })
});