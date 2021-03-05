$(document).ready(function () {
    $('.modal').modal();
    $valprec = "month";
    $('select').material_select();
    var deletePanel;
    input = $('#freesubscription');
    console.log(input);
    val = $('.user').val();
    //priceTblStandard = [7,6,5];
    //priceTblPremium = [14,10,7];
    priceTblStandard = [15,15,15];
    priceTblPremium = [15,15,15];
    standard = $('.price-standard ');
    premium = $('.price-premium ');
    tblVal = [];
    console.log(val < 99 ,val < 249);
    period = $('.period').children("option:selected").val();
    $.each($('.price-period'),function(){
        $(this).text($(this).closest('.pricing-elmts').data('m'));
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
        standard.text((priceTblStandard[0]*val));
        premium.text((priceTblPremium[0]*val));
    } else if ( val < 249) {
        tblVal.push(priceTblStandard[1]*val);
        tblVal.push(priceTblPremium[1]*val);
        standard.text((priceTblStandard[1]*val));
        premium.text((priceTblPremium[1]*val));

    } else {
        standard.text((priceTblStandard[2]*val));
        premium.text((priceTblPremium[2]*val));

    }
    $('.number').on('change',function() {

        val = $(this).find('option:selected').val();
        //priceTblStandard = [7,6,5];
        //priceTblPremium = [14,10,7];
        priceTblStandard = [15,15,15];
        priceTblPremium = [15,15,15];
        standard = $('.standard h1');
        premium = $('.premium h1');

        console.log(standard,premium);
        if ( val == "1" ) {
        standard.text(priceTblStandard[0]);
            premium.text(priceTblPremium[0]);
        } else if ( val == "100") {
            standard.text(priceTblStandard[1]);
            premium.text(priceTblPremium[1]);

        } else {
            standard.text(priceTblStandard[2]);
            premium.text(priceTblPremium[2]);

        }
    })

    $('.user').on('input',function() {
        const nbUsers = $(this).val();
        const isYearly = $('.switch input').is(':checked');
        var totalPrice = Math.min(100,nbUsers) * (isYearly ? 150 : 15) + Math.max(0, Math.min(250,nbUsers) - 100) * (isYearly ? 150 : 15) +  Math.max(0, nbUsers - 250) * (isYearly ? 150 : 15);
        var VATPrice = (Math.round(totalPrice * 0.17 * 100) / 100);
        var chargedPrice = (Math.round((totalPrice + VATPrice) * 100) / 100);
        $('.price.nb-user').empty().append(nbUsers);
        $('.total-price').empty().append(totalPrice.toString().replace('.',','));
        $('.vat-price').empty().append(VATPrice.toString().replace('.',','));
        $('.charged-price').empty().append(chargedPrice.toString().replace('.',','));
    })

    $('.switch input').on('change',function() {
       
        const nbUsers = $('.user').val();
        const isYearly = $(this).is(':checked');
        isYearly ? $('.reduction').css('visibility','visible') : $('.reduction').css('visibility','hidden');
        isYearly ?  $('.price-per-user').empty().append(150) : $('.price-per-user').empty().append(15);
        var totalPrice = Math.min(100,nbUsers) * (isYearly ? 70 : 7) + Math.max(0, Math.min(250,nbUsers) - 100) * (isYearly ? 60 : 6) +  Math.max(0, nbUsers - 250) * (isYearly ? 50 : 5);
        var VATPrice = (Math.round(totalPrice * 0.17 * 100) / 100);
        var chargedPrice = (Math.round((totalPrice + VATPrice) * 100) / 100);
        $('.total-price').empty().append(totalPrice.toString().replace('.',','));
        $('.vat-price').empty().append(VATPrice.toString().replace('.',','));
        $('.charged-price').empty().append(chargedPrice.toString().replace('.',','));
    })

    /*
    $('.switch input').on('change',function() {

        const $this = $(this);

        priceTblStandard = [7,6,5];
        priceTblPremium = [14,10,7] ;
        standard = $(this).closest('.div-sub').find('.price-standard');
        premium = $(this).closest('.div-sub').find('.price-premium');
        isYearly = $this.is(':checked');
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

        p = isYearly ? 10 : 1; 

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


        if (!isYearly) {
            $.each($(reduction),function(){
                $(this).hide();
            })
            if($valprec != "month"){
            console.log(parseInt(standard.text()) / 10);
            standard.text((parseInt(standard.text()) / 10) + "€");
            premium.text((parseInt(premium.text()) / 10) + "€");
            $valprec = val;
        }}
        if(isYearly) {

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
    */

    $('.modal-trigger').on('click',function() {

        $.each($('.modal-trigger'),function(){
            $(this).removeClass('active');
        })
        $(this).addClass('active');

    })

    $('.unsubscribe').on('click',function() {

       $(this).parent().addClass('currentsub');

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
            $('.ZIPCode').text($('.user').val());
            $('.account-price').text(premium.text());
            $('.price-user').text(pricePremium+ "€"+"/ per "+period);
            } else {
            $('.standard-choice').addClass('sub-choice');
            $('.premium-choice').addClass('no-choice');
            $('.account-price').text(standard.text());
            $('.ZIPCode').text($('.user').val());
            $('.price-user').text(priceStandard+ "€"+"/ per "+period);
            }



    })

    $('.subscription-form button').on('click',function() {


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

        if($('.iban-info').css("display") == "block"){
            $('.iban-info').hide()
        }
        else {
            $('.iban-info').show()
        }

    });

    /*
    $(this).find('.user-slider')[0].noUiSlider.on('slide', function (values, handle) {
        const $card = $($(this)[0].target.closest('.card'));
        var price_1 = $card.find('.switch input').is(':checked') ? 150 : 15;
        var price_2 = $card.find('.switch input').is(':checked') ? 150 : 15;
        var price_3 = $card.find('.switch input').is(':checked') ? 150 : 15;
        $card.find('.user-slider')[0].nextElementSibling.innerHTML = Number(values[handle]);
        $card.find('.price-standard')[0].innerHTML = 
            Math.min(100,Number(values[handle])) * price_1 + Math.max(0, Math.min(150, Number(values[handle]) - 100)) * price_2 + Math.max(0,Math.min(1000,Number(values[handle]) - 250)) * price_3;
        $userElmt = $card.find('.user-nb');
        if(Number(values[handle]) == 1){
            $userElmt.text($userElmt.text().slice(0,-1));
        }   
        if(Number(values[handle]) > 1 && $userElmt.text().charAt($userElmt.text().length - 1) != 's'){
            $userElmt.text($userElmt.text() + 's');
        }
    });
    */

    $('.switch input').on('change',function(){
        const $card = $(this).closest('.card');
        if($(this).is(':checked')){
            $card.find('.reduction').css('visibility','visible');
            $card.find('.price-standard').text(parseInt($(this).closest('.card').find('.price-standard').text() * 10));
            $card.find('.price-period').empty().append($(this).closest('.pricing-elmts').data('y'));
        } else {
            $card.find('.reduction').css('visibility','hidden');
            $card.find('.price-standard').text(parseInt($(this).closest('.card').find('.price-standard').text() / 10));
            $card.find('.price-period').empty().append($(this).closest('.pricing-elmts').data('m'));
        }
    })

    var index = $('.existing-plans td').index($('.s-plan'));
    $('table').not('.existing-plans').find('td').each(function(i,e){
        if(i % 4 == index){$(e).css('background-color',$('.s-plan').hasClass('free-plan') ? '#eefff6' : '#f5eeff')};
    })

    $('.p-plan').on('mouseenter',function(){
        $('.p-plan').css({
            'background-color' : $('.s-plan').hasClass('premium-plan') ? '#684896' : '#f5eeff',
            'color' : $('.s-plan').hasClass('premium-plan') ? 'white' : '',
            'border' : $('.s-plan').hasClass('premium-plan') ? '1px solid white' : '',
        });

        $('.p-plan .dd-text').removeClass('dd-text').addClass('dd-orange-text');

    }).on('mouseleave',function(){
        $('.p-plan').css({
            'background-color' : $('.s-plan').hasClass('premium-plan') ? '#f5eeff' : 'white',
            'color' : '',
            'border' : '1px solid #55318e',
        });

        $('.p-plan .dd-orange-text').removeClass('dd-orange-text').addClass('dd-text');
    })


    $('.f-plan').on('mouseenter',function(){
        $('.f-plan').css({
            'background-color' : $('.s-plan').hasClass('free-plan') ? '#378a5e' : '#eefff6',
            'color' : $('.s-plan').hasClass('free-plan') ? 'white' : 'black',
            'border-bottom' : $('.s-plan').hasClass('free-plan') ? '1px solid white' : '',
        });
    }).on('mouseleave',function(){
        $('.f-plan').css({
            'background-color' : $('.s-plan').hasClass('free-plan') ? '#eefff6' : 'white',
            'color' : 'black',
            'border-bottom' : '1px solid #55318e',
        });
       
    })

    $('[name="payment_choice"]').on('change',function(){
        if($('#card_choice').is(':checked')){
            $('.by-card').show();
            $('.by-wire-transfer').hide();
        } else {
            $('.by-card').hide();
            $('.by-wire-transfer').show();
        }
    })


});









