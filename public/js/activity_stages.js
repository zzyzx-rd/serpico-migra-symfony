// /** @type {string[]} */
// const userPics = JSON.parse(document.getElementById('user-pics').innerHTML);
// const defaultUserPic = userPics[0];

// /**
//  * sets user picture in participants list
//  * @param {JQuery} $img img element
//  * @param {number} userId user id
//  */
// function setUserPic($img, userId) {
//   if (!($img instanceof jQuery)) throw new TypeError('must be a jquery instance');

//   const pictureUrl = userId && userPics[userId];
//   const exists = 'string' === typeof pictureUrl;

//   $img.prop('src', exists ? pictureUrl : defaultUserPic);
// }


const STAGE_LIST = 'ol.stages';
const STAGE_ITEM = 'li.stage-element';
const PARTICIPANTS_ITEM = 'li.participants-list--item';
const STAGE_MODAL = '.stage-modal';
const STAGE_NAME_INPUT = 'input.stage-name-input';
const STAGE_LABEL = '.stage-label';
const CRITERION_MODAL = '.criterion-modal';
const CRITERION_NAME_SELECT = 'select.criterion-name-select';
const CRITERION_LABEL = '.criterion-label';

const participationTypes = { '-1': 'p', '0': 't', '1': 'a' };
const $stageList = $(STAGE_LIST);
const $stageAddItem = $stageList.find('> li.stage-add');
const $addStageBtn = $stageAddItem.find('.stage-fresh-new-btn');
/**
 * @type {string}
 */
const proto = $(document).find('template.stages-list--item__proto')[0];

setTimeout(function (){
  if($('#errors').length > 0){
      $('#errors').modal('open');
      $('#errors').find('label+span').each(function(){
          $(this).text($(this).prev().text()+' :');
          $(this).prev().remove();
      })
      $('#errors .modal-content ul').css('display','inline-block').addClass('no-margin');
  }
},200)

$.delete = function(url, data, callback, type){
 
  if ( $.isFunction(data) ){
    type = type || callback,
        callback = data,
        data = {}
  }
 
  return $.ajax({
    url: url,
    type: 'DELETE',
    success: callback,
    data: data,
    contentType: type
  });
}

if(location.pathname.split('/').includes('iprocess')){
  $('.stage-container').css('background-color','#ffffd4');
  setTimeout(function(){
    $('li.stage.active .stage-item-button').css({'background-color':'#d8cb5b','color': '#fff'});
    $('.stage-item-button').css({'border-color':'#d8cb5b',});
  },100);
  setTimeout(function(){
    $('.criteria > header > .tabs .indicator').css({'background-color':'#bb8d04',});
  },300);
  $('.btn-floating').css({'background-color':'#d8cb5b'});

  $('head').append(`<style>
    li.stage-element::after{border-bottom-color:#f5f5b3 !important;}
    ol.stages::before{background-color: #bb8d04 !important;}
    .stage-item-button:hover{background-color: #d8cb5b !important}
  </style>`);
  //$('.li.stage-element::after').css('background-color','#ffffd4');
}

lg='fr';
let $stageCollectionHolder = $('.stages');

function sliders($btn = null) {

  if($btn != null){
    if($btn.closest('.criteria').length > 0){
      theSliders = $btn.closest('.criteria').find('.weight-criterion-slider').toArray();
    } else {
      theSliders = $btn.closest('.criteria').find('.weight-stage-slider').toArray();
    }
  } else {
      theSliders = Array.from(document.querySelectorAll('.weight-criterion-slider, .weight-stage-slider'));
  }

  /*$('.weight-criterion-slider').each(function (key, value) {

    value.noUiSlider.on('slide', function (values, handle) {

        value.nextElementSibling.innerHTML = Number(values[handle]) + ' %';
        value.nextElementSibling.nextElementSibling.value = values[handle];
        $(value).closest('.element-input').prev().find('.cw').empty().append(Number(values[handle]));
    })
  });
  */

  //const sliders = Array.from(document.querySelectorAll('.weight-criterion-slider, .weight-stage-slider'));
  const newSliders = theSliders.filter(e => !e.classList.contains('initialized'));

  for (const e of newSliders) {
    const weightElmt = e.parentElement;
    //Removing '%' text added by PercentType
    weightElmt.removeChild(weightElmt.lastChild);
    const input = weightElmt.querySelector('input');
    const label = weightElmt.querySelector('.weight-criterion-slider-range-value, .weight-stage-slider-range-value');
    const slideCallback = (values, handle) => {
      label.innerHTML = `${+values[handle]} %`;
      label.nextElementSibling.value = values[handle];
      if($(e).hasClass('weight-criterion-slider')){
        $(e).closest('.criteria-list--item').find('.c-weighting').empty().append(`(${Number(values[handle])} %)`);
      }
    }

    noUiSlider.create(e, {
      start: +input.value,
      step: 1,
      connect: [true, false],
      range: {
        min: 0,
        max: 100,
      },
    });

    slideCallback([+input.value], 0);
    e.noUiSlider.on('slide', slideCallback);
    e.classList.add('initialized');
  }
}

sliders();

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


// Updates calendar datepickers

function updateDatepickers(k, index){

  var nbStages =  $('.stage').length;

  //Three possible cases : loading(0), addition of one stage(1), update(2) or removal (-1)

  if(k==0) {

      //Set datepickers boundaries of grading dates for all stages
      var current = 0;
      var $terminalStageEndCal = $('.dp-end:eq(-1)');

      $('.stage').each(function(){

          var startCal = $(this).find('.dp-start');
          var endCal = $(this).find('.dp-end');
          var gStartCal = $(this).find('.dp-gstart');
          var gEndCal = $(this).find('.dp-gend');
          var startDateTS = (startCal.val() == "") ? Date.now() : new Date(startCal.val());
          var endDateTS = (endCal.val() == "") ? startDateTS : new Date(endCal.val());
          var startDate = new Date(startDateTS);
          var endDate = new Date(endDateTS);
          startCal.pickadate('picker').set('select',startDate);
          endCal.pickadate('picker').set('select',endDate).set('min',startDate);

      });

  } else if(k==1) {

      var $lastStageEndCal = $('.dp-end:eq('+(index)+')');
      var $addedStageStartCal = $('.dp-start:eq('+(index+1)+')');
      var $addedStageEndCal = $('.dp-end:eq('+(index+1)+')');
      var $lastStageWeight = $('.weight-input:eq('+index+')');
      var $addedStageWeight = $('.weight-input:eq('+(index+1)+')');
      var $addedStageGStartCal = $('.dp-gstart:eq('+(index+1)+')');
      var $addedStageGEndCal = $('.dp-gend:eq('+(index+1)+')');

      $addedStageStartCal.add($addedStageEndCal).add($addedStageGStartCal).add($addedStageGEndCal).pickadate();

      var addedStageStartdate = new Date($lastStageEndCal.pickadate('picker').get('select').pick);
      var addedStageEnddate = new Date($lastStageEndCal.pickadate('picker').get('select').pick);
      var addedStageGStartdate = new Date($lastStageEndCal.pickadate('picker').get('select').pick);
      var addedStageGEnddate = new Date($lastStageEndCal.pickadate('picker').get('select').pick);

      $addedStageStartCal.pickadate('picker').set('select',addedStageStartdate);
      $addedStageEndCal.pickadate('picker').set('select',addedStageEnddate).set('min',$addedStageStartCal.pickadate('picker').get('select'));
      $addedStageGStartCal.pickadate('picker').set('select',addedStageGStartdate);
      $addedStageGEndCal.pickadate('picker').set('select',addedStageGEnddate).set('min',$addedStageGStartCal.pickadate('picker').get('select'));

  } else if (k==-1){

      var $upstreamStageWeight = $('.weight:eq('+index+')');
      var $removedStageWeight = $('.weight:eq('+(index+1)+')');
      $upstreamStageWeight.val(parseFloat($upstreamStageWeight.val())+parseFloat($removedStageWeight.val()));
  }

}


var endDates = $('.dp-end');
endDates.data('previous', endDates.val());


//Set datepickers boundaries on loading
updateDatepickers(0,0);

$('.dp-start, .dp-end, .dp-gstart').on('change',function() {

  var selectedDate = $(this).pickadate('picker').get('select');
  var $GStartCal = $(this).closest('.stage').find('.dp-gstart');
  var $GEndCal = $(this).closest('.stage').find('.dp-gend');

  if ($(this).hasClass('dp-start') || $(this).hasClass('dp-gstart')) {

      var classPrefix = $(this).attr('class').split(' ')[0].slice(0, -5);

      //Shifting enddates values (for grading and stage)
      var $relatedEndCal = $(this).closest('.row').find('.' + classPrefix + 'end');
      if ($relatedEndCal.pickadate('picker').get('select').pick < selectedDate.pick) {
          $relatedEndCal.pickadate('picker').set('select', new Date(selectedDate.pick /*+ 14 * 24 * 60 * 60 * 1000*/));
      }
      $relatedEndCal.pickadate('picker').set('min', selectedDate);
      $GStartCal.pickadate('picker').set('min', new Date($('.dp-start').pickadate('picker').get('select').pick));


  } else if ($(this).hasClass('dp-end') && $(this).closest('.recurring').length == 0) {

      if ($GStartCal.pickadate('picker').get('select').pick < selectedDate.pick){
          $GStartCal.pickadate('picker').set('select', new Date(selectedDate.pick + 1 * 24 * 60 * 60 * 1000));
      }

      var GStartDate = $GStartCal.pickadate('picker').get('select');
      if ($GEndCal.pickadate('picker').get('select').pick < GStartDate.pick){
          $GEndCal.pickadate('picker').set('select', new Date(GStartDate.pick + 7 * 24 * 60 * 60 * 1000));
      }
      $GEndCal.pickadate('picker').set('min', GStartDate);

  }
});

$('.stage-add').hover(function(){
  $(this).find('.stage-item-name').css('visibility','hidden');
},function(){
  $(this).find('.stage-item-name').css('visibility','visible');
});

$('.duplicate-btn').on('click',function(){
  $btn = $(this);
  urlToPieces = dpsurl.split('/');
  urlToPieces[urlToPieces.length-1] = $btn.closest('.modal').find('#stageSelect').val();
  url = urlToPieces.join('/');
  $.post(url)
    .done(function(data){
        location.reload();
    })
    .fail(function(data){
        console.log(data);
    })
});


$(document).on(
  'click', `${STAGE_ITEM} > .stage-item-button`,
  function() {
    const $this = $(this);
    toggleStage($this);
  }
).on(
  'click', '.remove-stage-btn',
  function() {
    const $this = $(this);
    const $stageItem = $this.closest(STAGE_ITEM);
    $stageItem.find('.stage-modal').modal('close');
    $stageItem.remove();
  }
).on(
  'click', '.edit-user-btn',
  function() {
    const $this = $(this);
    const $participantItem = $this.closest(PARTICIPANTS_ITEM);
    $participantItem.addClass('edit-mode');
  }
).on(
    'click', '.btn-add-output',
    function() {

        const $this = $(this);
        const $section = $this.closest('.output');

        const $outputList = $section.find('.output-criteria');

        if ($criteriaList.children('.new').length) {
            return;
        }

        //const $proto =  $section.find('template.criteria-list--item__proto');
        /** @type {HTMLTemplateElement} */
        const outputs = $section.find('.output-list--item');

        const nbOutput = outputs.length;
        const proto = $section.find('template.output-list--item__proto')[0];
        const $output = $section.closest('.output');
        const outputList = $output.find('ul.output-list');
        const protoHtml = proto.innerHTML.trim();
        const newProtoHtml = protoHtml
            .replace(/__otpIndex__/g, outputList.children().length - 1)



        const $crtElmt = $(newProtoHtml);
        //$crtElmt.append(newProtoHtml);
        $crtElmt.find('.modal').modal();
        $crtElmt.find('.tooltipped').tooltip();

        // Setting default values, and putting label upside by adding them "active" class
        $crtElmt.find('input[name*="lowerbound"]').val(0).prev().addClass("active");
        $crtElmt.find('input[name*="upperbound"]').val(5).prev().addClass("active");
        $crtElmt.find('input[name*="step"]').val(0.5).prev().addClass("active");



        $criteriaList.children().last().before($crtElmt);






        slider.next().next().hide();


        handleCNSelectElems($crtElmt);

        $crtElmt.find('.modal-output').modal({
            complete: function(){


            }
        });
        $crtElmt.find('.modal-output').modal('open');


    }
).on(
  'click', '.btn-add-criterion',
  function() {

      const $this = $(this);
      const $section = $this.closest('.output-item');

      const $criteriaList = $section.find('.criteria-list');

    if ($criteriaList.children('.new').length) {
      return;
    }

    //const $proto =  $section.find('template.criteria-list--item__proto');
    /** @type {HTMLTemplateElement} */
    const $criteria = $section.find('.criteria-list--item');

    const nbCriteria = $criteria.length;
    const proto = $section.find('template.criteria-modal--item__proto')[0];
    const $output = $section.closest('.output');
    const $outputList = $output.find('ul.output-list');
    console.log(proto);
    const protoHtml = proto.innerHTML.trim();
    const newProtoHtml = protoHtml
        .replace(/__otpIndex__/g, $criteriaList.children().length - 1)
        .replace(/__crtIndex__/g, $criteriaList.children().length - 1)
        .replace(/__crtNb__/g, $criteriaList.children().length - 1)
        .replace(/__lowerbound__/g, 0)
        .replace(/__upperbound__/g, 5)
        .replace(/__step__/g, 0.5)
        //.replace(/__weight__/g, Math.round(100/(nbCriteria + 1)))
        .replace(/__stgNb__/g, $('.stage').index($section.closest('.stage')));

    const $crtElmt = $(newProtoHtml);
    $criteriaList.append($crtElmt);
      $('.modal').modal();
      $criteriaList.find('.modal').modal();
      $criteriaList.find('.tooltipped').tooltip();

    // Setting default values, and putting label upside by adding them "active" class
    $crtElmt.find('input[name*="lowerbound"]').val(0).prev().addClass("active");
    $crtElmt.find('input[name*="upperbound"]').val(5).prev().addClass("active");
    $crtElmt.find('input[name*="step"]').val(0.5).prev().addClass("active");
    // Select new criterion as being an evaluation one (by default)

    $crtElmt.find('[id*="_type"]').eq(1)[0].checked = true;

    /*
    crtCNameText = $crtElmt.find('select[name*="cName"] option:selected').text();
    crtName = crtCNameText.split(' ').slice(1).join(' ');
    crtIcon = crtCNameText.split(' ')[0];
    $crtElmt.find('.cname').attr('data-icon',crtIcon).append(crtName);
    */

    //$crtElmt.find('.bounds').append('[0-5]');
    //$crtElmt.find('.stepping').append(0.5);

    $criteriaList.children().last().before($crtElmt);

    /*$criteriaList.append(
      protoHtml
        .replace(/__name__/g, $criteriaList.children().length)
        .replace(/__crtNb__/g, $criteriaList.children().length)
        .replace(/__stgNb__/g, $('.stage').index($section.closest('.stage')))
    );*/

    var slider = $crtElmt.find('.weight-criterion-slider');
    var weight = $crtElmt.find('.weight');

    //Removing '%' text added by PercentType
    //weight[0].removeChild(weight[0].lastChild);

    //Get new criteria objects after insertion
    //var relatedCriteria = $crtElmt.closest('.stage').find('.criterion');
    $relatedCriteria = $criteriaList.find('.criteria-list--item');
    console.log($criteriaList);

    var creationVal = Math.round(100 / $relatedCriteria.length);
    var sumVal = 0;

    creationVal = Math.round(100 / $relatedCriteria.length);

    noUiSlider.create(slider[0], {
        start: creationVal,
        step: 1,
        connect: [true, false],
        range: {
            'min': 0,
            'max': 100,
        },
    });

    slider[0].nextElementSibling.innerHTML = creationVal + ' %';
    slider[0].nextElementSibling.nextElementSibling.value = creationVal;

    slider[0].noUiSlider.on('slide', function (values, handle) {

        slider[0].nextElementSibling.innerHTML = Number(values[handle]) + ' %';
        slider[0].nextElementSibling.nextElementSibling.value = values[handle];

    });

    slider.next().next().hide();
    if(nbCriteria == 0){
      slider.closest('.weight').hide();
    }

    handleCNSelectElems($crtElmt);

    $crtElmt.find('.criterion-modal').modal({
      complete: function(){

        let modC = $(this)[0].$el;
        let $crtElmt = modC.closest('.criteria-list--item');
        let btnV = $crtElmt.find('.c-validate');
        var slider = $crtElmt.find('.weight-criterion-slider');
        if(!btnV.hasClass('clicked')){

            if($crtElmt.hasClass('new')){

              $crtElmt.remove();
            } else {

              prevWeight = +slider[0].nextElementSibling.nextElementSibling.getAttribute('value');
              prevUB = $crtElmt.find('.upperbound').attr('value');
              prevLB = $crtElmt.find('.lowerbound').attr('value');
              prevType = $crtElmt.find('input[name*="type"][checked="checked"]').val();
              slider[0].nextElementSibling.innerHTML = prevWeight + ' %';
              slider[0].nextElementSibling.nextElementSibling.value = prevWeight;
              slider[0].noUiSlider.set(prevWeight);
              $crtElmt.find('input[name*="type"]').eq(prevType - 1).prop("checked",true);
              $crtElmt.find('.upperbound').val(prevUB);
              $crtElmt.find('.lowerbound').val(prevLB);
              $crtElmt.find('.c-weighting').empty().append(`(${prevWeight} %)`);
              $crtElmt.find('select[name*="cName"]').val($crtElmt.find('select[name*="cName"] option[selected="selected"]').val());
              console.log($crtElmt.find('select[name*="cName"]'));
            }
        } else {
            btnV.removeClass('clicked');
            const weightValue = +$crtElmt.find('.weight input').val();
            slider[0].nextElementSibling.nextElementSibling.setAttribute('value',slider[0].nextElementSibling.nextElementSibling.value);
            $crtElmt.find('.upperbound').attr('value',$crtElmt.find('.upperbound').val());
            $crtElmt.find('.lowerbound').attr('value',$crtElmt.find('.lowerbound').val());
            $crtElmt.find('input[name*="type"][checked="checked"]').removeAttr("checked");
            $crtElmt.find('input[name*="type"]:checked').attr('checked',"checked");
            $crtElmt.find('.cname').text($crtElmt.find('select[name*="cName"] option:selected').text().split(' ').slice(1).join(' '));
            $crtElmt.find('.cname').attr('data-icon',$crtElmt.find('select[name*="cName"] option:selected').attr('data-icon'));
            $crtElmt.find('.c-weighting').empty().append(`(${weightValue} %)`);
            $crtElmt.removeClass('new').removeAttr('style');
            handleCNSelectElems($crtElmt);

            var slider = $crtElmt.find('.weight-criterion-slider');
            var oldValue = Number(slider[0].noUiSlider.get());
            var sliders = $crtElmt.closest('.stage').find('.weight-criterion-slider').not(slider);
            if(sliders.length == 1){
              sliders.closest('.weight').show();
            }
            var sumVal = 0;
            var k = 0;
            var newValue = 0;

            $.each(sliders, function (key, value) {

                var nv = (key != sliders.length - 1) ?
                  Math.round(Number(Number($(this)[0].noUiSlider.get()) * (100 - weightValue) / 100)) :
                  100 - sumVal - weightValue;

                $(this)[0].nextElementSibling.innerHTML = nv + ' %';
                $(this)[0].nextElementSibling.nextElementSibling.value = nv;
                $(this)[0].noUiSlider.set(nv);
                sumVal += nv;
                k++;
                $(value).closest('.criteria-list--item').find('.c-weighting').empty().append(`(${nv} %)`);

            })
        }
        /*if(modC.find('input[type="checkbox"]').is(':checked') || modC.find('textarea').val() != ""){
            $('[href="#criterionTarget_'+s+'_'+c+'"]').addClass('lime darken-3').empty().append($('<ul class="flex-center no-margin">'+modalModifyMsg+'<i class="far fa-dot-circle" style="margin-left:10px"></i><i class="fas fa-comment-dots" style="margin-left:10px"></i></ul>'));
        } else {
            $('[href="#criterionTarget_'+s+'_'+c+'"]').removeClass('lime darken-3').empty().append($('<ul class="flex-center no-margin">'+modalSetMsg+'<i class="far fa-dot-circle" style="margin-left:10px"></i><i class="far fa-comment" style="margin-left:10px"></i></ul>'));
        }*/
      }
    })

    $crtElmt.find('.modal').modal('open');


  }
).on(
  'click', '[href="#deleteParticipant"]',
  function(e) {
    const $this = $(this);
    const $participantItem = $this.closest(PARTICIPANTS_ITEM);
    if(!$participantItem.data('id')){
      e.stopPropagation();
      $participantItem.remove();
    } else {
      const $stageItem = $this.closest(STAGE_ITEM);
      const $modalDeletionBtn = $('#deleteParticipant .remove-participant-btn');
      let dpurl = removeParticipantUrl
      .replace('__stgId__', $stageItem.data('id'))
      .replace('__elmtId__', $participantItem.data('id'));
      $modalDeletionBtn.data('id',$participantItem.data('id'));
      $modalDeletionBtn.on('click',async function(){
        await $.post(dpurl);
        $userVal = $participantItem.find('select[name*="user"]').val();
        $participantList = $participantItem.closest('.participants-list');
        $participantItem.remove();
        $participantList.find('.participants-list--item').each(function(i,e){
          $select = $(e).find('select[name*="user"]');
          $select.find(`option[value="${$userVal}"]`).prop('disabled',false);
          $select.material_select();
        });
      });
    }
  }
).on(
  'click', '.btn-add-participant-i, .btn-add-participant-e, .btn-add-participant-t',
  function() {
    const $this = $(this);
    const pType = $this.hasClass('btn-add-participant-i') ? 'i' : ($this.hasClass('btn-add-participant-e') ? 'e' : 't');
    const $section = $this.closest('section');
    const $participantsList = $section.children('ul.participants-list');

    if ($participantsList.children('.new').length) {
      return;
    }

    if($participantsList.find(`.participants-list--item[mode="${pType}"]`).length && $participantsList.find(`.participants-list--item[mode="${pType}"]`).find('select[name*="user"] option:not(:disabled)').length == $participantsList.children().length){
      $('#noRemainingParticipant').modal('open');
      return false;
    };

    /** @type {HTMLTemplateElement} */
    const proto = $section.children(`template.participants-list--item__proto-${pType}`)[0];
    protoHtml = proto.innerHTML.trim();
    protoHtml = protoHtml.replace(/__stgIndex__/g,$('.stage-element').not('.completed-stage-element').length - 1);

    switch(pType){
      case 'i' :
        protoHtml = protoHtml.replace(/__iPartIndex__/g, $participantsList.find(`.participants-list--item[mode="${pType}"]`).length);break;
      case 'e' :
        protoHtml = protoHtml.replace(/__ePartIndex__/g, $participantsList.find(`.participants-list--item[mode="${pType}"]`).length);break;
      case 't' :
        protoHtml = protoHtml.replace(/__tPartIndex__/g, $participantsList.find(`.participants-list--item[mode="${pType}"]`).length);break;
    }
    $newProtoHtml = $(protoHtml);
    $newProtoHtml.attr('mode',pType);
    $participantsList.append($newProtoHtml);
    handleParticipantsSelectElems($newProtoHtml);
  }
)/*.on(
  'click', '.btn-add-criterion',
  function () {
    const $this = $(this);
    const $section = $this.closest('section');
    const $criteriaList = $section.find('ul.criteria-list');

    if ($criteriaList.children('.new').length) {
      return;
    }

    const proto = $section.children('template.criteria-list--item__proto')[0];
    const protoHtml =
      proto.innerHTML.trim()
      .replace(/__(c|name)__/g, $criteriaList.children().length);
    const $proto = window.$(protoHtml);

    $criteriaList.append($proto);
    sliders($this);
    handleCNSelectElems();
    addSelectChangeListeners();



    $proto.find('.modal').modal({
      complete: function() {

      }
    });
    $proto.find('.criterion-modal').modal('open');

  }
)*/.on(
  'click', '.remove-participant-btn',
  function () {
    const $this = $(this);
    const $participantItem = $this.closest(PARTICIPANTS_ITEM);
    $participantItem.remove();
  }
).on(
  'click', '.edit-mode .edit-user-btn', // edit button, only when in edit mode (check icon is shown)
  async function (e) {
    const $this = $(this);
    const $participantItem = $this.closest(PARTICIPANTS_ITEM);
    const $stageItem = $this.closest(STAGE_ITEM);
    const trigger = e.target;
    /** @type {JQuery<HTMLSelectElement>} */
    const $elmtSelect = $participantItem.find('select[name*="user"],select[name*="team"]');
    const $elmtName = $participantItem.find('.elmt-name');
    /** @type {JQuery<HTMLInputElement>} */
    const $elmtIsLeader = $participantItem.find('.elmt-is-leader');
    const elmtIsLeader = $elmtIsLeader.length > 0 ? $elmtIsLeader[0].checked : null;
    /** @type {JQuery<HTMLSelectElement>} */
    const $userParticipantType = $participantItem.find('select.user-participant-type');
    const userParticipantType = $userParticipantType[0].value;

    if(!$('.add-owner-lose-setup,.change-owner-button').hasClass('clicked')){
      // Change ownership management
      $potentialDifferentLeader = $participantItem.closest('.participants-list').find('.badge-participation-l:visible');

      if(elmtIsLeader == true){

        if($.inArray(+usrRole,[2,3]) !== -1
        && !$potentialDifferentLeader.length && $elmtSelect.val() != usrId
        || $potentialDifferentLeader.length && $potentialDifferentLeader.closest('.participants-list--item').find('select[name*="user"]').val() == usrId){
          $('#setOwnershipLoseSetup').modal('open').data('id',$this.closest('.stage').data('id'));
          return false;
        } else if($potentialDifferentLeader.length){
          $('#changeOwner').find('.sName').empty().append($this.closest('.stage').find('.stage-name-field').text())
          $('#changeOwner').find('#oldLeader').empty().append($potentialDifferentLeader.closest('.participants-list--item').find('select[name*="user"] option:selected').text())
          $('#changeOwner').find('#newLeader').empty().append($elmtName.text());
          $('#changeOwner').modal('open').data('id',$this.closest('.stage').data('id'));
          return false;
        }

      }
    }

    const url = validateParticipantUrl
    .replace('__stgId__', $stageItem.data('id'))
    .replace('__elmtId__', $participantItem.data('id') || 0);

    $elmtName.html(
      $elmtSelect.children(':checked').first().html()
    );

    const params = {
      pEntity: $elmtSelect.filter((_i, e) => /user/gi.test(e.name)).length ? 'user' : 'team',
      pId: $elmtSelect[0].value,
      type: userParticipantType,
      precomment: null,
    };

    if (elmtIsLeader) {
      params.leader = true;
    }

    if(!$this.hasClass('warned') && $participantItem.closest('.participants-list').find('.badge-participation-validated').length){

      if(userParticipantType != 0 && ($participantItem.hasClass('new') || $userParticipantType.find('option[selected="selected"]').val() == 0)){

          $('#unvalidatingOutput').modal('open');
          $('.unvalidate-btn').addClass('p-validate').removeClass('c-validate');
          $('.unvalidate-btn').removeData()
            .data('pid',$participantItem.closest('.participants-list--item').data('id'))

          $(document).on('click','.p-validate',function(){
            $clickingBtn = $(this).data('pid') ?
              $(`.participants-list--item[data-id="${$(this).data('pid')}"]`).find('.edit-user-btn') :
              $('.participants-list--item.new').find('.edit-user-btn');
            $clickingBtn.addClass('warned').click();
          })
          return false;
      }
    }
    try {

      const { eid, pElement, canSetup } = await $.post(url, params);
      if($this.hasClass('warned')){
        $participantItem.closest('.participants-list').find('.badge-participation-validated').attr('style','display:none;');
        $this.removeClass('warned');
      }

      if($(trigger) != $this && $('.participants-list--item:not([data-id]).edit-mode .edit-user-btn:visible').length == 0){
        $('form[name="activity_element_form"]').submit();
        return false;
      }

      if(!canSetup){
          window.location = $('.back-btn').attr('href');
      }

      $participantItem
        .removeClass('edit-mode new')
        .attr('data-id', eid)
        .attr('is-leader', elmtIsLeader)
        .attr('participation-type', participationTypes[userParticipantType] || '')
        .find('img.user-picture').prop('src', `/lib/img/${pElement.picture}`);

      $partElmt = $participantItem;
      //$partElmt = $(this).closest('.participants-list--item');
      //if(!$partElmt.hasClass('edit-mode')){
        if($partElmt.find('.remove-participant-btn').length){
          $partElmt.find('.remove-participant-btn').removeClass('remove-participant-btn').addClass('modal-trigger').attr('href','#deleteParticipant');
        }
        $badges = $partElmt.find('.badges');
        $badges.children().attr('style','display:none;');
        switch($partElmt.find('select[name*="type"]').val()){
          case "1":
            $badges.find('.badge-participation-a').removeAttr('style');break;
          case "0":
            $badges.find('.badge-participation-t').removeAttr('style');break;
          case "-1":
            $badges.find('.badge-participation-p').removeAttr('style');break;
        }
        if($partElmt.find('select[name*="uniqueExtParticipations"]').length){
            $badges.find('.badge-participation-e').removeAttr('style');
        }
        if($partElmt.find('input[name*="leader"]').is(':checked')){
            $badges.find('.badge-participation-l').removeAttr('style');
        }
        handleParticipantsSelectElems($partElmt);


    } catch(errorException) {

      error = errorException.responseJSON;
      if(error.msg == 'duplicateWithTeam'){
        $('.duplicate-user-with-team').empty().append(`"${$elmtSelect.find('option:selected').text()}"`);
        $('.duplicate-team').empty().append(` (${error.name})`);
        $('.duplicate-with-team').show();
        $('.duplicate-with-user').hide();
      } else {
        $('.duplicate-team-with-user').empty().append(`"${$elmtSelect.find('option:selected').text()}"`);
        $('.duplicate-user').empty().append(` (${error.name})`);
        $('.duplicate-with-team').hide();
        $('.duplicate-with-user').show();
      }
      $('#duplicateParticipant').modal('open');
      $participantItem.remove();
      return false;
    }

  }
).on(
  'input', `${STAGE_MODAL} ${STAGE_NAME_INPUT}`,
  function () {
    const $this = $(this);
    const $modal = $this.closest(STAGE_MODAL);
    const $stageLabel = $modal.find(STAGE_LABEL);

    $stageLabel.html(this.value);
  }
);

/*
function addSelectChangeListeners() {
  window.$('select.criterion-name-select:not(.listened)')
    .addClass('listened')
    .on('change', () => handleCNSelectElems());
}
*/

/*$addStageBtn.on('click', () => {
  const $proto = $(proto);
  $proto
    .data('new', true);

  $stageAddItem.before($proto);
  toggleStage($proto);
});
*/

/**
 * @param {JQuery} $e
 */
function toggleStage($e) {
  const $stageItem =
    $e.closest(STAGE_ITEM)
    || ($e.is(STAGE_ITEM) && $e);

  if (!$stageItem) return;

  // remove class to all stage-item
  $stageList.children(STAGE_ITEM).removeClass('active');
  // display requested stage
  $stageItem.addClass('active');
}

toggleStage($(STAGE_ITEM).not('.completed-stages-element').first());


$('.activity-element-save, .activity-element-update').on('click',function(e){
  e.preventDefault();
  $('.element-input').show();
  wgtElmts = [];
  $('.element-input').each(function(){
      if($(this).find('.weight .weight-input').is(':disabled')){
          wgtHiddenElmt = $(this).find('.weight .weight-input');
          wgtElmts.push(wgtHiddenElmt);
          wgtHiddenElmt.prop('disabled',false);
      }
  });

  $('[class*="dp-"]').each(function(){
      $(this).val($(this).pickadate('picker').get('select', 'dd/mm/yyyy'));
  });

  $('input[name="clicked-btn"]').attr("value", $(this).hasClass('activity-element-save') ? 'save' : 'update');
  $('[name="activity_element_form"]').submit();
  $('.element-input').hide();
  $.each(wgtElmts, function(){
      wgtHiddenElmt.prop('disabled',true);
  })
})

$('.add-owner-lose-setup, .change-owner-button').on('click',function(){
    $this = $(this);
    $this.addClass('clicked');
    $('.edit-mode .edit-user-btn').click();
    $losingOwnershipPart = $(`.stage[data-id="${ $this.hasClass('change-owner-button') ? $('#changeOwner').data('id') : $('#setOwnershipLoseSetup').data('id') }"]`).find('.badge-participation-l:visible').closest('.participants-list--item');
    $losingOwnershipPart.find('input[type="checkbox"]').prop('checked',false);
    $losingOwnershipPart.find('.badge-participation-l:visible').hide();
    setTimeout(function(){$this.removeClass('clicked');},500)
})


/**
 * Disables options in criterion name selects as appropriate
 * @param {JQuery|HTMLElement} [target]
 */
function handleCNSelectElems (target) {
  const isCName = (_i, e) => /_criteria_\d+_cname/gi.test(e.id);

  const $crtElems = target
    ? target.closest('.criteria-list')
    : $('.criteria-list');
  const $selects = $crtElems.find('select').filter(isCName);

  $selects.find('option').prop('disabled', false);

  for (const crtElem of $crtElems) {
    const $crtElem = $(crtElem);
    const $options =  $crtElem.find('select').filter(isCName).find('option');
    const inUse = $options.filter(':selected').get().map(e => e.value);
    $optionsToDisable = $options.filter((_i, e) => inUse.includes(e.value) && !e.selected)
    $optionsToDisable.each((_i ,e) => $(e).prop('disabled', true));
    if(target && target.hasClass('new')){
      $targetPartSelect = target.find('select').filter(isCName);
      potentialDuplicate = inUse.reduce((acc, v, i, arr) => arr.indexOf(v) !== i && acc.indexOf(v) === -1 ? acc.concat(v) : acc, [])
      if(potentialDuplicate.length){
        $targetPartSelect.find(`option[value="${potentialDuplicate[0]}"]`).prop('disabled',true);
        console.log(`option[value="${potentialDuplicate[0]}"]`);
        $targetPartSelect.find('option').each(function(i,e){
          if(!inUse.includes($(e).val())){
            $targetPartSelect.val($(e).val());
            console.log($(e).val());

            return false;
          }
        })
      }
    }
  }


  initCNIcons();

  $('.select-dropdown li').addClass('flex-center');

  $('.select-dropdown li img').each(function(i,e){
    const $this = $(e);
    $this.css({
      height : 'auto',
      width : '20px',
      margin : '0',
      float : 'none',
      color : '#26a69a',
    });
  });

}

/**
 * Disables options in criterion name selects as appropriate
 * @param {JQuery|HTMLElement} [target]
 */
function handleParticipantsSelectElems (target) {
  const isParticipant = (_i, e) => /_\d+_(user|team)/gi.test(e.id);
  //const isExtParticipant = (_i, e) => /_\d+_ext/gi.test(e.id);

  const $partElems = target
    ? target.closest('.participants-list')
    : $('.participants-list');
  const $selects = $partElems.find('select').filter(isParticipant);
  const $extSelects = [$partElems.find('select[name*="ExtPart"][name*="user"]')];

  $selects.find('option').prop('disabled', false);

  for (const partElem of $partElems) {
    $partElem = $(partElem);
    const $options =  $partElem.find('select').filter(isParticipant).find('option');
    const inUse = $options.filter(':selected').get().map(e => e.value);

    $optionsToDisable = $options.filter((_i, e) => inUse.includes(e.value) && !e.selected)
    $optionsToDisable.each((_i ,e) => $(e).prop('disabled', true));
    if(target && target.hasClass('new')){
      $targetPartSelect = target.find('select').filter(isParticipant);
      potentialDuplicate = inUse.reduce((acc, v, i, arr) => arr.indexOf(v) !== i && acc.indexOf(v) === -1 ? acc.concat(v) : acc, [])
      if(potentialDuplicate.length){
        $targetPartSelect.find(`option[value="${potentialDuplicate[0]}"]`).prop('disabled',true);
        $targetPartSelect.find('option').each(function(i,e){
          if(!inUse.includes($(e).val())){
            $targetPartSelect.val($(e).val());
            return false;
          }
        })
      }
    }
  }
  for(const $extSelect of $extSelects){
    newIndex = -1;
    $extSelect.find('option.synth').each(function(i,e){
        $option = $(e);
        $options = $option.closest('select').find('option');
        index = $options.index($option);
        $(e).remove();
        newIndex == -1 ? $extSelect.find('option').eq(0).before($option) : $extSelect.find('option').eq(newIndex).after($option);
        newIndex = index;
    })

    $extSelect.find('option:not(.synth)').each(function(i,e){
        content = $(e).text();
        $(e).empty().append(`&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${content}`);
    })
}
  $partElems.find('select').material_select();

  for(const $extSelect of $extSelects){

    $extSelect.parent().find('.select-dropdown>li').each(function(i,e){
        if($(e).parent().next().find('option').eq(i).hasClass('synth')){
            $(e).find('span').css({'color':'#0d564f'});
        }
    })
  }
}

function initCNIcons() {
  const $stylizableSelects = window.$('select');
  $stylizableSelects.find('option').each(function (_i, e) {
    e.innerHTML = e.innerHTML.trim()
  });
  $stylizableSelects.material_select();

  const regExp = /~(.+)~/;

  $('.select-dropdown').each(function (_i, e) {
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
          `<span class="cn-icon" data-icon="${icon}"></span>`
        );
      });
    }

    $this.addClass('stylized');
    });

}

handleCNSelectElems();
handleParticipantsSelectElems();

$(document).on('click','.c-validate, .s-validate, .remove-stage, .remove-criterion', function(){
  let $this = $(this);
  $this.addClass('clicked');
})

$(document).on('click','.c-validate-prior-check',function(){
  $btn = $(this);
  $modal = $btn.closest('.modal');

  //We check if user changed critical data
  if(
    $modal.find('select[name*="cName"] option[selected="selected"]').val() != $modal.find('select[name*="cName"] option:selected').val()
    || $modal.find('input[name*="type"][checked="checked"]').val() != $modal.find('input[name*="type"]:checked').val()
  ){

    $('#unvalidatingOutput').modal('open');
    $('.unvalidate-btn').addClass('c-validate').removeClass('p-validate');
    $('.unvalidate-btn').removeData()
      .data('cid',$modal.closest('.criteria-list--item').data('id'))
      .data('sid',$modal.closest('.stage').data('id'))
      .data('modalId',$modal.attr('id'));

  } else {
    $btn.addClass('c-validate modal-close').removeClass('c-validate-prior-check').click();
    $btn.removeClass('c-validate modal-close').addClass('c-validate-prior-check');
  }

})

$(document).on('click','[href="#deleteStage"]', function () {
  $('.remove-stage').data('sid', $(this).data('sid'));
  $('#deleteStage').css('z-index',9999);
});

$(document).on('click','[href="#deleteCriterion"]', function () {

  $('.remove-criterion').data('cid', $(this).data('cid'));
  $('#deleteCriterion').css('z-index',9999);
});


$(document).on('click', '.remove-stage', function (e) {
    $('.modal').modal('close');
    $(this).addClass('clicked');
    var removableElmt = ($(this).data('sid')) ?
        $('[data-sid="' + $(this).data('sid') + '"]').closest('.stage') :
        $(this).closest('.stage');

    var slider = removableElmt.find('.weight-stage-slider');
    var oldValue = Number(slider[0].noUiSlider.get());
    var sliders = $('.stage').find('.weight-stage-slider').not(slider);
    var sumVal = 0;
    var newValue = 0;

    $.each(sliders, function (key, value) {

      var nv = (key != sliders.lengh - 1) ?
            Math.round(Number(Number($(this)[0].noUiSlider.get()) * (100 - newValue) / (100 - oldValue))) :
            100 - sumVal;

      $(this)[0].nextElementSibling.innerHTML = nv + ' %';
      $(this)[0].nextElementSibling.nextElementSibling.value = nv;
      $(this)[0].noUiSlider.set(nv);
      sumVal += nv;
      $(value).closest('.stage').find('.stage-item-name').find('.s-weighting').empty().append(`(${nv} %)`);
      $(value).closest('.stage').find('.stage-weight').find('.s-weighting').empty().append(`${nv} %`);

    })

    if($(this).data('sid')){
        urlToPieces = dsurl.split('/');
        urlToPieces[urlToPieces.length-1] = $(this).data('sid');
        tempUrl = urlToPieces.join('/');

        $.post(tempUrl,null)
            .done(function(data) {

            })
            .fail(function (data){
                console.log(data)
            })
    }

      if($('.stage').length == 2){
          $('.stage').find('.weight').hide();
          $('.stage').find('.weight').hide();
          //$('.stage').find('a[href="#deleteStage"]').remove();
      }
      removableElmt.remove();
      $('.stage').last().addClass('active');

});

$(document).on('click', '.remove-criterion',function(e) {

  $(this).addClass('clicked');
  var removableElmt = ($(this).data('cid')) ? $('[data-cid="' + $(this).data('cid') + '"]') : $(this);
  var crtElmt = removableElmt.closest('.criteria-list--item');
  var criteriaHolder = removableElmt.closest('.criteria-list');

  if (crtElmt.find('.weight-criterion-slider').length > 0) {

      var slider = crtElmt.find('.weight-criterion-slider');
      var oldValue = Number(slider[0].noUiSlider.get());
      var sliders = criteriaHolder.find('.weight-criterion-slider').not(slider);
      var sumVal = 0;
      var newValue = 0;

      $.each(sliders, function (key, value) {

          var nv = (key != sliders.lengh - 1) ?
            Math.round(Number(Number($(this)[0].noUiSlider.get()) * (100 - newValue) / (100 - oldValue))) :
            100 - sumVal;

          $(this)[0].nextElementSibling.innerHTML = nv + ' %';
          $(this)[0].nextElementSibling.nextElementSibling.value = nv;
          $(this)[0].noUiSlider.set(nv);
          sumVal += nv;
          $(value).closest('.criteria-list--item').find('.c-weighting').empty().append(`(${nv} %)`);

      })

  }

  if ($(this).data('cid')) {

      urlToPieces = dcurl.split('/');
      urlToPieces[urlToPieces.length - 1] = $(this).data('cid');
      url = urlToPieces.join('/');

      $.post(url, null)
          .done(function (data) {
              $('.modal').modal('close');
              $('.modal-overlay').remove();
              crtElmt.remove();
          })
          .fail(function (data) {
              console.log(data)
              $.each($('.red-text'),function(){
                  $(this).remove();
              });
              $.each(data, function(key, value){
                  if(key == "responseJSON"){
                  console.log(key);
                  console.log(value);
                  $.each(value, function(cle, valeur){
                      $.each($('input, select'),function(){
                          if($(this).is('[name]') && $(this).attr('name').indexOf(cle) != -1){
                              $(this).after('<div class="red-text"><strong>'+valeur+'</strong></div>');
                              return false;
                            }
                      });

                  });
                  }
              });
          });
  }

});

$('.stage-modal').modal({
  ready: function(){
    $(this)[0].$el.find('.weight').css('width',`${ 0.25 * $(this)[0].$el.find('.first-data-row').width() }`);
    },
  complete: function(){
    if(!$('.remove-stage').hasClass('clicked')){
      const $stgElmt = $(this)[0].$el.closest('.stage');
      const btnV = $stgElmt.find('.s-validate');
      const $slider = $stgElmt.find('.weight .weight-stage-slider');

      if(!btnV.hasClass('clicked')){
          if($stgElmt.hasClass('new')){

            $stgElmt.remove();
            $('.stage').last().addClass('active');
          } else {

            var startCal =  $stgElmt.find('.dp-start');
            var endCal =  $stgElmt.find('.dp-end');
            var gStartCal =  $stgElmt.find('.dp-gstart');
            var gEndCal =  $stgElmt.find('.dp-gend');
            const regex = /janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre|enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre|janeiro|fevereiro|março|abril|maio|junho|julho|agosto|setembro|outubro|novembro|dezembro/g ;
            var startDateTS = (startCal.val() == "") ? initStartDate : parseDdmmyyyy(startCal.attr('value').replace(regex,function(match){return replaceVars[match];}));
            var endDateTS = (endCal.val() == "") ? initEndDate : parseDdmmyyyy(endCal.attr('value').replace(regex,function(match){return replaceVars[match];}));
            var gStartDateTS = (gStartCal.val() == "") ? initGStartDate : parseDdmmyyyy(gStartCal.attr('value').replace(regex,function(match){return replaceVars[match];}));
            var gEndDateTS = (gEndCal.val() == "") ? initGEndDate : parseDdmmyyyy(gEndCal.attr('value').replace(regex,function(match){return replaceVars[match];}));
            var startDate = new Date(startDateTS);
            var endDate = new Date(endDateTS);
            var gStartDate = new Date(gStartDateTS);
            var gEndDate = new Date(gEndDateTS);

            startCal.pickadate('picker').set('select',startDate);
            endCal.pickadate('picker').set('select',endDate).set('min',startDate);
            gStartCal.pickadate('picker').set('select',gStartDate).set('min',startDate);
            gEndCal.pickadate('picker').set('select',gEndDate).set('min',gStartDate);

            prevWeight = +$slider[0].nextElementSibling.nextElementSibling.getAttribute('value');
            stgName = $stgElmt.find('input[name*="name"]').attr('value');
            stgMode = $stgElmt.find('input[name*="mode"][checked="checked"]').val();

            $slider[0].nextElementSibling.innerHTML = prevWeight + ' %';
            $slider[0].nextElementSibling.nextElementSibling.value = prevWeight;
            $slider[0].noUiSlider.set(prevWeight);
            $stgElmt.find(`input[name*="mode"][value = ${stgMode}]`).prop("checked",true);
            $stgElmt.find('input[name*="name"]').val(stgName);
            $stgElmt.find('.stage-item-name').find('.s-weighting').empty().append(`(${prevWeight} %)`);
            $stgElmt.find('.stage-weight').find('.s-weighting').empty().append(`${prevWeight} %`);
            $stgElmt.find('select[name*="visibility"]').val($stgElmt.find('select[name*="visibility"] option[selected="selected"]').val());

          }
      } else {
          const weightValue = +$stgElmt.find('.weight input').val();
          btnV.removeClass('clicked');
          $stgElmt.find('input[name*="name"]').attr('value',$stgElmt.find('input[name*="name"]').val());
          $stgElmt.find('input[name*="mode"][checked="checked"]').removeAttr("checked");
          $stgElmt.find('input[name*="mode"]:checked').attr('checked',"checked");
          $stgElmt.find('.stage-name-field').text($stgElmt.find('input[name*="name"]').val());
          $stgElmt.find('.stage-item-name').find('.s-weighting').empty().append(`(${weightValue} %)`);
          $stgElmt.find('.stage-weight').find('.s-weighting').empty().append(`${weightValue} %`);
          $stgElmt.removeClass('new').removeAttr('style');
          handleCNSelectElems($stgElmt);
          const $sliders = $('.stage .weight').find('.weight-stage-slider').not($slider);
          if($sliders.length == 1){
            $sliders.closest('.weight').show();
          }

          var oldValue = $stgElmt.find('.weight input').attr('value');
          var sumVal = 0;
          var newValue = weightValue;

          $.each($sliders, function (key, value) {

              var nv = (key != sliders.lengh - 1) ?
                Math.round(Number(Number($(this)[0].noUiSlider.get()) * (100 - newValue) / (100 - oldValue))) :
                100 - sumVal;

              $(this)[0].nextElementSibling.innerHTML = nv + ' %';
              $(this)[0].nextElementSibling.nextElementSibling.value = nv;
              $(this)[0].noUiSlider.set(nv);
              sumVal += nv;
              $(value).closest('.stage').find('.stage-item-name').find('.s-weighting').empty().append(`(${nv} %)`);
              $(value).closest('.stage').find('.stage-weight').find('.s-weighting').empty().append(`${nv} %`);

          })
          $stgElmt.find('.weight input').attr('value',newValue);
      }
    } else {
      $('.remove-stage').removeClass('clicked');
    }
  }
})

$('.criterion-modal').modal({
  complete: function(){
      alert('yes');
    let modC = $(this)[0].$el;
    let $crtElmt = modC.closest('.criteria-list--item');
    let btnV = $crtElmt.find('.c-validate');
    var slider = $crtElmt.find('.weight-criterion-slider');
    prevWeight = +slider[0].nextElementSibling.nextElementSibling.getAttribute('value');

    if(!btnV.hasClass('clicked')){

      prevUB = $crtElmt.find('.upperbound').attr('value');
      prevLB = $crtElmt.find('.lowerbound').attr('value');
      prevType = $crtElmt.find('input[name*="type"][checked="checked"]').val();
      slider[0].nextElementSibling.innerHTML = prevWeight + ' %';
      slider[0].nextElementSibling.nextElementSibling.value = prevWeight;
      slider[0].noUiSlider.set(prevWeight);
      $crtElmt.find('input[name*="type"]').eq(prevType - 1).prop("checked",true);
      $crtElmt.find('.upperbound').val(prevUB);
      $crtElmt.find('.lowerbound').val(prevLB);
      $crtElmt.find('.c-weighting').empty().append(`(${prevWeight} %)`);
      $crtElmt.find('select[name*="cName"]').val($crtElmt.find('select[name*="cName"] option[selected="selected"]').val());

    } else {

        btnV.removeClass('clicked');
        var newValue = +$crtElmt.find('.weight input').val();
        slider[0].nextElementSibling.nextElementSibling.setAttribute('value',slider[0].nextElementSibling.nextElementSibling.value);
        $crtElmt.find('.upperbound').attr('value',$crtElmt.find('.upperbound').val());
        $crtElmt.find('.lowerbound').attr('value',$crtElmt.find('.lowerbound').val());
        $crtElmt.find('input[name*="type"][checked="checked"]').removeAttr("checked");
        $crtElmt.find('input[name*="type"]:checked').attr('checked',"checked");
        $crtElmt.find('.cname').text($crtElmt.find('select[name*="cName"] option:selected').text().split(' ').slice(1).join(' '));
        $crtElmt.find('.cname').attr('data-icon',$crtElmt.find('select[name*="cName"] option:selected').attr('data-icon'));
        console.log($crtElmt.find('select[name*="cName"] option:selected').text().split(' ').slice(1).join(' '));
        console.log($crtElmt.find('select[name*="cName"] option:selected').attr('data-icon'));
        $crtElmt.find('.c-weighting').empty().append(`(${newValue} %)`);


        var slider = $crtElmt.find('.weight-criterion-slider');
        var oldValue = prevWeight;
        var sliders = $crtElmt.closest('.stage').find('.weight-criterion-slider').not(slider);
        var sumVal = 0;

        $.each(sliders, function (key, value) {

            var nv = (key != sliders.lengh - 1) ?
              Math.round(Number(Number($(this)[0].noUiSlider.get()) * (100 - newValue) / (100 - oldValue))) :
              100 - sumVal;

            $(this)[0].nextElementSibling.innerHTML = nv + ' %';
            $(this)[0].nextElementSibling.nextElementSibling.value = nv;
            $(this)[0].nextElementSibling.nextElementSibling.setAttribute('value',nv);
            $(this)[0].noUiSlider.set(nv);
            sumVal += nv;
            $(value).closest('.criteria-list--item').find('.c-weighting').empty().append(`(${nv} %)`);

        })
    }
    handleCNSelectElems($crtElmt);
    /*if(modC.find('input[type="checkbox"]').is(':checked') || modC.find('textarea').val() != ""){
        $('[href="#criterionTarget_'+s+'_'+c+'"]').addClass('lime darken-3').empty().append($('<ul class="flex-center no-margin">'+modalModifyMsg+'<i class="far fa-dot-circle" style="margin-left:10px"></i><i class="fas fa-comment-dots" style="margin-left:10px"></i></ul>'));
    } else {
        $('[href="#criterionTarget_'+s+'_'+c+'"]').removeClass('lime darken-3').empty().append($('<ul class="flex-center no-margin">'+modalSetMsg+'<i class="far fa-dot-circle" style="margin-left:10px"></i><i class="far fa-comment" style="margin-left:10px"></i></ul>'));
    }*/
  }
});

$(document).on('change','select[name*="cName"]',function(){
    $select = $(this);
    $materalizeSelect = $(this).closest('.criterion-name').find('input');
    const regExp = /~(.+)~/;
    const match = $select.find('option:selected').text().match(regExp);
    let icon = String.fromCodePoint && match && match[1] ? String.fromCodePoint('0x' + match[1]) : '';

    if (!match) return;
    $materalizeSelect.val(icon + $materalizeSelect.val());
});

$(document).on('change', '.gradetype', function () {
    var k = 0;
    var crtElmt = $(this).closest('.criterion');
    var sliders = crtElmt.closest('.stage').find('.weight-criterion-slider');
    var crtIndex = $(this).closest('.stage').find('.criterion').index(crtElmt);

    var oldValue = Number(crtElmt.find('.weight-criterion-slider')[0].noUiSlider.get());

    if (crtElmt.find('.gradetype :checked').val() != 1) {
        crtElmt.find('.scale :text:not(.weight-input)').attr('disabled', 'disabled');
    } else {
        crtElmt.find('.scale :text:not(.weight-input)').removeAttr('disabled');
    }

    // Hide or display weight input, depending on number of non-pure-comments elements
    var k = 0;
    $(this).closest('.stage').find('.criterion input[type="radio"]:checked').each(function(key,selectedRadioBtn){
        if (selectedRadioBtn.value != 2){
            k++;
        }
        if(k > 1){
            return false;
        }
    })

    if (k > 1 && crtElmt.find('input[type="radio"]:checked').val() != 2) {
        crtElmt.find('.weight').show();
    } else {
        crtElmt.find('.weight').hide();
    }


    if (crtElmt.find('input[type="radio"]').eq(1).is(':checked')) {


        var slider = crtElmt.find('.weight-criterion-slider');

        var selectedSliderIndex = sliders.index(slider);

        crtElmt.find('.weight-criterion-slider')[0].nextElementSibling.innerHTML = '0 %';
        crtElmt.find('.weight-criterion-slider')[0].nextElementSibling.nextElementSibling.value = 0;
        crtElmt.find('.weight-criterion-slider')[0].noUiSlider.set(0);

        var sumVal = 0;
        var k = 0;
        var newValue = 0;

        $.each(sliders, function (key, value) {
            if (key != selectedSliderIndex) {
                //$(this).off();
                var nv = Math.round(Number(Number($(this)[0].noUiSlider.get()) * (100 - newValue) / (100 - oldValue)));

                if (k == sliders.length - 2 && sumVal + nv + newValue != 100) {
                    nv = 100 - sumVal - newValue;
                }

                $(this)[0].nextElementSibling.innerHTML = nv + ' %';
                $(this)[0].nextElementSibling.nextElementSibling.value = nv;
                $(this)[0].noUiSlider.set(nv);
                sumVal += nv;
                k++;
            }
        })

        $.each($(this).closest('.stage').find('.criterion').not(crtElmt), function () {
            var newValue = Math.round(Number($(this).find('.weight-criterion-slider').eq(0)[0].noUiSlider.get()));

            // Hide weight when only one criterion has 100 percent
            if (newValue == 100) {
                $(this).find('.scale .input-field').eq(-1).hide();
                $(this).find('.scale .input-field').not(':last').addClass('m4').removeClass('m3');
            }
        });

        // Hide scale

        crtElmt.find('.scale .input-field').hide();
        crtElmt.find('.force-comments').hide();


    } else {

        var oldValue = Math.round(crtElmt.find('.weight-criterion-slider')[0].noUiSlider.get());

        if (oldValue == 0) {

            //Get new criteria objects after insertion
            var relatedCriteria = crtElmt.closest('.stage').find('.criterion');

            var creationVal = Math.round(100 / relatedCriteria.length);

            var sliders = relatedCriteria.find('.weight-criterion-slider');
            var sumVal = 0;

            $.each(sliders, function (key, value) {
                if (key != crtIndex) {
                    var nv = Math.round(Number($(this)[0].noUiSlider.get()) * (relatedCriteria.length - 1) / relatedCriteria.length);
                    $(this)[0].noUiSlider.set(nv);
                    $(this)[0].nextElementSibling.innerHTML = nv + ' %';
                    $(this)[0].nextElementSibling.nextElementSibling.value = nv;
                    sumVal += nv;
                }
            })

            if (Math.round(100 / relatedCriteria.length) != 100 / relatedCriteria.length) {
                creationVal = 100 - sumVal;
            }

            crtElmt.find('.weight-criterion-slider')[0].nextElementSibling.innerHTML = creationVal + ' %';
            crtElmt.find('.weight-criterion-slider')[0].nextElementSibling.nextElementSibling.value = creationVal;
            crtElmt.find('.weight-criterion-slider')[0].noUiSlider.set(creationVal);

        }

    }

    //var nbCrt = $(this).closest('.stage').find('.criterion').length;
    $.each($(this).closest('.stage').find('.criterion'), function () {
        if (!$(this).find('input[type="radio"]').eq(1).is(':checked')) {
            k++;
        }
    });


    if ($(this).find('input[type="radio"]').eq(0).is(':checked')) {
        crtElmt.find('.force-choice label').text(forceCommentMsg_0);
        crtElmt.find('.input-field').not('.criterion-name, .weight').show();
        crtElmt.find('.weight').removeAttr('style');
        crtElmt.find('.force-sign, .force-value').show();

    } else if ($(this).find('input[type="radio"]').eq(2).is(':checked')) {

        crtElmt.find('.force-choice label').text(forceCommentMsg_2);
        crtElmt.find('.input-field').not('.criterion-name, .weight').hide();
        crtElmt.find('.force-sign, .force-value').hide();

    } else {
        crtElmt.find('.force-choice label').text(forceCommentMsg_1);
        crtElmt.find('.weight').removeAttr('style').hide();
        crtElmt.find('.force-comments').show();
        crtElmt.find('.force-sign, .force-value').hide();
    }

    if(crtElmt.find('input[type="radio"]:checked').val() != 1){
        crtElmt.find('.scale .input-field').hide();
    }

    if (!$(this).find('input[type="radio"]').eq(2).is(':checked') && crtElmt.find('.force-comments .col').eq(0).hasClass('m12')) {
        crtElmt.find('.force-comments .col').eq(0).removeClass('m12').addClass('m5')
    }

    if (crtElmt.find('input[type="radio"]').eq(2).is(':checked')) {
        crtElmt.find('input[name*="lowerbound"]').val(0);
        crtElmt.find('input[name*="upperbound"]').val(1);
        crtElmt.find('input[name*="step"]').val(1);
    }

});

$(document).on('click','.s-validate', function(e) {

  e.preventDefault();
  $btn = $(this);
  $curRow = $btn.closest('.stage-modal');
  if($curRow.find('.remove-stage-btn').length){
    $curRow.find('.remove-stage-btn').removeClass('remove-stage-btn').addClass('modal-trigger').attr('href','#deleteStage');
  }
  $curRow.find('.red-text').remove();
  sid = $curRow.closest('.stage').attr('data-id');

  inputName = $curRow.find('input[name*="name"]').val();
  weightVal = $curRow.find('input[name*="activeWeight"]').val();
  isDefiniteDates = $curRow.find('[name*="definiteDates"]').is(':checked');
  startdate = $curRow.find('[name*="startdate"]').val();
  enddate = $curRow.find('[name*="enddate"]').val();
  dPeriod = $curRow.find('[name*="dPeriod"]').val();
  dFrequency = $curRow.find('select[name*="dFrequency"] option:selected').val();
  dOrigin = $curRow.find('select[name*="dOrigin"] option:selected').val();
  fPeriod = $curRow.find('[name*="fPeriod"]').val();
  fFrequency = $curRow.find('select[name*="fFrequency"] option:selected').val();
  fOrigin = $curRow.find('select[name*="fOrigin"] option:selected').val();
  visibility = $curRow.find('select[name*="visibility"] option:selected').val();
  progress = $curRow.find('select[name*="progress"] option:selected').val();
  mode = $curRow.find('[name*="mode"]:checked').val();

  const $form = $('.s-form form');
  $form.find('input, select').removeAttr('disabled');

  $form.find('[name*="activeWeight"]').val(weightVal);
  $form.find('[name*="name"]').val(inputName);
  $form.find('[name*="definiteDates"]').prop('checked', isDefiniteDates);
  $form.find('[name*="[startdate]"]').val(startdate);
  $form.find('[name*="[enddate]"]').val(enddate);
  $form.find('[name*="dPeriod"]').val(dPeriod);
  $form.find('select[name*="dFrequency"]').val(dFrequency);
  $form.find('select[name*="dOrigin"]').val(dOrigin);
  $form.find('[name*="fPeriod"]').val(fPeriod);
  $form.find('select[name*="fFrequency"]').val(fFrequency);
  $form.find('select[name*="fOrigin"]').val(fOrigin);
  $form.find('select[name*="visibility"]').val(visibility);
  $form.find('select[name*="progress"]').val(progress);
  $form.find('[name*="mode"]').eq(mode).prop('checked',true);

  const urlToPieces = vsurl.split('/');
  urlToPieces[urlToPieces.length - 1] = sid;
  const url = urlToPieces.join('/');
  var tmp = $form.serialize().split('&');

  j = $('.dp-start').index($curRow.find('.dp-start'));
  for (i = 0; i < tmp.length; i++) {
      if(tmp[i].indexOf('startdate') != -1 && tmp[i].indexOf('gstartdate') == -1){
          tmp[i] = tmp[i].split('=');
          tmp[i+1] = tmp[i+1].split('=');
          tmp[i+2] = tmp[i+2].split('=');
          tmp[i+3] = tmp[i+3].split('=');
          startdateDDMMYYYY = $($("#" + $('.dp-start')[j].id)).pickadate('picker').get('select', 'dd/mm/yyyy');
          enddateDDMMYYYY = $($("#" + $('.dp-end')[j].id)).pickadate('picker').get('select', 'dd/mm/yyyy');
          tmp[i][1] = startdateDDMMYYYY;
          tmp[i+1][1] = enddateDDMMYYYY;
          tmp[i] = tmp[i].join('=');
          tmp[i+1] = tmp[i+1].join('=');
          tmp[i+2] = tmp[i+2].join('=');
          tmp[i+3] = tmp[i+3].join('=');
          $increment = true;
      } else {
          $increment = false;
      }
      if ($increment) {
          j++;
      }
  }

  mSerializedForm = tmp.join('&');
  //$li = $(this).closest('li.stage').find('a.insert-survey-btn')
  $.post(url, mSerializedForm)
    .done(function(data) {
      var startCal =  $curRow.find('.dp-start');
      var endCal =  $curRow.find('.dp-end');
      var gStartCal =  $curRow.find('.dp-gstart');
      var gEndCal =  $curRow.find('.dp-gend');
      startCal.attr('value',startdateDDMMYYYY);
      endCal.attr('value',enddateDDMMYYYY);
      startdateDDMM = startdateDDMMYYYY.split('/').slice(0,2).join('/');
      enddateDDMM = enddateDDMMYYYY.split('/').slice(0,2).join('/');
      stageDatesText = startdateDDMM == enddateDDMM ? startdateDDMM : startdateDDMM + ' - ' + enddateDDMM;
      $curRow.closest('.stage').find('.stage-dates').contents().last().replaceWith(stageDatesText);
      $curRow.closest('.stage').find('.grading-dates').contents().last().replaceWith(gradingDatesText);
      //const href=$li.attr('href').replace('0', data['sid']);
      //$li.attr('href',href);
      $removeBtn = $curRow.find('[href="#deleteStage"]');
      $removeBtn.attr('data-sid',data.sid);
      $curRow.closest('.stage').attr('data-id',data.sid);
      $curRow.modal('close');
    })
    .fail(function(data) {
      Object.keys(data.responseJSON).forEach(function(key){
          $curRow.find(`input[name*="${key}"]`).after(`<strong class="red-text">${data.responseJSON[key]}</strong>`);
      })
    });

})

$(document).on('click','.c-validate', function(e) {

  e.preventDefault();
  $btn = $(this);
  $deleteBtn = $btn.closest('.modal').find('[href="#deleteCriterion"]');
  $curRow = $btn.hasClass('unvalidate-btn') ? $(`.criterion-modal[id="${$(this).data('modalId')}"]`) : $(this).closest('.criterion-modal');
  $crtElmt = $curRow.closest('.criteria-list--item');
  //$crtElmt = $(this).closest('.criterion');
  $curRow.find('.red-text').remove();
  oid = $(this).closest(".output-item").data('oid');
  console.log($btn.attr('data-cid'));
  cid = $btn.attr('data-cid');

  crtVal = $curRow.find('select[name*="cName"] option:selected').val();
  typeVal = $curRow.find('[name*="type"]:checked').val();
  isRequiredComment = $curRow.find('[name*="forceCommentCompare"]:checked').val();
  commentSign = $curRow.find('select[name*="forceCommentSign"] option:selected').val();
  commentValue = $curRow.find('[name*="forceCommentValue"]').val();
  lowerbound = $curRow.find('[name*="lowerbound"]').val();
  upperbound = $curRow.find('[name*="upperbound"]').val();
  step = $curRow.find('[name*="step"]').val();
  weight = $curRow.find('.weight input').val();
  console.log(weight);


  const $form = $('.c-form form');

  const urlToPieces = vcurl.split('/');
  urlToPieces[urlToPieces.length - 4] = oid;
  urlToPieces[urlToPieces.length - 1] = cid;
  const url = urlToPieces.join('/');
    alert(cid);
  $form.find('[name*="cName"]').val(crtVal);
  $form.find('[name*="type"]').eq(typeVal - 1).prop('checked',true);
  $form.find('[name*="forceCommentCompare"]').prop('checked', isRequiredComment);
  $form.find('[name*="forceCommentSign"]').val(commentSign);
  $form.find('[name*="forceCommentValue"]').val(commentValue);
  $form.find('[name*="lowerbound"]').val(lowerbound);
  $form.find('[name*="upperbound"]').val(upperbound);
  $form.find('[name*="step"]').val(step);
  $form.find('[name*="weight"]').val(weight);


  $.post(url, $form.serialize())
  .done(function(data) {
      console.log($btn);
      $btn.attr('data-cid',data.cid);

      $crtElmt .attr('data-cid',data.cid);
      $deleteBtn.attr('data-cid',data.cid);
      handleCNSelectElems($crtElmt);
      $curRow.modal('close');
      console.log($curRow);
      if(typeVal == 1){
        $crtElmt.find('.bounds').show().empty().append(`[${ Math.round(parseFloat(lowerbound.replace(",",".")))}-${ Math.round(parseFloat(upperbound.replace(",","."))) }]`);
        $crtElmt.find('.stepping').show().empty().append(Math.round(parseFloat(step.replace(",",".")) * 100) / 100);
      } else {
        $crtElmt.find('.stepping').hide();
        $crtElmt.find('.bounds').hide();
      }
      if(isRequiredComment){
        $crtElmt.find('.comment-container').show();
        $crtElmt.find('.comment-value').empty().append((commentSign == 'smaller' ? '<' : '≤') + Math.round(parseFloat(commentValue.replace(",","."))));
      } else {
        $crtElmt.find('.comment-container').hide();
      }
  })
  .fail(function(data) {
      Object.keys(data.responseJSON).forEach(function(key){
          $btn.closest('.modal').find(`input[name*="${key}"]`).after(`<strong class="red-text">${data.responseJSON[key]}</strong>`);
      })
  });


});
$(document).on('click','.o-validate', function(e) {

    e.preventDefault();
    $btn = $(this);
    $deleteBtn = $btn.closest('.modal').find('[href="#deleteOutput"]');
    var $crtElmt = $(this).closest('.modal');

    //$crtElmt = $(this).closest('.criterion');
    sid =  $(this).closest('.stage').data('id');
    oid = $(this).data('oid');
    //crtVal = $crtElmt.find('select option:selected').val();
    typeVal = $crtElmt.find('input:checked').val();
    startdate = $crtElmt.find('.dp-start').val();
    enddate = $crtElmt.find('.dp-end').val();
    startdate = new Date(startdate);

    enddate = new Date(enddate);


    const $this = $(this);
    const $section = $this.closest('.output');
    const $outputList = $section.find('ul.output-list');
    const $output = $section.find('.output-list--item');
    const nbCriteria = $output.length;
    const proto = $section.find('template.output-list--item__proto')[0];
    const protoHtml = proto.innerHTML.trim();

    const newProtoHtml = protoHtml
        .replace(/__stgIndex__/g,$('.stage-element').not('.completed-stage-element').length - 1)
        .replace(/__otpIndex__/g, $outputList.children().length - 2)
        .replace(/__otpNb__/g, $outputList.children().length - 1)
        .replace(/__type__/g, typeVal)
        .replace(/__startdate__/g, startdate.getFullYear()+'-'+(startdate.getMonth()+1)+'-'+startdate.getDate() )
        .replace(/__enddate__/g, enddate.getFullYear()+'-'+(enddate.getMonth()+1)+'-'+enddate.getDate() )
        //.replace(/__weight__/g, Math.round(100/(nbCriteria + 1)))
        .replace(/__stgNb__/g, $('.stage').index($section.closest('.stage')));

    let $crtProto = $(newProtoHtml);
    console.log($crtProto);
    //$crtElmt.append(newProtoHtml);
    $crtProto.find('.modal').modal();
    $crtProto.find('.tooltipped').tooltip();
    var date = startdate.getFullYear()+'-'+(startdate.getMonth()+1)+'-'+startdate.getDate();
    console.log(date);
    var time = startdate.getHours() + ":" + startdate.getMinutes() + ":" + startdate.getSeconds();
    startdate = date+' '+time;
    var date = enddate.getFullYear()+'-'+(enddate.getMonth()+1)+'-'+enddate.getDate();
    var time = enddate.getHours() + ":" + enddate.getMinutes() + ":" + enddate.getSeconds();
    enddate = date+' '+time;

    $outputList.children().last().before($crtProto);

    const $form = $('.o-form form');

    const urlToPieces = vourl.split('/');
    urlToPieces[urlToPieces.length - 4] = sid;
    urlToPieces[urlToPieces.length - 1] = oid;
    const url = urlToPieces.join('/');


    $form.find('[name*="type"]').eq(typeVal - 1).prop('checked',true);
    $form.find('[name*="[startdate]"]').val(startdate);
    $form.find('[name*="[enddate]"]').val(enddate);


    console.log(enddate);
    $.post(url, $form.serialize())
        .done(function(data) {
            $crtProto.attr('data-oid',data.oid);
            $deleteBtn.attr('data-oid',data.oid);
            $btn.closest('.modal').modal('close');

        })
        .fail(function(data) {
            Object.keys(data.responseJSON).forEach(function(key){
                $btn.closest('.modal').find(`input[name*="${key}"]`).after(`<strong class="red-text">${data.responseJSON[key]}</strong>`);
            })
        });


});
$(document).on('click','.criteria-tab, .survey-tab',function(){

  if($(this).hasClass('survey-tab') && $(this).find('a').hasClass('active') && $(this).closest('section').find('.criteria-list .criteria-list--item').length > 0){
      $('#changeOutputType').modal('open');
      $('.change-output-btn').data('sid',$(this).closest('.stage').data('id'));
  }
});

$(document).on('click','.change-output-btn',function(){
  const urlToPieces = courl.split('/');
  let sid = $(this).data('sid');
  urlToPieces[urlToPieces.length-2] = sid;
  url = urlToPieces.join('/');
  $.post(url, null)
      .done(function(data) {
          let $stgElmt = $(`.stage[data-id="${sid}"]`);
          if(!data.surveyDeletion){
            $crtHolder = $stgElmt.find('.stage-container .criteria .criteria-list');
            $crtHolder.find('.criteria-list--item').each(function(i,e){
              $(e).remove();
            })
          } else {

          }
      })
      .fail(function(data) {

      });
});

$('.stage-add .stage-fresh-new-btn').on('click',function(){
  const nbStages = $('.stage').length;
  const proto = $(document).find('template.stages-list--item__proto')[0];
  const protoHtml = proto.innerHTML.trim();
  const newProtoHtml = protoHtml
    .replace(/__stgIndex__/g, nbStages)
    .replace(/__stgNb__/g, nbStages)
    .replace(/__realStgNb__/g, nbStages + 1);
  const $stgElmt = $(newProtoHtml);
  $stgElmt.find('.modal').modal();
  $stgElmt.find('.tooltipped').tooltip();
  $stgElmt.find('.tabs').tabs();
  $('.stage-add').before($stgElmt);
  toggleStage($stgElmt);

  // Initializing stage default values
  var initStartDate = new Date(Date.now());
  var initEndDate = new Date(Date.now() + 15 * 24 * 60 * 60 * 1000);
  var initGStartDate = new Date(Date.now());
  var initGEndDate = new Date(Date.now() + 30 * 24 * 60 * 60 * 1000);

  $stgElmt.find('.dp-start,.dp-end, .dp-gstart, .dp-gend').each(function() {
      $(this).pickadate();
  });

  $stgElmt.find('.dp-start').pickadate('picker').set('select',initStartDate);
  $stgElmt.find('.dp-end').pickadate('picker').set('select',initEndDate).set('min',initStartDate);

  $stgElmt.find('input[name*="period"]').val(15);
  $stgElmt.find('select[name*="frequency"]').val('D');
  $stgElmt.find('[name*="mode"]').eq(0).prop('checked',true);

  var slider = $stgElmt.find('.weight-stage-slider');
  var weight = $stgElmt.find('.weight');

  //Removing '%' text added by PercentType
  weight[0].removeChild(weight[0].lastChild);

  var creationVal = Math.round(100 / (nbStages + 1));

  noUiSlider.create(slider[0], {
      start: creationVal,
      step: 1,
      connect: [true, false],
      range: {
          'min': 0,
          'max': 100,
      },
  });

  slider[0].nextElementSibling.innerHTML = creationVal + ' %';
  slider[0].nextElementSibling.nextElementSibling.value = creationVal;

  slider[0].noUiSlider.on('slide', function (values, handle) {

      slider[0].nextElementSibling.innerHTML = Number(values[handle]) + ' %';
      slider[0].nextElementSibling.nextElementSibling.value = values[handle];

  });

  handleCNSelectElems($stgElmt);

  $stgElmt.find('.stage-modal').modal({
    ready: function(){
      $modal = $(this)[0].$el;
      setTimeout(function(){
        $modal.find('.weight').css('width',`${ 0.25 * $modal.find('.first-data-row').width() }`);
      },100);
    },
    complete: function(){
      if(!$('.remove-stage').hasClass('clicked')){
        let btnV = $stgElmt.find('.s-validate');
        var $slider = $stgElmt.find('.weight .weight-stage-slider');
        var $sliders = $('.stage .weight').find('.weight-stage-slider').not(slider);
        if(!btnV.hasClass('clicked')){
            if($stgElmt.hasClass('new')){

              $stgElmt.remove();
              $('.stage').last().addClass('active');

            } else {

              var startCal =  $stgElmt.find('.dp-start');
              var endCal =  $stgElmt.find('.dp-end');
              var gStartCal =  $stgElmt.find('.dp-gstart');
              var gEndCal =  $stgElmt.find('.dp-gend');
              const regex = /janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre|enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre|janeiro|fevereiro|março|abril|maio|junho|julho|agosto|setembro|outubro|novembro|dezembro/g ;
              var startDateTS = (startCal.val() == "") ? initStartDate : parseDdmmyyyy(startCal.attr('value').replace(regex,function(match){return replaceVars[match];}));
              var endDateTS = (endCal.val() == "") ? initEndDate : parseDdmmyyyy(endCal.attr('value').replace(regex,function(match){return replaceVars[match];}));
              var gStartDateTS = (gStartCal.val() == "") ? initGStartDate : parseDdmmyyyy(gStartCal.attr('value').replace(regex,function(match){return replaceVars[match];}));
              var gEndDateTS = (gEndCal.val() == "") ? initGEndDate : parseDdmmyyyy(gEndCal.attr('value').replace(regex,function(match){return replaceVars[match];}));
              var startDate = new Date(startDateTS);
              var endDate = new Date(endDateTS);
              var gStartDate = new Date(gStartDateTS);
              var gEndDate = new Date(gEndDateTS);

              startCal.pickadate('picker').set('select',startDate);
              endCal.pickadate('picker').set('select',endDate).set('min',startDate);
              gStartCal.pickadate('picker').set('select',gStartDate).set('min',startDate);
              gEndCal.pickadate('picker').set('select',gEndDate).set('min',gStartDate);

              prevWeight = +$slider[0].nextElementSibling.nextElementSibling.getAttribute('value');
              stgName = $stgElmt.find('input[name*="name"]').attr('value');
              stgMode = $stgElmt.find('input[name*="mode"][checked="checked"]').val();

              $slider[0].nextElementSibling.innerHTML = prevWeight + ' %';
              $slider[0].nextElementSibling.nextElementSibling.value = prevWeight;
              $slider[0].noUiSlider.set(prevWeight);
              $stgElmt.find(`input[name*="mode"][value = ${stgMode}]`).prop("checked",true);
              $stgElmt.find('input[name*="name"]').val(stgName);
              $stgElmt.find('.stage-item-name').find('.s-weighting').empty().append(`(${prevWeight} %)`);
              $stgElmt.find('.stage-weight').find('.s-weighting').empty().append(`${prevWeight} %`);
              $stgElmt.find('select[name*="visibility"]').val($stgElmt.find('select[name*="visibility"] option[selected="selected"]').val());

            }
        } else {
            btnV.removeClass('clicked');
            const weightValue = +$stgElmt.find('.weight input').val();
            $stgElmt.find('input[name*="name"]').attr('value',$stgElmt.find('input[name*="name"]').val());
            $stgElmt.find('input[name*="mode"][checked="checked"]').removeAttr("checked");
            $stgElmt.find('input[name*="mode"]:checked').attr('checked',"checked");
            $stgElmt.find('.stage-name-field').text($stgElmt.find('input[name*="name"]').val());

            $stgElmt.find('.stage-item-name').find('.s-weighting').empty().append(`(${weightValue} %)`);
            $stgElmt.find('.stage-weight').find('.s-weighting').empty().append(`${weightValue} %`);

            handleCNSelectElems($stgElmt);
            if(!$stgElmt.find('[id*="type--criteria"], [id*="type--survey"]').hasClass('active')){
              $stgElmt.find('.survey-tab a').click();
              setTimeout(function(){
                $stgElmt.find('.criteria-tab a').click();
              },100);
            }

            var $slider = $stgElmt.find('.weight-stage-slider');
            var $sliders = $('.stage .weight').find('.weight-stage-slider').not(slider);
            if($sliders.length == 1){
              $sliders.closest('.weight').show();
            }

            var oldValue = $stgElmt.hasClass('new') ? 0 : $stgElmt.find('.weight input').attr('value');
            var sumVal = 0;
            var newValue = weightValue;

            $.each($sliders, function (key, value) {

                var nv = (key != $sliders.length - 1) ?
                  Math.round(Number(Number($(this)[0].noUiSlider.get()) * (100 - newValue) / (100 - oldValue))) :
                  100 - sumVal - newValue;

                $(this)[0].nextElementSibling.innerHTML = nv + ' %';
                $(this)[0].nextElementSibling.nextElementSibling.value = nv;
                $(this)[0].nextElementSibling.nextElementSibling.setAttribute('value',nv);
                $(this)[0].noUiSlider.set(nv);
                sumVal += nv;

                $(value).closest('.stage').find('.stage-item-name').find('.s-weighting').empty().append(`(${nv} %)`);
                $(value).closest('.stage').find('.stage-weight').find('.s-weighting').empty().append(`${nv} %`);

            })
            $stgElmt.find('.weight input').attr('value',newValue);
            $stgElmt.removeClass('new').removeAttr('style');
        }
      } else {
        $('.remove-stage').removeClass('clicked');
      }

    }
  })
  $stgElmt.find('.stage-modal').modal('open');
})

$(document).on('change','.date-switch input',function(){
  $(this).is(':checked') ?  ($(this).closest('.row').find('.period-freq-input').hide(), $(this).closest('.row').find('.dates-input').show()) : ($(this).closest('.row').find('.period-freq-input').show(), $(this).closest('.row').find('.dates-input').hide());
});

$(document).on('click','.activity-name:not(.edit) > .btn-edit',function(){
  $('.activity-name').addClass('edit');
});

$(document).on('click','.activity-name.edit > .btn-edit',function(){
  const name = $('.custom-input').text().trim();
  const params = {name: name};
  if(name != $('.activity-name input').attr('value')){
    $.post(vnurl,params)
      .fail(function(data) {
        $('#duplicateElementName').modal('open');
      })
      .done(function(data){
        $('.activity-name input').attr('value',name);
        $('.activity-name .show').empty().append(name);
        $('.activity-name').removeClass('edit');
      });
  } else {
      $('.activity-name').removeClass('edit');
  }
});

$('.activity-element-update, .activity-element-save').on('click',function(e){
  e.preventDefault();
  const unvalidatedNewPart = $('.participants-list--item:not([data-id]).edit-mode .edit-user-btn:visible');
  if(!unvalidatedNewPart.length){
    $('form[name="activity_element_form"]').submit();
  } else {
    unvalidatedNewPart.each(function(i,e){
      $(e).click();
    })
  }
})

$('.remove-activity').on('click',function(e){
  e.preventDefault();
  $.delete(daurl,{r: 'json'})
    .done(function(data){
      $('.back-btn').click();
    })
    .fail(function(data){
      console.log(data);
    })
})


