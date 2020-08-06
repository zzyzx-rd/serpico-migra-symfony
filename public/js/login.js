$(() => {
  const isMac = /macintosh/i.test(navigator.userAgent);

  if (isMac) {
    $(document).on('mousedown', '.dp-start, .dp-end, .dp-gstart, .dp-gend', () => {
      setTimeout(() => {
        $('.picker').one('mouseup', e => {
          const $elmt = $(e.currentTarget);
          setTimeout(() => {
            $elmt.click();
            $elmt.focus();
          }, 100);
        });
      }, 20);
    });

    $(document).on('mousedown', '.select-dropdown', e => {
      const $selectDropdown = $(e.currentTarget);
      setTimeout(function () {
        if (!$selectDropdown.hasClass('active')) {
          $selectDropdown.click();
        }
      }, 400);
    });
  }

  $('#add_criterion_form_gradetype_1').on('click', () => {
    $('#add_criterion_form_lowerbound').parent().hide();
    $('#add_criterion_form_upperbound').parent().hide();
    $('#add_criterion_form_step').parent().attr('class', 'button-field col s10 m5');
  });

  $('#add_criterion_form_gradetype_0').on('click', () => {
    $('#add_criterion_form_step').parent().attr('class', 'button-field col s5 m3');
    $('#add_criterion_form_lowerbound').parent().show();
    $('#add_criterion_form_upperbound').parent().show();
  });
});
