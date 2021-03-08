$(function(){

    $('select').material_select();

    const regExp = /~(.+)~/;
    $('.event-type-select .select-dropdown').each(function (_i, e) {
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
            `<span class="evt-icon opt-icon-elmt" data-icon="${icon}"></span>`
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

    $('#eventTypeSelector').on('change',function(){
      const $selector = $('#eventTypeSelector');
      const val = $selector.find('option:selected').text();
      const match = val.match(regExp);
      let icon = String.fromCodePoint && match && match[1] ? String.fromCodePoint('0x' + match[1]) : '';
      if (!match) return;
      $('.event-type-select input.select-dropdown').val(val.replace(regExp, icon))
    })

    $(document).on('mouseover','.event-type-zone',function(){
        var $this = $(this);
        $this.find('.event-type-act-btns').show();
      }).on('mouseleave','.event-type-zone',function(){  
        var $this = $(this);
        $this.find('.event-type-act-btns').hide();
      })

    $('[href="#manageTrans"]').on('click',function(){
        const $this = $(this);

        const $params = {id: $this.closest('.event-type-zone').data('id'), e: 't', p: 'name'}
        $.post(rturl,$params)
          .done(function(data){
            $.each(data,function(i,transElmt){
              $('[name="t-fr"]').val(transElmt.fr);
              $('[name="t-en"]').val(transElmt.en);
              $('.update-trans-btn').data('id',$this.closest('.event-type-zone').data('id'));
            })
        })
    })

    $('[data-target="selectIcons"]').on('click',function(){
        const $this = $(this);
        const $modal = $('#selectIcons');
        $modal.find('.btn-i-select.dd-bg').removeClass('dd-bg white-text');
        $modal.find(`.btn-i-select[data-id="${$this.data('id')}"]`).addClass('dd-bg white-text');
        if($('.add-new-icon-btn')){
          $('.add-new-icon-btn').removeClass('add-new-icon-btn').addClass('update-icon-btn');
        }
        $('.update-icon-btn').attr('data-id',$(this).closest('.event-type-zone').data('id'));
    });

    $('.btn-i-select').on('click',function(){
      const $this = $(this);
      $('.btn-i-select').not($this).removeClass('selected');
      $this.addClass('selected');
    })

    $('[href="#addEventType"]').on('click',function(){
      const $this = $(this);
      const $modal = $('#addEventType');
      $modal.find('.event-group-name').empty().append($this.closest('.event-group-zone').find('.event-group-name').text());
    });

    $('.set-icon-btn').on('click',function(){
      $('.update-icon-btn').removeClass('update-icon-btn').addClass('add-new-icon-btn');
    })

    $(document).on('click','.add-new-icon-btn',function(){
      const $selectedIcon = $('.btn-i-select.selected');
      $('.set-icon-btn')
        .attr('data-id', $selectedIcon.data('id'))
        .attr('data-icon', $selectedIcon.data('icon'))
        .attr('data-icon-type', $selectedIcon.data('type'));
      $('.add-new-icon-btn').removeClass('add-new-icon-btn').addClass(`update-icon-btn evt-${$selectedIcon.data('type').indexOf('fa') > -1 ? 'fa' : 'm'}-icon`);
    })

    $('[name="creation-type"]').on('change',function(){
      if($(this).val() == 1){

        $('#eventTypeSelector').closest('.select-wrapper').hide();
        $('.evt-input-zone').show();
      } else {
        $('#eventTypeSelector').closest('.select-wrapper').show();
        $('.evt-input-zone').hide();
        $('.set-icon-btn').attr({
          'data-id' : '',
          'data-icon' : '',
        });
        $('input[name="evt"]').empty();
      }
    })

    $(document).on('click','.update-icon-btn',function(){
      const $this = $(this);
      const $selectedIcon = $('.btn-i-select.selected');
      const $params = {id: $this.data('id'), icoId: $selectedIcon.data('id')}
      $.post(uiurl,$params)
      .done(function(data){
        $(`.event-type-zone[data-id="${$this.data('id')}"] button.existing-icon`)
        .attr('data-id', $selectedIcon.data('id'))
        .attr('data-icon', $selectedIcon.data('icon'))
        .attr('data-icon-type', $selectedIcon.data('type'));
      })
    })

    $(document).on('click','.update-trans-btn',function(){
        const $this = $(this);
        const $trans = {fr: $('[name="t-fr"]').val(), en: $('[name="t-en"]').val()}
        const $params = {id: $this.data('id'), trans: $trans};
        $.post(uturl,$params)
          .done(function(data){
            switch(window.navigator.language.slice(0,2)){
              case 'fr':
                  $(`.event-type-zone[data-id="${$params.id}"] span`).empty().text($trans.fr);
                  break;
              case 'en':
                  $(`.event-type-zone[data-id="${$params.id}"] span`).empty().text($trans.en);
                  break;
              default:
                break;
            }
          })
    })

    /*
    $('[href="#addEventType"]').on('click',function(){
        $(this).find('select').material_select()
    });
    */
    
})