$(function () {
  var compteur = 0;
  var moy = 100 / ($('.answer').length);
  var i = moy;
  var tab = [];

  $('.modal').modal();
  if($('#errorModal')) {
    setTimeout(function () {
      $('#errorModal').modal('open');
      $('#errorModal .modal-content ul').css('display', 'inline-block').addClass('no-margin');
    }, 200)
  }

  $('.disable-other').each(function (key, value) {

    $(this).find('input').prop( "disabled", true );


  });

  var update = false;
  $('.grade-slider').each(function (key, value) {

    noUiSlider.create(value, {
      start: $(this).next().next().val(),
      step:  1,
      connect: [true, false],
      range: {
        'min':  0,
        'max':10,}
    })

    if($(this).hasClass('disable-slider')){

    value.setAttribute('disabled', true);

    }

    data = $(this).closest('.answer').find('.label-slider');
    console.log(data);

    value.noUiSlider.on('update', function (values, handle) {

      value.nextElementSibling.nextElementSibling.innerHTML = ($('.contributive').length == 0) ? Number(values[handle]).toFixed((Number(value.dataset.step))) : Number(values[handle]) + ' %';
      $(value).next().next().val(parseInt(values[handle]));


      if($('.contributive').length > 0){
        $('.total-percentage>span').empty();
        var totalPct = 0;
        $('input[type="text"]').each(function(){
          totalPct += Number($(this).val());
        });
        (totalPct > 100) ? $('.total-percentage>span').addClass('red-text') : $('.total-percentage>span').removeClass('red-text');
        $('.total-percentage>span').append(totalPct + ' %');
      }
      for (u=0;u<data.length;u++){
        li=data[u];
        const strCopy = li.id.replace('[ ', '').replace(']', '').split(",");

        upper=parseInt(strCopy[1]);
        lower=parseInt(strCopy[0]);
        valInt =parseInt(values[handle]);
        if((lower<=valInt) && (valInt<=upper)){

          li.style.display = "inline-block";

        }else{
          li.style.display = "none";
        }
      }
    })
  });

  $('.progress').find('.determinate').css('width', moy + '%');
  $('.answer').hide();
  $('.answer').first().show();
  $('.answer').each((function (key, value) {
      var txt=$(this).find('input').val();
      var multiple=$(this).find('input:checked');
      var vide = true;
  }));
  $('input').each(function (key, value) {

    console.log('test');
  })

  if ($('.answer:checkbox')) {
    $('.answer').find('input[type=checkbox]').addClass('filled-in');
  }
  $(document).find('p.rank').text((compteur + 1) + "/" + ($('.answer').length));

  if (compteur <= 0) {
    $(document).find('.prec').css('visibility', 'hidden');
  }

  if (compteur >= $('.answer').length - 1) {
    $(document).find('.suiv').css('visibility', 'hidden');
    $(document).find('button').css('visibility', 'visible');
  }

  if ($('.answer').find('.uniqueChoice')) {
    $('.answer').find('.uniqueChoice').parent('div').addClass('switch');
    $('.switch').find('input').wrapAll('<label class="labelTest" data-children-count="1"></label>');
    $('.switch').find('.labelTest').prepend('<br>');
    console.log($('.answer').find('.label-uc'));
    firstLabel =$('.answer').find('.label-uc')[0];
    secondLabel =$('.answer').find('.label-uc')[1];
    $('.switch').find('.labelTest').append($(firstLabel).text()+'<span class="lever"></span>'+$(secondLabel).text());
  }

  $(document).on('click', 'submit', function (e) {
    $(document).find('.falseQuestion').append('tab');
  })

  $(document).on('click', '.suiv', function (e) {

    $('.answer:eq('+ compteur + ')').css('display', 'none');
    console.log(i);
    $('.answer:eq('+ (compteur+1) + ')').show();


    $('.prec').css('visibility', 'visible');

    compteur++;
    moy += i;

    $('.progress').find('.determinate').css('width', moy + '%');
    $(document).find('p.rank').text((compteur + 1) + "/" + ($('.answer').length));

    if (compteur >= $('.answer').length - 1) {
      $(document).find('.suiv').css('visibility', 'hidden');
    }
  })



  $(document).on('click', '.prec', function (e) {
    $('.answer:eq(' + compteur + ')').css('display', 'none');

    $('.answer:eq(' + (compteur - 1) + ')').show();
    $('.suiv').css('visibility', 'visible');

    compteur--;
    moy -= i;

    $('.progress').find('.determinate').css('width', moy + '%');
    $(document).find('p.rank').text((compteur + 1) + "/" + ($('.answer').length));

    if(compteur<=0){
      $(document).find('.prec').css('visibility', 'hidden');
    }

  })
})
