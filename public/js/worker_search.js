$(function(){

    $('[href="#validateMassFirm"]').on('click',function(){
        $('.workerFirm').each(function(key, element){
            
            if($(this).find('input[name*=HQCity]').val() == '' || $(this).find('input[name*=commonName]').val() == ''){
                var wFirmData = $('.wFirm').eq(key).find('a.firm-data');
                var locComponents = wFirmData.data('location').split(',');
                var city = locComponents[0];
                locComponents.shift();
                var state = locComponents.join(' ');
                if($(this).find('input[name*=commonName]').val() == ''){
                    $(this).find('input[name*=commonName]').val(wFirmData.data('cname'));
                }
                if($(this).find('input[name*=HQCity]').val() == ''){
                    $(this).find('input[name*=HQCity]').val(city);
                }
                if($(this).find('input[name*=HQState]').val() == ''){
                    $(this).find('input[name*=HQState]').val(state);
                }
            } 
     
        })
        
    });
    
    
    
    $('[href="#validateFirm"]').on('click',function(){
            if($(this).data('city') == ''){
                var locComponents = $(this).data('location').split(',');
                city = locComponents[0];
                locComponents.shift();
                state = locComponents.join(' ');
            } else {
                city = $(this).data('city');
                state = $(this).data('state');
            }
            
        $('.validate-firm').data('fid',$(this).data('fid'));
        $('input[name*=commonName]').val($(this).data('cname'));
        $('select[name*=mailPrefix]').val($(this).data('prefix'));
        $('input[name*=mailSuffix]').val($(this).data('suffix'));
        $('input[name*=City]').val(city);
        $('input[name*=State]').val(state);
        $('select[name*=Country]').val($(this).data('country'));
    });
    
    $('[href="#validateMail"]').on('click',function(){
        var firmMailPrefix = $(this).data('fmp');
        var firmMailSuffix = $(this).data('fms');
        $('.validate-mail').data('wid',$(this).data('wid'));
        var FNComponents = $(this).data('fullname').split(' ');
        var fn = FNComponents[0];
        FNComponents.shift();
        var ln = FNComponents.join(' ');
        switch(firmMailPrefix){
            case '1':
                mail = fn.toLowerCase()+'.'+ln.toLowerCase();
                break;
            case '2':
                mail = fn.charAt(0).toLowerCase()+ln.toLowerCase();
                break;
            case '3':
                mail = fn.charAt(0).toLowerCase()+'.'+ln.toLowerCase();
                break;
            case '4':
                mail = fn.charAt(0).toLowerCase()+ln.charAt(0).toLowerCase();
                break;
        }
        mail = mail+'@'+firmMailSuffix;
        $('input[name*=firstname]').val(fn);
        $('input[name*=lastname]').val(ln);
        $('input[name*=male]').val($(this).data('male'));
        $('input[name*=email]').val(mail);
    });

    $('[href="#writeMail"]').on('click',function(){
        if($(this).data('fid') != null){
            urlToPieces = gmurl.split('/');
            urlToPieces[urlToPieces.length - 1] = $(this).data("fid");
            gmurl = urlToPieces.join('/');
            $.post(gmurl,null)
                .done(function(data){
                    $('#individualSelector').empty();
                    if(data.options.length > 0){
                        $('#individualSelector').append('<label for="individualSelect" class="required" style="font-size: 1rem; color: black">Select individual :</label>');
                        $('#individualSelector').append('<select id="individualSelect" style="display: block!important;"></select>');
                    }
                    $.each(data.options,function(key,elmt){
                        if(key == 0){
                            $("#writeMailModal").data('wid',elmt.value);
                            $('.mail-submit').data('email',elmt.email);
                        }
                        $('#individualSelect').append('<option data-email="'+elmt.email+'" value="'+elmt.value+'">'+elmt.key+'</option>');
                    })
                })
                .fail(function(data){
                    console.log(data);
                })
        }
        $('.mail-submit').data('email',$(this).data('email'));
    })

    $('.validate-mass-firm').on("click",function(e){
        e.preventDefault();
        $.post(mvfurl,$('form[name="validate_mass_firm_form"]').serialize())
            .done(function(data){
                console.log(data);
                $('#validateMassFirm').modal('close');
                $('#validateMassFirmSuccess').modal('open');
                /*$('[href="#validateMail"]').each(function(key,elmt){
                    $(this).data('email',data.emails[key]).attr('href','#writeMail').find('i').removeClass('fa-send-o').addClass('fa-send');
                })*/
            })
            .fail(function(data){
                console.log(data)
            })
    })


    $('.validate-firm').on("click",function(e){
        e.preventDefault();
        urlToPieces = vfmurl.split('/');
        var fid = $(this).data("fid");
        var wid = $(this).data("wid");

        urlToPieces[urlToPieces.length - 1] = fid;

        vfmurl = urlToPieces.join('/');
        $.post(vfmurl,$('form[name="validate_firm_form"]').serialize())
            .done(function(data){
                console.log(data);
                $('#validateFirm').modal('close');
                $('#validateMail').data('wid',wid);
                if(typeof(wid) != "undefined"){
                    $('#validateMail').modal('open');
                    $("#validateMail").data('fmp',data.firmMailPrefix).data('fms',data.firmMailSuffix)
                    $('a[data-wid="'+ wid +'"]').attr('href','#validateMail');
                } else {
                    $('a[data-fid="'+fid+']"').data('wid',wid).find('i').removeClass('fa-send-o').addClass('fa-send');
                }
            })
            .fail(function(data){
                console.log(data)
            })
    })

    $('.validate-mail').on("click",function(e){
        e.preventDefault();
        urlToPieces = vmurl.split('/');
        var wid = $(this).data("wid");

        urlToPieces[urlToPieces.length - 1] = wid;

        vmurl = urlToPieces.join('/');
        $.post(vmurl,$('form[name="validate_mail_form"]').serialize())
            .done(function(data){
                console.log(data);
                $('#writeMail').data('wid',wid);
                $('#validateMail').modal('close');
                $('#validateMailSuccess').modal('open');
                $('a[data-wid="'+ wid +'"]').data('email',data.email).attr('href','#writeMail').find('i').removeClass('fa-send-o').addClass('fa-send');
            })
            .fail(function(data){
                console.log(data)
            })
    })

    $('.mail-submit').on("click",function(e){
        e.preventDefault();
        if($('input[type="radio"]:checked').val() == 1){
            urlToPieces = smurl.split('/');
            urlToPieces[urlToPieces.length - 5] = $('select[name*="language"] option:selected').text().toLowerCase();
            urlToPieces[urlToPieces.length - 1] = $('#individualSelector option:selected').val();    
            smurl = urlToPieces.join('/');
            $.post(smurl,$('form[name="send_mail_prospect_form"]').serialize())
                .done(function(data){
                    $('#writeMail').modal('close');
                    $('a[data-wid="'+ $("#writeMailModal").data("wid") +'"]').after('<a class="waves-effect waves-light btn-flat yellow lighten-3" href=""><i class="far fa-send"></i>1</a>')
                    $('#writeMailSuccess').modal('open');
                    console.log(data)
                })
                .fail(function(data){
                    console.log(data)
                })
        } else {
            var link = document.createElement('a');
            link.href = 'mailto:'+$('select[name*="chosenIndividual"] option:selected').data('email')+'?cc=team@serpicoapp.com&amp;subject=The%20subject%20of%20the%20email&amp;body=The%20body%20of%20the%20email';
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();    
        }
        
    })

    $('#individualSelect').on('change',function(){
        $("#writeMailModal").data('wid',$(this).find('option:selected').attr('value'));
        $('.mail-submit').data('email',$(this).find('option:selected').data('email'));
    })

    $('select[name*="country"]').on('change',function(){
        urlToPieces = csurl.split('/');
        urlToPieces[urlToPieces.length - 1] = $('select[name*="country"] option:selected').val();    
        csurl = urlToPieces.join('/');
        $.post(csurl,null)
                .done(function(data){

                    $('select[name*="state"]').empty();
                    $('select[name*="state"]').append('<option value="0">(Tous)</option>');
                    $.each(data.states,function(key,elmt){
                        $('select[name*="state"]').append('<option value="'+elmt.value+'">'+elmt.key+'</option>');
                    })
                    $('select[name*="city"]').empty();
                    $('select[name*="city"]').append('<option value="0">(Toutes)</option>');
                    $.each(data.cities,function(key,elmt){
                        $('select[name*="city"]').append('<option value="'+elmt.value+'">'+elmt.key+'</option>');
                    })
                    console.log(data);
                })
                .fail(function(data){
                    console.log(data)
                })
    })

    $('select[name*="state"]').on('change',function(){
        urlToPieces = scurl.split('/');
        urlToPieces[urlToPieces.length - 1] = $('select[name*="state"] option:selected').val();    
        scurl = urlToPieces.join('/');
        $.post(scurl,null)
                .done(function(data){

                    $('select[name*="city"]').empty();
                    $('select[name*="city"]').append('<option value="0">(Toutes)</option>');
                    $.each(data.cities,function(key,elmt){
                        $('select[name*="city"]').append('<option value="'+elmt.value+'">'+elmt.key+'</option>');
                    })
                    console.log(data);
                })
                .fail(function(data){
                    console.log(data)
                })
    })


});