$(document).ready(function () {
    $('.modal').modal();
    $valprec = "month";
    $('select').material_select();

    input = $('#freesubscription');
    console.log(input);
    val = $('.user').val();
    priceTblStandard = [7,6,5];
    priceTblPremium = [14,10,7] ;
    standard = $('.price-standard ');
    premium = $('.price-premium ');
    tblVal = [];
    console.log(val < 99 ,val < 249);
    period = $('.period').children("option:selected").val();
    $.each($('.price-period'),function(){
        $(this).text("/ per "+period);
    })
    if(period == "year"){
        $.each($('.reduction'),function(){
            $(this).show();
        })
    }
    if(val == 0) {

        $(this).val(1);
    }
    else if ( val < 99 ) {
        tblVal.push(priceTblStandard[0]*val);
        tblVal.push(priceTblPremium[0]*val);
        standard.text((priceTblStandard[0]*val)+"€");
        premium.text((priceTblPremium[0]*val)+"€");
    } else if ( val < 249) {
        tblVal.push(priceTblStandard[1]*val);
        tblVal.push(priceTblPremium[1]*val);
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
    $('.user').on('input',function() {

        val = parseInt($(this).val());
        console.log(val);
        priceTblStandard = [7,6,5];
        priceTblPremium = [14,10,7] ;
        nbUser = $(this).closest('.div-sub').find('.nb-user ');
        standard = $(this).closest('.div-sub').find('.price-standard ');
        premium = $(this).closest('.div-sub').find('.price-premium ');
        period = 1;
        if( $valprec == "year"){
            period = 10;
        }

        nbUser.text(val);

        if(val == 0) {

            $(this).val(1);
        }
        else if ( val < 100 ) {
            tblVal[0]=(priceTblStandard[0]*val)*period;
            tblVal[1]=(priceTblPremium[0]*val)*period;
            standard.text(((priceTblStandard[0]*val)*period)+"€");
            premium.text(((priceTblPremium[0]*val)*period)+"€");
        } else if ( val < 250) {
            tblVal[0]=(priceTblStandard[1]*val)*period;
            tblVal[1]=(priceTblPremium[1]*val)*period;
            standard.text(((priceTblStandard[1]*val)*period)+"€");
            premium.text(((priceTblPremium[1]*val)*period)+"€");

        } else if ( val > 250) {
            tblVal[0]=(priceTblStandard[2]*val)*period;
            tblVal[1]=(priceTblPremium[2]*val)*period;
            standard.text(((priceTblStandard[2]*val)*period)+"€");
            premium.text(((priceTblPremium[2]*val)*period)+"€");

        }

        if($(this).closest('.div-sub').find('.sub-choice').hasClass('premium-choice')){
            ;
            $('.account-price').text(premium.text());
            $('.price-user').text((pricePremium*p)+ "€"+"/ per "+$valprec);
        }
        else{

            $('.account-price').text(standard.text());
            $('.price-user').text((priceStandard*p)+ "€"+"/ per "+$valprec);
        }
        console.log(tblVal);
    })
    $('.period').on('change',function() {


        priceTblStandard = [7,6,5];
        priceTblPremium = [14,10,7] ;
        standard = $(this).closest('.div-sub').find('.price-standard ');
        premium = $(this).closest('.div-sub').find('.price-premium ');
        period = $('.period').children("option:selected").val();
        if ( val < 100 ) {
            pricePremium=priceTblPremium[0];
            priceStandard=priceTblStandard[0];
        } else if ( val < 250) {
            pricePremium=priceTblPremium[1];
            priceStandard=priceTblStandard[1];
        } else if ( val > 250) {
            pricePremium=priceTblPremium[2];
            priceStandard=priceTblStandard[2];

        }
        if(period == "year"){
            p=10;
        } else {
            p=1;
        }
        console.log($(this).closest('.div-sub').find('.sub-choice').hasClass('premium-choice'));
        if($(this).closest('.div-sub').find('.sub-choice').hasClass('premium-choice')){
        ;
            $('.account-price').text(premium.text());
            $('.price-user').text((pricePremium*p)+ "€"+"/ per "+period);
        }
        else{

            $('.account-price').text(standard.text());
            $('.price-user').text((priceStandard*p)+ "€"+"/ per "+period);
        }
        val = $(this).children("option:selected").val();
        var period = $(this).closest('.div-sub').find('.price-period');
        var reduction = $(this).closest('.div-sub').find('.reduction');
        if (val) {
            $.each($(period), function () {
                $(this).text("/ per " + val);
            })

        }


            if (val == "month") {
                $.each($(reduction),function(){
                    $(this).hide();
                })
                if($valprec != "month"){
                console.log(parseInt(standard.text()) / 10);
                standard.text((parseInt(standard.text()) / 10) + "€");
                premium.text((parseInt(premium.text()) / 10) + "€");
                $valprec = val;
            }}
            if(val == "year") {

                    $.each($('.reduction'),function(){
                        $(this).show();
                    })

                if($valprec != "year") {
                    console.log(parseInt(standard.text()) * 10);
                    standard.text((parseInt(standard.text()) * 10) + "€");
                    premium.text((parseInt(premium.text()) * 10) + "€");
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
    $('.subscribe').on('click',function() {
        priceTblStandard = [7,6,5];
        priceTblPremium = [14,10,7] ;
        standard = $(this).closest('.div-sub').find('.price-standard ');
        premium = $(this).closest('.div-sub').find('.price-premium ');
        period = $('.period').children("option:selected").val();

         if ( val < 100 ) {
        pricePremium=priceTblPremium[0];
        priceStandard=priceTblStandard[0];
        } else if ( val < 250) {
             pricePremium=priceTblPremium[1];
             priceStandard=priceTblStandard[1];
        } else if ( val > 250) {
             pricePremium=priceTblPremium[2];
             priceStandard=priceTblStandard[2];

        }

        $.each($('.choice'),function(){
            $(this).removeClass('sub-choice');
            $(this).removeClass('no-choice');
        })
        if($(this).closest('.flip-card').hasClass('premium')){
            $('.standard-choice').addClass('no-choice');
            $('.premium-choice').addClass('sub-choice')
            $('.nb-user').text($('.user').val());
            $('.account-price').text(premium.text());
            $('.price-user').text(pricePremium+ "€"+"/ per "+period);
            }
            else{
            $('.standard-choice').addClass('sub-choice');
            $('.premium-choice').addClass('no-choice');
            $('.account-price').text(standard.text());
            $('.nb-user').text($('.user').val());
            $('.price-user').text(priceStandard+ "€"+"/ per "+period);
            }



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

    $('.creditcard').on('click',function() {
        curentpanel = $(this);

        $.each($('.creditcard'),function(){
            $(this).removeClass('panel-active');
        })
        curentpanel.addClass('panel-active');
    });
    $('.choice').on('click',function() {
        curentpanel = $(this);
        standard = $(this).closest('.div-sub').find('.price-standard ');
        premium = $(this).closest('.div-sub').find('.price-premium ');
        period = $('.period').children("option:selected").val();
        if ( val < 100 ) {
            pricePremium=priceTblPremium[0];
            priceStandard=priceTblStandard[0];
        } else if ( val < 250) {
            pricePremium=priceTblPremium[1];
            priceStandard=priceTblStandard[1];
        } else if ( val > 250) {
            pricePremium=priceTblPremium[2];
            priceStandard=priceTblStandard[2];

        }
        if(period == "year"){
            p=10;
        } else {
            p=1;
        }
        $.each($('.choice'),function(){
            $(this).removeClass('sub-choice');
            $(this).removeClass('no-choice');
        })
        if(curentpanel.hasClass('standard-choice')) {
            $('.standard-choice').addClass('sub-choice');
            $('.premium-choice').addClass('no-choice');
            $('.account-price').text(standard.text());
            $('.price-user').text((priceStandard*p)+ "€"+"/ per "+period);
        }
        else{
            $('.standard-choice').addClass('no-choice');
            $('.premium-choice').addClass('sub-choice');
            $('.account-price').text(premium.text());
            $('.price-user').text((pricePremium*p)+ "€"+"/ per "+period);
        }
    });
    $('.cancelnewpayment').on('click',function() {
        var pm = $(this).closest('.modal').find('.newpaymentmethod');
        var btn = $(this).closest('.modal').find('.newpayment');
        pm.hide();
        pm.removeClass('activecard');
        btn.show();
    });  $('.close-modal').on('click',function() {
        $('.modal').modal('close');
    });


    $('.cancelcard').on('click',function() {
        var id = $(this).attr("id");
        console.log($(this).closest('.cardpay').hide());
        $.ajax({
            method: "POST",
            url: urlcardremove,
            data: { id: id },
            success: function () {

            }
        });
    });
    $('.fa-minus').on('click',function() {
        quantity = parseInt($('.quantity').text());
        if(quantity-1 != 0) {
            $('.quantity').text(quantity - 1);
            $.ajax({
                method: "POST",
                url: urlminuser,
                success: function () {

                }
            })
        }
    });
    $('.fa-plus').on('click',function() {
        quantity = parseInt($('.quantity').text());
        $('.quantity').text(quantity+1);
        $.ajax({
            method: "POST",
            url: urlplususer,
            success: function () {

            }
        })
    });
    $('.iban').on('click',function() {

        if($('.iban-info').css("display")=="block"){
            $('.iban-info').hide()
        }
        else {
            $('.iban-info').show()
        }

    });
});








