
function initPickates(){
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
    yearend: '31/12/2020',
    closeOnSelect: true,
    clear: false,
    //format : 'dd MMMM, yyyy',
    //formatSubmit: 'yyyy/mm/dd'
  });
  
  $('.dp-start, .dp-end, .dp-gstart, .dp-gend').each(function() {
    $(this).pickadate();
  });
}

initPickates();

var startCal = $('#createActivity').find('.dp-start');
var endCal = $('#createActivity').find('.dp-end');
var startDateTS = (startCal.val() == "") ? Date.now() : new Date(startCal.val());
var endDateTS = (endCal.val() == "") ? startDateTS : new Date(endCal.val());
var startDate = new Date(startDateTS);
var endDate = new Date(endDateTS);

startCal.pickadate('picker').set('select',startDate);
endCal.pickadate('picker').set('select',endDate).set('min',startDate);

function initETIcons() {
  const $stylizableSelects = $('#updateEvent select');
  $stylizableSelects.find('option').each(function (_i, e) {
    e.innerHTML = e.innerHTML.trim()
  });
  $stylizableSelects.material_select();

  const regExp = /~(.+)~/;

  $('#updateEvent .select-dropdown').each(function (_i, e) {
    const $this = $(e);
    const match = $this.val().match(regExp);
    let icon = String.fromCodePoint && match && match[1] ? String.fromCodePoint('0x' + match[1]) : '';

    if ($this.is('input')) {
      if (!match) return;
      $this.val($this.val().replace(regExp, icon));
    } else {
      $this.find('li > span').each(function (_i, e) {
        e.innerHTML = e.innerHTML.trim().replace(
          regExp,
          `<span class="et-icon" data-icon="${icon}"></span>`
        );
      });
    }
    $this.find('li').each(function(i,f){
      const $opt = $(f);
      $opt.addClass('flex-center');
      $opt.find('img').css({
        height : 'auto',
        width : '20px',
        margin : '0',
        float : 'none',
        color : '#26a69a',
      });
    });
   
    $this.addClass('stylized');

    });
}

$(function () {

  function setCookie(key, value, expiry) {
    var expires = new Date();
    expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
    document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
  }

  function getCookie(key) {
      var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
      return keyValue ? keyValue[2] : null;
  }

  function eraseCookie(key) {
      var keyValue = getCookie(key);
      setCookie(key, keyValue, '-1');
  }

  

  $('a:has(.fa-plus)').on('click', function () {
    $('.process-name').empty().append($(this).closest('ul').find('header').text())
    $('.start-btn,.launch-btn,.modify-btn').removeData().data('pid', $(this).data('pid'));
  });

  var now = new Date();
  var annee   = now.getFullYear();
  $('.dmin').append(annee +'<div class="line"></div>');
  $('.dmax').append(annee + 1 + '<div class="line"></div>');
  var centralElWidth = $('.activity-content-stage:visible').eq(0).width();
  var now = new Date();
  var annee = now.getFullYear();
  var c = getDay();
  var tDays = ndDayPerYears(annee);
  var dateChevron = $('.chevron');
  var actCurDate = $('.curDate');

  var echelle = centralElWidth / tDays;
  dateChevron.css({'left': 'calc('+Math.round(10000 * (c / tDays )) / 100 + '% - 10px)' });
  actCurDate.each(function(i,e){
    $(e).css({'left': Math.round(10000 * (c / tDays )) / 100 + '%' });
  });
  $('.activity-content-stage').css({
    'background' : 'repeating-linear-gradient(90deg, #f3ccff2b, #63009445 '+ centralElWidth / 12 +'px, #ffffff '+ centralElWidth / 12 +'px, #ffffff '+ centralElWidth / 6 +'px)'
  });

  $(window).on('resize',function(){
    //setTimeout(function(){
      var centralElWidth = $('.activity-content-stage:visible').eq(0).width();  
      var dateChevron = $('.chevron');
      var actCurDate = $('.curDate');
      dateChevron.css({'left': 'calc('+Math.round(10000 * (c / tDays )) / 100 + '% - 10px)' });
      actCurDate.each(function(i,e){
        $(e).css({'left': Math.round(10000 * (c / tDays )) / 100 + '%' });
      });
      $('.activity-content-stage').css({
        'background' : 'repeating-linear-gradient(90deg, #f3ccff2b, #63009445 '+ centralElWidth / 12 +'px, #ffffff '+ centralElWidth / 12 +'px, #ffffff '+ centralElWidth / 6 +'px)'
      });
    //}, 200);
  });

  $.each($('.activity-component'), function (){
    var $this = $(this);
    var sd = $this.data("sd");
    var p =  $this.data("p");
    var id = $this.data("id");
    pxWidthP = (p + 1) * echelle;
    pxWidthSD = sd * echelle;
    pctWidthSD = getPercentage(pxWidthSD, centralElWidth);
    pctWidthP = getPercentage(pxWidthP, centralElWidth);

    $this.css({'margin-left': pctWidthSD + "%" });
    $this.css({'width': pctWidthP + "%" });

    $this.find('.stage-element').each(function(){
      var ssd = $(this).data("sd");
      var sp =  $(this).data("p");
      
      sPctWidthSD = (ssd - sd) / p;
      sPctWidthP = Math.max(3,(sp + 1)) / (p + 1);

      $(this).css({'margin-left': Math.round(10000 * sPctWidthSD) / 100 + "%",
        'width': Math.round(10000 * sPctWidthP) / 100 + "%",
        'background' : ssd >= c ? '#5CD08F' : (ssd + sp > c ? 'linear-gradient(to right, transparent, transparent ' + Math.round(10000 * (c - ssd) / sp) / 100 + '%, #7942d0 '+ Math.round(10000 * (c - ssd) / sp) / 100 +'%), repeating-linear-gradient(61deg, #7942d0, #7942d0 0.5rem, transparent 0.5px, transparent 1rem)' : 'gray'),
        'height' : '7px',
        'border-radius' : '0.3rem',  
      });
    });

    $this.find('.event').each(function(){
      var od = $(this).data("od");
      var sp =  $(this).data("p");
      
      sPctWidthSD = (od - sd) / p;
      sPctWidthP = Math.max(3,(sp + 1)) / (p + 1);

      $(this).css({'margin-left': Math.round(10000 * sPctWidthSD) / 100 + "%",
        'width': Math.round(10000 * sPctWidthP) / 100 + "%",
        'height' : '15px',
        'border-radius' : '0.3rem',  
      });
    });
  });

  $('.stage-item-button').on('mouseenter',function(){
      var $this = $(this);
      $this.parent().css('z-index',999);
  }).on('mouseleave',function(){
      var $this = $(this);
      $this.parent().css('z-index',1);
  })
  
  

  initETIcons();


  $('.start-btn,.launch-btn,.modify-btn').on('click', function (e) {
    e.preventDefault();
    $.each($('.red-text'), function () {
      $(this).remove();
    });
    params = {};
    params['fi'] = 1;
    params['up'] = $('#processSelect').val() == 0 ? 1 : 0;
    params['m'] = $(this).hasClass('modify-btn') ? 1 : 0;
    params['an'] = $(this).closest('.modal') ? $(this).closest('.modal').find('input[name="activity_name"]').val() : '';
    params['im'] = $('#initializedActivity input[type="checkbox"]').is(':checked') ? 1 : 0;
    urlToPieces = aurl.split('/');
    pid = $(this).data('eid') ? $(this).data('eid') : 0;
    urlToPieces[urlToPieces.length - 1] = pid;
    url = urlToPieces.join('/');

    $.post(url, params)
      .done(function (data) {

        $('[id*="initializedActivity"]').modal('close');
        $("#activityCreationSuccess").modal('open');
        setTimeout(function(){
            window.location = data.redirect;
        },1200);
        /*$processBody = $('[data-pid="' + pid + '"]').closest('ul.flex-center').next();
        $activityHandler = $('<a href="/fr/activity/' + data.aid + '" class="collection-item">' + params['n'] + '</a>');
        if ($processBody.find('>.activities').length == 0) {
          $actCollectionHandler = $('<div class="activities collection"></div>');
          $processBody.empty().append($actCollectionHandler);
        } else {
          $actCollectionHandler = $processBody.find('>.activities');
        }
        $actCollectionHandler.append($activityHandler);
        */
      })
      .fail(function (data) {
        errorHtmlMsg = '';
        $(data.responseJSON).each((i,e) => errorHtmlMsg +='<strong>'+Object.values(e)[0]+'</strong>')
        $('#errorModal').find('.error-msg').append($(errorHtmlMsg));
        $('#errorModal').modal('open');
        console.log(data);
      });
  });

  $('[href="#chooseGradableStage"]').on('click', function () {
    $('#stageSelect').empty();
    aid = $(this).data('aid');
    urlToPieces = gsurl.split('/');
    urlToPieces[urlToPieces.length - 2] = aid;
    url = urlToPieces.join('/');
    $.post(url)
      .done(function (data) {
        $.each(data.stages, function (key, stage) {
          $('#stageSelect').append($('<option value="' + stage.id + '">' + stage.name + '</option>'));
          if (key == 0) {
            urlToPieces = $('#chooseGradableStage .btn').attr('href').split('/');
            urlToPieces[urlToPieces.length - 2] = stage.id;
            $('#chooseGradableStage .btn').attr('href', urlToPieces.join('/'));
          }
        })
      })
      .fail(function (data) {
        console.log(data);
      });
  });

  $('#stageSelect').on('change', function () {
    urlToPieces = $('#chooseGradableStage .btn').attr('href').split('/');
    urlToPieces[urlToPieces.length - 2] = $(this).val();
    $('#chooseGradableStage .btn').attr('href', urlToPieces.join('/'));
  })


  /*$('[href="#createActivity"]').on('click', function () {
    $('#processSelect').empty();
    oid = $(this).data('oid');
    urlToPieces = ipurl.split('/');
    urlToPieces[urlToPieces.length - 1] = oid;
    url = urlToPieces.join('/');
    $.post(url)
      .done(function (data) {
        $.each($('.red-text'), function () {
          $(this).remove();
        });
        $('#processSelect').append($('<option>' + '(Non liée à un process)' + '</option>'));
        $.each(data.processes, function (key, process) {
          $('#processSelect').append($('<option value="' + process.key + '">' + process.value + '</option>'));
        })
        console.log(data);
      })
      .fail(function (data) {
        console.log(data);
      });
  });
  */

  $('.status-selector:not(.showable) .stage-item-button').css('background-color','transparent');

  $('.tabs-t-view .stage-item-button').on('mouseenter',function(){
    $(this).parent().hasClass('showable') ? ($(this).find('.to-hide').show(), $(this).css('background-color','transparent')) : ($(this).find('.to-show').show(), $(this).css('background-color',""));
  }).on('mouseleave',function(){
    $(this).parent().hasClass('showable') ? ($(this).find('.to-hide').hide(), $(this).css('background-color',"")) : ($(this).find('.to-show').hide(),$(this).css('background-color','transparent'));
  });

  $('.change-date-type').on('click',function(){

    $this = $(this);
    $visibleChoiceEls = $this.prev().find('>:visible');
    $invisibleChoiceEls = $this.prev().find('>:not(:visible)');
    $visibleChoiceEls.hide();
    $invisibleChoiceEls.show();

    getCookie('date_type') == 's' ? 
      (eraseCookie('date_type'), setCookie('date_type','o',365), $('.stage-element.o-dates:not(.embedded)').show(), $('.stage-element.s-dates').hide()) : 
      (eraseCookie('date_type'), setCookie('date_type','s',365), $('.stage-element.o-dates').hide(), $('.stage-element.s-dates:not(.embedded)').show());
  })

  $('.tabs-t-view .stage-item-button').on('click',function(){
    var $this = $(this);
    var status = $this.parent().data('o-status') ? $this.parent().data('o-status') : $this.parent().data('p-status');
    if($this.parent().hasClass('showable')){
      setCookie('r_s_' + getCookie('sorting_type') + '_' + status,0,365);
      $('.activity-list').each(function(i,e){
        $(e).find(`.row[class*="${status}"]:visible`).hide();
        if(!$(e).find('>.row:visible').length){
          $(e).closest('.process-list--item').hide();
        }
      });

      $this.parent().removeClass('showable');
    } else {
      eraseCookie('r_s_' + getCookie('sorting_type') + '_' + status);
      $this.parent().addClass('showable');
      $(`.row[class*="${status}"]`).show();
      $(`.row[class*="${status}"]`).closest('.process-list--item').show();
    }

    $this.find('i').hide();

  })

  $('.process-list-t .activity-list').each(function(i,e){
    if(!$(e).find('> .row:visible').length){
      $(e).closest('.process-list--item').hide();
    }
  });

  $('.validate-btn').on('click', function (e) {
    e.preventDefault();
    urlToPieces = vmurl.split('/');
    urlToPieces[urlToPieces.length - 1] = $(this).data('mid');

    vmurl = urlToPieces.join('/');
    $.post(vmurl, null)
      .done(function (data) {
        console.log(data);
        $("#validateMail").modal('close');
        $("#validateMailSuccess").modal('open');
      })
      .fail(function (data) {
        console.log(data)
      });
  });

  $(document).on('click','[href="#deleteActivity"]',function(e){
    $('.delete-button').data('eid',$(this).attr('data-eid'));
  })

  // Modal button to delete activity
  $('.delete-button').on('click',function(e){
      $btn = $(this);
      const removableElmt = $btn.closest('')
      var urlToPieces = adeleteUrl.split('/');
      const eid = $btn.data('eid');
      urlToPieces[urlToPieces.length-1] = eid;

      var url = urlToPieces.join('/');
      e.preventDefault();
      $.ajax({
          url : url,
          type : 'POST',
          success: function(jsonData){
              $(`[href="#activityInfo"][data-eid="${eid}"]`).closest('.activity-list--item').remove();
              $('.modal').modal('close');
          },
          fail: function(data){
              console.log(data);
          },
      })
  });

  if($('.status-current').length){
      $('.status-current>a').click();
  }
  function ndDayPerYears(annee) {

    if(annee % 4 == 0 && annee % 100 == 0 && annee % 400 == 0 ){
        return 366;
    } else {
      return 365;
    }
  }
  function getDay()
{
	var MyDate = new Date;
	var PremierJour = Date.UTC(MyDate.getFullYear(), 0, 0);
	var Aujourdhui  = Date.UTC(MyDate.getFullYear(), MyDate.getMonth(), MyDate.getDate());
	return Math.floor((Aujourdhui - PremierJour) / (1000 * 60 * 60 * 24));
}
function getPercentage(min,max){
  var result = min / max;
  return result * 100;
}

$('[href="#requestNewProcess"]').on('click',function(){
  $('#requestNewProcess select').material_select();
})

$('.process-request').on('click',function(e){
  e.preventDefault();
  headingName = $('#requestNewProcess form input').val();
  $.post(cpurl,$('#requestNewProcess form').serialize())
      .done(function(data){
          $('#addUserProcessActivitySuccess').modal('open');
          $('#processSelect').append(`<option value="${data.id}">${headingName}</option>`);
          $('#processSelect').val(data.id);
          $('#processSelect').material_select();
          console.log(data);
      })
      .fail(function(data){
          console.log(data)
      });
});

$('[href="#createActivity"]').on('click',function(){
  $('.setup-activity').prepend('<i class="fa fa-cog sm-right"></i>')/*.append('<i class="fa fa-question-circle sm-left"></i>')*/;
});

$('.stages-holder').on('mouseenter',function(){
    
    $(this).closest('.activity-holder').find('.act-info .fixed-action-btn').css('visibility','');
    //$(this).closest('.activity-holder').find('.stages-holder').off('mouseenter');
}).on('mouseleave',function(){
  var $this = $(this);
    /*
    if(!$this.closest('.activity-holder').find('.act-info .fixed-action-btn').is(':hover')){
      $this.closest('.activity-holder').find('.act-info .fixed-action-btn').css('visibility','hidden');
    }*/
    
    var interval = setInterval(function(){
      if(!$this.closest('.activity-holder').find('.act-info .fixed-action-btn').is(':hover')) {
        $this.closest('.activity-holder').find('.act-info .fixed-action-btn').css('visibility','hidden');
        clearInterval(interval);
      }
    },50);
})

/*$('.fixed-action-btn').on('mouseenter',function(){
  $(this).closest('.activity-holder').find('.stages-holder').off('mouseenter');
}).on('mouseleave',function(){
  $(this).closest('.activity-holder').find('.stages-holder').on('mouseenter',function(){
      $(this).closest('.activity-holder').find('.act-info .fixed-action-btn').show();
  }).on('mouseleave',function(){
      $(this).closest('.activity-holder').find('.act-info .fixed-action-btn').hide();
  });
})
*/

  $('[href="#updateEvent"]').on('click',function(){
    initETIcons();
    $('.event-element-name').empty().append($(this).closest('.act-info').find('.act-info-name').text());
    $('.update-event-btn').attr('data-aid',$(this).attr('data-aid'));
    $('.update-event-btn').attr('data-sid',$(this).attr('data-sid'));
    $('.update-event-btn').attr('data-ms',$(this).attr('data-ms'));
    $('.update-event-btn').attr('data-eid',$(this).hasClass('btn-floating') ? 0 : $(this).attr('data-eid'));
  })

  $('.insert-document-btn, .insert-comment-btn').on('click',function(e){
    e.preventDefault();
    $holder = $(this).hasClass('insert-comment-btn') ? $('ul.comments') : $('ul.documents');
    $newProto = $($holder.data('prototype'));
    if($(this).hasClass('insert-document-btn')){
      $newProto.find('.dropify').dropify();
    }
    $holder.append($newProto);
  })

  $('.update-event-btn').on('click',function(e){
    e.preventDefault();
    const $this = $(this);
    const params = {sid: $this.attr('data-sid'), eid: $this.hasClass('btn-floating') ? 0 : $this.attr('data-eid'), mids: $('#partNotification').is(':checked')};
    data = $('#updateEvent form').serialize() + '&' + $.param(params);
    $.post(eurl,data)
        .done(function(data){
            location.reload();
        })
        .fail(function(data){
            console.log(data);
        })
  })

  $('.btn-participant-add').on('click',function(){
      const $this = $(this);
      const prototype = $('.participants-list').data('prototype');
      var newForm = prototype
            .replace(/__name__/g, $('.participants-list--item').length);
      let $newForm = $(newForm);
      $newForm.find('.validation-buttons').after('<select name="participantSelector" style="margin-top:45px"></select>');
      $newForm.addClass('edit');
      $this.before($newForm);
      $('.participants-list').css('margin-bottom','60px');
  })

  $(document).on('keyup','input[name*="fullname"]',function(event){
    var $this = $(this);
    var $selector = $(this).closest('.participants-list--item').find('[name*="participantSelector"]');
    $selectorMElmts = $selector.closest('.select-wrapper');

    if($this.val().length >= 3 /*&& event.keyCode != 8*/){
        //urlToPieces = surl.split('/');
        //urlToPieces[urlToPieces.length - 1] = $(this).val();
        //surl = urlToPieces.join('/');
        const params = {name: $this.val()};
        $.post(surl,params)
            .done(function(data){

                if(!data.qParts.length){
                    $this.removeAttr('value');
                    $selectorMElmts.hide();
                    return false;
                }
                
                $selector.closest('.select-wrapper').find('img').remove();
                $selector.empty();
                $.each(data.qParts,function(key,el){
                    let elId = el.e == 'team' ? el.teaId : (el.e == 'partner' ? el.extUsrId : el.usrId);
                    let elName = el.e == 'partner' ? el.orgName : (el.e == 'team' ? el.teaName : el.username);
                    let elPic = el.e == 'partner' ? el.logo : (el.e == 'team' ? el.teaPicture : el.usrPicture);
                    //$option = $(`<option class="flex-center" value=${firm.id}></option>`);
                    //$option.append(`<img class="firm-option-logo" src="/lib/img/org/${firm.logo ? firm.logo : 'no-picture.png'}">`)
                    //$option.append(`<span>${firm.name}</span>`);
                    $selector.append(`<option value="${elId}" data-type="${el.e}" data-fname="${el.wfiId != currentWfiId ? el.orgName : ''}" data-wid="${data.wfiId}" data-oid="${el.orgId ? el.orgId : ""}" data-pic="${elPic ? elPic : ""}" data>${elName}</option`);
                })
                //el.val(selector.find(":selected").text());
                //$selector.prepend(`<option value>(${noFirm})</option>`);
                //$this.attr("value",$selector.find(":selected").val());
                $selector.material_select();
                $selectorMElmts = $selector.closest('.select-wrapper')
                $selector.prev().find('li').each(function(i,e){
                    logo = $selector.find('option').eq(i).attr('data-pic');
                    elType = $selector.find('option').eq(i).attr('data-type');
                    orgName = $selector.find('option').eq(i).attr('data-fname');
                    folder = elType == 'partner' ? 'org' : elType;
                    //$selector.prev().find('li').index($(e)) == 0 ? ($(e).find('span').css('color','black'), $(e).prepend(`<img class="firm-option-logo" src="/lib/img/org/new-firm.png">`)) :
                    $(e).prepend(`<img class="firm-option-logo" src="/lib/img/${folder}/${logo ? logo : 'no-picture.png'}">`);
                    $(e).append(`<span class="el-type">${orgName != '' && elType != 'partner' ? orgName : elType}</span>`);
                    $(e).addClass('flex-center');
                    //$option.append(`<span>${firm.name}</span>`);
                    //selector.append($option);
                });
                //$selectorMElmts.prepend(`<img class="firm-input-logo" src="/lib/img/org/${data.workerFirms[0].logo ? data.workerFirms[0].logo : 'no-picture.png'}">`);


                //$('select[name="firmSelector"]').eq(index).show();
            })
            .fail(function(data){
               console.log(data);
            })
    } else {
        //if($selectorMElmts){
            $selectorMElmts.hide();
        //}
    }
  });

  $(document).on('change','[name*="participantSelector"]',function(){
      var $this = $(this);
      var $partElmt = $this.closest('.participants-list--item');
      var $selectedOpt = $this.find(":selected");
      //var index = $('.participants-list--item').index($this.closest('.participants-list--item'));
      if($this.val() != ""){
          $partElmt.find('.tooltipped').attr('data-tooltip',$selectedOpt.text()).tooltip();
          
          if(!$selectedOpt.attr('data-oid')){
            // ** AJAX CREATE CLIENT IN DB
          }

          switch($selectedOpt.attr('data-type')){
            case 'partner':
              $partElmt.find('input[name*="externalUser"]').attr('value',$this.val());
              break;
            case 'team':
              $partElmt.find('input[name*="team"]').attr('value',$this.val());
              break;
            case 'user':
              $partElmt.find('input[name*="user"]').attr('value',$this.val());
              break;
          }

          $this.val($this.val());
          $partElmt.removeClass('edit');
          $partElmt.closest('.participants-list').css('margin-bottom','');
      } else {
          $this.val("");
      }
      $partElmt.find('.selected-participant-logo').attr('src', $this.prev().find('li').eq($this.find('option').index($this.find('option:selected'))).find('img').attr('src'));
      
      if($this.val() != ""){
          $partElmt.find('.participant-fullname-zone').show();
          $partElmt.find('.participant-field-zone').hide();
      } else {
          $this.parent().hide();
      }
  });

  $('.create-activity').on('click',function(e){
    e.preventDefault();
    const $this = $(this);
    $.post(acurl,$this.closest('form').serialize())
      .done(function(data){
        console.log(data)
      })
      .fail(function(data){
        console.log(data)
      })
  })

});




