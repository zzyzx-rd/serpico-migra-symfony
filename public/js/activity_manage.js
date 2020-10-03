
function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

function getRandomInt(min, max) {
  min = Math.ceil(min);
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min + 1)) + min;
}

function getDay(){
  var MyDate = new Date;
  var PremierJour = Date.UTC(MyDate.getFullYear(), 0, 0);
  var Aujourdhui  = Date.UTC(MyDate.getFullYear(), MyDate.getMonth(), MyDate.getDate());
  return Math.floor((Aujourdhui - PremierJour) / (1000 * 60 * 60 * 24));
}

function dateFromDay(year, day){
  var date = new Date(year, 0); // initialize a date in `year-01-01`
  return new Date(date.setDate(day)); // add the number of days
}

function ndDayPerYears(annee) {

  if(annee % 4 == 0 && annee % 100 == 0 && annee % 400 == 0 ){
      return 366;
  } else {
    return 365;
  }
}

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

var now = new Date();
var annee = now.getFullYear();
var c = getDay();
var tDays = ndDayPerYears(annee);
var annee = now.getFullYear();
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

  if($('.no-processes').length){

    $('.dmin').append('<span class="starting-mark">' + annee + '</span>' + '<div class="line-no-processes"></div>');
    $('.dmax').append('<span class="ending-mark">' + (annee + 1) + '</span>' + '<div class="line-no-processes"></div>');
    
  } else {

    $('.dmin').append(annee + '<div class="line"></div>');
    $('.dmax').append(annee + 1 + '<div class="line"></div>');
  }

  /**
   * Display activities in a proper way
   * @param {HTMLInputElement} $activities
   * @param bool setEvents
   */
  function displayTemporalActivities($activities, $setEvents){

    var centralElWidth = $('.activity-content-stage:visible').length ? $('.activity-content-stage:visible').eq(0).width() : $('.dummy-activities-container').width() * 0.75;
    var actCurDate = $activities.find('.curDate');
    var echelle = centralElWidth / tDays;
    
    actCurDate.each(function(i,e){
      $(e).css('left', Math.round(10000 * c / tDays) / 100 + '%');
    });
  
  
  
    $activities.find('.activity-content-stage').css({
      'background' : 'repeating-linear-gradient(90deg, #f3ccff2b, #63009445 '+ centralElWidth / 12 +'px, #ffffff '+ centralElWidth / 12 +'px, #ffffff '+ centralElWidth / 6 +'px)'
    });

    $activities.find('.activity-component').each(function (){
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
    });

    if($setEvents){

      $activities.find('.event').each(function(){
        var sd = $(this).closest('.activity-component').data("sd");
        var p = $(this).closest('.activity-component').data("p");
        var ed = $(this).data("od");
        var ep =  $(this).data("p") ? $(this).data("p") : 0;
        
        sPctWidthSD = (ed - sd) / p;
        sPctWidthP = Math.max(3,(ep + 1)) / (p + 1);
  
        $(this).css({'margin-left': Math.round(10000 * sPctWidthSD) / 100 + "%",
          'width': Math.round(10000 * sPctWidthP) / 100 + "%",
          'height' : '15px',
          'border-radius' : '0.3rem',  
        });
      });
    
    }
  
  }

  $('.chevron').css({'left': 'calc('+Math.round(10000 * (c / tDays )) / 100 + '% - 10px)' });

  displayTemporalActivities($('.activity-holder'),true);

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

  /*
  $(document).on('mouseenter','.stage-item-button',function(){
      var $this = $(this);
      $this.parent().css('z-index',999);
  }).on('mouseleave',function(){
      var $this = $(this);
      $this.parent().css('z-index',1);
  });
  */

  $(document).on('mouseover',function(e){
    var $this = $(e.target);
    if($this.closest('.virtual-activities-holder').length){
      $('.no-activity-overlay').css('visibility','hidden');
    }
  }).on('mouseout',function(e){
    var $this = $(e.target);
    if(!$this.closest('.virtual-activities-holder').length){
      $('.no-activity-overlay').css('visibility','');
    }
  });
  /*.on('mouseleave',function(){
    $('.no-activity-overlay').css('visibility','');
  });*/

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
  });


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
  });

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

  });

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
  });

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
    if(!$('.setup-activity').find('.fa-cog').length){
      $('.setup-activity').prepend('<i class="fa fa-cog sm-right"></i>')/*.append('<i class="fa fa-question-circle sm-left"></i>')*/;
    }
    if(!$('.participants-list--item').length){
      directInsert(myself,un,uid,userPic,'u');
    }
  });

  $('.activity-holder').on('mouseover',function(){
      
      $(this).find('.act-info .fixed-action-btn').css('visibility','');
      //$(this).closest('.activity-holder').find('.stages-holder').off('mouseenter');
  }).on('mouseout',function(e){

    var $relatedTarget = $(e.relatedTarget);
    if($relatedTarget != $(this)){
      $(this).find('.act-info .fixed-action-btn').css('visibility','hidden');
    }
   
  });



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
      if(!$this.parent().find('.edit').length){
        const prototype = $('.participants-list').data('prototype');
        var newForm = prototype
              .replace(/__name__/g, $('.participants-list--item').length);
        let $newForm = $(newForm);
        $newForm.find('.validation-buttons').after('<select name="participantSelector" style="margin-top:45px"></select>');
        $newForm.addClass('edit');
        $this.before($newForm);
        $('.participants-list').css('margin-bottom','60px');
      }
  })


  /** Function which directly insert participant in the activity - tn for tooltip, fn fullname, secId in case of extUsr  */
  function directInsert(tn, fn, id, pic = null, type = null, secId = null){

    if($('.participants-list--item.edit').length){
      $partElmt = $('.participants-list--item.edit');
    } else {
      var prototype = $('.participants-list').data('prototype');
      var newForm = prototype
        .replace(/__name__/g, $('.participants-list--item').length);
      $partElmt = $(newForm);
    } 

    $partElmt.find('.validation-buttons').after('<select name="participantSelector" style="margin-top:45px"></select>');
    $partBtn = $partElmt.find('.participant-btn');
    $partFZone = $partElmt.find('.participant-field-zone');
    $partFZone.find('.participant-fullname').val(fn).addClass('part-feeded');
    $partFZone.find('.participant-fullname').prev().addClass('active');
    var $img = $partBtn.find('.selected-participant-logo');
    $img.attr('src',pic);
    $partBtn.attr('data-tooltip',tn).tooltip();
    etype = type ? type : data.type;
    switch(etype){
      case 'eu' : 
        $partFZone.find('input.u').attr('value',id);
        $partFZone.find('input.eu').attr('value',secId);
        break;
      case 'u' :
        $partFZone.find('input.u').attr('value',id);
        break;
      case 't' :
        $partFZone.find('input.t').attr('value',id);
        break;
    }
    var $inputImg = $img.clone();
    $inputImg.attr('class','');
    $inputImg.addClass('input-img');
    $inputImg.css({
      'position': 'absolute',
      'left': '0',
      'top': '15%',
      'height': '30px',
    });
    $partFZone.append($inputImg);
    $partBtn.show();
    $partElmt.removeClass('edit');       
    $partFZone.hide();
    $('.btn-participant-add').before($partElmt);
  }

  $(document).on('keyup','input[name*="firm"]',function(e){

    var $this = $(this);
    var $selector = $('[name="firmSelector"]');
    if($selector.find('option:selected').length && $this.val() != $selector.find('option:selected').text()){
      $inputZone = $this.parent();
      $inputZone.find('input').not($this).each(function(i,e){
        $(e).removeAttr('value');
      })
      $inputZone.find('.input-f-img').remove();
      $this.removeClass('part-feeded');
    }
    $selectorMElmts = $selector.closest('.select-wrapper');

    if($this.val().length >= 3){
        
        const params = {name: $this.val(), type:'firm'};
        $.post(surl,params)
            .done(function(data){

                if(!data.qParts.length){
                    $this.removeAttr('value');
                    $selector.empty();
                    $selector.material_select();
                    //$selectorMElmts.hide();
                    return false;
                }
                
                $selector.closest('.select-wrapper').find('img').remove();
                $selector.empty();
                $.each(data.qParts,function(key,el){
                    let elName = el.orgName;
                    let elPic = el.logo;
                    $selector.append(`<option value="${el.wfiId}" data-wid="${el.wfiId}" data-oid="${el.orgId ? el.orgId : ''}" data-cid="${el.cliId ? el.cliId : ''}" data-pic="${elPic ? elPic : ""}">${elName}</option`);
                })
                $selector.material_select();
                $selectorMElmts = $selector.closest('.select-wrapper')
                $selector.prev().find('li').each(function(i,e){
                    logo = $selector.find('option').eq(i).attr('data-pic');
                    folder = 'org';
                    $(e).prepend(`<img class="s-firm-option-logo" src="/lib/img/${folder}/${logo ? logo : 'no-picture.png'}">`);
                    $(e).addClass('flex-center');
                });
              
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

  $(document).on('keyup','input[name*="fullname"]',function(event){
    var $this = $(this);
    var $partElmt = $(this).closest('.participants-list--item');
    var $selector = $partElmt.find('[name*="participantSelector"]');
    if($selector.find('option:selected').length && $this.val() != $selector.find('option:selected').text()){
      $partInputZone = $this.parent();
      $partInputZone.find('input').not($this).each(function(i,e){
        $(e).removeAttr('value');
      })
      $partInputZone.find('.input-img').remove();
      $this.removeClass('part-feeded');

    }
    $selectorMElmts = $selector.closest('.select-wrapper');
    const p = [];
    $('.participants-list--item').not($partElmt).each(function(i,e){
      $(e).find('.participant-field-zone input:not(.select-dropdown)').each(function(i,f){
        if($(f).attr('value')){
          p.push({el: $(f).attr('class'), id: $(f).attr('value')});
        }
      })
    })

    if($this.val().length >= 3 /*&& event.keyCode != 8*/){
        //urlToPieces = surl.split('/');
        //urlToPieces[urlToPieces.length - 1] = $(this).val();
        //surl = urlToPieces.join('/');
        const params = {name: $this.val(), type: 'all', p: p};
        
        $.post(surl,params)
            .done(function(data){

                if(!data.qParts.length){
                    $this.removeAttr('value');
                    //$selectorMElmts.hide();
                    $selector.empty();
                    $selector.material_select();
                    return false;
                }
                
                $selector.closest('.select-wrapper').find('img').remove();
                $selector.empty();
                $.each(data.qParts,function(key,el){
                    let elId = el.e == 't' ? el.teaId : (el.e == 'f' ? el.wfiId : (el.e == 'eu' ? el.extUsrId : el.usrId));
                    let elName = el.e == 'f' ? el.orgName : (el.e == 't' ? el.teaName : el.username);
                    let elPic = el.e == 'f' || el.e == 'eu' && el.s ? el.logo : (el.e == 't' ? el.teaPicture : el.usrPicture);
                    //$option = $(`<option class="flex-center" value=${firm.id}></option>`);
                    //$option.append(`<img class="firm-option-logo" src="/lib/img/org/${firm.logo ? firm.logo : 'no-picture.png'}">`)
                    //$option.append(`<span>${firm.name}</span>`);
                    $selector.append(`<option value="${elId}" data-type="${el.e}" data-s="${el.s ? 1 : 0}" data-fname="${el.wfiId != currentWfiId ? el.orgName : ''}" data-wid="${el.wfiId}" data-oid="${el.orgId ? el.orgId : ""}" data-pic="${elPic ? elPic : ""}" data-uid="${el.usrId}">${elName}</option`);
                })
                //el.val(selector.find(":selected").text());
                //$selector.prepend(`<option value>(${noFirm})</option>`);
                //$this.attr("value",$selector.find(":selected").val());
                $selector.material_select();
                $selectorMElmts = $selector.closest('.select-wrapper')
                $selector.prev().find('li').each(function(i,e){
                    logo = $selector.find('option').eq(i).attr('data-pic');
                    elType = $selector.find('option').eq(i).attr('data-type');
                    synth = $selector.find('option').eq(i).attr('data-s');
                    orgName = $selector.find('option').eq(i).attr('data-fname');
                    folder = elType == 'f' || elType == 'eu' && synth == '1' ? 'org' : (elType == 'u' || elType == 'eu' ? 'user' : 'team');
                    //$selector.prev().find('li').index($(e)) == 0 ? ($(e).find('span').css('color','black'), $(e).prepend(`<img class="firm-option-logo" src="/lib/img/org/new-firm.png">`)) :
                    $(e).prepend(`<img class="firm-option-logo" src="/lib/img/${folder}/${logo ? logo : 'no-picture.png'}">`);
                    $(e).append(`<span class="el-type">${elType == 'eu' && synth == 0 ? orgName : (elType == 'eu' && synth == 1 ? 'Client' : (elType == 'f' ? 'New partner' : (elType == 'u' ? 'user' : 'team'))) }</span>`);
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


  $(document).on('click',function(e){
    var $this = $(e.target);
    var $visibleSel = $('.dropdown-content:visible');
    
    if($visibleSel.closest('.participant-field-zone').length || $this.hasClass('participant-fullname')){
      
      if (!$this.hasClass('participant-fullname') && $visibleSel.length){
        $visibleSel.attr('style','display:none!important');
      } else if($this.hasClass('participant-fullname')){
        $sel = $this.closest('.participants-list--item').find('.participant-field-zone .dropdown-content');
        if($sel.find('li').length){
          $sel.removeAttr('style');
        }
      }
    
    } else if($visibleSel.closest('.firm-field-zone').length || $this.hasClass('firm-name')) {

      if (!$this.hasClass('firm-name') && $visibleSel.length){
        $visibleSel.attr('style','display:none!important');
      } else if($this.hasClass('firm-name')){
        $sel = $('#createParticipant').find('.dropdown-content');
        if($sel.find('li').length){
          $sel.removeAttr('style');
        }
      }


    }
  })



  $(document).on('change','[name*="participantSelector"]',function(){
      var $this = $(this);
      var $partElmt = $this.closest('.participants-list--item');
      var $selectedOpt = $this.find(":selected");

      if($selectedOpt.attr('data-s') == 1 || $selectedOpt.attr('data-type') == 'f'){
        $('#legalPerson').modal('open');
      }
      //var index = $('.participants-list--item').index($this.closest('.participants-list--item'));
      if($this.val() != ""){
          $partElmt.find('.tooltipped').attr('data-tooltip',$selectedOpt.text()).tooltip();
          $this.prev().attr('style','display:none!important');

          if(!$selectedOpt.attr('data-oid')){
            // ** AJAX CREATE CLIENT IN DB
          }

          switch($selectedOpt.attr('data-type')){
            case 'f':
              $partElmt.find('input[name*="workerFirm"]').attr('value',$this.val());
              break;
            case 'eu':
              $partElmt.find('input[name*="externalUser"]').attr('value',$this.val());
              $partElmt.find('input[name*="user"]').attr('value',$selectedOpt.attr('data-uid'));
              break;
            case 't':
              $partElmt.find('input[name*="team"]').attr('value',$this.val());
              break;
            case 'u':
              $partElmt.find('input[name*="user"]').attr('value',$this.val());
              break;
          }

          $this.closest('.participant-field-zone').find('input.participant-fullname').val($selectedOpt.text());
          var img = $this.prev().find('li').eq($this.find('option').index($this.find('option:selected'))).find('img');
          if(!$partElmt.find('.input-img').length){
            var inputImg = img.clone();
            inputImg.attr('class','');
            inputImg.addClass('input-img');
            inputImg.css({
              'position': 'absolute',
              'left': '0',
              'top': '15%',
              'height': '30px',
            });
            $partElmt.find('.participant-fullname').addClass('part-feeded');
            //inputElmt.css({'padding-left':'3rem!important'});
            $partElmt.find('.participant-field-zone').append(inputImg);
          }
          $partElmt.find('.selected-participant-logo').attr('src', img.attr('src'));
          if($selectedOpt.attr('data-s') != 1 && $selectedOpt.attr('data-type') != 'f'){
            $partElmt.removeClass('edit');
            $partElmt.closest('.participants-list').css('margin-bottom','');
            $partElmt.find('.participant-fullname-zone').show();
            $partElmt.find('.participant-field-zone').hide();
          }
          
      } else {
          $this.parent().hide();
          $this.val("");
      }
  });

  $('[name*="firmSelector').on('change',function(){
      var $this = $(this);
      var $selectedOpt = $this.find(":selected");
      const $usrElmts = $this.closest('.user-inputs');
      $usrElmts.find('input[name="wid"]').val($selectedOpt.attr('data-wid'));
      $usrElmts.find('input[name="oid"]').val($selectedOpt.attr('data-oid'));
      $usrElmts.find('input[name="cid"]').val($selectedOpt.attr('data-cid'));
      $usrElmts.find('.firm-name').val($selectedOpt.text());
      var img = $this.prev().find('li').eq($this.find('option').index($this.find('option:selected'))).find('img');
      if(!$usrElmts.find('.input-f-img').length){
        var inputImg = img.clone();
        inputImg.attr('class','');
        inputImg.addClass('input-f-img');
        inputImg.css({
          'position': 'absolute',
          'left': '0.75rem',
          'top': '15%',
          'height': '30px',
        });
        $usrElmts.find('.firm-name').addClass('part-feeded');
        //inputElmt.css({'padding-left':'3rem!important'});
        $usrElmts.find('.firm-field-zone').append(inputImg);
      }
  });

  $('.setup-activity, .create-activity').on('click',function(e){
    e.preventDefault();
    const $this = $(this);
    $.each($('.red-text'),function(){
      $(this).remove();
    });

    const params = {btn: $this.hasClass('create-activity') ? 'submit' : 'complexify'};
    var data = $this.closest('form').serialize() + '&' + $.param(params)

    $.post(acurl,data)
      .done(function(data){
        $('#createActivity').modal('close');
        location.reload();
      })
      .fail(function(data){
        $.each(data.responseJSON, function(key, value){
          $.each($('#createActivity input'),function(){
            if($(this).attr('name').indexOf(key) != -1){
                $(this).after('<div class="red-text"><strong>'+value+'</strong></div>');
                return false;
                }
          });
        })
      })
  })

  $(document).on('click','.participant-delete',function(e){
    $(this).closest('.participants-list--item').remove();
  });

  $(document).on('click','.participant-btn',function(e){
    const $this = $(this);
    const $partHolder = $this.closest('.participants-list--item');
    $partHolder.addClass('edit');
    $partHolder.parent().css('margin-bottom','60px');
    $this.hide();
    $this.next().show();
    /*
    const $partHolder = $this.closest('.participants-list--item');
    $partHolder.find('input').each(function(i,e){
      if($(e).attr('value')){
        $('#deleteParticipant')
          .attr('data-e',$(e).attr('class'))
          .attr('data-eid',$(e).attr('value'))
      }
    })
    $(this).closest('.participants-list--item').remove();
    */

  });

  $(document).on('click','.participant-validate',function(){
    const $this = $(this);
    const $partElmt = $this.closest('.participants-list--item');

    if($partElmt.find('input.participant-fullname').hasClass('part-feeded')){
          $partElmt.removeClass('edit');
          $partElmt.find('.participant-fullname-zone').show();
          $partElmt.find('.participant-field-zone').hide();
    } else {
      var creationModal = $('#createParticipant');
      var partFN = $partElmt.find('input.participant-fullname').val();
      creationModal.find('.new-part-name').empty().append(partFN);
      var partNameToPieces = partFN.split(' ');
      //if(!$('input[name="firstname"]').val().length){
        $('input[name="firstname"]').val(partNameToPieces[0]).prev().addClass('active');
        $('input[name="lastname"]').val(partNameToPieces.slice(1).join(' ')).prev().addClass('active');
      //}
      $('#createParticipant').modal('open');
    }
    
  })

  $('#choice-indpt').on('change',function(){
    $('#choice-user, #choice-firm').prop('checked',!$(this).is(':checked'));
    if($(this).is(':checked')){
      $('.firm-field-zone, .f-prefix').hide();
      $('.user-fn, .user-ln').removeClass('m3').addClass('m6');
      $('.user-inputs').show();
    }
  })

  $('#choice-firm').on('change',function(){
    $('#choice-user, #choice-indpt').prop('checked',!$(this).is(':checked'));
    if($(this).is(':checked')){
      $('.user-inputs').hide();
    }
  })

  $('#choice-user').on('change',function(){

    $('#choice-firm, #choice-indpt').prop('checked',!$(this).is(':checked'));
    if($(this).is(':checked')){
      $('.user-fn, .user-ln').removeClass('m6').addClass('m3');
      $('.f-prefix, .firm-field-zone').show();
      var partNameToPieces = $(this).closest('.modal').find('.new-part-name').text().split(' ');
      $('.user-inputs').show();
      if(!$('input[name="firstname"]').val().length){
        $('input[name="firstname"]').val(partNameToPieces[0]).prev().addClass('active');
        $('input[name="lastname"]').val(partNameToPieces.slice(1).join(' ')).prev().addClass('active');
      }
    } else {
      $('.user-inputs').hide();
    }
  })

  $('.create-part-button').on('click',function(e){

    const $this = $(this);
    if($('#createParticipant').is(':visible') || $('#legalPerson').is(':visible') && $('.participant-field-zone:visible').find('input.f').attr('value')){
      
      // In case new client
      if($('.participant-field-zone:visible').find('input.f').attr('value')){
        $('#createParticipant').find('input[name="wid"]').val($('.participant-field-zone:visible').find('input.f').attr('value'));
        $('#createParticipant').find('input[name="oid"]').val($('.participant-field-zone:visible [name*="participantSelector"] option:selected').attr('data-oid'));
        nc = 1;
      } else {
        nc = 0;
      }

      var $emailInput = $('.user-inputs').find('input[name="email"]');

      if($emailInput.is(':visible') && !isEmail($emailInput.val()) && !$('#interactPart').is(':visible')){
        $('#interactPart').modal('open');
        return false;
      }
      if($('#choice-firm').is(':checked') && !$('#legalPerson').is(':visible')){
        $('#legalPerson').modal('open');
        return false;
      }

      let type = $('#choice-firm').is(':checked') || nc == 1 ? 'f' : ($('#choice-indpt').is(':checked') ? 'i' : 'u');
      let uname = $('.new-part-name').text();
      const params = {type: type, uname: uname};
      $.post(pcurl,$('#createParticipant form').serialize() + '&' + $.param(params))
        .done(function(data){
          directInsert(tn = data.tn, fn = data.fn, id = data.uid, pic = data.pic, secId = data.euid);
          $('#interactPart, #legalPerson, #createParticipant').modal('close');
        })
        .fail(function(data){
          console.log(data);
        })




    } else {
      $('.participant-validate:visible').click();
    }

  })

  if(getCookie('view_type') == 't'){
    $actHeight = !$('.stages-holder:visible').length ? 75 : $('.stages-holder:visible').eq(0).height();
    $mainHeaderElmtsHeight = $('.sorting-type').height() + $('.timescale').height() + $('.tabs-t-view').height();
    $mainHeight = Math.min(1000, $('main').height());
    $actList = $();
    totalPotentialAct = Math.floor(($mainHeight - $mainHeaderElmtsHeight) / $actHeight);
    nbVisibleAct = $('.stages-holder:visible').length;
    if (nbVisibleAct < totalPotentialAct){
        for(k = nbVisibleAct; k < totalPotentialAct; k++){
          actProto = $('.process-list-t').data('prototype');
          var sdDay = getRandomInt(7,320);
          var period = getRandomInt(Math.max(0,15 - sdDay), 350 - sdDay);
          var sdate = new Date(annee, 0, sdDay).getDate();
          var edate = new Date(annee, 0 , sdDay + period).getDate();

          $actProto = $(actProto);
          $actProto.find('.stage-element').attr('data-sd',sdDay);
          $actProto.find('.stage-element').attr('data-p',period);
          $actProto.find('.s-day').empty().append(sdate);
          $actProto.find('.e-day').empty().append(edate);

          $actList = $actList.add($actProto);
        }

        displayTemporalActivities($actList,false);
        const dummyParams = {wa:1, td: totalPotentialAct - nbVisibleAct + 1};
        $.post(dcurl,dummyParams)
          .done(function(data){
            $actList.each(function(i,e){
              $(e).find('.act-info-name').append(data.dummyElmts[i].actName);
              $(e).find('.activity-client-name').attr('data-tooltip',data.dummyElmts[i].name).tooltip();
              $(e).find('.client-logo').attr('src',data.dummyElmts[i].logo);
            })
          })
          .fail(function(data){
            console.log(data);
          })
        
        var $actHolder = $('<div class="virtual-activities-holder"></div>');

        $actList.each(function(i,e){
          $actHolder.append($(e));
        })

        $actHolder.append(noActOverlay);

        $appenedElmt = $('.activity-list:visible').length ? $('.activity-list:visible').last() : $('.dummy-activities-container');

        $appenedElmt.append($actHolder);

        
        
      }
  };
});




