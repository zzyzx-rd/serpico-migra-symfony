
//var $newLinkLi = $('<li></li>').append($addelementLink);
let elementId;
let elementArray = [];
let elementIdArray = [];


$(function () {
  $collectionHolder = $('ul.elements');
  $('.dropdown-button').dropdown({
      inDuration: 300,
      outDuration: 225,
      click: true, // Activate on hover
      closeOnClick: true,
      alignment: 'right',

    }
  );

  $totalTargets = $collectionHolder.find('.element-input').length;
  $collectionHolder.data('total', $totalTargets);
  $collectionHolderparam = $(this).find('ul.param');
  $totalTargetsparam = $collectionHolderparam.find('.param').length;
  $collectionHolderparam.data('total', $totalTargetsparam);
  $btnParameters = $('.param-btn');
  var title = $('.surveyTitleLabel');



  $(".select-type").each(function () {
    var currentLi = $(this).closest('li.element-input');
    var currentElmtdata = currentLi.find('div.element-type');

    var value = $(this).children("option:selected").val();

    if (value == "MC" || value == "SC") {
      nbParam = currentLi.children('ul.param').children('li.param');
      currentParam = currentLi.find('div.type-save');

      if (value == "MC") {
        currentParam.prepend('     <div>\n' +
          '        <input type="checkbox">\n' +
          '        <label >\t</label>\n' +
          '      </div>');
      } else {
        currentParam.prepend('    <div>\n' +
          '        <input type="radio" id="check">\n' +
          '        <label >\t</label>\n' +
          '      </div>');

      }

    } else if (value == "UC") {
      firstLabel =currentLi.find('.param-choice')[0];
      secondLabel =currentLi.find('.param-choice')[1];
      currentElmtdata.prepend('   <div class="switch">\n' +
        '    <label>\n' +
        '      <label class="label-switch" id="0">'+$(firstLabel).val()+'</label>\n' +
        '      <input type="checkbox">\n' +
        '      <span class="lever"></span>\n' +
        '      <label class="label-switch" id="1">'+$(secondLabel).val()+'</label>\n' +
        '    </label>\n' +
        '  </div> ');

    } else if (value == "LT") {
      currentElmtdata.prepend(' <textarea id="textarea1" class="materialize-textarea"></textarea> ')
    } else if (value == "LS") {


      currentElmtdata.prepend('<div style="display:inline-block;width:70%" class="grade-slider grade-slider-{{ k }}" data-lb="0" data-ub="10" data-step="1" data-value="1"></div>\n' +
        '  <div class="grade-slider-range-value center" style="font-size:1.2rem;margin-left: 20px;display:inline-block"></div>');
      var val = currentLi.find('.grade-slider');
      lower=currentLi.find('.field-low option:selected').text();
      upper=currentLi.find('.field-upp option:selected').text();
      console.log(val[0]);

      noUiSlider.create(val[0], {
        start: parseInt(upper)-parseInt(lower),
        step:  1,
        connect: [true, false],
        range: {
          'min':  0,
          'max':10,}

      });
      var update = false;



    } else {
      currentElmtdata.append(" <input type='text'> ")


    }

  });

  elementIdArray = $(document).find('.elementId');
  elementArray = $(document).find('input[name*=elements]');

  $(document).on('click', ' .description', function () {

    currentLi = $(this).closest('.element-input');
    //currentLi = currentLi.find('.element-desc').show();
    link = currentLi.find('.modify');
    $(this).parent().remove();
    console.log(currentLi.find('.desc'));

    currentLi.find('.field-desc').val('description');
    currentLi.find('.element-desc').addClass('display');
    if ($(link).children().hasClass('fa-pencil-alt')) {

      currentLi.find('.desc').prepend('     <div class="col s12  element-data"  >\n' +
        '            <h7><div class="col s12 m4">description</div>\n' +
        '              </h7>\n' +
        '          </div>');
    } else {
      currentLi.find('.desc').prepend('     <div class="col s12  element-data" style="display:none"  >\n' +
        '            <h7><div class="col s12 description-val m4">description</div>\n' +
        '              </h7>\n' +
        '          </div>');


    }


    if ($(link).children().hasClass('fa-check')) {

      currentLi.find('.element-desc').show();
    }

  })



  



  $(document).on('click', '.confirm-question-btn:has(.fa-check)', function () {
    const $this = $(this);
    const $container = $this.closest('.container-css');
    const $label = $container.find('.required-label');
    const $requiredCheckbox = $container.find('.field-Is');
    const checked = $requiredCheckbox[0].checked;
    checked ? $label.show() : $label.hide();
  });


  $(document).on('click', '.modify', function (e) {
    var icone = $(this).children()[0];

    if($(icone).hasClass('fa-pencil-alt')) {
      var currentLi =$(this).closest($(this).closest('.element-input'));

      currentLi.find('.element-data').hide();
      description = currentLi.find('.element-desc');
      $(this).closest('.row').find('.element-edit').show().find('input');
      console.log(description);
      if(description.hasClass('display') ){
        currentLi.find('.element-desc').show();

      }
      var currentElmtdata = currentLi.find('div.element-type');
      currentLi.find('.element-data').hide();
      currentLi.find('.dropdown-button').show();
      currentLi.find('.element-edit').show().find('input');
      $(this).closest('.row').find('.fa-pencil-alt').removeClass('fa-pencil-alt').addClass('fa-check ').closest('a').addClass('blue darken-2 white-text btn field').removeClass('btn-flat');
      val = currentLi.find('.select-type').children("option:selected").val();

      if (val=="MC" || val=="SC" || val=="LS"){
        currentLi.find('.param-btn').show();
        if(val=="LS"){
          var value = currentLi.find(".element-linear");
          value.show();
        }
      }
    }
    else{

      var currentLi = $(this).closest($(this).closest('.element-input'));
      param = currentLi.find('li.param').find('.param-value');
      title = currentLi.find('.field-title').val();
      description = currentLi.find('.field-desc').val();
      console.log(param);
      $(this).closest('.element-input').find('.element-desc');
      valide = true;
      currentLi.find(".element-linear").hide();

      if (title == "") {
        valide = false;
        currentLi.find('.field-title').val("question");
      }

      param.each(function () {
        $(this).closest('.type-save').children('.element-data').text($(this).val());
        console.log($(this).closest('.type-save').children('.element-data'));
        if ($(this).val() == "") {
          $(this).val("option");
        }

      })
      $(this).closest('.row').find('.element-data').show();
      $(this).closest('.row').find('.element-edit').show().find('input');
      currentLi.find('.element-data').show();
      currentLi.find('.element-edit').hide().find('input');
      currentLi.find('.param-btn').hide();
      currentLi.find('.element-desc').hide();
      currentLi.find('.element-desc').hide();
      $(this).closest('.row').find('.fa-check').removeClass('fa-check ').addClass('fa-pencil-alt').closest('a').addClass('btn-flat').removeClass('blue darken-2 white-text btn field');
      $(this).closest('li.element-input').find('.title').children().remove();
      $(this).closest('li.element-input').find('.description-val').text(description);
      $(this).closest('li.element-input').find('.title').append("<h5 class=\"header\">" + title + "</h5>");
    } });

  $(document).on('change', '.select-type' , function (e) {
    var selectedField = $(this).children("option:selected").val();
    var btnParameter = $(this).closest('li.element-input').find('.param-btn');
    var data =$(this).closest('li.element-input').attr("id");
    $collectionHolderparam = $(this).closest('li.element-input').children('ul.param');
    var selectedIndexparam = ($(this).hasClass('param-btn')) ? $collectionHolderparam.children().length : $collectionHolderparam.children().index($(this).closest('li')) + 1;
    var selectedIndexparam = ($(this).hasClass('select-type')) ? $collectionHolderparam.children().length : $collectionHolderparam.children().index($(this).closest('li')) + 1;
    var currentLi = $(this).closest('li.element-input');
    var currentElmtdata = currentLi.find('div.element-type');
    var selectedFieldText = $(this).children("option:selected").text();
    var linear = currentLi.find(".element-linear");
    $(this).closest('ul.row').find('.update-labels').text(selectedFieldText);

    switch (selectedField) {
      case "MC":
        currentElmtdata.children().remove();
        $(this).closest('li.element-input').find('ul.param').find('li.param').remove();
        btnParameter.show();
        updateParam(currentLi.attr('id'),selectedField,true);
        addParamForm($collectionHolderparam, $(this), selectedIndexparam);
        linear.hide();
        break;
      case "SC":
        currentElmtdata.children().remove();
        $(this).closest('li.element-input').find('ul.param').find('li.param').remove();
        btnParameter.show();
        updateParam(currentLi.attr('id'),selectedField,true);
        addParamForm($collectionHolderparam, $(this), selectedIndexparam);
        linear.hide();
        break;
      case "UC":
        currentElmtdata.children().remove();
        $(this).closest('li.element-input').find('ul.param').find('li.param').remove();
        currentElmtdata.children().remove();
        currentElmtdata.prepend('   <div class="switch">\n' +
          '    <label>\n' +
          '      <label class="label-switch" id="0">Oui</label>\n' +
          '      <input type="checkbox">\n' +
          '      <span class="lever"></span>\n' +
          '      <label class="label-switch" id="1">Non</label>\n' +
          '    </label>\n' +
          '  </div> ');
        btnParameter.hide();
        linear.hide();
        updateParam(currentLi.attr('id'),selectedField,true);
        addParamForm($collectionHolderparam, $(this), selectedIndexparam);
        currentLi.find('.element-choice').find('input').addClass("param-choice").val("oui");
        addParamForm($collectionHolderparam, $(this), selectedIndexparam);
        currentLi.find('.element-choice').last().find('input').addClass("param-choice").val("non");
        currentLi.find('.delete').hide();


        break;
      case "LT" :

        currentElmtdata.children().remove();
        currentElmtdata.prepend(' <textarea id="textarea1" class="materialize-textarea"></textarea> ');
        btnParameter.hide();
        $(this).closest('li.element-input').find('ul.param').find('li.param').remove();
        linear.hide();
        updateParam(currentLi.attr('id'),selectedField,false);
        removeAllParam(data)
        break;
      case "ST" :
        currentElmtdata.children().remove();
        currentElmtdata.prepend(" <input type='text'> ");
        btnParameter.hide();
        $(this).closest('li.element-input').find('ul.param').find('li.param').remove();
        linear.hide();
        updateParam(currentLi.attr('id'),selectedField,false);
        removeAllParam(data)
        break;
      case "LS" :
        currentElmtdata.children().remove();
        $(this).closest('li.element-input').find('ul.param').find('li.param').remove();
        removeAllParam(data);
        updateParam(currentLi.attr('id'),selectedField,false);
        linear.show();
        break;
      default:
        btnParameter.hide();
        linear.show();
        $(this).closest('li.element-input').find('ul.param').find('li.param').remove();
        removeAllParam(data)
    }
  });

  $(document).on('change','.param-choice',function (e) {
  currentLi = $(this).closest('.element-input');

  position =$(this).closest('.param').attr('id');
  label = currentLi.find('.label-switch')[parseInt(position)];
  $(label).text($(this).val());
  console.log($(label));


  })

  $(document).on('click', '.remove-element, .insert-btn  , .param-btn, .remove-parameter ', function (e) {
    // prevent the link from creating a "#" on the URL
    e.preventDefault();
    $collectionHolder = $('ul.elements');
    $collectionHolderparam = $(this).closest('li.element-input').children('ul.param');
    $collectionHolderparamLinear = $(this).closest('li.element-input').children('ul.param');

    var total = $collectionHolder.data('total');
    var totalparam = $collectionHolderparam.data('total');
    var totalparamLinear = $collectionHolderparamLinear.data('total');
    var selectedIndex = ($(this).hasClass('insert-btn')) ? $collectionHolder.children().length : $collectionHolder.children().index($(this).closest('li')) + 1;
    var selectedIndexparam = ($(this).hasClass('param-btn')) ? $collectionHolderparam.children().length : $collectionHolderparam.children().index($(this).closest('li')) + 1;
    var selectedIndexparamLinear = ($(this).hasClass('param-btn')) ? $collectionHolderparamLinear.children().length : $collectionHolderparamLinear.children().index($(this).closest('li')) + 1;

    if ($(this).hasClass('remove-element')) {

      $('.tooltipped').tooltip('close');
      $('.tooltipped').tooltip();
      if ($collectionHolder.children().length == 1) {
        $('.create-targets').attr('disabled', true);
      }


      if (selectedIndex < $collectionHolder.children().length) {
        for (i = selectedIndex; i <= $collectionHolder.children().length - 1; i++) {

          $collectionHolder.find('h4:eq(' + (i) + ')').text("Question " + (i));
          $collectionHolder.find('i.fa-trash:eq('+(i)+')').prop('id',i-1);
        }
      }
      $collectionHolder.data('total', $collectionHolder.data('total') - 1);
      $(this).closest('li').remove();

    } else if ($(this).hasClass('remove-parameter')) {

      if ($collectionHolderparam.children().length == 1) {
        $('.create-targets').attr('disabled', true);
      }


      if (selectedIndex < $collectionHolderparam.children().length) {
        for (i = selectedIndex; i <= $collectionHolderparam.children().length - 1; i++) {

          $collectionHolderparam.find('h4:eq(' + (i) + ')').text("parametre " + (i));
        }
      }
      $collectionHolderparam.data('total', $collectionHolderparam.data('total') - 1);
      $(this).closest('li').remove();

    }

    else if ($(this).hasClass('insert-btn')) {

      addQuestionForm($collectionHolder, $(this), selectedIndex);

    }
    else if ($(this).hasClass('param-btn')) {

      type = $(this).closest('li.element-input').find('.select-type').children("option:selected").val();

        addParamForm($collectionHolderparam, $(this), selectedIndexparam);
    }
  });


  function addQuestionForm($collectionHolder) {
    // Get the data-prototype
    var prototype = $collectionHolder.data('prototype');
    var total = $collectionHolder.data('total');
    console.log(total);

    // Replacing prototype constants

    var newForm = prototype
      .replace(/__name__/g, total + 1)
      .replace(/__qnb__/g, total + 1)
      .replace(/__DeleteButton__/g, '<a class="waves-effect waves-light accent-4 btn-flat tooltip remove-field remove-element" id="'+total+'"><i class="fa fa-trash" id="'+total+'"></i></a>');

    // increase the index with one for the next item
    $collectionHolder.data('total', total + 1);

    // Display the form in the page in an li, before the "Add a user" link li
    var $newFormLi = $('<li class="element-input darken-1 card-panel" id="'+total+'" ></li>').append(newForm);


    $newFormLi.find('.tooltipped').tooltip();
    $newFormLi.find('.field-title').val("Question" );
    var currentElmtdata = $newFormLi.find('div.element-type');
    var currentid = $newFormLi.find('.survey-id');
    currentElmtdata.append(" <input type='text'> ");


    //Remove temporarily
    $('.fixed-action-btn').addClass('floating-add').removeClass('fixed-action-btn');

    $collectionHolder.append($newFormLi);
    $('.floating-add').addClass('fixed-action-btn').removeClass('floating-add');
    $('.dropdown-button').dropdown({
        inDuration: 300,
        outDuration: 225,
        click: true, // Activate on hover
        closeOnClick: true,
        alignment: 'right',

      }

    );
  }

  function addParamForm($collectionHolder) {
    // Get the data-prototype
    var prototype = $collectionHolder.data('prototype');
    var type = $collectionHolder.closest('li.element-input').find('.select-type').val();
    var id  = $collectionHolder.closest('li.element-input').attr('id');
    updateParam(id,type,false);
    var total = $collectionHolder.closest('ul.param').find('li.param').length;
    // Replacing prototype constants
    if(type=="MC") {
      var newForm =
      prototype
      .replace(/__Type__/g, /*html*/`
        <div>
          <input type="checkbox">
          <label></label>
        </div>
      `)
      .replace(/__DeleButton__/g, /*html*/`
        <div class="waves-effect waves-light accent-4 btn-flat tooltip delete-btn remove-parameter" id="${total}" >
          <a class="remove-parameter" id="${total}">
            <i class="fas fa-times-circle remove-parameter" id="${total}"></i>
          </a>
        </div>
      `)
      .replace(/__pname__/g, total);
    }
    else if (type=="SC"){
      var newForm =
      prototype
      .replace(/__Type__/g, /*html*/`
        <div>
          <input type="radio" id="check">
          <label></label>
        </div>
      `)
      .replace(/__DeleButton__/g, /*html*/`
        <div class="waves-effect waves-light accent-4 btn-flat tooltip delete-btn remove-parameter" id="${total}" >
          <a class="remove-parameter" id="${total}">
            <i class="fas fa-times-circle remove-parameter" id="${total}"></i>
          </a>
        </div>
      `)
      .replace(/__pname__/g, total);
    }
    else{
      var newForm =
        prototype
          .replace(/__Type__/g, '')
          .replace(/__DeleButton__/g, /*html*/`
        <div class="waves-effect waves-light accent-4 btn-flat tooltip delete-btn remove-parameter" id="${total}" >
          <a class="remove-parameter" id="${total}">
            <i class="fas fa-times-circle remove-parameter" id="${total}"></i>
          </a>
        </div>
      `)
          .replace(/__pname__/g, total);

    }


    var $newFormLi = $('<li class="param" id='+(total)+'></li>').append(newForm);
    if(type=="LS") {
      $newFormLi.find('.element-linear').show();
    }
    if(type=="UC"){
      $newFormLi.find('.element-edit').addClass("element-choice");
    }
    $newFormLi.find('.param-value').val("Option " );
    $newFormLi.find('.tooltipped').tooltip();

    $collectionHolder.data('total', total + 1);
    //Remove temporarily
    $('.fixed-action-btn').addClass('floating-add').removeClass('fixed-action-btn');

    $collectionHolder.append($newFormLi);
    $('.floating-add').addClass('fixed-action-btn').removeClass('floating-add');
  }



});
