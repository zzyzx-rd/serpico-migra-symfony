$($ => {
  $('#waitingSpinner').remove();

  const routes = {
    removeCriterionGroup: {
      url: $('#removeCriterionGroupRoute').attr('value'),
      method: 'delete'
    },
    removeCriterion: {
      url: $('#removeCriterionRoute').attr('value'),
      method: 'delete'
    },
    updateCriterionGroupName: {
      url: $('#updateCriterionGroupNameRoute').attr('value'),
      method: 'patch'
    },
    updateCriterionName: {
      url: $('#updateCriterionNameRoute').attr('value'),
      method: 'patch'
    },
    createCriterionGroup: {
      url: $('#createCriterionGroupRoute').attr('value'),
      method: 'put'
    },
    addCriterion: {
      url: $('#addCriterionRoute').attr('value'),
      method: 'post'
    },
    setCgpDepartment: {
      url: $('#setCgpDepartmentRoute').attr('value'),
      method: 'patch'
    },
    setCriterionIcon: {
      url: $('#setCriterionIconRoute').attr('value'),
      method: 'patch'
    },
  };

  const translations = {
    cgpDeletionConfirmation: $('#cgp_deletion_confirmation__trans').attr('value')
  };

  const $newCriterionNameForm = $('#new-criterionname-label-modal');

  const userAddedCNLabels_localStorage = localStorage.getItem('labels');
  /** @type {string[]} */
  const userAddedCNLabels = userAddedCNLabels_localStorage
                          ? JSON.parse(userAddedCNLabels_localStorage)
                          : [];


  /**
   * add label to localStorage (for saving manually-added CN labels)
   * @param {string} label CNLabel
   */
  function addUserCNLabel(label) {
    userAddedCNLabels.push(label);
    localStorage.setItem('labels', JSON.stringify(userAddedCNLabels));
  }


  /**
   * @param {JQuery.ClickEvent} e
   */
  function onConfirmEditCriteriongroupname(e) {
    if (e.key && e.key != 'Enter') {
      return;
    }

    e.preventDefault();
    e.stopPropagation();

    /**
     * @type {JQuery}
     */
    const $this = $(e.target);
    /**
     * @type {JQuery}
     */
    const $item = $this.closest('.criteriongroupname');
    const $nameInput = $item.find('input:text');
    const cgpId = $item.closest('.criterion-groups--item').data('id');
    const defaultName = $nameInput.attr('value');
    const name = $nameInput.val();

    $nameInput.attr('disabled', '');

    if (name) {
      $.ajax({
        url: routes.updateCriterionGroupName.url.replace('000', cgpId),
        method: routes.updateCriterionGroupName.method,
        data: { name }
      }).done(() => $item.removeClass('editing').find('h3').html(name));
    } else {
      $nameInput.prop('value', defaultName)
        .siblings('label').addClass('active');
      $item.removeClass('editing');
    }
  }

  /**
   * @param {JQuery.ClickEvent} e
   */
  function onConfirmEditCriterionname(e) {
    if (e.key && e.key != 'Enter') {
      return;
    }

    e.preventDefault();
    e.stopPropagation();

    /**
     * @type {JQuery}
     */
    const $this = $(e.target);
    /**
     * @type {JQuery}
     */
    const $item = $this.closest('.criteria-list--item');
    const $nameInput = $item.find('.criterionname-input:input');
    const critId = $item.data('id');
    const defaultName = $nameInput.attr('value');
    const name = $nameInput.prop('value');

    if (name) {
      $item.addClass('faded');
      $.ajax({
        url: routes.updateCriterionName.url.replace('000', critId),
        method: routes.updateCriterionName.method,
        data: { name }
      }).done(
        () => $item.removeClass('faded editing').find('.criterionname').html(name)
      );
    } else {
      $nameInput.prop('value', defaultName)
        .siblings('label').addClass('active');
      $item.removeClass('editing');
    }
  }

  /**
   * @param {JQuery} $modal
   */
  function onCloseCriterionGroupDepartmentModal($modal) {
    const $cgpItem = $modal.closest('.criterion-groups--item');
    const cgpId = $cgpItem.data('id');
    const $criterionGroupItem = $modal.closest('.criterion-groups--item');
    const $triggerBtn = $criterionGroupItem.find('.link-to-department-btn');
    const $departmentNameSpan = $triggerBtn.find('.department-name');
    const $departmentSelect = $modal.find('select.criteriongroup-department-select');
    const linkedDptId = $departmentSelect.val();
    const selectedDepartment = $departmentSelect.children(':selected').html();
    const form = /*html*/`
      <form action="${routes.setCgpDepartment.url.replace('000', cgpId)}"
            method="${routes.setCgpDepartment.method}">
        <input type="text" name="linked-dpt-id" value="${linkedDptId}">
      </form>
    `;
    const $form = $(form);


    $triggerBtn.attr('disabled', '');
    $departmentNameSpan.html('&hellip;');
    $.ajax({
      url: $form.attr('action'),
      method: $form.attr('method'),
      data: $form.serialize()
    })
    .done(() => {
      $triggerBtn.removeAttr('disabled');
      if ($departmentSelect.val()) {
        $criterionGroupItem.addClass('dpt-linked');
        $triggerBtn.addClass('lime darken-3');
        $departmentNameSpan.html(selectedDepartment);
      } else {
        $criterionGroupItem.removeClass('dpt-linked');
        $triggerBtn.removeClass('lime darken-3');
        $departmentNameSpan.empty();
      }
    });
  }

  /**
   * @param {JQuery} $modal
   * @param {JQuery} $trigger
   */
  function onOpenSetCNIconModal($modal, $trigger) {
    const icoId = $trigger.data('id');
    const $iconInput = $modal.find(`[name="cn-icon"][value="${icoId}"]`);
    $iconInput.prop('checked', true);
  }

  /**
   * @param {JQuery} $modal
   */
  function onCloseSetCNIconModal($modal) {
    /** @type {JQuery} */
    const $trigger = this.openingTrigger;
    const $selected = $modal.find('[name="cn-icon"]:checked');
    const cnId = $trigger.closest('.criteria-list--item').data('id');
    const icoId = +$selected.val() || -1;

    if (!cnId) {
      return false;
    }

    $modal[0].reset();
    $trigger
      .attr('disabled', '')
      .attr('data-icon', '')
      .attr('data-icon-type', '');

    const $form = $(/*html*/`
      <form action="${routes.setCriterionIcon.url.replace('000', cnId)}"
            method="${routes.setCriterionIcon.method}">
        <input type="text" name="ico-id" value="${icoId}">
      </form>
    `);

    $.ajax({
      url: $form.attr('action'),
      method: $form.attr('method'),
      data: $form.serialize()
    })
    .always(() => $trigger.removeAttr('disabled'))
    .done(response => {
      const icon = response.icon;
      const type = response.type;

      $trigger
        .data('id', icoId)
        .attr('data-icon', icon)
        .attr('data-icon-type', type);
    });
  }
  const $criterionGroupsList = $('#criterion-groups-list');
  const $addCriterionGroupBtn = $('#add-criteriongroup-btn');

  const criterionGroupProto = $('#criterion-groups-list > .proto').html();
  const criterionProto = $('#criterion0-proto').html();

  function nextCriterionGroupFormIndex() {
    const $lastCGItem = $('.criterion-groups--item').last();

    if (!$lastCGItem.length) return 0;

    const index = +$lastCGItem.find('> .collection-hidden-index label').html();
    return index + 1;
  }


  /**
   * Disables options in criterion name selects as appropriate
   * @param {JQuery|HTMLElement} target
   */
  function handleCNSelectElems(target) {
    const $cgpElems = target
                    ? $(target).closest('.criterion-groups--item')
                    : $('.criterion-groups--item');
    const $selects = $cgpElems.find('select.criterionname-input');
    let inUse;

    $selects.children().prop('disabled', false);

    for (const cgpElem of $cgpElems) {
      const $cgpElem = $(cgpElem);
      /** @type {JQuery} */
      const $selects = $cgpElem.find('select.criterionname-input');
      const $options = $selects.children();
      inUse = $options.filter(':selected').get().map(e => e.value);
      const $optionsToDisable = $options.filter((_i, e) => inUse.includes(e.value));

      $optionsToDisable.prop('disabled', true);
    }

    $selects.material_select();

    return target ? inUse : null;
  }
  handleCNSelectElems();



  $('select').material_select();
  $('.criteriongroup-department-modal').modal({
    complete: onCloseCriterionGroupDepartmentModal
  });
  $('#set-icon-modal').modal({
    ready: onOpenSetCNIconModal,
    complete: onCloseSetCNIconModal
  });
  $newCriterionNameForm.modal({
    ready: ($modal, $trigger) => $modal.data('$trigger', $trigger),
    complete: $modal => $modal.get(0).name.value = ''
  });



  $addCriterionGroupBtn.click(() => {
    const index = nextCriterionGroupFormIndex();
    const cgpCriterionProto = criterionProto.replace(
      /criterionGroups(_|\]\[)__name__(_|\]\[)criteria/g,
      `criterionGroups$1${index}$2criteria`
    );
    const proto = criterionGroupProto
      .replace(/__name(__label)?__/g, index)
      .replace('__criterialist_item__', cgpCriterionProto);

    $criterionGroupsList.children('li').each(function(i) {
      $criterionGroupsList.collapsible('close', i);
    });
    $criterionGroupsList.append(proto);

    $('.new-select').removeClass('new-select').material_select();
    $('.new-tooltip').removeClass('new-tooltip').tooltip();
    $('.new-modal').removeClass('new-modal').modal({
      complete: onCloseCriterionGroupDepartmentModal
    });
  });







  $(document)
  .on('keydown', 'input.new', e => e.key !== 'Enter')

  .on('input', '.criteriongroupname-input', e => {
    const classToggle = 'invalid red-text';
    /** @type {HTMLInputElement} */
    const target = e.target;
    const $target = $(target);
    const newCgpName = target.value.trim();
    /** @type {string[]} */
    const cgpNames = $('.criteriongroupname-input:not(.new)').get().map(e => e.value);

    const valid = !cgpNames.includes(newCgpName);
    $target.data('valid', valid);
    if (valid) {
      $target.removeClass(classToggle);
    } else {
      $target.addClass(classToggle);
    }
  })

  .on('click', '.create-criteriongroup-btn', e => {
    e.stopPropagation();
    const $this = $(e.target);
    const $cgpItem = $this.closest('.criterion-groups--item');
    const $cgpNameHeader = $cgpItem.find('.criteriongroupname');
    const $cgpNameInput = $cgpItem.find('.criteriongroupname-input');
    const $cgpNameItem = $cgpItem.find('.criteriongroupname-item');
    const cgpName = $cgpNameInput.val().trim();
    /** @type {boolean} */
    const nameIsValid = $cgpNameInput.data('valid');
    const $form = $(/*html*/`
      <form action="${routes.createCriterionGroup.url}"
            method="${routes.createCriterionGroup.method}">
        <input type="text" name="name" value="${cgpName}">
      </form>
    `);


    if (!cgpName || !nameIsValid) {
      return false;
    }


    $cgpNameHeader.addClass('faded');
    $cgpItem
      .find('h3.criteriongroupname-item')
      .html(cgpName);
    $cgpNameInput
      .removeClass('new')
      .attr('disabled', '');

    $.ajax({
      url: $form.attr('action'),
      method: $form.attr('method'),
      data: $form.serialize()
    })
    .always(e => {
      $cgpNameHeader.removeClass('faded');
    })
    .done(response => {
      $cgpItem.data('id', response.id);
      $cgpNameHeader.removeClass('editing');
      $cgpNameItem.removeClass('attention');
      $cgpItem
        .find('.add-criterion-btn').removeAttr('disabled');
      $cgpItem
        .find('.delete-criteriongroup-btn')
        .removeClass('new-cgp')
        .addClass('modal-trigger delete-cgp-trigger');
      $this
        .removeClass('create-criteriongroup-btn')
        .addClass($this.data('class-aftercreate'));
    });
  })

  .on('click', '.confirm-add-criterion-btn', e => {
    e.stopPropagation();
    const $this = $(e.target);
    const $cgpItem = $this.closest('.criterion-groups--item');
    const cgpId = $cgpItem.data('id');
    const $cgpcItem = $this.closest('.criteria-list--item');
    const $cgpcNameInput = $cgpcItem.find('select.criterionname-input');
    const cgpcName = $cgpcNameInput.prop('value').trim();
    const $form = $(/*html*/`
      <form action="${routes.addCriterion.url.replace('000', cgpId)}"
            method="${routes.addCriterion.method}">
        <input type="text" name="name" value="${cgpcName}">
      </form>
    `);

    if (!cgpcName) {
      return false;
    }

    $cgpcItem.find('.criterionname').html(cgpcName);
    $this.attr('disabled', '');
    $cgpcNameInput
      .attr('disabled', '')
      .removeClass('invalid');

    $.ajax({
      url: $form.attr('action'),
      method: $form.attr('method'),
      data: $form.serialize()
    })
    .always(() => $cgpcNameInput.removeAttr('disabled'))
    .done(response => {
      $cgpcNameInput.removeClass('new');
      $cgpcItem
        .data('id', response.id)
        .removeClass('editing')
        .find('.edit .delete-criterion-btn').remove();
      $this
        .removeAttr('disabled')
        .removeClass('confirm-add-criterion-btn')
        .addClass('confirm-edit-criterionname-btn');
    })
    .fail(e => {
      $this.removeAttr('disabled');
      $cgpcNameInput
        .addClass('invalid')
        .next('label').attr('data-error', e.responseText);
    });
  })

  .on('click', '.delete-criteriongroup-btn', e => {
    const $this = $(e.target);

    $this.tooltip('remove');

    if ($this.hasClass('new-cgp')) {
      const $item = $this.closest('.criterion-groups--item');
      afterCriterionGroupDelete($item);
      return;
    }

    /**
     * @type number
     */
    const cgpId = $this.data('id');
    const $item = $('.criterion-groups--item').filter((_i, e) => $(e).data('id') === cgpId);

    function afterCriterionGroupDelete($item) {
      $item.remove();

      if ($criterionGroupsList.children('.criterion-groups--item').length === 1) {
        $criterionGroupsList.collapsible('open', 0);
      }
    }


    if (cgpId === undefined) {
      afterCriterionGroupDelete($item);
    } else {
      $item.addClass('faded');
      $.ajax({
        url: routes.removeCriterionGroup.url.replace('000', cgpId),
        method: routes.removeCriterionGroup.method,
      }).done(() => afterCriterionGroupDelete($item));
    }
  });


  $criterionGroupsList

  // prevent click events from spreading beyond an input:text element
  .on('click', 'input:text', e => e.stopPropagation())

  .on('change', 'select.criterionname-input', e => handleCNSelectElems(e.target))

  .on('click', '.criteriongroup-buttons .modal-trigger', e => {
    e.stopPropagation();
    const $target = $(e.target);
    $('#'+$target.data('target')).modal('open');
  })

  .on('click', '.edit-criteriongroupname-btn', e => {
    e.stopPropagation();
    const $this = $(e.target);
    const $item = $this.closest('.criteriongroupname');
    const $nameInput = $item.find('input:text');

    $nameInput.removeAttr('disabled');
    $item.addClass('editing');
  })

  .on('click', '.confirm-edit-criteriongroupname-btn', onConfirmEditCriteriongroupname)
  .on('keydown', '.criteriongroupname-input:not(.new)', onConfirmEditCriteriongroupname)

  .on('click', '.delete-cgp-trigger', e => {
    const $this = $(e.target);
    const $modal = $('#delete-criteriongroup-modal');
    const cgpId = $this.closest('.criterion-groups--item').data('id');
    const cgpName =
      $this
      .closest('.criterion-groups--item')
      .find('input.criteriongroupname-input').val();
    const message = translations.cgpDeletionConfirmation;

    $modal.find('.modal-content').html(message.replace(
      '__cgpName__', cgpName
    ));
    $modal.find('.delete-criteriongroup-btn').data('id', cgpId);
  })

  .on('click', '.add-criterion-btn:enabled', e => {
    const $this = $(e.target);
    const $item = $this.closest('.criteria-list');
    const index = +$item.children('.criteria-list--item').last()
                        .find('.collection-hidden-index label').html();
    const proto = $item.children('.proto').html().replace(
      /__name(__label)?__/g,
      isNaN(index) ? 0 : index + 1
    );

    const $proto = $(proto);

    $item
    .removeClass('no-criteria')
    .find('.criterialist-actions').before($proto);

    handleCNSelectElems(e.target);

    const $protoSelect = $proto.find('select.criterionname-input');
    $protoSelect.append(
      userAddedCNLabels.map(e => /*html*/`<option value="${e}">${e}</option>`).join('')
    );
    $protoSelect.prop('value', $protoSelect.find('option:enabled').eq(0).attr('value'));

    handleCNSelectElems(e.target);
  })

  .on('click', '.edit-criterionname-btn', e => {
    const $this = $(e.target);
    const $item = $this.closest('.criteria-list--item');
    $item.addClass('editing');
  })
  .on('click', '.confirm-edit-criterionname-btn', onConfirmEditCriterionname)
  .on('keydown', '.criteria-list--item .criterionname-input:not(.new)', onConfirmEditCriterionname)

  .on('click', '.delete-criterion-btn', e => {
    const $this = $(e.target);
    const $item = $this.closest('.criteria-list--item');
    const $list = $this.closest('.criteria-list');
    /**
     * @type number
     */
    const crtId = $item.data('id');

    function afterDeleteCriterion() {
      $item.remove();
      const criteriaCount = $list.children('.criteria-list--item').length;
      if (!criteriaCount) {
        $list.addClass('no-criteria');
      }
      handleCNSelectElems(e.target);
    }

    if (!crtId) {
      afterDeleteCriterion();
    } else {
      $item.addClass('faded');
      $.ajax({
        url: routes.removeCriterion.url,
        method: routes.removeCriterion.method,
        data: { crtId }
      })
      .done(afterDeleteCriterion)
      .fail(() => $item.removeClass('faded'));
    }
  });

  $newCriterionNameForm.submit(e => {
    const form = e.target;
    /** @type {HTMLInputElement} */
    const name = form.name;
    const nameValue = name.value;

    if (!nameValue || $(name).hasClass('invalid')) {
      return false;
    }

    /** @type {JQuery} */
    const $trigger = $(form).data('$trigger');
    const $select = $trigger.closest('.criteria-list--item').find('select.criterionname-input');

    addUserCNLabel(nameValue);
    $('select.criterionname-input').append(/*html*/`<option value="${nameValue}">${nameValue}</option>`);
    $select.prop('value', nameValue);

    handleCNSelectElems();
    $(form).modal('close');

    return false; // tell form not to send
  });

  $('#new-criterionname-label-input').on('input', e => {
    const $this = $(e.target);
    /** @type {JQuery} */
    const $trigger = $this.closest('form').data('$trigger');
    const name = e.target.value;
    const $someCriterionNameInput = $trigger.closest('.criterion-groups--item').find('select.criterionname-input').eq(0);
    const existingNames = $someCriterionNameInput.find('option').get().map(e => e.value);

    if (existingNames.includes(name)) {
      $this.addClass('invalid');
    } else {
      $this.removeClass('invalid');
    }
  });
});
