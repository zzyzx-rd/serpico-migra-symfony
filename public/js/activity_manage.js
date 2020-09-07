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
    'background' : 'repeating-linear-gradient(90deg, #0096882b, #0096886b '+ centralElWidth / 12 +'px, #ffffff '+ centralElWidth / 12 +'px, #ffffff '+ centralElWidth / 6 +'px)'
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
        'background' : 'repeating-linear-gradient(90deg, #0096882b, #0096886b '+ centralElWidth / 12 +'px, #ffffff '+ centralElWidth / 12 +'px, #ffffff '+ centralElWidth / 6 +'px)'
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
        'background' : ssd >= c ? '#5CD08F' : (ssd + sp > c ? 'linear-gradient(to right, transparent, transparent ' + Math.round(10000 * (c - ssd) / sp) / 100 + '%, #16AFB7 '+ Math.round(10000 * (c - ssd) / sp) / 100 +'%), repeating-linear-gradient(61deg, #16AFB7, #16AFB7 0.5rem, transparent 0.5px, transparent 1rem)' : 'gray'),
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




