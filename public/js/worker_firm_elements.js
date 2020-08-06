$(function(){

    $('[href="#validateMassMail"]').on('click',function(){
        $('.workingIndividual').each(function(key, element){
                
            if($('.individual').eq(key).find('.user-fullname').length > 0 ){
                var FNComponents = $('.individual').eq(key).find('.user-fullname').text().split(' ');
                var fn = FNComponents[0];
                FNComponents.shift();
                var ln = FNComponents.join(' ');
            } else {
                fn = $('.individual').eq(key).find('.user-firstname').text();
                ln = $('.individual').eq(key).find('.user-lastname').text();
                
            }         
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
            $(this).find('input[name*=firstname]').val(fn);
            $(this).find('input[name*=lastname]').val(ln);
            $(this).find('input[name*=email]').val(mail);
                
        })
        
    });

    $('[href="#validateMail"]').on('click',function(){
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
        $('.mail-submit').data('email',$(this).data('email'));
        $("#writeMail").data('wid',$(this).data("wid"));
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

    $('.validate-mass-mail').on("click",function(e){
        e.preventDefault();
        $.post(mvmurl,$('form[name="validate_mass_mail_form"]').serialize())
            .done(function(data){
                console.log(data);
                $('#validateMassMail').modal('close');
                $('#validateMailSuccess').modal('open');
                $('[href="#validateMail"]').each(function(key,elmt){
                    $(this).data('email',data.emails[key]).attr('href','#writeMail').find('i').removeClass('fa-send-o').addClass('fa-send');
                })
            })
            .fail(function(data){
                console.log(data)
            })
    });

    $('.mail-submit').on("click",function(e){
        e.preventDefault();
        if($('input[type="radio"]:checked').val() == 1){
            urlToPieces = smurl.split('/');
            urlToPieces[urlToPieces.length - 5] = $('select[name*="language"] option:selected').text().toLowerCase();
            var wid = $("#writeMail").data("wid");
            urlToPieces[urlToPieces.length - 1] = wid;
            smurl = urlToPieces.join('/');
            $.post(smurl,$('form[name="send_mail_prospect_form"]').serialize())
                .done(function(data){
                    console.log(data)
                    $('#writeMail').modal('close');
                    $('a[data-wid="'+ wid +'"]').after('<a class="waves-effect waves-light btn-flat yellow lighten-3" href=""><i class="far fa-send"></i>1</a>')
                    $('#writeMailSuccess').modal('open');
                })
                .fail(function(data){
                    console.log(data)
                })
        } else {
            var link = document.createElement('a');
            link.href = 'mailto:'+$(this).data('email')+'?cc=team@serpicoapp.com&amp;subject=The%20subject%20of%20the%20email&amp;body=The%20body%20of%20the%20email';
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();    
        }

    })

    $('[href="#deleteIndividual"]').on('click',function(){
        $('#deleteIndividual').find('.delete-button').data('win',$(this).data('win'));
    });

    $('.delete-button').on('click',function(e){

        var id = $(this).data("win");
        var clickedBtn = $('[data-win="'+ id +'"]');
        var removableElmt = clickedBtn.closest('.collapsible');
        var urlToPieces = deleteUrl.split('/');
        urlToPieces[urlToPieces.length-1] =  id;

        var url = urlToPieces.join('/');
        e.preventDefault();
        $.ajax({
            url : url,
            type : 'DELETE',
            success: function(jsonData){
                removableElmt.remove();
            },
            fail: function(data){
                console.log(data);
            },
        })
    });


});