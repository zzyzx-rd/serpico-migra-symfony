
if(performance.getEntriesByType("navigation")[0].type == "navigate" && typeof luurl != "undefined"){
  $.get(luurl)
    .done(function(data){
      if(data.hasToBeReloaded){
        location.reload();
      }
    })
}

function getRandomInt(min, max) {
  min = Math.ceil(min);
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min + 1)) + min;
}

/*function getDay(){
  var MyDate = new Date;
  var PremierJour = Date.UTC(MyDate.getFullYear(), 0, 0);
  var Aujourdhui  = Date.UTC(MyDate.getFullYear(), MyDate.getMonth(), MyDate.getDate());
  return Math.floor((Aujourdhui - PremierJour) / (1000 * 60 * 60 * 24));
}*/

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

$('#firstConnectionModal, #beforeStarting, #invitationStage').modal({
  dismissible: false,
})
$('#beforeStarting').modal({
  ready: function(){
    $('#beforeStarting').find('img').attr({
      'src' : $('.account-logo').eq(1).attr('src'),
      'data-tooltip': $('.account-logo').eq(1).parent().attr('data-tooltip')
    }).tooltip();
  }
})

$('.event').tooltip({
  complete: function(){
    $(this).closest('.stage-element').tooltip('close');
  }
})

$('#createStage').modal({
  complete: function(){
    $(this)[0].$el.find('.s-elmt-dates').show();
  }
})

$('#createParticipant, #legalPerson, #deleteParticipant, #addUserClient').modal();

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
var annee   = now.getFullYear();
var anneeSuiv = annee + 1;
const $sentDatesOptions = { month: 'numeric', day: 'numeric', year: 'numeric'};
var ts = getCookie("ts");
var ci = getCookie("ci");
var mr = $('#activities-container').hasClass('mr') ? 1 : 0;
const $partHolder = $('ul.participants-list');
const $stageModal = $('#createStage');

if(mr){
  $('.ts-scale > *').not('.chevron').css('min-height', Math.round(900 / $('.ts-scale').children().not('.chevron').length).toString() + 'px');
}

function dTrans($elmts, $entity, $prop){
  var tElmts = [];
    
  $elmts.each(function(i,e){
    var tElmt = {};
    tElmt.e = $entity;
    tElmt.id = e.value;
    tElmt.p = $prop;
    tElmts.push(tElmt);
  
  })
  
  const params = {elmts: tElmts};

  $.post(dturl, params)
    .done(function(data){
      var lElmts = data.lElmts;
      $elmts.each(function(i,e){
          $(e).empty().append(lElmts[i]);
      })
    })
}

$('.dp-start, .dp-end').on('change',function(){
  const $cal = $(this);
  const $selectedDate = $cal.pickadate('picker').get('select');
  if($selectedDate){
    const $modal = $(this).closest('.modal');
    if($cal.hasClass('dp-start')){
      $relatedEndCal = $modal.find('.dp-end');
      if($relatedEndCal.pickadate('picker').get('select') && $selectedDate.pick > $relatedEndCal.pickadate('picker').get('select').pick){
        $relatedEndCal.pickadate('picker').set('select',new Date($selectedDate.pick));
      }
      $relatedEndCal.pickadate('picker').set('min', new Date($selectedDate.pick))
    } else {
      $relatedStartCal = $modal.find('.dp-start');
      if(new Date($selectedDate.pick) - $cal.closest('.modal').find('.dp-start').pickadate('picker').get('select').pick > 24*60*60*1000){
        $relatedStartCal.pickadate('picker').set('max', new Date($selectedDate.pick))
      } else {
        $relatedStartCal.pickadate('picker').set('max',false);
      }
    }
  }
})

$.each($('#createStage, #updateEvent'),function(i,e){
  const $modal = $(e);
  var startCal = $modal.find('.dp-start');
  var endCal = $modal.find('.dp-end');
  var startDateTS = (startCal.val() == "") ? Date.now() : new Date(startCal.val());
  var endDateTS = (endCal.val() == "") ? startDateTS : new Date(endCal.val());
  var startDate = new Date(startDateTS);
  var endDate = new Date(endDateTS);
  y = parseInt(ci.split('-').slice(-1)[0]);
  cInt = parseInt(ci.split('-')[1]); 
         
    if (ts == "y"){
        $('.scale option[value=years]').attr('selected','selected');
  
        $('.value-scale')
            .append('<option value="'+annee+'">'+annee+'</option>')
            .append('<option value="'+anneeSuiv+'">'+anneeSuiv+'</option>');
        $('.value-scale option[value='+ci+']').attr('selected','selected');
  
    } else {
        $('.scale option[value=trimester]').attr('selected','selected');
        for (var u=0; u<2;u++) {
            var Y = annee + u;
            for (var i = 1; i < 5; i++) {
                $('.value-scale')
                    .append('<option value="q-' + i + '-' + Y + '">q-' + i + '-' + Y + '</option>')
  
            }
        }
        $('.value-scale option[value='+ci+']').attr('selected','selected');
    }
  startCal.pickadate('picker').set('select',startDate);
  if(!endCal.closest('.event').length){
    endCal.pickadate('picker').set('select',endDate).set('min',startDate);
  }

});


$('#eventGSelector').on('change',function(){
  updateEvents();
  if($(this).find('option:selected').data('evgid') == 1){
    $('.add-e-comment').attr('data-tooltip',$('.add-e-comment').data('upload-msg')).tooltip();
  } else {
    $('.add-e-comment').attr('data-tooltip',$('.add-e-comment').data('normal-msg')).tooltip();
  }
})



function updateEvents($evgnId = null, $evnId = null) {
  $evgSelect = $('#eventGSelector');
  $evtSelect = $('[id*="eventType"]');
  $evgId = null;
  if($evgnId){
    $consideredOption = $evgSelect.find(`option[data-evgid="${$evgnId}"]`);
    $consideredOption.prop('selected',true);
    $evgId = $consideredOption.val();
  } else {
    $evgId = $evgSelect.val() ? $evgSelect.val() : $evgSelect.find('option').eq(0).val();
  }

  const $stylizableSelects = $evgSelect.add($evtSelect);

  $.post(geurl,{id: $evgId})
    .done(function(eventTypes){ 
      $evtSelect.empty();
      $.each(eventTypes, function(i,e){
        $evtSelect.append(`<option data-evnid="${e.evnId}" value="${e.id}">${e.name}</option>`);
      })

      if($evnId){
        $consideredOption = $evtSelect.find(`option[data-evnid="${$evnId}"]`);
        $consideredOption.prop('selected',true);
      }

      $stylizableSelects.material_select();

      if($evgSelect.find('option:selected').data('evgid') == 1){
        $('.add-e-comment').attr('data-tooltip',$('.add-e-comment').data('upload-msg')).tooltip();
      }
 
      $('.select-evg').find('.select-dropdown').each(function(_i, e){
        const $this = $(this);
        if ($this.is('input')) {
            $this.closest('.input-field').find('.evg-input-circle').remove();
            $(this).css('text-indent', '1.5rem');
            $this.parent().prepend(`<div class="evg-circle evg-input-circle no-margin evg-b-${$('#eventGSelector option:selected').attr('data-evgid')}" style=""></div>`);
        } else {
          $this.find('li > span').each(function (i, f) {
            $(f).parent().addClass('flex-center').prepend($(`<div class="evg-circle sm-left evg-b-${$('#eventGSelector option').eq(i).attr('data-evgid')}"></div>`));
          });
        }
      })

      const regExp = /~(.+)~/;

      $('.select-with-fa .select-dropdown').each(function (_i, e) {
        const $this = $(e);
        const match = $this.val().match(regExp);
        let icon = String.fromCodePoint && match && match[1] ? String.fromCodePoint('0x' + match[1]) : '';

        if ($this.is('input')) {
          if (!match) return;
          $this.val($this.val().replace(regExp, icon));
        } else {
          $this.find('li > span').each(function (_i, e) {

            const $this = $(e);
            const match = $this.text().match(regExp);
            let icon = String.fromCodePoint && match && match[1] ? String.fromCodePoint('0x' + match[1]) : '';

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
      //dTrans($evtSelect.find('option'),'EventName','name');
    })
  

  
}

$(function () {

  $('a:has(.fa-plus)').on('click', function () {
    $('.process-name').empty().append($(this).closest('ul').find('header').text())
    $('.start-btn,.launch-btn,.modify-btn').removeData().data('pid', $(this).data('pid'));
  });

    var now = new Date();
    var annee = now.getFullYear();
    if (ts == "y"){
        annee = parseInt(ci) ;
    }
    anneeSuiv=(annee + 1);
  $('.curr-int-value').append(annee);
  if(ts == 'y'){
    $('.start-int-value').empty().append('1/1');
    $('.end-int-value').empty().append('31/12');
  }
  var centralElSize = mr ? 900 : $('.activity-content-stage:visible').eq(0).width();
  var now = new Date();
  var annee = now.getFullYear();
  var c = now;
  
  if(ts == 't' || ts == 'w'){
    ci = getCookie("ci");
    cy = parseInt(ci.split('-').slice(-1)[0]);
    nbInt = parseInt(ci.split('-')[1]);  
    var dateTri = datesInterval(ts, cy, nbInt);
    var si = dateTri[0];
    var ei = dateTri[1];
    var tDays = parseInt(dayDiff(si, ei));
    
    if(ts == 't'){
      
      wDate = moment(si);
      ct = wDate.quarter();
      var nbSubInt = 0;
      while (wDate.quarter() == ct){
        wDate = wDate.add(1,'w');
        nbSubInt++;
      }
    
    } else {
      var nbSubInt = 7;
    }

  } else {
    var tDays = ndDayPerYears(annee);
    var nbSubInt = 12;
  }

  // Necessary to setup dashboard
  displayTemporalActivities($('.activity-holder'), nbSubInt, true, true);

//var dateChevron = $('.chevron');


//var echelle = centralElSize / tDays;
  

/*
  if($('.no-processes').length){

    $('.dmin').append('<span class="starting-mark">' + annee + '</span>' + '<div class="line-no-processes"></div>');
    $('.dmax').append('<span class="ending-mark">' + (annee + 1) + '</span>' + '<div class="line-no-processes"></div>');
    
  } else {

    $('.dmin').append(annee + '<div class="line"></div>');
    $('.dmax').append(annee + 1 + '<div class="line"></div>');
  }
*/
  /**
   * Display activities in a proper way
   * @param {HTMLInputElement} $activities
   * @param bool setEvents
   */
  function displayTemporalActivities($activities, $nbSubInt, $nonEmptySet, $setEvents){

    var centralElSize = mr ? 900 : ($('.activity-content-stage:visible').length ? $('.activity-content-stage:visible').eq(0).width() : ($('.dummy-activities-container').length ? $('.dummy-activities-container').width() * 0.75 : $('.no-int-act-overlay').width() * 0.75));
    var echelle = centralElSize / tDays;
    var $actCurDate = $activities.find('.curDate');
    cOf = Math.max(0, moment.duration(moment().diff(moment(si),'days')).milliseconds());

    if(cOf > 0 && cOf < parseInt(dayDiff(ei, si))){
      
      $actCurDate.show();
      $actCurDate.each(function(i,e){
        $(e).css((mr ? 'top' : 'left'), Math.round(10000 * c / tDays) / 100 + '%');
      });
    
    } else {
      $actCurDate.hide();
    }
    
    $activities.find('.activity-content-stage').each(function(_i,e){
      $(e).css({'background' : `repeating-linear-gradient(${mr ? 0 : 90}deg, #f3ccff2b, #63009445 ${centralElSize / $nbSubInt}px, #ffffff ${centralElSize / $nbSubInt}px, #ffffff ${centralElSize / ($nbSubInt/2) }px)`})
    });

    if($nonEmptySet){
      
      $activities.find('.activity-component').each(function (){
        var $this = $(this);
        var sd = $this.data("sd");
        var p =  $this.data("p");
        var id = $this.data("id");
    
        $this.css(mr ? {'top': "0 %"} : {'margin-left': "0%" });
        $this.css(mr ? {'width' : "100%" } : {'height' : "100%" });
    
        $this.find('.stage-element').each(function(){
          var ssd = $(this).data("sd");
          var sp =  $(this).data("p");
          
          sPctWidthSD = (ssd - sd) / p;
          sPctWidthP = Math.max(3,(sp + 1)) / (p + 1);
    
          $(this).css({
            'background'    : ssd >= c ? '#5CD08F' : (ssd + sp > c ? 'linear-gradient('+ (mr ? 'to bottom' : 'to right') +', transparent, transparent ' + Math.round(10000 * (c - ssd) / sp) / 100 + '%, #7942d0 '+ Math.round(10000 * (c - ssd) / sp) / 100 +'%), repeating-linear-gradient(61deg, #7942d0, #7942d0 0.5rem, transparent 0.5px, transparent 1rem)' : 'gray'),
            'border-radius' : '0.3rem',  
          });
          if(mr){
            $(this).css({
              'top'     : Math.round(10000 * sPctWidthSD) / 100 + "%",
              'height'  : Math.round(10000 * sPctWidthP) / 100 + "%",
              'width'   : '7px',
            })
          } else {
            $(this).css({
              'margin-left' : Math.round(10000 * sPctWidthSD) / 100 + "%",
              'width'       : Math.round(10000 * sPctWidthP) / 100 + "%",
              'height'      : '7px',
            })
          }
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
    
          $(this).css({
            'border-radius' : '0.3rem',  
          });
          
          if(mr){
            $(this).css({
              'top'   : Math.round(10000 * sPctWidthSD) / 100 + "%",
              'height' : Math.max(1,Math.round(10000 * sPctWidthP) / 100) + "%",
              'width' : '15px',
            });
          } else {
            $(this).css({
              'margin-left'   : Math.round(10000 * sPctWidthSD) / 100 + "%",
              'width' : Math.max(1,Math.round(10000 * sPctWidthP) / 100) + "%",
              'height' : '15px',
            });
          }
        });
      } 
    }
  }

  //$('.chevron').css({'left': 'calc('+Math.round(10000 * (c / tDays )) / 100 + '% - 10px)' });

  $(window).on('resize',function(){
    //setTimeout(function(){
      var centralElSize = $('.activity-content-stage:visible').eq(0).width();  
      var dateChevron = $('.chevron');
      var actCurDate = $('.curDate');
      var nbIntSubElmts = $('.ts-scale').children().not('.chevron').length;
      dateChevron.css({'left': 'calc('+Math.round(10000 * (c - si) / (ei - si)) / 100 + '% - 10px)' });
      actCurDate.each(function(i,e){
        $(e).css({'left': Math.round(10000 * (c - si) / (ei - si)) / 100 + '%' });
      });
      $('.activity-content-stage').css({
        'background' : 'repeating-linear-gradient(90deg, #f3ccff2b, #63009445 '+ centralElSize / nbIntSubElmts +'px, #ffffff '+ centralElSize / nbIntSubElmts +'px, #ffffff '+ centralElSize / (nbIntSubElmts/2) +'px)'
      });
    //}, 200);
  });

  if(!$('.activity-content-stage').length){
    feedDashboardScreen();
  }


  dateUpdate();

function dateUpdate(updateTimeScale = true, actSet = null) {
    
    var ts = getCookie("ts");
    var ci = getCookie("ci");
    y = parseInt(ci.split('-').slice(-1)[0]);
    cInt = parseInt(ci.split('-')[1]);
    var now = new Date(); 
    var c = new Date();
    if (ts == "y") {
      si = new Date(ci, 0, 1);
      ei = new Date((parseInt(ci) + 1), 0, 1);
    } else {
      var datesInt = datesInterval(ts, y, cInt);
      var si = datesInt[0];
      var ei = datesInt[1];
    }

    if(updateTimeScale){

      $('.s-day').css('padding-left','');
      $('.e-day').css('padding-right','');
      var month = ['J', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D'];
      $('#activities-container').find('.ts-scale').children().not('.chevron').remove();
      $timescale = $('#activities-container').find('.ts-scale');

      if (ts == "y") {
          for (var i = 0; i < 12; i++) {
              $('#activities-container').find('.ts-scale').append('<div class="col s1">' + month[i] + '</div>');
          }
          $('.curr-int-value').empty().append(parseInt(ci));
          $('.prev-interval-val').empty().append((parseInt(ci) - 1));
          $('.next-interval-val').empty().append((parseInt(ci) + 1));  
      } else {
          $('.ts-scale').removeClass('row').addClass('flex-center-sa'); 
          var cDivider = ts == 't' ? 5 : (ts == 'w' && moment(`${y+1}-01-01`).day() > 3 ? 54 : 53);
          var pDivider = ts == 't' ? 5 : (ts == 'w' && moment(`${y}-01-01`).day() > 3 ? 54 : 53);
          pInt = (pDivider + cInt - 1) % pDivider;
          if(pInt == 0){
            pInt = pDivider - 1;
            py = y - 1;
          } else {
            py = '';
          }
  
          nInt = Math.max(1,(cDivider + cInt + 1) % cDivider);
          ny = nInt == 1 ? y + 1 : '';
  
          prefix = (ts == 't') ? (lg == 'fr' ? 'T' : 'Q') : (lg == 'fr' ? 'S' : 'W');
  
          var tDays = parseInt(dayDiff(si, ei)) + 1;
          //var c = moment.duration(moment().diff(moment(si),'days')).milliseconds();
          /*if(ts == 't'){*/
            $('.prev-interval-val').empty().append(`${prefix}${pInt} ${py}`);
            $('.next-interval-val').empty().append(`${prefix}${nInt} ${ny}`);
            $('.curr-int-value').empty().append(`${prefix}${cInt} <span class="int-precision">${y}</span>`);
          /*} else {
            $('.prev-interval-val').empty().append(`${lg == 'fr' ? 'S' : 'W'}${pInt == 0 ? 52 : pInt} ${pInt == 0 ? py : ''}`);
            $('.next-interval-val').empty().append(`${lg == 'fr' ? 'S' : 'W'}${nInt == 0 ? 52 : nInt} ${nInt == 1 ? ny : ''}`);
            $('.curr-int-value').empty().append(`${lg == 'fr' ? 'S' : 'W'}${cInt} <span class="int-precision">${cy}</span>`);
          }*/
          $('.start-int-value').empty().append(`${si.getDate()}/${si.getMonth() + 1}`);
          $('.end-int-value').empty().append(`${ei.getDate()}/${ei.getMonth() + 1}`);
          //width = 100 / 13;
          //week = moment(si).week();
          wDate = moment(si);
          
          currDiffWDaysUSEU = moment.duration(moment(datesInterval(ts,y,1)[0]).diff(moment(`${y}-01-01`))).days();
          nextDiffWDaysUSEU = moment.duration(moment(datesInterval(ts,y+1,1)[0]).diff(moment(`${y+1}-01-01`))).days();
          
          if(ts == 't'){
            nct = new Date(wDate);
            nct = moment(nct).add(Math.max(-currDiffWDaysUSEU,0),'d');
            ct = nct.quarter(); // Just to be sure to be in according quarter 
            offset = /* currDiffWDaysUSEU < 3 &&*/ moment(moment(datesInterval(ts,y,1)[0])).week() == 2 ? -1 : 0;
  
            while (nct.quarter() == ct){
              week = wDate.week();  
              
              if(week == 1){
                if (nextDiffWDaysUSEU >= 3 && ct == 4){
                  week = 53;
                }
              }
              $timescale.append('<div><sub>s</sub>' + (week + offset == 0 ? 52 : week + offset) + '</div>');
              wDate = wDate.add(1,'w')/*.add(Math.max(-currDiffWDaysUSEU,0),'d')*/;
              nct = nct.add(1,'w')/*.add(Math.max(-currDiffWDaysUSEU,0),'d')*/;
            }
          } else if(ts == 'w'){
            weekdays_short = [];
            weekdays_short['en'] = ['M','T','W','T','F','S','S'];
            weekdays_short['fr'] = ['L','M','M','J','V','S','D'];
            for(i=0;i<7;i++){
              $timescale.append(`<div><sub>${weekdays_short[lg][i]}</sub>${i != 0 ? wDate.add(1,'d').date() : wDate.date()}</div>`)
            }
  
          }

          
        }
      
      if(mr){
        $timescale.find('> *').not('.chevron').addClass('flex-center').css('min-height', Math.round(900 / $timescale.children().not('.chevron').length).toString() + 'px');
      } else {
        $timescale.find('> *').css('text-align','center');
      }
        
      div = $('.ts-scale').children().length - 1;
  
      $('.activity-content-stage').css({
          'background': 'repeating-linear-gradient('+ (mr ? 0 : 90).toString() +'deg, #f3ccff2b, #63009445 ' + (((centralElSize) / div)) + 'px, #ffffff ' + (((centralElSize) / div)) + 'px, #ffffff ' + ((centralElSize) / (div / 2)) + 'px)'
      });
  
      var dateChevron = $('.chevron');
      var actCurDate = $('.curDate');
  
      var echelle = centralElSize / (ei - si);
      if (ei > now && now > si) {
          dateChevron.show();
          dateChevron.css(mr ? {'top': 'calc(' + Math.round(10000 * (c - si) / (ei - si)) / 100 + '% - 2.5rem)'} :  {'left': 'calc(' + Math.round(10000 * (c - si) / (ei - si)) / 100 + '% - 10px)'});
          actCurDate.each(function (i, e) {
              $(e).css(mr ? {'top': Math.round(10000 * (c - si) / (ei - si)) / 100 + '%'} : {'left': Math.round(10000 * (c - si) / (ei - si)) / 100 + '%'});
              $(e).show();
          });
      } else {
          dateChevron.hide();
          actCurDate.each(function (i, e) {
              $(e).hide();
          });
      }
    
    }

    //if(!$('.dummy-activities-container').length){


        if(actSet == null){
          $actElmts = $(document).find('.activity-component');
        } else {
          $actHolders = $('');
          $.each(actSet, function(i,e){
            $actHolders = $actHolders.add($(document).find(`.activity-holder[data-id="${e}"]`));
          })
          $actElmts = $actHolders.find('.activity-component');
        }
    
        $.each($actElmts, function () {
            var $this = $(this);
            $this.show();
    
            if($this.closest('.activity-holder').hasClass('tbd')){
              nbSubInt = $('.ts-scale').children().not('.chevron').length;
              var $actContentStage = $(this).closest('.activity-content-stage');
              $actContentStage.css({
                'background' : 'repeating-linear-gradient('+ mr ? 0 : 90 +'deg, #f3ccff2b, #63009445 '+ centralElSize / nbSubInt +'px, #ffffff '+ centralElSize / nbSubInt +'px, #ffffff '+ centralElSize / (nbSubInt/2) +'px)'
              });
              actCurDate = $actContentStage.find('.curDate');
              (ei > now && now > si) ? actCurDate.css(mr ? {'top': Math.round(10000 * (c - si) / (ei - si)) / 100 + '%'} : {'left': Math.round(10000 * (c - si) / (ei - si)) / 100 + '%'}).show() : actCurDate.hide(); 
            }
    
            if(!mr){$(this).closest('.activity-holder').show()};
            var sdU = parseInt($this.data("sd"));
            var p = parseInt($this.data("p"));
            var id = $this.data("id");
            var sa = new Date(sdU * 1000);
            var ea = new Date((sdU + p) * 1000);
            csd = Math.max(si,sa);
            ced = Math.min(ei,ea);
            actOff = (Math.min(ei,csd) - si) / (ei - si);
            actW = (ced - csd) / (ei - si);
            $this.css(mr ? {'top': Math.round(10000 * actOff) / 100 + "%"} : {'margin-left': Math.round(10000 * actOff) / 100 + "%"});
          
            $this.css(mr ? {'height': Math.round(10000 * actW) / 100  + "%"} : {'width': Math.round(10000 * actW) / 100  + "%"});
            $this.parent().css({'overflow': "hidden"});
    
            $this.find('.stage-element').each(function () {
    
                var sdVal = $(this).data("sd");
                var spVal = $(this).data("p");
                ssd = new Date(sdVal * 1000);
                sed = new Date((sdVal + spVal)  * 1000);
    
                if(ssd < ei){
                    $(this).find('.s-day').text(getCookie('ts') == 'y' ? ssd.getDate() : ssd.getDate() + '/' + (ssd.getMonth() + 1));
                    $(this).find('.e-day').text(getCookie('ts') == 'y' ? sed.getDate() : sed.getDate() + '/' + (sed.getMonth() + 1));
    
                } else {
                    $(this).find('.s-day').text(getCookie('ts') == 'y' ? ssd.getDate() : ssd.getDate() + '/' + (ssd.getMonth() + 1));
                    $(this).find('.e-day').text(sed.getDate() + '/' + (sed.getMonth() + 1));
    
                }
                if (ssd > ei && !$(this).closest('.activity-content-stage').hasClass('dummy')) {
                    $(this).css({
                        'display': "none",
                    });
                } else {
                    $(this).css({
                        'display': "block",
                    });
                }
                sOff = (Math.min(ea,Math.max(sa,ssd)) - sa) / (ea - sa);
                sW = (Math.min(sed,ea) - Math.max(sa,ssd)) / (ea - sa);
    
                ssd > ei || sed < si ? $(this).css('display',"none") : $(this).css('display',"block");
    
                $(this).css({
                  'background'    : ssd >= c ? '#5CD08F' : (sed > c ? 'linear-gradient('+ (mr ? 'to bottom' : 'to right') +', transparent, transparent ' + Math.max(1, Math.round(10000 * (c - csd) / (ced - csd)) / 100) + '%, #7942d0 ' + Math.round(10000 * (c - csd) / (ced - csd)) / 100 + '%), repeating-linear-gradient(61deg, #7942d0, #7942d0 0.5rem, transparent 0.5px, transparent 1rem)' : 'gray'),
                  'border-radius' : '0.3rem',
                });
                
                if(mr){
                  $(this).css({
                    'top'     : Math.round(10000 * sOff) / 100 + "%",
                    'height'  : Math.round(10000 * sW) / 100 + "%",
                    'width'   : '7px',
                  })
                } else {
                  $(this).css({
                    'margin-left'   : Math.round(10000 * sOff) / 100 + "%",
                    'width'   : Math.round(10000 * sW) / 100 + "%",
                    'height'  : '7px',
                  })
                }
    
            });
    
            $this.find('.event').each(function () {
                var odU = $(this).data("od");
                var ep = $(this).data("p") ? $(this).data("p") : 0;
    
                od = new Date(odU * 1000);
                erd = new Date((odU + ep)  * 1000);
                eOff = (Math.min(ced,Math.max(csd,od)) - csd) / (ced - csd);
                eW = (Math.min(erd,ced) - Math.max(csd,od)) / (ced - csd);
    
                od > ei || erd < si ? $(this).css('visibility',"hidden") : $(this).css('visibility','');
    
                $(this).css({
                  'border-radius': '0.3rem',
                });
                
                if(mr){
                  $(this).css({
                    'top': Math.round(10000 * eOff) / 100 + "%",
                    'height'  : Math.round(10000 * eW) / 100 + "%",
                    'width'   : '15px',
                  })
                } else {
                  $(this).css({
                    'margin-left': Math.round(10000 * eOff) / 100 + "%",
                    'width'   : Math.round(10000 * eW) / 100 + "%",
                    'height'  : '15px',
                  })
                }
            });
    
            if($this.find('.stage-element').length && !$this.find('.stage-element:visible').length && !$(this).closest('.dummy-activities-container').length){
              $this.closest('.activity-holder').hide();  
            } else {
              $this.closest('.activity-holder').show();
            }
    
            $this.closest('.activity-holder').removeClass('tbd');
        })
    
        if(!$('.stage-element:visible').length){
          if(!$('.no-int-act-overlay').length){
            proto = $('.process-list').data('prototype-no-int-act');
            $('.process-list').append($(proto));
          }
        } else { 
           $('.no-int-act-overlay').remove();
        }
    
          
        //});
        
        feedDashboardScreen();
        if($('.activity-list>.activity-holder:visible').length && $('.virtual-activities-holder>.activity-holder:visible').length){
          $('.virtual-activities-holder').remove();
        } 
    //}

};

    function getNbJoursMois(mois, annee) {
        var lgMois = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        if ((annee%4 == 0 && annee%100 != 0) || annee%400 == 0) lgMois[1] += 1;
        return lgMois[mois]; // 0 < mois <11
    }

    function datesInterval(period,year,intNb){
        //switch(period){
            //case 't' :
              var dates = [];

              //Retrieving start and end interval dates
              for(var i=0;i<2;i++){

                  thisDate = period == 't' ? moment(year, "YYYY").quarter(intNb+i).toDate() : moment(year, "YYYY").week(intNb+i).toDate();
                  // If different to monday
                  
                  if(thisDate.getDay() != 1) {

                      d = thisDate.getDay() < 4 ? 1 - thisDate.getDay() : 8 - thisDate.getDay(); 
                      thisDate.setDate(thisDate.getDate()+d);

                  } else if (i == 1) {
                      if(thisDate.getDay() == 1) {
                          m = (thisDate.getMonth() == 12) ? 1 : thisDate.getMonth() + 1;
                          thisDate.setMonth(m);
                          thisDate.setDate(getNbJoursMois(12 , year-1));
                          thisDate.setFullYear(year-1);
                      }
                  }

                  dates.push(thisDate);
              }

        //}
        
        return dates;
    }

    function posScale(month,time,sa,ea,si,ei) {
            var pos= " ";
            if (sa < si) {

                if (ea < si) {

                    pos = " ";
                }
                else if(ea > ei){

                    pos = "int";
                }
                else {
                    pos = " ext ";

                    var dayext = dayDiff(si,ea);
                    pos += " end "
                }

            } else if (sa < ei) {
                if (ea < ei) {
                    pos = "in";

                }else{
                    var dayext = dayDiff(sa,ei);
                    pos = "ext";
                }
            } else{
                pos = " ";

            }

        return pos;
    };
  $('.stage-item-button').on('mouseenter',function(){
      var $this = $(this);
      $this.parent().css('z-index',999);
  }).on('mouseleave',function(){
      var $this = $(this);
      $this.parent().css('z-index',1);
  });
  
  /*
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
  */

    function dayDiff(d1, d2)
    {
        d1 = d1.getTime() / 86400000;
        d2 = d2.getTime() / 86400000;
        return new Number(d2 - d1).toFixed(0);
    }

    if(oid != 0){
      updateEvents();
    }

    $('.prev-int-btn, .next-int-btn').on('click', function (e) {
        
      if($(this).hasClass('prev-int-btn')){
            if (ts == "y") {
                ci--;
            } else {
               if(ts == 't' || ts == 'w'){
                 prevValue = $('.prev-interval-val').text().trim();
                 if(prevValue.split(' ').length > 1){
                   yVal = y - 1;
                   prevValue = prevValue.split(' ')[0];
                 } else {
                   yVal = y;
                 }
                 tsVal = ts == 't' ? 'q' : ts;
                 intVal = prevValue.slice(1);
                 ci = `${tsVal}-${intVal}-${yVal}`;
               }
            }
      } else {
            if (ts == "y") {
                ci++;
            } else {
              if(ts == 't' || ts == 'w'){
                nextValue = $('.next-interval-val').text().trim();
                if(nextValue.split(' ').length > 1){
                  yVal = y + 1;
                  nextValue = nextValue.split(' ')[0];
                } else {
                  yVal = y;
                }
                tsVal = ts == 't' ? 'q' : ts;
                intVal = nextValue.slice(1);
                ci = `${ts}-${intVal}-${yVal}`;
              }
            }
        }
        setCookie('ci', ci, 365);
        dateUpdate();
    });

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

  /*
  $('.scale').on('change', function () {
      var time = $(this).children("option:selected").val();
      if(time !== undefined) {
          eraseCookie('time');
          setCookie('time',time,365);
          $.each( $('.select-value-scale ').find('.dropdown-content li'), function () {

              $(this).remove();

          });
          $.each( $('.value-scale ').find('option'), function () {

              $(this).remove();

          });

          if ( time == "years"){



              $('.value-scale select')
                  .append('<option value="'+annee+'">'+annee+'</option>')
                  .append('<option value="'+anneeSuiv+'">'+anneeSuiv+'</option>');
              $('.select-value-scale ').find('.dropdown-content')
                  .append('<li class=""><span>'+annee+'</li>')
                  .append('<li class=""> <span>'+anneeSuiv+'</span></li>');
              $('.value-scale ').find('input.select-dropdown ').attr('value',annee);
              eraseCookie('ci');
              setCookie('ci',annee,365);
              $('.dmin span').remove();
              $('.dmax span').remove();
              $('.dmin').prepend('<span>'+parseInt(annee) +'</span>');
              $('.dmax').prepend('<span>'+(parseInt(annee) + 1) +'</span>');
              $('.value-scale option[value="'+annee+'"]').attr('selected','selected');
          } else {

              for (var i= 1; i<5;i++){
                  $('.value-scale select')
                      .append('<option value="q-'+i+'-'+annee+'">q-'+i+'-'+annee+'</option>')
                      .append('<option value="q-'+i+'-'+anneeSuiv+'">q-'+i+'-'+anneeSuiv+'</option>');
                  $('.select-value-scale ').find('.dropdown-content')
                      .append('<li class=""><span>q-'+i+'-'+annee+'</li>')
                      .append('<li class=""> <span>q-'+i+'-'+anneeSuiv+'</span></li>');
              }
              $('.value-scale ').find('input.select-dropdown ').attr('value','q-'+1+'-'+annee);
              eraseCookie('ci');
              setCookie('ci','q-'+1+'-'+annee ,365);

              $('.value-scale option[value="q-'+1+'-'+annee+'"]').attr('selected','selected');
          }
          $(this).material_select();
          dateUpdate();
      }

  })
  */


  $('.value-scale').on('change', function () {
      var ci = $(this).children("option:selected").val();
      intervalChange(ci);
          $(this).material_select();
          dateUpdate();


  })

  function eraseCookie(name) {
      createCookie(name,"",-1);
  }
  function createCookie(name,value,days) {
      if (days) {
          var date = new Date();
          date.setTime(date.getTime()+(days*24*60*60*1000));
          var expires = "; expires="+date.toGMTString();
      }
      else var expires = "";
      document.cookie = name+"="+value+expires+"; path=/";
  }
  function readCookie(name) {
      var nameEQ = name + "=";
      var ca = document.cookie.split(';');
      for(var i=0;i < ca.length;i++) {
          var c = ca[i];
          while (c.charAt(0)==' ') c = c.substring(1,c.length);
          if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
      }
      return null;
  }
  /*$('[href="#createStage"]').on('click', function () {
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

  $('.change-view-type > *').on('click',function(){
    cookie = getCookie('view_type');
    eraseCookie('view_type');
    cookie == 'd' ? setCookie('view_type','t',365) : setCookie('view_type','d',365);
    location.reload();
  });

  /*
  $('.change-status-type').on('click',function(){
    document.cookie = `sorting_type=${$(this).data('type') == "o" ? "p" : "o"}`;
    window.location = window.location;
  })
  */
  /*
  $('.change-timescale-type > *').on('click',function(){
    ts = getCookie('ts');
    ci = getCookie('ci');
    new_ci = ts == 'y' ? `q-${moment().quarter()}-${moment().year()}` : ci.split('-').slice(-1)[0];
    eraseCookie('ts');
    eraseCookie('ci');
    ts == 'y' ? setCookie('ts','t',365) : setCookie('ts','y',365);
    setCookie('ci',new_ci,365);
    location.reload();
  });
  */

  /*
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
  */

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
    if(!$(e).find('> .row:visible').length && $('.process-list--item:visible').length > 1){
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

  $('[href="#createStage"]').on('click',function(){
    const $modal = $('#createStage');
    if($modal.data('id')){
      $modal.removeAttr('data-id');
      $modal.find('.participants-list--item').each(function(i,e){
        $(e).remove();
      })
      $modal.find('.btn-participant-add').show();
      $modal.find('.events-list').empty();
      $modal.find('.nb-events').empty();
      $modal.find('.events').hide();
      $modal.find('.s-name').empty().append($modal.find('.s-name').data('create'));
      $modal.find('.s-elmt-dates').empty();
      $modal.find('.s-name-input input').val("");
      $modal.find('.s-name-input').show();
      $modal.find('.s-dates-row .dp-start').pickadate('picker').set('select',new Date());
      //$modal.find('.s-dates-row .dp-end').pickadate('picker').set('select',new Date()).set('min',new Date());
      $modal.find('.s-dates-row .dp-end').pickadate('picker').clear();
      $modal.find('.nb-participants').empty().append('(1)');
      $modal.find('.s-dates-row').show();
      $modal.find('.s-link').attr('data-value',generateToken());
    } else {
      $modal.find('.s-dates-row .dp-end').pickadate('picker').clear();
      if(!$('.participants-btn').length){
        proto = $partHolder.data('prototype');
        proto = proto.replace(/__name__/g, $partHolder.children().length - 1);
        $partElmt = $(proto);
        $partElmt.find('.selected-participant-logo').attr('src', userPic);
        $partElmt.attr('data-tooltip',myself).tooltip();
        $partElmt.find('.u').val(uid);
        $partHolder.prepend($partElmt);
      }
    }
    if(!$('.setup-activity').find('.fa-cog').length){
      $('.setup-activity').prepend('<i class="fa fa-cog sm-right"></i>')/*.append('<i class="fa fa-question-circle sm-left"></i>')*/;
    }
    $('#addParticipant').removeAttr('id');
  });

  $(document).on('mouseover','.activity-holder',function(){
      $(this).find('.act-info .fixed-action-btn').css('visibility','');
  }).on('mouseout','.activity-holder',function(e){
      var $relatedTarget = $(e.relatedTarget);
      if($relatedTarget != $(this)){
        $(this).find('.act-info .fixed-action-btn').css('visibility','hidden');
      }
  });

  $(document).on('mouseenter','.participant-btn',function(){
    $(this).find('.p-delete-overlay').show();
  }).on('mouseleave','.participant-btn',function(){
    $(this).find('.p-delete-overlay').hide();
  }),

  $(document).on('click','.stage-element, .m-act-update',function(){
    if($(this).hasClass('s-multiple-events')){
      $(this).removeClass('s-selectable');
    }
    var $stage = $(this);
    var id = $stage.attr('data-id');
    $.post(sdurl,{id: id})
      .done(function(data){
        console.log(data);
        const options = { month: 'numeric', day: 'numeric' };
        $modal = $('#createStage');
        $modal.attr({'data-id':id, 'data-sid':data.aid});
        if(data.ms){$modal.addClass('a-multiple-stages');}
        $modal.find('.btn-s-update').hide();
        $modal.find('.btn-s-modify').show();
        $modal.find('.s-name').empty().append(data.name);
        $modal.find('input[name*="name"]').closest('.input-field').hide();
        $modal.find('.s-elmt-dates').empty().append(`<i class="fa fa-calendar"></i><span class="sm-left s-elmt-date">${new Date(data.sdate.date).toLocaleDateString(lg+'-'+lg.toUpperCase(),options)}</span><span class="sm-right sm-left">-</span><span class="e-elmt-date">${new Date(data.edate.date).toLocaleDateString(lg+'-'+lg.toUpperCase(),options)}</span><div class="s-modify-dates m-left"><i class="btn-s-update btn-s-dates fa fa-pen dd-orange-text" style="display:none"></i></div>`);
        startDate = new Date(data.sdate.date);
        endDate = new Date(data.edate.date);
        $modal.find('.dp-start').pickadate('picker').set('select',startDate);
        if(endDate - startDate > 24*60*60*1000){
          $modal.find('.dp-start').pickadate('picker').set('max',new Date(endDate));
        }
        $modal.find('.dp-end').pickadate('picker').set('select',new Date(endDate)).set('min',new Date(startDate));
        $modal.find('.dp-start').closest('.row').hide();
        if(!$modal.find('.s-dates-row .dates-validate').length){$modal.find('.s-dates-row').append('<div class="btn dates-validate s-dates-validate"><i class="material-icons">check</i></div>');}
        $modal.find('.s-dates-row').addClass('flex-center-sb').find('.col').removeClass('s6 m6');
        $modal.find('.events').show();
        $partHolder.find('.participant-btn').remove();
        $partHolder.find('.btn-participant-add').attr('id','addParticipant');
        $modal.find('.nb-participants').empty().append(`(${data.participants ? data.participants.length : 0})`);
        $(data.participants).each(function(i,p){
          $partElmt = $($partHolder.data('prototype'));
          /*
          $partElmt.find('.participant-field-zone').remove();
          $partElmt.find('');
          */
          $partElmt.find('.selected-participant-logo').attr('src', p.picture);
          $partElmt
            .attr({
              'data-tooltip' : p.fullname + (p.synth ? ' (' + synthSuffix + ')' : ''),
            })
            .addClass('existing deletable')
            .tooltip()
            .append(`<div class="p-delete-overlay modal-trigger flex-center" href="#deleteParticipant" data-pid="${p.id}" style="display:none;"><i class="fa fa-trash"></i></div>`)
            .show();
          $partHolder.prepend($partElmt);
          
        })
        $evtHolder = $modal.find('ul.events-list');
        $evtHolder.empty();
        $modal.find('.nb-events').empty().append(`(${data.events ? data.events.length : 0})`);
        $(data.events).each(function(i,e){
          $evtElmt = $($evtHolder.data('prototype'));
          $evtElmt.find('.e-odate').empty().append(e.odate);
          $evtElmt.find('.evg-circle').addClass(`evg-b-${e.evgId}`);
          if(e.nbcoms > 0){
            $evtElmt.find('.e-comments').show().find('.nb-coms').append(e.nbcoms);
          }
          if(e.nbdocs > 0){
            $evtElmt.find('.e-documents').show().find('.nb-docs').append(e.nbdocs);
          }
          $evtElmt.find('.evg-name').append(e.evg);
          $evtElmt.find('.evt-name').append(e.evt);
          $evtElmt.find('.fa-external-link-alt').attr('data-id',e.id);
          $evtElmt.find('.tooltipped').tooltip();
          $evtHolder.append($evtElmt);
        })
        $modal.modal('open');
        
      })
      .fail(function(data){
        console.log(data);
      })

  });

  $(document).on('click','.btn-s-dates', function(){
    $('.s-elmt-dates').hide();
    $('.s-dates-row').show();
  })

  $(document).on('click','.s-dates-validate',function(){
    const $this = $(this);
    const $modal = $this.closest('.modal');
    const $outputOptions = { month: 'numeric', day: 'numeric'};
    const loc = `${lg}-${lg.toUpperCase()}`;
    const sdStr = $modal.find('.dp-start').val().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    const edStr = $modal.find('.dp-end').val().normalize("NFD").replace(/[\u0300-\u036f]/g, "")
    const sd = new Date(sdStr).toLocaleString('en-EN',$sentDatesOptions);
    const ed = new Date(edStr).toLocaleString('en-EN',$sentDatesOptions);
    const $params = {id: $modal.attr('data-id'), sd: sd, ed: ed}  
    $.post(usdurl,$params)
      .done(function(data){
        $('.s-dates-row').hide();
        $('.s-elmt-date').empty().append(new Date(sdStr).toLocaleString(loc,$outputOptions));
        $('.e-elmt-date').empty().append(new Date(edStr).toLocaleString(loc,$outputOptions));
        $('.s-elmt-dates').show();
      })
  });



  $(document).on('click','.e-dates-validate',function(){
    const $this = $(this);
    const $modal = $this.closest('.modal');
    const $outputOptions = { month: 'numeric', day: 'numeric'};
    const loc = `${lg}-${lg.toUpperCase()}`;
    const sdStr = $modal.find('.dp-start').val().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    const edStr = $modal.find('.dp-end').val().normalize("NFD").replace(/[\u0300-\u036f]/g, "")
    const sd = new Date(sdStr).toLocaleString('en-EN',$sentDatesOptions);
    const ed = new Date(edStr).toLocaleString('en-EN',$sentDatesOptions);
    const sid = $modal.find('.fa-external-link-alt').data('id');
    const id = $('#updateEvent').data('id');
    const evtid = $('[name*="eventType"]').val();
    const $params = {id: $modal.attr('data-id'), sd: sd, ed: ed, sid: sid, id: id, evtid: evtid}  
    $.post(uedurl,$params)
      .done(function(data){
        location.reload();
        $('.s-dates-row').hide();
        $('.e-odate').empty().append(new Date(sdStr).toLocaleString(loc,$outputOptions));
        if(edStr != ""){
          $('.e-rdate').empty().append(new Date(edStr).toLocaleString(loc,$outputOptions));
        }
        $('.event-dates-content').hide();
        $('.e-dates-header').show();
      })
  });

  $(document).on('click','.fa-external-link-alt',function(){
    var $this = $(this);
    if($this.closest('.modal').is('#createStage')){
      $(`.event[data-id="${$this.data('id')}"]`).addClass('e-selectable').click();
      $('#multipleEvent').modal('close');
      $this.closest('.modal').modal('close');
    } else if($this.closest('.modal').is('#updateEvent')){
      $(`.stage-element[data-id="${$this.data('id')}"]`).click();
      $('#updateEvent').modal('close');
    }
  });

  $(document).on('click','.e-multiple-events',function(){

      $('#eventSelector').empty();
      const $this = $(this);
      const $evtHolder = $(this).parent();
      ids = [];
      $visibleEventTT = $($evtHolder.find('.event:visible').attr('data-tooltip'));
      $visibleEventTT.find('.t-event-info').each(function(i,e){
        evt = {};
        evt.name = $(e).find('.evg').text() + ' - ' + $(e).find('.evt').text();
        evt.id = $evtHolder.find('.event').eq(i).attr('data-id');
        ids.push(evt);
      })
      $(ids).each(function(i,evt){
        $('#eventSelector').append(`<option value="${evt.id}">${evt.name}</option>`);
      })

      $('#eventSelector').material_select();
      $('#multipleEvent').modal('open');
  })

  $(document).on('click','[href="#updateEvent"]', function(e){
    if($(e.target).hasClass('add-direct-evt') || $(e.target).parent().hasClass('add-direct-evt')){
      $modal = $('#updateEvent');
      $modal.removeAttr('data-id');
      $modal.find('.btn-e-update, .e-create').show();
      $modal.find('.event-selection').show();
      $modal.find('.btn-e-modify').hide();
      $modal.find('.ev-info').remove();
      $modal.find('.e-documents, .e-comments').empty();
      $modal.find('.documents-number').empty().append(`${$modal.find('.documents-number').data('none')}`);
      $modal.find('.comments-number').empty().append(`${$modal.find('.comments-number').data('none')}`);
      $modal.find('.event-element-name').empty().append($(`.stage-element[data-id="${$(this).attr('data-sid')}"]`).data('name'));
      $modal.find('.fa-external-link-alt').attr({
        'data-id' : $(this).attr('data-sid'),
        'data-tooltip' : $(this).attr('data-sid') ? evtAccStgMsg : evtAccActMsg,   
      }).tooltip();
      updateEvents();
      $stgElmt = $(`.stage-element[data-id="${$(this).attr('data-sid')}"]`);
      //$modal.find('.dp-start').pickadate('picker')
          
      //    .set('min', new Date(parseInt($(`.stage-element[data-id="${$(this).attr('data-sid')}"]`).data('sd')) * 1000));


      //$stgElmt = $(`.stage-element[data-id="${$modal.find('.fa-external-link-alt').data('id')}"]`);
      sdate = new Date(parseInt($stgElmt.data('sd')) * 1000);
      edate = new Date((parseInt($stgElmt.data('sd')) + parseInt($stgElmt.data('p'))) * 1000);

      $modal.find('.dp-start').pickadate('picker')
        .set('select',Math.min(edate, Math.max(sdate,new Date())))
        .set('min', sdate)
        .set('max', edate);
      $modal.find('.dp-end').pickadate('picker')
        //.set('select',new Date())
        .set('min', sdate)
        .set('max', edate);
      
      $modal.find('.e-dates-header').show();
      $modal.find('.event-dates-content').hide();
      //$('.event-element-name').empty().append($(this).closest('.act-info').find('.act-info-name').text());
      $('.update-event-btn').attr('data-aid',$(this).attr('data-aid'));
      $('.update-event-btn').attr('data-sid',$(this).attr('data-sid'));
      $('.update-event-btn').attr('data-ms',$(this).attr('data-ms'));
      $('.update-event-btn').attr('data-eid',$(this).hasClass('btn-floating') ? 0 : $(this).attr('data-eid'));
    }
  })

  $('.e-set-exp-res-date').on('click',function(){
    $(this).parent().find('.element-input').show();
    $(this).hide();
  });

  $('.e-dates-btn').on('click',function(){
    const $modal = $(this).closest('.modal');
    $modal.find('.e-dates-header').hide();
    $modal.find('.event-dates-content').show();
  })

  $('.add-e-document, .add-e-comment').on('click',function(e){
    const $this = $(this);
    if($this.hasClass('add-e-comment') && $this.closest('.modal').find('#eventGSelector option:selected').data('evgid') == 1){
      return false;
    }
    e.preventDefault();
    $holder = $this.hasClass('add-e-comment') ? $('ul.e-comments') : $('ul.e-documents');
    proto = $holder.data('prototype-creation');
    proto = proto.replace(/__name__/g, $holder.children().length);
    $newProto = $(proto);
    if($this.hasClass('add-e-document')){
      $newProto.find('.dropify').dropify({
        messages: {
          'default' : msgDocInsert,
          'replace' : msgDocReplace,
          'remove' : msgDocRemove,
          'error': msgDocError,
        }
      });
      $newProto.find('.dropify-message').addClass('flex-center');
      $newProto.find('.dropify-infos-message').addClass('no-margin');
    }
    $holder.append($newProto);
  })

  $(document).on('click','.e-com-reply',function(){
    const $this = $(this);
    $holder = $('ul.e-comments');
    proto = $holder.data('prototype-creation');
    proto = proto.replace(/__name__/g, $holder.children().length);
    $newProto = $(proto);
    $this.closest('.com-reply-zone').hide();
    $this.closest('.e-comment').addClass('being-replied').after($newProto);
  })

  $(document).on('click','.e-doc-remove',function(){
    $(this).closest('.e-document').remove();
  });

  $(document).on('click','.e-com-remove',function(){
    const $comElmt = $(this).closest('.e-comment');
    if($comElmt.prev().hasClass('being-replied')){
      $comElmt.prev().find('.com-reply-zone').show();
    }
    $comElmt.remove();
  });

  function prepareEvent(e){
    $actHolder = $(document).find('.process-list').eq(0);
    $evtElmt = $($actHolder.data('prototype-evt'));
    $evtElmt.attr({
      'data-id' : e.eid,
      'data-od' : e.od,
      'data-p' : e.p,  
    }).css('visibility','hidden');

    if(e.it.includes('fa')){
        $evtElmt.find('.event-logo-container i').addClass(`fa fa-${e.in} evg-${e.gg}`);
    } else {
        $evtElmt.find('.event-logo-container i').addClass(`material-icons evg-${e.gg}`).append(e.in);
    }
    $eventTooltip = $($evtElmt.attr('data-tooltip'));
    $eventTooltip.find('.evg').append(e.gt).addClass(`evg-${e.gn}`);
    $eventTooltip.find('.t-od').append(new Date(e.od * 1000).toLocaleDateString(lg+'-'+lg.toUpperCase(),{ month: 'numeric', day: 'numeric' }));
    if(e.rd){
        $eventTooltip.find('.t-rd').append(new Date(e.rd * 1000).toLocaleDateString(lg+'-'+lg.toUpperCase(),{ month: 'numeric', day: 'numeric' }));
    } else {
        $eventTooltip.find('.t-rd, .fa-calendar-check').remove();
    }
    if(e.it.includes('fa')){
        $eventTooltip.find('i:not(.fa)').addClass(`fa fa-${e.in} evg-${e.gn}`);
    } else {
        $eventTooltip.find('i:not(.fa)').addClass(`material-icons evg-${e.gn}`).append(e.in);
    }
    $eventTooltip.find('.evt').append(e.tt);
    if(!e.nbd){
        $eventTooltip.find('.event-documents').remove();
    } else {
        $eventTooltip.find('.event-documents span').append(`${e.nbd} ${$eventTooltip.data('document')}${e.nbd > 1 ? 's' : ''}`)
    }   
    if(!e.nbc){
        $eventTooltip.find('.event-comments').remove();
    } else {
        $eventTooltip.find('.event-comments span').append(`${e.nbc} ${$eventTooltip.data('comment')}${e.nbc > 1 ? 's' : ''}`)
    }

    $evtElmt.attr('data-tooltip',$eventTooltip.html()).tooltip();
    $indivActHolder = $(document).find(`.stage-element[data-id="${e.sid}"]`).closest('.activity-holder');
    $indivActHolder.addClass('tbd');
    $indivActHolder.find('.activity-component').append($evtElmt);
  }

  $('.update-event-btn').on('click',function(e){
    e.preventDefault();
    const $this = $(this);
    //const params = {sid: $this.attr('data-sid'), eid: $this.hasClass('btn-floating') ? 0 : $this.attr('data-eid'), mids: true};
    //data = $('#updateEvent form').serialize() + '&' + $.param(params);
    
    formData = new FormData($('#updateEvent form')[0]);
    formData.append('sid',$this.attr('data-sid'));
    formData.append('eid',$this.hasClass('btn-floating') ? 0 : $this.attr('data-eid'));
    formData.append('mids',true);

    $.ajax({
      type: "POST",
      data: formData,
      url: eurl,
      processData: false,
      contentType: $this.closest('.modal').find('.e-documents').length == 0 ? 'application/x-www-form-urlencoded' : false,
    })
    .done(function(e){
      prepareEvent(e);
      $('#updateEvent').modal('close');
    });
    



      /*$.post(eurl,data)
        .done(function(data){
            //location.reload();
        })
        .fail(function(data){
            console.log(data);
        })
        */
  })

  $(document).on('click','.e-selectable',function(){
    const options = { month: 'numeric', day: 'numeric' };
    if($(this).hasClass('e-multiple-events')){
      $(this).removeClass('e-selectable');
    }

    var $event = $(this);
    var id = $(this).attr('data-id');
    
    $.post(edurl,{id: id})
      .done(function(data){
        $("#eventGSelector").val(data.group);
        $modal = $("#updateEvent");
        $modal.find('.btn-e-update, .e-create').hide();
        $modal.find('.btn-e-modify').show();
        $stgElmt = $(`.stage-element[data-id="${data.sid}"]`);
        $modal.find('.dp-start').pickadate('picker')
          .set('select',new Date(data.odate.date))
          .set('min', new Date(parseInt($stgElmt.data('sd')) * 1000))
          .set('max', new Date((parseInt($stgElmt.data('sd')) + parseInt($stgElmt.data('p'))) * 1000));
        $modal.find('.e-odate').empty().append(new Date(data.odate.date).toLocaleDateString(lg+'-'+lg.toUpperCase(),options));
        if(data.rdate){
          $modal.find('.dp-end').pickadate('picker').set('select',new Date(data.rdate.date));
          $modal.find('.e-rdate').empty().append(new Date(data.rdate.date).toLocaleDateString(lg+'-'+lg.toUpperCase(),options));
        }
        $modal.find('.dp-end').pickadate('picker')
          .set('min', $modal.find('.dp-start').pickadate('picker').get('select'))
          .set('max', new Date((parseInt($stgElmt.data('sd')) + parseInt($stgElmt.data('p'))) * 1000));

        if(data.documents){
          $modal.find('.documents-number').empty().append(data.documents.length);
        } else {
          $modal.find('.documents-number').empty().append(`(${$modal.find('.documents-number').data('none')})`);
        }
        $docHolder = $modal.find('.e-documents');
        $docHolder.empty();
        $(data.documents).each(function(i,d){

            $docElmt = $($docHolder.data('prototype-existing'));
            if(d.oid != oid){
              $docElmt.find('.e-doc-rename-zone, .e-doc-upload-zone, .e-doc-delete-zone').remove();
            }
            $docElmt.find('.e-doc-ext').append(d.type);
            $docElmt.find('.e-doc-title').append(d.title);
            $docElmt.find('.e-doc-size').append(`${Math.round(d.size/1000)} Ko`);
            $docElmt.find('.fa-file-download').attr({
              'data-path' : d.path,
              'data-mime' : d.mime,
              'data-title' : d.title
            });
            $docElmt.attr('data-id',d.id);
            $docElmt.find('.tooltipped').tooltip();
            $docElmt.find('.collapsible').collapsible();
            $docHolder.append($docElmt);
        });
        $docHolder.find('.btn-e-update').hide();

        if(data.comments){
          $modal.find('.comments-number').empty().append(data.comments.length);
        } else {
          $modal.find('.comments-number').empty().append(`(${$modal.find('.documents-number').data('none')})`);
        }
        $comHolder = $modal.find('.e-comments');
        $comHolder.empty();
        $(data.comments).each(function(i,c){
            $comAndReplies = $('<div></div>');
            $comElmt = $($comHolder.data('prototype-existing'));
            $comAndReplies.append($comElmt);
            $comElmt.attr('data-id',c.id);
            $comElmt.find('.e-com-author').append(c.author);
            $comElmt.find('.e-com-content').append(c.content);      
            $comElmt.find('.e-com-updated').append(`${c.inserted} ${c.modified ? ' ('+modifiedMsg+')' : ''}`);  
            if(c.self){
              $modifyElmt = $(`<span class="e-com-modify tooltipped" data-tooltip="${msgComModify}" data-position="top"><i class="fa fa-pen"><i></span>`)
              $modifyElmt.tooltip();
              $comElmt.addClass('self').find('.e-com-right-elmts').append($modifyElmt);
            } else {
              $comElmt.append(`<div class="com-reply-zone flex-center-fe"><div class="dd-orange-text e-com-reply btn-e-update"><i class="fa fa-reply m-right"></i><span>Reply</span></div></div>`);
            }      
            if(c.replies){
              $repliesHolder = $('<ul class="replies"></ul>'); 
              $(c.replies).each(function(i,r){
                $reply = $($comHolder.data('prototype-existing'));
                $reply.attr('data-id',r.id);
                $reply.find('.e-com-author').append(r.author);
                $reply.find('.e-com-content').append(r.content);      
                $reply.find('.e-com-updated').append(`${r.inserted} ${r.modified ? ' ('+modifiedMsg+')' : ''}`);  
                if(r.self){
                  $modifyElmt = $(`<span class="e-com-modify tooltipped" data-tooltip="${msgComModify}" data-position="top"><i class="fa fa-pen"><i></span>`)
                  $modifyElmt.tooltip();
                  $reply.addClass('self').find('.e-com-right-elmts').append($modifyElmt);
                } else {
                  $reply.append(`<div class="com-reply-zone flex-center-fe"><div class="dd-orange-text e-com-reply btn-e-update"><i class="fa fa-reply m-right"></i><span>Reply</span></div></div>`);
                }
                $repliesHolder.append($reply);
              })
              $comAndReplies.append($repliesHolder);
            }
            $comHolder.append($comAndReplies);

        });

        $modal.find('.ev-info').remove();
        updateEvents(data.group, data.type);
        setTimeout(function(){
          var $evtSelector = $('[id*="eventType"]');
          //$('[id*="eventType"]').val(data.type);
          var $evgCircle = $("#updateEvent .evg-input-circle").clone();
          $evgCircle.css('position','');
          $evgCircle.removeClass('evg-input-circle').removeClass('evg-b-1').addClass(`evg-b-${$('#eventGSelector option:selected').attr('data-evgid')}`);
          $modal.find('.event-element-name').empty().append(data.sname);
          $modal.find('.fa-external-link-alt').attr({
            'data-id' : data.sid,
            'data-tooltip' : data.ms ? evtAccStgMsg : evtAccActMsg,   
          }).tooltip();

          var $headerEVG = $('<div class="ev-info flex-center"></div>');
          $headerEVG
            .append($evgCircle)
            .append(`<span class="sm-left">${$("#eventGSelector option:selected").text()}</span>`)
            .append('<span class="sm-left sm-right">:</span>')
            .append(`<span>${$('[id*="eventType"] option:selected').text().split('~').slice(-1)[0].trim()}</span>`)
            //.append(`<div class="btn-flat btn-e-update modal-trigger" href="#deleteEvent" data-id="${id}" style="display:none;"><i class="fa fa-trash"></i><div>`)
            .append(`<div class="btn-flat btn-e-update" style="display:none;"><i class="fa fa-cog"></i><div>`);
            $modal.find('header>h5').prepend($headerEVG);
            $modal.find('.event-selection').hide();
          $("#updateEvent").attr('data-id',id).modal('open');

        },200);
          
      })
      .fail(function(data){
        console.log(data);
      })  
  })

  $('.event-select-btn').on('click',function(){
    const selectedVal = $('#eventSelector').val();
    if($(`.event[data-id="${selectedVal}"]`).hasClass('e-multiple-events')){
      $(`.event[data-id="${selectedVal}"]`).addClass('e-selectable');
    }
    $(`.event[data-id="${parseInt(($('#eventSelector').val()))}"]`).click();
  })

  $(document).on('click', '.e-doc-update', function(){
    $(this).parent().find('.doc-actions').show();
    $(this).hide();
  })

  $(document).on('mouseover','.e-document',function(){
      var $modal = $(this).closest('.modal');
      if(!$modal.find('.doc-name-validate:visible, input:not(.select-dropdown):visible, textarea:visible').length){
        $(this).find('.doc-actions').show();
      }
  }).on('mouseleave','.e-document',function(){
    $(this).find('.doc-actions').hide();
  })
  
  $(document).on('click','.com-validate',function(){
      const $this = $(this);
      const $comElmt = $this.closest('.e-comment');
      const $comHolder = $this.closest('.e-comments');
      const $evtElmt = $comElmt.closest('.modal');
      $relatedDashEvt = $evtElmt.data('id') ? $(`.event[data-id="${$evtElmt.data('id')}"]`) : null;  
      const comExists = $comElmt.prev().hasClass('existing');
      const isEvtExisting = $evtElmt.attr('data-id') != null;
      const isReply = $comElmt.prev().hasClass('being-replied');
      if(comExists && !isReply){
        $existingComElmt = $comElmt.prev();
      } else {
        $existingComElmt = $($comHolder.data('prototype-existing'));
        $modifyElmt = $(`<span class="e-com-modify tooltipped" data-tooltip="${msgComModify}" data-position="top"><i class="fa fa-pen"><i></span>`)
        $modifyElmt.tooltip();
        $existingComElmt.addClass('self');
        $existingComElmt.find('.e-com-right-elmts').append($modifyElmt);
        $comElmt.before($existingComElmt);
      }
      const content = $comElmt.find('textarea').val();

      $params = {id: $comElmt.data('id'), eid: $evtElmt.data('id'), content: content};
      if(isReply){
        $params['cid'] = $comElmt.prev().prev().attr('data-id');
      }
      if($comElmt.data('pid')){$params['pid'] = $comElmt.data('pid');}
      if(!$comElmt.data('id') && $comElmt.prev().hasClass('being-replied')){$params['cid'] = $comElmt.prev().data('id');}
      if(!isEvtExisting){
        $params['sid'] = $evtElmt.find('.fa-external-link-alt').data('id');
        $params['evtid'] = $evtElmt.find('[name*="eventType"]').val();
        const sdStr = $modal.find('.dp-start').val() ? $modal.find('.dp-start').val().normalize("NFD").replace(/[\u0300-\u036f]/g, "") : "";
        const edStr = $modal.find('.dp-end').val() ? $modal.find('.dp-end').val().normalize("NFD").replace(/[\u0300-\u036f]/g, "") : sdStr;
        $params['oDateStr'] = (sdStr != "" ? new Date(sdStr) : new Date()).toLocaleString('en-EN',$sentDatesOptions);
        $params['expResDateStr'] = (edStr != "" ? new Date(edStr) : new Date()).toLocaleString('en-EN', $sentDatesOptions); 
      }
      $.post(uccurl,$params)
        .done(function(c){
          if(!isEvtExisting){
            $evtElmt.find('[type="submit"]').hide().parent();
            $evtElmt.find('.modal-close').show();
            $evtElmt.find('[href="#deleteEvent"]').show();
            prepareEvent(c);
          }
          if($relatedDashEvt){
            $relatedDashEvt.addClass('self');
          }
          $comElmt.remove();
          if(!comExists || isReply){
            $existingComElmt.attr('data-id',c.cid).find('.e-com-author').append(c.author);
            $evtElmt.find('.comments-number').empty().append($comHolder.find('.e-comment').length);
          } 
          if(!comExists || isReply || c.modified){
            $existingComElmt.find('.e-com-updated').empty().append(`${c.inserted} ${c.modified ? ' ('+modifiedMsg+')' : ''}`);
            $existingComElmt.find('.e-com-content').empty().append(content);  
          }
          $existingComElmt.closest('.com-reply-zone').show();
          $existingComElmt.show();
        })
  });

  $(document).on('click','.doc-name-validate',function(){
      const $this = $(this);
      const $docElmt = $this.closest('.e-document');
      const title = $this.parent().find('input').val();
      const $params = {id: $docElmt.data('id'), title: title}
      $.post(udturl,$params)
        .done(function(data){
          $docElmt.find('.doc-input-zone').remove();
          $docElmt.find('.e-doc-title').empty().append(title).show();
          $docElmt.find('.e-doc-size').show();
        })
  });

  $(document).on('click','.doc-upload-validate',function(){
      const $this = $(this);
      const $docElmt = $this.closest('.e-document');
      const $docHolder = $docElmt.closest('.e-documents');
      const $evtElmt = $docElmt.closest('.modal');
      if($this.closest('.col').find('.e-doc-remove').length){
        $hiddenDocElmt = $($docHolder.data('prototype-existing'));
        $hiddenDocElmt.hide();
        $docElmt.before($hiddenDocElmt);
        isExisting = false;
      } else {
        $hiddenDocElmt = $docElmt.prev();
        isExisting = true;
      }
      const isEvtExisting = $evtElmt.attr('data-id') != null;
      const $docFile = $docElmt.find('.dropify');
      var form = new FormData();
      form.append("file",$docFile[0].files[0]);
      
      if(!isEvtExisting){
        form.append("sid",$evtElmt.find('.fa-external-link-alt').data('id'));
        form.append("evtid",$evtElmt.find('[name*="eventType"]').val());
        const sdStr = $modal.find('.dp-start').val() ? $modal.find('.dp-start').val().normalize("NFD").replace(/[\u0300-\u036f]/g, "") : "";
        const edStr = $modal.find('.dp-end').val() ? $modal.find('.dp-end').val().normalize("NFD").replace(/[\u0300-\u036f]/g, "") : sdStr;
        form.append("oDateStr",(sdStr != "" ? new Date(sdStr) : new Date()).toLocaleString('en-EN',$sentDatesOptions));
        form.append("expResDateStr",(edStr != "" ? new Date(edStr) : new Date()).toLocaleString('en-EN', $sentDatesOptions));
      }

      form.append("eid",$evtElmt.data('id') ? $evtElmt.data('id') : 0);
      if(!isExisting){
        form.append("title",$docElmt.find('input[type="text"]').val());
      } else {
        form.append("id", $hiddenDocElmt.attr('data-id'));
      }
      var xhr = new XMLHttpRequest();
      xhr.open("POST",udcurl);
      xhr.onload = function (oEvent) {
        if (xhr.status === 200) {
            d = JSON.parse(xhr.response);
            $docElmt.remove();
            console.log('upload success',xhr.responseText);
            $hiddenDocElmt.find('.e-doc-ext').empty().append(d.type);
            $hiddenDocElmt.find('.fa-file-download').attr({
              'data-path' : d.path,
              'data-mime' : d.mime,
              'data-title' : d.title,
            });
            if(!isExisting){
              $hiddenDocElmt.find('.e-doc-title').append($docElmt.find('input[type="text"]').val());
              $evtElmt.find('.documents-number').empty().append($evtElmt.find('.e-document').length);
            }
            if(!isEvtExisting){
              $evtElmt.attr('data-id',d.eid);
              $evtElmt.find('[type="submit"]').hide().parent();
              $evtElmt.find('.modal-close').show();
              $evtElmt.find('[href="#deleteEvent"]').show();
              prepareEvent(c);
            }
            $hiddenDocElmt.find('.e-doc-size').empty().append(`${Math.round(d.size/1000)} Ko`);
            $hiddenDocElmt.find('.doc-actions').show();
            $hiddenDocElmt.attr('data-id',d.did).show();
            $evtElmt.find('.add-e-comment').removeClass('tooltipped').removeAttr('data-tooltip');
        } else {
            console.log("Error " + xhr.status + " occurred when trying to upload your file.<br \/>");
        }
      };
      xhr.send(form);
  });

  $(document).on('click','.e-doc-upload',function(){
      const $docElmt = $(this).closest('.e-document');
      const docTitle = $docElmt.find('.e-doc-title').text();
      const $docHolder = $docElmt.closest('.e-documents');
      $proto = $($docHolder.data('prototype-creation'));
      $proto.addClass('updating');
      $proto.find('.dropify').dropify();
      $proto.find('.input-field').empty().append(`<span>${docTitle}</span>`);
      $proto.find('.col:last-of-type').empty().append('<div class="btn doc-upload-validate btn-reduced-padding"><i class="material-icons">check</i></div>')
      $proto.find('.dropify-message p').css('margin','auto');
      $docElmt.after($proto);
      $docElmt.hide();
  });

  $(document).on('mouseover','.s-elmt-dates, .events-title, .s-title-zone',function(){
    if(!$(this).closest('.modal').find('input:visible').length){
      $(this).find('.btn-s-update').show();
    }
  }).on('mouseleave','.s-elmt-dates, .events-title, .s-title-zone',function(){  
    if(!$(this).find('input').length){
      $(this).find('.btn-s-update').hide();
    }
  })

  $(document).on('mouseover','ul.participants-list',function(){
    if($stageModal.data('id')){
      $(this).find('.btn-s-update').show();
    }
  }).on('mouseleave','ul.participants-list',function(){  
    if($stageModal.data('id')){
      $(this).find('.btn-s-update').hide();
    }
  })

  $(document).on('mouseover','.e-dates-header, .e-documents-header, .e-comments-header, .e-comment',function(){
    var $modal = $(this).closest('.modal');
    if(!$modal.find('input:not(.select-dropdown):visible,textarea:visible').length){
      $(this).find('.btn-e-update').show();
    }
  }).on('mouseleave','.e-dates-header, .e-documents-header, .e-comments-header, .e-comment',function(){  
    $(this).find('.btn-e-update').hide();
  })

  $(document).on('click','.s-name-update',function(){
    const $this = $(this);
    if(!$this.find('input').length){
      const $modal = $this.closest('.modal');
      const $sName = $modal.find('.s-name');
      $sName.hide();
      $this.find('.fa-pen').hide();
      $this.prepend(`<div class="s-name-input-zone"><input type="text" class="s-name-input-name" value="${$sName.text()}"><div class="btn btn-reduced-padding s-name-validate"><i class="material-icons">check</i></div><div>`);
    } 
  })

  $(document).on('click','.s-name-validate',function(){
    const $this = $(this);
    const $modal = $this.closest('.modal');
    const name = $this.parent().find('input').val();
    const $params = {id: $modal.data('id'), name: name}
    $.post(usnurl,$params)
      .done(function(data){
        if(data.actNameChg){
          $(`.stage-element[data-id=${$modal.data('id')}]`).closest('.activity-holder').find('.act-info').find('.act-info-name').empty().append(name);
        }
        $modal.find('.s-name-input-zone').remove();
        $modal.find('.s-name-update > i').show();
        $modal.find('.s-name-update').hide();
        $modal.find('.s-name').empty().append(name).show();
      })
  })

  $(document).on('click','.e-com-modify',function(){
    const $comment = $(this).closest('.existing');
    const $content = $comment.find('.e-com-content').text().trim();
    $proto = $($(this).closest('.e-comments').data('prototype-creation'));
    $proto.find('textarea').text($content);
    $proto.attr('data-id',$comment.data('id')).addClass('flex-center');
    $proto.find('.e-com-remove').removeClass('e-com-remove').addClass('modal-trigger').attr('href','#deleteComment');
    //$proto.append('<div class="c-flex-center c-action-buttons"><i class="fa fa-times dd-text btn-flat modal-trigger" href="#deleteComment"></i><i class="fa fa-check dd-text btn-flat e-com-delete"></i></div>');
    //$proto.children().first().css('width','90%');
    $comment.after($proto);
    $comment.hide();
  });

  $(document).on('click','[href="#deleteComment"]',function(){
    $('.e-com-delete').attr('data-id',$(this).closest('.e-comment').data('id'));
  });

  $(document).on('click','.e-com-delete',function(){
   
    urlToPieces = dcomurl.split('/');
    id = $(this).attr('data-id');
    urlToPieces[urlToPieces.length - 1] = id;
    url = urlToPieces.join('/');
    $.delete(url,null)
      .done(function(){
        $(`.e-comment.existing[data-id="${id}"], .e-comment[data-id="${id}"]`).remove();
        var nbComs = parseInt($('.comments-number').text());
        $('.comments-number').empty().append(nbComs ? `(${$('.comments-number').data('none')})` : nbComs - 1); 
      })
    
  });

  $(document).on('click','[href="#deleteDocument"]',function(){
    $('.e-doc-delete').attr('data-id', $(this).closest('.e-document').data('id'));
  });

  $(document).on('click','.e-doc-delete',function(){
   
    urlToPieces = ddocurl.split('/');
    id = $(this).attr('data-id');
    urlToPieces[urlToPieces.length - 1] = id;
    url = urlToPieces.join('/');
    $.delete(url,null)
      .done(function(){
        $(`.e-document[data-id="${id}"]`).remove();
        var nbDocs = parseInt($('.documents-number').text());
        $('.documents-number').empty().append(nbDocs ? `(${$('.documents-number').data('none')})` : nbDocs - 1); 
      })
    
  });

  $(document).on('click','.fa-file-download',function(){
    var $this = $(this);
    var xhr = new XMLHttpRequest();
    xhr.open("GET", `../../lib/evt/${$this.attr('data-path')}`,true);
    xhr.responseType = "blob";
    xhr.onreadystatechange = function(){
      if(xhr.readyState === 4 && xhr.status === 200) {
        saveFile(xhr.response,$this.attr('data-title'));
      }
    }
    xhr.send();
  })

  $(document).on('click', '.e-document .fa-pen',function(){
    const $doc = $(this).closest('.e-document');
    $doc.find('.e-doc-size').hide();
    $docTitle = $doc.find('.e-doc-title');
    $docTitle.after(`<div class="doc-input-zone"><input type="text" class="doc-input-name" value="${$docTitle.text()}"><div class="btn btn-reduced-padding doc-name-validate"><i class="material-icons">check</i></div><div>`);
    $docTitle.hide();

  })

  $(document).on('click','[href="#deleteEvent"]',function(){
    $('.remove-event').attr('data-id',$(this).closest('.modal').attr('data-id'));
  })

  $('.remove-event').on('click',function(){
    urlToPieces = deurl.split('/');
    id = $(this).attr('data-id');
    urlToPieces[urlToPieces.length - 1] = id;
    url = urlToPieces.join('/');
    $.delete(url,null)
      .done(function(){
        $(`.event[data-id="${id}"]`).remove();
        $('#updateEvent').modal('close');
      })
  });

  $(document).on('click','.participant-delete, .btn-participant-validate',function(){
    var $this = $(this);
    $this.closest('.modal').find('.modal-footer .red-text').remove();
    $('.red-text').remove();
  })

  /*
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
  */

  $('.btn-add-event').on('click',function(){
    var $modal = $(this).closest('.modal');
    if ($(this).closest('.modal').hasClass('a-multiple-stages')){
      $('#multipleStage').modal('open');
    }
    $(`[href="#updateEvent"][data-sid="${$modal.data('id')}"]`).find('i').click();
    $modal.modal('close');
  })

  /*
  $('.btn-s-modify:not(.modal-close').on('click',function(){
    var $modal = $(this).closest('.modal');
    $modal.find('.participant-btn').each(function(i,e){
      $(e).addClass('deletable modal-trigger').attr('href','#deleteParticipant').append('<div class="p-delete-overlay flex-center" style="display:none;"><i class="fa fa-trash"></i></div>');
    });
    $modal.find('.btn-s-modify').hide();
    $modal.find('.btn-s-update').show();
  })
  */

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

  $('.setup-activity, .create-stage').on('click',function(e){
    e.preventDefault();
    const $this = $(this);
    $.each($('.red-text'),function(){
      $(this).remove();
    });

    if($('.participants-list--item.edit').length){
      $this.parent().prepend('<span class="red-text sm-right part-error">Please validate (or remove) above participant before validating</span>');
      return false;
    }

    const params = {aid: 0, link: $('.s-link').data('tooltip'), btn: $this.hasClass('create-stage') ? 'submit' : 'complexify'};
    var data = $this.closest('form').serialize() + '&' + $.param(params)

    $.post(scurl,data)
      .done(function(s){
        if($('.no-activity-overlay:visible').length){
          location.reload();
        }
        $('#createStage').modal('close');
        
        $actHolder = $(document).find('.process-list').eq(0);
        $stgElmt = $($actHolder.data('prototype'));
        $stgElmt.attr('data-id',s.aid).removeClass('dummy-activity').addClass(s.apr).addClass('tbd').find('.activity-component').attr({
            'data-sd' : s.asd,
            'data-p' : s.ap
        }).find('.stage-element').attr({
            'data-sd' : s.sd,
            'data-p' : s.p,
            'data-id' : s.id,
            'data-name' : s.n
        });
        $stgElmt.find('.act-info-name').append(s.an);
        $stgElmt.find('[href="#deleteActivity"]').attr({
          'data-aid' : s.aid
        });
        $stgElmt.find('[href="#updateStageProgressStatus"]').attr({
          'data-eid' : s.aid,
          'data-sid' : s.id,
          'data-progress' : s.apr,
        });
        $stgElmt.find('[href="#updateEvent"]').attr({
            'data-aid' : s.aid,
            'data-sid' : s.id,
        });
        $stageTooltip = $($stgElmt.find('.stage-element').attr('data-tooltip'));

        $stageTooltip.find('.t-stage-name').append(s.n);
        $stageTooltip.find('.t-stage-dates span').append(s.ssed);
        $tooltipPartHolder = $stageTooltip.find('.participants-t-holder');
        if(!s.participants){
            $noPartProto = $($tooltipPartHolder.data('prototype-no-participants'));
            $tooltipPartHolder.append($noPartProto);
        } else {
            $.each(s.participants,function(_i,p){
                $partProto = $($tooltipPartHolder.data('prototype'));
                $partProto.find('.user-picture').attr('src', p.picture).next().text(p.fullname);
                $tooltipPartHolder.append($partProto);
            })
        }
        
        $stgElmt.find('.stage-element').attr('data-tooltip',$stageTooltip.html());
        $clientZone = $stgElmt.find('.activity-clients');
        if(!s.clients){
            $clientZone.empty();
        } else {
            $.each(s.clients,function(i,c){
                $clientProto = $($clientZone.data('prototype'));
                $clientProto.attr('data-tooltip',c.name).find('.account-logo').attr('src',c.logo);
                $clientZone.append($clientProto);
            })
        }
        $stgElmt.find('.tooltipped').tooltip();
        $stgElmt.hide();
        $actHolder.find('.activity-list').prepend($stgElmt);
        dateUpdate(false,[s.aid]);

        //location.reload();
      })
      .fail(function(data){
        $.each(data.responseJSON, function(key, value){
          $.each($('#createStage input'),function(){
            if($(this).attr('name').indexOf(key) != -1){
                $(this).after('<div class="red-text"><strong>'+value+'</strong></div>');
                return false;
                }
          });
        })
      })
  })

  $(document).on('click','.participant-delete',function(e){
    var $this = $(this);
    pid = $this.data('pid');
    if(!pid){
      $(this).closest('.participant-btn').remove();
    } else {
      urlToPieces = dpurl.split('/');
      urlToPieces[urlToPieces.length - 4] = $this.data('id');
      urlToPieces[urlToPieces.length - 1] = pid;
      url = urlToPieces.join('/');
      $.delete(url,null)
        .done(function(){
          $('#deleteParticipant').modal('close');
          $(document).find(`[href="#deleteParticipant"][data-pid="${pid}"]`).closest('.participant-btn').remove();
          $('.nb-participants').empty().append(`(${$('.participant-btn').length})`);
        })

    }
  });

  $(document).on('click','[href="#deleteParticipant"]',function(){
    var $this = $(this);
    var $modal = $(this).closest('.modal');
    $('.participant-delete').attr({'data-id':$modal.data('id'),'data-pid':$this.data('pid')});
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

  //if(getCookie('view_type') == 't'){

  //feedDashboardScreen();
    
  function feedDashboardScreen(){

    $actHeight = !$('.stages-holder:visible').length ? 75 : $('.stages-holder:visible').eq(0).height();
    $mainHeaderElmtsHeight = $('.sorting-type').height() + $('.timescale').height() + $('.tabs-t-view').height();
    $mainHeight = Math.min(1000, $(window).height());
    $actList = $();
    totalPotentialAct = Math.floor(($mainHeight - $mainHeaderElmtsHeight) / $actHeight);
    nbVisibleAct = $('.activity-content-stage:visible:not(.dummy)').length;
    if(nbVisibleAct){
      $('.activity-list').attr('data-pNb',$('.activity-content-stage:visible:not(.dummy)').length);
    }
    nbAct = $('.stages-holder').length;
    if (nbVisibleAct == 0 /* && !$('.virtual-activities-holder').length*/){
        for(k = 0; k < parseInt($('.activity-list').attr('data-pNb') ?  $('.activity-list').attr('data-pNb') : totalPotentialAct); k++){
          actProto = $('.process-list-t').data('prototype');
          $actProto = $(actProto);
  
          //if(!nbVisibleAct){
          var sdOffDays = getRandomInt(7,320);
          //var ssd = moment(moment().year()+'-01-01').add(sdOffDays,'d');
          var periodDays = getRandomInt(Math.max(0,15 - sdOffDays), 350 - sdOffDays);
          //var sed = ssd.add(periodDays,'d');
          //var p = moment.duration(moment(sed).diff(moment(ssd)));
          var sdate = new Date(annee, 0, sdOffDays);
          var edate = new Date(annee, 0 , sdOffDays + periodDays);
          var sdateU = sdate.getTime() / 1000;
          var periodU = (edate.getTime() - sdate.getTime()) / 1000;
          //$actComponent = $(`<ol class="activity-component" data-sd="${sdateU}" data-p="${periodU}"></ol>`);
          $actComponent = $actProto.find('.activity-component').attr({
            'data-sd': sdateU,
            'data-p' : periodU
          });
          $stgElmt = $($actProto.find('.stages-holder').data('dummy'));
          $stgElmt.attr('data-sd',sdateU);
          $stgElmt.attr('data-p', periodU);
          $stgElmt.find('.s-day').empty().append(sdate.getDate());
          $stgElmt.find('.e-day').empty().append(edate.getDate());
          $actComponent.append($stgElmt);
          //$actProto.find('.stages-holder').append($actComponent);
          /*
          $actProto.find('.stage-element').attr('data-sd',sdate.getTime() / 1000);
          $actProto.find('.stage-element').attr('data-p', (edate.getTime() - sdate.getTime()) / 1000);
          $actProto.find('.s-day').empty().append(sdate.getDate());
          $actProto.find('.e-day').empty().append(edate.getDate());
          */
          //} 
  
          $actList = $actList.add($actProto);
            
        }
        
        displayTemporalActivities($actList, nbSubInt, nbAct > 0, false);
        if(nbAct){
          $actList.each(function(i,e){
            $(e).find('.stage-element').remove();
            $(e).find('.act-info').empty();
          });
        } else {
          const $toBeFeededActs = $actList;
          const totalPotentialAct = Math.floor(($mainHeight - $mainHeaderElmtsHeight) / $actHeight);
          const dummyParams = {wa:1, td: totalPotentialAct};
          $.post(dcurl,dummyParams)
            .done(function(data){
  
              $toBeFeededActs.each(function(i,e){
                  $(e).find('.act-info-name').append(data.dummyElmts[i].actName);
                  $(e).find('.activity-client-name').attr('data-tooltip',data.dummyElmts[i].name).tooltip();
                  $(e).find('.account-logo').attr('src',data.dummyElmts[i].logo);
                
              })
            })
            .fail(function(data){
              console.log(data);
            })
          }

          if(!$('.virtual-activities-holder').length){

              var $actHolder = $('<div class="virtual-activities-holder"></div>');
              
              if(!nbVisibleAct){
                $actList.each(function(i,e){
                  $actHolder.append($(e));
                })
              }
              
            
              $appenedElmt = $('.activity-list:visible').length ? $('.activity-list:visible').last().parent() : $('.dummy-activities-container');
              $appenedElmt.append($actHolder);
          }

          if(!nbAct){
            $actHolder.append(noActOverlay);
          }
          
      }
  
  }

  

  $('.remove-activity').on('click',function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $.delete(daurl,{r: 'json',id: $(this).data('id')})
      .done(function(data){
        $(`[href="#deleteActivity"][data-aid="${id}"]`).closest('.activity-holder').remove();
      })
      .fail(function(data){
        console.log(data);
      })
  });

  $(document).on('click','[href="#deleteActivity"]',function(e){
    $('.remove-activity').data('id',$(this).attr('data-aid'));
  })

  if(currentWfiId && fc){
    setTimeout(function(){
        $('#beforeStarting img').attr({
          'src' : $('.account-logo').eq(1).attr('src'),
          'data-tooltip': $('.account-logo').eq(1).parent().attr('data-tooltip')
        }).tooltip();
        $('#beforeStarting').modal('open');
    },300);
  }

  $('.letz-start-btn').on('click',function(e){
    e.preventDefault();
    if(!currentWfiId){
      location.reload();
    }
  })

  $('.timescale-change-btn').on('click',function(){
      $('#timescaleSelector').material_select();
      $(this).hide();
  });

  $('#timescaleSelector').on('change',function(){
      const val = $(this).val();
      const now = new Date();
      eraseCookie('ts');
      eraseCookie('ci');
      setCookie('ci',`${val == 'y' ? now.getFullYear() : (val == 't' ? 'q-' + moment().quarter() + '-' + now.getFullYear() : 'w-'+ moment().week() + '-' + now.getFullYear() )}`,365);
      setCookie('ts',$(this).val(),365);
      location.reload();
  });

  setTimeout(function(){
    setInterval(function(){
        var $actTBD = $(document).find('.tbd');
        if(!$actTBD.length){
          return false;
        } else {
          var actIds = [];
          $actTBD.each(function(i,e){
            $elmt = $(e).hasClass('activity-holder') ? $(e) : $(e).closest('.activity-holder');
            actIds.push($elmt.data('id'));
          })
          dateUpdate(false,actIds);
        }
    },15000);
  },1500)

  $('.prev-act-btn, .next-act-btn').on('click',function(){
      const $this = $(this);
      index = $('.activity-holder').index($('.activity-holder:visible'));
      $showableAct = $('.activity-holder').eq($this.hasClass('prev-act-btn') ? index-1 : (index == $('.activity-holder').length - 1 ? 0 : index + 1));
      $showableAct.show();
      $('.activity-holder').eq(index).hide();
      $('.header-m-row .act-name').empty().append($showableAct.find('.stages-holder').data('act-name'));
      $('.m-act-update').attr('data-id',$('.stage-element:visible').data('id'));
      $('.add-direct-evt').attr({
        'data-sid' : $('.stage-element:visible').data('id'),
        'data-aid' : $('.activity-holder:visible').data('id')
      });

  })

  if($('#invitationStage').length){
    $('#invitationStage select').material_select();
    $('#invitationStage').modal('open');
  }

  $('.link-accept-stage-btn').on('click',function(){
    const $params = {id: $(this).data('id'), aid: $('[name="stageAccountSelector"]').val()}
    $.post(lsurl,$params)
      .done(function(data){
        location.reload();
      })
  })

  $('.link-decline-stage-btn').on('click',function(){
    eraseCookie('is');
  })

  $('.add-enddate-zone').on('click',function(){
    $('.enddate-input').show();
    $('.add-enddate-zone').parent().hide();
    $endCal = $('.s-dates-row .dp-end');
    if(!$endCal.pickadate('picker').get('select')){
      $endCal.pickadate('picker').set({
        'select' : Date.now(),
        'min' :  new Date($('.s-dates-row .dp-start').pick),
      });
    }
  })

  $('.set-open-enddate').on('click',function(){
    $('.enddate-input').hide();
    $('.add-enddate-zone').parent().show();
    $endCal = $('.s-dates-row .dp-end');
    $endCal.pickadate('picker').clear();
  })

});




