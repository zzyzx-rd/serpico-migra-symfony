$(function(){
    /*
    var stripe = Stripe('pk_test_51Hn5ftLU0XoF52vK8M36bvbf4dBV2ZDnSZnGCW9MDcEHUuznmIcSuDkU748TNbNfj9bGWbrvpfLu6qgkpMwNoylS00QptGVFj4');
    var $subForm = $('#subscriptionForm');
    var elements = stripe.elements();
    var card = elements.create('card');
    card.mount('#card-element');
    card.on('change', showCardError);
    var errors = document.getElementById('card-errors');
    const occurenceOptions = { month: 'numeric', day: 'numeric', hour: '2-digit', minute: '2-digit'}
    const renewalOptions = { month: 'numeric', day: 'numeric'};
    */

    $('.admin-section, .subscription-section').on('mouseover',function(){
        $(this).find('.section-act-btns').css('visibility','');
    }).on('mouseleave',function(){
        if(!$(this).find('.collapsible-body').is(':visible')){
            $(this).find('.section-act-btns').css('visibility','hidden');
        }
    })

    /*$('.subscription-section').on('mouseover',function(){
        $(this).find('.section-act-btns').css('visibility','');
    }).on('mouseleave',function(){
        $(this).find('.section-act-btns').css('visibility','hidden');
    })*/

    $('.new-admin-zone').on('mouseover',function(){
        $(this).find('.btn-flat').css('visibility','');
    }).on('mouseleave',function(){
        $(this).find('.btn-flat').css('visibility','hidden');
    })

    $('.collapsible-btn').on('click',function(){
        const $this = $(this);
        $this.blur();
        if($this.closest('section').find('.collapsible-body').is(':visible')){
            $this.empty().append($this.data('see-msg'));
        } else {
            $this.empty().append($this.data('hide-msg'));
        }
    })

    $(document).on('mouseover','.firm-users li',function(){
        $(this).find('[href="#updateUserRole"]').css('visibility','');
    }).on('mouseleave','.firm-users li',function(){
        $(this).find('[href="#updateUserRole"]').css('visibility','hidden');
    })

    $('.subscription-table tr').on('mouseover',function(){
        $(this).find('.user-subscription-btn').css('visibility','');
    }).on('mouseleave',function(){
        $(this).find('.user-subscription-btn').css('visibility','hidden');
    })

    $('.update-user-role-btn, .new-super-admin-btn').on('click',function(){
      const r = $('#roleSelector').val();
      const id = $(this).data('id');
        if(r == 1 && !$('#newSuperAdminWarning').is(':visible')){
            $('.new-super-admin-btn').attr('data-id',id);
            $('#newSuperAdminWarning').modal('open');
            return false;
        }

      const params = {id: id, r: r}
      $.post(uururl, params)
        .done(function(data){
            if(typeof data.cp !== "undefined"){
                window.location.href = location.origin + '/myactivities';
            }
            if(r > 2){
              $(`[href="#updateUserRole"][data-id="${id}"]`).closest('.account').remove();
              var nbAdmins = +$('.admin-section-title').find('.nb-users').text();
              $('.admin-section-title').find('.nb-users').empty().append(nbAdmins - 1);
            }

            $('.modal').modal('close');
          //$(`.delete-user-btn[data-id="${uid}"]`).closest('.account').remove();
        })
    });

    $('[href="#updateUserRole"]').on('click',function(){

        const $this = $(this);
        usrRole = $this.closest('.account').data('r');
        $('.user-fullname-value').empty().append($(this).closest('.account').find('.user-name').text());
        $('.update-user-role-btn').attr('data-id',$(this).data('id'));
        $(`#roleSelector option[value="${usrRole}"]`).prop('disabled',true);
        $('#roleSelector').material_select();
    })

    $('[href="#addUserClient"]').on('click',function(){

        $('#addUserClient').attr('data-qt','iua');
    });

    $('.get-invoices-btn').on('click',function(){
        const $modal = $('#getPaymentInvoices');
        if(!$modal.find('.subscription-table').length){
            $.get(gsurl,null)
            .done(function(subscriptions){
                if(!subscriptions){
    
                } else {
                    
                    var $table = $($('.subscriptions-zone').data('prototype'));
                    
                    $.each(subscriptions,function(i,s){
                        si = s.items.data[0];
                        var $row = $('<tr></tr>');
                        $row.append(`<td>${i+1}</td>`)
                            .append(`<td>${(new Date(s.created * 1000)).toLocaleString(lg+'-'+lg.toUpperCase(),occurenceOptions)}</td>`)
                            .append(`<td>${'Premium'}</td>`)
                            .append(`<td>${si.price.recurring.interval}</td>`)
                            .append(`<td>${si.quantity}</td>`)
                            .append(`<td>${ si.quantity * si.price.unit_amount_decimal * (1 + (si.tax_rates.length ? si.tax_rates[0].percentage / 100 : 0)) / 100 }</td>`)
                            .append(`<td>${s.status}</i></td>`)
                            .append(`<td>${(new Date(s.current_period_end * 1000)).toLocaleString(lg+'-'+lg.toUpperCase(),renewalOptions)}</i></td>`)
                            .append(`<td><a href="${s.invoices[0].hosted_invoice_url}" target="_blank"><i class="fa fa-file-invoice"></i></a></td>`)
                        $table.append($row);
                        
                    })
                }
                $('.subscriptions-zone').append($table);
                $('#getPaymentInvoices').modal('open');
            })
        } else {
            $('#getPaymentInvoices').modal('open');
        }

    })

    /*
    $subForm.on('submit', function (e) {
        e.preventDefault();
        if($('[name="card_sel"]').val()){
            paymentMethodId = $('[name="card_sel"]').val();
            createSubscription(cId,paymentMethodId,)
        } else {
            createPaymentMethod({card});
        }
    */


    /*
    $subForm.on('submit', function (e) {
        e.preventDefault();
        var modal = $('.modal')[0];
        modal.close;
        const latestInvoicePaymentIntentStatus = localStorage.getItem(
            'latestInvoicePaymentIntentStatus'
        );

        if($('[name="card_sel"]').val()){

        } else {
            createPaymentMethod({card});
        }


        if (latestInvoicePaymentIntentStatus === 'requires_payment_method') {

            const invoiceId = localStorage.getItem('latestInvoiceId');
            const isPaymentRetry = true;

            createPaymentMethod({
                card,
                isPaymentRetry,
                invoiceId,
            });

        } else {
            createPaymentMethod({card});
        }
    });

    function createPaymentMethod({card, isPaymentRetry, invoiceId ,elmt}) {
            
        label = $('.sub-choice');
        price = parseFloat($('.charged-price-zone .account-price'));
        //price = removeExtraSpace(price);
        product = "p";
        var pm = $('.newpaymentmethod');
        console.log(pm.hasClass('activecard'));

        if(!$('[name="card_sel"]').val()) {
            let billingName = 'tes';
            stripe.createPaymentMethod({
                type: 'card',
                card: card,
                billing_details: {
                    name: billingName,
                },
            })
                .then((result) => {

                    if (result.error) {

                    } else {

                        if (isPaymentRetry) {
                            retryInvoiceWithNewPaymentMethod({
                                customerId: customerId,
                                paymentMethodId: result.paymentMethod.id,
                                invoiceId: invoiceId,
                                priceId: parseInt(price),
                            });
                        } else {
                            test = createSubscription({
                                paymentMethodId: result.paymentMethod.id,
                                priceId: price,
                                product: product,
                                elmt: elmt,
                            });

                            modal = $('.modal').modal('close');
                        }
                    }
                });
        } else {

            test = createSubscription({
                paymentMethodId: $('[name="card_sel"]').val(),
                priceId: price,
                product: product,
                elmt: elmt,
            });
            modal = $('.modal').modal('close');
        }

        $.get(uaurl)
            .done(function(accounts){
                const $modal = $('#linkSubscriptionToUsers');
                const $table = $modal.find('table');
                const $subUnlinkedAccount = $($table.data('prototype')); 
                $.each(accounts, function(_i, a){
                    $subUnlinkedAccount.find('.user-profile-l-picture').data('src',a.pic);
                    $subUnlinkedAccount.find('.user-name').append(a.name);
                    $table.append($subUnlinkedAccount);
                })
            })

        $modal.modal('open', {dismissible: false});
    }

    function createSubscription({paymentMethodId, priceId, product}) {
            
        var isYearly = $('.switch input').is(":checked");
        val = $('.user').val();
        return (
            fetch('/create-subscription', {
                method: 'post',
                headers: {
                    'Content-type': 'application/json',
                },
                body: JSON.stringify({
                    pmId: paymentMethodId,
                    plan: product,
                    priceId: priceId,
                    quantity: +$('.user').val(),
                    period: isYearly ? "y" : "m",
                    num: $('.panel-active').attr('id')
                }),
            })
                .then((response) => {
                    console.log(result);
                    return response.json();
                })

                .then((result) => {
                    if (result.error) {
                        throw result;
                    } else {
                        console.log(result);
                    }
                    return result;
                })

                .then((result) => {

                    return {
                        paymentMethodId: paymentMethodId,
                        priceId: priceId,
                        subscription: result,
                    };

                })

                .catch((error) => {
                    showCardError(error);
                })
        );
    }

    function showCardError(event) {
        let displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    }

    $('[id*="card_"]').on('change',function(){
        if($(this).is('#card_new') && $(this).is(':checked')){
            $('.new-payment-zone').show();
        } else {
            $('.new-payment-zone').hide();
        }
    });

    $('.link-unsubscribed-accounts-btn').on('click',function(){
        const $modal = $('#linkSubscriptionToUsers');
        $.get(uaurl,null)
            .done(function(accounts){
                const $table = $modal.find('table');
                
                if(accounts.length && !$table.find('tbody tr').length){
                    
                    const $subUnlinkedAccount = $($table.data('prototype')); 
                    $.get(osurl,null)
                    .done(function(subscriptions){
                        
                            $selector = $(`<select name=""></select>`);
                            $.each(subscriptions,function(_i,s){
                            $selector.append(`<option value="${_i}">${'Premium Subscription ' + (_i + 1) + ' - ' + s.items.data[0].quantity + ' subs to associate'}</option>`);
                        });
                        $subUnlinkedAccount.find('.sub-zone').append($selector);
                        $('.no-users-to-associate').remove();
                        $.each(accounts, function(_i, a){
                            $subUnlinkedAccount.find('.user-profile-l-picture').attr('src',a.pic);
                            $subUnlinkedAccount.find('.user-name').append(a.name);
                            $subUnlinkedAccount.find('select').attr('name',`u_${a.id}`).material_select();
                            $subUnlinkedAccount.find('input[type="checkbox"]').attr('name',`assoc_${a.id}`);
                            $table.append($subUnlinkedAccount);
                        })
                        $modal.modal('open', {dismissible: false});
                    })

                } else {
                    if(!accounts.length){
                        $table.hide();
                    }
                    $modal.modal('open', {dismissible: false});
                }
                
            })

    })
    */

})