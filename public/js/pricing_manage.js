
console.log($('.user-slider')[0]);
$(document).ready(function () {

    $(this).find('.user-slider')[0].noUiSlider.on('slide', function (values, handle) {

        nbUsers =  Number(values[handle]);
        yearlyPrice = $('.switch input').is(':checked');
        divider = yearlyPrice ? 10 : 1;
        if($(window).width() < 600){
            if(nbUsers >= 14 / divider && nbUsers < 141 / divider){
                $('.price-value').css('font-size','35px');
            } else if (nbUsers >= 140 / divider && nbUsers < 1401 / divider) {
                $('.price-value').css('font-size','25px');
            } else if(nbUsers >= 1400 / divider){
                $('.price-value').css('font-size','20px');
            }
        }
        
        $('.user-slider')[0].nextElementSibling.innerHTML = nbUsers;

        var price_1 = $('.switch input').is(':checked') ? 150 : 15;
        var price_2 = $('.switch input').is(':checked') ? 150 : 15;
        var price_3 = $('.switch input').is(':checked') ? 150 : 15;

        $('.price-value')[0].innerHTML = Math.min(100,nbUsers) * price_1 + Math.max(0, Math.min(150, nbUsers - 100)) * price_2 + Math.max(0,Math.min(1000,nbUsers - 250)) * price_3;
       

       $userElmt = $('.user-nb');

        if(nbUsers == 1){
            $userElmt.text($userElmt.text().slice(0,-1));
        }
        if(nbUsers > 1 && $userElmt.text().charAt($userElmt.text().length - 1) != 's'){
            $userElmt.text($userElmt.text() + 's');
        }
    });
    $('.switch input').on('change',function(){
        const $card = $(this).closest('.card');
        var val = parseInt( $('.price-value').text());
        if($(this).is(':checked'))
            {
                $('.period-year').css('display','contents');
                $('.period-month').hide();
                $('.price-value').text(val*10);
                $('.free-month').show();
            }
        else
            {
                $('.period-year').hide();
                $('.period-month').show();
                $('.price-value').text(val/10);
                $('.free-month').hide();
            }
    })
});
