
var replaceVars = {
    "janvier":"January","février":"February","mars":"March","avril":"April","mai":"May","juin":"June","juillet":"July","août":"August","septembre":"September","octobre":"October","novembre":"November","décembre":"December",
    "enero":"January","febrero":"February","marzo":"March","abril":"April","mayo":"May","junio":"June","julio":"July","agosto":"August","septiembre":"September","octubre":"October","noviembre":"November","diciembre":"December",
    "Janeiro":"January","Fevereiro":"February","Março":"March","Abril":"April","Maio":"May","Junho":"June","Julho":"July","Agosto":"August","Setembro":"September","Outubro":"October","Novembro":"November","Dezembro":"December",
};

var engRegex =  /January|February|March|April|May|June|July|August|September|October|November|December'/g ;
var regex = /janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre|enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre|Janeiro|Fevereiro|Março|Abril|Maio|Junho|Julho|Agosto|Setembro|Outubro|Novembro|Dezembro'/g ;
var lg;

function parseDdmmyyyy(str)
{
    var parts = str.split('/');
    return new Date(parts[2], parts[1] - 1, parts[0]);
}


$(function() {

    switch(lg){

        case 'fr':
            $.extend($.fn.pickadate.defaults, {
                monthsFull: ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'],
                monthsShort: [ 'Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec' ],
                weekdaysFull: [ 'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi' ],
                weekdaysShort: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ],
                today: 'Aujourd\'hui',
                clear: 'Effacer',
                close: 'Fermer',
                firstDay: 1,
                //format: 'dd mmmm yyyy',
            });
            break;
        case 'es':
            $.extend($.fn.pickadate.defaults, {
                monthsFull: [ 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre' ],
                monthsShort: [ 'ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic' ],
                weekdaysFull: [ 'domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado' ],
                weekdaysShort: [ 'dom', 'lun', 'mar', 'mié', 'jue', 'vie', 'sáb' ],
                today: 'hoy',
                clear: 'borrar',
                close: 'cerrar',
                firstDay: 1,
                //format: 'dddd d !de mmmm !de yyyy',
            });
            break;
        case 'pt':
            $.extend($.fn.pickadate.defaults, {
                monthsFull: [ 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro' ],
                monthsShort: [ 'jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez' ],
                weekdaysFull: [ 'Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado' ],
                weekdaysShort: [ 'dom', 'seg', 'ter', 'qua', 'qui', 'sex', 'sab' ],
                today: 'Hoje',
                clear: 'Limpar',
                close: 'Fechar',
                firstDay: 1,
                //format: 'd !de mmmm !de yyyy',
            });
            break;
        default:
            break;

    }

    $.extend($.fn.pickadate.defaults, {
        selectMonths: true,
        selectYears: 5,
        yearend: '31/12/2018',
        closeOnSelect: true,
        clear: false,
        //format : 'dd MMMM, yyyy',
        //formatSubmit: 'yyyy/mm/dd'
        //formatSubmit: 'yyyy/mm/dd'
    });

    $(document).on('mousedown', '.dp-mdate', function(e) {
        setTimeout(function (){
            $('.picker').one('mouseup',function(){
                var elmt = $(this);
                setTimeout(function (){
                    elmt.click();
                    elmt.focus();
                },100)
            })
        }, 20)
    })

    $(document).on('mousedown', '.dp-mtime', function(e) {
        setTimeout(function (){
            $('.clockpicker').one('mouseup',function(){
                var elmt = $(this);
                setTimeout(function (){
                    $('.dp-mtime').click();
                },100)
            })
        }, 20)
    })

    if(lg == 'fr'){
        $('.dp-mdate').pickadate({
            disable: [6, 7]
        });
    } else {
        $('.dp-mdate').pickadate({
            disable: [1, 7]
        });
    }

    //$('.dp-mdate').pickadate('picker').set('select',new Date()).set('min',new Date(Date.now() + 1 * 24 * 60 * 60 * 1000));

    $('.dp-mtime').pickatime({
        twelvehour : false,
        defaultTime : '10:00',
    });


    $('#firstContactModal').on('open',function(){
        if($('.clockpicker-hours .clockpicker-tick').length > 10){
            var removableElmts = [];
            $('.clockpicker-hours .clockpicker-tick').each(function(){
                if($('.clockpicker-hours .clockpicker-tick').index($(this)) < 10 || $('.clockpicker-hours .clockpicker-tick').index($(this)) > 18){
                    removableElmts.push($(this));
                }
            });
            for(i=0;i<removableElmts.length;i++){
                removableElmts[i].remove();
            }
        }

    });

    var url = window.location.pathname.split('/')[4];
    if($(window).width() < 920){
        $('nav ul li').eq(1).hide();
    }

    ($(window).width() < 800) ? $('#services p').addClass('center') : $('#services p').removeClass('center');

    $(window).bind('scroll', function () {
        if ($('li').hasClass('active')){
            $('.collapsible-body').slideUp();
            $('li, div').removeClass('active');
        }
    });

    $('.prev-btn, .next-btn').on('click',function(){
        $(this).closest('.modal').modal("close");
    })

    $('#videoModal').modal({
        dismissible:true,
        complete: function (){
            var videoURL = $('#presVideo').prop('src');
            videoURL = videoURL.replace("&autoplay=1", "");
            $('#presVideo').prop('src','');
            $('#presVideo').prop('src',videoURL);
        }
    });

    $('#register, #contactModal').modal({
        dismissible:true,
        complete:function(){
            var form;
            ($(this).attr('id') == 'register')? form = $("#registerForm") : form = $("#contactForm");
            if(!(form.find("[name=email], [name=full_name]").val()=="")){
                var formSerialize = form.serialize();
                ($(this).attr('id') == 'register') ? actionName = 'register' : actionName = 'contact';
                ($('.locale-flag').hasClass('france')) ? locale="fr" : locale="en";
                var url = locale+"/contact/"+actionName+"/abort";
                $.post(url, formSerialize, function(data) {}, 'JSON');
            }
        },
    });

    $('#retrievePwdForm').on('submit',function(e){
        e.preventDefault();
        ($('.locale-flag').hasClass('france')) ? locale="fr" : locale="en";
        var formSerialize =  $(this).serialize();
        //var rurl = locale + "/password/reset";
        $.post(rurl, formSerialize)
            .done(function(data){
                $('.modal').modal('close');
                $('#retrievePwdForm').find("[type=email]").val("");
                $('#retrievePwdSuccess').modal('open');
            })
            .fail(function(data){
                console.log(data);
            })
    });

    $('.contact-submit').on('click',function(e){
        e.preventDefault();
        var tmp = $(this).closest('form').serialize().split('&');
        tmp[1] = tmp[1].split('=');
        tmp[1][1] = $('.dp-mdate').pickadate('picker').get('select', 'dd/mm/yyyy');
        tmp[1] = tmp[1].join('=');
        tmp = tmp.join('&');

        $.post(curl, tmp)
            .done(function(data){

                $.each($('.red-text'),function(){
                    $(this).remove();
                });
                try {
                    var data = JSON.parse(data);
                    var whereIsTheError = 0;
                    $.each(data, function(key, value){
                        $.each($('form[name="contact_form"] input'),function(){

                            if($(this).attr('name').indexOf(key) != -1){
                                // Determine which modal to reopen to show the error
                                if($(this).closest('#firstContactModal').length > 0){
                                    whereIsTheError = 1;
                                } else {
                                    if(whereIsTheError == 0){
                                        whereIsTheError = 2;
                                    }
                                }
                                $(this).after('<div class="red-text"><strong>'+value+'</strong></div>');
                                return false;
                            }
                        })
                    })

                    $('#thirdContactModal').modal("close");
                    (whereIsTheError == 1) ? $('#firstContactModal').modal("open") : $('#secondContactModal').modal("open");

                }
                catch(e){
                    $('#thirdContactModal').modal("close");
                    $('#contactSuccess').modal("open");
                }
            })
            .fail(function(data){
                console.log(data);
            })
    });


    function submitModalForm(actionName){
        ($('.locale-flag').hasClass('france')) ? locale="fr" : locale="en";
        var url= locale+"/contact/"+actionName+"/sent";
        var formSerialize = $('#'+actionName+'Form').serialize();
        $.post(url, formSerialize, function(data) {
                success : {
                    $('.modal').modal('close');
                    $('#' + actionName +'Form').find("[type=text], [type=email], textarea").val("");
                    $('#' + actionName +'Success').modal('open');
                }
            }, 'JSON'
        );
    }

    $('.js-scrollTo').on('click', function() { // Au clic sur un élément
        var page = $(this).attr('href'); // Page cible
        var speed = 750; // Durée de l'animation (en ms)
        $('html, body').animate( { scrollTop: $(page).offset().top }, speed ); // Go
        return false;
    });


    var l = Number($('.calendar-element').attr('data-l'));
    var c = Number($('.calendar-element').attr('data-c'));

    var calendarWidth = $('.calendar-holder').width();
    var scale = calendarWidth / l;
    function getPercentage(min,max){
        var result = min / max;
        return result * 100;
    }

    var sd = 0;


    var actCurDate = $('.curDate');
    actCurDate.each(function(i,e){
      $(e).css({'left': Math.round(10000 * (c /l )) / 100 + '%' });
    });

    $.each($('.activity-component'), function (){
        var $this = $(this);
        pxWidthSD = 0;
        pxWidthP = scale;
        pctWidthSD = getPercentage(pxWidthSD, calendarWidth);
        pctWidthP = getPercentage(pxWidthP, calendarWidth);

        //$this.css({'margin-left': pctWidthSD + "%" });
        //$this.css({'width': pctWidthP + "%" });

        $this.find('.stage-element').each(function(){
            var ssd = $(this).data("sd");
            var p =  $(this).data("p");
            sPctWidthSD = (ssd - sd) / l;
            sPctWidthP = Math.max(3,(p + 1)) / (l + 1);
    
            $(this).css({'margin-left': Math.round(10000 * sPctWidthSD) / 100 + "%",
            'width': Math.round(10000 * sPctWidthP) / 100 + "%",
            'background' : ssd >= c ? '#5CD08F' : (ssd + p > c ? 'linear-gradient(to right, transparent, transparent ' + Math.round(10000 * (c - ssd) / p) / 100 + '%, #16AFB7 '+ Math.round(10000 * (c - ssd) / p) / 100 +'%), repeating-linear-gradient(61deg, #16AFB7, #16AFB7 0.5rem, transparent 0.5px, transparent 1rem)' : 'gray'),
            'height' : '7px',
            'border-radius' : '0.3rem',  
            });
        });
    });

    /*
    $('.stages-holder').on('mouseenter',function(){
        $(this).closest('.activity-holder').find('.act-info .fixed-action-btn').css('visibility','');
    }).on('mouseleave',function(){
      var $this = $(this);
        var interval = setInterval(function(){
          if(!$this.closest('.activity-holder').find('.act-info .fixed-action-btn').is(':hover')) {
            $this.closest('.activity-holder').find('.act-info .fixed-action-btn').css('visibility','hidden');
            clearInterval(interval);
          }
        },50);
    })*/

    $('.stages-holder').on('mouseenter',function(){
        var holderIndex = $('.stages-holder').index($(this));
        const $fixedBtn = $('.act-info').find('.fixed-action-btn').eq(holderIndex);
        $fixedBtn.css('visibility','');

    }).on('mouseleave',function(){
        var holderIndex = $('.stages-holder').index($(this));
        var interval = setInterval(function(){
          if(!$('.act-info').find('.fixed-action-btn').eq(holderIndex).is(':hover')) {
            $('.act-info').find('.fixed-action-btn').eq(holderIndex).css('visibility','hidden');
            clearInterval(interval);
          }
        },50);
    })

    $('.act-info .fixed-action-btn').each(function(i,e){
        const $fixedBtns = $('.act-info .fixed-action-btn');
        $(e).css('top', Math.round(100 * ($fixedBtns.index($(this)) / $fixedBtns.length)) + '%');
    })
});