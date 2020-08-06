$(function () {


  $(document).on('click', ' .remove-field , .insert , .remove-parameter , .insert-parameter ,.field', function (e) {
    e.preventDefault();
    $collectionHolder = $('div.question');
    $collectionHolderparam = $collectionHolder.children("ul.fields");
    var total = $collectionHolder.data('total');
    var totalparam = $collectionHolderparam.data('total');
    var selectedIndex = ($(this).hasClass('.insert')) ? $collectionHolder.children().length : $collectionHolder.children().index($(this).closest('li')) + 1;

    if ($(this).hasClass('remove-field')) {
      data = $(this).closest('li.element-input').attr('id');
      console.log(data);
      data = parseInt(data);


      $.ajax({
        method: "POST",
        url: urlremove,
        data: { data: data },
        success: function () {
//location.reload()
        }
      });

    }
    else if($(this).hasClass('remove-parameter')){
      param = e.target.id;
      data = $(this).parent('div.waves-effect').attr('id');
      data = parseInt(data);
      param = parseInt(param);
      $.ajax({
        method: "POST",
        url: urlparamdelete,
        data: { data: data , param:param },
        success: function () {
       //   location.reload()
        }
      });

    }
    else if($(this).hasClass('insert-parameter')){

      data = $(this).parent('li.element-input').attr('id');
      console.log($(this).parent('li.element-input').attr('id'));
      type = $(this).parent('li.element-input').find('.select-type').children("option:selected").val();
      data = parseInt(data);
      updateParam(data,type,true);



    }




    else if ($(this).hasClass('insert')){


      $.ajax({
        method: "POST",
        url: url,
        success: function () {
          // location.reload()

        }
      });

    }


    else {
      currentLi=$(this).closest('li.element-input');
     id = currentLi.attr('id');

      type = currentLi.find('.select-type').children("option:selected").val();
      mand= currentLi.find('.field-Is').is(':checked');
      inputTitle=currentLi.find('.field-title')
      title =currentLi.find('.field-title').val();
      param =currentLi.find('li.param').find('input.param-value');
      lower=currentLi.find('.field-low option:selected').text();
      upper=currentLi.find('.field-upp option:selected').text();
      description=currentLi.find('.field-desc').val();
      console.log(lower);
      console.log(upper)
      const value=[];
      valide=true;
      console.log(param);
      param.each(function(){
        value.push($(this).val());
    })

      var data = new Object();
      data.id = parseInt(id);
      data.type  =type;
      data.mand = mand;
      data.title = title;
      data.description=description;
      data.lowerbound= lower;
      data.upperbound= upper;
      data.value = value;

      var jsonData= JSON.stringify(data);
      console.log(jsonData);
        $.ajax({
          method: "POST",
          url: urlSaveField,
         // data: { data: data , type : type , mandatory:mand ,title:title},
          data: { data: jsonData},
          success: function () {


          }
        });


    }
  })

  $(document).on('focusout', ' .field-title , .surveyTitleLabel , .param-value ', function (e) {


    if ($(this).hasClass('surveyTitleLabel')){
      if($(this).val()==""){
        $(this).val("Titre du questionnaire");
      }
    }

    if ($(this).hasClass('field-title')){
      title =$(this).val();
      if(title==""){
        $(this).val("Question");
      }
    }else{
      option =$(this).val();
      if(option==""){
        $(this).val("Option");
      }

    }

  })
  $(document).on('click', ' .copy-field ', function (e) {

    currentLi=$(this).closest('li.element-input');
    var copie = currentLi.clone();
    currentLi.after(copie);
    var dropdownCopy = $(copie).find(".dropdown-content");
    console.log(dropdownCopy);
    id = currentLi.attr('id');
    $('ul.dropdown-content').hide();

    type = currentLi.find('.select-type').children("option:selected").val();
    console.log(copie);
    mand= currentLi.find('.field-Is').is(':checked');
    title =currentLi.find('.field-title').val();
    param =currentLi.find('li.param').find('input.field-value');

    const value=[];
    param.each(function(){

      value.push($(this).val());

    })

    var data = new Object();
    data.id = id;
    data.type  =type;
    data.mand = mand;
    data.title = title;
    data.value = value;
    var jsonData= JSON.stringify(data);
    $.ajax({
      method: "POST",
      url: urlcopy,
      data: { data: jsonData},
      success: function () {
        //   location.reload()
      }
    });

  $('.dropdown-button').dropdown({
    inDuration: 300,
    outDuration: 225,
    click: true, // Activate on hover
    closeOnClick: true,
    alignment: 'right',

  }
);
  });

});
function updateParam(id,type,bool) {
  console.log('type'+type);

    $.ajax({
      method: "POST",
      url: urlparaminsert,
      data: {data: id, type: type,bool:bool},
      success: function () {

        //  location.reload()
      }
    });

}

function removeAllParam(data) {

  $.ajax({
    method: "POST",
    url: urlremoveallparm,
    data: { data: data },
    success: function () {
      location.reload();
    }
  });
}
