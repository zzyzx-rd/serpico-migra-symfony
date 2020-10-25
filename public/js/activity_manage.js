
function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
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

$('#firstConnectionModal, #beforeStarting').modal({
  dismissible: false,
})

$('.event').tooltip({
  complete: function(){
    $(this).closest('.stage-element').tooltip('close');
  }
})

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
$(document).ready(function(){

    console.log($('.value-scale').material_select());
    console.log($('.scale').material_select());
});

moment().format();
var now = new Date();
var annee   = now.getFullYear();
var anneeSuiv = annee + 1;


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

//dTrans($('#eventGSelector').find('option'),'EventGroupName','name');

if($('[class*="dp-"]').length){

  var startCal = $('#createActivity').find('.dp-start');
  var endCal = $('#createActivity').find('.dp-end');
  var startDateTS = (startCal.val() == "") ? Date.now() : new Date(startCal.val());
  var endDateTS = (endCal.val() == "") ? startDateTS : new Date(endCal.val());
  var startDate = new Date(startDateTS);
  var endDate = new Date(endDateTS);
  var ts = getCookie("ts");
  var ci = getCookie("ci");
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
  endCal.pickadate('picker').set('select',endDate).set('min',startDate);
}


$('#eventGSelector').on('change',function(){
  updateEvents();
})



function updateEvents($evgId = null, $evtId = null) {
  $evgSelect = $('#eventGSelector');
  $evtSelect = $('[id*="eventType"]');
  if($evgId){
    $evgSelect.val($evgId);
  }
  const $stylizableSelects = $evgSelect.add($evtSelect);

  $.post(geurl,{id: $evgSelect.val()})
    .done(function(eventTypes){ 
      $evtSelect.empty();
      $.each(eventTypes, function(i,e){
        $evtSelect.append(`<option value="${e.id}">${e.name}</option>`);
      })

      if($evtId){
        $evtSelect.val($evtId);
      }

      $stylizableSelects.material_select(); 

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
  var centralElWidth = $('.activity-content-stage:visible').eq(0).width();
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


//var echelle = centralElWidth / tDays;
  

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

    var centralElWidth = $('.activity-content-stage:visible').length ? $('.activity-content-stage:visible').eq(0).width() : $('.dummy-activities-container').width() * 0.75;
    var echelle = centralElWidth / tDays;
    var $actCurDate = $activities.find('.curDate');
    cOf = Math.max(0, moment.duration(moment().diff(moment(si),'days')).milliseconds());

    if(cOf > 0 && cOf < parseInt(dayDiff(ei, si))){
      
      $actCurDate.show();
      $actCurDate.each(function(i,e){
        $(e).css('left', Math.round(10000 * c / tDays) / 100 + '%');
      });
    
    } else {
      $actCurDate.hide();
    }
    
    $activities.find('.activity-content-stage').css({
      'background' : 'repeating-linear-gradient(90deg, #f3ccff2b, #63009445 '+ centralElWidth / $nbSubInt +'px, #ffffff '+ centralElWidth / $nbSubInt +'px, #ffffff '+ centralElWidth / ($nbSubInt/2) +'px)'
    });

    if($nonEmptySet){
      
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
            'width': Math.max(1,Math.round(10000 * sPctWidthP) / 100) + "%",
            'height' : '15px',
            'border-radius' : '0.3rem',  
          });
        });
      
      }
    }
  }

  //$('.chevron').css({'left': 'calc('+Math.round(10000 * (c / tDays )) / 100 + '% - 10px)' });

  $(window).on('resize',function(){
    //setTimeout(function(){
      var centralElWidth = $('.activity-content-stage:visible').eq(0).width();  
      var dateChevron = $('.chevron');
      var actCurDate = $('.curDate');
      var nbIntSubElmts = $('.months-ref').children().length - 1;
      dateChevron.css({'left': 'calc('+Math.round(10000 * (c / tDays )) / 100 + '% - 10px)' });
      actCurDate.each(function(i,e){
        $(e).css({'left': Math.round(10000 * (c / tDays )) / 100 + '%' });
      });
      $('.activity-content-stage').css({
        'background' : 'repeating-linear-gradient(90deg, #f3ccff2b, #63009445 '+ centralElWidth / nbIntSubElmts +'px, #ffffff '+ centralElWidth / nbIntSubElmts +'px, #ffffff '+ centralElWidth / (nbIntSubElmts/2) +'px)'
      });
    //}, 200);
  });

  if(!$('.activity-component').length){
    feedDashboardScreen();
  }


  dateUpdate();

function dateUpdate() {


    $('.s-day').css('padding-left','');
    $('.e-day').css('padding-right','');
    var ts = getCookie("ts");
    var ci = getCookie("ci");
    var c = new Date();
    y = parseInt(ci.split('-').slice(-1)[0]);
    cInt = parseInt(ci.split('-')[1]); 
    var month = ['J', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D'];
    $('#activities-container').find('.months-ref').children().not('.chevron').remove()
    if (ts == "y") {
        for (var i = 0; i < 12; i++) {
            $('#activities-container').find('.months-ref').append('<div class="col s1">' + month[i] + '</div>');
        }
        si = new Date(ci, 0, 1);
        ei = new Date((parseInt(ci) + 1), 0, 1);
        $('.curr-int-value').empty().append(parseInt(ci));
        $('.prev-interval-val').empty().append((parseInt(ci) - 1));
        $('.next-interval-val').empty().append((parseInt(ci) + 1));
        var tDays = ndDayPerYears((parseInt(ci)));
        //var c = getDay();

    } else {
        $('.months-ref').removeClass('row').addClass('flex-center-sa'); 
        var datesInt = datesInterval(ts, cy, cInt);
        var si = datesInt[0];
        var ei = datesInt[1];
        pInt = ts == 't' ? (cInt - 1) % 4 : (cInt - 1) % 52; 
        nInt = ts == 't' ? (cInt + 1) % 4 : (cInt + 1) % 52; 
        var ny = cy + 1;
        var py = cy - 1;
        var tDays = parseInt(dayDiff(si, ei)) + 1;
        //var c = moment.duration(moment().diff(moment(si),'days')).milliseconds();
        if(ts == 't'){
          $('.prev-interval-val').empty().append(`${lg == 'fr' ? 'T' : 'Q'}${pInt == 0 ? 4 : pInt} ${pInt == 0 ? py : ''}`);
          $('.next-interval-val').empty().append(`${lg == 'fr' ? 'T' : 'Q'}${nInt == 0 ? 4 : nInt} ${nInt == 1 ? ny : ''}`);
          $('.curr-int-value').empty().append(`${lg == 'fr' ? 'T' : 'Q'}${cInt} <span class="int-precision">${cy}</span>`);
        } else {
          $('.prev-interval-val').empty().append(`${lg == 'fr' ? 'S' : 'W'}${pInt == 0 ? 52 : pInt} ${pInt == 0 ? py : ''}`);
          $('.next-interval-val').empty().append(`${lg == 'fr' ? 'S' : 'W'}${nInt == 0 ? 52 : nInt} ${nInt == 1 ? ny : ''}`);
          $('.curr-int-value').empty().append(`${lg == 'fr' ? 'S' : 'W'}${cInt} <span class="int-precision">${cy}</span>`);
        }
        $('.start-int-value').empty().append(`${si.getDate()}/${si.getMonth() + 1}`);
        $('.end-int-value').empty().append(`${ei.getDate()}/${ei.getMonth() + 1}`);
        //width = 100 / 13;
        //week = moment(si).week();
        wDate = moment(si);
        $timescale = $('#activities-container').find('.months-ref');
        if(ts == 't'){
          ct = wDate.quarter();
          offset = moment.duration(moment(datesInterval(ts,cy,1)[0]).diff(moment(`${wDate.year()}-01-01`))).days() < 4 && moment(moment(datesInterval(ts,cy,1)[0])).week() == 2 ? -1 : 0;
          while (wDate.quarter() == ct){
            week = wDate.week();
            
            if(week == 1){
              if (moment.duration(moment(`${ct == 4 ? cy : cy - 1}-12-31`).diff(moment(wDate))).days() >= 3){
                week = 53;
              }
            }
            $timescale.append('<div><sub>s</sub>' + (week + offset == 0 ? 52 : week + offset) + '</div>');
            wDate = wDate.add(1,'w');
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

    div = $('.months-ref').children().length - 1;

    $('.activity-content-stage').css({
        'background': 'repeating-linear-gradient(90deg, #f3ccff2b, #63009445 ' + (((centralElWidth) / div)) + 'px, #ffffff ' + (((centralElWidth) / div)) + 'px, #ffffff ' + ((centralElWidth) / (div / 2)) + 'px)'
    });

    var now = new Date();

    var dateChevron = $('.chevron');
    var actCurDate = $('.curDate');

    var echelle = centralElWidth / (ei - si);
    if (ei > now && now > si) {
        dateChevron.show();
        dateChevron.css({'left': 'calc(' + Math.round(10000 * (c - si) / (ei - si)) / 100 + '% - 10px)'});
        actCurDate.each(function (i, e) {
            $(e).css({'left': Math.round(10000 * (c - si) / (ei - si)) / 100 + '%'});
            $(e).show();
        });
    } else {
        dateChevron.hide();
        actCurDate.each(function (i, e) {
            $(e).hide();
        });
    }

    $.each($('.activity-component'), function () {
        var $this = $(this);
        $this.show();
        $(this).closest('.activity-holder').show();
        var sdU = parseInt($this.data("sd"));
        var p = parseInt($this.data("p"));

        var id = $this.data("id");
        var sa = new Date(sdU * 1000);
        var ea = new Date((sdU + p) * 1000);
        csd = Math.max(si,sa);
        ced = Math.min(ei,ea);
        actOff = (Math.min(ei,csd) - si) / (ei - si);
        actW = (ced - csd) / (ei - si);
        $this.css({'margin-left': Math.round(10000 * actOff) / 100 + "%"});
        $this.css({'width': Math.round(10000 * actW) / 100  + "%"});
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
            if (ssd > ei) {
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
                'margin-left': Math.round(10000 * sOff) / 100 + "%",
                'width': Math.round(10000 * sW) / 100 + "%",
                'background': ssd >= c ? '#5CD08F' : (sed > c ? 'linear-gradient(to right, transparent, transparent ' + Math.max(1, Math.round(10000 * (c - csd) / (ced - csd)) / 100) + '%, #7942d0 ' + Math.round(10000 * (c - csd) / (ced - csd)) / 100 + '%), repeating-linear-gradient(61deg, #7942d0, #7942d0 0.5rem, transparent 0.5px, transparent 1rem)' : 'gray'),
                'height': '7px',
                'border-radius': '0.3rem',
            });

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
                'margin-left': Math.round(10000 * eOff) / 100 + "%",
                'width': Math.round(10000 * eW) / 100 + "%",
                'height': '15px',
                'border-radius': '0.3rem',
            });
        });
    })
       
    //});

    feedDashboardScreen();
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

                  date = period == 't' ? moment(year, "YYYY").quarter(intNb+i).toDate() : moment(year, "YYYY").week(intNb+i).toDate();
                  // If different to monday
                  
                  if(date.getDay() != 1) {
                      // First day starts on monday
                      /*
                      if (i == 0) {
                          d = (date.getDay() == 0) ? 1 : (8 - date.getDay() % 7);
                          date.setDate(date.getDate()+d);
                      } else {
                          // and finishes on sunday
                          d = (date.getDay() == 0) ? 0 : (8 - date.getDay() % 7);
                          date.setDate(date.getDate()+d);
                      }
                      */

                      d = date.getDay() < 4 ? 1 - date.getDay() : date.getDay(); 
                      date.setDate(date.getDate()+d);

                  } else if (i == 1) {
                      if(date.getDay() == 1) {
                          m = (date.getMonth() == 12) ? 1 : date.getMonth() + 1;
                          date.setMonth(m);
                          date.setDate(getNbJoursMois(12 , year-1));
                          date.setFullYear(year-1);
                      }

                  } else {

                  }
                  dates.push(date);
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

    function dayDiff(d1, d2)
    {
        d1 = d1.getTime() / 86400000;
        d2 = d2.getTime() / 86400000;
        return new Number(d2 - d1).toFixed(0);
    }

  updateEvents();

    $('.prev-int-btn, .next-int-btn').on('click', function (e) {
        
      if($(this).hasClass('prev-int-btn')){
            if (ts == "y") {
                ci--;
            } else {
                if(cInt == 1){
                    ci = ts == 't' ? `q-4-${y-1}` : `w-52-${y-1}`;
                } else {
                    ci = `${ts == 't' ? 'q' : 'w'}-${cInt - 1}-${y}`;
                }
            }
      } else {
            if (ts == "y") {
                ci++;
            } else {
                if(ts == 't' && cInt == 4 || ts == 'tw' && cInt == 52){
                    ci = `${ts == 't' ? 'q' : 'w'}-1-${y+1}`;
                } else {
                    ci =`${ts == 't' ? 'q' : 'w'}-${cInt + 1}-${y}`;
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
    $('#addParticipant').removeAttr('id');
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

  $(document).on('mouseenter','.participant-btn',function(){
    $(this).find('.p-delete-overlay').show();
  }).on('mouseleave','.participant-btn',function(){
    $(this).find('.p-delete-overlay').hide();
  }),

  $(document).on('click','.stage-element',function(){
    if($(this).hasClass('s-multiple-events')){
      $(this).removeClass('s-selectable');
    }
    var $stage = $(this);
    var id = $stage.attr('data-id');
    $.post(sdurl,{id: id})
      .done(function(data){
        console.log(data);
        const options = { month: 'numeric', day: 'numeric' };
        $modal = $('#createActivity');
        $modal.attr({'data-id':id, 'data-sid':data.aid});
        if(data.ms){$modal.addClass('a-multiple-stages');}
        $modal.find('.btn-s-update').hide();
        $modal.find('.btn-s-modify').show();
        $modal.find('.s-name').empty().append(data.name);
        $modal.find('input[name*="name"]').closest('.input-field').hide();
        $modal.find('.s-dates').empty().append(`<i class="fa fa-calendar"></i><span class="sm-left s-date">${new Date(data.sdate.date).toLocaleDateString(lg+'-'+lg.toUpperCase(),options)}</span><span class="sm-right sm-left">-</span><span class="e-date">${new Date(data.edate.date).toLocaleDateString(lg+'-'+lg.toUpperCase(),options)}</span><div class="s-modify-dates m-left"><i class="btn-s-update btn-s-dates fa fa-pen dd-orange-text" style="display:none"></i></div>`);
        $modal.find('.dp-start').pickadate('picker').set('select',new Date(data.sdate.date));
        $modal.find('.dp-end').pickadate('picker').set('select',new Date(data.edate.date));
        $modal.find('.dp-start').closest('.row').hide();
        $modal.find('.s-dates-row').append('<div class="btn dates-validate"><i class="material-icons">check</i></div>');
        $modal.find('.s-dates-row').addClass('flex-center-sb').find('.col').removeClass('s6 m6');
        $modal.find('.events').show();
        $partHolder = $modal.find('ul.participants-list');
        $partHolder.find('.participants-list--item').remove();
        $partHolder.find('.btn-participant-add').attr('id','addParticipant');
        $modal.find('.nb-participants').empty().append(`(${data.participants ? data.participants.length : 0})`);
        $(data.participants).each(function(i,p){
          $partElmt = $($partHolder.data('prototype'));
          $partElmt.find('.participant-field-zone').remove();
          $partElmt.find('');
          $partElmt.find('.selected-participant-logo').attr('src', p.picture);
          $partElmt.find('.participant-btn').attr({
            'data-tooltip' : p.fullname + (p.synth ? ' (' + synthSuffix + ')' : ''),
            'data-pid' : p.id,
          }).addClass('existing deletable modal-trigger').attr('href','#deleteParticipant').append('<div class="p-delete-overlay flex-center" style="display:none;"><i class="fa fa-trash"></i></div>').show();
          $partElmt.find('.tooltipped').tooltip();
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
    $('.s-dates').hide();
    $('.s-dates-row').show();
  })

  $(document).on('click','.dates-validate',function(){
    const $this = $(this);
    const $modal = $this.closest('.modal');
    const $outputOptions = { month: 'numeric', day: 'numeric'};
    const $sentOptions = { month: 'numeric', day: 'numeric', year: 'numeric'};
    const loc = `${lg}-${lg.toUpperCase()}`;
    const sdStr = $modal.find('.dp-start').val().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    const edStr = $modal.find('.dp-end').val().normalize("NFD").replace(/[\u0300-\u036f]/g, "")
    const sd = new Date(sdStr).toLocaleString('en-EN',$sentOptions);
    const ed = new Date(edStr).toLocaleString('en-EN',$sentOptions);
    const $params = {id: $modal.attr('data-id'), sd: sd, ed: ed}  
    $.post(usdurl,$params)
      .done(function(data){
        $('.s-dates-row').hide();
        $('.s-date').empty().append(new Date(sdStr).toLocaleString(loc,$outputOptions));
        $('.e-date').empty().append(new Date(edStr).toLocaleString(loc,$outputOptions));
        $('.s-dates').show();
      })
  });

  $(document).on('click','.fa-external-link-alt',function(){
    var $this = $(this);
    if($this.closest('.modal').is('#createActivity')){
      $(`.event[data-id="${$this.data('id')}"]`).addClass('e-selectable').click();
      $('#multipleEvent').modal('close');
      $this.closest('.modal').modal('close');
    } else if($this.closest('.modal').is('#updateEvent')){
      $(`.stage-element[data-id="${$this.data('id')}"]`).click();
      $('#updateEvent').modal('close');
    }
  });



  $('.e-multiple-events').on('click',function(){

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

  $('[href="#updateEvent"]').on('click',function(){

    $modal = $('#updateEvent');
    $modal.find('.btn-e-update, .e-create').show();
    $modal.find('.btn-e-modify').hide();
    $modal.find('.ev-info').remove();
    $modal.find('.e-documents, .e-comments').empty();
    $modal.find('.documents-number').empty().append(`${$modal.find('.documents-number').data('none')}`);
    $modal.find('.comments-number').empty().append(`${$modal.find('.comments-number').data('none')}`);

    updateEvents();
    //$('.event-element-name').empty().append($(this).closest('.act-info').find('.act-info-name').text());
    $('.update-event-btn').attr('data-aid',$(this).attr('data-aid'));
    $('.update-event-btn').attr('data-sid',$(this).attr('data-sid'));
    $('.update-event-btn').attr('data-ms',$(this).attr('data-ms'));
    $('.update-event-btn').attr('data-eid',$(this).hasClass('btn-floating') ? 0 : $(this).attr('data-eid'));
  })

  $('.add-e-document, .add-e-comment').on('click',function(e){
    e.preventDefault();
    $holder = $(this).hasClass('add-e-comment') ? $('ul.e-comments') : $('ul.e-documents');
    proto = $holder.data('prototype-creation');
    proto = proto.replace(/__name__/g, $holder.children().length);
    $newProto = $(proto);
    if($(this).hasClass('add-e-document')){
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
      contentType: false,
      success: function(data){
        location.reload();
      }
    })

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
        if(data.documents){
          $modal.find('.documents-number').empty().append(data.documents.length);
        } else {
          $modal.find('.documents-number').empty().append(`(${$modal.find('.documents-number').data('none')})`);
        }
        $docHolder = $modal.find('.e-documents');
        $docHolder.empty();
        $(data.documents).each(function(i,d){

            $docElmt = $($docHolder.data('prototype-existing'));
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
            $comElmt = $($comHolder.data('prototype-existing'));
            $comElmt.attr('data-id',c.id);
            $comElmt.find('.e-com-author').append(c.author);
            $comElmt.find('.e-com-content').append(c.content);
            var updateDT =  c.modified ? c.modified : c.inserted;         
            $comElmt.find('.e-com-updated').append(`${updateDT} ${c.modified ? ' ('+modifiedMsg+')' : ''}`);  
            if(c.self){
              $modifyElmt = $(`<span class="e-com-modify tooltipped" data-tooltip="${msgComModify}" data-position="top"><i class="fa fa-pen"><i></span>`)
              $modifyElmt.tooltip();
              $comElmt.addClass('self').find('.e-com-right-elmts').append($modifyElmt);
            } else {
              $comElmt.append(`<div class="com-reply-zone flex-center-fe"><div class="dd-orange-text e-com-reply btn-e-update"><i class="fa fa-reply m-right"></i><span>Reply</span></div></div>`);
            }      
            $comHolder.append($comElmt);
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
      if(!$modal.find('.doc-name-validate:visible, input:visible, textarea:visible').length){
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
      const comExists = $comElmt.prev().hasClass('existing');
      if(comExists){
        $existingComElmt = $comElmt.prev();
      } else {
        $existingComElmt = $($comHolder.data('prototype-existing'));
        $modifyElmt = $(`<span class="e-com-modify tooltipped" data-tooltip="${msgComModify}" data-position="top"><i class="fa fa-pen"><i></span>`)
        $modifyElmt.tooltip();
        $existingComElmt.find('.e-com-right-elmts').append($modifyElmt);
        $comElmt.before($existingComElmt);
      }
      const content = $this.closest('.e-comment').find('textarea').val();

      $params = {id: $comElmt.data('id'), eid: $evtElmt.data('id'), content: content};
      if($comElmt.data('pid')){$params['pid'] = $comElmt.data('pid');}
      if(!$comElmt.data('id') && $comElmt.prev().hasClass('being-replied')){$params['cid'] = $comElmt.prev().data('id');}
      $.post(uccurl,$params)
        .done(function(data){
          $comElmt.remove();
          if(!comExists){
            $existingComElmt.attr('data-id',data.id).find('.e-com-author').append(data.author);
          } 
          if(!comExists || data.modified){
            $existingComElmt.find('.e-com-updated').empty().append(`${new Date(data.updated.date).toLocaleDateString("fr-FR",{ weekday: 'long', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric' })} ${data.modified ? ' ('+modifiedMsg+')' : ''}`);
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
      const $docFile = $docElmt.find('.dropify');
      var form = new FormData();
      form.append("file",$docFile[0].files[0]);
      if(!isExisting){
        form.append("eid",$evtElmt.data('id'));
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
            $hiddenDocElmt.find('.e-doc-size').empty().append(`${Math.round(d.size/1000)} Ko`);
            $hiddenDocElmt.attr('data-id',d.id).show();
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

  $(document).on('mouseover','.s-dates, ul.participants-list, .events-title, .s-title-zone',function(){
    if(!$(this).closest('.modal').find('input:visible').length){
      $(this).find('.btn-s-update').show();
    }
  }).on('mouseleave','.s-dates, ul.participants-list, .events-title, .s-title-zone',function(){  
    if(!$(this).find('input').length){
      $(this).find('.btn-s-update').hide();
    }
  })

  $(document).on('mouseover','.e-dates-header, .e-documents-header, .e-comments-header, .e-comment',function(){
    var $modal = $(this).closest('.modal');
    if(!$modal.find('input:visible,textarea:visible').length){
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

  $('.btn-add-event').on('click',function(){
    var $modal = $(this).closest('.modal');
    if ($(this).closest('.modal').hasClass('a-multiple-stages')){
      $('#multipleStage').modal('open');
    }
    $(`[href="#updateEvent"][data-sid="${$modal.data('id')}"]`).find('i').click();
    $modal.modal('close');
  })

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
    var $partHolder = $(this).closest('.participants-list');
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
      if(!$partHolder.find('.existing').length){
        $(e).find('.participant-field-zone input:not(.select-dropdown)').each(function(i,f){
          if($(f).attr('value')){
            p.push({el: $(f).attr('class'), id: $(f).attr('value')});
          }
        });
      } else {
        $(e).find('.participant-btn').each(function(i,f){
          p.push({id: $(f).data('pid')});
        });
      }
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



  $(document).on('change','[name*="participantSelector"]',function(){
      var $this = $(this);
      var $partElmt = $this.closest('.participants-list--item');
      var $selectedOpt = $this.find(":selected");

      if($selectedOpt.attr('data-s') == 1 || $selectedOpt.attr('data-type') == 'f'){
        $('#legalPerson').modal('open');
      }
      //var index = $('.participants-list--item').index($this.closest('.participants-list--item'));
      if($this.val() != ""){
          $partElmt.find('.tooltipped').attr('data-tooltip',`${$selectedOpt.text()} ${$selectedOpt.data('fname').length > 0 ? ' (' + $selectedOpt.data('fname') + ')' : ''}`).tooltip();
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
          if($selectedOpt.attr('data-s') != 1 && $selectedOpt.attr('data-type') != 'f' && !$('.participant-btn.existing').length){
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
      if(!$this.closest('#firstConnectionModal').length){
        $usrElmts.find('input[name="oid"]').val($selectedOpt.attr('data-oid'));
        $usrElmts.find('input[name="cid"]').val($selectedOpt.attr('data-cid'));
      }
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

    if($('.participants-list--item.edit').length){
      $this.parent().prepend('<span class="red-text sm-right part-error">Please validate (or remove) above participant before validating</span>');
      return false;
    }

    const params = {btn: $this.hasClass('create-activity') ? 'submit' : 'complexify'};
    var data = $this.closest('form').serialize() + '&' + $.param(params)

    $.post(acurl,data)
      .done(function(data){
        $('#createActivity').modal('close');
        //location.reload();
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
    var $this = $(this);
    pid = $this.data('pid');
    if(!pid){
      $(this).closest('.participants-list--item').remove();
    } else {
      urlToPieces = dpurl.split('/');
      urlToPieces[urlToPieces.length - 4] = $this.data('id');
      urlToPieces[urlToPieces.length - 1] = pid;
      url = urlToPieces.join('/');
      $.delete(url,null)
        .done(function(){
          $('#deleteParticipant').modal('close');
          $(`.participant-btn[data-pid=${pid}]`).closest('.participants-list--item').remove();
          $('.nb-participants').empty().append(`(${$('.participants-list--item').length})`);
        })

    }
  });

  $(document).on('click','.participant-btn.existing',function(){
    var $this = $(this);
    var $modal = $(this).closest('.modal');
    $('.participant-delete').attr({'data-id':$modal.data('id'),'data-pid':$this.data('pid')});
  })

  $(document).on('click','.participant-btn:not(.existing)',function(e){
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
    if(!$('.participant-btn.existing').length){


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

    } else {

      const $partInputs = $this.closest('.participant-field-zone').find('input');
      const $modal = $this.closest('.modal');
      $params = {};
      $partInputs.each(function(i,e){
        value = $(e).attr('value');
        c = $(e).attr('class')
        if(value){
          $params[c] = value;
        }
      })
      
      if (isEmpty($params)){
        var creationModal = $('#createParticipant');
        var partFN = $partElmt.find('input.participant-fullname').val();
        creationModal.find('.new-part-name').empty().append(partFN);
        var partNameToPieces = partFN.split(' ');
        //if(!$('input[name="firstname"]').val().length){
          $('input[name="firstname"]').val(partNameToPieces[0]).prev().addClass('active');
          $('input[name="lastname"]').val(partNameToPieces.slice(1).join(' ')).prev().addClass('active');
          //}
          $('#createParticipant').modal('open');

      } else {
          
        $params.id = $modal.attr('data-id');
        $.post(apurl,$params)
          .done(function(data){
            $partElmt.removeClass('edit');
            $partElmt.find('.participant-btn').addClass('existing deletable modal-trigger').attr({
              'href' : '#deleteParticipant',
              'data-pid' : data.pid
            }).append('<div class="p-delete-overlay flex-center" style="display:none;"><i class="fa fa-trash"></i></div>').show();
            $partElmt.find('.participant-field-zone').hide(); 
          })    
      }
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

  //if(getCookie('view_type') == 't'){

  //feedDashboardScreen();
    
  function feedDashboardScreen(){

    $actHeight = !$('.stages-holder:visible').length ? 75 : $('.stages-holder:visible').eq(0).height();
    $mainHeaderElmtsHeight = $('.sorting-type').height() + $('.timescale').height() + $('.tabs-t-view').height();
    $mainHeight = Math.min(1000, $('main').height());
    $actList = $();
    totalPotentialAct = Math.floor(($mainHeight - $mainHeaderElmtsHeight) / $actHeight);
    nbVisibleAct = $('.stages-holder:visible').length;
    nbAct = $('.stages-holder').length;
    if (nbVisibleAct < totalPotentialAct){
        for(k = nbVisibleAct; k < totalPotentialAct; k++){
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
          $actProto.find('.stage-element').attr('data-sd',sdate.getTime() / 1000);
          $actProto.find('.stage-element').attr('data-p', (edate.getTime() - sdate.getTime()) / 1000);
          $actProto.find('.s-day').empty().append(sdate.getDate());
          $actProto.find('.e-day').empty().append(edate.getDate());
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
          const dummyParams = {wa:1, td: totalPotentialAct - nbVisibleAct + 1};
          $.post(dcurl,dummyParams)
            .done(function(data){
  
              $toBeFeededActs.each(function(i,e){
                  $(e).find('.act-info-name').append(data.dummyElmts[i].actName);
                  $(e).find('.activity-client-name').attr('data-tooltip',data.dummyElmts[i].name).tooltip();
                  $(e).find('.client-logo').attr('src',data.dummyElmts[i].logo);
                
              })
            })
            .fail(function(data){
              console.log(data);
            })
        }
        
        var $actHolder = $('<div class="virtual-activities-holder"></div>');
  
        $actList.each(function(i,e){
          $actHolder.append($(e));
        })
        
        if(!nbAct){
          $actHolder.append(noActOverlay);
        }
  
        $appenedElmt = $('.activity-list:visible').length ? $('.activity-list:visible').last() : $('.dummy-activities-container');
        $appenedElmt.append($actHolder);
        
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

  $('[href="#deleteActivity"]').on('click',function(e){
    $('.remove-activity').data('id',$(this).attr('data-aid'));
  })

  $('.set-usr-org-btn').on('click',function(e){
      e.preventDefault();
      var $this = $(this);
      $.post(suourl, $this.closest('form').serialize())
        .done(function(data){
          $('#firstConnectionModal').modal('close');
          $('#beforeStarting img').attr({
            'src' : $('.client-logo').eq(1).attr('src'),
            'data-tooltip': $('.client-logo').eq(1).parent().attr('data-tooltip')
          }).tooltip();
          $('#beforeStarting').modal('open');

        })
        .fail(function(data){
          console.log(data);
        });
  });

  if(currentWfiId && fc){
    setTimeout(function(){
        $('#beforeStarting img').attr({
          'src' : $('.client-logo').eq(1).attr('src'),
          'data-tooltip': $('.client-logo').eq(1).parent().attr('data-tooltip')
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

});




