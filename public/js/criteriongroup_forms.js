$($ => {
  $('select').material_select();

  /**
   * @type JQuery<HTMLSelectElement>
   */
  const $organizationSelect = $('select.organization-select');
  /**
   * @type JQuery<HTMLSelectElement>
   */
  const $departmentSelect = $('select.department-select');

  if ($organizationSelect) {
    $organizationSelect.change(function() {
      const $this = $(this);
      /**
       * @type HTMLOptionElement
       */
      const selected = $this.children(':selected')[0];

      const departments = selected.dataset.departments.split(',');

      $departmentSelect.children().each(function() {
        this.disabled = !departments.includes(this.value);
      });
      $departmentSelect.children(':not(:disabled)').eq(0).prop('selected', true);
      $departmentSelect.material_select();
    });
  }
});
