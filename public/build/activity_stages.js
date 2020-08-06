(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["activity_stages"],{

/***/ "./web/js/activity_stages.js":
/*!***********************************!*\
  !*** ./web/js/activity_stages.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _readOnlyError(name) { throw new Error("\"" + name + "\" is read-only"); }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

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
var STAGE_LIST = 'ol.stages';
var STAGE_ITEM = 'li.stage-element';
var PARTICIPANTS_ITEM = 'li.participants-list--item';
var STAGE_MODAL = '.stage-modal';
var STAGE_NAME_INPUT = 'input.stage-name-input';
var STAGE_LABEL = '.stage-label';
var CRITERION_MODAL = '.criterion-modal';
var CRITERION_NAME_SELECT = 'select.criterion-name-select';
var CRITERION_LABEL = '.criterion-label';
var participationTypes = {
  '-1': 'p',
  '0': 't',
  '1': 'a'
};
var $stageList = $(STAGE_LIST);
var $stageAddItem = $stageList.find('> li.stage-add');
var $addStageBtn = $stageAddItem.find('.stage-fresh-new-btn');
/**
 * @type {string}
 */

var proto = $(document).find('template.stages-list--item__proto')[0];
setTimeout(function () {
  if ($('#errors').length > 0) {
    $('#errors').modal('open');
    $('#errors').find('label+span').each(function () {
      $(this).text($(this).prev().text() + ' :');
      $(this).prev().remove();
    });
    $('#errors .modal-content ul').css('display', 'inline-block').addClass('no-margin');
  }
}, 200);
lg = 'fr';
var $stageCollectionHolder = $('.stages');

function sliders() {
  var $btn = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;

  if ($btn != null) {
    if ($btn.closest('.criteria').length > 0) {
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


  var newSliders = theSliders.filter(function (e) {
    return !e.classList.contains('initialized');
  });

  var _iterator = _createForOfIteratorHelper(newSliders),
      _step;

  try {
    var _loop = function _loop() {
      var e = _step.value;
      var weightElmt = e.parentElement; //Removing '%' text added by PercentType

      weightElmt.removeChild(weightElmt.lastChild);
      var input = weightElmt.querySelector('input');
      var label = weightElmt.querySelector('.weight-criterion-slider-range-value, .weight-stage-slider-range-value');

      var slideCallback = function slideCallback(values, handle) {
        label.innerHTML = "".concat(+values[handle], " %");
        label.nextElementSibling.value = values[handle];

        if ($(e).hasClass('weight-criterion-slider')) {
          $(e).closest('.criteria-list--item').find('.c-weighting').empty().append("(".concat(Number(values[handle]), " %)"));
        }
      };

      noUiSlider.create(e, {
        start: +input.value,
        step: 1,
        connect: [true, false],
        range: {
          min: 0,
          max: 100
        }
      });
      slideCallback([+input.value], 0);
      e.noUiSlider.on('slide', slideCallback);
      e.classList.add('initialized');
    };

    for (_iterator.s(); !(_step = _iterator.n()).done;) {
      _loop();
    }
  } catch (err) {
    _iterator.e(err);
  } finally {
    _iterator.f();
  }
}

sliders();

function parseDdmmyyyy(str) {
  var parts = str.split('/');
  return new Date(parts[2], parts[1] - 1, parts[0]);
} // Updates calendar datepickers


function updateDatepickers(k, index) {
  var _replaceVars;

  var nbStages = $('.stage').length;
  var replaceVars = (_replaceVars = {
    "janvier": "January",
    "enero": "January",
    "janeiro": "January",
    "février": "February",
    "febrero": "February",
    "fevereiro": "February",
    "mars": "March",
    "marzo": "March",
    "março": "March",
    "avril": "April",
    "abril": "April"
  }, _defineProperty(_replaceVars, "abril", "April"), _defineProperty(_replaceVars, "mai", "May"), _defineProperty(_replaceVars, "mayo", "May"), _defineProperty(_replaceVars, "maio", "May"), _defineProperty(_replaceVars, "juin", "June"), _defineProperty(_replaceVars, "junio", "June"), _defineProperty(_replaceVars, "junho", "June"), _defineProperty(_replaceVars, "juillet", "July"), _defineProperty(_replaceVars, "julio", "July"), _defineProperty(_replaceVars, "julho", "July"), _defineProperty(_replaceVars, "août", "August"), _defineProperty(_replaceVars, "agosto", "August"), _defineProperty(_replaceVars, "agosto", "August"), _defineProperty(_replaceVars, "septembre", "September"), _defineProperty(_replaceVars, "septiembre", "September"), _defineProperty(_replaceVars, "setembro", "September"), _defineProperty(_replaceVars, "octobre", "October"), _defineProperty(_replaceVars, "octubre", "October"), _defineProperty(_replaceVars, "outubro", "October"), _defineProperty(_replaceVars, "novembre", "November"), _defineProperty(_replaceVars, "noviembre", "November"), _defineProperty(_replaceVars, "novembro", "November"), _defineProperty(_replaceVars, "décembre", "December"), _defineProperty(_replaceVars, "diciembre", "December"), _defineProperty(_replaceVars, "dezembro", "December"), _replaceVars);
  var regex = /janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre|enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre|janeiro|fevereiro|março|abril|maio|junho|julho|agosto|setembro|outubro|novembro|dezembro/g; //Three possible cases : loading(0), addition of one stage(1), update(2) or removal (-1)

  if (k == 0) {
    //Set datepickers boundaries of grading dates for all stages
    var current = 0;
    var $terminalStageEndCal = $('.dp-end:eq(-1)');
    $('.stage').each(function () {
      var startCal = $(this).find('.dp-start');
      var endCal = $(this).find('.dp-end');
      var gStartCal = $(this).find('.dp-gstart');
      var gEndCal = $(this).find('.dp-gend');
      var startDateTS = startCal.val() == "" ? Date.now() : parseDdmmyyyy(startCal.val().replace(regex, function (match) {
        return replaceVars[match];
      }));
      var endDateTS = endCal.val() == "" ? startDateTS : parseDdmmyyyy(endCal.val().replace(regex, function (match) {
        return replaceVars[match];
      }));
      var gStartDateTS = gStartCal.val() == "" ? startDateTS : parseDdmmyyyy(gStartCal.val().replace(regex, function (match) {
        return replaceVars[match];
      }));
      var gEndDateTS = gEndCal.val() == "" ? startDateTS : parseDdmmyyyy(gEndCal.val().replace(regex, function (match) {
        return replaceVars[match];
      }));
      var startDate = new Date(startDateTS);
      var endDate = new Date(endDateTS);
      var gStartDate = new Date(gStartDateTS);
      var gEndDate = new Date(gEndDateTS);
      startCal.pickadate('picker').set('select', startDate);
      endCal.pickadate('picker').set('select', endDate).set('min', startDate);
      gStartCal.pickadate('picker').set('select', gStartDate).set('min', startDate);
      gEndCal.pickadate('picker').set('select', gEndDate).set('min', gStartDate);
    });
  } else if (k == 1) {
    var $lastStageEndCal = $('.dp-end:eq(' + index + ')');
    var $addedStageStartCal = $('.dp-start:eq(' + (index + 1) + ')');
    var $addedStageEndCal = $('.dp-end:eq(' + (index + 1) + ')');
    var $lastStageWeight = $('.weight-input:eq(' + index + ')');
    var $addedStageWeight = $('.weight-input:eq(' + (index + 1) + ')');
    var $addedStageGStartCal = $('.dp-gstart:eq(' + (index + 1) + ')');
    var $addedStageGEndCal = $('.dp-gend:eq(' + (index + 1) + ')');
    $addedStageStartCal.add($addedStageEndCal).add($addedStageGStartCal).add($addedStageGEndCal).pickadate();
    var addedStageStartdate = new Date($lastStageEndCal.pickadate('picker').get('select').pick);
    var addedStageEnddate = new Date($lastStageEndCal.pickadate('picker').get('select').pick);
    var addedStageGStartdate = new Date($lastStageEndCal.pickadate('picker').get('select').pick);
    var addedStageGEnddate = new Date($lastStageEndCal.pickadate('picker').get('select').pick);
    $addedStageStartCal.pickadate('picker').set('select', addedStageStartdate);
    $addedStageEndCal.pickadate('picker').set('select', addedStageEnddate).set('min', $addedStageStartCal.pickadate('picker').get('select'));
    $addedStageGStartCal.pickadate('picker').set('select', addedStageGStartdate);
    $addedStageGEndCal.pickadate('picker').set('select', addedStageGEnddate).set('min', $addedStageGStartCal.pickadate('picker').get('select'));
  } else if (k == -1) {
    var $upstreamStageWeight = $('.weight:eq(' + index + ')');
    var $removedStageWeight = $('.weight:eq(' + (index + 1) + ')');
    $upstreamStageWeight.val(parseFloat($upstreamStageWeight.val()) + parseFloat($removedStageWeight.val()));
  }
}

switch (lg) {
  case 'fr':
    $.extend($.fn.pickadate.defaults, {
      monthsFull: ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'],
      monthsShort: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'],
      weekdaysFull: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
      weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
      today: 'Aujourd\'hui',
      clear: 'Effacer',
      close: 'Fermer',
      firstDay: 1 //format: 'dd mmmm yyyy',

    });
    break;

  case 'es':
    $.extend($.fn.pickadate.defaults, {
      monthsFull: ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'],
      monthsShort: ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'],
      weekdaysFull: ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'],
      weekdaysShort: ['dom', 'lun', 'mar', 'mié', 'jue', 'vie', 'sáb'],
      today: 'hoy',
      clear: 'borrar',
      close: 'cerrar',
      firstDay: 1 //format: 'dddd d !de mmmm !de yyyy',

    });
    break;

  case 'pt':
    $.extend($.fn.pickadate.defaults, {
      monthsFull: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
      monthsShort: ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'],
      weekdaysFull: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
      weekdaysShort: ['dom', 'seg', 'ter', 'qua', 'qui', 'sex', 'sab'],
      today: 'Hoje',
      clear: 'Limpar',
      close: 'Fechar',
      firstDay: 1 //format: 'd !de mmmm !de yyyy',

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
  clear: false //format : 'dd MMMM, yyyy',
  //formatSubmit: 'yyyy/mm/dd'

});
$('.dp-start, .dp-end, .dp-gstart, .dp-gend').each(function () {
  $(this).pickadate();
});
var endDates = $('.dp-end');
endDates.data('previous', endDates.val()); //Set datepickers boundaries on loading

updateDatepickers(0, 0);
$('.dp-start, .dp-end, .dp-gstart').on('change', function () {
  var selectedDate = $(this).pickadate('picker').get('select');
  var $GStartCal = $(this).closest('.stage').find('.dp-gstart');
  var $GEndCal = $(this).closest('.stage').find('.dp-gend');

  if ($(this).hasClass('dp-start') || $(this).hasClass('dp-gstart')) {
    var classPrefix = $(this).attr('class').split(' ')[0].slice(0, -5); //Shifting enddates values (for grading and stage)

    var $relatedEndCal = $(this).closest('.row').find('.' + classPrefix + 'end');

    if ($relatedEndCal.pickadate('picker').get('select').pick < selectedDate.pick) {
      $relatedEndCal.pickadate('picker').set('select', new Date(selectedDate.pick
      /*+ 14 * 24 * 60 * 60 * 1000*/
      ));
    }

    $relatedEndCal.pickadate('picker').set('min', selectedDate);
    $GStartCal.pickadate('picker').set('min', new Date($('.dp-start').pickadate('picker').get('select').pick));
  } else if ($(this).hasClass('dp-end') && $(this).closest('.recurring').length == 0) {
    if ($GStartCal.pickadate('picker').get('select').pick < selectedDate.pick) {
      $GStartCal.pickadate('picker').set('select', new Date(selectedDate.pick + 1 * 24 * 60 * 60 * 1000));
    }

    var GStartDate = $GStartCal.pickadate('picker').get('select');

    if ($GEndCal.pickadate('picker').get('select').pick < GStartDate.pick) {
      $GEndCal.pickadate('picker').set('select', new Date(GStartDate.pick + 7 * 24 * 60 * 60 * 1000));
    }

    $GEndCal.pickadate('picker').set('min', GStartDate);
  }
});
$('.stage-add').hover(function () {
  $(this).find('.stage-item-name').css('visibility', 'hidden');
}, function () {
  $(this).find('.stage-item-name').css('visibility', 'visible');
});
$('.duplicate-btn').on('click', function () {
  $btn = $(this);
  urlToPieces = dpsurl.split('/');
  urlToPieces[urlToPieces.length - 1] = $btn.closest('.modal').find('#stageSelect').val();
  url = urlToPieces.join('/');
  $.post(url).done(function (data) {
    location.reload();
  }).fail(function (data) {
    console.log(data);
  });
});
$(document).on('click', "".concat(STAGE_ITEM, " > .stage-item-button"), function () {
  var $this = $(this);
  toggleStage($this);
}).on('click', '.remove-stage-btn', function () {
  var $this = $(this);
  var $stageItem = $this.closest(STAGE_ITEM);
  $stageItem.find('.stage-modal').modal('close');
  $stageItem.remove();
}).on('click', '.edit-user-btn', function () {
  var $this = $(this);
  var $participantItem = $this.closest(PARTICIPANTS_ITEM);
  $participantItem.addClass('edit-mode');
}).on('click', '.btn-add-criterion', function () {
  var $this = $(this);
  var $section = $this.closest('section');
  var $criteriaList = $section.find('ul.criteria-list');

  if ($criteriaList.children('.new').length) {
    return;
  } //const $proto =  $section.find('template.criteria-list--item__proto');

  /** @type {HTMLTemplateElement} */


  var $criteria = $section.find('.criteria-list--item');
  var nbCriteria = $criteria.length;
  var proto = $section.find('template.criteria-list--item__proto')[0];
  var protoHtml = proto.innerHTML.trim();
  var newProtoHtml = protoHtml.replace(/__name__/g, $criteriaList.children().length - 2).replace(/__crtNb__/g, $criteriaList.children().length - 1).replace(/__lowerbound__/g, 0).replace(/__upperbound__/g, 5).replace(/__step__/g, 0.5) //.replace(/__weight__/g, Math.round(100/(nbCriteria + 1)))
  .replace(/__stgNb__/g, $('.stage').index($section.closest('.stage')));
  var $crtElmt = $(newProtoHtml); //$crtElmt.append(newProtoHtml);

  $crtElmt.find('.modal').modal();
  $crtElmt.find('.tooltipped').tooltip(); // Setting default values, and putting label upside by adding them "active" class

  $crtElmt.find('input[name*="lowerbound"]').val(0).prev().addClass("active");
  $crtElmt.find('input[name*="upperbound"]').val(5).prev().addClass("active");
  $crtElmt.find('input[name*="step"]').val(0.5).prev().addClass("active"); // Select new criterion as being an evaluation one (by default)

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
  var weight = $crtElmt.find('.weight'); //Removing '%' text added by PercentType

  weight[0].removeChild(weight[0].lastChild); //Get new criteria objects after insertion
  //var relatedCriteria = $crtElmt.closest('.stage').find('.criterion');

  $relatedCriteria = $criteriaList.find('.criteria-list--item');
  var creationVal = Math.round(100 / $relatedCriteria.length);
  var sumVal = 0;
  creationVal = Math.round(100 / $relatedCriteria.length);
  noUiSlider.create(slider[0], {
    start: creationVal,
    step: 1,
    connect: [true, false],
    range: {
      'min': 0,
      'max': 100
    }
  });
  slider[0].nextElementSibling.innerHTML = creationVal + ' %';
  slider[0].nextElementSibling.nextElementSibling.value = creationVal;
  slider[0].noUiSlider.on('slide', function (values, handle) {
    slider[0].nextElementSibling.innerHTML = Number(values[handle]) + ' %';
    slider[0].nextElementSibling.nextElementSibling.value = values[handle];
  });
  slider.next().next().hide();

  if (nbCriteria == 0) {
    slider.closest('.weight').hide();
  }

  handleCNSelectElems($crtElmt);
  $crtElmt.find('.criterion-modal').modal({
    complete: function complete() {
      var modC = $(this)[0].$el;
      var $crtElmt = modC.closest('.criteria-list--item');
      var btnV = $crtElmt.find('.c-validate');
      var slider = $crtElmt.find('.weight-criterion-slider');

      if (!btnV.hasClass('clicked')) {
        if ($crtElmt.hasClass('new')) {
          $crtElmt.remove();
        } else {
          prevWeight = +slider[0].nextElementSibling.nextElementSibling.getAttribute('value');
          prevUB = $crtElmt.find('.upperbound').attr('value');
          prevLB = $crtElmt.find('.lowerbound').attr('value');
          prevType = $crtElmt.find('input[name*="type"][checked="checked"]').val();
          slider[0].nextElementSibling.innerHTML = prevWeight + ' %';
          slider[0].nextElementSibling.nextElementSibling.value = prevWeight;
          slider[0].noUiSlider.set(prevWeight);
          $crtElmt.find('input[name*="type"]').eq(prevType - 1).prop("checked", true);
          $crtElmt.find('.upperbound').val(prevUB);
          $crtElmt.find('.lowerbound').val(prevLB);
          $crtElmt.find('.c-weighting').empty().append("(".concat(prevWeight, " %)"));
          $crtElmt.find('select[name*="cName"]').val($crtElmt.find('select[name*="cName"] option[selected="selected"]').val());
        }
      } else {
        btnV.removeClass('clicked');
        var weightValue = +$crtElmt.find('.weight input').val();
        slider[0].nextElementSibling.nextElementSibling.setAttribute('value', slider[0].nextElementSibling.nextElementSibling.value);
        $crtElmt.find('.upperbound').attr('value', $crtElmt.find('.upperbound').val());
        $crtElmt.find('.lowerbound').attr('value', $crtElmt.find('.lowerbound').val());
        $crtElmt.find('input[name*="type"][checked="checked"]').removeAttr("checked");
        $crtElmt.find('input[name*="type"]:checked').attr('checked', "checked");
        $crtElmt.find('.cname').text($crtElmt.find('select[name*="cName"] option:selected').text().split(' ').slice(1).join(' '));
        $crtElmt.find('.cname').attr('data-icon', $crtElmt.find('select[name*="cName"] option:selected').attr('data-icon'));
        $crtElmt.find('.c-weighting').empty().append("(".concat(weightValue, " %)"));
        $crtElmt.removeClass('new').removeAttr('style');
        handleCNSelectElems($crtElmt);
        var slider = $crtElmt.find('.weight-criterion-slider');
        var oldValue = Number(slider[0].noUiSlider.get());
        var sliders = $crtElmt.closest('.stage').find('.weight-criterion-slider').not(slider);

        if (sliders.length == 1) {
          sliders.closest('.weight').show();
        }

        var sumVal = 0;
        var k = 0;
        var newValue = 0;
        $.each(sliders, function (key, value) {
          var nv = key != sliders.length - 1 ? Math.round(Number(Number($(this)[0].noUiSlider.get()) * (100 - weightValue) / 100)) : 100 - sumVal - weightValue;
          $(this)[0].nextElementSibling.innerHTML = nv + ' %';
          $(this)[0].nextElementSibling.nextElementSibling.value = nv;
          $(this)[0].noUiSlider.set(nv);
          sumVal += nv;
          k++;
          $(value).closest('.criteria-list--item').find('.c-weighting').empty().append("(".concat(nv, " %)"));
        });
      }
      /*if(modC.find('input[type="checkbox"]').is(':checked') || modC.find('textarea').val() != ""){
          $('[href="#criterionTarget_'+s+'_'+c+'"]').addClass('lime darken-3').empty().append($('<ul class="flex-center no-margin">'+modalModifyMsg+'<i class="far fa-dot-circle" style="margin-left:10px"></i><i class="fas fa-comment-dots" style="margin-left:10px"></i></ul>'));
      } else {
          $('[href="#criterionTarget_'+s+'_'+c+'"]').removeClass('lime darken-3').empty().append($('<ul class="flex-center no-margin">'+modalSetMsg+'<i class="far fa-dot-circle" style="margin-left:10px"></i><i class="far fa-comment" style="margin-left:10px"></i></ul>'));
      }*/

    }
  });
  $crtElmt.find('.criterion-modal').modal('open');
}).on('click', '[href="#deleteParticipant"]', function (e) {
  var $this = $(this);
  var $participantItem = $this.closest(PARTICIPANTS_ITEM);

  if (!$participantItem.data('id')) {
    e.stopPropagation();
    $participantItem.remove();
  } else {
    var $stageItem = $this.closest(STAGE_ITEM);
    var $modalDeletionBtn = $('#deleteParticipant .remove-participant-btn');
    var dpurl = removeParticipantUrl.replace('__stgId__', $stageItem.data('id')).replace('__elmtId__', $participantItem.data('id'));
    $modalDeletionBtn.data('id', $participantItem.data('id'));
    $modalDeletionBtn.on('click', async function () {
      await $.post(dpurl);
      $userVal = $participantItem.find('select[name*="DirectUser"]').val();
      $participantList = $participantItem.closest('.participant-list');
      $participantItem.remove();
      $participantList.find('.participant-list--item').each(function (i, e) {
        $(e).find("select[name*=\"DirectUser\"] option[value=\"".concat($userVal, "\"]")).prop('disabled', false);
      });
    });
  }
}).on('click', '.btn-add-participant-i, .btn-add-participant-e', function () {
  var $this = $(this);
  var pType = $this.hasClass('btn-add-participant-i') ? 'i' : $this.hasClass('btn-add-participant-e') ? 'e' : 't';
  var $section = $this.closest('section');
  var $participantsList = $section.children('ul.participants-list');

  if ($participantsList.children('.new').length) {
    return;
  }

  if (!$participantsList.find(".participants-list--item[mode=\"".concat(pType, "\"]")).find('select[name*="directUser"] option:visible').length) {
    $('#noRemainingParticipant').modal('open');
    return false;
  }

  ;
  /** @type {HTMLTemplateElement} */

  var proto = $section.children("template.participants-list--item__proto-".concat(pType))[0];
  var protoHtml = proto.innerHTML.trim();
  $newProtoHtml = $(protoHtml.replace(/__name__/g, $participantsList.children().length));
  /*
  $existingParticipantSelects = $participantsList.find('select[name*="directUser"]');
  $participantHiddenSelect = $newProtoHtml.find('select[name*="directUser"]');
  hiddenElmts = [];
  // Retrieving all previous values of selected participants
  $.each($existingParticipantSelects, function() {hiddenElmts.push($(this).val())});
   // Disable all previously inserted participant in new participant select
  $.each(hiddenElmts, function(key, value) {
      $participantHiddenSelect.find('option[value="'+value+'"]').prop('disabled', true);
  })
   selectedNewParticipantVal = $participantHiddenSelect.find('option:not(:disabled)').eq(0).val();
  $participantHiddenSelect.val(selectedNewParticipantVal);
  // Disable new selected participant in all existing participant selects
   $.each($existingParticipantSelects, function(key, existingParticipantSelect) {
      $(existingParticipantSelect).find('option[value="'+selectedNewParticipantVal+'"]').prop('disabled', true);
  })
   handleCNSelectElems();
  */

  $participantsList.append($newProtoHtml);
  handleParticipantsSelectElems($newProtoHtml);
})
/*.on(
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
)*/
.on('click', '.remove-participant-btn', function () {
  var $this = $(this);
  var $participantItem = $this.closest(PARTICIPANTS_ITEM);
  $participantItem.remove();
}).on('click', '.edit-mode .edit-user-btn', // edit button, only when in edit mode (check icon is shown)
async function () {
  var $this = $(this);
  var $participantItem = $this.closest(PARTICIPANTS_ITEM);
  var $stageItem = $this.closest(STAGE_ITEM);
  /** @type {JQuery<HTMLSelectElement>} */

  var $userSelect = $participantItem.find('select.user-select');
  var $userName = $participantItem.find('.user-name');
  /** @type {JQuery<HTMLInputElement>} */

  var $userIsLeader = $participantItem.find('.user-is-leader');
  var userIsLeader = $userIsLeader.length > 0 ? $userIsLeader[0].checked : null;
  /** @type {JQuery<HTMLSelectElement>} */

  var $userParticipantType = $participantItem.find('select.user-participant-type');
  var userParticipantType = $userParticipantType[0].value;

  if (!$('.add-owner-lose-setup,.change-owner-button').hasClass('clicked')) {
    // Change ownership management
    $potentialDifferentLeader = $participantItem.closest('.participants-list').find('.badge-participation-l:visible');

    if (userIsLeader == true) {
      if ($.inArray(+usrRole, [2, 3]) !== -1 && !$potentialDifferentLeader.length && $userSelect.val() != usrId || $potentialDifferentLeader.length && $potentialDifferentLeader.closest('.participants-list--item').find('select[name*="directUser"]').val() == usrId) {
        $('#setOwnershipLoseSetup').modal('open').data('id', $this.closest('.stage').data('id'));
        return false;
      } else if ($potentialDifferentLeader.length) {
        $('#changeOwner').find('.sName').empty().append($this.closest('.stage').find('.stage-name-field').text());
        $('#changeOwner').find('#oldLeader').empty().append($potentialDifferentLeader.closest('.participants-list--item').find('select[name*="directUser"] option:selected').text());
        $('#changeOwner').find('#newLeader').empty().append($userName.text());
        $('#changeOwner').modal('open').data('id', $this.closest('.stage').data('id'));
        return false;
      }
    }
  }

  var url = validateParticipantUrl.replace('__stgId__', $stageItem.data('id')).replace('__elmtId__', $participantItem.data('id') || 0);
  $userName.html($userSelect.children(':checked').first().html());
  var params = {
    user: $userSelect[0].value,
    type: userParticipantType,
    precomment: null
  };

  if (userIsLeader) {
    params.leader = true;
  }

  if (!$this.hasClass('warned') && $participantItem.closest('.participants-list').find('.badge-participation-validated').length) {
    if (userParticipantType != 0 && ($participantItem.hasClass('new') || $userParticipantType.find('option[selected="selected"]').val() == 0)) {
      $('#unvalidatingOutput').modal('open');
      $('.unvalidate-btn').addClass('p-validate').removeClass('c-validate');
      $('.unvalidate-btn').removeData().data('pid', $participantItem.closest('.participants-list--item').data('id'));
      $(document).on('click', '.p-validate', function () {
        $clickingBtn = $(this).data('pid') ? $(".participants-list--item[data-id=\"".concat($(this).data('pid'), "\"]")).find('.edit-user-btn') : $('.participants-list--item.new').find('.edit-user-btn');
        $clickingBtn.addClass('warned').click();
      });
      return false;
    }
  }

  var _await$$$post = await $.post(url, params),
      eid = _await$$$post.eid,
      user = _await$$$post.user,
      canSetup = _await$$$post.canSetup;

  if ($this.hasClass('warned')) {
    $participantItem.closest('.participants-list').find('.badge-participation-validated').attr('style', 'display:none;');
    $this.removeClass('warned');
  }

  if (!canSetup) {
    window.location = $('.back-btn').attr('href');
  }

  $participantItem.removeClass('edit-mode new').attr('data-id', eid).attr('is-leader', userIsLeader).attr('participation-type', participationTypes[userParticipantType] || '').find('img.user-picture').prop('src', "/lib/img/".concat(user.picture));
  $partElmt = $participantItem; //$partElmt = $(this).closest('.participants-list--item');
  //if(!$partElmt.hasClass('edit-mode')){

  if ($partElmt.find('.remove-participant-btn').length) {
    $partElmt.find('.remove-participant-btn').removeClass('remove-participant-btn').addClass('modal-trigger').attr('href', '#deleteParticipant');
  }

  $badges = $partElmt.find('.badges');
  $badges.children().attr('style', 'display:none;');

  switch ($partElmt.find('select[name*="type"]').val()) {
    case "1":
      $badges.find('.badge-participation-a').removeAttr('style');
      break;

    case "0":
      $badges.find('.badge-participation-t').removeAttr('style');
      break;

    case "-1":
      $badges.find('.badge-participation-p').removeAttr('style');
      break;
  }

  if ($partElmt.find('select[name*="uniqueExtParticipations"]').length) {
    $badges.find('.badge-participation-e').removeAttr('style');
  }

  if ($partElmt.find('input[name*="leader"]').is(':checked')) {
    $badges.find('.badge-participation-l').removeAttr('style');
  }

  handleParticipantsSelectElems($partElmt);
}).on('input', "".concat(STAGE_MODAL, " ").concat(STAGE_NAME_INPUT), function () {
  var $this = $(this);
  var $modal = $this.closest(STAGE_MODAL);
  var $stageLabel = $modal.find(STAGE_LABEL);
  $stageLabel.html(this.value);
});
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
  var $stageItem = $e.closest(STAGE_ITEM) || $e.is(STAGE_ITEM) && $e;
  if (!$stageItem) return; // remove class to all stage-item

  $stageList.children(STAGE_ITEM).removeClass('active'); // display requested stage

  $stageItem.addClass('active');
}

toggleStage($(STAGE_ITEM).not('.completed-stages-element').first());
$('.activity-element-save, .activity-element-update').on('click', function (e) {
  e.preventDefault();
  $('.element-input').show();
  wgtElmts = [];
  $('.element-input').each(function () {
    if ($(this).find('.weight .weight-input').is(':disabled')) {
      wgtHiddenElmt = $(this).find('.weight .weight-input');
      wgtElmts.push(wgtHiddenElmt);
      wgtHiddenElmt.prop('disabled', false);
    }
  });
  $('[class*="dp-"]').each(function () {
    $(this).val($(this).pickadate('picker').get('select', 'dd/mm/yyyy'));
  });
  $('input[name="clicked-btn"]').attr("value", $(this).hasClass('activity-element-save') ? 'save' : 'update');
  $('[name="activity_element_form"]').submit();
  $('.element-input').hide();
  $.each(wgtElmts, function () {
    wgtHiddenElmt.prop('disabled', true);
  });
});
$('.add-owner-lose-setup, .change-owner-button').on('click', function () {
  $this = $(this);
  $this.addClass('clicked');
  $('.edit-mode .edit-user-btn').click();
  $losingOwnershipPart = $(".stage[data-id=\"".concat($this.hasClass('change-owner-button') ? $('#changeOwner').data('id') : $('#setOwnershipLoseSetup').data('id'), "\"]")).find('.badge-participation-l:visible').closest('.participants-list--item');
  $losingOwnershipPart.find('input[type="checkbox"]').prop('checked', false);
  $losingOwnershipPart.find('.badge-participation-l:visible').hide();
  setTimeout(function () {
    $this.removeClass('clicked');
  }, 500);
});
/**
 * Disables options in criterion name selects as appropriate
 * @param {JQuery|HTMLElement} [target]
 */

function handleCNSelectElems(target) {
  var isCName = function isCName(_i, e) {
    return /_criteria_\d+_cname/gi.test(e.id);
  };

  var $crtElems = target ? target.closest('.criteria-list') : $('.criteria-list');
  var $selects = $crtElems.find('select').filter(isCName);
  $selects.find('option').prop('disabled', false);

  var _iterator2 = _createForOfIteratorHelper($crtElems),
      _step2;

  try {
    var _loop2 = function _loop2() {
      var crtElem = _step2.value;
      var $crtElem = $(crtElem);
      var $options = $crtElem.find('select').filter(isCName).find('option');
      var inUse = $options.filter(':selected').get().map(function (e) {
        return e.value;
      });
      $optionsToDisable = $options.filter(function (_i, e) {
        return inUse.includes(e.value) && !e.selected;
      });
      $optionsToDisable.each(function (_i, e) {
        return $(e).prop('disabled', true);
      });

      if (target && target.hasClass('new')) {
        $targetPartSelect = target.find('select').filter(isCName);
        potentialDuplicate = inUse.reduce(function (acc, v, i, arr) {
          return arr.indexOf(v) !== i && acc.indexOf(v) === -1 ? acc.concat(v) : acc;
        }, []);

        if (potentialDuplicate.length) {
          $targetPartSelect.find("option[value=\"".concat(potentialDuplicate[0], "\"]")).prop('disabled', true);
          $targetPartSelect.find('option').each(function (i, e) {
            if (!inUse.includes($(e).val())) {
              $targetPartSelect.val($(e).val());
              return false;
            }
          });
        }
      }
    };

    for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
      _loop2();
    }
  } catch (err) {
    _iterator2.e(err);
  } finally {
    _iterator2.f();
  }

  initCNIcons();
  $('.select-dropdown li').addClass('flex-center');
  $('.select-dropdown li img').each(function (i, e) {
    var $this = $(e);
    $this.css({
      height: 'auto',
      width: '20px',
      margin: '0',
      "float": 'none',
      color: '#26a69a'
    });
  });
}
/**
 * Disables options in criterion name selects as appropriate
 * @param {JQuery|HTMLElement} [target]
 */


function handleParticipantsSelectElems(target) {
  var isCName = function isCName(_i, e) {
    return /_\d+_directuser/gi.test(e.id);
  };

  var $partElems = target ? target.closest('.participants-list') : $('.participants-list');
  var $selects = $partElems.find('select').filter(isCName);
  $selects.find('option').prop('disabled', false);

  var _iterator3 = _createForOfIteratorHelper($partElems),
      _step3;

  try {
    var _loop3 = function _loop3() {
      var partElem = _step3.value;
      $partElem = $(partElem);
      var $options = $partElem.find('select').filter(isCName).find('option');
      var inUse = $options.filter(':selected').get().map(function (e) {
        return e.value;
      });
      $optionsToDisable = $options.filter(function (_i, e) {
        return inUse.includes(e.value) && !e.selected;
      });
      $optionsToDisable.each(function (_i, e) {
        return $(e).prop('disabled', true);
      });

      if (target && target.hasClass('new')) {
        $targetPartSelect = target.find('select').filter(isCName);
        potentialDuplicate = inUse.reduce(function (acc, v, i, arr) {
          return arr.indexOf(v) !== i && acc.indexOf(v) === -1 ? acc.concat(v) : acc;
        }, []);

        if (potentialDuplicate.length) {
          $targetPartSelect.find("option[value=\"".concat(potentialDuplicate[0], "\"]")).prop('disabled', true);
          $targetPartSelect.find('option').each(function (i, e) {
            if (!inUse.includes($(e).val())) {
              $targetPartSelect.val($(e).val());
              return false;
            }
          });
        }
      }
    };

    for (_iterator3.s(); !(_step3 = _iterator3.n()).done;) {
      _loop3();
    }
  } catch (err) {
    _iterator3.e(err);
  } finally {
    _iterator3.f();
  }

  $partElems.find('select').material_select();
}

function initCNIcons() {
  var $stylizableSelects = window.$('select');
  $stylizableSelects.find('option').each(function (_i, e) {
    e.innerHTML = e.innerHTML.trim();
  });
  $stylizableSelects.material_select();
  var regExp = /~(.+)~/;
  $('.select-dropdown').each(function (_i, e) {
    var $this = $(e);
    var match = $this.val().match(regExp);
    var icon = String.fromCodePoint && match && match[1] ? String.fromCodePoint('0x' + match[1]) : '';

    if ($this.is('input')) {
      if (!match) return;
      $this.val($this.val().replace(regExp, icon));
    } else {
      $this.find('li > span').each(function (_i, e) {
        e.innerHTML = e.innerHTML.trim().replace(regExp, "<span class=\"cn-icon\" data-icon=\"".concat(icon, "\"></span>"));
      });
    }

    $this.addClass('stylized');
  });
}

handleCNSelectElems();
handleParticipantsSelectElems();
$(document).on('click', '.c-validate, .s-validate, .remove-stage, .remove-criterion', function () {
  var $this = $(this);
  $this.addClass('clicked');
});
$(document).on('click', '.c-validate-prior-check', function () {
  $btn = $(this);
  $modal = $btn.closest('.modal'); //We check if user changed critical data

  if ($modal.find('select[name*="cName"] option[selected="selected"]').val() != $modal.find('select[name*="cName"] option:selected').val() || $modal.find('input[name*="type"][checked="checked"]').val() != $modal.find('input[name*="type"]:checked').val()) {
    $('#unvalidatingOutput').modal('open');
    $('.unvalidate-btn').addClass('c-validate').removeClass('p-validate');
    $('.unvalidate-btn').removeData().data('cid', $modal.closest('.criteria-list--item').data('id')).data('sid', $modal.closest('.stage').data('id')).data('modalId', $modal.attr('id'));
  } else {
    $btn.addClass('c-validate modal-close').removeClass('c-validate-prior-check').click();
    $btn.removeClass('c-validate modal-close').addClass('c-validate-prior-check');
  }
});
$(document).on('click', '[href="#deleteStage"]', function () {
  $('.remove-stage').data('sid', $(this).data('sid'));
  $('#deleteStage').css('z-index', 9999);
});
$(document).on('click', '[href="#deleteCriterion"]', function () {
  $('.remove-criterion').data('cid', $(this).data('cid'));
  $('#deleteCriterion').css('z-index', 9999);
});
$(document).on('click', '.remove-stage', function (e) {
  $('.modal').modal('close');
  $(this).addClass('clicked');
  var removableElmt = $(this).data('sid') ? $('[data-sid="' + $(this).data('sid') + '"]').closest('.stage') : $(this).closest('.stage');
  var slider = removableElmt.find('.weight-stage-slider');
  var oldValue = Number(slider[0].noUiSlider.get());
  var sliders = $('.stage').find('.weight-stage-slider').not(slider);
  var sumVal = 0;
  var newValue = 0;
  $.each(sliders, function (key, value) {
    var nv = key != sliders.lengh - 1 ? Math.round(Number(Number($(this)[0].noUiSlider.get()) * (100 - newValue) / (100 - oldValue))) : 100 - sumVal;
    $(this)[0].nextElementSibling.innerHTML = nv + ' %';
    $(this)[0].nextElementSibling.nextElementSibling.value = nv;
    $(this)[0].noUiSlider.set(nv);
    sumVal += nv;
    $(value).closest('.stage').find('.stage-item-name').find('.s-weighting').empty().append("(".concat(nv, " %)"));
    $(value).closest('.stage').find('.stage-weight').find('.s-weighting').empty().append("".concat(nv, " %"));
  });

  if ($(this).data('sid')) {
    urlToPieces = dsurl.split('/');
    urlToPieces[urlToPieces.length - 1] = $(this).data('sid');
    tempUrl = urlToPieces.join('/');
    $.post(tempUrl, null).done(function (data) {}).fail(function (data) {
      console.log(data);
    });
  }

  if ($('.stage').length == 2) {
    $('.stage').find('.weight').hide();
    $('.stage').find('.weight').hide(); //$('.stage').find('a[href="#deleteStage"]').remove();
  }

  removableElmt.remove();
  $('.stage').last().addClass('active');
});
$(document).on('click', '.remove-criterion', function (e) {
  $(this).addClass('clicked');
  var removableElmt = $(this).data('cid') ? $('[data-cid="' + $(this).data('cid') + '"]') : $(this);
  var crtElmt = removableElmt.closest('.criteria-list--item');
  var criteriaHolder = removableElmt.closest('.criteria-list');

  if (crtElmt.find('.weight-criterion-slider').length > 0) {
    var slider = crtElmt.find('.weight-criterion-slider');
    var oldValue = Number(slider[0].noUiSlider.get());
    var sliders = criteriaHolder.find('.weight-criterion-slider').not(slider);
    var sumVal = 0;
    var newValue = 0;
    $.each(sliders, function (key, value) {
      var nv = key != sliders.lengh - 1 ? Math.round(Number(Number($(this)[0].noUiSlider.get()) * (100 - newValue) / (100 - oldValue))) : 100 - sumVal;
      $(this)[0].nextElementSibling.innerHTML = nv + ' %';
      $(this)[0].nextElementSibling.nextElementSibling.value = nv;
      $(this)[0].noUiSlider.set(nv);
      sumVal += nv;
      $(value).closest('.criteria-list--item').find('.c-weighting').empty().append("(".concat(nv, " %)"));
    });
  }

  if ($(this).data('cid')) {
    urlToPieces = dcurl.split('/');
    urlToPieces[urlToPieces.length - 1] = $(this).data('cid');
    url = urlToPieces.join('/');
    $.post(url, null).done(function (data) {
      $('.modal').modal('close');
      $('.modal-overlay').remove();
      crtElmt.remove();
    }).fail(function (data) {
      console.log(data);
      $.each($('.red-text'), function () {
        $(this).remove();
      });
      $.each(data, function (key, value) {
        if (key == "responseJSON") {
          console.log(key);
          console.log(value);
          $.each(value, function (cle, valeur) {
            $.each($('input, select'), function () {
              if ($(this).is('[name]') && $(this).attr('name').indexOf(cle) != -1) {
                $(this).after('<div class="red-text"><strong>' + valeur + '</strong></div>');
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
  complete: function complete() {
    if (!$('.remove-stage').hasClass('clicked')) {
      var $stgElmt = $(this)[0].$el.closest('.stage');
      var btnV = $stgElmt.find('.s-validate');
      var $slider = $stgElmt.find('.weight .weight-stage-slider');

      if (!btnV.hasClass('clicked')) {
        if ($stgElmt.hasClass('new')) {
          $stgElmt.remove();
          $('.stage').last().addClass('active');
        } else {
          var startCal = $stgElmt.find('.dp-start');
          var endCal = $stgElmt.find('.dp-end');
          var gStartCal = $stgElmt.find('.dp-gstart');
          var gEndCal = $stgElmt.find('.dp-gend');
          var regex = /janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre|enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre|janeiro|fevereiro|março|abril|maio|junho|julho|agosto|setembro|outubro|novembro|dezembro/g;
          var startDateTS = startCal.val() == "" ? initStartDate : parseDdmmyyyy(startCal.attr('value').replace(regex, function (match) {
            return replaceVars[match];
          }));
          var endDateTS = endCal.val() == "" ? initEndDate : parseDdmmyyyy(endCal.attr('value').replace(regex, function (match) {
            return replaceVars[match];
          }));
          var gStartDateTS = gStartCal.val() == "" ? initGStartDate : parseDdmmyyyy(gStartCal.attr('value').replace(regex, function (match) {
            return replaceVars[match];
          }));
          var gEndDateTS = gEndCal.val() == "" ? initGEndDate : parseDdmmyyyy(gEndCal.attr('value').replace(regex, function (match) {
            return replaceVars[match];
          }));
          var startDate = new Date(startDateTS);
          var endDate = new Date(endDateTS);
          var gStartDate = new Date(gStartDateTS);
          var gEndDate = new Date(gEndDateTS);
          startCal.pickadate('picker').set('select', startDate);
          endCal.pickadate('picker').set('select', endDate).set('min', startDate);
          gStartCal.pickadate('picker').set('select', gStartDate).set('min', startDate);
          gEndCal.pickadate('picker').set('select', gEndDate).set('min', gStartDate);
          prevWeight = +$slider[0].nextElementSibling.nextElementSibling.getAttribute('value');
          stgName = $stgElmt.find('input[name*="name"]').attr('value');
          stgMode = $stgElmt.find('input[name*="mode"][checked="checked"]').val();
          $slider[0].nextElementSibling.innerHTML = prevWeight + ' %';
          $slider[0].nextElementSibling.nextElementSibling.value = prevWeight;
          $slider[0].noUiSlider.set(prevWeight);
          $stgElmt.find("input[name*=\"mode\"][value = ".concat(stgMode, "]")).prop("checked", true);
          $stgElmt.find('input[name*="name"]').val(stgName);
          $stgElmt.find('.stage-item-name').find('.s-weighting').empty().append("(".concat(prevWeight, " %)"));
          $stgElmt.find('.stage-weight').find('.s-weighting').empty().append("".concat(prevWeight, " %"));
          $stgElmt.find('select[name*="visibility"]').val($stgElmt.find('select[name*="visibility"] option[selected="selected"]').val());
        }
      } else {
        var weightValue = +$stgElmt.find('.weight input').val();
        btnV.removeClass('clicked');
        $stgElmt.find('input[name*="name"]').attr('value', $stgElmt.find('input[name*="name"]').val());
        $stgElmt.find('input[name*="mode"][checked="checked"]').removeAttr("checked");
        $stgElmt.find('input[name*="mode"]:checked').attr('checked', "checked");
        $stgElmt.find('.stage-name-field').text($stgElmt.find('input[name*="name"]').val());
        $stgElmt.find('.stage-item-name').find('.s-weighting').empty().append("(".concat(weightValue, " %)"));
        $stgElmt.find('.stage-weight').find('.s-weighting').empty().append("".concat(weightValue, " %"));
        $stgElmt.removeClass('new').removeAttr('style');
        handleCNSelectElems($stgElmt);
        var $sliders = $('.stage .weight').find('.weight-stage-slider').not($slider);

        if ($sliders.length == 1) {
          $sliders.closest('.weight').show();
        }

        var oldValue = $stgElmt.find('.weight input').attr('value');
        var sumVal = 0;
        var newValue = weightValue;
        $.each($sliders, function (key, value) {
          var nv = key != sliders.lengh - 1 ? Math.round(Number(Number($(this)[0].noUiSlider.get()) * (100 - newValue) / (100 - oldValue))) : 100 - sumVal;
          $(this)[0].nextElementSibling.innerHTML = nv + ' %';
          $(this)[0].nextElementSibling.nextElementSibling.value = nv;
          $(this)[0].noUiSlider.set(nv);
          sumVal += nv;
          $(value).closest('.stage').find('.stage-item-name').find('.s-weighting').empty().append("(".concat(nv, " %)"));
          $(value).closest('.stage').find('.stage-weight').find('.s-weighting').empty().append("".concat(nv, " %"));
        });
        $stgElmt.find('.weight input').attr('value', newValue);
      }
    } else {
      $('.remove-stage').removeClass('clicked');
    }
  }
});
$('.criterion-modal').modal({
  complete: function complete() {
    var modC = $(this)[0].$el;
    var $crtElmt = modC.closest('.criteria-list--item');
    var btnV = $crtElmt.find('.c-validate');
    var slider = $crtElmt.find('.weight-criterion-slider');
    prevWeight = +slider[0].nextElementSibling.nextElementSibling.getAttribute('value');

    if (!btnV.hasClass('clicked')) {
      prevUB = $crtElmt.find('.upperbound').attr('value');
      prevLB = $crtElmt.find('.lowerbound').attr('value');
      prevType = $crtElmt.find('input[name*="type"][checked="checked"]').val();
      slider[0].nextElementSibling.innerHTML = prevWeight + ' %';
      slider[0].nextElementSibling.nextElementSibling.value = prevWeight;
      slider[0].noUiSlider.set(prevWeight);
      $crtElmt.find('input[name*="type"]').eq(prevType - 1).prop("checked", true);
      $crtElmt.find('.upperbound').val(prevUB);
      $crtElmt.find('.lowerbound').val(prevLB);
      $crtElmt.find('.c-weighting').empty().append("(".concat(prevWeight, " %)"));
      $crtElmt.find('select[name*="cName"]').val($crtElmt.find('select[name*="cName"] option[selected="selected"]').val());
    } else {
      btnV.removeClass('clicked');
      var newValue = +$crtElmt.find('.weight input').val();
      slider[0].nextElementSibling.nextElementSibling.setAttribute('value', slider[0].nextElementSibling.nextElementSibling.value);
      $crtElmt.find('.upperbound').attr('value', $crtElmt.find('.upperbound').val());
      $crtElmt.find('.lowerbound').attr('value', $crtElmt.find('.lowerbound').val());
      $crtElmt.find('input[name*="type"][checked="checked"]').removeAttr("checked");
      $crtElmt.find('input[name*="type"]:checked').attr('checked', "checked");
      $crtElmt.find('.cname').text($crtElmt.find('select[name*="cName"] option:selected').text().split(' ').slice(1).join(' '));
      $crtElmt.find('.cname').attr('data-icon', $crtElmt.find('select[name*="cName"] option:selected').attr('data-icon'));
      $crtElmt.find('.c-weighting').empty().append("(".concat(newValue, " %)"));
      var slider = $crtElmt.find('.weight-criterion-slider');
      var oldValue = prevWeight;
      var sliders = $crtElmt.closest('.stage').find('.weight-criterion-slider').not(slider);
      var sumVal = 0;
      $.each(sliders, function (key, value) {
        var nv = key != sliders.lengh - 1 ? Math.round(Number(Number($(this)[0].noUiSlider.get()) * (100 - newValue) / (100 - oldValue))) : 100 - sumVal;
        $(this)[0].nextElementSibling.innerHTML = nv + ' %';
        $(this)[0].nextElementSibling.nextElementSibling.value = nv;
        $(this)[0].nextElementSibling.nextElementSibling.setAttribute('value', nv);
        $(this)[0].noUiSlider.set(nv);
        sumVal += nv;
        $(value).closest('.criteria-list--item').find('.c-weighting').empty().append("(".concat(nv, " %)"));
      });
    }

    handleCNSelectElems($crtElmt);
    /*if(modC.find('input[type="checkbox"]').is(':checked') || modC.find('textarea').val() != ""){
        $('[href="#criterionTarget_'+s+'_'+c+'"]').addClass('lime darken-3').empty().append($('<ul class="flex-center no-margin">'+modalModifyMsg+'<i class="far fa-dot-circle" style="margin-left:10px"></i><i class="fas fa-comment-dots" style="margin-left:10px"></i></ul>'));
    } else {
        $('[href="#criterionTarget_'+s+'_'+c+'"]').removeClass('lime darken-3').empty().append($('<ul class="flex-center no-margin">'+modalSetMsg+'<i class="far fa-dot-circle" style="margin-left:10px"></i><i class="far fa-comment" style="margin-left:10px"></i></ul>'));
    }*/
  }
});
$(document).on('change', 'select[name*="cName"]', function () {
  $select = $(this);
  $materalizeSelect = $(this).closest('.criterion-name').find('input');
  var regExp = /~(.+)~/;
  var match = $select.find('option:selected').text().match(regExp);
  var icon = String.fromCodePoint && match && match[1] ? String.fromCodePoint('0x' + match[1]) : '';
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
  } // Hide or display weight input, depending on number of non-pure-comments elements


  var k = 0;
  $(this).closest('.stage').find('.criterion input[type="radio"]:checked').each(function (key, selectedRadioBtn) {
    if (selectedRadioBtn.value != 2) {
      k++;
    }

    if (k > 1) {
      return false;
    }
  });

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
    });
    $.each($(this).closest('.stage').find('.criterion').not(crtElmt), function () {
      var newValue = Math.round(Number($(this).find('.weight-criterion-slider').eq(0)[0].noUiSlider.get())); // Hide weight when only one criterion has 100 percent

      if (newValue == 100) {
        $(this).find('.scale .input-field').eq(-1).hide();
        $(this).find('.scale .input-field').not(':last').addClass('m4').removeClass('m3');
      }
    }); // Hide scale

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
      });

      if (Math.round(100 / relatedCriteria.length) != 100 / relatedCriteria.length) {
        creationVal = 100 - sumVal;
      }

      crtElmt.find('.weight-criterion-slider')[0].nextElementSibling.innerHTML = creationVal + ' %';
      crtElmt.find('.weight-criterion-slider')[0].nextElementSibling.nextElementSibling.value = creationVal;
      crtElmt.find('.weight-criterion-slider')[0].noUiSlider.set(creationVal);
    }
  } //var nbCrt = $(this).closest('.stage').find('.criterion').length;


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

  if (crtElmt.find('input[type="radio"]:checked').val() != 1) {
    crtElmt.find('.scale .input-field').hide();
  }

  if (!$(this).find('input[type="radio"]').eq(2).is(':checked') && crtElmt.find('.force-comments .col').eq(0).hasClass('m12')) {
    crtElmt.find('.force-comments .col').eq(0).removeClass('m12').addClass('m5');
  }

  if (crtElmt.find('input[type="radio"]').eq(2).is(':checked')) {
    crtElmt.find('input[name*="lowerbound"]').val(0);
    crtElmt.find('input[name*="upperbound"]').val(1);
    crtElmt.find('input[name*="step"]').val(1);
  }
});
$(document).on('click', '.s-validate', function (e) {
  e.preventDefault();
  $btn = $(this);
  $curRow = $btn.closest('.stage-modal');

  if ($curRow.find('.remove-stage-btn').length) {
    $curRow.find('.remove-stage-btn').removeClass('remove-stage-btn').addClass('modal-trigger').attr('href', '#deleteStage');
  }

  $curRow.find('.red-text').remove();
  sid = $curRow.closest('.stage').attr('data-id');
  inputName = $curRow.find('input[name*="name"]').val();
  weightVal = $curRow.find('input[name*="activeWeight"]').val();
  isDefiniteDates = $curRow.find('[name*="definiteDates"]').is(':checked');
  startdate = $curRow.find('[name*="startdate"]').val();
  enddate = $curRow.find('[name*="enddate"]').val();
  gstartdate = $curRow.find('[name*="gstardate"]').val();
  genddate = $curRow.find('[name*="genddate"]').val();
  dPeriod = $curRow.find('[name*="dPeriod"]').val();
  dFrequency = $curRow.find('select[name*="dFrequency"] option:selected').val();
  dOrigin = $curRow.find('select[name*="dOrigin"] option:selected').val();
  fPeriod = $curRow.find('[name*="fPeriod"]').val();
  fFrequency = $curRow.find('select[name*="fFrequency"] option:selected').val();
  fOrigin = $curRow.find('select[name*="fOrigin"] option:selected').val();
  visibility = $curRow.find('select[name*="visibility"] option:selected').val();
  mode = $curRow.find('[name*="mode"]:checked').val();
  var $form = $('.s-form form');
  $form.find('input, select').removeAttr('disabled');
  $form.find('[name*="activeWeight"]').val(weightVal);
  $form.find('[name*="name"]').val(inputName);
  $form.find('[name*="definiteDates"]').prop('checked', isDefiniteDates);
  $form.find('[name*="[gstartdate]"]').val(gstartdate);
  $form.find('[name*="[genddate]"]').val(genddate);
  $form.find('[name*="[startdate]"]').val(startdate);
  $form.find('[name*="[enddate]"]').val(enddate);
  $form.find('[name*="dPeriod"]').val(dPeriod);
  $form.find('select[name*="dFrequency"]').val(dFrequency);
  $form.find('select[name*="dOrigin"]').val(dOrigin);
  $form.find('[name*="fPeriod"]').val(fPeriod);
  $form.find('select[name*="fFrequency"]').val(fFrequency);
  $form.find('select[name*="fOrigin"]').val(fOrigin);
  $form.find('select[name*="visibility"]').val(visibility);
  $form.find('[name*="mode"]').eq(mode).prop('checked', true);
  var urlToPieces = vsurl.split('/');
  urlToPieces[urlToPieces.length - 1] = sid;
  var url = urlToPieces.join('/');
  var tmp = $form.serialize().split('&');
  j = $('.dp-start').index($curRow.find('.dp-start'));

  for (i = 0; i < tmp.length; i++) {
    if (tmp[i].indexOf('startdate') != -1 && tmp[i].indexOf('gstartdate') == -1) {
      tmp[i] = tmp[i].split('=');
      tmp[i + 1] = tmp[i + 1].split('=');
      tmp[i + 2] = tmp[i + 2].split('=');
      tmp[i + 3] = tmp[i + 3].split('=');
      startdateDDMMYYYY = $($("#" + $('.dp-start')[j].id)).pickadate('picker').get('select', 'dd/mm/yyyy');
      enddateDDMMYYYY = $($("#" + $('.dp-end')[j].id)).pickadate('picker').get('select', 'dd/mm/yyyy');
      gstartdateDDMMYYYY = $($("#" + $('.dp-gstart')[j].id)).pickadate('picker').get('select', 'dd/mm/yyyy');
      genddateDDMMYYYY = $($("#" + $('.dp-gend')[j].id)).pickadate('picker').get('select', 'dd/mm/yyyy');
      tmp[i][1] = startdateDDMMYYYY;
      tmp[i + 1][1] = enddateDDMMYYYY;
      tmp[i + 2][1] = gstartdateDDMMYYYY;
      tmp[i + 3][1] = genddateDDMMYYYY;
      tmp[i] = tmp[i].join('=');
      tmp[i + 1] = tmp[i + 1].join('=');
      tmp[i + 2] = tmp[i + 2].join('=');
      tmp[i + 3] = tmp[i + 3].join('=');
      $increment = true;
    } else {
      $increment = false;
    }

    if ($increment) {
      j++;
    }
  }

  mSerializedForm = tmp.join('&');
  $li = $(this).closest('li.stage').find('a.insert-survey-btn');
  $.post(url, mSerializedForm).done(function (data) {
    var startCal = $curRow.find('.dp-start');
    var endCal = $curRow.find('.dp-end');
    var gStartCal = $curRow.find('.dp-gstart');
    var gEndCal = $curRow.find('.dp-gend');
    startCal.attr('value', startdateDDMMYYYY);
    endCal.attr('value', enddateDDMMYYYY);
    gStartCal.attr('value', gstartdateDDMMYYYY);
    gEndCal.attr('value', genddateDDMMYYYY);
    startdateDDMM = startdateDDMMYYYY.split('/').slice(0, 2).join('/');
    enddateDDMM = enddateDDMMYYYY.split('/').slice(0, 2).join('/');
    gstartdateDDMM = gstartdateDDMMYYYY.split('/').slice(0, 2).join('/');
    genddateDDMM = genddateDDMMYYYY.split('/').slice(0, 2).join('/');
    stageDatesText = startdateDDMM == enddateDDMM ? startdateDDMM : startdateDDMM + ' - ' + enddateDDMM;
    gradingDatesText = gstartdateDDMM == genddateDDMM ? gstartdateDDMM : gstartdateDDMM + ' - ' + genddateDDMM;
    $curRow.closest('.stage').find('.stage-dates').contents().last().replaceWith(stageDatesText);
    $curRow.closest('.stage').find('.grading-dates').contents().last().replaceWith(gradingDatesText);
    var href = $li.attr('href').replace('0', data['sid']);
    $li.attr('href', href);
    $removeBtn = $curRow.find('[href="#deleteStage"]');
    $removeBtn.attr('data-sid', data.sid);
    $curRow.closest('.stage').attr('data-id', data.sid);
    $curRow.modal('close');
  }).fail(function (data) {
    errorHtmlMsg = '';
    $(data.responseJSON).each(function (i, e) {
      return errorHtmlMsg += '<strong>' + Object.values(e)[0] + '</strong>';
    });
    $curRow.find('.first-data-row').after(
    /*html*/
    "\n            <div class=\"red-text\">\n              ".concat(errorHtmlMsg, "\n            </div>\n        "));
  });
});
$(document).on('click', '.c-validate', function (e) {
  e.preventDefault();
  $btn = $(this);
  $deleteBtn = $btn.closest('.modal').find('[href="#deleteCriterion"]');
  $curRow = $btn.hasClass('unvalidate-btn') ? $(".criterion-modal[id=\"".concat($(this).data('modalId'), "\"]")) : $(this).closest('.criterion-modal');
  $crtElmt = $curRow.closest('.criteria-list--item'); //$crtElmt = $(this).closest('.criterion');

  $curRow.find('.red-text').remove();
  sid = $btn.hasClass('unvalidate-btn') ? $btn.data('sid') : $(this).closest('.stage').data('id');
  cid = $(this).data('cid');
  crtVal = $curRow.find('select[name*="cName"] option:selected').val();
  typeVal = $curRow.find('[name*="type"]:checked').val();
  isRequiredComment = $curRow.find('[type*="forceCommentCompare"]:checked').val();
  commentSign = $curRow.find('select[name*="forceCommentSign"] option:selected').val();
  commentValue = $curRow.find('[name*="forceCommentValue"]').val();
  lowerbound = $curRow.find('[name*="lowerbound"]').val();
  upperbound = $curRow.find('[name*="upperbound"]').val();
  step = $curRow.find('[name*="step"]').val();
  weight = +$curRow.find('.weight input').val();
  var $form = $('.c-form form');
  var urlToPieces = vcurl.split('/');
  urlToPieces[urlToPieces.length - 4] = sid;
  urlToPieces[urlToPieces.length - 1] = cid;
  var url = urlToPieces.join('/');
  $form.find('[name*="cName"]').val(crtVal);
  $form.find('[name*="type"]').eq(typeVal - 1).prop('checked', true);
  $form.find('[name*="forceCommentCompare"]').prop('checked', isRequiredComment);
  $form.find('[name*="forceCommentSign"]').val(commentSign);
  $form.find('[name*="forceCommentValue"]').val(commentValue);
  $form.find('[name*="lowerbound"]').val(lowerbound);
  $form.find('[name*="upperbound"]').val(upperbound);
  $form.find('[name*="step"]').val(step);
  $form.find('[name*="weight"]').val(weight);
  $.post(url, $form.serialize()).done(function (data) {
    $btn.attr('data-cid', data.cid);
    $deleteBtn.attr('data-cid', data.cid);
    $curRow.modal('close');
  }).fail(function (data) {
    var errorHtmlMsg = '';
    $(data.responseJSON).each(function (i, e) {
      return errorHtmlMsg += (_readOnlyError("errorHtmlMsg"), '<strong>' + Object.values(e)[0] + '</strong>');
    });
    $curRow.find('.first-data-row').after(
    /*html*/
    "\n          <div class=\"red-text\">\n              ".concat(errorHtmlMsg, "\n          </div>\n      "));
  });
});
$(document).on('click', '.criteria-tab, .survey-tab', function () {
  if ($(this).hasClass('survey-tab') && $(this).find('a').hasClass('active') && $(this).closest('section').find('.criteria-list .criteria-list--item').length > 0) {
    $('#changeOutputType').modal('open');
    $('.change-output-btn').data('sid', $(this).closest('.stage').data('id'));
  }
});
$(document).on('click', '.change-output-btn', function () {
  var urlToPieces = courl.split('/');
  var sid = $(this).data('sid');
  urlToPieces[urlToPieces.length - 2] = sid;
  url = urlToPieces.join('/');
  $.post(url, null).done(function (data) {
    var $stgElmt = $(".stage[data-id=\"".concat(sid, "\"]"));

    if (!data.surveyDeletion) {
      $crtHolder = $stgElmt.find('.stage-container .criteria .criteria-list');
      $crtHolder.find('.criteria-list--item').each(function (i, e) {
        $(e).remove();
      });
    } else {}
  }).fail(function (data) {});
});
$('.stage-add .stage-fresh-new-btn').on('click', function () {
  var nbStages = $('.stage').length;
  var proto = $(document).find('template.stages-list--item__proto')[0];
  var protoHtml = proto.innerHTML.trim();
  var newProtoHtml = protoHtml.replace(/__name__/g, nbStages).replace(/__stgNb__/g, nbStages).replace(/__realStgNb__/g, nbStages + 1);
  var $stgElmt = $(newProtoHtml);
  $stgElmt.find('.modal').modal();
  $stgElmt.find('.tooltipped').tooltip();
  $stgElmt.find('.tabs').tabs();
  $('.stage-add').before($stgElmt);
  toggleStage($stgElmt); // Initializing stage default values

  var initStartDate = new Date(Date.now());
  var initEndDate = new Date(Date.now() + 15 * 24 * 60 * 60 * 1000);
  var initGStartDate = new Date(Date.now());
  var initGEndDate = new Date(Date.now() + 30 * 24 * 60 * 60 * 1000);
  $stgElmt.find('.dp-start, .dp-end, .dp-gstart, .dp-gend').each(function () {
    $(this).pickadate();
  });
  $stgElmt.find('.dp-start').pickadate('picker').set('select', initStartDate);
  $stgElmt.find('.dp-end').pickadate('picker').set('select', initEndDate).set('min', initStartDate);
  $stgElmt.find('.dp-gstart').pickadate('picker').set('select', initGStartDate).set('min', initStartDate);
  $stgElmt.find('.dp-gend').pickadate('picker').set('select', initGEndDate).set('min', initGStartDate);
  $stgElmt.find('input[name*="period"]').val(15);
  $stgElmt.find('select[name*="frequency"]').val('D');
  $stgElmt.find('[name*="mode"]').eq(0).prop('checked', true);
  var slider = $stgElmt.find('.weight-stage-slider');
  var weight = $stgElmt.find('.weight'); //Removing '%' text added by PercentType

  weight[0].removeChild(weight[0].lastChild);
  var creationVal = Math.round(100 / (nbStages + 1));
  noUiSlider.create(slider[0], {
    start: creationVal,
    step: 1,
    connect: [true, false],
    range: {
      'min': 0,
      'max': 100
    }
  });
  slider[0].nextElementSibling.innerHTML = creationVal + ' %';
  slider[0].nextElementSibling.nextElementSibling.value = creationVal;
  slider[0].noUiSlider.on('slide', function (values, handle) {
    slider[0].nextElementSibling.innerHTML = Number(values[handle]) + ' %';
    slider[0].nextElementSibling.nextElementSibling.value = values[handle];
  });
  handleCNSelectElems($stgElmt);
  $stgElmt.find('.stage-modal').modal({
    complete: function complete() {
      if (!$('.remove-stage').hasClass('clicked')) {
        var btnV = $stgElmt.find('.s-validate');
        var $slider = $stgElmt.find('.weight .weight-stage-slider');
        var $sliders = $('.stage .weight').find('.weight-stage-slider').not(slider);

        if (!btnV.hasClass('clicked')) {
          if ($stgElmt.hasClass('new')) {
            $stgElmt.remove();
            $('.stage').last().addClass('active');
          } else {
            var startCal = $stgElmt.find('.dp-start');
            var endCal = $stgElmt.find('.dp-end');
            var gStartCal = $stgElmt.find('.dp-gstart');
            var gEndCal = $stgElmt.find('.dp-gend');
            var regex = /janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre|enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre|janeiro|fevereiro|março|abril|maio|junho|julho|agosto|setembro|outubro|novembro|dezembro/g;
            var startDateTS = startCal.val() == "" ? initStartDate : parseDdmmyyyy(startCal.attr('value').replace(regex, function (match) {
              return replaceVars[match];
            }));
            var endDateTS = endCal.val() == "" ? initEndDate : parseDdmmyyyy(endCal.attr('value').replace(regex, function (match) {
              return replaceVars[match];
            }));
            var gStartDateTS = gStartCal.val() == "" ? initGStartDate : parseDdmmyyyy(gStartCal.attr('value').replace(regex, function (match) {
              return replaceVars[match];
            }));
            var gEndDateTS = gEndCal.val() == "" ? initGEndDate : parseDdmmyyyy(gEndCal.attr('value').replace(regex, function (match) {
              return replaceVars[match];
            }));
            var startDate = new Date(startDateTS);
            var endDate = new Date(endDateTS);
            var gStartDate = new Date(gStartDateTS);
            var gEndDate = new Date(gEndDateTS);
            startCal.pickadate('picker').set('select', startDate);
            endCal.pickadate('picker').set('select', endDate).set('min', startDate);
            gStartCal.pickadate('picker').set('select', gStartDate).set('min', startDate);
            gEndCal.pickadate('picker').set('select', gEndDate).set('min', gStartDate);
            prevWeight = +$slider[0].nextElementSibling.nextElementSibling.getAttribute('value');
            stgName = $stgElmt.find('input[name*="name"]').attr('value');
            stgMode = $stgElmt.find('input[name*="mode"][checked="checked"]').val();
            $slider[0].nextElementSibling.innerHTML = prevWeight + ' %';
            $slider[0].nextElementSibling.nextElementSibling.value = prevWeight;
            $slider[0].noUiSlider.set(prevWeight);
            $stgElmt.find("input[name*=\"mode\"][value = ".concat(stgMode, "]")).prop("checked", true);
            $stgElmt.find('input[name*="name"]').val(stgName);
            $stgElmt.find('.stage-item-name').find('.s-weighting').empty().append("(".concat(prevWeight, " %)"));
            $stgElmt.find('.stage-weight').find('.s-weighting').empty().append("".concat(prevWeight, " %"));
            $stgElmt.find('select[name*="visibility"]').val($stgElmt.find('select[name*="visibility"] option[selected="selected"]').val());
          }
        } else {
          btnV.removeClass('clicked');
          var weightValue = +$stgElmt.find('.weight input').val();
          $stgElmt.find('input[name*="name"]').attr('value', $stgElmt.find('input[name*="name"]').val());
          $stgElmt.find('input[name*="mode"][checked="checked"]').removeAttr("checked");
          $stgElmt.find('input[name*="mode"]:checked').attr('checked', "checked");
          $stgElmt.find('.stage-name-field').text($stgElmt.find('input[name*="name"]').val());
          $stgElmt.find('.stage-item-name').find('.s-weighting').empty().append("(".concat(weightValue, " %)"));
          $stgElmt.find('.stage-weight').find('.s-weighting').empty().append("".concat(weightValue, " %"));
          handleCNSelectElems($stgElmt);

          if (!$stgElmt.find('[id*="type--criteria"], [id*="type--survey"]').hasClass('active')) {
            $stgElmt.find('.survey-tab a').click();
            setTimeout(function () {
              $stgElmt.find('.criteria-tab a').click();
            }, 100);
          }

          var $slider = $stgElmt.find('.weight-stage-slider');
          var $sliders = $('.stage .weight').find('.weight-stage-slider').not(slider);

          if ($sliders.length == 1) {
            $sliders.closest('.weight').show();
          }

          var oldValue = $stgElmt.hasClass('new') ? 0 : $stgElmt.find('.weight input').attr('value');
          var sumVal = 0;
          var newValue = weightValue;
          $.each($sliders, function (key, value) {
            var nv = key != $sliders.length - 1 ? Math.round(Number(Number($(this)[0].noUiSlider.get()) * (100 - newValue) / (100 - oldValue))) : 100 - sumVal - newValue;
            $(this)[0].nextElementSibling.innerHTML = nv + ' %';
            $(this)[0].nextElementSibling.nextElementSibling.value = nv;
            $(this)[0].nextElementSibling.nextElementSibling.setAttribute('value', nv);
            $(this)[0].noUiSlider.set(nv);
            sumVal += nv;
            $(value).closest('.stage').find('.stage-item-name').find('.s-weighting').empty().append("(".concat(nv, " %)"));
            $(value).closest('.stage').find('.stage-weight').find('.s-weighting').empty().append("".concat(nv, " %"));
          });
          $stgElmt.find('.weight input').attr('value', newValue);
          $stgElmt.removeClass('new').removeAttr('style');
        }
      } else {
        $('.remove-stage').removeClass('clicked');
      }
    }
  });
  $stgElmt.find('.stage-modal').modal('open');
});
$(document).on('change', '.date-switch input', function () {
  $(this).is(':checked') ? ($(this).closest('.row').find('.period-freq-input').hide(), $(this).closest('.row').find('.dates-input').show()) : ($(this).closest('.row').find('.period-freq-input').show(), $(this).closest('.row').find('.dates-input').hide());
});
$(document).on('click', '.activity-name:not(.edit) > .btn-edit', function () {
  $('.activity-name').addClass('edit');
});
$(document).on('click', '.activity-name.edit > .btn-edit', function () {
  var name = $('.custom-input').text().trim();
  var params = {
    name: name
  };

  if (name != $('.activity-name input').attr('value')) {
    $.post(vnurl, params).fail(function (data) {
      $('#duplicateElementName').modal('open');
    }).done(function (data) {
      $('.activity-name input').attr('value', name);
      $('.activity-name .show').empty().append(name);
      $('.activity-name').removeClass('edit');
    });
  } else {
    $('.activity-name').removeClass('edit');
  }
});

/***/ })

},[["./web/js/activity_stages.js","runtime"]]]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi93ZWIvanMvYWN0aXZpdHlfc3RhZ2VzLmpzIl0sIm5hbWVzIjpbIlNUQUdFX0xJU1QiLCJTVEFHRV9JVEVNIiwiUEFSVElDSVBBTlRTX0lURU0iLCJTVEFHRV9NT0RBTCIsIlNUQUdFX05BTUVfSU5QVVQiLCJTVEFHRV9MQUJFTCIsIkNSSVRFUklPTl9NT0RBTCIsIkNSSVRFUklPTl9OQU1FX1NFTEVDVCIsIkNSSVRFUklPTl9MQUJFTCIsInBhcnRpY2lwYXRpb25UeXBlcyIsIiRzdGFnZUxpc3QiLCIkIiwiJHN0YWdlQWRkSXRlbSIsImZpbmQiLCIkYWRkU3RhZ2VCdG4iLCJwcm90byIsImRvY3VtZW50Iiwic2V0VGltZW91dCIsImxlbmd0aCIsIm1vZGFsIiwiZWFjaCIsInRleHQiLCJwcmV2IiwicmVtb3ZlIiwiY3NzIiwiYWRkQ2xhc3MiLCJsZyIsIiRzdGFnZUNvbGxlY3Rpb25Ib2xkZXIiLCJzbGlkZXJzIiwiJGJ0biIsImNsb3Nlc3QiLCJ0aGVTbGlkZXJzIiwidG9BcnJheSIsIkFycmF5IiwiZnJvbSIsInF1ZXJ5U2VsZWN0b3JBbGwiLCJuZXdTbGlkZXJzIiwiZmlsdGVyIiwiZSIsImNsYXNzTGlzdCIsImNvbnRhaW5zIiwid2VpZ2h0RWxtdCIsInBhcmVudEVsZW1lbnQiLCJyZW1vdmVDaGlsZCIsImxhc3RDaGlsZCIsImlucHV0IiwicXVlcnlTZWxlY3RvciIsImxhYmVsIiwic2xpZGVDYWxsYmFjayIsInZhbHVlcyIsImhhbmRsZSIsImlubmVySFRNTCIsIm5leHRFbGVtZW50U2libGluZyIsInZhbHVlIiwiaGFzQ2xhc3MiLCJlbXB0eSIsImFwcGVuZCIsIk51bWJlciIsIm5vVWlTbGlkZXIiLCJjcmVhdGUiLCJzdGFydCIsInN0ZXAiLCJjb25uZWN0IiwicmFuZ2UiLCJtaW4iLCJtYXgiLCJvbiIsImFkZCIsInBhcnNlRGRtbXl5eXkiLCJzdHIiLCJwYXJ0cyIsInNwbGl0IiwiRGF0ZSIsInVwZGF0ZURhdGVwaWNrZXJzIiwiayIsImluZGV4IiwibmJTdGFnZXMiLCJyZXBsYWNlVmFycyIsInJlZ2V4IiwiY3VycmVudCIsIiR0ZXJtaW5hbFN0YWdlRW5kQ2FsIiwic3RhcnRDYWwiLCJlbmRDYWwiLCJnU3RhcnRDYWwiLCJnRW5kQ2FsIiwic3RhcnREYXRlVFMiLCJ2YWwiLCJub3ciLCJyZXBsYWNlIiwibWF0Y2giLCJlbmREYXRlVFMiLCJnU3RhcnREYXRlVFMiLCJnRW5kRGF0ZVRTIiwic3RhcnREYXRlIiwiZW5kRGF0ZSIsImdTdGFydERhdGUiLCJnRW5kRGF0ZSIsInBpY2thZGF0ZSIsInNldCIsIiRsYXN0U3RhZ2VFbmRDYWwiLCIkYWRkZWRTdGFnZVN0YXJ0Q2FsIiwiJGFkZGVkU3RhZ2VFbmRDYWwiLCIkbGFzdFN0YWdlV2VpZ2h0IiwiJGFkZGVkU3RhZ2VXZWlnaHQiLCIkYWRkZWRTdGFnZUdTdGFydENhbCIsIiRhZGRlZFN0YWdlR0VuZENhbCIsImFkZGVkU3RhZ2VTdGFydGRhdGUiLCJnZXQiLCJwaWNrIiwiYWRkZWRTdGFnZUVuZGRhdGUiLCJhZGRlZFN0YWdlR1N0YXJ0ZGF0ZSIsImFkZGVkU3RhZ2VHRW5kZGF0ZSIsIiR1cHN0cmVhbVN0YWdlV2VpZ2h0IiwiJHJlbW92ZWRTdGFnZVdlaWdodCIsInBhcnNlRmxvYXQiLCJleHRlbmQiLCJmbiIsImRlZmF1bHRzIiwibW9udGhzRnVsbCIsIm1vbnRoc1Nob3J0Iiwid2Vla2RheXNGdWxsIiwid2Vla2RheXNTaG9ydCIsInRvZGF5IiwiY2xlYXIiLCJjbG9zZSIsImZpcnN0RGF5Iiwic2VsZWN0TW9udGhzIiwic2VsZWN0WWVhcnMiLCJ5ZWFyZW5kIiwiY2xvc2VPblNlbGVjdCIsImVuZERhdGVzIiwiZGF0YSIsInNlbGVjdGVkRGF0ZSIsIiRHU3RhcnRDYWwiLCIkR0VuZENhbCIsImNsYXNzUHJlZml4IiwiYXR0ciIsInNsaWNlIiwiJHJlbGF0ZWRFbmRDYWwiLCJHU3RhcnREYXRlIiwiaG92ZXIiLCJ1cmxUb1BpZWNlcyIsImRwc3VybCIsInVybCIsImpvaW4iLCJwb3N0IiwiZG9uZSIsImxvY2F0aW9uIiwicmVsb2FkIiwiZmFpbCIsImNvbnNvbGUiLCJsb2ciLCIkdGhpcyIsInRvZ2dsZVN0YWdlIiwiJHN0YWdlSXRlbSIsIiRwYXJ0aWNpcGFudEl0ZW0iLCIkc2VjdGlvbiIsIiRjcml0ZXJpYUxpc3QiLCJjaGlsZHJlbiIsIiRjcml0ZXJpYSIsIm5iQ3JpdGVyaWEiLCJwcm90b0h0bWwiLCJ0cmltIiwibmV3UHJvdG9IdG1sIiwiJGNydEVsbXQiLCJ0b29sdGlwIiwiZXEiLCJjaGVja2VkIiwibGFzdCIsImJlZm9yZSIsInNsaWRlciIsIndlaWdodCIsIiRyZWxhdGVkQ3JpdGVyaWEiLCJjcmVhdGlvblZhbCIsIk1hdGgiLCJyb3VuZCIsInN1bVZhbCIsIm5leHQiLCJoaWRlIiwiaGFuZGxlQ05TZWxlY3RFbGVtcyIsImNvbXBsZXRlIiwibW9kQyIsIiRlbCIsImJ0blYiLCJwcmV2V2VpZ2h0IiwiZ2V0QXR0cmlidXRlIiwicHJldlVCIiwicHJldkxCIiwicHJldlR5cGUiLCJwcm9wIiwicmVtb3ZlQ2xhc3MiLCJ3ZWlnaHRWYWx1ZSIsInNldEF0dHJpYnV0ZSIsInJlbW92ZUF0dHIiLCJvbGRWYWx1ZSIsIm5vdCIsInNob3ciLCJuZXdWYWx1ZSIsImtleSIsIm52Iiwic3RvcFByb3BhZ2F0aW9uIiwiJG1vZGFsRGVsZXRpb25CdG4iLCJkcHVybCIsInJlbW92ZVBhcnRpY2lwYW50VXJsIiwiJHVzZXJWYWwiLCIkcGFydGljaXBhbnRMaXN0IiwiaSIsInBUeXBlIiwiJHBhcnRpY2lwYW50c0xpc3QiLCIkbmV3UHJvdG9IdG1sIiwiaGFuZGxlUGFydGljaXBhbnRzU2VsZWN0RWxlbXMiLCIkdXNlclNlbGVjdCIsIiR1c2VyTmFtZSIsIiR1c2VySXNMZWFkZXIiLCJ1c2VySXNMZWFkZXIiLCIkdXNlclBhcnRpY2lwYW50VHlwZSIsInVzZXJQYXJ0aWNpcGFudFR5cGUiLCIkcG90ZW50aWFsRGlmZmVyZW50TGVhZGVyIiwiaW5BcnJheSIsInVzclJvbGUiLCJ1c3JJZCIsInZhbGlkYXRlUGFydGljaXBhbnRVcmwiLCJodG1sIiwiZmlyc3QiLCJwYXJhbXMiLCJ1c2VyIiwidHlwZSIsInByZWNvbW1lbnQiLCJsZWFkZXIiLCJyZW1vdmVEYXRhIiwiJGNsaWNraW5nQnRuIiwiY2xpY2siLCJlaWQiLCJjYW5TZXR1cCIsIndpbmRvdyIsInBpY3R1cmUiLCIkcGFydEVsbXQiLCIkYmFkZ2VzIiwiaXMiLCIkbW9kYWwiLCIkc3RhZ2VMYWJlbCIsIiRlIiwicHJldmVudERlZmF1bHQiLCJ3Z3RFbG10cyIsIndndEhpZGRlbkVsbXQiLCJwdXNoIiwic3VibWl0IiwiJGxvc2luZ093bmVyc2hpcFBhcnQiLCJ0YXJnZXQiLCJpc0NOYW1lIiwiX2kiLCJ0ZXN0IiwiaWQiLCIkY3J0RWxlbXMiLCIkc2VsZWN0cyIsImNydEVsZW0iLCIkY3J0RWxlbSIsIiRvcHRpb25zIiwiaW5Vc2UiLCJtYXAiLCIkb3B0aW9uc1RvRGlzYWJsZSIsImluY2x1ZGVzIiwic2VsZWN0ZWQiLCIkdGFyZ2V0UGFydFNlbGVjdCIsInBvdGVudGlhbER1cGxpY2F0ZSIsInJlZHVjZSIsImFjYyIsInYiLCJhcnIiLCJpbmRleE9mIiwiY29uY2F0IiwiaW5pdENOSWNvbnMiLCJoZWlnaHQiLCJ3aWR0aCIsIm1hcmdpbiIsImNvbG9yIiwiJHBhcnRFbGVtcyIsInBhcnRFbGVtIiwiJHBhcnRFbGVtIiwibWF0ZXJpYWxfc2VsZWN0IiwiJHN0eWxpemFibGVTZWxlY3RzIiwicmVnRXhwIiwiaWNvbiIsIlN0cmluZyIsImZyb21Db2RlUG9pbnQiLCJyZW1vdmFibGVFbG10IiwibGVuZ2giLCJkc3VybCIsInRlbXBVcmwiLCJjcnRFbG10IiwiY3JpdGVyaWFIb2xkZXIiLCJkY3VybCIsImNsZSIsInZhbGV1ciIsImFmdGVyIiwiJHN0Z0VsbXQiLCIkc2xpZGVyIiwiaW5pdFN0YXJ0RGF0ZSIsImluaXRFbmREYXRlIiwiaW5pdEdTdGFydERhdGUiLCJpbml0R0VuZERhdGUiLCJzdGdOYW1lIiwic3RnTW9kZSIsIiRzbGlkZXJzIiwiJHNlbGVjdCIsIiRtYXRlcmFsaXplU2VsZWN0IiwiY3J0SW5kZXgiLCJzZWxlY3RlZFJhZGlvQnRuIiwic2VsZWN0ZWRTbGlkZXJJbmRleCIsInJlbGF0ZWRDcml0ZXJpYSIsImZvcmNlQ29tbWVudE1zZ18wIiwiZm9yY2VDb21tZW50TXNnXzIiLCJmb3JjZUNvbW1lbnRNc2dfMSIsIiRjdXJSb3ciLCJzaWQiLCJpbnB1dE5hbWUiLCJ3ZWlnaHRWYWwiLCJpc0RlZmluaXRlRGF0ZXMiLCJzdGFydGRhdGUiLCJlbmRkYXRlIiwiZ3N0YXJ0ZGF0ZSIsImdlbmRkYXRlIiwiZFBlcmlvZCIsImRGcmVxdWVuY3kiLCJkT3JpZ2luIiwiZlBlcmlvZCIsImZGcmVxdWVuY3kiLCJmT3JpZ2luIiwidmlzaWJpbGl0eSIsIm1vZGUiLCIkZm9ybSIsInZzdXJsIiwidG1wIiwic2VyaWFsaXplIiwiaiIsInN0YXJ0ZGF0ZURETU1ZWVlZIiwiZW5kZGF0ZURETU1ZWVlZIiwiZ3N0YXJ0ZGF0ZURETU1ZWVlZIiwiZ2VuZGRhdGVERE1NWVlZWSIsIiRpbmNyZW1lbnQiLCJtU2VyaWFsaXplZEZvcm0iLCIkbGkiLCJzdGFydGRhdGVERE1NIiwiZW5kZGF0ZURETU0iLCJnc3RhcnRkYXRlRERNTSIsImdlbmRkYXRlRERNTSIsInN0YWdlRGF0ZXNUZXh0IiwiZ3JhZGluZ0RhdGVzVGV4dCIsImNvbnRlbnRzIiwicmVwbGFjZVdpdGgiLCJocmVmIiwiJHJlbW92ZUJ0biIsImVycm9ySHRtbE1zZyIsInJlc3BvbnNlSlNPTiIsIk9iamVjdCIsIiRkZWxldGVCdG4iLCJjaWQiLCJjcnRWYWwiLCJ0eXBlVmFsIiwiaXNSZXF1aXJlZENvbW1lbnQiLCJjb21tZW50U2lnbiIsImNvbW1lbnRWYWx1ZSIsImxvd2VyYm91bmQiLCJ1cHBlcmJvdW5kIiwidmN1cmwiLCJjb3VybCIsInN1cnZleURlbGV0aW9uIiwiJGNydEhvbGRlciIsInRhYnMiLCJuYW1lIiwidm51cmwiXSwibWFwcGluZ3MiOiI7Ozs7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Q0FBRCxDQUFvQm1MLFdBQXBCLENBQWdDLE1BQWhDO0FBQ0g7QUFDRixDQWhCRCxFIiwiZmlsZSI6ImFjdGl2aXR5X3N0YWdlcy5qcyIsInNvdXJjZXNDb250ZW50IjpbIi8vIC8qKiBAdHlwZSB7c3RyaW5nW119ICovXG4vLyBjb25zdCB1c2VyUGljcyA9IEpTT04ucGFyc2UoZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3VzZXItcGljcycpLmlubmVySFRNTCk7XG4vLyBjb25zdCBkZWZhdWx0VXNlclBpYyA9IHVzZXJQaWNzWzBdO1xuXG4vLyAvKipcbi8vICAqIHNldHMgdXNlciBwaWN0dXJlIGluIHBhcnRpY2lwYW50cyBsaXN0XG4vLyAgKiBAcGFyYW0ge0pRdWVyeX0gJGltZyBpbWcgZWxlbWVudFxuLy8gICogQHBhcmFtIHtudW1iZXJ9IHVzZXJJZCB1c2VyIGlkXG4vLyAgKi9cbi8vIGZ1bmN0aW9uIHNldFVzZXJQaWMoJGltZywgdXNlcklkKSB7XG4vLyAgIGlmICghKCRpbWcgaW5zdGFuY2VvZiBqUXVlcnkpKSB0aHJvdyBuZXcgVHlwZUVycm9yKCdtdXN0IGJlIGEganF1ZXJ5IGluc3RhbmNlJyk7XG5cbi8vICAgY29uc3QgcGljdHVyZVVybCA9IHVzZXJJZCAmJiB1c2VyUGljc1t1c2VySWRdO1xuLy8gICBjb25zdCBleGlzdHMgPSAnc3RyaW5nJyA9PT0gdHlwZW9mIHBpY3R1cmVVcmw7XG5cbi8vICAgJGltZy5wcm9wKCdzcmMnLCBleGlzdHMgPyBwaWN0dXJlVXJsIDogZGVmYXVsdFVzZXJQaWMpO1xuLy8gfVxuXG5cbmNvbnN0IFNUQUdFX0xJU1QgPSAnb2wuc3RhZ2VzJztcbmNvbnN0IFNUQUdFX0lURU0gPSAnbGkuc3RhZ2UtZWxlbWVudCc7XG5jb25zdCBQQVJUSUNJUEFOVFNfSVRFTSA9ICdsaS5wYXJ0aWNpcGFudHMtbGlzdC0taXRlbSc7XG5jb25zdCBTVEFHRV9NT0RBTCA9ICcuc3RhZ2UtbW9kYWwnO1xuY29uc3QgU1RBR0VfTkFNRV9JTlBVVCA9ICdpbnB1dC5zdGFnZS1uYW1lLWlucHV0JztcbmNvbnN0IFNUQUdFX0xBQkVMID0gJy5zdGFnZS1sYWJlbCc7XG5jb25zdCBDUklURVJJT05fTU9EQUwgPSAnLmNyaXRlcmlvbi1tb2RhbCc7XG5jb25zdCBDUklURVJJT05fTkFNRV9TRUxFQ1QgPSAnc2VsZWN0LmNyaXRlcmlvbi1uYW1lLXNlbGVjdCc7XG5jb25zdCBDUklURVJJT05fTEFCRUwgPSAnLmNyaXRlcmlvbi1sYWJlbCc7XG5cbmNvbnN0IHBhcnRpY2lwYXRpb25UeXBlcyA9IHsgJy0xJzogJ3AnLCAnMCc6ICd0JywgJzEnOiAnYScgfTtcbmNvbnN0ICRzdGFnZUxpc3QgPSAkKFNUQUdFX0xJU1QpO1xuY29uc3QgJHN0YWdlQWRkSXRlbSA9ICRzdGFnZUxpc3QuZmluZCgnPiBsaS5zdGFnZS1hZGQnKTtcbmNvbnN0ICRhZGRTdGFnZUJ0biA9ICRzdGFnZUFkZEl0ZW0uZmluZCgnLnN0YWdlLWZyZXNoLW5ldy1idG4nKTtcbi8qKlxuICogQHR5cGUge3N0cmluZ31cbiAqL1xuY29uc3QgcHJvdG8gPSAkKGRvY3VtZW50KS5maW5kKCd0ZW1wbGF0ZS5zdGFnZXMtbGlzdC0taXRlbV9fcHJvdG8nKVswXTtcblxuc2V0VGltZW91dChmdW5jdGlvbiAoKXtcbiAgaWYoJCgnI2Vycm9ycycpLmxlbmd0aCA+IDApe1xuICAgICAgJCgnI2Vycm9ycycpLm1vZGFsKCdvcGVuJyk7XG4gICAgICAkKCcjZXJyb3JzJykuZmluZCgnbGFiZWwrc3BhbicpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAkKHRoaXMpLnRleHQoJCh0aGlzKS5wcmV2KCkudGV4dCgpKycgOicpO1xuICAgICAgICAgICQodGhpcykucHJldigpLnJlbW92ZSgpO1xuICAgICAgfSlcbiAgICAgICQoJyNlcnJvcnMgLm1vZGFsLWNvbnRlbnQgdWwnKS5jc3MoJ2Rpc3BsYXknLCdpbmxpbmUtYmxvY2snKS5hZGRDbGFzcygnbm8tbWFyZ2luJyk7XG4gIH1cbn0sMjAwKVxuXG5sZz0nZnInO1xubGV0ICRzdGFnZUNvbGxlY3Rpb25Ib2xkZXIgPSAkKCcuc3RhZ2VzJyk7XG5cbmZ1bmN0aW9uIHNsaWRlcnMoJGJ0biA9IG51bGwpIHtcblxuICBpZigkYnRuICE9IG51bGwpe1xuICAgIGlmKCRidG4uY2xvc2VzdCgnLmNyaXRlcmlhJykubGVuZ3RoID4gMCl7XG4gICAgICB0aGVTbGlkZXJzID0gJGJ0bi5jbG9zZXN0KCcuY3JpdGVyaWEnKS5maW5kKCcud2VpZ2h0LWNyaXRlcmlvbi1zbGlkZXInKS50b0FycmF5KCk7XG4gICAgfSBlbHNlIHtcbiAgICAgIHRoZVNsaWRlcnMgPSAkYnRuLmNsb3Nlc3QoJy5jcml0ZXJpYScpLmZpbmQoJy53ZWlnaHQtc3RhZ2Utc2xpZGVyJykudG9BcnJheSgpO1xuICAgIH1cbiAgfSBlbHNlIHtcbiAgICAgIHRoZVNsaWRlcnMgPSBBcnJheS5mcm9tKGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy53ZWlnaHQtY3JpdGVyaW9uLXNsaWRlciwgLndlaWdodC1zdGFnZS1zbGlkZXInKSk7XG4gIH1cblxuICAvKiQoJy53ZWlnaHQtY3JpdGVyaW9uLXNsaWRlcicpLmVhY2goZnVuY3Rpb24gKGtleSwgdmFsdWUpIHtcblxuICAgIHZhbHVlLm5vVWlTbGlkZXIub24oJ3NsaWRlJywgZnVuY3Rpb24gKHZhbHVlcywgaGFuZGxlKSB7XG4gIFxuICAgICAgICB2YWx1ZS5uZXh0RWxlbWVudFNpYmxpbmcuaW5uZXJIVE1MID0gTnVtYmVyKHZhbHVlc1toYW5kbGVdKSArICcgJSc7XG4gICAgICAgIHZhbHVlLm5leHRFbGVtZW50U2libGluZy5uZXh0RWxlbWVudFNpYmxpbmcudmFsdWUgPSB2YWx1ZXNbaGFuZGxlXTtcbiAgICAgICAgJCh2YWx1ZSkuY2xvc2VzdCgnLmVsZW1lbnQtaW5wdXQnKS5wcmV2KCkuZmluZCgnLmN3JykuZW1wdHkoKS5hcHBlbmQoTnVtYmVyKHZhbHVlc1toYW5kbGVdKSk7XG4gICAgfSlcbiAgfSk7XG4gICovXG5cbiAgLy9jb25zdCBzbGlkZXJzID0gQXJyYXkuZnJvbShkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcud2VpZ2h0LWNyaXRlcmlvbi1zbGlkZXIsIC53ZWlnaHQtc3RhZ2Utc2xpZGVyJykpO1xuICBjb25zdCBuZXdTbGlkZXJzID0gdGhlU2xpZGVycy5maWx0ZXIoZSA9PiAhZS5jbGFzc0xpc3QuY29udGFpbnMoJ2luaXRpYWxpemVkJykpO1xuXG4gIGZvciAoY29uc3QgZSBvZiBuZXdTbGlkZXJzKSB7XG4gICAgY29uc3Qgd2VpZ2h0RWxtdCA9IGUucGFyZW50RWxlbWVudDtcbiAgICAvL1JlbW92aW5nICclJyB0ZXh0IGFkZGVkIGJ5IFBlcmNlbnRUeXBlXG4gICAgd2VpZ2h0RWxtdC5yZW1vdmVDaGlsZCh3ZWlnaHRFbG10Lmxhc3RDaGlsZCk7XG4gICAgY29uc3QgaW5wdXQgPSB3ZWlnaHRFbG10LnF1ZXJ5U2VsZWN0b3IoJ2lucHV0Jyk7XG4gICAgY29uc3QgbGFiZWwgPSB3ZWlnaHRFbG10LnF1ZXJ5U2VsZWN0b3IoJy53ZWlnaHQtY3JpdGVyaW9uLXNsaWRlci1yYW5nZS12YWx1ZSwgLndlaWdodC1zdGFnZS1zbGlkZXItcmFuZ2UtdmFsdWUnKTtcbiAgICBjb25zdCBzbGlkZUNhbGxiYWNrID0gKHZhbHVlcywgaGFuZGxlKSA9PiB7XG4gICAgICBsYWJlbC5pbm5lckhUTUwgPSBgJHsrdmFsdWVzW2hhbmRsZV19ICVgO1xuICAgICAgbGFiZWwubmV4dEVsZW1lbnRTaWJsaW5nLnZhbHVlID0gdmFsdWVzW2hhbmRsZV07XG4gICAgICBpZigkKGUpLmhhc0NsYXNzKCd3ZWlnaHQtY3JpdGVyaW9uLXNsaWRlcicpKXtcbiAgICAgICAgJChlKS5jbG9zZXN0KCcuY3JpdGVyaWEtbGlzdC0taXRlbScpLmZpbmQoJy5jLXdlaWdodGluZycpLmVtcHR5KCkuYXBwZW5kKGAoJHtOdW1iZXIodmFsdWVzW2hhbmRsZV0pfSAlKWApO1xuICAgICAgfVxuICAgIH1cblxuICAgIG5vVWlTbGlkZXIuY3JlYXRlKGUsIHtcbiAgICAgIHN0YXJ0OiAraW5wdXQudmFsdWUsXG4gICAgICBzdGVwOiAxLFxuICAgICAgY29ubmVjdDogW3RydWUsIGZhbHNlXSxcbiAgICAgIHJhbmdlOiB7XG4gICAgICAgIG1pbjogMCxcbiAgICAgICAgbWF4OiAxMDAsXG4gICAgICB9LFxuICAgIH0pO1xuXG4gICAgc2xpZGVDYWxsYmFjayhbK2lucHV0LnZhbHVlXSwgMCk7XG4gICAgZS5ub1VpU2xpZGVyLm9uKCdzbGlkZScsIHNsaWRlQ2FsbGJhY2spO1xuICAgIGUuY2xhc3NMaXN0LmFkZCgnaW5pdGlhbGl6ZWQnKTtcbiAgfVxufVxuXG5zbGlkZXJzKCk7XG5cbmZ1bmN0aW9uIHBhcnNlRGRtbXl5eXkoc3RyKVxue1xuICB2YXIgcGFydHMgPSBzdHIuc3BsaXQoJy8nKTtcbiAgcmV0dXJuIG5ldyBEYXRlKHBhcnRzWzJdLCBwYXJ0c1sxXSAtIDEsIHBhcnRzWzBdKTtcbn1cblxuLy8gVXBkYXRlcyBjYWxlbmRhciBkYXRlcGlja2Vyc1xuXG5mdW5jdGlvbiB1cGRhdGVEYXRlcGlja2VycyhrLCBpbmRleCl7XG5cbiAgdmFyIG5iU3RhZ2VzID0gICQoJy5zdGFnZScpLmxlbmd0aDtcblxuICB2YXIgcmVwbGFjZVZhcnMgPSB7XG4gICAgICBcImphbnZpZXJcIjpcIkphbnVhcnlcIixcImVuZXJvXCI6XCJKYW51YXJ5XCIsXCJqYW5laXJvXCI6XCJKYW51YXJ5XCIsXG4gICAgICBcImbDqXZyaWVyXCI6XCJGZWJydWFyeVwiLFwiZmVicmVyb1wiOlwiRmVicnVhcnlcIixcImZldmVyZWlyb1wiOlwiRmVicnVhcnlcIixcbiAgICAgIFwibWFyc1wiOlwiTWFyY2hcIixcIm1hcnpvXCI6XCJNYXJjaFwiLFwibWFyw6dvXCI6XCJNYXJjaFwiLFxuICAgICAgXCJhdnJpbFwiOlwiQXByaWxcIixcImFicmlsXCI6XCJBcHJpbFwiLFwiYWJyaWxcIjpcIkFwcmlsXCIsXG4gICAgICBcIm1haVwiOlwiTWF5XCIsXCJtYXlvXCI6XCJNYXlcIixcIm1haW9cIjpcIk1heVwiLFxuICAgICAgXCJqdWluXCI6XCJKdW5lXCIsXCJqdW5pb1wiOlwiSnVuZVwiLFwianVuaG9cIjpcIkp1bmVcIixcbiAgICAgIFwianVpbGxldFwiOlwiSnVseVwiLFwianVsaW9cIjpcIkp1bHlcIixcImp1bGhvXCI6XCJKdWx5XCIsXG4gICAgICBcImFvw7t0XCI6XCJBdWd1c3RcIixcImFnb3N0b1wiOlwiQXVndXN0XCIsXCJhZ29zdG9cIjpcIkF1Z3VzdFwiLFxuICAgICAgXCJzZXB0ZW1icmVcIjpcIlNlcHRlbWJlclwiLFwic2VwdGllbWJyZVwiOlwiU2VwdGVtYmVyXCIsXCJzZXRlbWJyb1wiOlwiU2VwdGVtYmVyXCIsXG4gICAgICBcIm9jdG9icmVcIjpcIk9jdG9iZXJcIixcIm9jdHVicmVcIjpcIk9jdG9iZXJcIixcIm91dHVicm9cIjpcIk9jdG9iZXJcIixcbiAgICAgIFwibm92ZW1icmVcIjpcIk5vdmVtYmVyXCIsXCJub3ZpZW1icmVcIjpcIk5vdmVtYmVyXCIsXCJub3ZlbWJyb1wiOlwiTm92ZW1iZXJcIixcbiAgICAgIFwiZMOpY2VtYnJlXCI6XCJEZWNlbWJlclwiLFwiZGljaWVtYnJlXCI6XCJEZWNlbWJlclwiLFwiZGV6ZW1icm9cIjpcIkRlY2VtYmVyXCIsXG4gIH07XG4gIHZhciByZWdleCA9IC9qYW52aWVyfGbDqXZyaWVyfG1hcnN8YXZyaWx8bWFpfGp1aW58anVpbGxldHxhb8O7dHxzZXB0ZW1icmV8b2N0b2JyZXxub3ZlbWJyZXxkw6ljZW1icmV8ZW5lcm98ZmVicmVyb3xtYXJ6b3xhYnJpbHxtYXlvfGp1bmlvfGp1bGlvfGFnb3N0b3xzZXB0aWVtYnJlfG9jdHVicmV8bm92aWVtYnJlfGRpY2llbWJyZXxqYW5laXJvfGZldmVyZWlyb3xtYXLDp298YWJyaWx8bWFpb3xqdW5ob3xqdWxob3xhZ29zdG98c2V0ZW1icm98b3V0dWJyb3xub3ZlbWJyb3xkZXplbWJyby9nIDtcblxuICAvL1RocmVlIHBvc3NpYmxlIGNhc2VzIDogbG9hZGluZygwKSwgYWRkaXRpb24gb2Ygb25lIHN0YWdlKDEpLCB1cGRhdGUoMikgb3IgcmVtb3ZhbCAoLTEpXG5cbiAgaWYoaz09MCkge1xuXG4gICAgICAvL1NldCBkYXRlcGlja2VycyBib3VuZGFyaWVzIG9mIGdyYWRpbmcgZGF0ZXMgZm9yIGFsbCBzdGFnZXNcbiAgICAgIHZhciBjdXJyZW50ID0gMDtcbiAgICAgIHZhciAkdGVybWluYWxTdGFnZUVuZENhbCA9ICQoJy5kcC1lbmQ6ZXEoLTEpJyk7XG5cbiAgICAgICQoJy5zdGFnZScpLmVhY2goZnVuY3Rpb24oKXtcblxuICAgICAgICAgIHZhciBzdGFydENhbCA9ICQodGhpcykuZmluZCgnLmRwLXN0YXJ0Jyk7XG4gICAgICAgICAgdmFyIGVuZENhbCA9ICQodGhpcykuZmluZCgnLmRwLWVuZCcpO1xuICAgICAgICAgIHZhciBnU3RhcnRDYWwgPSAkKHRoaXMpLmZpbmQoJy5kcC1nc3RhcnQnKTtcbiAgICAgICAgICB2YXIgZ0VuZENhbCA9ICQodGhpcykuZmluZCgnLmRwLWdlbmQnKTtcbiAgICAgICAgICB2YXIgc3RhcnREYXRlVFMgPSAoc3RhcnRDYWwudmFsKCkgPT0gXCJcIikgPyBEYXRlLm5vdygpIDogcGFyc2VEZG1teXl5eShzdGFydENhbC52YWwoKS5yZXBsYWNlKHJlZ2V4LGZ1bmN0aW9uKG1hdGNoKXtyZXR1cm4gcmVwbGFjZVZhcnNbbWF0Y2hdO30pKTtcbiAgICAgICAgICB2YXIgZW5kRGF0ZVRTID0gKGVuZENhbC52YWwoKSA9PSBcIlwiKSA/IHN0YXJ0RGF0ZVRTIDogcGFyc2VEZG1teXl5eShlbmRDYWwudmFsKCkucmVwbGFjZShyZWdleCxmdW5jdGlvbihtYXRjaCl7cmV0dXJuIHJlcGxhY2VWYXJzW21hdGNoXTt9KSk7XG4gICAgICAgICAgdmFyIGdTdGFydERhdGVUUyA9IChnU3RhcnRDYWwudmFsKCkgPT0gXCJcIikgPyBzdGFydERhdGVUUyA6IHBhcnNlRGRtbXl5eXkoZ1N0YXJ0Q2FsLnZhbCgpLnJlcGxhY2UocmVnZXgsZnVuY3Rpb24obWF0Y2gpe3JldHVybiByZXBsYWNlVmFyc1ttYXRjaF07fSkpO1xuICAgICAgICAgIHZhciBnRW5kRGF0ZVRTID0gKGdFbmRDYWwudmFsKCkgPT0gXCJcIikgPyBzdGFydERhdGVUUyA6IHBhcnNlRGRtbXl5eXkoZ0VuZENhbC52YWwoKS5yZXBsYWNlKHJlZ2V4LGZ1bmN0aW9uKG1hdGNoKXtyZXR1cm4gcmVwbGFjZVZhcnNbbWF0Y2hdO30pKTtcbiAgICAgICAgICB2YXIgc3RhcnREYXRlID0gbmV3IERhdGUoc3RhcnREYXRlVFMpO1xuICAgICAgICAgIHZhciBlbmREYXRlID0gbmV3IERhdGUoZW5kRGF0ZVRTKTtcbiAgICAgICAgICB2YXIgZ1N0YXJ0RGF0ZSA9IG5ldyBEYXRlKGdTdGFydERhdGVUUyk7XG4gICAgICAgICAgdmFyIGdFbmREYXRlID0gbmV3IERhdGUoZ0VuZERhdGVUUyk7XG5cbiAgICAgICAgICBzdGFydENhbC5waWNrYWRhdGUoJ3BpY2tlcicpLnNldCgnc2VsZWN0JyxzdGFydERhdGUpO1xuICAgICAgICAgIGVuZENhbC5waWNrYWRhdGUoJ3BpY2tlcicpLnNldCgnc2VsZWN0JyxlbmREYXRlKS5zZXQoJ21pbicsc3RhcnREYXRlKTtcbiAgICAgICAgICBnU3RhcnRDYWwucGlja2FkYXRlKCdwaWNrZXInKS5zZXQoJ3NlbGVjdCcsZ1N0YXJ0RGF0ZSkuc2V0KCdtaW4nLHN0YXJ0RGF0ZSk7XG4gICAgICAgICAgZ0VuZENhbC5waWNrYWRhdGUoJ3BpY2tlcicpLnNldCgnc2VsZWN0JyxnRW5kRGF0ZSkuc2V0KCdtaW4nLGdTdGFydERhdGUpO1xuICAgICAgfSk7XG5cbiAgfSBlbHNlIGlmKGs9PTEpIHtcblxuICAgICAgdmFyICRsYXN0U3RhZ2VFbmRDYWwgPSAkKCcuZHAtZW5kOmVxKCcrKGluZGV4KSsnKScpO1xuICAgICAgdmFyICRhZGRlZFN0YWdlU3RhcnRDYWwgPSAkKCcuZHAtc3RhcnQ6ZXEoJysoaW5kZXgrMSkrJyknKTtcbiAgICAgIHZhciAkYWRkZWRTdGFnZUVuZENhbCA9ICQoJy5kcC1lbmQ6ZXEoJysoaW5kZXgrMSkrJyknKTtcbiAgICAgIHZhciAkbGFzdFN0YWdlV2VpZ2h0ID0gJCgnLndlaWdodC1pbnB1dDplcSgnK2luZGV4KycpJyk7XG4gICAgICB2YXIgJGFkZGVkU3RhZ2VXZWlnaHQgPSAkKCcud2VpZ2h0LWlucHV0OmVxKCcrKGluZGV4KzEpKycpJyk7XG4gICAgICB2YXIgJGFkZGVkU3RhZ2VHU3RhcnRDYWwgPSAkKCcuZHAtZ3N0YXJ0OmVxKCcrKGluZGV4KzEpKycpJyk7XG4gICAgICB2YXIgJGFkZGVkU3RhZ2VHRW5kQ2FsID0gJCgnLmRwLWdlbmQ6ZXEoJysoaW5kZXgrMSkrJyknKTtcblxuICAgICAgJGFkZGVkU3RhZ2VTdGFydENhbC5hZGQoJGFkZGVkU3RhZ2VFbmRDYWwpLmFkZCgkYWRkZWRTdGFnZUdTdGFydENhbCkuYWRkKCRhZGRlZFN0YWdlR0VuZENhbCkucGlja2FkYXRlKCk7XG5cbiAgICAgIHZhciBhZGRlZFN0YWdlU3RhcnRkYXRlID0gbmV3IERhdGUoJGxhc3RTdGFnZUVuZENhbC5waWNrYWRhdGUoJ3BpY2tlcicpLmdldCgnc2VsZWN0JykucGljayk7XG4gICAgICB2YXIgYWRkZWRTdGFnZUVuZGRhdGUgPSBuZXcgRGF0ZSgkbGFzdFN0YWdlRW5kQ2FsLnBpY2thZGF0ZSgncGlja2VyJykuZ2V0KCdzZWxlY3QnKS5waWNrKTtcbiAgICAgIHZhciBhZGRlZFN0YWdlR1N0YXJ0ZGF0ZSA9IG5ldyBEYXRlKCRsYXN0U3RhZ2VFbmRDYWwucGlja2FkYXRlKCdwaWNrZXInKS5nZXQoJ3NlbGVjdCcpLnBpY2spO1xuICAgICAgdmFyIGFkZGVkU3RhZ2VHRW5kZGF0ZSA9IG5ldyBEYXRlKCRsYXN0U3RhZ2VFbmRDYWwucGlja2FkYXRlKCdwaWNrZXInKS5nZXQoJ3NlbGVjdCcpLnBpY2spO1xuXG4gICAgICAkYWRkZWRTdGFnZVN0YXJ0Q2FsLnBpY2thZGF0ZSgncGlja2VyJykuc2V0KCdzZWxlY3QnLGFkZGVkU3RhZ2VTdGFydGRhdGUpO1xuICAgICAgJGFkZGVkU3RhZ2VFbmRDYWwucGlja2FkYXRlKCdwaWNrZXInKS5zZXQoJ3NlbGVjdCcsYWRkZWRTdGFnZUVuZGRhdGUpLnNldCgnbWluJywkYWRkZWRTdGFnZVN0YXJ0Q2FsLnBpY2thZGF0ZSgncGlja2VyJykuZ2V0KCdzZWxlY3QnKSk7XG4gICAgICAkYWRkZWRTdGFnZUdTdGFydENhbC5waWNrYWRhdGUoJ3BpY2tlcicpLnNldCgnc2VsZWN0JyxhZGRlZFN0YWdlR1N0YXJ0ZGF0ZSk7XG4gICAgICAkYWRkZWRTdGFnZUdFbmRDYWwucGlja2FkYXRlKCdwaWNrZXInKS5zZXQoJ3NlbGVjdCcsYWRkZWRTdGFnZUdFbmRkYXRlKS5zZXQoJ21pbicsJGFkZGVkU3RhZ2VHU3RhcnRDYWwucGlja2FkYXRlKCdwaWNrZXInKS5nZXQoJ3NlbGVjdCcpKTtcblxuICB9IGVsc2UgaWYgKGs9PS0xKXtcblxuICAgICAgdmFyICR1cHN0cmVhbVN0YWdlV2VpZ2h0ID0gJCgnLndlaWdodDplcSgnK2luZGV4KycpJyk7XG4gICAgICB2YXIgJHJlbW92ZWRTdGFnZVdlaWdodCA9ICQoJy53ZWlnaHQ6ZXEoJysoaW5kZXgrMSkrJyknKTtcbiAgICAgICR1cHN0cmVhbVN0YWdlV2VpZ2h0LnZhbChwYXJzZUZsb2F0KCR1cHN0cmVhbVN0YWdlV2VpZ2h0LnZhbCgpKStwYXJzZUZsb2F0KCRyZW1vdmVkU3RhZ2VXZWlnaHQudmFsKCkpKTtcbiAgfVxuXG59XG5cbnN3aXRjaChsZyl7XG5cbiAgY2FzZSAnZnInOlxuICAgICAgJC5leHRlbmQoJC5mbi5waWNrYWRhdGUuZGVmYXVsdHMsIHtcbiAgICAgICAgICBtb250aHNGdWxsOiBbJ2phbnZpZXInLCAnZsOpdnJpZXInLCAnbWFycycsICdhdnJpbCcsICdtYWknLCAnanVpbicsICdqdWlsbGV0JywgJ2Fvw7t0JywgJ3NlcHRlbWJyZScsICdvY3RvYnJlJywgJ25vdmVtYnJlJywgJ2TDqWNlbWJyZSddLFxuICAgICAgICAgIG1vbnRoc1Nob3J0OiBbICdKYW4nLCAnRmV2JywgJ01hcicsICdBdnInLCAnTWFpJywgJ0p1aW4nLCAnSnVpbCcsICdBb3UnLCAnU2VwJywgJ09jdCcsICdOb3YnLCAnRGVjJyBdLFxuICAgICAgICAgIHdlZWtkYXlzRnVsbDogWyAnRGltYW5jaGUnLCAnTHVuZGknLCAnTWFyZGknLCAnTWVyY3JlZGknLCAnSmV1ZGknLCAnVmVuZHJlZGknLCAnU2FtZWRpJyBdLFxuICAgICAgICAgIHdlZWtkYXlzU2hvcnQ6IFsgJ0RpbScsICdMdW4nLCAnTWFyJywgJ01lcicsICdKZXUnLCAnVmVuJywgJ1NhbScgXSxcbiAgICAgICAgICB0b2RheTogJ0F1am91cmRcXCdodWknLFxuICAgICAgICAgIGNsZWFyOiAnRWZmYWNlcicsXG4gICAgICAgICAgY2xvc2U6ICdGZXJtZXInLFxuICAgICAgICAgIGZpcnN0RGF5OiAxLFxuICAgICAgICAgIC8vZm9ybWF0OiAnZGQgbW1tbSB5eXl5JyxcbiAgICAgIH0pO1xuICAgICAgYnJlYWs7XG4gIGNhc2UgJ2VzJzpcbiAgICAgICQuZXh0ZW5kKCQuZm4ucGlja2FkYXRlLmRlZmF1bHRzLCB7XG4gICAgICAgICAgbW9udGhzRnVsbDogWyAnZW5lcm8nLCAnZmVicmVybycsICdtYXJ6bycsICdhYnJpbCcsICdtYXlvJywgJ2p1bmlvJywgJ2p1bGlvJywgJ2Fnb3N0bycsICdzZXB0aWVtYnJlJywgJ29jdHVicmUnLCAnbm92aWVtYnJlJywgJ2RpY2llbWJyZScgXSxcbiAgICAgICAgICBtb250aHNTaG9ydDogWyAnZW5lJywgJ2ZlYicsICdtYXInLCAnYWJyJywgJ21heScsICdqdW4nLCAnanVsJywgJ2FnbycsICdzZXAnLCAnb2N0JywgJ25vdicsICdkaWMnIF0sXG4gICAgICAgICAgd2Vla2RheXNGdWxsOiBbICdkb21pbmdvJywgJ2x1bmVzJywgJ21hcnRlcycsICdtacOpcmNvbGVzJywgJ2p1ZXZlcycsICd2aWVybmVzJywgJ3PDoWJhZG8nIF0sXG4gICAgICAgICAgd2Vla2RheXNTaG9ydDogWyAnZG9tJywgJ2x1bicsICdtYXInLCAnbWnDqScsICdqdWUnLCAndmllJywgJ3PDoWInIF0sXG4gICAgICAgICAgdG9kYXk6ICdob3knLFxuICAgICAgICAgIGNsZWFyOiAnYm9ycmFyJyxcbiAgICAgICAgICBjbG9zZTogJ2NlcnJhcicsXG4gICAgICAgICAgZmlyc3REYXk6IDEsXG4gICAgICAgICAgLy9mb3JtYXQ6ICdkZGRkIGQgIWRlIG1tbW0gIWRlIHl5eXknLFxuICAgICAgfSk7XG4gICAgICBicmVhaztcbiAgY2FzZSAncHQnOlxuICAgICAgJC5leHRlbmQoJC5mbi5waWNrYWRhdGUuZGVmYXVsdHMsIHtcbiAgICAgICAgICBtb250aHNGdWxsOiBbICdKYW5laXJvJywgJ0ZldmVyZWlybycsICdNYXLDp28nLCAnQWJyaWwnLCAnTWFpbycsICdKdW5obycsICdKdWxobycsICdBZ29zdG8nLCAnU2V0ZW1icm8nLCAnT3V0dWJybycsICdOb3ZlbWJybycsICdEZXplbWJybycgXSxcbiAgICAgICAgICBtb250aHNTaG9ydDogWyAnamFuJywgJ2ZldicsICdtYXInLCAnYWJyJywgJ21haScsICdqdW4nLCAnanVsJywgJ2FnbycsICdzZXQnLCAnb3V0JywgJ25vdicsICdkZXonIF0sXG4gICAgICAgICAgd2Vla2RheXNGdWxsOiBbICdEb21pbmdvJywgJ1NlZ3VuZGEnLCAnVGVyw6dhJywgJ1F1YXJ0YScsICdRdWludGEnLCAnU2V4dGEnLCAnU8OhYmFkbycgXSxcbiAgICAgICAgICB3ZWVrZGF5c1Nob3J0OiBbICdkb20nLCAnc2VnJywgJ3RlcicsICdxdWEnLCAncXVpJywgJ3NleCcsICdzYWInIF0sXG4gICAgICAgICAgdG9kYXk6ICdIb2plJyxcbiAgICAgICAgICBjbGVhcjogJ0xpbXBhcicsXG4gICAgICAgICAgY2xvc2U6ICdGZWNoYXInLFxuICAgICAgICAgIGZpcnN0RGF5OiAxLFxuICAgICAgICAgIC8vZm9ybWF0OiAnZCAhZGUgbW1tbSAhZGUgeXl5eScsXG4gICAgICB9KTtcbiAgICAgIGJyZWFrO1xuICBkZWZhdWx0OlxuICAgICAgYnJlYWs7XG5cbn1cblxuJC5leHRlbmQoJC5mbi5waWNrYWRhdGUuZGVmYXVsdHMsIHtcbiAgc2VsZWN0TW9udGhzOiB0cnVlLFxuICBzZWxlY3RZZWFyczogNSxcbiAgeWVhcmVuZDogJzMxLzEyLzIwMjAnLFxuICBjbG9zZU9uU2VsZWN0OiB0cnVlLFxuICBjbGVhcjogZmFsc2UsXG4gIC8vZm9ybWF0IDogJ2RkIE1NTU0sIHl5eXknLFxuICAvL2Zvcm1hdFN1Ym1pdDogJ3l5eXkvbW0vZGQnXG59KTtcblxuJCgnLmRwLXN0YXJ0LCAuZHAtZW5kLCAuZHAtZ3N0YXJ0LCAuZHAtZ2VuZCcpLmVhY2goZnVuY3Rpb24oKSB7XG4gICQodGhpcykucGlja2FkYXRlKCk7XG59KTtcblxudmFyIGVuZERhdGVzID0gJCgnLmRwLWVuZCcpO1xuZW5kRGF0ZXMuZGF0YSgncHJldmlvdXMnLCBlbmREYXRlcy52YWwoKSk7XG5cblxuLy9TZXQgZGF0ZXBpY2tlcnMgYm91bmRhcmllcyBvbiBsb2FkaW5nXG51cGRhdGVEYXRlcGlja2VycygwLDApO1xuXG4kKCcuZHAtc3RhcnQsIC5kcC1lbmQsIC5kcC1nc3RhcnQnKS5vbignY2hhbmdlJyxmdW5jdGlvbigpIHtcblxuICB2YXIgc2VsZWN0ZWREYXRlID0gJCh0aGlzKS5waWNrYWRhdGUoJ3BpY2tlcicpLmdldCgnc2VsZWN0Jyk7XG4gIHZhciAkR1N0YXJ0Q2FsID0gJCh0aGlzKS5jbG9zZXN0KCcuc3RhZ2UnKS5maW5kKCcuZHAtZ3N0YXJ0Jyk7XG4gIHZhciAkR0VuZENhbCA9ICQodGhpcykuY2xvc2VzdCgnLnN0YWdlJykuZmluZCgnLmRwLWdlbmQnKTtcblxuICBpZiAoJCh0aGlzKS5oYXNDbGFzcygnZHAtc3RhcnQnKSB8fCAkKHRoaXMpLmhhc0NsYXNzKCdkcC1nc3RhcnQnKSkge1xuXG4gICAgICB2YXIgY2xhc3NQcmVmaXggPSAkKHRoaXMpLmF0dHIoJ2NsYXNzJykuc3BsaXQoJyAnKVswXS5zbGljZSgwLCAtNSk7XG5cbiAgICAgIC8vU2hpZnRpbmcgZW5kZGF0ZXMgdmFsdWVzIChmb3IgZ3JhZGluZyBhbmQgc3RhZ2UpXG4gICAgICB2YXIgJHJlbGF0ZWRFbmRDYWwgPSAkKHRoaXMpLmNsb3Nlc3QoJy5yb3cnKS5maW5kKCcuJyArIGNsYXNzUHJlZml4ICsgJ2VuZCcpO1xuICAgICAgaWYgKCRyZWxhdGVkRW5kQ2FsLnBpY2thZGF0ZSgncGlja2VyJykuZ2V0KCdzZWxlY3QnKS5waWNrIDwgc2VsZWN0ZWREYXRlLnBpY2spIHtcbiAgICAgICAgICAkcmVsYXRlZEVuZENhbC5waWNrYWRhdGUoJ3BpY2tlcicpLnNldCgnc2VsZWN0JywgbmV3IERhdGUoc2VsZWN0ZWREYXRlLnBpY2sgLyorIDE0ICogMjQgKiA2MCAqIDYwICogMTAwMCovKSk7XG4gICAgICB9XG4gICAgICAkcmVsYXRlZEVuZENhbC5waWNrYWRhdGUoJ3BpY2tlcicpLnNldCgnbWluJywgc2VsZWN0ZWREYXRlKTtcbiAgICAgICRHU3RhcnRDYWwucGlja2FkYXRlKCdwaWNrZXInKS5zZXQoJ21pbicsIG5ldyBEYXRlKCQoJy5kcC1zdGFydCcpLnBpY2thZGF0ZSgncGlja2VyJykuZ2V0KCdzZWxlY3QnKS5waWNrKSk7XG5cblxuICB9IGVsc2UgaWYgKCQodGhpcykuaGFzQ2xhc3MoJ2RwLWVuZCcpICYmICQodGhpcykuY2xvc2VzdCgnLnJlY3VycmluZycpLmxlbmd0aCA9PSAwKSB7XG5cbiAgICAgIGlmICgkR1N0YXJ0Q2FsLnBpY2thZGF0ZSgncGlja2VyJykuZ2V0KCdzZWxlY3QnKS5waWNrIDwgc2VsZWN0ZWREYXRlLnBpY2spe1xuICAgICAgICAgICRHU3RhcnRDYWwucGlja2FkYXRlKCdwaWNrZXInKS5zZXQoJ3NlbGVjdCcsIG5ldyBEYXRlKHNlbGVjdGVkRGF0ZS5waWNrICsgMSAqIDI0ICogNjAgKiA2MCAqIDEwMDApKTtcbiAgICAgIH1cblxuICAgICAgdmFyIEdTdGFydERhdGUgPSAkR1N0YXJ0Q2FsLnBpY2thZGF0ZSgncGlja2VyJykuZ2V0KCdzZWxlY3QnKTtcbiAgICAgIGlmICgkR0VuZENhbC5waWNrYWRhdGUoJ3BpY2tlcicpLmdldCgnc2VsZWN0JykucGljayA8IEdTdGFydERhdGUucGljayl7XG4gICAgICAgICAgJEdFbmRDYWwucGlja2FkYXRlKCdwaWNrZXInKS5zZXQoJ3NlbGVjdCcsIG5ldyBEYXRlKEdTdGFydERhdGUucGljayArIDcgKiAyNCAqIDYwICogNjAgKiAxMDAwKSk7XG4gICAgICB9XG4gICAgICAkR0VuZENhbC5waWNrYWRhdGUoJ3BpY2tlcicpLnNldCgnbWluJywgR1N0YXJ0RGF0ZSk7XG5cbiAgfVxufSk7XG5cbiQoJy5zdGFnZS1hZGQnKS5ob3ZlcihmdW5jdGlvbigpe1xuICAkKHRoaXMpLmZpbmQoJy5zdGFnZS1pdGVtLW5hbWUnKS5jc3MoJ3Zpc2liaWxpdHknLCdoaWRkZW4nKTtcbn0sZnVuY3Rpb24oKXtcbiAgJCh0aGlzKS5maW5kKCcuc3RhZ2UtaXRlbS1uYW1lJykuY3NzKCd2aXNpYmlsaXR5JywndmlzaWJsZScpO1xufSk7XG5cbiQoJy5kdXBsaWNhdGUtYnRuJykub24oJ2NsaWNrJyxmdW5jdGlvbigpe1xuICAkYnRuID0gJCh0aGlzKTsgXG4gIHVybFRvUGllY2VzID0gZHBzdXJsLnNwbGl0KCcvJyk7XG4gIHVybFRvUGllY2VzW3VybFRvUGllY2VzLmxlbmd0aC0xXSA9ICRidG4uY2xvc2VzdCgnLm1vZGFsJykuZmluZCgnI3N0YWdlU2VsZWN0JykudmFsKCk7XG4gIHVybCA9IHVybFRvUGllY2VzLmpvaW4oJy8nKTtcbiAgJC5wb3N0KHVybClcbiAgICAuZG9uZShmdW5jdGlvbihkYXRhKXtcbiAgICAgICAgbG9jYXRpb24ucmVsb2FkKCk7XG4gICAgfSlcbiAgICAuZmFpbChmdW5jdGlvbihkYXRhKXtcbiAgICAgICAgY29uc29sZS5sb2coZGF0YSk7XG4gICAgfSlcbn0pO1xuXG5cbiQoZG9jdW1lbnQpLm9uKFxuICAnY2xpY2snLCBgJHtTVEFHRV9JVEVNfSA+IC5zdGFnZS1pdGVtLWJ1dHRvbmAsXG4gIGZ1bmN0aW9uKCkge1xuICAgIGNvbnN0ICR0aGlzID0gJCh0aGlzKTtcbiAgICB0b2dnbGVTdGFnZSgkdGhpcyk7XG4gIH1cbikub24oXG4gICdjbGljaycsICcucmVtb3ZlLXN0YWdlLWJ0bicsXG4gIGZ1bmN0aW9uKCkge1xuICAgIGNvbnN0ICR0aGlzID0gJCh0aGlzKTtcbiAgICBjb25zdCAkc3RhZ2VJdGVtID0gJHRoaXMuY2xvc2VzdChTVEFHRV9JVEVNKTtcbiAgICAkc3RhZ2VJdGVtLmZpbmQoJy5zdGFnZS1tb2RhbCcpLm1vZGFsKCdjbG9zZScpO1xuICAgICRzdGFnZUl0ZW0ucmVtb3ZlKCk7XG4gIH1cbikub24oXG4gICdjbGljaycsICcuZWRpdC11c2VyLWJ0bicsXG4gIGZ1bmN0aW9uKCkge1xuICAgIGNvbnN0ICR0aGlzID0gJCh0aGlzKTtcbiAgICBjb25zdCAkcGFydGljaXBhbnRJdGVtID0gJHRoaXMuY2xvc2VzdChQQVJUSUNJUEFOVFNfSVRFTSk7XG4gICAgJHBhcnRpY2lwYW50SXRlbS5hZGRDbGFzcygnZWRpdC1tb2RlJyk7XG4gIH1cbikub24oXG4gICdjbGljaycsICcuYnRuLWFkZC1jcml0ZXJpb24nLFxuICBmdW5jdGlvbigpIHtcbiAgICBjb25zdCAkdGhpcyA9ICQodGhpcyk7XG4gICAgY29uc3QgJHNlY3Rpb24gPSAkdGhpcy5jbG9zZXN0KCdzZWN0aW9uJyk7XG4gICAgY29uc3QgJGNyaXRlcmlhTGlzdCA9ICRzZWN0aW9uLmZpbmQoJ3VsLmNyaXRlcmlhLWxpc3QnKTtcblxuICAgIGlmICgkY3JpdGVyaWFMaXN0LmNoaWxkcmVuKCcubmV3JykubGVuZ3RoKSB7XG4gICAgICByZXR1cm47XG4gICAgfVxuXG4gICAgLy9jb25zdCAkcHJvdG8gPSAgJHNlY3Rpb24uZmluZCgndGVtcGxhdGUuY3JpdGVyaWEtbGlzdC0taXRlbV9fcHJvdG8nKTtcbiAgICAvKiogQHR5cGUge0hUTUxUZW1wbGF0ZUVsZW1lbnR9ICovXG4gICAgY29uc3QgJGNyaXRlcmlhID0gJHNlY3Rpb24uZmluZCgnLmNyaXRlcmlhLWxpc3QtLWl0ZW0nKTtcbiAgICBjb25zdCBuYkNyaXRlcmlhID0gJGNyaXRlcmlhLmxlbmd0aDtcbiAgICBjb25zdCBwcm90byA9ICRzZWN0aW9uLmZpbmQoJ3RlbXBsYXRlLmNyaXRlcmlhLWxpc3QtLWl0ZW1fX3Byb3RvJylbMF07XG4gICAgY29uc3QgcHJvdG9IdG1sID0gcHJvdG8uaW5uZXJIVE1MLnRyaW0oKTtcbiAgICBjb25zdCBuZXdQcm90b0h0bWwgPSBwcm90b0h0bWxcbiAgICAucmVwbGFjZSgvX19uYW1lX18vZywgJGNyaXRlcmlhTGlzdC5jaGlsZHJlbigpLmxlbmd0aCAtIDIpXG4gICAgLnJlcGxhY2UoL19fY3J0TmJfXy9nLCAkY3JpdGVyaWFMaXN0LmNoaWxkcmVuKCkubGVuZ3RoIC0gMSlcbiAgICAucmVwbGFjZSgvX19sb3dlcmJvdW5kX18vZywgMClcbiAgICAucmVwbGFjZSgvX191cHBlcmJvdW5kX18vZywgNSlcbiAgICAucmVwbGFjZSgvX19zdGVwX18vZywgMC41KVxuICAgIC8vLnJlcGxhY2UoL19fd2VpZ2h0X18vZywgTWF0aC5yb3VuZCgxMDAvKG5iQ3JpdGVyaWEgKyAxKSkpXG4gICAgLnJlcGxhY2UoL19fc3RnTmJfXy9nLCAkKCcuc3RhZ2UnKS5pbmRleCgkc2VjdGlvbi5jbG9zZXN0KCcuc3RhZ2UnKSkpO1xuXG4gICAgY29uc3QgJGNydEVsbXQgPSAkKG5ld1Byb3RvSHRtbCk7XG4gICAgLy8kY3J0RWxtdC5hcHBlbmQobmV3UHJvdG9IdG1sKTtcbiAgICAkY3J0RWxtdC5maW5kKCcubW9kYWwnKS5tb2RhbCgpO1xuICAgICRjcnRFbG10LmZpbmQoJy50b29sdGlwcGVkJykudG9vbHRpcCgpO1xuXG4gICAgLy8gU2V0dGluZyBkZWZhdWx0IHZhbHVlcywgYW5kIHB1dHRpbmcgbGFiZWwgdXBzaWRlIGJ5IGFkZGluZyB0aGVtIFwiYWN0aXZlXCIgY2xhc3NcbiAgICAkY3J0RWxtdC5maW5kKCdpbnB1dFtuYW1lKj1cImxvd2VyYm91bmRcIl0nKS52YWwoMCkucHJldigpLmFkZENsYXNzKFwiYWN0aXZlXCIpO1xuICAgICRjcnRFbG10LmZpbmQoJ2lucHV0W25hbWUqPVwidXBwZXJib3VuZFwiXScpLnZhbCg1KS5wcmV2KCkuYWRkQ2xhc3MoXCJhY3RpdmVcIik7XG4gICAgJGNydEVsbXQuZmluZCgnaW5wdXRbbmFtZSo9XCJzdGVwXCJdJykudmFsKDAuNSkucHJldigpLmFkZENsYXNzKFwiYWN0aXZlXCIpO1xuICAgIC8vIFNlbGVjdCBuZXcgY3JpdGVyaW9uIGFzIGJlaW5nIGFuIGV2YWx1YXRpb24gb25lIChieSBkZWZhdWx0KVxuICAgICRjcnRFbG10LmZpbmQoJ1tpZCo9XCJfdHlwZVwiXScpLmVxKDEpWzBdLmNoZWNrZWQgPSB0cnVlO1xuICAgIFxuICAgIC8qXG4gICAgY3J0Q05hbWVUZXh0ID0gJGNydEVsbXQuZmluZCgnc2VsZWN0W25hbWUqPVwiY05hbWVcIl0gb3B0aW9uOnNlbGVjdGVkJykudGV4dCgpO1xuICAgIGNydE5hbWUgPSBjcnRDTmFtZVRleHQuc3BsaXQoJyAnKS5zbGljZSgxKS5qb2luKCcgJyk7XG4gICAgY3J0SWNvbiA9IGNydENOYW1lVGV4dC5zcGxpdCgnICcpWzBdO1xuICAgICRjcnRFbG10LmZpbmQoJy5jbmFtZScpLmF0dHIoJ2RhdGEtaWNvbicsY3J0SWNvbikuYXBwZW5kKGNydE5hbWUpO1xuICAgICovXG5cbiAgICAvLyRjcnRFbG10LmZpbmQoJy5ib3VuZHMnKS5hcHBlbmQoJ1swLTVdJyk7XG4gICAgLy8kY3J0RWxtdC5maW5kKCcuc3RlcHBpbmcnKS5hcHBlbmQoMC41KTtcbiAgICBcbiAgICAkY3JpdGVyaWFMaXN0LmNoaWxkcmVuKCkubGFzdCgpLmJlZm9yZSgkY3J0RWxtdCk7XG5cbiAgICAvKiRjcml0ZXJpYUxpc3QuYXBwZW5kKFxuICAgICAgcHJvdG9IdG1sXG4gICAgICAgIC5yZXBsYWNlKC9fX25hbWVfXy9nLCAkY3JpdGVyaWFMaXN0LmNoaWxkcmVuKCkubGVuZ3RoKVxuICAgICAgICAucmVwbGFjZSgvX19jcnROYl9fL2csICRjcml0ZXJpYUxpc3QuY2hpbGRyZW4oKS5sZW5ndGgpXG4gICAgICAgIC5yZXBsYWNlKC9fX3N0Z05iX18vZywgJCgnLnN0YWdlJykuaW5kZXgoJHNlY3Rpb24uY2xvc2VzdCgnLnN0YWdlJykpKVxuICAgICk7Ki9cbiAgICBcbiAgICB2YXIgc2xpZGVyID0gJGNydEVsbXQuZmluZCgnLndlaWdodC1jcml0ZXJpb24tc2xpZGVyJyk7XG4gICAgdmFyIHdlaWdodCA9ICRjcnRFbG10LmZpbmQoJy53ZWlnaHQnKTtcblxuICAgIC8vUmVtb3ZpbmcgJyUnIHRleHQgYWRkZWQgYnkgUGVyY2VudFR5cGVcbiAgICB3ZWlnaHRbMF0ucmVtb3ZlQ2hpbGQod2VpZ2h0WzBdLmxhc3RDaGlsZCk7XG5cbiAgICAvL0dldCBuZXcgY3JpdGVyaWEgb2JqZWN0cyBhZnRlciBpbnNlcnRpb25cbiAgICAvL3ZhciByZWxhdGVkQ3JpdGVyaWEgPSAkY3J0RWxtdC5jbG9zZXN0KCcuc3RhZ2UnKS5maW5kKCcuY3JpdGVyaW9uJyk7XG4gICAgJHJlbGF0ZWRDcml0ZXJpYSA9ICRjcml0ZXJpYUxpc3QuZmluZCgnLmNyaXRlcmlhLWxpc3QtLWl0ZW0nKTtcblxuXG4gICAgdmFyIGNyZWF0aW9uVmFsID0gTWF0aC5yb3VuZCgxMDAgLyAkcmVsYXRlZENyaXRlcmlhLmxlbmd0aCk7ICAgIFxuICAgIHZhciBzdW1WYWwgPSAwO1xuXG4gICAgY3JlYXRpb25WYWwgPSBNYXRoLnJvdW5kKDEwMCAvICRyZWxhdGVkQ3JpdGVyaWEubGVuZ3RoKTtcblxuICAgIG5vVWlTbGlkZXIuY3JlYXRlKHNsaWRlclswXSwge1xuICAgICAgICBzdGFydDogY3JlYXRpb25WYWwsXG4gICAgICAgIHN0ZXA6IDEsXG4gICAgICAgIGNvbm5lY3Q6IFt0cnVlLCBmYWxzZV0sXG4gICAgICAgIHJhbmdlOiB7XG4gICAgICAgICAgICAnbWluJzogMCxcbiAgICAgICAgICAgICdtYXgnOiAxMDAsXG4gICAgICAgIH0sXG4gICAgfSk7XG5cbiAgICBzbGlkZXJbMF0ubmV4dEVsZW1lbnRTaWJsaW5nLmlubmVySFRNTCA9IGNyZWF0aW9uVmFsICsgJyAlJztcbiAgICBzbGlkZXJbMF0ubmV4dEVsZW1lbnRTaWJsaW5nLm5leHRFbGVtZW50U2libGluZy52YWx1ZSA9IGNyZWF0aW9uVmFsO1xuXG4gICAgc2xpZGVyWzBdLm5vVWlTbGlkZXIub24oJ3NsaWRlJywgZnVuY3Rpb24gKHZhbHVlcywgaGFuZGxlKSB7XG5cbiAgICAgICAgc2xpZGVyWzBdLm5leHRFbGVtZW50U2libGluZy5pbm5lckhUTUwgPSBOdW1iZXIodmFsdWVzW2hhbmRsZV0pICsgJyAlJztcbiAgICAgICAgc2xpZGVyWzBdLm5leHRFbGVtZW50U2libGluZy5uZXh0RWxlbWVudFNpYmxpbmcudmFsdWUgPSB2YWx1ZXNbaGFuZGxlXTtcblxuICAgIH0pO1xuICAgIFxuICAgIHNsaWRlci5uZXh0KCkubmV4dCgpLmhpZGUoKTtcbiAgICBpZihuYkNyaXRlcmlhID09IDApe1xuICAgICAgc2xpZGVyLmNsb3Nlc3QoJy53ZWlnaHQnKS5oaWRlKCk7XG4gICAgfVxuXG4gICAgaGFuZGxlQ05TZWxlY3RFbGVtcygkY3J0RWxtdCk7XG5cbiAgICAkY3J0RWxtdC5maW5kKCcuY3JpdGVyaW9uLW1vZGFsJykubW9kYWwoe1xuICAgICAgY29tcGxldGU6IGZ1bmN0aW9uKCl7XG5cbiAgICAgICAgbGV0IG1vZEMgPSAkKHRoaXMpWzBdLiRlbDtcbiAgICAgICAgbGV0ICRjcnRFbG10ID0gbW9kQy5jbG9zZXN0KCcuY3JpdGVyaWEtbGlzdC0taXRlbScpO1xuICAgICAgICBsZXQgYnRuViA9ICRjcnRFbG10LmZpbmQoJy5jLXZhbGlkYXRlJyk7XG4gICAgICAgIHZhciBzbGlkZXIgPSAkY3J0RWxtdC5maW5kKCcud2VpZ2h0LWNyaXRlcmlvbi1zbGlkZXInKTtcbiAgICAgICAgaWYoIWJ0blYuaGFzQ2xhc3MoJ2NsaWNrZWQnKSl7XG4gICAgICAgICAgICBpZigkY3J0RWxtdC5oYXNDbGFzcygnbmV3JykpeyAgXG5cbiAgICAgICAgICAgICAgJGNydEVsbXQucmVtb3ZlKCk7XG4gICAgICAgICAgICB9IGVsc2Uge1xuXG4gICAgICAgICAgICAgIHByZXZXZWlnaHQgPSArc2xpZGVyWzBdLm5leHRFbGVtZW50U2libGluZy5uZXh0RWxlbWVudFNpYmxpbmcuZ2V0QXR0cmlidXRlKCd2YWx1ZScpO1xuICAgICAgICAgICAgICBwcmV2VUIgPSAkY3J0RWxtdC5maW5kKCcudXBwZXJib3VuZCcpLmF0dHIoJ3ZhbHVlJyk7XG4gICAgICAgICAgICAgIHByZXZMQiA9ICRjcnRFbG10LmZpbmQoJy5sb3dlcmJvdW5kJykuYXR0cigndmFsdWUnKTtcbiAgICAgICAgICAgICAgcHJldlR5cGUgPSAkY3J0RWxtdC5maW5kKCdpbnB1dFtuYW1lKj1cInR5cGVcIl1bY2hlY2tlZD1cImNoZWNrZWRcIl0nKS52YWwoKTtcbiAgICAgICAgICAgICAgc2xpZGVyWzBdLm5leHRFbGVtZW50U2libGluZy5pbm5lckhUTUwgPSBwcmV2V2VpZ2h0ICsgJyAlJztcbiAgICAgICAgICAgICAgc2xpZGVyWzBdLm5leHRFbGVtZW50U2libGluZy5uZXh0RWxlbWVudFNpYmxpbmcudmFsdWUgPSBwcmV2V2VpZ2h0O1xuICAgICAgICAgICAgICBzbGlkZXJbMF0ubm9VaVNsaWRlci5zZXQocHJldldlaWdodCk7XG4gICAgICAgICAgICAgICRjcnRFbG10LmZpbmQoJ2lucHV0W25hbWUqPVwidHlwZVwiXScpLmVxKHByZXZUeXBlIC0gMSkucHJvcChcImNoZWNrZWRcIix0cnVlKTtcbiAgICAgICAgICAgICAgJGNydEVsbXQuZmluZCgnLnVwcGVyYm91bmQnKS52YWwocHJldlVCKTtcbiAgICAgICAgICAgICAgJGNydEVsbXQuZmluZCgnLmxvd2VyYm91bmQnKS52YWwocHJldkxCKTtcbiAgICAgICAgICAgICAgJGNydEVsbXQuZmluZCgnLmMtd2VpZ2h0aW5nJykuZW1wdHkoKS5hcHBlbmQoYCgke3ByZXZXZWlnaHR9ICUpYCk7XG4gICAgICAgICAgICAgICRjcnRFbG10LmZpbmQoJ3NlbGVjdFtuYW1lKj1cImNOYW1lXCJdJykudmFsKCRjcnRFbG10LmZpbmQoJ3NlbGVjdFtuYW1lKj1cImNOYW1lXCJdIG9wdGlvbltzZWxlY3RlZD1cInNlbGVjdGVkXCJdJykudmFsKCkpO1xuXG4gICAgICAgICAgICB9XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBidG5WLnJlbW92ZUNsYXNzKCdjbGlja2VkJyk7XG4gICAgICAgICAgICBjb25zdCB3ZWlnaHRWYWx1ZSA9ICskY3J0RWxtdC5maW5kKCcud2VpZ2h0IGlucHV0JykudmFsKCk7XG4gICAgICAgICAgICBzbGlkZXJbMF0ubmV4dEVsZW1lbnRTaWJsaW5nLm5leHRFbGVtZW50U2libGluZy5zZXRBdHRyaWJ1dGUoJ3ZhbHVlJyxzbGlkZXJbMF0ubmV4dEVsZW1lbnRTaWJsaW5nLm5leHRFbGVtZW50U2libGluZy52YWx1ZSk7XG4gICAgICAgICAgICAkY3J0RWxtdC5maW5kKCcudXBwZXJib3VuZCcpLmF0dHIoJ3ZhbHVlJywkY3J0RWxtdC5maW5kKCcudXBwZXJib3VuZCcpLnZhbCgpKTtcbiAgICAgICAgICAgICRjcnRFbG10LmZpbmQoJy5sb3dlcmJvdW5kJykuYXR0cigndmFsdWUnLCRjcnRFbG10LmZpbmQoJy5sb3dlcmJvdW5kJykudmFsKCkpO1xuICAgICAgICAgICAgJGNydEVsbXQuZmluZCgnaW5wdXRbbmFtZSo9XCJ0eXBlXCJdW2NoZWNrZWQ9XCJjaGVja2VkXCJdJykucmVtb3ZlQXR0cihcImNoZWNrZWRcIik7XG4gICAgICAgICAgICAkY3J0RWxtdC5maW5kKCdpbnB1dFtuYW1lKj1cInR5cGVcIl06Y2hlY2tlZCcpLmF0dHIoJ2NoZWNrZWQnLFwiY2hlY2tlZFwiKTtcbiAgICAgICAgICAgICRjcnRFbG10LmZpbmQoJy5jbmFtZScpLnRleHQoJGNydEVsbXQuZmluZCgnc2VsZWN0W25hbWUqPVwiY05hbWVcIl0gb3B0aW9uOnNlbGVjdGVkJykudGV4dCgpLnNwbGl0KCcgJykuc2xpY2UoMSkuam9pbignICcpKTtcbiAgICAgICAgICAgICRjcnRFbG10LmZpbmQoJy5jbmFtZScpLmF0dHIoJ2RhdGEtaWNvbicsJGNydEVsbXQuZmluZCgnc2VsZWN0W25hbWUqPVwiY05hbWVcIl0gb3B0aW9uOnNlbGVjdGVkJykuYXR0cignZGF0YS1pY29uJykpO1xuICAgICAgICAgICAgJGNydEVsbXQuZmluZCgnLmMtd2VpZ2h0aW5nJykuZW1wdHkoKS5hcHBlbmQoYCgke3dlaWdodFZhbHVlfSAlKWApO1xuICAgICAgICAgICAgJGNydEVsbXQucmVtb3ZlQ2xhc3MoJ25ldycpLnJlbW92ZUF0dHIoJ3N0eWxlJyk7XG4gICAgICAgICAgICBoYW5kbGVDTlNlbGVjdEVsZW1zKCRjcnRFbG10KTtcblxuICAgICAgICAgICAgdmFyIHNsaWRlciA9ICRjcnRFbG10LmZpbmQoJy53ZWlnaHQtY3JpdGVyaW9uLXNsaWRlcicpO1xuICAgICAgICAgICAgdmFyIG9sZFZhbHVlID0gTnVtYmVyKHNsaWRlclswXS5ub1VpU2xpZGVyLmdldCgpKTtcbiAgICAgICAgICAgIHZhciBzbGlkZXJzID0gJGNydEVsbXQuY2xvc2VzdCgnLnN0YWdlJykuZmluZCgnLndlaWdodC1jcml0ZXJpb24tc2xpZGVyJykubm90KHNsaWRlcik7XG4gICAgICAgICAgICBpZihzbGlkZXJzLmxlbmd0aCA9PSAxKXtcbiAgICAgICAgICAgICAgc2xpZGVycy5jbG9zZXN0KCcud2VpZ2h0Jykuc2hvdygpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgdmFyIHN1bVZhbCA9IDA7XG4gICAgICAgICAgICB2YXIgayA9IDA7XG4gICAgICAgICAgICB2YXIgbmV3VmFsdWUgPSAwO1xuXG4gICAgICAgICAgICAkLmVhY2goc2xpZGVycywgZnVuY3Rpb24gKGtleSwgdmFsdWUpIHtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICB2YXIgbnYgPSAoa2V5ICE9IHNsaWRlcnMubGVuZ3RoIC0gMSkgPyBcbiAgICAgICAgICAgICAgICAgIE1hdGgucm91bmQoTnVtYmVyKE51bWJlcigkKHRoaXMpWzBdLm5vVWlTbGlkZXIuZ2V0KCkpICogKDEwMCAtIHdlaWdodFZhbHVlKSAvIDEwMCkpIDpcbiAgICAgICAgICAgICAgICAgIDEwMCAtIHN1bVZhbCAtIHdlaWdodFZhbHVlO1xuXG4gICAgICAgICAgICAgICAgJCh0aGlzKVswXS5uZXh0RWxlbWVudFNpYmxpbmcuaW5uZXJIVE1MID0gbnYgKyAnICUnO1xuICAgICAgICAgICAgICAgICQodGhpcylbMF0ubmV4dEVsZW1lbnRTaWJsaW5nLm5leHRFbGVtZW50U2libGluZy52YWx1ZSA9IG52O1xuICAgICAgICAgICAgICAgICQodGhpcylbMF0ubm9VaVNsaWRlci5zZXQobnYpO1xuICAgICAgICAgICAgICAgIHN1bVZhbCArPSBudjtcbiAgICAgICAgICAgICAgICBrKys7XG4gICAgICAgICAgICAgICAgJCh2YWx1ZSkuY2xvc2VzdCgnLmNyaXRlcmlhLWxpc3QtLWl0ZW0nKS5maW5kKCcuYy13ZWlnaHRpbmcnKS5lbXB0eSgpLmFwcGVuZChgKCR7bnZ9ICUpYCk7XG4gICAgICAgIFxuICAgICAgICAgICAgfSlcbiAgICAgICAgfVxuICAgICAgICAvKmlmKG1vZEMuZmluZCgnaW5wdXRbdHlwZT1cImNoZWNrYm94XCJdJykuaXMoJzpjaGVja2VkJykgfHwgbW9kQy5maW5kKCd0ZXh0YXJlYScpLnZhbCgpICE9IFwiXCIpe1xuICAgICAgICAgICAgJCgnW2hyZWY9XCIjY3JpdGVyaW9uVGFyZ2V0XycrcysnXycrYysnXCJdJykuYWRkQ2xhc3MoJ2xpbWUgZGFya2VuLTMnKS5lbXB0eSgpLmFwcGVuZCgkKCc8dWwgY2xhc3M9XCJmbGV4LWNlbnRlciBuby1tYXJnaW5cIj4nK21vZGFsTW9kaWZ5TXNnKyc8aSBjbGFzcz1cImZhciBmYS1kb3QtY2lyY2xlXCIgc3R5bGU9XCJtYXJnaW4tbGVmdDoxMHB4XCI+PC9pPjxpIGNsYXNzPVwiZmFzIGZhLWNvbW1lbnQtZG90c1wiIHN0eWxlPVwibWFyZ2luLWxlZnQ6MTBweFwiPjwvaT48L3VsPicpKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICQoJ1tocmVmPVwiI2NyaXRlcmlvblRhcmdldF8nK3MrJ18nK2MrJ1wiXScpLnJlbW92ZUNsYXNzKCdsaW1lIGRhcmtlbi0zJykuZW1wdHkoKS5hcHBlbmQoJCgnPHVsIGNsYXNzPVwiZmxleC1jZW50ZXIgbm8tbWFyZ2luXCI+Jyttb2RhbFNldE1zZysnPGkgY2xhc3M9XCJmYXIgZmEtZG90LWNpcmNsZVwiIHN0eWxlPVwibWFyZ2luLWxlZnQ6MTBweFwiPjwvaT48aSBjbGFzcz1cImZhciBmYS1jb21tZW50XCIgc3R5bGU9XCJtYXJnaW4tbGVmdDoxMHB4XCI+PC9pPjwvdWw+JykpO1xuICAgICAgICB9Ki9cbiAgICAgIH1cbiAgICB9KVxuICAgICRjcnRFbG10LmZpbmQoJy5jcml0ZXJpb24tbW9kYWwnKS5tb2RhbCgnb3BlbicpO1xuXG5cbiAgfVxuKS5vbihcbiAgJ2NsaWNrJywgJ1tocmVmPVwiI2RlbGV0ZVBhcnRpY2lwYW50XCJdJyxcbiAgZnVuY3Rpb24oZSkge1xuICAgIGNvbnN0ICR0aGlzID0gJCh0aGlzKTtcbiAgICBjb25zdCAkcGFydGljaXBhbnRJdGVtID0gJHRoaXMuY2xvc2VzdChQQVJUSUNJUEFOVFNfSVRFTSk7XG4gICAgaWYoISRwYXJ0aWNpcGFudEl0ZW0uZGF0YSgnaWQnKSl7XG4gICAgICBlLnN0b3BQcm9wYWdhdGlvbigpO1xuICAgICAgJHBhcnRpY2lwYW50SXRlbS5yZW1vdmUoKTtcbiAgICB9IGVsc2UgeyAgXG4gICAgICBjb25zdCAkc3RhZ2VJdGVtID0gJHRoaXMuY2xvc2VzdChTVEFHRV9JVEVNKTtcbiAgICAgIGNvbnN0ICRtb2RhbERlbGV0aW9uQnRuID0gJCgnI2RlbGV0ZVBhcnRpY2lwYW50IC5yZW1vdmUtcGFydGljaXBhbnQtYnRuJyk7XG4gICAgICBsZXQgZHB1cmwgPSByZW1vdmVQYXJ0aWNpcGFudFVybFxuICAgICAgLnJlcGxhY2UoJ19fc3RnSWRfXycsICRzdGFnZUl0ZW0uZGF0YSgnaWQnKSlcbiAgICAgIC5yZXBsYWNlKCdfX2VsbXRJZF9fJywgJHBhcnRpY2lwYW50SXRlbS5kYXRhKCdpZCcpKTtcbiAgICAgICRtb2RhbERlbGV0aW9uQnRuLmRhdGEoJ2lkJywkcGFydGljaXBhbnRJdGVtLmRhdGEoJ2lkJykpO1xuICAgICAgJG1vZGFsRGVsZXRpb25CdG4ub24oJ2NsaWNrJyxhc3luYyBmdW5jdGlvbigpe1xuICAgICAgICBhd2FpdCAkLnBvc3QoZHB1cmwpO1xuICAgICAgICAkdXNlclZhbCA9ICRwYXJ0aWNpcGFudEl0ZW0uZmluZCgnc2VsZWN0W25hbWUqPVwiRGlyZWN0VXNlclwiXScpLnZhbCgpO1xuICAgICAgICAkcGFydGljaXBhbnRMaXN0ID0gJHBhcnRpY2lwYW50SXRlbS5jbG9zZXN0KCcucGFydGljaXBhbnQtbGlzdCcpO1xuICAgICAgICAkcGFydGljaXBhbnRJdGVtLnJlbW92ZSgpO1xuICAgICAgICAkcGFydGljaXBhbnRMaXN0LmZpbmQoJy5wYXJ0aWNpcGFudC1saXN0LS1pdGVtJykuZWFjaChmdW5jdGlvbihpLGUpe1xuICAgICAgICAgICQoZSkuZmluZChgc2VsZWN0W25hbWUqPVwiRGlyZWN0VXNlclwiXSBvcHRpb25bdmFsdWU9XCIkeyR1c2VyVmFsfVwiXWApLnByb3AoJ2Rpc2FibGVkJyxmYWxzZSk7XG4gICAgICAgIH0pXG4gICAgICB9KTtcbiAgICB9XG4gIH1cbikub24oXG4gICdjbGljaycsICcuYnRuLWFkZC1wYXJ0aWNpcGFudC1pLCAuYnRuLWFkZC1wYXJ0aWNpcGFudC1lJyxcbiAgZnVuY3Rpb24oKSB7XG4gICAgY29uc3QgJHRoaXMgPSAkKHRoaXMpO1xuICAgIGNvbnN0IHBUeXBlID0gJHRoaXMuaGFzQ2xhc3MoJ2J0bi1hZGQtcGFydGljaXBhbnQtaScpID8gJ2knIDogKCR0aGlzLmhhc0NsYXNzKCdidG4tYWRkLXBhcnRpY2lwYW50LWUnKSA/ICdlJyA6ICd0Jyk7XG4gICAgY29uc3QgJHNlY3Rpb24gPSAkdGhpcy5jbG9zZXN0KCdzZWN0aW9uJyk7XG4gICAgY29uc3QgJHBhcnRpY2lwYW50c0xpc3QgPSAkc2VjdGlvbi5jaGlsZHJlbigndWwucGFydGljaXBhbnRzLWxpc3QnKTtcblxuICAgIGlmICgkcGFydGljaXBhbnRzTGlzdC5jaGlsZHJlbignLm5ldycpLmxlbmd0aCkge1xuICAgICAgcmV0dXJuO1xuICAgIH1cblxuICAgIGlmKCEkcGFydGljaXBhbnRzTGlzdC5maW5kKGAucGFydGljaXBhbnRzLWxpc3QtLWl0ZW1bbW9kZT1cIiR7cFR5cGV9XCJdYCkuZmluZCgnc2VsZWN0W25hbWUqPVwiZGlyZWN0VXNlclwiXSBvcHRpb246dmlzaWJsZScpLmxlbmd0aCl7XG4gICAgICAkKCcjbm9SZW1haW5pbmdQYXJ0aWNpcGFudCcpLm1vZGFsKCdvcGVuJyk7XG4gICAgICByZXR1cm4gZmFsc2U7XG4gICAgfTtcblxuICAgIC8qKiBAdHlwZSB7SFRNTFRlbXBsYXRlRWxlbWVudH0gKi9cbiAgICBjb25zdCBwcm90byA9ICRzZWN0aW9uLmNoaWxkcmVuKGB0ZW1wbGF0ZS5wYXJ0aWNpcGFudHMtbGlzdC0taXRlbV9fcHJvdG8tJHtwVHlwZX1gKVswXTtcbiAgICBjb25zdCBwcm90b0h0bWwgPSBwcm90by5pbm5lckhUTUwudHJpbSgpO1xuICAgICRuZXdQcm90b0h0bWwgPSAkKHByb3RvSHRtbC5yZXBsYWNlKC9fX25hbWVfXy9nLCAkcGFydGljaXBhbnRzTGlzdC5jaGlsZHJlbigpLmxlbmd0aCkpO1xuXG4gICAgLypcbiAgICAkZXhpc3RpbmdQYXJ0aWNpcGFudFNlbGVjdHMgPSAkcGFydGljaXBhbnRzTGlzdC5maW5kKCdzZWxlY3RbbmFtZSo9XCJkaXJlY3RVc2VyXCJdJyk7XG4gICAgJHBhcnRpY2lwYW50SGlkZGVuU2VsZWN0ID0gJG5ld1Byb3RvSHRtbC5maW5kKCdzZWxlY3RbbmFtZSo9XCJkaXJlY3RVc2VyXCJdJyk7XG4gICAgaGlkZGVuRWxtdHMgPSBbXTtcbiAgICAvLyBSZXRyaWV2aW5nIGFsbCBwcmV2aW91cyB2YWx1ZXMgb2Ygc2VsZWN0ZWQgcGFydGljaXBhbnRzXG4gICAgJC5lYWNoKCRleGlzdGluZ1BhcnRpY2lwYW50U2VsZWN0cywgZnVuY3Rpb24oKSB7aGlkZGVuRWxtdHMucHVzaCgkKHRoaXMpLnZhbCgpKX0pO1xuXG4gICAgLy8gRGlzYWJsZSBhbGwgcHJldmlvdXNseSBpbnNlcnRlZCBwYXJ0aWNpcGFudCBpbiBuZXcgcGFydGljaXBhbnQgc2VsZWN0XG4gICAgJC5lYWNoKGhpZGRlbkVsbXRzLCBmdW5jdGlvbihrZXksIHZhbHVlKSB7XG4gICAgICAgICRwYXJ0aWNpcGFudEhpZGRlblNlbGVjdC5maW5kKCdvcHRpb25bdmFsdWU9XCInK3ZhbHVlKydcIl0nKS5wcm9wKCdkaXNhYmxlZCcsIHRydWUpO1xuICAgIH0pXG5cbiAgICBzZWxlY3RlZE5ld1BhcnRpY2lwYW50VmFsID0gJHBhcnRpY2lwYW50SGlkZGVuU2VsZWN0LmZpbmQoJ29wdGlvbjpub3QoOmRpc2FibGVkKScpLmVxKDApLnZhbCgpO1xuICAgICRwYXJ0aWNpcGFudEhpZGRlblNlbGVjdC52YWwoc2VsZWN0ZWROZXdQYXJ0aWNpcGFudFZhbCk7XG4gICAgLy8gRGlzYWJsZSBuZXcgc2VsZWN0ZWQgcGFydGljaXBhbnQgaW4gYWxsIGV4aXN0aW5nIHBhcnRpY2lwYW50IHNlbGVjdHNcblxuICAgICQuZWFjaCgkZXhpc3RpbmdQYXJ0aWNpcGFudFNlbGVjdHMsIGZ1bmN0aW9uKGtleSwgZXhpc3RpbmdQYXJ0aWNpcGFudFNlbGVjdCkge1xuICAgICAgICAkKGV4aXN0aW5nUGFydGljaXBhbnRTZWxlY3QpLmZpbmQoJ29wdGlvblt2YWx1ZT1cIicrc2VsZWN0ZWROZXdQYXJ0aWNpcGFudFZhbCsnXCJdJykucHJvcCgnZGlzYWJsZWQnLCB0cnVlKTtcbiAgICB9KVxuXG4gICAgaGFuZGxlQ05TZWxlY3RFbGVtcygpO1xuICAgICovXG4gICAkcGFydGljaXBhbnRzTGlzdC5hcHBlbmQoJG5ld1Byb3RvSHRtbCk7XG4gICBoYW5kbGVQYXJ0aWNpcGFudHNTZWxlY3RFbGVtcygkbmV3UHJvdG9IdG1sKTtcbiAgfVxuKS8qLm9uKFxuICAnY2xpY2snLCAnLmJ0bi1hZGQtY3JpdGVyaW9uJyxcbiAgZnVuY3Rpb24gKCkge1xuICAgIGNvbnN0ICR0aGlzID0gJCh0aGlzKTtcbiAgICBjb25zdCAkc2VjdGlvbiA9ICR0aGlzLmNsb3Nlc3QoJ3NlY3Rpb24nKTtcbiAgICBjb25zdCAkY3JpdGVyaWFMaXN0ID0gJHNlY3Rpb24uZmluZCgndWwuY3JpdGVyaWEtbGlzdCcpO1xuXG4gICAgaWYgKCRjcml0ZXJpYUxpc3QuY2hpbGRyZW4oJy5uZXcnKS5sZW5ndGgpIHtcbiAgICAgIHJldHVybjtcbiAgICB9XG5cbiAgICBjb25zdCBwcm90byA9ICRzZWN0aW9uLmNoaWxkcmVuKCd0ZW1wbGF0ZS5jcml0ZXJpYS1saXN0LS1pdGVtX19wcm90bycpWzBdO1xuICAgIGNvbnN0IHByb3RvSHRtbCA9XG4gICAgICBwcm90by5pbm5lckhUTUwudHJpbSgpXG4gICAgICAucmVwbGFjZSgvX18oY3xuYW1lKV9fL2csICRjcml0ZXJpYUxpc3QuY2hpbGRyZW4oKS5sZW5ndGgpO1xuICAgIGNvbnN0ICRwcm90byA9IHdpbmRvdy4kKHByb3RvSHRtbCk7XG5cbiAgICAkY3JpdGVyaWFMaXN0LmFwcGVuZCgkcHJvdG8pO1xuICAgIHNsaWRlcnMoJHRoaXMpO1xuICAgIGhhbmRsZUNOU2VsZWN0RWxlbXMoKTtcbiAgICBhZGRTZWxlY3RDaGFuZ2VMaXN0ZW5lcnMoKTtcblxuXG5cbiAgICAkcHJvdG8uZmluZCgnLm1vZGFsJykubW9kYWwoe1xuICAgICAgY29tcGxldGU6IGZ1bmN0aW9uKCkge1xuXG4gICAgICB9XG4gICAgfSk7XG4gICAgJHByb3RvLmZpbmQoJy5jcml0ZXJpb24tbW9kYWwnKS5tb2RhbCgnb3BlbicpO1xuICAgIFxuICB9XG4pKi8ub24oXG4gICdjbGljaycsICcucmVtb3ZlLXBhcnRpY2lwYW50LWJ0bicsXG4gIGZ1bmN0aW9uICgpIHtcbiAgICBjb25zdCAkdGhpcyA9ICQodGhpcyk7XG4gICAgY29uc3QgJHBhcnRpY2lwYW50SXRlbSA9ICR0aGlzLmNsb3Nlc3QoUEFSVElDSVBBTlRTX0lURU0pO1xuICAgICRwYXJ0aWNpcGFudEl0ZW0ucmVtb3ZlKCk7XG4gIH1cbikub24oXG4gICdjbGljaycsICcuZWRpdC1tb2RlIC5lZGl0LXVzZXItYnRuJywgLy8gZWRpdCBidXR0b24sIG9ubHkgd2hlbiBpbiBlZGl0IG1vZGUgKGNoZWNrIGljb24gaXMgc2hvd24pXG4gIGFzeW5jIGZ1bmN0aW9uICgpIHtcbiAgICBjb25zdCAkdGhpcyA9ICQodGhpcyk7XG4gICAgY29uc3QgJHBhcnRpY2lwYW50SXRlbSA9ICR0aGlzLmNsb3Nlc3QoUEFSVElDSVBBTlRTX0lURU0pO1xuICAgIGNvbnN0ICRzdGFnZUl0ZW0gPSAkdGhpcy5jbG9zZXN0KFNUQUdFX0lURU0pO1xuICAgIC8qKiBAdHlwZSB7SlF1ZXJ5PEhUTUxTZWxlY3RFbGVtZW50Pn0gKi9cbiAgICBjb25zdCAkdXNlclNlbGVjdCA9ICRwYXJ0aWNpcGFudEl0ZW0uZmluZCgnc2VsZWN0LnVzZXItc2VsZWN0Jyk7XG4gICAgY29uc3QgJHVzZXJOYW1lID0gJHBhcnRpY2lwYW50SXRlbS5maW5kKCcudXNlci1uYW1lJyk7XG4gICAgLyoqIEB0eXBlIHtKUXVlcnk8SFRNTElucHV0RWxlbWVudD59ICovXG4gICAgY29uc3QgJHVzZXJJc0xlYWRlciA9ICRwYXJ0aWNpcGFudEl0ZW0uZmluZCgnLnVzZXItaXMtbGVhZGVyJyk7XG4gICAgY29uc3QgdXNlcklzTGVhZGVyID0gJHVzZXJJc0xlYWRlci5sZW5ndGggPiAwID8gJHVzZXJJc0xlYWRlclswXS5jaGVja2VkIDogbnVsbDtcbiAgICAvKiogQHR5cGUge0pRdWVyeTxIVE1MU2VsZWN0RWxlbWVudD59ICovXG4gICAgY29uc3QgJHVzZXJQYXJ0aWNpcGFudFR5cGUgPSAkcGFydGljaXBhbnRJdGVtLmZpbmQoJ3NlbGVjdC51c2VyLXBhcnRpY2lwYW50LXR5cGUnKTtcbiAgICBjb25zdCB1c2VyUGFydGljaXBhbnRUeXBlID0gJHVzZXJQYXJ0aWNpcGFudFR5cGVbMF0udmFsdWU7XG5cbiAgICBpZighJCgnLmFkZC1vd25lci1sb3NlLXNldHVwLC5jaGFuZ2Utb3duZXItYnV0dG9uJykuaGFzQ2xhc3MoJ2NsaWNrZWQnKSl7ICAgXG4gICAgICAvLyBDaGFuZ2Ugb3duZXJzaGlwIG1hbmFnZW1lbnRcbiAgICAgICRwb3RlbnRpYWxEaWZmZXJlbnRMZWFkZXIgPSAkcGFydGljaXBhbnRJdGVtLmNsb3Nlc3QoJy5wYXJ0aWNpcGFudHMtbGlzdCcpLmZpbmQoJy5iYWRnZS1wYXJ0aWNpcGF0aW9uLWw6dmlzaWJsZScpO1xuICAgICAgXG4gICAgICBpZih1c2VySXNMZWFkZXIgPT0gdHJ1ZSl7XG5cbiAgICAgICAgaWYoJC5pbkFycmF5KCt1c3JSb2xlLFsyLDNdKSAhPT0gLTEgXG4gICAgICAgICYmICEkcG90ZW50aWFsRGlmZmVyZW50TGVhZGVyLmxlbmd0aCAmJiAkdXNlclNlbGVjdC52YWwoKSAhPSB1c3JJZFxuICAgICAgICB8fCAkcG90ZW50aWFsRGlmZmVyZW50TGVhZGVyLmxlbmd0aCAmJiAkcG90ZW50aWFsRGlmZmVyZW50TGVhZGVyLmNsb3Nlc3QoJy5wYXJ0aWNpcGFudHMtbGlzdC0taXRlbScpLmZpbmQoJ3NlbGVjdFtuYW1lKj1cImRpcmVjdFVzZXJcIl0nKS52YWwoKSA9PSB1c3JJZCl7XG4gICAgICAgICAgJCgnI3NldE93bmVyc2hpcExvc2VTZXR1cCcpLm1vZGFsKCdvcGVuJykuZGF0YSgnaWQnLCR0aGlzLmNsb3Nlc3QoJy5zdGFnZScpLmRhdGEoJ2lkJykpO1xuICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfSBlbHNlIGlmKCRwb3RlbnRpYWxEaWZmZXJlbnRMZWFkZXIubGVuZ3RoKXtcbiAgICAgICAgICAkKCcjY2hhbmdlT3duZXInKS5maW5kKCcuc05hbWUnKS5lbXB0eSgpLmFwcGVuZCgkdGhpcy5jbG9zZXN0KCcuc3RhZ2UnKS5maW5kKCcuc3RhZ2UtbmFtZS1maWVsZCcpLnRleHQoKSlcbiAgICAgICAgICAkKCcjY2hhbmdlT3duZXInKS5maW5kKCcjb2xkTGVhZGVyJykuZW1wdHkoKS5hcHBlbmQoJHBvdGVudGlhbERpZmZlcmVudExlYWRlci5jbG9zZXN0KCcucGFydGljaXBhbnRzLWxpc3QtLWl0ZW0nKS5maW5kKCdzZWxlY3RbbmFtZSo9XCJkaXJlY3RVc2VyXCJdIG9wdGlvbjpzZWxlY3RlZCcpLnRleHQoKSlcbiAgICAgICAgICAkKCcjY2hhbmdlT3duZXInKS5maW5kKCcjbmV3TGVhZGVyJykuZW1wdHkoKS5hcHBlbmQoJHVzZXJOYW1lLnRleHQoKSk7XG4gICAgICAgICAgJCgnI2NoYW5nZU93bmVyJykubW9kYWwoJ29wZW4nKS5kYXRhKCdpZCcsJHRoaXMuY2xvc2VzdCgnLnN0YWdlJykuZGF0YSgnaWQnKSk7XG4gICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG5cbiAgICAgIH1cbiAgICB9XG5cbiAgICBjb25zdCB1cmwgPSB2YWxpZGF0ZVBhcnRpY2lwYW50VXJsXG4gICAgLnJlcGxhY2UoJ19fc3RnSWRfXycsICRzdGFnZUl0ZW0uZGF0YSgnaWQnKSlcbiAgICAucmVwbGFjZSgnX19lbG10SWRfXycsICRwYXJ0aWNpcGFudEl0ZW0uZGF0YSgnaWQnKSB8fCAwKTtcblxuICAgICR1c2VyTmFtZS5odG1sKFxuICAgICAgJHVzZXJTZWxlY3QuY2hpbGRyZW4oJzpjaGVja2VkJykuZmlyc3QoKS5odG1sKClcbiAgICApO1xuXG4gICAgY29uc3QgcGFyYW1zID0ge1xuICAgICAgdXNlcjogJHVzZXJTZWxlY3RbMF0udmFsdWUsXG4gICAgICB0eXBlOiB1c2VyUGFydGljaXBhbnRUeXBlLFxuICAgICAgcHJlY29tbWVudDogbnVsbCxcbiAgICB9O1xuXG4gICAgaWYgKHVzZXJJc0xlYWRlcikge1xuICAgICAgcGFyYW1zLmxlYWRlciA9IHRydWU7XG4gICAgfVxuXG4gICAgaWYoISR0aGlzLmhhc0NsYXNzKCd3YXJuZWQnKSAmJiAkcGFydGljaXBhbnRJdGVtLmNsb3Nlc3QoJy5wYXJ0aWNpcGFudHMtbGlzdCcpLmZpbmQoJy5iYWRnZS1wYXJ0aWNpcGF0aW9uLXZhbGlkYXRlZCcpLmxlbmd0aCl7XG4gICAgICBcbiAgICAgIGlmKHVzZXJQYXJ0aWNpcGFudFR5cGUgIT0gMCAmJiAoJHBhcnRpY2lwYW50SXRlbS5oYXNDbGFzcygnbmV3JykgfHwgJHVzZXJQYXJ0aWNpcGFudFR5cGUuZmluZCgnb3B0aW9uW3NlbGVjdGVkPVwic2VsZWN0ZWRcIl0nKS52YWwoKSA9PSAwKSl7XG4gICAgICAgICAgXG4gICAgICAgICAgJCgnI3VudmFsaWRhdGluZ091dHB1dCcpLm1vZGFsKCdvcGVuJyk7XG4gICAgICAgICAgJCgnLnVudmFsaWRhdGUtYnRuJykuYWRkQ2xhc3MoJ3AtdmFsaWRhdGUnKS5yZW1vdmVDbGFzcygnYy12YWxpZGF0ZScpO1xuICAgICAgICAgICQoJy51bnZhbGlkYXRlLWJ0bicpLnJlbW92ZURhdGEoKVxuICAgICAgICAgICAgLmRhdGEoJ3BpZCcsJHBhcnRpY2lwYW50SXRlbS5jbG9zZXN0KCcucGFydGljaXBhbnRzLWxpc3QtLWl0ZW0nKS5kYXRhKCdpZCcpKVxuICAgICAgICAgIFxuICAgICAgICAgICQoZG9jdW1lbnQpLm9uKCdjbGljaycsJy5wLXZhbGlkYXRlJyxmdW5jdGlvbigpe1xuICAgICAgICAgICAgJGNsaWNraW5nQnRuID0gJCh0aGlzKS5kYXRhKCdwaWQnKSA/IFxuICAgICAgICAgICAgICAkKGAucGFydGljaXBhbnRzLWxpc3QtLWl0ZW1bZGF0YS1pZD1cIiR7JCh0aGlzKS5kYXRhKCdwaWQnKX1cIl1gKS5maW5kKCcuZWRpdC11c2VyLWJ0bicpIDpcbiAgICAgICAgICAgICAgJCgnLnBhcnRpY2lwYW50cy1saXN0LS1pdGVtLm5ldycpLmZpbmQoJy5lZGl0LXVzZXItYnRuJyk7XG4gICAgICAgICAgICAkY2xpY2tpbmdCdG4uYWRkQ2xhc3MoJ3dhcm5lZCcpLmNsaWNrKCk7XG4gICAgICAgICAgfSlcbiAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICB9XG4gICAgfVxuXG4gICAgY29uc3QgeyBlaWQsIHVzZXIsIGNhblNldHVwIH0gPSBhd2FpdCAkLnBvc3QodXJsLCBwYXJhbXMpO1xuXG4gICAgaWYoJHRoaXMuaGFzQ2xhc3MoJ3dhcm5lZCcpKXtcbiAgICAgICRwYXJ0aWNpcGFudEl0ZW0uY2xvc2VzdCgnLnBhcnRpY2lwYW50cy1saXN0JykuZmluZCgnLmJhZGdlLXBhcnRpY2lwYXRpb24tdmFsaWRhdGVkJykuYXR0cignc3R5bGUnLCdkaXNwbGF5Om5vbmU7Jyk7XG4gICAgICAkdGhpcy5yZW1vdmVDbGFzcygnd2FybmVkJyk7XG4gICAgfVxuXG4gICAgaWYoIWNhblNldHVwKXtcbiAgICAgICAgd2luZG93LmxvY2F0aW9uID0gJCgnLmJhY2stYnRuJykuYXR0cignaHJlZicpO1xuICAgIH1cblxuICAgICRwYXJ0aWNpcGFudEl0ZW1cbiAgICAgIC5yZW1vdmVDbGFzcygnZWRpdC1tb2RlIG5ldycpXG4gICAgICAuYXR0cignZGF0YS1pZCcsIGVpZClcbiAgICAgIC5hdHRyKCdpcy1sZWFkZXInLCB1c2VySXNMZWFkZXIpXG4gICAgICAuYXR0cigncGFydGljaXBhdGlvbi10eXBlJywgcGFydGljaXBhdGlvblR5cGVzW3VzZXJQYXJ0aWNpcGFudFR5cGVdIHx8ICcnKVxuICAgICAgLmZpbmQoJ2ltZy51c2VyLXBpY3R1cmUnKS5wcm9wKCdzcmMnLCBgL2xpYi9pbWcvJHt1c2VyLnBpY3R1cmV9YCk7XG4gICAgXG4gICAgJHBhcnRFbG10ID0gJHBhcnRpY2lwYW50SXRlbTtcbiAgICAvLyRwYXJ0RWxtdCA9ICQodGhpcykuY2xvc2VzdCgnLnBhcnRpY2lwYW50cy1saXN0LS1pdGVtJyk7XG4gICAgLy9pZighJHBhcnRFbG10Lmhhc0NsYXNzKCdlZGl0LW1vZGUnKSl7XG4gICAgICBpZigkcGFydEVsbXQuZmluZCgnLnJlbW92ZS1wYXJ0aWNpcGFudC1idG4nKS5sZW5ndGgpe1xuICAgICAgICAkcGFydEVsbXQuZmluZCgnLnJlbW92ZS1wYXJ0aWNpcGFudC1idG4nKS5yZW1vdmVDbGFzcygncmVtb3ZlLXBhcnRpY2lwYW50LWJ0bicpLmFkZENsYXNzKCdtb2RhbC10cmlnZ2VyJykuYXR0cignaHJlZicsJyNkZWxldGVQYXJ0aWNpcGFudCcpO1xuICAgICAgfVxuICAgICAgJGJhZGdlcyA9ICRwYXJ0RWxtdC5maW5kKCcuYmFkZ2VzJyk7XG4gICAgICAkYmFkZ2VzLmNoaWxkcmVuKCkuYXR0cignc3R5bGUnLCdkaXNwbGF5Om5vbmU7Jyk7XG4gICAgICBzd2l0Y2goJHBhcnRFbG10LmZpbmQoJ3NlbGVjdFtuYW1lKj1cInR5cGVcIl0nKS52YWwoKSl7XG4gICAgICAgIGNhc2UgXCIxXCI6XG4gICAgICAgICAgJGJhZGdlcy5maW5kKCcuYmFkZ2UtcGFydGljaXBhdGlvbi1hJykucmVtb3ZlQXR0cignc3R5bGUnKTticmVhaztcbiAgICAgICAgY2FzZSBcIjBcIjpcbiAgICAgICAgICAkYmFkZ2VzLmZpbmQoJy5iYWRnZS1wYXJ0aWNpcGF0aW9uLXQnKS5yZW1vdmVBdHRyKCdzdHlsZScpO2JyZWFrO1xuICAgICAgICBjYXNlIFwiLTFcIjpcbiAgICAgICAgICAkYmFkZ2VzLmZpbmQoJy5iYWRnZS1wYXJ0aWNpcGF0aW9uLXAnKS5yZW1vdmVBdHRyKCdzdHlsZScpO2JyZWFrO1xuICAgICAgfVxuICAgICAgaWYoJHBhcnRFbG10LmZpbmQoJ3NlbGVjdFtuYW1lKj1cInVuaXF1ZUV4dFBhcnRpY2lwYXRpb25zXCJdJykubGVuZ3RoKXtcbiAgICAgICAgICAkYmFkZ2VzLmZpbmQoJy5iYWRnZS1wYXJ0aWNpcGF0aW9uLWUnKS5yZW1vdmVBdHRyKCdzdHlsZScpO1xuICAgICAgfVxuICAgICAgaWYoJHBhcnRFbG10LmZpbmQoJ2lucHV0W25hbWUqPVwibGVhZGVyXCJdJykuaXMoJzpjaGVja2VkJykpe1xuICAgICAgICAgICRiYWRnZXMuZmluZCgnLmJhZGdlLXBhcnRpY2lwYXRpb24tbCcpLnJlbW92ZUF0dHIoJ3N0eWxlJyk7XG4gICAgICB9XG4gICAgICBoYW5kbGVQYXJ0aWNpcGFudHNTZWxlY3RFbGVtcygkcGFydEVsbXQpOyBcblxuICAgIFxuXG4gIH1cbikub24oXG4gICdpbnB1dCcsIGAke1NUQUdFX01PREFMfSAke1NUQUdFX05BTUVfSU5QVVR9YCxcbiAgZnVuY3Rpb24gKCkge1xuICAgIGNvbnN0ICR0aGlzID0gJCh0aGlzKTtcbiAgICBjb25zdCAkbW9kYWwgPSAkdGhpcy5jbG9zZXN0KFNUQUdFX01PREFMKTtcbiAgICBjb25zdCAkc3RhZ2VMYWJlbCA9ICRtb2RhbC5maW5kKFNUQUdFX0xBQkVMKTtcblxuICAgICRzdGFnZUxhYmVsLmh0bWwodGhpcy52YWx1ZSk7XG4gIH1cbik7XG5cbi8qXG5mdW5jdGlvbiBhZGRTZWxlY3RDaGFuZ2VMaXN0ZW5lcnMoKSB7XG4gIHdpbmRvdy4kKCdzZWxlY3QuY3JpdGVyaW9uLW5hbWUtc2VsZWN0Om5vdCgubGlzdGVuZWQpJylcbiAgICAuYWRkQ2xhc3MoJ2xpc3RlbmVkJylcbiAgICAub24oJ2NoYW5nZScsICgpID0+IGhhbmRsZUNOU2VsZWN0RWxlbXMoKSk7XG59XG4qL1xuXG4vKiRhZGRTdGFnZUJ0bi5vbignY2xpY2snLCAoKSA9PiB7XG4gIGNvbnN0ICRwcm90byA9ICQocHJvdG8pO1xuICAkcHJvdG9cbiAgICAuZGF0YSgnbmV3JywgdHJ1ZSk7XG5cbiAgJHN0YWdlQWRkSXRlbS5iZWZvcmUoJHByb3RvKTtcbiAgdG9nZ2xlU3RhZ2UoJHByb3RvKTtcbn0pO1xuKi9cblxuLyoqXG4gKiBAcGFyYW0ge0pRdWVyeX0gJGVcbiAqL1xuZnVuY3Rpb24gdG9nZ2xlU3RhZ2UoJGUpIHtcbiAgY29uc3QgJHN0YWdlSXRlbSA9XG4gICAgJGUuY2xvc2VzdChTVEFHRV9JVEVNKVxuICAgIHx8ICgkZS5pcyhTVEFHRV9JVEVNKSAmJiAkZSk7XG5cbiAgaWYgKCEkc3RhZ2VJdGVtKSByZXR1cm47XG5cbiAgLy8gcmVtb3ZlIGNsYXNzIHRvIGFsbCBzdGFnZS1pdGVtXG4gICRzdGFnZUxpc3QuY2hpbGRyZW4oU1RBR0VfSVRFTSkucmVtb3ZlQ2xhc3MoJ2FjdGl2ZScpO1xuICAvLyBkaXNwbGF5IHJlcXVlc3RlZCBzdGFnZVxuICAkc3RhZ2VJdGVtLmFkZENsYXNzKCdhY3RpdmUnKTtcbn1cblxudG9nZ2xlU3RhZ2UoJChTVEFHRV9JVEVNKS5ub3QoJy5jb21wbGV0ZWQtc3RhZ2VzLWVsZW1lbnQnKS5maXJzdCgpKTtcblxuXG4kKCcuYWN0aXZpdHktZWxlbWVudC1zYXZlLCAuYWN0aXZpdHktZWxlbWVudC11cGRhdGUnKS5vbignY2xpY2snLGZ1bmN0aW9uKGUpe1xuICBlLnByZXZlbnREZWZhdWx0KCk7XG4gICQoJy5lbGVtZW50LWlucHV0Jykuc2hvdygpO1xuICB3Z3RFbG10cyA9IFtdO1xuICAkKCcuZWxlbWVudC1pbnB1dCcpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgIGlmKCQodGhpcykuZmluZCgnLndlaWdodCAud2VpZ2h0LWlucHV0JykuaXMoJzpkaXNhYmxlZCcpKXtcbiAgICAgICAgICB3Z3RIaWRkZW5FbG10ID0gJCh0aGlzKS5maW5kKCcud2VpZ2h0IC53ZWlnaHQtaW5wdXQnKTtcbiAgICAgICAgICB3Z3RFbG10cy5wdXNoKHdndEhpZGRlbkVsbXQpO1xuICAgICAgICAgIHdndEhpZGRlbkVsbXQucHJvcCgnZGlzYWJsZWQnLGZhbHNlKTtcbiAgICAgIH1cbiAgfSk7XG5cbiAgJCgnW2NsYXNzKj1cImRwLVwiXScpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICQodGhpcykudmFsKCQodGhpcykucGlja2FkYXRlKCdwaWNrZXInKS5nZXQoJ3NlbGVjdCcsICdkZC9tbS95eXl5JykpO1xuICB9KTtcblxuICAkKCdpbnB1dFtuYW1lPVwiY2xpY2tlZC1idG5cIl0nKS5hdHRyKFwidmFsdWVcIiwgJCh0aGlzKS5oYXNDbGFzcygnYWN0aXZpdHktZWxlbWVudC1zYXZlJykgPyAnc2F2ZScgOiAndXBkYXRlJyk7XG4gICQoJ1tuYW1lPVwiYWN0aXZpdHlfZWxlbWVudF9mb3JtXCJdJykuc3VibWl0KCk7XG4gICQoJy5lbGVtZW50LWlucHV0JykuaGlkZSgpO1xuICAkLmVhY2god2d0RWxtdHMsIGZ1bmN0aW9uKCl7XG4gICAgICB3Z3RIaWRkZW5FbG10LnByb3AoJ2Rpc2FibGVkJyx0cnVlKTtcbiAgfSlcbn0pXG5cbiQoJy5hZGQtb3duZXItbG9zZS1zZXR1cCwgLmNoYW5nZS1vd25lci1idXR0b24nKS5vbignY2xpY2snLGZ1bmN0aW9uKCl7XG4gICAgJHRoaXMgPSAkKHRoaXMpO1xuICAgICR0aGlzLmFkZENsYXNzKCdjbGlja2VkJyk7XG4gICAgJCgnLmVkaXQtbW9kZSAuZWRpdC11c2VyLWJ0bicpLmNsaWNrKCk7XG4gICAgJGxvc2luZ093bmVyc2hpcFBhcnQgPSAkKGAuc3RhZ2VbZGF0YS1pZD1cIiR7ICR0aGlzLmhhc0NsYXNzKCdjaGFuZ2Utb3duZXItYnV0dG9uJykgPyAkKCcjY2hhbmdlT3duZXInKS5kYXRhKCdpZCcpIDogJCgnI3NldE93bmVyc2hpcExvc2VTZXR1cCcpLmRhdGEoJ2lkJykgfVwiXWApLmZpbmQoJy5iYWRnZS1wYXJ0aWNpcGF0aW9uLWw6dmlzaWJsZScpLmNsb3Nlc3QoJy5wYXJ0aWNpcGFudHMtbGlzdC0taXRlbScpO1xuICAgICRsb3NpbmdPd25lcnNoaXBQYXJ0LmZpbmQoJ2lucHV0W3R5cGU9XCJjaGVja2JveFwiXScpLnByb3AoJ2NoZWNrZWQnLGZhbHNlKTtcbiAgICAkbG9zaW5nT3duZXJzaGlwUGFydC5maW5kKCcuYmFkZ2UtcGFydGljaXBhdGlvbi1sOnZpc2libGUnKS5oaWRlKCk7XG4gICAgc2V0VGltZW91dChmdW5jdGlvbigpeyR0aGlzLnJlbW92ZUNsYXNzKCdjbGlja2VkJyk7fSw1MDApXG59KVxuXG5cbi8qKlxuICogRGlzYWJsZXMgb3B0aW9ucyBpbiBjcml0ZXJpb24gbmFtZSBzZWxlY3RzIGFzIGFwcHJvcHJpYXRlXG4gKiBAcGFyYW0ge0pRdWVyeXxIVE1MRWxlbWVudH0gW3RhcmdldF1cbiAqL1xuZnVuY3Rpb24gaGFuZGxlQ05TZWxlY3RFbGVtcyAodGFyZ2V0KSB7XG4gIGNvbnN0IGlzQ05hbWUgPSAoX2ksIGUpID0+IC9fY3JpdGVyaWFfXFxkK19jbmFtZS9naS50ZXN0KGUuaWQpO1xuXG4gIGNvbnN0ICRjcnRFbGVtcyA9IHRhcmdldFxuICAgID8gdGFyZ2V0LmNsb3Nlc3QoJy5jcml0ZXJpYS1saXN0JylcbiAgICA6ICQoJy5jcml0ZXJpYS1saXN0Jyk7XG4gIGNvbnN0ICRzZWxlY3RzID0gJGNydEVsZW1zLmZpbmQoJ3NlbGVjdCcpLmZpbHRlcihpc0NOYW1lKTtcblxuICAkc2VsZWN0cy5maW5kKCdvcHRpb24nKS5wcm9wKCdkaXNhYmxlZCcsIGZhbHNlKTtcblxuICBmb3IgKGNvbnN0IGNydEVsZW0gb2YgJGNydEVsZW1zKSB7XG4gICAgY29uc3QgJGNydEVsZW0gPSAkKGNydEVsZW0pO1xuICAgIGNvbnN0ICRvcHRpb25zID0gICRjcnRFbGVtLmZpbmQoJ3NlbGVjdCcpLmZpbHRlcihpc0NOYW1lKS5maW5kKCdvcHRpb24nKTtcbiAgICBjb25zdCBpblVzZSA9ICRvcHRpb25zLmZpbHRlcignOnNlbGVjdGVkJykuZ2V0KCkubWFwKGUgPT4gZS52YWx1ZSk7XG4gICAgJG9wdGlvbnNUb0Rpc2FibGUgPSAkb3B0aW9ucy5maWx0ZXIoKF9pLCBlKSA9PiBpblVzZS5pbmNsdWRlcyhlLnZhbHVlKSAmJiAhZS5zZWxlY3RlZClcbiAgICAkb3B0aW9uc1RvRGlzYWJsZS5lYWNoKChfaSAsZSkgPT4gJChlKS5wcm9wKCdkaXNhYmxlZCcsIHRydWUpKTtcbiAgICBpZih0YXJnZXQgJiYgdGFyZ2V0Lmhhc0NsYXNzKCduZXcnKSl7XG4gICAgICAkdGFyZ2V0UGFydFNlbGVjdCA9IHRhcmdldC5maW5kKCdzZWxlY3QnKS5maWx0ZXIoaXNDTmFtZSk7XG4gICAgICBwb3RlbnRpYWxEdXBsaWNhdGUgPSBpblVzZS5yZWR1Y2UoKGFjYywgdiwgaSwgYXJyKSA9PiBhcnIuaW5kZXhPZih2KSAhPT0gaSAmJiBhY2MuaW5kZXhPZih2KSA9PT0gLTEgPyBhY2MuY29uY2F0KHYpIDogYWNjLCBbXSlcbiAgICAgIGlmKHBvdGVudGlhbER1cGxpY2F0ZS5sZW5ndGgpe1xuICAgICAgICAkdGFyZ2V0UGFydFNlbGVjdC5maW5kKGBvcHRpb25bdmFsdWU9XCIke3BvdGVudGlhbER1cGxpY2F0ZVswXX1cIl1gKS5wcm9wKCdkaXNhYmxlZCcsdHJ1ZSk7XG4gICAgICAgICR0YXJnZXRQYXJ0U2VsZWN0LmZpbmQoJ29wdGlvbicpLmVhY2goZnVuY3Rpb24oaSxlKXtcbiAgICAgICAgICBpZighaW5Vc2UuaW5jbHVkZXMoJChlKS52YWwoKSkpe1xuICAgICAgICAgICAgJHRhcmdldFBhcnRTZWxlY3QudmFsKCQoZSkudmFsKCkpO1xuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgIH1cbiAgICAgICAgfSlcbiAgICAgIH1cbiAgICB9XG4gIH1cblxuXG4gIGluaXRDTkljb25zKCk7XG4gIFxuICAkKCcuc2VsZWN0LWRyb3Bkb3duIGxpJykuYWRkQ2xhc3MoJ2ZsZXgtY2VudGVyJyk7XG5cbiAgJCgnLnNlbGVjdC1kcm9wZG93biBsaSBpbWcnKS5lYWNoKGZ1bmN0aW9uKGksZSl7XG4gICAgY29uc3QgJHRoaXMgPSAkKGUpO1xuICAgICR0aGlzLmNzcyh7XG4gICAgICBoZWlnaHQgOiAnYXV0bycsXG4gICAgICB3aWR0aCA6ICcyMHB4JyxcbiAgICAgIG1hcmdpbiA6ICcwJyxcbiAgICAgIGZsb2F0IDogJ25vbmUnLFxuICAgICAgY29sb3IgOiAnIzI2YTY5YScsXG4gICAgfSk7XG4gIH0pO1xuXG59XG5cbi8qKlxuICogRGlzYWJsZXMgb3B0aW9ucyBpbiBjcml0ZXJpb24gbmFtZSBzZWxlY3RzIGFzIGFwcHJvcHJpYXRlXG4gKiBAcGFyYW0ge0pRdWVyeXxIVE1MRWxlbWVudH0gW3RhcmdldF1cbiAqL1xuZnVuY3Rpb24gaGFuZGxlUGFydGljaXBhbnRzU2VsZWN0RWxlbXMgKHRhcmdldCkge1xuICBjb25zdCBpc0NOYW1lID0gKF9pLCBlKSA9PiAvX1xcZCtfZGlyZWN0dXNlci9naS50ZXN0KGUuaWQpO1xuXG4gIGNvbnN0ICRwYXJ0RWxlbXMgPSB0YXJnZXRcbiAgICA/IHRhcmdldC5jbG9zZXN0KCcucGFydGljaXBhbnRzLWxpc3QnKVxuICAgIDogJCgnLnBhcnRpY2lwYW50cy1saXN0Jyk7XG4gIGNvbnN0ICRzZWxlY3RzID0gJHBhcnRFbGVtcy5maW5kKCdzZWxlY3QnKS5maWx0ZXIoaXNDTmFtZSk7XG5cbiAgJHNlbGVjdHMuZmluZCgnb3B0aW9uJykucHJvcCgnZGlzYWJsZWQnLCBmYWxzZSk7XG5cbiAgZm9yIChjb25zdCBwYXJ0RWxlbSBvZiAkcGFydEVsZW1zKSB7XG4gICAgJHBhcnRFbGVtID0gJChwYXJ0RWxlbSk7XG4gICAgY29uc3QgJG9wdGlvbnMgPSAgJHBhcnRFbGVtLmZpbmQoJ3NlbGVjdCcpLmZpbHRlcihpc0NOYW1lKS5maW5kKCdvcHRpb24nKTtcbiAgICBjb25zdCBpblVzZSA9ICRvcHRpb25zLmZpbHRlcignOnNlbGVjdGVkJykuZ2V0KCkubWFwKGUgPT4gZS52YWx1ZSk7XG4gICAgJG9wdGlvbnNUb0Rpc2FibGUgPSAkb3B0aW9ucy5maWx0ZXIoKF9pLCBlKSA9PiBpblVzZS5pbmNsdWRlcyhlLnZhbHVlKSAmJiAhZS5zZWxlY3RlZClcbiAgICAkb3B0aW9uc1RvRGlzYWJsZS5lYWNoKChfaSAsZSkgPT4gJChlKS5wcm9wKCdkaXNhYmxlZCcsIHRydWUpKTtcbiAgICBpZih0YXJnZXQgJiYgdGFyZ2V0Lmhhc0NsYXNzKCduZXcnKSl7XG4gICAgICAkdGFyZ2V0UGFydFNlbGVjdCA9IHRhcmdldC5maW5kKCdzZWxlY3QnKS5maWx0ZXIoaXNDTmFtZSk7XG4gICAgICBwb3RlbnRpYWxEdXBsaWNhdGUgPSBpblVzZS5yZWR1Y2UoKGFjYywgdiwgaSwgYXJyKSA9PiBhcnIuaW5kZXhPZih2KSAhPT0gaSAmJiBhY2MuaW5kZXhPZih2KSA9PT0gLTEgPyBhY2MuY29uY2F0KHYpIDogYWNjLCBbXSlcbiAgICAgIGlmKHBvdGVudGlhbER1cGxpY2F0ZS5sZW5ndGgpe1xuICAgICAgICAkdGFyZ2V0UGFydFNlbGVjdC5maW5kKGBvcHRpb25bdmFsdWU9XCIke3BvdGVudGlhbER1cGxpY2F0ZVswXX1cIl1gKS5wcm9wKCdkaXNhYmxlZCcsdHJ1ZSk7XG4gICAgICAgICR0YXJnZXRQYXJ0U2VsZWN0LmZpbmQoJ29wdGlvbicpLmVhY2goZnVuY3Rpb24oaSxlKXtcbiAgICAgICAgICBpZighaW5Vc2UuaW5jbHVkZXMoJChlKS52YWwoKSkpe1xuICAgICAgICAgICAgJHRhcmdldFBhcnRTZWxlY3QudmFsKCQoZSkudmFsKCkpO1xuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgIH1cbiAgICAgICAgfSlcbiAgICAgIH1cbiAgICB9XG4gIH0gIFxuICAkcGFydEVsZW1zLmZpbmQoJ3NlbGVjdCcpLm1hdGVyaWFsX3NlbGVjdCgpO1xufVxuXG5mdW5jdGlvbiBpbml0Q05JY29ucygpIHtcbiAgY29uc3QgJHN0eWxpemFibGVTZWxlY3RzID0gd2luZG93LiQoJ3NlbGVjdCcpO1xuICAkc3R5bGl6YWJsZVNlbGVjdHMuZmluZCgnb3B0aW9uJykuZWFjaChmdW5jdGlvbiAoX2ksIGUpIHtcbiAgICBlLmlubmVySFRNTCA9IGUuaW5uZXJIVE1MLnRyaW0oKVxuICB9KTtcbiAgJHN0eWxpemFibGVTZWxlY3RzLm1hdGVyaWFsX3NlbGVjdCgpO1xuXG4gIGNvbnN0IHJlZ0V4cCA9IC9+KC4rKX4vO1xuXG5cbiAgJCgnLnNlbGVjdC1kcm9wZG93bicpLmVhY2goZnVuY3Rpb24gKF9pLCBlKSB7XG4gICAgY29uc3QgJHRoaXMgPSAkKGUpO1xuICAgIGNvbnN0IG1hdGNoID0gJHRoaXMudmFsKCkubWF0Y2gocmVnRXhwKTtcbiAgICBsZXQgaWNvbiA9IFN0cmluZy5mcm9tQ29kZVBvaW50ICYmIG1hdGNoICYmIG1hdGNoWzFdID8gU3RyaW5nLmZyb21Db2RlUG9pbnQoJzB4JyArIG1hdGNoWzFdKSA6ICcnO1xuXG4gICAgaWYgKCR0aGlzLmlzKCdpbnB1dCcpKSB7XG4gICAgICBpZiAoIW1hdGNoKSByZXR1cm47XG4gICAgICAkdGhpcy52YWwoJHRoaXMudmFsKCkucmVwbGFjZShyZWdFeHAsIGljb24pKTtcbiAgICB9IGVsc2Uge1xuICAgICAgJHRoaXMuZmluZCgnbGkgPiBzcGFuJykuZWFjaChmdW5jdGlvbiAoX2ksIGUpIHtcbiAgICAgICAgZS5pbm5lckhUTUwgPSBlLmlubmVySFRNTC50cmltKCkucmVwbGFjZShcbiAgICAgICAgICByZWdFeHAsXG4gICAgICAgICAgYDxzcGFuIGNsYXNzPVwiY24taWNvblwiIGRhdGEtaWNvbj1cIiR7aWNvbn1cIj48L3NwYW4+YFxuICAgICAgICApO1xuICAgICAgfSk7XG4gICAgfVxuXG4gICAgJHRoaXMuYWRkQ2xhc3MoJ3N0eWxpemVkJyk7XG4gIH0pO1xuXG59XG5cbmhhbmRsZUNOU2VsZWN0RWxlbXMoKTtcbmhhbmRsZVBhcnRpY2lwYW50c1NlbGVjdEVsZW1zKCk7XG5cbiQoZG9jdW1lbnQpLm9uKCdjbGljaycsJy5jLXZhbGlkYXRlLCAucy12YWxpZGF0ZSwgLnJlbW92ZS1zdGFnZSwgLnJlbW92ZS1jcml0ZXJpb24nLCBmdW5jdGlvbigpe1xuICBsZXQgJHRoaXMgPSAkKHRoaXMpO1xuICAkdGhpcy5hZGRDbGFzcygnY2xpY2tlZCcpO1xufSlcblxuJChkb2N1bWVudCkub24oJ2NsaWNrJywnLmMtdmFsaWRhdGUtcHJpb3ItY2hlY2snLGZ1bmN0aW9uKCl7XG4gICRidG4gPSAkKHRoaXMpO1xuICAkbW9kYWwgPSAkYnRuLmNsb3Nlc3QoJy5tb2RhbCcpO1xuICBcbiAgLy9XZSBjaGVjayBpZiB1c2VyIGNoYW5nZWQgY3JpdGljYWwgZGF0YVxuICBpZihcbiAgICAkbW9kYWwuZmluZCgnc2VsZWN0W25hbWUqPVwiY05hbWVcIl0gb3B0aW9uW3NlbGVjdGVkPVwic2VsZWN0ZWRcIl0nKS52YWwoKSAhPSAkbW9kYWwuZmluZCgnc2VsZWN0W25hbWUqPVwiY05hbWVcIl0gb3B0aW9uOnNlbGVjdGVkJykudmFsKCkgXG4gICAgfHwgJG1vZGFsLmZpbmQoJ2lucHV0W25hbWUqPVwidHlwZVwiXVtjaGVja2VkPVwiY2hlY2tlZFwiXScpLnZhbCgpICE9ICRtb2RhbC5maW5kKCdpbnB1dFtuYW1lKj1cInR5cGVcIl06Y2hlY2tlZCcpLnZhbCgpXG4gICl7XG5cbiAgICAkKCcjdW52YWxpZGF0aW5nT3V0cHV0JykubW9kYWwoJ29wZW4nKTtcbiAgICAkKCcudW52YWxpZGF0ZS1idG4nKS5hZGRDbGFzcygnYy12YWxpZGF0ZScpLnJlbW92ZUNsYXNzKCdwLXZhbGlkYXRlJyk7XG4gICAgJCgnLnVudmFsaWRhdGUtYnRuJykucmVtb3ZlRGF0YSgpXG4gICAgICAuZGF0YSgnY2lkJywkbW9kYWwuY2xvc2VzdCgnLmNyaXRlcmlhLWxpc3QtLWl0ZW0nKS5kYXRhKCdpZCcpKVxuICAgICAgLmRhdGEoJ3NpZCcsJG1vZGFsLmNsb3Nlc3QoJy5zdGFnZScpLmRhdGEoJ2lkJykpXG4gICAgICAuZGF0YSgnbW9kYWxJZCcsJG1vZGFsLmF0dHIoJ2lkJykpO1xuXG4gIH0gZWxzZSB7XG4gICAgJGJ0bi5hZGRDbGFzcygnYy12YWxpZGF0ZSBtb2RhbC1jbG9zZScpLnJlbW92ZUNsYXNzKCdjLXZhbGlkYXRlLXByaW9yLWNoZWNrJykuY2xpY2soKTtcbiAgICAkYnRuLnJlbW92ZUNsYXNzKCdjLXZhbGlkYXRlIG1vZGFsLWNsb3NlJykuYWRkQ2xhc3MoJ2MtdmFsaWRhdGUtcHJpb3ItY2hlY2snKTtcbiAgfVxuXG59KVxuXG4kKGRvY3VtZW50KS5vbignY2xpY2snLCdbaHJlZj1cIiNkZWxldGVTdGFnZVwiXScsIGZ1bmN0aW9uICgpIHtcbiAgJCgnLnJlbW92ZS1zdGFnZScpLmRhdGEoJ3NpZCcsICQodGhpcykuZGF0YSgnc2lkJykpO1xuICAkKCcjZGVsZXRlU3RhZ2UnKS5jc3MoJ3otaW5kZXgnLDk5OTkpO1xufSk7XG5cbiQoZG9jdW1lbnQpLm9uKCdjbGljaycsJ1tocmVmPVwiI2RlbGV0ZUNyaXRlcmlvblwiXScsIGZ1bmN0aW9uICgpIHtcbiAgJCgnLnJlbW92ZS1jcml0ZXJpb24nKS5kYXRhKCdjaWQnLCAkKHRoaXMpLmRhdGEoJ2NpZCcpKTtcbiAgJCgnI2RlbGV0ZUNyaXRlcmlvbicpLmNzcygnei1pbmRleCcsOTk5OSk7XG59KTtcblxuXG4kKGRvY3VtZW50KS5vbignY2xpY2snLCAnLnJlbW92ZS1zdGFnZScsIGZ1bmN0aW9uIChlKSB7XG4gICAgJCgnLm1vZGFsJykubW9kYWwoJ2Nsb3NlJyk7XG4gICAgJCh0aGlzKS5hZGRDbGFzcygnY2xpY2tlZCcpO1xuICAgIHZhciByZW1vdmFibGVFbG10ID0gKCQodGhpcykuZGF0YSgnc2lkJykpID9cbiAgICAgICAgJCgnW2RhdGEtc2lkPVwiJyArICQodGhpcykuZGF0YSgnc2lkJykgKyAnXCJdJykuY2xvc2VzdCgnLnN0YWdlJykgOlxuICAgICAgICAkKHRoaXMpLmNsb3Nlc3QoJy5zdGFnZScpO1xuXG4gICAgdmFyIHNsaWRlciA9IHJlbW92YWJsZUVsbXQuZmluZCgnLndlaWdodC1zdGFnZS1zbGlkZXInKTtcbiAgICB2YXIgb2xkVmFsdWUgPSBOdW1iZXIoc2xpZGVyWzBdLm5vVWlTbGlkZXIuZ2V0KCkpO1xuICAgIHZhciBzbGlkZXJzID0gJCgnLnN0YWdlJykuZmluZCgnLndlaWdodC1zdGFnZS1zbGlkZXInKS5ub3Qoc2xpZGVyKTtcbiAgICB2YXIgc3VtVmFsID0gMDtcbiAgICB2YXIgbmV3VmFsdWUgPSAwO1xuXG4gICAgJC5lYWNoKHNsaWRlcnMsIGZ1bmN0aW9uIChrZXksIHZhbHVlKSB7XG4gICAgICAgIFxuICAgICAgdmFyIG52ID0gKGtleSAhPSBzbGlkZXJzLmxlbmdoIC0gMSkgP1xuICAgICAgICAgICAgTWF0aC5yb3VuZChOdW1iZXIoTnVtYmVyKCQodGhpcylbMF0ubm9VaVNsaWRlci5nZXQoKSkgKiAoMTAwIC0gbmV3VmFsdWUpIC8gKDEwMCAtIG9sZFZhbHVlKSkpIDpcbiAgICAgICAgICAgIDEwMCAtIHN1bVZhbDtcblxuICAgICAgJCh0aGlzKVswXS5uZXh0RWxlbWVudFNpYmxpbmcuaW5uZXJIVE1MID0gbnYgKyAnICUnO1xuICAgICAgJCh0aGlzKVswXS5uZXh0RWxlbWVudFNpYmxpbmcubmV4dEVsZW1lbnRTaWJsaW5nLnZhbHVlID0gbnY7XG4gICAgICAkKHRoaXMpWzBdLm5vVWlTbGlkZXIuc2V0KG52KTtcbiAgICAgIHN1bVZhbCArPSBudjtcblxuICAgICAgJCh2YWx1ZSkuY2xvc2VzdCgnLnN0YWdlJykuZmluZCgnLnN0YWdlLWl0ZW0tbmFtZScpLmZpbmQoJy5zLXdlaWdodGluZycpLmVtcHR5KCkuYXBwZW5kKGAoJHtudn0gJSlgKTtcbiAgICAgICQodmFsdWUpLmNsb3Nlc3QoJy5zdGFnZScpLmZpbmQoJy5zdGFnZS13ZWlnaHQnKS5maW5kKCcucy13ZWlnaHRpbmcnKS5lbXB0eSgpLmFwcGVuZChgJHtudn0gJWApO1xuICAgICAgXG4gICAgfSlcblxuICAgIGlmKCQodGhpcykuZGF0YSgnc2lkJykpe1xuICAgICAgICB1cmxUb1BpZWNlcyA9IGRzdXJsLnNwbGl0KCcvJyk7XG4gICAgICAgIHVybFRvUGllY2VzW3VybFRvUGllY2VzLmxlbmd0aC0xXSA9ICQodGhpcykuZGF0YSgnc2lkJyk7XG4gICAgICAgIHRlbXBVcmwgPSB1cmxUb1BpZWNlcy5qb2luKCcvJyk7XG5cbiAgICAgICAgJC5wb3N0KHRlbXBVcmwsbnVsbClcbiAgICAgICAgICAgIC5kb25lKGZ1bmN0aW9uKGRhdGEpIHtcblxuICAgICAgICAgICAgfSlcbiAgICAgICAgICAgIC5mYWlsKGZ1bmN0aW9uIChkYXRhKXtcbiAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhkYXRhKVxuICAgICAgICAgICAgfSlcbiAgICB9XG5cbiAgICAgIGlmKCQoJy5zdGFnZScpLmxlbmd0aCA9PSAyKXtcbiAgICAgICAgICAkKCcuc3RhZ2UnKS5maW5kKCcud2VpZ2h0JykuaGlkZSgpO1xuICAgICAgICAgICQoJy5zdGFnZScpLmZpbmQoJy53ZWlnaHQnKS5oaWRlKCk7XG4gICAgICAgICAgLy8kKCcuc3RhZ2UnKS5maW5kKCdhW2hyZWY9XCIjZGVsZXRlU3RhZ2VcIl0nKS5yZW1vdmUoKTtcbiAgICAgIH1cbiAgICAgIHJlbW92YWJsZUVsbXQucmVtb3ZlKCk7XG4gICAgICAkKCcuc3RhZ2UnKS5sYXN0KCkuYWRkQ2xhc3MoJ2FjdGl2ZScpO1xuICAgICAgXG59KTtcblxuJChkb2N1bWVudCkub24oJ2NsaWNrJywgJy5yZW1vdmUtY3JpdGVyaW9uJyxmdW5jdGlvbihlKSB7XG5cbiAgJCh0aGlzKS5hZGRDbGFzcygnY2xpY2tlZCcpO1xuICB2YXIgcmVtb3ZhYmxlRWxtdCA9ICgkKHRoaXMpLmRhdGEoJ2NpZCcpKSA/ICQoJ1tkYXRhLWNpZD1cIicgKyAkKHRoaXMpLmRhdGEoJ2NpZCcpICsgJ1wiXScpIDogJCh0aGlzKTtcbiAgdmFyIGNydEVsbXQgPSByZW1vdmFibGVFbG10LmNsb3Nlc3QoJy5jcml0ZXJpYS1saXN0LS1pdGVtJyk7XG4gIHZhciBjcml0ZXJpYUhvbGRlciA9IHJlbW92YWJsZUVsbXQuY2xvc2VzdCgnLmNyaXRlcmlhLWxpc3QnKTtcblxuICBpZiAoY3J0RWxtdC5maW5kKCcud2VpZ2h0LWNyaXRlcmlvbi1zbGlkZXInKS5sZW5ndGggPiAwKSB7XG5cbiAgICAgIHZhciBzbGlkZXIgPSBjcnRFbG10LmZpbmQoJy53ZWlnaHQtY3JpdGVyaW9uLXNsaWRlcicpO1xuICAgICAgdmFyIG9sZFZhbHVlID0gTnVtYmVyKHNsaWRlclswXS5ub1VpU2xpZGVyLmdldCgpKTtcbiAgICAgIHZhciBzbGlkZXJzID0gY3JpdGVyaWFIb2xkZXIuZmluZCgnLndlaWdodC1jcml0ZXJpb24tc2xpZGVyJykubm90KHNsaWRlcik7XG4gICAgICB2YXIgc3VtVmFsID0gMDtcbiAgICAgIHZhciBuZXdWYWx1ZSA9IDA7XG5cbiAgICAgICQuZWFjaChzbGlkZXJzLCBmdW5jdGlvbiAoa2V5LCB2YWx1ZSkge1xuICAgICAgICAgIFxuICAgICAgICAgIHZhciBudiA9IChrZXkgIT0gc2xpZGVycy5sZW5naCAtIDEpID9cbiAgICAgICAgICAgIE1hdGgucm91bmQoTnVtYmVyKE51bWJlcigkKHRoaXMpWzBdLm5vVWlTbGlkZXIuZ2V0KCkpICogKDEwMCAtIG5ld1ZhbHVlKSAvICgxMDAgLSBvbGRWYWx1ZSkpKSA6XG4gICAgICAgICAgICAxMDAgLSBzdW1WYWw7XG5cbiAgICAgICAgICAkKHRoaXMpWzBdLm5leHRFbGVtZW50U2libGluZy5pbm5lckhUTUwgPSBudiArICcgJSc7XG4gICAgICAgICAgJCh0aGlzKVswXS5uZXh0RWxlbWVudFNpYmxpbmcubmV4dEVsZW1lbnRTaWJsaW5nLnZhbHVlID0gbnY7XG4gICAgICAgICAgJCh0aGlzKVswXS5ub1VpU2xpZGVyLnNldChudik7XG4gICAgICAgICAgc3VtVmFsICs9IG52O1xuICAgICAgICAgICQodmFsdWUpLmNsb3Nlc3QoJy5jcml0ZXJpYS1saXN0LS1pdGVtJykuZmluZCgnLmMtd2VpZ2h0aW5nJykuZW1wdHkoKS5hcHBlbmQoYCgke252fSAlKWApO1xuICAgICAgICAgIFxuICAgICAgfSlcblxuICB9XG5cbiAgaWYgKCQodGhpcykuZGF0YSgnY2lkJykpIHtcblxuICAgICAgdXJsVG9QaWVjZXMgPSBkY3VybC5zcGxpdCgnLycpO1xuICAgICAgdXJsVG9QaWVjZXNbdXJsVG9QaWVjZXMubGVuZ3RoIC0gMV0gPSAkKHRoaXMpLmRhdGEoJ2NpZCcpO1xuICAgICAgdXJsID0gdXJsVG9QaWVjZXMuam9pbignLycpO1xuXG4gICAgICAkLnBvc3QodXJsLCBudWxsKVxuICAgICAgICAgIC5kb25lKGZ1bmN0aW9uIChkYXRhKSB7XG4gICAgICAgICAgICAgICQoJy5tb2RhbCcpLm1vZGFsKCdjbG9zZScpO1xuICAgICAgICAgICAgICAkKCcubW9kYWwtb3ZlcmxheScpLnJlbW92ZSgpO1xuICAgICAgICAgICAgICBjcnRFbG10LnJlbW92ZSgpO1xuICAgICAgICAgIH0pXG4gICAgICAgICAgLmZhaWwoZnVuY3Rpb24gKGRhdGEpIHtcbiAgICAgICAgICAgICAgY29uc29sZS5sb2coZGF0YSlcbiAgICAgICAgICAgICAgJC5lYWNoKCQoJy5yZWQtdGV4dCcpLGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgICAkKHRoaXMpLnJlbW92ZSgpO1xuICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgJC5lYWNoKGRhdGEsIGZ1bmN0aW9uKGtleSwgdmFsdWUpe1xuICAgICAgICAgICAgICAgICAgaWYoa2V5ID09IFwicmVzcG9uc2VKU09OXCIpe1xuICAgICAgICAgICAgICAgICAgY29uc29sZS5sb2coa2V5KTtcbiAgICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKHZhbHVlKTtcbiAgICAgICAgICAgICAgICAgICQuZWFjaCh2YWx1ZSwgZnVuY3Rpb24oY2xlLCB2YWxldXIpe1xuICAgICAgICAgICAgICAgICAgICAgICQuZWFjaCgkKCdpbnB1dCwgc2VsZWN0JyksZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgaWYoJCh0aGlzKS5pcygnW25hbWVdJykgJiYgJCh0aGlzKS5hdHRyKCduYW1lJykuaW5kZXhPZihjbGUpICE9IC0xKXtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICQodGhpcykuYWZ0ZXIoJzxkaXYgY2xhc3M9XCJyZWQtdGV4dFwiPjxzdHJvbmc+Jyt2YWxldXIrJzwvc3Ryb25nPjwvZGl2PicpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgIH0pO1xuICAgICAgICAgIH0pO1xuICB9XG5cbn0pO1xuXG4kKCcuc3RhZ2UtbW9kYWwnKS5tb2RhbCh7XG4gIGNvbXBsZXRlOiBmdW5jdGlvbigpe1xuICAgIGlmKCEkKCcucmVtb3ZlLXN0YWdlJykuaGFzQ2xhc3MoJ2NsaWNrZWQnKSl7XG4gICAgICBjb25zdCAkc3RnRWxtdCA9ICQodGhpcylbMF0uJGVsLmNsb3Nlc3QoJy5zdGFnZScpO1xuICAgICAgY29uc3QgYnRuViA9ICRzdGdFbG10LmZpbmQoJy5zLXZhbGlkYXRlJyk7XG4gICAgICBjb25zdCAkc2xpZGVyID0gJHN0Z0VsbXQuZmluZCgnLndlaWdodCAud2VpZ2h0LXN0YWdlLXNsaWRlcicpO1xuICAgICAgXG4gICAgICBpZighYnRuVi5oYXNDbGFzcygnY2xpY2tlZCcpKXtcbiAgICAgICAgICBpZigkc3RnRWxtdC5oYXNDbGFzcygnbmV3JykpeyAgXG5cbiAgICAgICAgICAgICRzdGdFbG10LnJlbW92ZSgpO1xuICAgICAgICAgICAgJCgnLnN0YWdlJykubGFzdCgpLmFkZENsYXNzKCdhY3RpdmUnKTtcbiAgICAgICAgICB9IGVsc2Uge1xuXG4gICAgICAgICAgICB2YXIgc3RhcnRDYWwgPSAgJHN0Z0VsbXQuZmluZCgnLmRwLXN0YXJ0Jyk7XG4gICAgICAgICAgICB2YXIgZW5kQ2FsID0gICRzdGdFbG10LmZpbmQoJy5kcC1lbmQnKTtcbiAgICAgICAgICAgIHZhciBnU3RhcnRDYWwgPSAgJHN0Z0VsbXQuZmluZCgnLmRwLWdzdGFydCcpO1xuICAgICAgICAgICAgdmFyIGdFbmRDYWwgPSAgJHN0Z0VsbXQuZmluZCgnLmRwLWdlbmQnKTtcbiAgICAgICAgICAgIGNvbnN0IHJlZ2V4ID0gL2phbnZpZXJ8ZsOpdnJpZXJ8bWFyc3xhdnJpbHxtYWl8anVpbnxqdWlsbGV0fGFvw7t0fHNlcHRlbWJyZXxvY3RvYnJlfG5vdmVtYnJlfGTDqWNlbWJyZXxlbmVyb3xmZWJyZXJvfG1hcnpvfGFicmlsfG1heW98anVuaW98anVsaW98YWdvc3RvfHNlcHRpZW1icmV8b2N0dWJyZXxub3ZpZW1icmV8ZGljaWVtYnJlfGphbmVpcm98ZmV2ZXJlaXJvfG1hcsOnb3xhYnJpbHxtYWlvfGp1bmhvfGp1bGhvfGFnb3N0b3xzZXRlbWJyb3xvdXR1YnJvfG5vdmVtYnJvfGRlemVtYnJvL2cgO1xuICAgICAgICAgICAgdmFyIHN0YXJ0RGF0ZVRTID0gKHN0YXJ0Q2FsLnZhbCgpID09IFwiXCIpID8gaW5pdFN0YXJ0RGF0ZSA6IHBhcnNlRGRtbXl5eXkoc3RhcnRDYWwuYXR0cigndmFsdWUnKS5yZXBsYWNlKHJlZ2V4LGZ1bmN0aW9uKG1hdGNoKXtyZXR1cm4gcmVwbGFjZVZhcnNbbWF0Y2hdO30pKTtcbiAgICAgICAgICAgIHZhciBlbmREYXRlVFMgPSAoZW5kQ2FsLnZhbCgpID09IFwiXCIpID8gaW5pdEVuZERhdGUgOiBwYXJzZURkbW15eXl5KGVuZENhbC5hdHRyKCd2YWx1ZScpLnJlcGxhY2UocmVnZXgsZnVuY3Rpb24obWF0Y2gpe3JldHVybiByZXBsYWNlVmFyc1ttYXRjaF07fSkpO1xuICAgICAgICAgICAgdmFyIGdTdGFydERhdGVUUyA9IChnU3RhcnRDYWwudmFsKCkgPT0gXCJcIikgPyBpbml0R1N0YXJ0RGF0ZSA6IHBhcnNlRGRtbXl5eXkoZ1N0YXJ0Q2FsLmF0dHIoJ3ZhbHVlJykucmVwbGFjZShyZWdleCxmdW5jdGlvbihtYXRjaCl7cmV0dXJuIHJlcGxhY2VWYXJzW21hdGNoXTt9KSk7XG4gICAgICAgICAgICB2YXIgZ0VuZERhdGVUUyA9IChnRW5kQ2FsLnZhbCgpID09IFwiXCIpID8gaW5pdEdFbmREYXRlIDogcGFyc2VEZG1teXl5eShnRW5kQ2FsLmF0dHIoJ3ZhbHVlJykucmVwbGFjZShyZWdleCxmdW5jdGlvbihtYXRjaCl7cmV0dXJuIHJlcGxhY2VWYXJzW21hdGNoXTt9KSk7XG4gICAgICAgICAgICB2YXIgc3RhcnREYXRlID0gbmV3IERhdGUoc3RhcnREYXRlVFMpO1xuICAgICAgICAgICAgdmFyIGVuZERhdGUgPSBuZXcgRGF0ZShlbmREYXRlVFMpO1xuICAgICAgICAgICAgdmFyIGdTdGFydERhdGUgPSBuZXcgRGF0ZShnU3RhcnREYXRlVFMpO1xuICAgICAgICAgICAgdmFyIGdFbmREYXRlID0gbmV3IERhdGUoZ0VuZERhdGVUUyk7XG5cbiAgICAgICAgICAgIHN0YXJ0Q2FsLnBpY2thZGF0ZSgncGlja2VyJykuc2V0KCdzZWxlY3QnLHN0YXJ0RGF0ZSk7XG4gICAgICAgICAgICBlbmRDYWwucGlja2FkYXRlKCdwaWNrZXInKS5zZXQoJ3NlbGVjdCcsZW5kRGF0ZSkuc2V0KCdtaW4nLHN0YXJ0RGF0ZSk7XG4gICAgICAgICAgICBnU3RhcnRDYWwucGlja2FkYXRlKCdwaWNrZXInKS5zZXQoJ3NlbGVjdCcsZ1N0YXJ0RGF0ZSkuc2V0KCdtaW4nLHN0YXJ0RGF0ZSk7XG4gICAgICAgICAgICBnRW5kQ2FsLnBpY2thZGF0ZSgncGlja2VyJykuc2V0KCdzZWxlY3QnLGdFbmREYXRlKS5zZXQoJ21pbicsZ1N0YXJ0RGF0ZSk7XG5cbiAgICAgICAgICAgIHByZXZXZWlnaHQgPSArJHNsaWRlclswXS5uZXh0RWxlbWVudFNpYmxpbmcubmV4dEVsZW1lbnRTaWJsaW5nLmdldEF0dHJpYnV0ZSgndmFsdWUnKTtcbiAgICAgICAgICAgIHN0Z05hbWUgPSAkc3RnRWxtdC5maW5kKCdpbnB1dFtuYW1lKj1cIm5hbWVcIl0nKS5hdHRyKCd2YWx1ZScpO1xuICAgICAgICAgICAgc3RnTW9kZSA9ICRzdGdFbG10LmZpbmQoJ2lucHV0W25hbWUqPVwibW9kZVwiXVtjaGVja2VkPVwiY2hlY2tlZFwiXScpLnZhbCgpO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAkc2xpZGVyWzBdLm5leHRFbGVtZW50U2libGluZy5pbm5lckhUTUwgPSBwcmV2V2VpZ2h0ICsgJyAlJztcbiAgICAgICAgICAgICRzbGlkZXJbMF0ubmV4dEVsZW1lbnRTaWJsaW5nLm5leHRFbGVtZW50U2libGluZy52YWx1ZSA9IHByZXZXZWlnaHQ7XG4gICAgICAgICAgICAkc2xpZGVyWzBdLm5vVWlTbGlkZXIuc2V0KHByZXZXZWlnaHQpO1xuICAgICAgICAgICAgJHN0Z0VsbXQuZmluZChgaW5wdXRbbmFtZSo9XCJtb2RlXCJdW3ZhbHVlID0gJHtzdGdNb2RlfV1gKS5wcm9wKFwiY2hlY2tlZFwiLHRydWUpO1xuICAgICAgICAgICAgJHN0Z0VsbXQuZmluZCgnaW5wdXRbbmFtZSo9XCJuYW1lXCJdJykudmFsKHN0Z05hbWUpO1xuICAgICAgICAgICAgJHN0Z0VsbXQuZmluZCgnLnN0YWdlLWl0ZW0tbmFtZScpLmZpbmQoJy5zLXdlaWdodGluZycpLmVtcHR5KCkuYXBwZW5kKGAoJHtwcmV2V2VpZ2h0fSAlKWApO1xuICAgICAgICAgICAgJHN0Z0VsbXQuZmluZCgnLnN0YWdlLXdlaWdodCcpLmZpbmQoJy5zLXdlaWdodGluZycpLmVtcHR5KCkuYXBwZW5kKGAke3ByZXZXZWlnaHR9ICVgKTtcbiAgICAgICAgICAgICRzdGdFbG10LmZpbmQoJ3NlbGVjdFtuYW1lKj1cInZpc2liaWxpdHlcIl0nKS52YWwoJHN0Z0VsbXQuZmluZCgnc2VsZWN0W25hbWUqPVwidmlzaWJpbGl0eVwiXSBvcHRpb25bc2VsZWN0ZWQ9XCJzZWxlY3RlZFwiXScpLnZhbCgpKTtcblxuICAgICAgICAgIH1cbiAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgY29uc3Qgd2VpZ2h0VmFsdWUgPSArJHN0Z0VsbXQuZmluZCgnLndlaWdodCBpbnB1dCcpLnZhbCgpO1xuICAgICAgICAgIGJ0blYucmVtb3ZlQ2xhc3MoJ2NsaWNrZWQnKTtcbiAgICAgICAgICAkc3RnRWxtdC5maW5kKCdpbnB1dFtuYW1lKj1cIm5hbWVcIl0nKS5hdHRyKCd2YWx1ZScsJHN0Z0VsbXQuZmluZCgnaW5wdXRbbmFtZSo9XCJuYW1lXCJdJykudmFsKCkpO1xuICAgICAgICAgICRzdGdFbG10LmZpbmQoJ2lucHV0W25hbWUqPVwibW9kZVwiXVtjaGVja2VkPVwiY2hlY2tlZFwiXScpLnJlbW92ZUF0dHIoXCJjaGVja2VkXCIpO1xuICAgICAgICAgICRzdGdFbG10LmZpbmQoJ2lucHV0W25hbWUqPVwibW9kZVwiXTpjaGVja2VkJykuYXR0cignY2hlY2tlZCcsXCJjaGVja2VkXCIpO1xuICAgICAgICAgICRzdGdFbG10LmZpbmQoJy5zdGFnZS1uYW1lLWZpZWxkJykudGV4dCgkc3RnRWxtdC5maW5kKCdpbnB1dFtuYW1lKj1cIm5hbWVcIl0nKS52YWwoKSk7XG4gICAgICAgICAgJHN0Z0VsbXQuZmluZCgnLnN0YWdlLWl0ZW0tbmFtZScpLmZpbmQoJy5zLXdlaWdodGluZycpLmVtcHR5KCkuYXBwZW5kKGAoJHt3ZWlnaHRWYWx1ZX0gJSlgKTtcbiAgICAgICAgICAkc3RnRWxtdC5maW5kKCcuc3RhZ2Utd2VpZ2h0JykuZmluZCgnLnMtd2VpZ2h0aW5nJykuZW1wdHkoKS5hcHBlbmQoYCR7d2VpZ2h0VmFsdWV9ICVgKTtcbiAgICAgICAgICAkc3RnRWxtdC5yZW1vdmVDbGFzcygnbmV3JykucmVtb3ZlQXR0cignc3R5bGUnKTtcbiAgICAgICAgICBoYW5kbGVDTlNlbGVjdEVsZW1zKCRzdGdFbG10KTtcbiAgICAgICAgICBjb25zdCAkc2xpZGVycyA9ICQoJy5zdGFnZSAud2VpZ2h0JykuZmluZCgnLndlaWdodC1zdGFnZS1zbGlkZXInKS5ub3QoJHNsaWRlcik7XG4gICAgICAgICAgaWYoJHNsaWRlcnMubGVuZ3RoID09IDEpe1xuICAgICAgICAgICAgJHNsaWRlcnMuY2xvc2VzdCgnLndlaWdodCcpLnNob3coKTtcbiAgICAgICAgICB9XG4gICAgICAgICAgXG4gICAgICAgICAgdmFyIG9sZFZhbHVlID0gJHN0Z0VsbXQuZmluZCgnLndlaWdodCBpbnB1dCcpLmF0dHIoJ3ZhbHVlJyk7XG4gICAgICAgICAgdmFyIHN1bVZhbCA9IDA7XG4gICAgICAgICAgdmFyIG5ld1ZhbHVlID0gd2VpZ2h0VmFsdWU7XG5cbiAgICAgICAgICAkLmVhY2goJHNsaWRlcnMsIGZ1bmN0aW9uIChrZXksIHZhbHVlKSB7XG4gICAgICAgICAgICBcbiAgICAgICAgICAgICAgdmFyIG52ID0gKGtleSAhPSBzbGlkZXJzLmxlbmdoIC0gMSkgP1xuICAgICAgICAgICAgICAgIE1hdGgucm91bmQoTnVtYmVyKE51bWJlcigkKHRoaXMpWzBdLm5vVWlTbGlkZXIuZ2V0KCkpICogKDEwMCAtIG5ld1ZhbHVlKSAvICgxMDAgLSBvbGRWYWx1ZSkpKSA6XG4gICAgICAgICAgICAgICAgMTAwIC0gc3VtVmFsO1xuXG4gICAgICAgICAgICAgICQodGhpcylbMF0ubmV4dEVsZW1lbnRTaWJsaW5nLmlubmVySFRNTCA9IG52ICsgJyAlJztcbiAgICAgICAgICAgICAgJCh0aGlzKVswXS5uZXh0RWxlbWVudFNpYmxpbmcubmV4dEVsZW1lbnRTaWJsaW5nLnZhbHVlID0gbnY7XG4gICAgICAgICAgICAgICQodGhpcylbMF0ubm9VaVNsaWRlci5zZXQobnYpO1xuICAgICAgICAgICAgICBzdW1WYWwgKz0gbnY7XG4gICAgICAgICAgICAgICQodmFsdWUpLmNsb3Nlc3QoJy5zdGFnZScpLmZpbmQoJy5zdGFnZS1pdGVtLW5hbWUnKS5maW5kKCcucy13ZWlnaHRpbmcnKS5lbXB0eSgpLmFwcGVuZChgKCR7bnZ9ICUpYCk7XG4gICAgICAgICAgICAgICQodmFsdWUpLmNsb3Nlc3QoJy5zdGFnZScpLmZpbmQoJy5zdGFnZS13ZWlnaHQnKS5maW5kKCcucy13ZWlnaHRpbmcnKS5lbXB0eSgpLmFwcGVuZChgJHtudn0gJWApO1xuICAgICAgICAgICAgICBcbiAgICAgICAgICB9KVxuICAgICAgICAgICRzdGdFbG10LmZpbmQoJy53ZWlnaHQgaW5wdXQnKS5hdHRyKCd2YWx1ZScsbmV3VmFsdWUpO1xuICAgICAgfVxuICAgIH0gZWxzZSB7XG4gICAgICAkKCcucmVtb3ZlLXN0YWdlJykucmVtb3ZlQ2xhc3MoJ2NsaWNrZWQnKTtcbiAgICB9ICAgICAgXG4gIH1cbn0pXG4gIFxuJCgnLmNyaXRlcmlvbi1tb2RhbCcpLm1vZGFsKHtcbiAgY29tcGxldGU6IGZ1bmN0aW9uKCl7XG5cbiAgICBsZXQgbW9kQyA9ICQodGhpcylbMF0uJGVsO1xuICAgIGxldCAkY3J0RWxtdCA9IG1vZEMuY2xvc2VzdCgnLmNyaXRlcmlhLWxpc3QtLWl0ZW0nKTtcbiAgICBsZXQgYnRuViA9ICRjcnRFbG10LmZpbmQoJy5jLXZhbGlkYXRlJyk7XG4gICAgdmFyIHNsaWRlciA9ICRjcnRFbG10LmZpbmQoJy53ZWlnaHQtY3JpdGVyaW9uLXNsaWRlcicpO1xuICAgIHByZXZXZWlnaHQgPSArc2xpZGVyWzBdLm5leHRFbGVtZW50U2libGluZy5uZXh0RWxlbWVudFNpYmxpbmcuZ2V0QXR0cmlidXRlKCd2YWx1ZScpO1xuICAgIFxuICAgIGlmKCFidG5WLmhhc0NsYXNzKCdjbGlja2VkJykpe1xuXG4gICAgICBwcmV2VUIgPSAkY3J0RWxtdC5maW5kKCcudXBwZXJib3VuZCcpLmF0dHIoJ3ZhbHVlJyk7XG4gICAgICBwcmV2TEIgPSAkY3J0RWxtdC5maW5kKCcubG93ZXJib3VuZCcpLmF0dHIoJ3ZhbHVlJyk7XG4gICAgICBwcmV2VHlwZSA9ICRjcnRFbG10LmZpbmQoJ2lucHV0W25hbWUqPVwidHlwZVwiXVtjaGVja2VkPVwiY2hlY2tlZFwiXScpLnZhbCgpO1xuICAgICAgc2xpZGVyWzBdLm5leHRFbGVtZW50U2libGluZy5pbm5lckhUTUwgPSBwcmV2V2VpZ2h0ICsgJyAlJztcbiAgICAgIHNsaWRlclswXS5uZXh0RWxlbWVudFNpYmxpbmcubmV4dEVsZW1lbnRTaWJsaW5nLnZhbHVlID0gcHJldldlaWdodDtcbiAgICAgIHNsaWRlclswXS5ub1VpU2xpZGVyLnNldChwcmV2V2VpZ2h0KTtcbiAgICAgICRjcnRFbG10LmZpbmQoJ2lucHV0W25hbWUqPVwidHlwZVwiXScpLmVxKHByZXZUeXBlIC0gMSkucHJvcChcImNoZWNrZWRcIix0cnVlKTtcbiAgICAgICRjcnRFbG10LmZpbmQoJy51cHBlcmJvdW5kJykudmFsKHByZXZVQik7XG4gICAgICAkY3J0RWxtdC5maW5kKCcubG93ZXJib3VuZCcpLnZhbChwcmV2TEIpO1xuICAgICAgJGNydEVsbXQuZmluZCgnLmMtd2VpZ2h0aW5nJykuZW1wdHkoKS5hcHBlbmQoYCgke3ByZXZXZWlnaHR9ICUpYCk7XG4gICAgICAkY3J0RWxtdC5maW5kKCdzZWxlY3RbbmFtZSo9XCJjTmFtZVwiXScpLnZhbCgkY3J0RWxtdC5maW5kKCdzZWxlY3RbbmFtZSo9XCJjTmFtZVwiXSBvcHRpb25bc2VsZWN0ZWQ9XCJzZWxlY3RlZFwiXScpLnZhbCgpKTtcbiAgICAgICAgXG4gICAgfSBlbHNlIHtcblxuICAgICAgICBidG5WLnJlbW92ZUNsYXNzKCdjbGlja2VkJyk7XG4gICAgICAgIHZhciBuZXdWYWx1ZSA9ICskY3J0RWxtdC5maW5kKCcud2VpZ2h0IGlucHV0JykudmFsKCk7XG4gICAgICAgIHNsaWRlclswXS5uZXh0RWxlbWVudFNpYmxpbmcubmV4dEVsZW1lbnRTaWJsaW5nLnNldEF0dHJpYnV0ZSgndmFsdWUnLHNsaWRlclswXS5uZXh0RWxlbWVudFNpYmxpbmcubmV4dEVsZW1lbnRTaWJsaW5nLnZhbHVlKTtcbiAgICAgICAgJGNydEVsbXQuZmluZCgnLnVwcGVyYm91bmQnKS5hdHRyKCd2YWx1ZScsJGNydEVsbXQuZmluZCgnLnVwcGVyYm91bmQnKS52YWwoKSk7XG4gICAgICAgICRjcnRFbG10LmZpbmQoJy5sb3dlcmJvdW5kJykuYXR0cigndmFsdWUnLCRjcnRFbG10LmZpbmQoJy5sb3dlcmJvdW5kJykudmFsKCkpO1xuICAgICAgICAkY3J0RWxtdC5maW5kKCdpbnB1dFtuYW1lKj1cInR5cGVcIl1bY2hlY2tlZD1cImNoZWNrZWRcIl0nKS5yZW1vdmVBdHRyKFwiY2hlY2tlZFwiKTtcbiAgICAgICAgJGNydEVsbXQuZmluZCgnaW5wdXRbbmFtZSo9XCJ0eXBlXCJdOmNoZWNrZWQnKS5hdHRyKCdjaGVja2VkJyxcImNoZWNrZWRcIik7XG4gICAgICAgICRjcnRFbG10LmZpbmQoJy5jbmFtZScpLnRleHQoJGNydEVsbXQuZmluZCgnc2VsZWN0W25hbWUqPVwiY05hbWVcIl0gb3B0aW9uOnNlbGVjdGVkJykudGV4dCgpLnNwbGl0KCcgJykuc2xpY2UoMSkuam9pbignICcpKTtcbiAgICAgICAgJGNydEVsbXQuZmluZCgnLmNuYW1lJykuYXR0cignZGF0YS1pY29uJywkY3J0RWxtdC5maW5kKCdzZWxlY3RbbmFtZSo9XCJjTmFtZVwiXSBvcHRpb246c2VsZWN0ZWQnKS5hdHRyKCdkYXRhLWljb24nKSk7XG4gICAgICAgICRjcnRFbG10LmZpbmQoJy5jLXdlaWdodGluZycpLmVtcHR5KCkuYXBwZW5kKGAoJHtuZXdWYWx1ZX0gJSlgKTtcbiAgICAgICAgXG5cbiAgICAgICAgdmFyIHNsaWRlciA9ICRjcnRFbG10LmZpbmQoJy53ZWlnaHQtY3JpdGVyaW9uLXNsaWRlcicpO1xuICAgICAgICB2YXIgb2xkVmFsdWUgPSBwcmV2V2VpZ2h0O1xuICAgICAgICB2YXIgc2xpZGVycyA9ICRjcnRFbG10LmNsb3Nlc3QoJy5zdGFnZScpLmZpbmQoJy53ZWlnaHQtY3JpdGVyaW9uLXNsaWRlcicpLm5vdChzbGlkZXIpO1xuICAgICAgICB2YXIgc3VtVmFsID0gMDtcblxuICAgICAgICAkLmVhY2goc2xpZGVycywgZnVuY3Rpb24gKGtleSwgdmFsdWUpIHtcbiAgICAgICAgICBcbiAgICAgICAgICAgIHZhciBudiA9IChrZXkgIT0gc2xpZGVycy5sZW5naCAtIDEpID9cbiAgICAgICAgICAgICAgTWF0aC5yb3VuZChOdW1iZXIoTnVtYmVyKCQodGhpcylbMF0ubm9VaVNsaWRlci5nZXQoKSkgKiAoMTAwIC0gbmV3VmFsdWUpIC8gKDEwMCAtIG9sZFZhbHVlKSkpIDpcbiAgICAgICAgICAgICAgMTAwIC0gc3VtVmFsO1xuXG4gICAgICAgICAgICAkKHRoaXMpWzBdLm5leHRFbGVtZW50U2libGluZy5pbm5lckhUTUwgPSBudiArICcgJSc7XG4gICAgICAgICAgICAkKHRoaXMpWzBdLm5leHRFbGVtZW50U2libGluZy5uZXh0RWxlbWVudFNpYmxpbmcudmFsdWUgPSBudjtcbiAgICAgICAgICAgICQodGhpcylbMF0ubmV4dEVsZW1lbnRTaWJsaW5nLm5leHRFbGVtZW50U2libGluZy5zZXRBdHRyaWJ1dGUoJ3ZhbHVlJyxudik7IFxuICAgICAgICAgICAgJCh0aGlzKVswXS5ub1VpU2xpZGVyLnNldChudik7XG4gICAgICAgICAgICBzdW1WYWwgKz0gbnY7XG4gICAgICAgICAgICAkKHZhbHVlKS5jbG9zZXN0KCcuY3JpdGVyaWEtbGlzdC0taXRlbScpLmZpbmQoJy5jLXdlaWdodGluZycpLmVtcHR5KCkuYXBwZW5kKGAoJHtudn0gJSlgKTtcbiAgICAgICAgICAgIFxuICAgICAgICB9KVxuICAgIH1cbiAgICBoYW5kbGVDTlNlbGVjdEVsZW1zKCRjcnRFbG10KTtcbiAgICAvKmlmKG1vZEMuZmluZCgnaW5wdXRbdHlwZT1cImNoZWNrYm94XCJdJykuaXMoJzpjaGVja2VkJykgfHwgbW9kQy5maW5kKCd0ZXh0YXJlYScpLnZhbCgpICE9IFwiXCIpe1xuICAgICAgICAkKCdbaHJlZj1cIiNjcml0ZXJpb25UYXJnZXRfJytzKydfJytjKydcIl0nKS5hZGRDbGFzcygnbGltZSBkYXJrZW4tMycpLmVtcHR5KCkuYXBwZW5kKCQoJzx1bCBjbGFzcz1cImZsZXgtY2VudGVyIG5vLW1hcmdpblwiPicrbW9kYWxNb2RpZnlNc2crJzxpIGNsYXNzPVwiZmFyIGZhLWRvdC1jaXJjbGVcIiBzdHlsZT1cIm1hcmdpbi1sZWZ0OjEwcHhcIj48L2k+PGkgY2xhc3M9XCJmYXMgZmEtY29tbWVudC1kb3RzXCIgc3R5bGU9XCJtYXJnaW4tbGVmdDoxMHB4XCI+PC9pPjwvdWw+JykpO1xuICAgIH0gZWxzZSB7XG4gICAgICAgICQoJ1tocmVmPVwiI2NyaXRlcmlvblRhcmdldF8nK3MrJ18nK2MrJ1wiXScpLnJlbW92ZUNsYXNzKCdsaW1lIGRhcmtlbi0zJykuZW1wdHkoKS5hcHBlbmQoJCgnPHVsIGNsYXNzPVwiZmxleC1jZW50ZXIgbm8tbWFyZ2luXCI+Jyttb2RhbFNldE1zZysnPGkgY2xhc3M9XCJmYXIgZmEtZG90LWNpcmNsZVwiIHN0eWxlPVwibWFyZ2luLWxlZnQ6MTBweFwiPjwvaT48aSBjbGFzcz1cImZhciBmYS1jb21tZW50XCIgc3R5bGU9XCJtYXJnaW4tbGVmdDoxMHB4XCI+PC9pPjwvdWw+JykpO1xuICAgIH0qL1xuICB9XG59KTtcblxuJChkb2N1bWVudCkub24oJ2NoYW5nZScsJ3NlbGVjdFtuYW1lKj1cImNOYW1lXCJdJyxmdW5jdGlvbigpe1xuICAgICRzZWxlY3QgPSAkKHRoaXMpO1xuICAgICRtYXRlcmFsaXplU2VsZWN0ID0gJCh0aGlzKS5jbG9zZXN0KCcuY3JpdGVyaW9uLW5hbWUnKS5maW5kKCdpbnB1dCcpO1xuICAgIGNvbnN0IHJlZ0V4cCA9IC9+KC4rKX4vO1xuICAgIGNvbnN0IG1hdGNoID0gJHNlbGVjdC5maW5kKCdvcHRpb246c2VsZWN0ZWQnKS50ZXh0KCkubWF0Y2gocmVnRXhwKTtcbiAgICBsZXQgaWNvbiA9IFN0cmluZy5mcm9tQ29kZVBvaW50ICYmIG1hdGNoICYmIG1hdGNoWzFdID8gU3RyaW5nLmZyb21Db2RlUG9pbnQoJzB4JyArIG1hdGNoWzFdKSA6ICcnO1xuXG4gICAgaWYgKCFtYXRjaCkgcmV0dXJuO1xuICAgICRtYXRlcmFsaXplU2VsZWN0LnZhbChpY29uICsgJG1hdGVyYWxpemVTZWxlY3QudmFsKCkpO1xufSk7XG5cbiQoZG9jdW1lbnQpLm9uKCdjaGFuZ2UnLCAnLmdyYWRldHlwZScsIGZ1bmN0aW9uICgpIHtcbiAgICB2YXIgayA9IDA7XG4gICAgdmFyIGNydEVsbXQgPSAkKHRoaXMpLmNsb3Nlc3QoJy5jcml0ZXJpb24nKTtcbiAgICB2YXIgc2xpZGVycyA9IGNydEVsbXQuY2xvc2VzdCgnLnN0YWdlJykuZmluZCgnLndlaWdodC1jcml0ZXJpb24tc2xpZGVyJyk7XG4gICAgdmFyIGNydEluZGV4ID0gJCh0aGlzKS5jbG9zZXN0KCcuc3RhZ2UnKS5maW5kKCcuY3JpdGVyaW9uJykuaW5kZXgoY3J0RWxtdCk7XG5cbiAgICB2YXIgb2xkVmFsdWUgPSBOdW1iZXIoY3J0RWxtdC5maW5kKCcud2VpZ2h0LWNyaXRlcmlvbi1zbGlkZXInKVswXS5ub1VpU2xpZGVyLmdldCgpKTtcblxuICAgIGlmIChjcnRFbG10LmZpbmQoJy5ncmFkZXR5cGUgOmNoZWNrZWQnKS52YWwoKSAhPSAxKSB7XG4gICAgICAgIGNydEVsbXQuZmluZCgnLnNjYWxlIDp0ZXh0Om5vdCgud2VpZ2h0LWlucHV0KScpLmF0dHIoJ2Rpc2FibGVkJywgJ2Rpc2FibGVkJyk7XG4gICAgfSBlbHNlIHtcbiAgICAgICAgY3J0RWxtdC5maW5kKCcuc2NhbGUgOnRleHQ6bm90KC53ZWlnaHQtaW5wdXQpJykucmVtb3ZlQXR0cignZGlzYWJsZWQnKTtcbiAgICB9XG5cbiAgICAvLyBIaWRlIG9yIGRpc3BsYXkgd2VpZ2h0IGlucHV0LCBkZXBlbmRpbmcgb24gbnVtYmVyIG9mIG5vbi1wdXJlLWNvbW1lbnRzIGVsZW1lbnRzXG4gICAgdmFyIGsgPSAwO1xuICAgICQodGhpcykuY2xvc2VzdCgnLnN0YWdlJykuZmluZCgnLmNyaXRlcmlvbiBpbnB1dFt0eXBlPVwicmFkaW9cIl06Y2hlY2tlZCcpLmVhY2goZnVuY3Rpb24oa2V5LHNlbGVjdGVkUmFkaW9CdG4pe1xuICAgICAgICBpZiAoc2VsZWN0ZWRSYWRpb0J0bi52YWx1ZSAhPSAyKXtcbiAgICAgICAgICAgIGsrKztcbiAgICAgICAgfVxuICAgICAgICBpZihrID4gMSl7XG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cbiAgICB9KVxuXG4gICAgaWYgKGsgPiAxICYmIGNydEVsbXQuZmluZCgnaW5wdXRbdHlwZT1cInJhZGlvXCJdOmNoZWNrZWQnKS52YWwoKSAhPSAyKSB7XG4gICAgICAgIGNydEVsbXQuZmluZCgnLndlaWdodCcpLnNob3coKTtcbiAgICB9IGVsc2Uge1xuICAgICAgICBjcnRFbG10LmZpbmQoJy53ZWlnaHQnKS5oaWRlKCk7XG4gICAgfVxuXG5cbiAgICBpZiAoY3J0RWxtdC5maW5kKCdpbnB1dFt0eXBlPVwicmFkaW9cIl0nKS5lcSgxKS5pcygnOmNoZWNrZWQnKSkge1xuXG5cbiAgICAgICAgdmFyIHNsaWRlciA9IGNydEVsbXQuZmluZCgnLndlaWdodC1jcml0ZXJpb24tc2xpZGVyJyk7XG5cbiAgICAgICAgdmFyIHNlbGVjdGVkU2xpZGVySW5kZXggPSBzbGlkZXJzLmluZGV4KHNsaWRlcik7XG5cbiAgICAgICAgY3J0RWxtdC5maW5kKCcud2VpZ2h0LWNyaXRlcmlvbi1zbGlkZXInKVswXS5uZXh0RWxlbWVudFNpYmxpbmcuaW5uZXJIVE1MID0gJzAgJSc7XG4gICAgICAgIGNydEVsbXQuZmluZCgnLndlaWdodC1jcml0ZXJpb24tc2xpZGVyJylbMF0ubmV4dEVsZW1lbnRTaWJsaW5nLm5leHRFbGVtZW50U2libGluZy52YWx1ZSA9IDA7XG4gICAgICAgIGNydEVsbXQuZmluZCgnLndlaWdodC1jcml0ZXJpb24tc2xpZGVyJylbMF0ubm9VaVNsaWRlci5zZXQoMCk7XG5cbiAgICAgICAgdmFyIHN1bVZhbCA9IDA7XG4gICAgICAgIHZhciBrID0gMDtcbiAgICAgICAgdmFyIG5ld1ZhbHVlID0gMDtcblxuICAgICAgICAkLmVhY2goc2xpZGVycywgZnVuY3Rpb24gKGtleSwgdmFsdWUpIHtcbiAgICAgICAgICAgIGlmIChrZXkgIT0gc2VsZWN0ZWRTbGlkZXJJbmRleCkge1xuICAgICAgICAgICAgICAgIC8vJCh0aGlzKS5vZmYoKTtcbiAgICAgICAgICAgICAgICB2YXIgbnYgPSBNYXRoLnJvdW5kKE51bWJlcihOdW1iZXIoJCh0aGlzKVswXS5ub1VpU2xpZGVyLmdldCgpKSAqICgxMDAgLSBuZXdWYWx1ZSkgLyAoMTAwIC0gb2xkVmFsdWUpKSk7XG5cbiAgICAgICAgICAgICAgICBpZiAoayA9PSBzbGlkZXJzLmxlbmd0aCAtIDIgJiYgc3VtVmFsICsgbnYgKyBuZXdWYWx1ZSAhPSAxMDApIHtcbiAgICAgICAgICAgICAgICAgICAgbnYgPSAxMDAgLSBzdW1WYWwgLSBuZXdWYWx1ZTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAkKHRoaXMpWzBdLm5leHRFbGVtZW50U2libGluZy5pbm5lckhUTUwgPSBudiArICcgJSc7XG4gICAgICAgICAgICAgICAgJCh0aGlzKVswXS5uZXh0RWxlbWVudFNpYmxpbmcubmV4dEVsZW1lbnRTaWJsaW5nLnZhbHVlID0gbnY7XG4gICAgICAgICAgICAgICAgJCh0aGlzKVswXS5ub1VpU2xpZGVyLnNldChudik7XG4gICAgICAgICAgICAgICAgc3VtVmFsICs9IG52O1xuICAgICAgICAgICAgICAgIGsrKztcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSlcblxuICAgICAgICAkLmVhY2goJCh0aGlzKS5jbG9zZXN0KCcuc3RhZ2UnKS5maW5kKCcuY3JpdGVyaW9uJykubm90KGNydEVsbXQpLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgbmV3VmFsdWUgPSBNYXRoLnJvdW5kKE51bWJlcigkKHRoaXMpLmZpbmQoJy53ZWlnaHQtY3JpdGVyaW9uLXNsaWRlcicpLmVxKDApWzBdLm5vVWlTbGlkZXIuZ2V0KCkpKTtcblxuICAgICAgICAgICAgLy8gSGlkZSB3ZWlnaHQgd2hlbiBvbmx5IG9uZSBjcml0ZXJpb24gaGFzIDEwMCBwZXJjZW50XG4gICAgICAgICAgICBpZiAobmV3VmFsdWUgPT0gMTAwKSB7XG4gICAgICAgICAgICAgICAgJCh0aGlzKS5maW5kKCcuc2NhbGUgLmlucHV0LWZpZWxkJykuZXEoLTEpLmhpZGUoKTtcbiAgICAgICAgICAgICAgICAkKHRoaXMpLmZpbmQoJy5zY2FsZSAuaW5wdXQtZmllbGQnKS5ub3QoJzpsYXN0JykuYWRkQ2xhc3MoJ200JykucmVtb3ZlQ2xhc3MoJ20zJyk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuXG4gICAgICAgIC8vIEhpZGUgc2NhbGVcblxuICAgICAgICBjcnRFbG10LmZpbmQoJy5zY2FsZSAuaW5wdXQtZmllbGQnKS5oaWRlKCk7XG4gICAgICAgIGNydEVsbXQuZmluZCgnLmZvcmNlLWNvbW1lbnRzJykuaGlkZSgpO1xuXG5cbiAgICB9IGVsc2Uge1xuXG4gICAgICAgIHZhciBvbGRWYWx1ZSA9IE1hdGgucm91bmQoY3J0RWxtdC5maW5kKCcud2VpZ2h0LWNyaXRlcmlvbi1zbGlkZXInKVswXS5ub1VpU2xpZGVyLmdldCgpKTtcblxuICAgICAgICBpZiAob2xkVmFsdWUgPT0gMCkge1xuXG4gICAgICAgICAgICAvL0dldCBuZXcgY3JpdGVyaWEgb2JqZWN0cyBhZnRlciBpbnNlcnRpb25cbiAgICAgICAgICAgIHZhciByZWxhdGVkQ3JpdGVyaWEgPSBjcnRFbG10LmNsb3Nlc3QoJy5zdGFnZScpLmZpbmQoJy5jcml0ZXJpb24nKTtcblxuICAgICAgICAgICAgdmFyIGNyZWF0aW9uVmFsID0gTWF0aC5yb3VuZCgxMDAgLyByZWxhdGVkQ3JpdGVyaWEubGVuZ3RoKTtcblxuICAgICAgICAgICAgdmFyIHNsaWRlcnMgPSByZWxhdGVkQ3JpdGVyaWEuZmluZCgnLndlaWdodC1jcml0ZXJpb24tc2xpZGVyJyk7XG4gICAgICAgICAgICB2YXIgc3VtVmFsID0gMDtcblxuICAgICAgICAgICAgJC5lYWNoKHNsaWRlcnMsIGZ1bmN0aW9uIChrZXksIHZhbHVlKSB7XG4gICAgICAgICAgICAgICAgaWYgKGtleSAhPSBjcnRJbmRleCkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgbnYgPSBNYXRoLnJvdW5kKE51bWJlcigkKHRoaXMpWzBdLm5vVWlTbGlkZXIuZ2V0KCkpICogKHJlbGF0ZWRDcml0ZXJpYS5sZW5ndGggLSAxKSAvIHJlbGF0ZWRDcml0ZXJpYS5sZW5ndGgpO1xuICAgICAgICAgICAgICAgICAgICAkKHRoaXMpWzBdLm5vVWlTbGlkZXIuc2V0KG52KTtcbiAgICAgICAgICAgICAgICAgICAgJCh0aGlzKVswXS5uZXh0RWxlbWVudFNpYmxpbmcuaW5uZXJIVE1MID0gbnYgKyAnICUnO1xuICAgICAgICAgICAgICAgICAgICAkKHRoaXMpWzBdLm5leHRFbGVtZW50U2libGluZy5uZXh0RWxlbWVudFNpYmxpbmcudmFsdWUgPSBudjtcbiAgICAgICAgICAgICAgICAgICAgc3VtVmFsICs9IG52O1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pXG5cbiAgICAgICAgICAgIGlmIChNYXRoLnJvdW5kKDEwMCAvIHJlbGF0ZWRDcml0ZXJpYS5sZW5ndGgpICE9IDEwMCAvIHJlbGF0ZWRDcml0ZXJpYS5sZW5ndGgpIHtcbiAgICAgICAgICAgICAgICBjcmVhdGlvblZhbCA9IDEwMCAtIHN1bVZhbDtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgY3J0RWxtdC5maW5kKCcud2VpZ2h0LWNyaXRlcmlvbi1zbGlkZXInKVswXS5uZXh0RWxlbWVudFNpYmxpbmcuaW5uZXJIVE1MID0gY3JlYXRpb25WYWwgKyAnICUnO1xuICAgICAgICAgICAgY3J0RWxtdC5maW5kKCcud2VpZ2h0LWNyaXRlcmlvbi1zbGlkZXInKVswXS5uZXh0RWxlbWVudFNpYmxpbmcubmV4dEVsZW1lbnRTaWJsaW5nLnZhbHVlID0gY3JlYXRpb25WYWw7XG4gICAgICAgICAgICBjcnRFbG10LmZpbmQoJy53ZWlnaHQtY3JpdGVyaW9uLXNsaWRlcicpWzBdLm5vVWlTbGlkZXIuc2V0KGNyZWF0aW9uVmFsKTtcblxuICAgICAgICB9XG5cbiAgICB9XG5cbiAgICAvL3ZhciBuYkNydCA9ICQodGhpcykuY2xvc2VzdCgnLnN0YWdlJykuZmluZCgnLmNyaXRlcmlvbicpLmxlbmd0aDtcbiAgICAkLmVhY2goJCh0aGlzKS5jbG9zZXN0KCcuc3RhZ2UnKS5maW5kKCcuY3JpdGVyaW9uJyksIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgaWYgKCEkKHRoaXMpLmZpbmQoJ2lucHV0W3R5cGU9XCJyYWRpb1wiXScpLmVxKDEpLmlzKCc6Y2hlY2tlZCcpKSB7XG4gICAgICAgICAgICBrKys7XG4gICAgICAgIH1cbiAgICB9KTtcblxuXG4gICAgaWYgKCQodGhpcykuZmluZCgnaW5wdXRbdHlwZT1cInJhZGlvXCJdJykuZXEoMCkuaXMoJzpjaGVja2VkJykpIHtcbiAgICAgICAgY3J0RWxtdC5maW5kKCcuZm9yY2UtY2hvaWNlIGxhYmVsJykudGV4dChmb3JjZUNvbW1lbnRNc2dfMCk7XG4gICAgICAgIGNydEVsbXQuZmluZCgnLmlucHV0LWZpZWxkJykubm90KCcuY3JpdGVyaW9uLW5hbWUsIC53ZWlnaHQnKS5zaG93KCk7XG4gICAgICAgIGNydEVsbXQuZmluZCgnLndlaWdodCcpLnJlbW92ZUF0dHIoJ3N0eWxlJyk7XG4gICAgICAgIGNydEVsbXQuZmluZCgnLmZvcmNlLXNpZ24sIC5mb3JjZS12YWx1ZScpLnNob3coKTtcblxuICAgIH0gZWxzZSBpZiAoJCh0aGlzKS5maW5kKCdpbnB1dFt0eXBlPVwicmFkaW9cIl0nKS5lcSgyKS5pcygnOmNoZWNrZWQnKSkge1xuXG4gICAgICAgIGNydEVsbXQuZmluZCgnLmZvcmNlLWNob2ljZSBsYWJlbCcpLnRleHQoZm9yY2VDb21tZW50TXNnXzIpO1xuICAgICAgICBjcnRFbG10LmZpbmQoJy5pbnB1dC1maWVsZCcpLm5vdCgnLmNyaXRlcmlvbi1uYW1lLCAud2VpZ2h0JykuaGlkZSgpO1xuICAgICAgICBjcnRFbG10LmZpbmQoJy5mb3JjZS1zaWduLCAuZm9yY2UtdmFsdWUnKS5oaWRlKCk7XG5cbiAgICB9IGVsc2Uge1xuICAgICAgICBjcnRFbG10LmZpbmQoJy5mb3JjZS1jaG9pY2UgbGFiZWwnKS50ZXh0KGZvcmNlQ29tbWVudE1zZ18xKTtcbiAgICAgICAgY3J0RWxtdC5maW5kKCcud2VpZ2h0JykucmVtb3ZlQXR0cignc3R5bGUnKS5oaWRlKCk7XG4gICAgICAgIGNydEVsbXQuZmluZCgnLmZvcmNlLWNvbW1lbnRzJykuc2hvdygpO1xuICAgICAgICBjcnRFbG10LmZpbmQoJy5mb3JjZS1zaWduLCAuZm9yY2UtdmFsdWUnKS5oaWRlKCk7XG4gICAgfVxuXG4gICAgaWYoY3J0RWxtdC5maW5kKCdpbnB1dFt0eXBlPVwicmFkaW9cIl06Y2hlY2tlZCcpLnZhbCgpICE9IDEpe1xuICAgICAgICBjcnRFbG10LmZpbmQoJy5zY2FsZSAuaW5wdXQtZmllbGQnKS5oaWRlKCk7XG4gICAgfVxuXG4gICAgaWYgKCEkKHRoaXMpLmZpbmQoJ2lucHV0W3R5cGU9XCJyYWRpb1wiXScpLmVxKDIpLmlzKCc6Y2hlY2tlZCcpICYmIGNydEVsbXQuZmluZCgnLmZvcmNlLWNvbW1lbnRzIC5jb2wnKS5lcSgwKS5oYXNDbGFzcygnbTEyJykpIHtcbiAgICAgICAgY3J0RWxtdC5maW5kKCcuZm9yY2UtY29tbWVudHMgLmNvbCcpLmVxKDApLnJlbW92ZUNsYXNzKCdtMTInKS5hZGRDbGFzcygnbTUnKVxuICAgIH1cblxuICAgIGlmIChjcnRFbG10LmZpbmQoJ2lucHV0W3R5cGU9XCJyYWRpb1wiXScpLmVxKDIpLmlzKCc6Y2hlY2tlZCcpKSB7XG4gICAgICAgIGNydEVsbXQuZmluZCgnaW5wdXRbbmFtZSo9XCJsb3dlcmJvdW5kXCJdJykudmFsKDApO1xuICAgICAgICBjcnRFbG10LmZpbmQoJ2lucHV0W25hbWUqPVwidXBwZXJib3VuZFwiXScpLnZhbCgxKTtcbiAgICAgICAgY3J0RWxtdC5maW5kKCdpbnB1dFtuYW1lKj1cInN0ZXBcIl0nKS52YWwoMSk7XG4gICAgfVxuXG59KTtcblxuJChkb2N1bWVudCkub24oJ2NsaWNrJywnLnMtdmFsaWRhdGUnLCBmdW5jdGlvbihlKSB7XG5cbiAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAkYnRuID0gJCh0aGlzKTtcbiAgJGN1clJvdyA9ICRidG4uY2xvc2VzdCgnLnN0YWdlLW1vZGFsJyk7XG4gIGlmKCRjdXJSb3cuZmluZCgnLnJlbW92ZS1zdGFnZS1idG4nKS5sZW5ndGgpe1xuICAgICRjdXJSb3cuZmluZCgnLnJlbW92ZS1zdGFnZS1idG4nKS5yZW1vdmVDbGFzcygncmVtb3ZlLXN0YWdlLWJ0bicpLmFkZENsYXNzKCdtb2RhbC10cmlnZ2VyJykuYXR0cignaHJlZicsJyNkZWxldGVTdGFnZScpO1xuICB9XG4gICRjdXJSb3cuZmluZCgnLnJlZC10ZXh0JykucmVtb3ZlKCk7XG4gIHNpZCA9ICRjdXJSb3cuY2xvc2VzdCgnLnN0YWdlJykuYXR0cignZGF0YS1pZCcpO1xuXG4gIGlucHV0TmFtZSA9ICRjdXJSb3cuZmluZCgnaW5wdXRbbmFtZSo9XCJuYW1lXCJdJykudmFsKCk7XG4gIHdlaWdodFZhbCA9ICRjdXJSb3cuZmluZCgnaW5wdXRbbmFtZSo9XCJhY3RpdmVXZWlnaHRcIl0nKS52YWwoKTtcbiAgaXNEZWZpbml0ZURhdGVzID0gJGN1clJvdy5maW5kKCdbbmFtZSo9XCJkZWZpbml0ZURhdGVzXCJdJykuaXMoJzpjaGVja2VkJyk7XG4gIHN0YXJ0ZGF0ZSA9ICRjdXJSb3cuZmluZCgnW25hbWUqPVwic3RhcnRkYXRlXCJdJykudmFsKCk7XG4gIGVuZGRhdGUgPSAkY3VyUm93LmZpbmQoJ1tuYW1lKj1cImVuZGRhdGVcIl0nKS52YWwoKTtcbiAgZ3N0YXJ0ZGF0ZSA9ICRjdXJSb3cuZmluZCgnW25hbWUqPVwiZ3N0YXJkYXRlXCJdJykudmFsKCk7XG4gIGdlbmRkYXRlID0gJGN1clJvdy5maW5kKCdbbmFtZSo9XCJnZW5kZGF0ZVwiXScpLnZhbCgpO1xuICBkUGVyaW9kID0gJGN1clJvdy5maW5kKCdbbmFtZSo9XCJkUGVyaW9kXCJdJykudmFsKCk7XG4gIGRGcmVxdWVuY3kgPSAkY3VyUm93LmZpbmQoJ3NlbGVjdFtuYW1lKj1cImRGcmVxdWVuY3lcIl0gb3B0aW9uOnNlbGVjdGVkJykudmFsKCk7XG4gIGRPcmlnaW4gPSAkY3VyUm93LmZpbmQoJ3NlbGVjdFtuYW1lKj1cImRPcmlnaW5cIl0gb3B0aW9uOnNlbGVjdGVkJykudmFsKCk7XG4gIGZQZXJpb2QgPSAkY3VyUm93LmZpbmQoJ1tuYW1lKj1cImZQZXJpb2RcIl0nKS52YWwoKTtcbiAgZkZyZXF1ZW5jeSA9ICRjdXJSb3cuZmluZCgnc2VsZWN0W25hbWUqPVwiZkZyZXF1ZW5jeVwiXSBvcHRpb246c2VsZWN0ZWQnKS52YWwoKTtcbiAgZk9yaWdpbiA9ICRjdXJSb3cuZmluZCgnc2VsZWN0W25hbWUqPVwiZk9yaWdpblwiXSBvcHRpb246c2VsZWN0ZWQnKS52YWwoKTtcbiAgdmlzaWJpbGl0eSA9ICRjdXJSb3cuZmluZCgnc2VsZWN0W25hbWUqPVwidmlzaWJpbGl0eVwiXSBvcHRpb246c2VsZWN0ZWQnKS52YWwoKTtcbiAgbW9kZSA9ICRjdXJSb3cuZmluZCgnW25hbWUqPVwibW9kZVwiXTpjaGVja2VkJykudmFsKCk7XG5cbiAgY29uc3QgJGZvcm0gPSAkKCcucy1mb3JtIGZvcm0nKTtcbiAgJGZvcm0uZmluZCgnaW5wdXQsIHNlbGVjdCcpLnJlbW92ZUF0dHIoJ2Rpc2FibGVkJyk7XG5cbiAgJGZvcm0uZmluZCgnW25hbWUqPVwiYWN0aXZlV2VpZ2h0XCJdJykudmFsKHdlaWdodFZhbCk7XG4gICRmb3JtLmZpbmQoJ1tuYW1lKj1cIm5hbWVcIl0nKS52YWwoaW5wdXROYW1lKTtcbiAgJGZvcm0uZmluZCgnW25hbWUqPVwiZGVmaW5pdGVEYXRlc1wiXScpLnByb3AoJ2NoZWNrZWQnLCBpc0RlZmluaXRlRGF0ZXMpO1xuICAkZm9ybS5maW5kKCdbbmFtZSo9XCJbZ3N0YXJ0ZGF0ZV1cIl0nKS52YWwoZ3N0YXJ0ZGF0ZSk7XG4gICRmb3JtLmZpbmQoJ1tuYW1lKj1cIltnZW5kZGF0ZV1cIl0nKS52YWwoZ2VuZGRhdGUpO1xuICAkZm9ybS5maW5kKCdbbmFtZSo9XCJbc3RhcnRkYXRlXVwiXScpLnZhbChzdGFydGRhdGUpO1xuICAkZm9ybS5maW5kKCdbbmFtZSo9XCJbZW5kZGF0ZV1cIl0nKS52YWwoZW5kZGF0ZSk7XG4gICRmb3JtLmZpbmQoJ1tuYW1lKj1cImRQZXJpb2RcIl0nKS52YWwoZFBlcmlvZCk7XG4gICRmb3JtLmZpbmQoJ3NlbGVjdFtuYW1lKj1cImRGcmVxdWVuY3lcIl0nKS52YWwoZEZyZXF1ZW5jeSk7XG4gICRmb3JtLmZpbmQoJ3NlbGVjdFtuYW1lKj1cImRPcmlnaW5cIl0nKS52YWwoZE9yaWdpbik7XG4gICRmb3JtLmZpbmQoJ1tuYW1lKj1cImZQZXJpb2RcIl0nKS52YWwoZlBlcmlvZCk7XG4gICRmb3JtLmZpbmQoJ3NlbGVjdFtuYW1lKj1cImZGcmVxdWVuY3lcIl0nKS52YWwoZkZyZXF1ZW5jeSk7XG4gICRmb3JtLmZpbmQoJ3NlbGVjdFtuYW1lKj1cImZPcmlnaW5cIl0nKS52YWwoZk9yaWdpbik7XG4gICRmb3JtLmZpbmQoJ3NlbGVjdFtuYW1lKj1cInZpc2liaWxpdHlcIl0nKS52YWwodmlzaWJpbGl0eSk7XG4gICRmb3JtLmZpbmQoJ1tuYW1lKj1cIm1vZGVcIl0nKS5lcShtb2RlKS5wcm9wKCdjaGVja2VkJyx0cnVlKTtcblxuICBjb25zdCB1cmxUb1BpZWNlcyA9IHZzdXJsLnNwbGl0KCcvJyk7XG4gIHVybFRvUGllY2VzW3VybFRvUGllY2VzLmxlbmd0aCAtIDFdID0gc2lkO1xuICBjb25zdCB1cmwgPSB1cmxUb1BpZWNlcy5qb2luKCcvJyk7XG4gIHZhciB0bXAgPSAkZm9ybS5zZXJpYWxpemUoKS5zcGxpdCgnJicpO1xuXG4gIGogPSAkKCcuZHAtc3RhcnQnKS5pbmRleCgkY3VyUm93LmZpbmQoJy5kcC1zdGFydCcpKTtcbiAgZm9yIChpID0gMDsgaSA8IHRtcC5sZW5ndGg7IGkrKykge1xuICAgICAgaWYodG1wW2ldLmluZGV4T2YoJ3N0YXJ0ZGF0ZScpICE9IC0xICYmIHRtcFtpXS5pbmRleE9mKCdnc3RhcnRkYXRlJykgPT0gLTEpe1xuICAgICAgICAgIHRtcFtpXSA9IHRtcFtpXS5zcGxpdCgnPScpO1xuICAgICAgICAgIHRtcFtpKzFdID0gdG1wW2krMV0uc3BsaXQoJz0nKTtcbiAgICAgICAgICB0bXBbaSsyXSA9IHRtcFtpKzJdLnNwbGl0KCc9Jyk7XG4gICAgICAgICAgdG1wW2krM10gPSB0bXBbaSszXS5zcGxpdCgnPScpO1xuICAgICAgICAgIHN0YXJ0ZGF0ZURETU1ZWVlZID0gJCgkKFwiI1wiICsgJCgnLmRwLXN0YXJ0Jylbal0uaWQpKS5waWNrYWRhdGUoJ3BpY2tlcicpLmdldCgnc2VsZWN0JywgJ2RkL21tL3l5eXknKTtcbiAgICAgICAgICBlbmRkYXRlRERNTVlZWVkgPSAkKCQoXCIjXCIgKyAkKCcuZHAtZW5kJylbal0uaWQpKS5waWNrYWRhdGUoJ3BpY2tlcicpLmdldCgnc2VsZWN0JywgJ2RkL21tL3l5eXknKTtcbiAgICAgICAgICBnc3RhcnRkYXRlRERNTVlZWVkgPSAkKCQoXCIjXCIgKyAkKCcuZHAtZ3N0YXJ0Jylbal0uaWQpKS5waWNrYWRhdGUoJ3BpY2tlcicpLmdldCgnc2VsZWN0JywgJ2RkL21tL3l5eXknKTtcbiAgICAgICAgICBnZW5kZGF0ZURETU1ZWVlZID0gJCgkKFwiI1wiICsgJCgnLmRwLWdlbmQnKVtqXS5pZCkpLnBpY2thZGF0ZSgncGlja2VyJykuZ2V0KCdzZWxlY3QnLCAnZGQvbW0veXl5eScpO1xuICAgICAgICAgIHRtcFtpXVsxXSA9IHN0YXJ0ZGF0ZURETU1ZWVlZO1xuICAgICAgICAgIHRtcFtpKzFdWzFdID0gZW5kZGF0ZURETU1ZWVlZO1xuICAgICAgICAgIHRtcFtpKzJdWzFdID0gZ3N0YXJ0ZGF0ZURETU1ZWVlZO1xuICAgICAgICAgIHRtcFtpKzNdWzFdID0gZ2VuZGRhdGVERE1NWVlZWTtcbiAgICAgICAgICB0bXBbaV0gPSB0bXBbaV0uam9pbignPScpO1xuICAgICAgICAgIHRtcFtpKzFdID0gdG1wW2krMV0uam9pbignPScpO1xuICAgICAgICAgIHRtcFtpKzJdID0gdG1wW2krMl0uam9pbignPScpO1xuICAgICAgICAgIHRtcFtpKzNdID0gdG1wW2krM10uam9pbignPScpO1xuICAgICAgICAgICRpbmNyZW1lbnQgPSB0cnVlO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAkaW5jcmVtZW50ID0gZmFsc2U7XG4gICAgICB9XG4gICAgICBpZiAoJGluY3JlbWVudCkge1xuICAgICAgICAgIGorKztcbiAgICAgIH1cbiAgfVxuXG4gIG1TZXJpYWxpemVkRm9ybSA9IHRtcC5qb2luKCcmJyk7XG4gICRsaSA9ICQodGhpcykuY2xvc2VzdCgnbGkuc3RhZ2UnKS5maW5kKCdhLmluc2VydC1zdXJ2ZXktYnRuJylcbiAgJC5wb3N0KHVybCwgbVNlcmlhbGl6ZWRGb3JtKVxuICAgIC5kb25lKGZ1bmN0aW9uKGRhdGEpIHtcbiAgICAgIHZhciBzdGFydENhbCA9ICAkY3VyUm93LmZpbmQoJy5kcC1zdGFydCcpO1xuICAgICAgdmFyIGVuZENhbCA9ICAkY3VyUm93LmZpbmQoJy5kcC1lbmQnKTtcbiAgICAgIHZhciBnU3RhcnRDYWwgPSAgJGN1clJvdy5maW5kKCcuZHAtZ3N0YXJ0Jyk7XG4gICAgICB2YXIgZ0VuZENhbCA9ICAkY3VyUm93LmZpbmQoJy5kcC1nZW5kJyk7XG4gICAgICBzdGFydENhbC5hdHRyKCd2YWx1ZScsc3RhcnRkYXRlRERNTVlZWVkpO1xuICAgICAgZW5kQ2FsLmF0dHIoJ3ZhbHVlJyxlbmRkYXRlRERNTVlZWVkpO1xuICAgICAgZ1N0YXJ0Q2FsLmF0dHIoJ3ZhbHVlJyxnc3RhcnRkYXRlRERNTVlZWVkpO1xuICAgICAgZ0VuZENhbC5hdHRyKCd2YWx1ZScsZ2VuZGRhdGVERE1NWVlZWSk7XG4gICAgICBzdGFydGRhdGVERE1NID0gc3RhcnRkYXRlRERNTVlZWVkuc3BsaXQoJy8nKS5zbGljZSgwLDIpLmpvaW4oJy8nKTtcbiAgICAgIGVuZGRhdGVERE1NID0gZW5kZGF0ZURETU1ZWVlZLnNwbGl0KCcvJykuc2xpY2UoMCwyKS5qb2luKCcvJyk7XG4gICAgICBnc3RhcnRkYXRlRERNTSA9IGdzdGFydGRhdGVERE1NWVlZWS5zcGxpdCgnLycpLnNsaWNlKDAsMikuam9pbignLycpO1xuICAgICAgZ2VuZGRhdGVERE1NID0gZ2VuZGRhdGVERE1NWVlZWS5zcGxpdCgnLycpLnNsaWNlKDAsMikuam9pbignLycpO1xuICAgICAgc3RhZ2VEYXRlc1RleHQgPSBzdGFydGRhdGVERE1NID09IGVuZGRhdGVERE1NID8gc3RhcnRkYXRlRERNTSA6IHN0YXJ0ZGF0ZURETU0gKyAnIC0gJyArIGVuZGRhdGVERE1NO1xuICAgICAgZ3JhZGluZ0RhdGVzVGV4dCA9IGdzdGFydGRhdGVERE1NID09IGdlbmRkYXRlRERNTSA/IGdzdGFydGRhdGVERE1NIDogZ3N0YXJ0ZGF0ZURETU0gKyAnIC0gJyArIGdlbmRkYXRlRERNTTtcbiAgICAgICRjdXJSb3cuY2xvc2VzdCgnLnN0YWdlJykuZmluZCgnLnN0YWdlLWRhdGVzJykuY29udGVudHMoKS5sYXN0KCkucmVwbGFjZVdpdGgoc3RhZ2VEYXRlc1RleHQpO1xuICAgICAgJGN1clJvdy5jbG9zZXN0KCcuc3RhZ2UnKS5maW5kKCcuZ3JhZGluZy1kYXRlcycpLmNvbnRlbnRzKCkubGFzdCgpLnJlcGxhY2VXaXRoKGdyYWRpbmdEYXRlc1RleHQpO1xuICAgICAgY29uc3QgaHJlZj0kbGkuYXR0cignaHJlZicpLnJlcGxhY2UoJzAnLCBkYXRhWydzaWQnXSk7XG4gICAgICAkbGkuYXR0cignaHJlZicsaHJlZik7XG4gICAgICAkcmVtb3ZlQnRuID0gJGN1clJvdy5maW5kKCdbaHJlZj1cIiNkZWxldGVTdGFnZVwiXScpO1xuICAgICAgJHJlbW92ZUJ0bi5hdHRyKCdkYXRhLXNpZCcsZGF0YS5zaWQpO1xuICAgICAgJGN1clJvdy5jbG9zZXN0KCcuc3RhZ2UnKS5hdHRyKCdkYXRhLWlkJyxkYXRhLnNpZCk7XG4gICAgICAkY3VyUm93Lm1vZGFsKCdjbG9zZScpO1xuICAgIH0pXG4gICAgLmZhaWwoZnVuY3Rpb24oZGF0YSkge1xuICAgICAgICBlcnJvckh0bWxNc2cgPSAnJztcbiAgICAgICAgJChkYXRhLnJlc3BvbnNlSlNPTikuZWFjaCgoaSxlKSA9PiBlcnJvckh0bWxNc2cgKz0nPHN0cm9uZz4nK09iamVjdC52YWx1ZXMoZSlbMF0rJzwvc3Ryb25nPicpXG4gICAgICAgICRjdXJSb3cuZmluZCgnLmZpcnN0LWRhdGEtcm93JykuYWZ0ZXIoLypodG1sKi9gXG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwicmVkLXRleHRcIj5cbiAgICAgICAgICAgICAgJHtlcnJvckh0bWxNc2d9XG4gICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgYCk7XG4gICAgfSk7XG5cbn0pXG5cbiQoZG9jdW1lbnQpLm9uKCdjbGljaycsJy5jLXZhbGlkYXRlJywgZnVuY3Rpb24oZSkge1xuXG4gIGUucHJldmVudERlZmF1bHQoKTtcbiAgJGJ0biA9ICQodGhpcyk7XG4gICRkZWxldGVCdG4gPSAkYnRuLmNsb3Nlc3QoJy5tb2RhbCcpLmZpbmQoJ1tocmVmPVwiI2RlbGV0ZUNyaXRlcmlvblwiXScpO1xuICAkY3VyUm93ID0gJGJ0bi5oYXNDbGFzcygndW52YWxpZGF0ZS1idG4nKSA/ICQoYC5jcml0ZXJpb24tbW9kYWxbaWQ9XCIkeyQodGhpcykuZGF0YSgnbW9kYWxJZCcpfVwiXWApIDogJCh0aGlzKS5jbG9zZXN0KCcuY3JpdGVyaW9uLW1vZGFsJyk7XG4gICRjcnRFbG10ID0gJGN1clJvdy5jbG9zZXN0KCcuY3JpdGVyaWEtbGlzdC0taXRlbScpO1xuICAvLyRjcnRFbG10ID0gJCh0aGlzKS5jbG9zZXN0KCcuY3JpdGVyaW9uJyk7XG4gICRjdXJSb3cuZmluZCgnLnJlZC10ZXh0JykucmVtb3ZlKCk7XG4gIHNpZCA9ICRidG4uaGFzQ2xhc3MoJ3VudmFsaWRhdGUtYnRuJykgPyAkYnRuLmRhdGEoJ3NpZCcpIDogJCh0aGlzKS5jbG9zZXN0KCcuc3RhZ2UnKS5kYXRhKCdpZCcpO1xuICBjaWQgPSAkKHRoaXMpLmRhdGEoJ2NpZCcpO1xuICBjcnRWYWwgPSAkY3VyUm93LmZpbmQoJ3NlbGVjdFtuYW1lKj1cImNOYW1lXCJdIG9wdGlvbjpzZWxlY3RlZCcpLnZhbCgpO1xuICB0eXBlVmFsID0gJGN1clJvdy5maW5kKCdbbmFtZSo9XCJ0eXBlXCJdOmNoZWNrZWQnKS52YWwoKTtcbiAgaXNSZXF1aXJlZENvbW1lbnQgPSAkY3VyUm93LmZpbmQoJ1t0eXBlKj1cImZvcmNlQ29tbWVudENvbXBhcmVcIl06Y2hlY2tlZCcpLnZhbCgpO1xuICBjb21tZW50U2lnbiA9ICRjdXJSb3cuZmluZCgnc2VsZWN0W25hbWUqPVwiZm9yY2VDb21tZW50U2lnblwiXSBvcHRpb246c2VsZWN0ZWQnKS52YWwoKTtcbiAgY29tbWVudFZhbHVlID0gJGN1clJvdy5maW5kKCdbbmFtZSo9XCJmb3JjZUNvbW1lbnRWYWx1ZVwiXScpLnZhbCgpO1xuICBsb3dlcmJvdW5kID0gJGN1clJvdy5maW5kKCdbbmFtZSo9XCJsb3dlcmJvdW5kXCJdJykudmFsKCk7XG4gIHVwcGVyYm91bmQgPSAkY3VyUm93LmZpbmQoJ1tuYW1lKj1cInVwcGVyYm91bmRcIl0nKS52YWwoKTtcbiAgc3RlcCA9ICRjdXJSb3cuZmluZCgnW25hbWUqPVwic3RlcFwiXScpLnZhbCgpO1xuICB3ZWlnaHQgPSArJGN1clJvdy5maW5kKCcud2VpZ2h0IGlucHV0JykudmFsKCk7XG5cblxuICBjb25zdCAkZm9ybSA9ICQoJy5jLWZvcm0gZm9ybScpO1xuXG4gIGNvbnN0IHVybFRvUGllY2VzID0gdmN1cmwuc3BsaXQoJy8nKTtcbiAgdXJsVG9QaWVjZXNbdXJsVG9QaWVjZXMubGVuZ3RoIC0gNF0gPSBzaWQ7XG4gIHVybFRvUGllY2VzW3VybFRvUGllY2VzLmxlbmd0aCAtIDFdID0gY2lkO1xuICBjb25zdCB1cmwgPSB1cmxUb1BpZWNlcy5qb2luKCcvJyk7XG5cbiAgJGZvcm0uZmluZCgnW25hbWUqPVwiY05hbWVcIl0nKS52YWwoY3J0VmFsKTtcbiAgJGZvcm0uZmluZCgnW25hbWUqPVwidHlwZVwiXScpLmVxKHR5cGVWYWwgLSAxKS5wcm9wKCdjaGVja2VkJyx0cnVlKTtcbiAgJGZvcm0uZmluZCgnW25hbWUqPVwiZm9yY2VDb21tZW50Q29tcGFyZVwiXScpLnByb3AoJ2NoZWNrZWQnLCBpc1JlcXVpcmVkQ29tbWVudCk7XG4gICRmb3JtLmZpbmQoJ1tuYW1lKj1cImZvcmNlQ29tbWVudFNpZ25cIl0nKS52YWwoY29tbWVudFNpZ24pO1xuICAkZm9ybS5maW5kKCdbbmFtZSo9XCJmb3JjZUNvbW1lbnRWYWx1ZVwiXScpLnZhbChjb21tZW50VmFsdWUpO1xuICAkZm9ybS5maW5kKCdbbmFtZSo9XCJsb3dlcmJvdW5kXCJdJykudmFsKGxvd2VyYm91bmQpO1xuICAkZm9ybS5maW5kKCdbbmFtZSo9XCJ1cHBlcmJvdW5kXCJdJykudmFsKHVwcGVyYm91bmQpO1xuICAkZm9ybS5maW5kKCdbbmFtZSo9XCJzdGVwXCJdJykudmFsKHN0ZXApO1xuICAkZm9ybS5maW5kKCdbbmFtZSo9XCJ3ZWlnaHRcIl0nKS52YWwod2VpZ2h0KTtcblxuICAkLnBvc3QodXJsLCAkZm9ybS5zZXJpYWxpemUoKSlcbiAgLmRvbmUoZnVuY3Rpb24oZGF0YSkge1xuICAgICAgJGJ0bi5hdHRyKCdkYXRhLWNpZCcsZGF0YS5jaWQpO1xuICAgICAgJGRlbGV0ZUJ0bi5hdHRyKCdkYXRhLWNpZCcsZGF0YS5jaWQpO1xuICAgICAgJGN1clJvdy5tb2RhbCgnY2xvc2UnKTtcbiAgfSlcbiAgLmZhaWwoZnVuY3Rpb24oZGF0YSkge1xuICAgICAgY29uc3QgZXJyb3JIdG1sTXNnID0gJyc7XG4gICAgICAkKGRhdGEucmVzcG9uc2VKU09OKS5lYWNoKChpLGUpID0+IGVycm9ySHRtbE1zZyArPSc8c3Ryb25nPicrT2JqZWN0LnZhbHVlcyhlKVswXSsnPC9zdHJvbmc+JylcbiAgICAgICRjdXJSb3cuZmluZCgnLmZpcnN0LWRhdGEtcm93JykuYWZ0ZXIoLypodG1sKi9gXG4gICAgICAgICAgPGRpdiBjbGFzcz1cInJlZC10ZXh0XCI+XG4gICAgICAgICAgICAgICR7ZXJyb3JIdG1sTXNnfVxuICAgICAgICAgIDwvZGl2PlxuICAgICAgYCk7XG4gIH0pO1xuXG5cbn0pO1xuXG4kKGRvY3VtZW50KS5vbignY2xpY2snLCcuY3JpdGVyaWEtdGFiLCAuc3VydmV5LXRhYicsZnVuY3Rpb24oKXtcblxuICBpZigkKHRoaXMpLmhhc0NsYXNzKCdzdXJ2ZXktdGFiJykgJiYgJCh0aGlzKS5maW5kKCdhJykuaGFzQ2xhc3MoJ2FjdGl2ZScpICYmICQodGhpcykuY2xvc2VzdCgnc2VjdGlvbicpLmZpbmQoJy5jcml0ZXJpYS1saXN0IC5jcml0ZXJpYS1saXN0LS1pdGVtJykubGVuZ3RoID4gMCl7XG4gICAgICAkKCcjY2hhbmdlT3V0cHV0VHlwZScpLm1vZGFsKCdvcGVuJyk7XG4gICAgICAkKCcuY2hhbmdlLW91dHB1dC1idG4nKS5kYXRhKCdzaWQnLCQodGhpcykuY2xvc2VzdCgnLnN0YWdlJykuZGF0YSgnaWQnKSk7XG4gIH1cbn0pO1xuXG4kKGRvY3VtZW50KS5vbignY2xpY2snLCcuY2hhbmdlLW91dHB1dC1idG4nLGZ1bmN0aW9uKCl7XG4gIGNvbnN0IHVybFRvUGllY2VzID0gY291cmwuc3BsaXQoJy8nKTtcbiAgbGV0IHNpZCA9ICQodGhpcykuZGF0YSgnc2lkJyk7XG4gIHVybFRvUGllY2VzW3VybFRvUGllY2VzLmxlbmd0aC0yXSA9IHNpZDtcbiAgdXJsID0gdXJsVG9QaWVjZXMuam9pbignLycpO1xuICAkLnBvc3QodXJsLCBudWxsKVxuICAgICAgLmRvbmUoZnVuY3Rpb24oZGF0YSkge1xuICAgICAgICAgIGxldCAkc3RnRWxtdCA9ICQoYC5zdGFnZVtkYXRhLWlkPVwiJHtzaWR9XCJdYCk7XG4gICAgICAgICAgaWYoIWRhdGEuc3VydmV5RGVsZXRpb24pe1xuICAgICAgICAgICAgJGNydEhvbGRlciA9ICRzdGdFbG10LmZpbmQoJy5zdGFnZS1jb250YWluZXIgLmNyaXRlcmlhIC5jcml0ZXJpYS1saXN0Jyk7XG4gICAgICAgICAgICAkY3J0SG9sZGVyLmZpbmQoJy5jcml0ZXJpYS1saXN0LS1pdGVtJykuZWFjaChmdW5jdGlvbihpLGUpe1xuICAgICAgICAgICAgICAkKGUpLnJlbW92ZSgpO1xuICAgICAgICAgICAgfSlcbiAgICAgICAgICB9IGVsc2Uge1xuXG4gICAgICAgICAgfVxuICAgICAgfSlcbiAgICAgIC5mYWlsKGZ1bmN0aW9uKGRhdGEpIHtcblxuICAgICAgfSk7XG59KTtcblxuJCgnLnN0YWdlLWFkZCAuc3RhZ2UtZnJlc2gtbmV3LWJ0bicpLm9uKCdjbGljaycsZnVuY3Rpb24oKXtcbiAgY29uc3QgbmJTdGFnZXMgPSAkKCcuc3RhZ2UnKS5sZW5ndGg7XG4gIGNvbnN0IHByb3RvID0gJChkb2N1bWVudCkuZmluZCgndGVtcGxhdGUuc3RhZ2VzLWxpc3QtLWl0ZW1fX3Byb3RvJylbMF07XG4gIGNvbnN0IHByb3RvSHRtbCA9IHByb3RvLmlubmVySFRNTC50cmltKCk7XG4gIGNvbnN0IG5ld1Byb3RvSHRtbCA9IHByb3RvSHRtbFxuICAgIC5yZXBsYWNlKC9fX25hbWVfXy9nLCBuYlN0YWdlcylcbiAgICAucmVwbGFjZSgvX19zdGdOYl9fL2csIG5iU3RhZ2VzKVxuICAgIC5yZXBsYWNlKC9fX3JlYWxTdGdOYl9fL2csIG5iU3RhZ2VzICsgMSk7XG4gIGNvbnN0ICRzdGdFbG10ID0gJChuZXdQcm90b0h0bWwpO1xuICAkc3RnRWxtdC5maW5kKCcubW9kYWwnKS5tb2RhbCgpO1xuICAkc3RnRWxtdC5maW5kKCcudG9vbHRpcHBlZCcpLnRvb2x0aXAoKTtcbiAgJHN0Z0VsbXQuZmluZCgnLnRhYnMnKS50YWJzKCk7XG4gICQoJy5zdGFnZS1hZGQnKS5iZWZvcmUoJHN0Z0VsbXQpO1xuICB0b2dnbGVTdGFnZSgkc3RnRWxtdCk7XG5cbiAgLy8gSW5pdGlhbGl6aW5nIHN0YWdlIGRlZmF1bHQgdmFsdWVzXG4gIHZhciBpbml0U3RhcnREYXRlID0gbmV3IERhdGUoRGF0ZS5ub3coKSk7XG4gIHZhciBpbml0RW5kRGF0ZSA9IG5ldyBEYXRlKERhdGUubm93KCkgKyAxNSAqIDI0ICogNjAgKiA2MCAqIDEwMDApO1xuICB2YXIgaW5pdEdTdGFydERhdGUgPSBuZXcgRGF0ZShEYXRlLm5vdygpKTtcbiAgdmFyIGluaXRHRW5kRGF0ZSA9IG5ldyBEYXRlKERhdGUubm93KCkgKyAzMCAqIDI0ICogNjAgKiA2MCAqIDEwMDApO1xuXG4gICRzdGdFbG10LmZpbmQoJy5kcC1zdGFydCwgLmRwLWVuZCwgLmRwLWdzdGFydCwgLmRwLWdlbmQnKS5lYWNoKGZ1bmN0aW9uKCkge1xuICAgICAgJCh0aGlzKS5waWNrYWRhdGUoKTtcbiAgfSk7XG5cbiAgJHN0Z0VsbXQuZmluZCgnLmRwLXN0YXJ0JykucGlja2FkYXRlKCdwaWNrZXInKS5zZXQoJ3NlbGVjdCcsaW5pdFN0YXJ0RGF0ZSk7XG4gICRzdGdFbG10LmZpbmQoJy5kcC1lbmQnKS5waWNrYWRhdGUoJ3BpY2tlcicpLnNldCgnc2VsZWN0Jyxpbml0RW5kRGF0ZSkuc2V0KCdtaW4nLGluaXRTdGFydERhdGUpO1xuICAkc3RnRWxtdC5maW5kKCcuZHAtZ3N0YXJ0JykucGlja2FkYXRlKCdwaWNrZXInKS5zZXQoJ3NlbGVjdCcsaW5pdEdTdGFydERhdGUpLnNldCgnbWluJyxpbml0U3RhcnREYXRlKTtcbiAgJHN0Z0VsbXQuZmluZCgnLmRwLWdlbmQnKS5waWNrYWRhdGUoJ3BpY2tlcicpLnNldCgnc2VsZWN0Jyxpbml0R0VuZERhdGUpLnNldCgnbWluJyxpbml0R1N0YXJ0RGF0ZSk7XG5cbiAgJHN0Z0VsbXQuZmluZCgnaW5wdXRbbmFtZSo9XCJwZXJpb2RcIl0nKS52YWwoMTUpO1xuICAkc3RnRWxtdC5maW5kKCdzZWxlY3RbbmFtZSo9XCJmcmVxdWVuY3lcIl0nKS52YWwoJ0QnKTtcbiAgJHN0Z0VsbXQuZmluZCgnW25hbWUqPVwibW9kZVwiXScpLmVxKDApLnByb3AoJ2NoZWNrZWQnLHRydWUpO1xuXG4gIHZhciBzbGlkZXIgPSAkc3RnRWxtdC5maW5kKCcud2VpZ2h0LXN0YWdlLXNsaWRlcicpO1xuICB2YXIgd2VpZ2h0ID0gJHN0Z0VsbXQuZmluZCgnLndlaWdodCcpO1xuXG4gIC8vUmVtb3ZpbmcgJyUnIHRleHQgYWRkZWQgYnkgUGVyY2VudFR5cGVcbiAgd2VpZ2h0WzBdLnJlbW92ZUNoaWxkKHdlaWdodFswXS5sYXN0Q2hpbGQpO1xuXG4gIHZhciBjcmVhdGlvblZhbCA9IE1hdGgucm91bmQoMTAwIC8gKG5iU3RhZ2VzICsgMSkpOyAgICBcblxuICBub1VpU2xpZGVyLmNyZWF0ZShzbGlkZXJbMF0sIHtcbiAgICAgIHN0YXJ0OiBjcmVhdGlvblZhbCxcbiAgICAgIHN0ZXA6IDEsXG4gICAgICBjb25uZWN0OiBbdHJ1ZSwgZmFsc2VdLFxuICAgICAgcmFuZ2U6IHtcbiAgICAgICAgICAnbWluJzogMCxcbiAgICAgICAgICAnbWF4JzogMTAwLFxuICAgICAgfSxcbiAgfSk7XG5cbiAgc2xpZGVyWzBdLm5leHRFbGVtZW50U2libGluZy5pbm5lckhUTUwgPSBjcmVhdGlvblZhbCArICcgJSc7XG4gIHNsaWRlclswXS5uZXh0RWxlbWVudFNpYmxpbmcubmV4dEVsZW1lbnRTaWJsaW5nLnZhbHVlID0gY3JlYXRpb25WYWw7XG5cbiAgc2xpZGVyWzBdLm5vVWlTbGlkZXIub24oJ3NsaWRlJywgZnVuY3Rpb24gKHZhbHVlcywgaGFuZGxlKSB7XG5cbiAgICAgIHNsaWRlclswXS5uZXh0RWxlbWVudFNpYmxpbmcuaW5uZXJIVE1MID0gTnVtYmVyKHZhbHVlc1toYW5kbGVdKSArICcgJSc7XG4gICAgICBzbGlkZXJbMF0ubmV4dEVsZW1lbnRTaWJsaW5nLm5leHRFbGVtZW50U2libGluZy52YWx1ZSA9IHZhbHVlc1toYW5kbGVdO1xuXG4gIH0pO1xuXG4gIGhhbmRsZUNOU2VsZWN0RWxlbXMoJHN0Z0VsbXQpO1xuXG4gICRzdGdFbG10LmZpbmQoJy5zdGFnZS1tb2RhbCcpLm1vZGFsKHtcbiAgICBjb21wbGV0ZTogZnVuY3Rpb24oKXtcbiAgICAgIGlmKCEkKCcucmVtb3ZlLXN0YWdlJykuaGFzQ2xhc3MoJ2NsaWNrZWQnKSl7XG4gICAgICAgIGxldCBidG5WID0gJHN0Z0VsbXQuZmluZCgnLnMtdmFsaWRhdGUnKTtcbiAgICAgICAgdmFyICRzbGlkZXIgPSAkc3RnRWxtdC5maW5kKCcud2VpZ2h0IC53ZWlnaHQtc3RhZ2Utc2xpZGVyJyk7XG4gICAgICAgIHZhciAkc2xpZGVycyA9ICQoJy5zdGFnZSAud2VpZ2h0JykuZmluZCgnLndlaWdodC1zdGFnZS1zbGlkZXInKS5ub3Qoc2xpZGVyKTtcbiAgICAgICAgaWYoIWJ0blYuaGFzQ2xhc3MoJ2NsaWNrZWQnKSl7XG4gICAgICAgICAgICBpZigkc3RnRWxtdC5oYXNDbGFzcygnbmV3JykpeyAgXG4gIFxuICAgICAgICAgICAgICAkc3RnRWxtdC5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgJCgnLnN0YWdlJykubGFzdCgpLmFkZENsYXNzKCdhY3RpdmUnKTtcblxuICAgICAgICAgICAgfSBlbHNlIHtcbiAgXG4gICAgICAgICAgICAgIHZhciBzdGFydENhbCA9ICAkc3RnRWxtdC5maW5kKCcuZHAtc3RhcnQnKTtcbiAgICAgICAgICAgICAgdmFyIGVuZENhbCA9ICAkc3RnRWxtdC5maW5kKCcuZHAtZW5kJyk7XG4gICAgICAgICAgICAgIHZhciBnU3RhcnRDYWwgPSAgJHN0Z0VsbXQuZmluZCgnLmRwLWdzdGFydCcpO1xuICAgICAgICAgICAgICB2YXIgZ0VuZENhbCA9ICAkc3RnRWxtdC5maW5kKCcuZHAtZ2VuZCcpO1xuICAgICAgICAgICAgICBjb25zdCByZWdleCA9IC9qYW52aWVyfGbDqXZyaWVyfG1hcnN8YXZyaWx8bWFpfGp1aW58anVpbGxldHxhb8O7dHxzZXB0ZW1icmV8b2N0b2JyZXxub3ZlbWJyZXxkw6ljZW1icmV8ZW5lcm98ZmVicmVyb3xtYXJ6b3xhYnJpbHxtYXlvfGp1bmlvfGp1bGlvfGFnb3N0b3xzZXB0aWVtYnJlfG9jdHVicmV8bm92aWVtYnJlfGRpY2llbWJyZXxqYW5laXJvfGZldmVyZWlyb3xtYXLDp298YWJyaWx8bWFpb3xqdW5ob3xqdWxob3xhZ29zdG98c2V0ZW1icm98b3V0dWJyb3xub3ZlbWJyb3xkZXplbWJyby9nIDtcbiAgICAgICAgICAgICAgdmFyIHN0YXJ0RGF0ZVRTID0gKHN0YXJ0Q2FsLnZhbCgpID09IFwiXCIpID8gaW5pdFN0YXJ0RGF0ZSA6IHBhcnNlRGRtbXl5eXkoc3RhcnRDYWwuYXR0cigndmFsdWUnKS5yZXBsYWNlKHJlZ2V4LGZ1bmN0aW9uKG1hdGNoKXtyZXR1cm4gcmVwbGFjZVZhcnNbbWF0Y2hdO30pKTtcbiAgICAgICAgICAgICAgdmFyIGVuZERhdGVUUyA9IChlbmRDYWwudmFsKCkgPT0gXCJcIikgPyBpbml0RW5kRGF0ZSA6IHBhcnNlRGRtbXl5eXkoZW5kQ2FsLmF0dHIoJ3ZhbHVlJykucmVwbGFjZShyZWdleCxmdW5jdGlvbihtYXRjaCl7cmV0dXJuIHJlcGxhY2VWYXJzW21hdGNoXTt9KSk7XG4gICAgICAgICAgICAgIHZhciBnU3RhcnREYXRlVFMgPSAoZ1N0YXJ0Q2FsLnZhbCgpID09IFwiXCIpID8gaW5pdEdTdGFydERhdGUgOiBwYXJzZURkbW15eXl5KGdTdGFydENhbC5hdHRyKCd2YWx1ZScpLnJlcGxhY2UocmVnZXgsZnVuY3Rpb24obWF0Y2gpe3JldHVybiByZXBsYWNlVmFyc1ttYXRjaF07fSkpO1xuICAgICAgICAgICAgICB2YXIgZ0VuZERhdGVUUyA9IChnRW5kQ2FsLnZhbCgpID09IFwiXCIpID8gaW5pdEdFbmREYXRlIDogcGFyc2VEZG1teXl5eShnRW5kQ2FsLmF0dHIoJ3ZhbHVlJykucmVwbGFjZShyZWdleCxmdW5jdGlvbihtYXRjaCl7cmV0dXJuIHJlcGxhY2VWYXJzW21hdGNoXTt9KSk7XG4gICAgICAgICAgICAgIHZhciBzdGFydERhdGUgPSBuZXcgRGF0ZShzdGFydERhdGVUUyk7XG4gICAgICAgICAgICAgIHZhciBlbmREYXRlID0gbmV3IERhdGUoZW5kRGF0ZVRTKTtcbiAgICAgICAgICAgICAgdmFyIGdTdGFydERhdGUgPSBuZXcgRGF0ZShnU3RhcnREYXRlVFMpO1xuICAgICAgICAgICAgICB2YXIgZ0VuZERhdGUgPSBuZXcgRGF0ZShnRW5kRGF0ZVRTKTtcbiAgXG4gICAgICAgICAgICAgIHN0YXJ0Q2FsLnBpY2thZGF0ZSgncGlja2VyJykuc2V0KCdzZWxlY3QnLHN0YXJ0RGF0ZSk7XG4gICAgICAgICAgICAgIGVuZENhbC5waWNrYWRhdGUoJ3BpY2tlcicpLnNldCgnc2VsZWN0JyxlbmREYXRlKS5zZXQoJ21pbicsc3RhcnREYXRlKTtcbiAgICAgICAgICAgICAgZ1N0YXJ0Q2FsLnBpY2thZGF0ZSgncGlja2VyJykuc2V0KCdzZWxlY3QnLGdTdGFydERhdGUpLnNldCgnbWluJyxzdGFydERhdGUpO1xuICAgICAgICAgICAgICBnRW5kQ2FsLnBpY2thZGF0ZSgncGlja2VyJykuc2V0KCdzZWxlY3QnLGdFbmREYXRlKS5zZXQoJ21pbicsZ1N0YXJ0RGF0ZSk7XG4gIFxuICAgICAgICAgICAgICBwcmV2V2VpZ2h0ID0gKyRzbGlkZXJbMF0ubmV4dEVsZW1lbnRTaWJsaW5nLm5leHRFbGVtZW50U2libGluZy5nZXRBdHRyaWJ1dGUoJ3ZhbHVlJyk7XG4gICAgICAgICAgICAgIHN0Z05hbWUgPSAkc3RnRWxtdC5maW5kKCdpbnB1dFtuYW1lKj1cIm5hbWVcIl0nKS5hdHRyKCd2YWx1ZScpO1xuICAgICAgICAgICAgICBzdGdNb2RlID0gJHN0Z0VsbXQuZmluZCgnaW5wdXRbbmFtZSo9XCJtb2RlXCJdW2NoZWNrZWQ9XCJjaGVja2VkXCJdJykudmFsKCk7XG4gICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAkc2xpZGVyWzBdLm5leHRFbGVtZW50U2libGluZy5pbm5lckhUTUwgPSBwcmV2V2VpZ2h0ICsgJyAlJztcbiAgICAgICAgICAgICAgJHNsaWRlclswXS5uZXh0RWxlbWVudFNpYmxpbmcubmV4dEVsZW1lbnRTaWJsaW5nLnZhbHVlID0gcHJldldlaWdodDtcbiAgICAgICAgICAgICAgJHNsaWRlclswXS5ub1VpU2xpZGVyLnNldChwcmV2V2VpZ2h0KTtcbiAgICAgICAgICAgICAgJHN0Z0VsbXQuZmluZChgaW5wdXRbbmFtZSo9XCJtb2RlXCJdW3ZhbHVlID0gJHtzdGdNb2RlfV1gKS5wcm9wKFwiY2hlY2tlZFwiLHRydWUpO1xuICAgICAgICAgICAgICAkc3RnRWxtdC5maW5kKCdpbnB1dFtuYW1lKj1cIm5hbWVcIl0nKS52YWwoc3RnTmFtZSk7XG4gICAgICAgICAgICAgICRzdGdFbG10LmZpbmQoJy5zdGFnZS1pdGVtLW5hbWUnKS5maW5kKCcucy13ZWlnaHRpbmcnKS5lbXB0eSgpLmFwcGVuZChgKCR7cHJldldlaWdodH0gJSlgKTtcbiAgICAgICAgICAgICAgJHN0Z0VsbXQuZmluZCgnLnN0YWdlLXdlaWdodCcpLmZpbmQoJy5zLXdlaWdodGluZycpLmVtcHR5KCkuYXBwZW5kKGAke3ByZXZXZWlnaHR9ICVgKTtcbiAgICAgICAgICAgICAgJHN0Z0VsbXQuZmluZCgnc2VsZWN0W25hbWUqPVwidmlzaWJpbGl0eVwiXScpLnZhbCgkc3RnRWxtdC5maW5kKCdzZWxlY3RbbmFtZSo9XCJ2aXNpYmlsaXR5XCJdIG9wdGlvbltzZWxlY3RlZD1cInNlbGVjdGVkXCJdJykudmFsKCkpO1xuICBcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIGJ0blYucmVtb3ZlQ2xhc3MoJ2NsaWNrZWQnKTtcbiAgICAgICAgICAgIGNvbnN0IHdlaWdodFZhbHVlID0gKyRzdGdFbG10LmZpbmQoJy53ZWlnaHQgaW5wdXQnKS52YWwoKTtcbiAgICAgICAgICAgICRzdGdFbG10LmZpbmQoJ2lucHV0W25hbWUqPVwibmFtZVwiXScpLmF0dHIoJ3ZhbHVlJywkc3RnRWxtdC5maW5kKCdpbnB1dFtuYW1lKj1cIm5hbWVcIl0nKS52YWwoKSk7XG4gICAgICAgICAgICAkc3RnRWxtdC5maW5kKCdpbnB1dFtuYW1lKj1cIm1vZGVcIl1bY2hlY2tlZD1cImNoZWNrZWRcIl0nKS5yZW1vdmVBdHRyKFwiY2hlY2tlZFwiKTtcbiAgICAgICAgICAgICRzdGdFbG10LmZpbmQoJ2lucHV0W25hbWUqPVwibW9kZVwiXTpjaGVja2VkJykuYXR0cignY2hlY2tlZCcsXCJjaGVja2VkXCIpO1xuICAgICAgICAgICAgJHN0Z0VsbXQuZmluZCgnLnN0YWdlLW5hbWUtZmllbGQnKS50ZXh0KCRzdGdFbG10LmZpbmQoJ2lucHV0W25hbWUqPVwibmFtZVwiXScpLnZhbCgpKTtcbiAgICAgICAgICAgICRzdGdFbG10LmZpbmQoJy5zdGFnZS1pdGVtLW5hbWUnKS5maW5kKCcucy13ZWlnaHRpbmcnKS5lbXB0eSgpLmFwcGVuZChgKCR7d2VpZ2h0VmFsdWV9ICUpYCk7XG4gICAgICAgICAgICAkc3RnRWxtdC5maW5kKCcuc3RhZ2Utd2VpZ2h0JykuZmluZCgnLnMtd2VpZ2h0aW5nJykuZW1wdHkoKS5hcHBlbmQoYCR7d2VpZ2h0VmFsdWV9ICVgKTtcbiAgICAgICAgICAgIFxuICAgICAgICAgICAgaGFuZGxlQ05TZWxlY3RFbGVtcygkc3RnRWxtdCk7XG4gICAgICAgICAgICBpZighJHN0Z0VsbXQuZmluZCgnW2lkKj1cInR5cGUtLWNyaXRlcmlhXCJdLCBbaWQqPVwidHlwZS0tc3VydmV5XCJdJykuaGFzQ2xhc3MoJ2FjdGl2ZScpKXtcbiAgICAgICAgICAgICAgJHN0Z0VsbXQuZmluZCgnLnN1cnZleS10YWIgYScpLmNsaWNrKCk7XG4gICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24oKXtcbiAgICAgICAgICAgICAgICAkc3RnRWxtdC5maW5kKCcuY3JpdGVyaWEtdGFiIGEnKS5jbGljaygpO1xuICAgICAgICAgICAgICB9LDEwMCk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHZhciAkc2xpZGVyID0gJHN0Z0VsbXQuZmluZCgnLndlaWdodC1zdGFnZS1zbGlkZXInKTtcbiAgICAgICAgICAgIHZhciAkc2xpZGVycyA9ICQoJy5zdGFnZSAud2VpZ2h0JykuZmluZCgnLndlaWdodC1zdGFnZS1zbGlkZXInKS5ub3Qoc2xpZGVyKTtcbiAgICAgICAgICAgIGlmKCRzbGlkZXJzLmxlbmd0aCA9PSAxKXtcbiAgICAgICAgICAgICAgJHNsaWRlcnMuY2xvc2VzdCgnLndlaWdodCcpLnNob3coKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgdmFyIG9sZFZhbHVlID0gJHN0Z0VsbXQuaGFzQ2xhc3MoJ25ldycpID8gMCA6ICRzdGdFbG10LmZpbmQoJy53ZWlnaHQgaW5wdXQnKS5hdHRyKCd2YWx1ZScpO1xuICAgICAgICAgICAgdmFyIHN1bVZhbCA9IDA7XG4gICAgICAgICAgICB2YXIgbmV3VmFsdWUgPSB3ZWlnaHRWYWx1ZTtcblxuICAgICAgICAgICAgJC5lYWNoKCRzbGlkZXJzLCBmdW5jdGlvbiAoa2V5LCB2YWx1ZSkge1xuICAgICAgICAgICAgICAgIFxuICAgICAgICAgICAgICAgIHZhciBudiA9IChrZXkgIT0gJHNsaWRlcnMubGVuZ3RoIC0gMSkgPyBcbiAgICAgICAgICAgICAgICAgIE1hdGgucm91bmQoTnVtYmVyKE51bWJlcigkKHRoaXMpWzBdLm5vVWlTbGlkZXIuZ2V0KCkpICogKDEwMCAtIG5ld1ZhbHVlKSAvICgxMDAgLSBvbGRWYWx1ZSkpKSA6XG4gICAgICAgICAgICAgICAgICAxMDAgLSBzdW1WYWwgLSBuZXdWYWx1ZTtcbiAgXG4gICAgICAgICAgICAgICAgJCh0aGlzKVswXS5uZXh0RWxlbWVudFNpYmxpbmcuaW5uZXJIVE1MID0gbnYgKyAnICUnO1xuICAgICAgICAgICAgICAgICQodGhpcylbMF0ubmV4dEVsZW1lbnRTaWJsaW5nLm5leHRFbGVtZW50U2libGluZy52YWx1ZSA9IG52O1xuICAgICAgICAgICAgICAgICQodGhpcylbMF0ubmV4dEVsZW1lbnRTaWJsaW5nLm5leHRFbGVtZW50U2libGluZy5zZXRBdHRyaWJ1dGUoJ3ZhbHVlJyxudik7XG4gICAgICAgICAgICAgICAgJCh0aGlzKVswXS5ub1VpU2xpZGVyLnNldChudik7XG4gICAgICAgICAgICAgICAgc3VtVmFsICs9IG52O1xuICAgICAgICAgICAgICAgICQodmFsdWUpLmNsb3Nlc3QoJy5zdGFnZScpLmZpbmQoJy5zdGFnZS1pdGVtLW5hbWUnKS5maW5kKCcucy13ZWlnaHRpbmcnKS5lbXB0eSgpLmFwcGVuZChgKCR7bnZ9ICUpYCk7XG4gICAgICAgICAgICAgICAgJCh2YWx1ZSkuY2xvc2VzdCgnLnN0YWdlJykuZmluZCgnLnN0YWdlLXdlaWdodCcpLmZpbmQoJy5zLXdlaWdodGluZycpLmVtcHR5KCkuYXBwZW5kKGAke252fSAlYCk7XG4gICAgICAgIFxuICAgICAgICAgICAgfSlcbiAgICAgICAgICAgICRzdGdFbG10LmZpbmQoJy53ZWlnaHQgaW5wdXQnKS5hdHRyKCd2YWx1ZScsbmV3VmFsdWUpO1xuICAgICAgICAgICAgJHN0Z0VsbXQucmVtb3ZlQ2xhc3MoJ25ldycpLnJlbW92ZUF0dHIoJ3N0eWxlJyk7XG4gICAgICAgIH1cbiAgICAgIH0gZWxzZSB7XG4gICAgICAgICQoJy5yZW1vdmUtc3RhZ2UnKS5yZW1vdmVDbGFzcygnY2xpY2tlZCcpO1xuICAgICAgfVxuICAgICAgXG4gICAgfVxuICB9KVxuICAkc3RnRWxtdC5maW5kKCcuc3RhZ2UtbW9kYWwnKS5tb2RhbCgnb3BlbicpOyBcbn0pXG5cbiQoZG9jdW1lbnQpLm9uKCdjaGFuZ2UnLCcuZGF0ZS1zd2l0Y2ggaW5wdXQnLGZ1bmN0aW9uKCl7XG4gICQodGhpcykuaXMoJzpjaGVja2VkJykgPyAgKCQodGhpcykuY2xvc2VzdCgnLnJvdycpLmZpbmQoJy5wZXJpb2QtZnJlcS1pbnB1dCcpLmhpZGUoKSwgJCh0aGlzKS5jbG9zZXN0KCcucm93JykuZmluZCgnLmRhdGVzLWlucHV0Jykuc2hvdygpKSA6ICgkKHRoaXMpLmNsb3Nlc3QoJy5yb3cnKS5maW5kKCcucGVyaW9kLWZyZXEtaW5wdXQnKS5zaG93KCksICQodGhpcykuY2xvc2VzdCgnLnJvdycpLmZpbmQoJy5kYXRlcy1pbnB1dCcpLmhpZGUoKSk7XG59KTtcblxuJChkb2N1bWVudCkub24oJ2NsaWNrJywnLmFjdGl2aXR5LW5hbWU6bm90KC5lZGl0KSA+IC5idG4tZWRpdCcsZnVuY3Rpb24oKXtcbiAgJCgnLmFjdGl2aXR5LW5hbWUnKS5hZGRDbGFzcygnZWRpdCcpO1xufSk7XG5cbiQoZG9jdW1lbnQpLm9uKCdjbGljaycsJy5hY3Rpdml0eS1uYW1lLmVkaXQgPiAuYnRuLWVkaXQnLGZ1bmN0aW9uKCl7XG4gIGNvbnN0IG5hbWUgPSAkKCcuY3VzdG9tLWlucHV0JykudGV4dCgpLnRyaW0oKTtcbiAgY29uc3QgcGFyYW1zID0ge25hbWU6IG5hbWV9O1xuICBpZihuYW1lICE9ICQoJy5hY3Rpdml0eS1uYW1lIGlucHV0JykuYXR0cigndmFsdWUnKSl7XG4gICAgJC5wb3N0KHZudXJsLHBhcmFtcylcbiAgICAgIC5mYWlsKGZ1bmN0aW9uKGRhdGEpIHtcbiAgICAgICAgJCgnI2R1cGxpY2F0ZUVsZW1lbnROYW1lJykubW9kYWwoJ29wZW4nKTtcbiAgICAgIH0pXG4gICAgICAuZG9uZShmdW5jdGlvbihkYXRhKXtcbiAgICAgICAgJCgnLmFjdGl2aXR5LW5hbWUgaW5wdXQnKS5hdHRyKCd2YWx1ZScsbmFtZSk7XG4gICAgICAgICQoJy5hY3Rpdml0eS1uYW1lIC5zaG93JykuZW1wdHkoKS5hcHBlbmQobmFtZSk7XG4gICAgICAgICQoJy5hY3Rpdml0eS1uYW1lJykucmVtb3ZlQ2xhc3MoJ2VkaXQnKTtcbiAgICAgIH0pO1xuICB9IGVsc2Uge1xuICAgICAgJCgnLmFjdGl2aXR5LW5hbWUnKS5yZW1vdmVDbGFzcygnZWRpdCcpO1xuICB9XG59KTtcbiJdLCJzb3VyY2VSb290IjoiIn0=