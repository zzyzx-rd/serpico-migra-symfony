$(document).ready(function () {
    $('.modal').modal();
    $valprec = "month";
    $('select').material_select();

    input = $('#freesubscription');
    console.log(input);
    val = $('.user').val();
    priceTblStandard = [7,6,5];
    priceTblPremium = [14,10,7] ;
    standard = $('.standard h1');
    premium = $('.premium h1');

    console.log(val < 99 ,val < 249);
    if(val == 0) {

        $(this).val(1);
    }
    else if ( val < 99 ) {
        standard.text((priceTblStandard[0]*val)+"€");
        premium.text((priceTblPremium[0]*val)+"€");
    } else if ( val < 249) {
        standard.text((priceTblStandard[1]*val)+"€");
        premium.text((priceTblPremium[1]*val)+"€");

    } else {
        standard.text((priceTblStandard[2]*val)+"€");
        premium.text((priceTblPremium[2]*val)+"€");

    }
    $('.number').on('change',function() {

        val = $(this).find('option:selected').val();
        priceTblStandard = [7,6,5];
        priceTblPremium = [14,10,7] ;
        standard = $('.standard h1');
        premium = $('.premium h1');

        console.log(standard,premium);
        if ( val == "1" ) {
        standard.text(priceTblStandard[0]+"€");
            premium.text(priceTblPremium[0]+"€");
        } else if ( val == "100") {
            standard.text(priceTblStandard[1]+"€");
            premium.text(priceTblPremium[1]+"€");

        } else {
            standard.text(priceTblStandard[2]+"€");
            premium.text(priceTblPremium[2]+"€");

        }
    })
    $('.user').on('change',function() {

        val = parseInt($(this).val());

        priceTblStandard = [7,6,5];
        priceTblPremium = [14,10,7] ;
        standard = $('.standard h1');
        premium = $('.premium h1');
        period = 1;
        if( $valprec == "year"){
            period = 12;
        }
        console.log(val < 99 ,val < 249);
        if(val == 0) {

            $(this).val(1);
        }
        else if ( val < 99 ) {
            standard.text(((priceTblStandard[0]*val)*period)+"€");
            premium.text(((priceTblPremium[0]*val)*period)+"€");
        } else if ( val < 249) {
            standard.text(((priceTblStandard[1]*val)*period)+"€");
            premium.text(((priceTblPremium[1]*val)*period)+"€");

        } else {
            standard.text(((priceTblStandard[2]*val)*period)+"€");
            premium.text(((priceTblPremium[2]*val)*period)+"€");

        }
    })
    $('.period').on('change',function() {

        val = $(this).children("option:selected").val();
        priceTblStandard = [7,6,5];
        priceTblPremium = [14,10,7] ;
        standard = $('.standard h1');
        premium = $('.premium h1');




            if (val == "month") {
                if($valprec != "month"){
                console.log(parseInt(standard.text()) / 12);
                standard.text((parseInt(standard.text()) / 12) + "€");
                premium.text((parseInt(premium.text()) / 12) + "€");
                $valprec = val;
            }}
            if(val == "year") {
                if($valprec != "year") {
                    console.log(parseInt(standard.text()) * 12);
                    standard.text((parseInt(standard.text()) * 12) + "€");
                    premium.text((parseInt(premium.text()) * 12) + "€");
                    $valprec = val;
                }
            }

    })
    $('.modal-trigger').on('click',function() {

        $.each($('.modal-trigger'),function(){
            $(this).removeClass('active');
        })
        $(this).addClass('active');

    })
    $('.free').on('click',function() {

        $(this).closest('#subscription-form');
        console.log($('.sub').find(".MyCardElement").hide());
        divElement = $('.sub').find(".MyCardElement");
        console.log(divElement.find('.CardField'));
    })
    $('.buy').on('click',function() {

        $(this).closest('#subscription-form');
        console.log($('.sub').find(".MyCardElement").show());
        divElement = $('.sub').find(".MyCardElement");
    })

    $('.subscription-form button').on('click',function() {
        alert('test');

    });
    $('.newpayment').on('click',function() {
       var pm = $(this).closest('.modal').find('.newpaymentmethod');
       pm.show();
       pm.addClass('activecard');
       $(this).hide();
    });
    $('.cancelnewpayment').on('click',function() {
        var pm = $(this).closest('.modal').find('.newpaymentmethod');
        var btn = $(this).closest('.modal').find('.newpayment');
        pm.hide();
        pm.removeClass('activecard');
        btn.show();
    });
});








