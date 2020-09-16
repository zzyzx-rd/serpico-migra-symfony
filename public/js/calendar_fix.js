$(() => {
  const isMac = /macintosh/i.test(navigator.userAgent);

  if (isMac) {
    $(document).on('mousedown', '.dp-start, .dp-end, .dp-gstart, .dp-gend', () => {
      setTimeout(() => {
          const $elmt = $(e.currentTarget);
          setTimeout(() => {
            $elmt.click();
            $elmt.focus();
          }, 100);
        });
      }, 20);
    };

    $(document).on('mousedown', '.select-dropdown', e => {
      const $selectDropdown = $(e.currentTarget);
      setTimeout(function () {
        if (!$selectDropdown.hasClass('active')) {
          $selectDropdown.click();
        }
      }, 400);
    });
  
});
