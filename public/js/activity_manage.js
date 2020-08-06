$(function () {
  $('a:has(.fa-plus)').on('click', function () {
    $('.process-name').empty().append($(this).closest('ul').find('header').text())
    $('.start-btn,.launch-btn,.modify-btn').removeData().data('pid', $(this).data('pid'));
  });

  var now = new Date();
  var annee   = now.getFullYear();
  $('.dmin').append(annee +'<div class="line"></div>');
  $('.dmax').append(annee + 1 + '<div class="line"></div>');
  var actContentStage = $('.activity-content-stage')[0];
  var widthElement = $(actContentStage).width();
  const $arrow = $('.arrow');
  var width = ($arrow.width() / 2);
  var now = new Date();
  var annee   = now.getFullYear();
  var nbDay = getDay();
  var jour = ndDayPerYears(annee);
  var echelle = widthElement / jour;
  var dayWidth = ( nbDay * echelle ) /* + ( widthElement / 2 )*/;
  //dayWidth = getPercentage( dayWidth , actContentStage );
  var currentDate = $('.currentDate');
  currentDate.css({'margin-left': Math.round(10000 * (currentDate.prev().width() + dayWidth) / $arrow.width()) / 100 + '%'});
  $('.activity-content-stage').css({
    'background' : 'repeating-linear-gradient(90deg, #0096882b, #0096886b '+ widthElement / 12 +'px, #ffffff '+ widthElement / 12 +'px, #ffffff '+ widthElement / 6 +'px)'
  });

  $.each($('.activity-component'), function () {
    var margLeft = (parseInt($(this).css('margin-left').split("px")[0]));
    var widthComp = (parseInt($(this).css('width').split("px")[0]));
    console.log(margLeft);
    console.log(widthComp);
    var widthCircle = (parseInt($(this).find('.completed-stages').css('width').split("px")[0]));
    widthCompEche = widthComp * echelle;

    if ( ( nbDay  )  > margLeft){

      var widthGrayPeriod = ( nbDay   -  margLeft)  ;
      var widthBluePeriod = widthComp - widthGrayPeriod;
      widthBluePeriod = widthBluePeriod * echelle;
      widthBluePeriod=getPercentage( widthBluePeriod , widthCompEche );
      $(this).children().css({'width':widthBluePeriod + "%" });

 }
    margLeft = margLeft * echelle;
    margLeft = getPercentage( margLeft  , widthElement );
    widthComp = getPercentage( widthCompEche , widthElement) ;

    $(this).css({'margin-left':margLeft + "%" });
    $(this).css({'width':widthComp + "%" });
    if ( ( nbDay  )  < margLeft){

      $(this).find('.blue-periode').css({'width':widthComp + "%" });

    }

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
    urlToPieces[urlToPieces.length - 1] = eid;
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
  var result=min / max;
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
