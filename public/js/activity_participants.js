$(function() {
    /** @type {string[]} */
    const userPics = JSON.parse(document.getElementById('user-pics').innerHTML);
    const defaultUserPic = userPics[0];

    /**
     * sets user picture in participants list
     * @param {JQuery} $img img element
     * @param {number} userId user id
     */
    function setUserPic($img, userId) {
        if ( !($img instanceof jQuery) ) throw new TypeError('must be a jquery instance');

        const pictureUrl = userId && userPics[userId];
        const exists = 'string' === typeof pictureUrl;

        $img.prop('src', exists ? pictureUrl : defaultUserPic);
    }

    $('.stage').each(function(key,value){
        pValues = [];
        $(value).find('select[name*="directUser"] option:selected').each(function(key,value){
            pValues.push($(value).val());
        })

        $(value).find('select[name*="directUser"]').each(function(key,value){
            $(value).find('option:not(:selected)').each(function(key,value){
                if($.inArray($(value).val(),pValues) != -1){
                    $(value).prop('disabled',true);
                }
            })
        })
    })

    function stylizeSelect() {
        const $stylizableSelects = $('.input-field select');
        $stylizableSelects.find('option').each(function(_i, e) {
            e.innerHTML = e.innerHTML.trim()
        });
        $stylizableSelects.material_select();
    }
    stylizeSelect();

    /*$(document).on('click', 'a.p-validate:has(.fa-pencil-alt)', function(e) {
        const $row = $(this).closest('.row');
        $row.find('.element-data').hide();
        $row.find('.element-input').show();
        $row
        .find('.fa-pencil-alt').removeClass('fa-pencil-alt').addClass('fa-check')
        .closest('a').addClass('blue darken-2 white-text btn').removeClass('btn-flat');
    });*/

    $(document).on('click','a.p-validate:has(.fa-check), #add-participant-modal a.confirm-button', function(e) {
        var $this = $(this);
        var $_form = $('form[name="manage_stage_participants_form"]');
        var isFinalized = !!$_form.data('finalized');
        var inModal = $this.hasClass('confirm-button');
        var $curRow = $this.closest('.stage-participant');
        const userId = $curRow.find('select[name*="ser"]').val();
        var sid = $this.closest('.stage-participants').data('sid');
        var eid = $curRow.find('.fa-times').closest('a').attr('data-eid');
        var isNewParticipant = eid == 0;

        setUserPic($curRow.find('img.user-picture-l'), userId);

        if (!inModal && isFinalized && isNewParticipant) {
            var modal = $('#add-participant-modal');
            modal.find('.confirm-button')
                 .data('$this', $this)
                 .data('eid', eid)
                 .data('sid', sid)
                 .data('$curRow', $curRow);
            modal.find('.modal-content').html(
                modal.find('.modal-content').html().replace(
                    /__name__/g,
                    $curRow.find('.participant-name input').val()
                )
            );
            modal.modal('open');
            return;
        }

        if (inModal) {
            eid = $this.data('eid');
            sid = $this.data('sid');
            $curRow = $this.data('$curRow');
            $this = $this.data('$this');
        }

        const $icon = $this.find('.fa-check');
        e.preventDefault();
        $curRow.find('.red-text').remove();
        const $inputName = $curRow.find('input[name*="name"]');
        const $clonedRow = $curRow.clone();
        $clonedRow.find('select[name*="ser"]').attr('name', 'user').val(userId);
        $clonedRow.find('input[type="checkbox"]').attr('name','leader').val($curRow.find('input[type="checkbox"]').val());
        $clonedRow.find('textarea').attr('name','precomment').val($curRow.find('textarea').val());
        $clonedRow.find('select[name*="type"]').attr('name','type').val($curRow.find('select[name*="type"]').val());
        const $form = $('<form method="post" name="individual-element"></form>').append($clonedRow);
        const $participantName = $curRow.find('.participant-name input').val();
        const urlToPieces = window.location.pathname.split('/');
        const offSet = (mp == 1) ? 1 : 2;
        urlToPieces[urlToPieces.length - offSet] = 'stage';
        urlToPieces[urlToPieces.length - offSet + 1] = sid;
        urlToPieces.push('participant', 'validate', eid);

        const vurl = urlToPieces.join('/');
        $.post(vurl, $form.serialize())
        .done(function(data) {
            const mailed = data.mailed;
            $curRow.find('.element-input').hide();
            $curRow.find('.element-name').text($participantName);
            $curRow.find('.element-type').empty();
            $curRow.find('element-leader').remove();

            if ($curRow.find('input[type="checkbox"]').prop('checked')) {
                if ($curRow.find('.fa-star').length == 0) {
                    $curRow.find('.element-name').after(/*html*/`
                        <div class="element-leader" style="margin-left: 10px;">
                            <i class="fa fa-star tooltipped"></i>
                        </div>
                    `);
                }
            } else {
                if ($curRow.find('.fa-star').length > 0) {
                    $curRow.find('.fa-star').parent().remove();
                }
            }

            if(data.finalizable && $('#activity_element_form_update').prop('disabled')){
                $('#activity_element_form_update').prop('disabled', false);
            }

            if (mailed) {
                $curRow.find('.element-type').append('\
                    <div style="margin-left: 10px;">\
                        <i class="fa fa-envelope"></i>\
                    </div>\
                ');
            }

            if ($curRow.find('select[name*="type"]').val() == -1) {
                $curRow.find('.element-type').append(/*html*/`
                    <span
                        style="
                            background-color: orange;
                            color: white;
                            padding: 0px 5px;
                            border-radius: 100%;"
                        class="tooltipped" data-position="top" data-delay="50"
                        data-tooltip="{% trans %}tooltip.activity.passive_participant_msg{% endtrans %}"
                        >P</span>
                `);
            } else if ($curRow.find('select[name*="type"]').val() == 0) {
                $curRow.find('.element-type').append(/*html*/`
                <span
                    style="
                        background-color: purple;
                        color: white;
                        padding: 0px 5px;
                        border-radius: 100%;"
                    class="tooltipped" data-position="top" data-delay="50"
                    data-tooltip="{% trans %}tooltip.activity.tp_participant_msg{% endtrans %}"
                    >T</span>
                `);
            }

            $curRow.find('.element-data').show();
            const preComment = $curRow.find('textarea').val();
            $icon.removeClass('fa-check').addClass('fa-pencil-alt').closest('a').removeClass('blue darken-2 white-text btn').addClass('btn-flat');
            if (eid == 0) {
                let elmtHtml;
                if (preComment.length) {
                    elmtHtml = /*html*/`
                        <a
                            class="waves-effect waves-light btn-flat tooltipped element-data" data-delay="50"
                            data-position="top" data-tooltip="Guidelines : ${preComment}"
                            ><i class="fa fa-flag"></i></a>
                    `;
                } else {
                    elmtHtml = /*html*/`
                        <a
                            class="waves-effect waves-light btn-flat tooltipped element-data" data-delay="50"
                            data-position="top" data-tooltip="{% trans %}participants.guidelines.no_guidelines{% endtrans %}"
                            ><i class="far fa-flag"></i></a>
                    `;
                }
                const $elmtData = $(elmtHtml);
                $curRow.find('.element-precomment').prepend($elmtData);
                $elmtData.tooltip();
                $curRow.find('.fa-flag').closest('li').show();
                $curRow.find('.fa-times').closest('a').removeClass('remove-element').addClass('modal-trigger').attr('data-eid', data.eid).attr('href','#deleteParticipant');
            } else {
                $relatedElementTriggerBtn = $curRow.find('.element-precomment > a');

                if (preComment != '') {
                    $relatedElementTriggerBtn.find('i').removeClass('far fa-flag').addClass('fa fa-flag');
                    $relatedElementTriggerBtn.attr('data-tooltip', 'Guidelines :'+preComment);
                } else {
                    $relatedElementTriggerBtn.find('i').removeClass('fa fa-flag').addClass('far fa-flag');
                    $relatedElementTriggerBtn.attr('data-tooltip', precommentNoneMsg);
                }
                $relatedElementTriggerBtn.tooltip();
            }
        })
        .fail(function(data) {
            $inputName.after(/*html*/`
                <div class="red-text">
                    <strong>${data.responseJSON.errorMsg}</strong>
                </div>
            `);
        });
    })

    // Defining data attributes to delete/precomment btn to trigger events
    $(document).on('click','[href="#deleteParticipant"]', function() {
        $('.delete-button').data('eid',$(this).attr('data-eid'));
    });

    $(document).on('click','[href*="#precomment"]', function() {
        $('.modal.open').find('.validate-precomment').data('elmtName',$(this).attr('href'));
    });

    // Changing color of precomment in case user inserts guidelines
    $(document).on('click', '.validate-precomment', function() {
        var $relatedCommentModalTriggBtnContent = $('[href="'+$(this).data('elmtName')+'"] i');
        if ($(this).closest('.modal').find('textarea').val() != '' && $relatedCommentModalTriggBtnContent.hasClass('far')) {
            $relatedCommentModalTriggBtnContent.removeClass('far fa-flag').addClass('fa fa-flag').closest('a').addClass('lime darken-4');
            $relatedCommentModalTriggBtnContent.closest('a').attr('data-tooltip', precommentUpdateMsg);
        } else if ($(this).closest('.modal').find('textarea').val() == '' && $relatedCommentModalTriggBtnContent.hasClass('fa')) {
            $relatedCommentModalTriggBtnContent.removeClass('fa fa-flag').addClass('far fa-flag').closest('a').removeClass('lime darken-4');
            $relatedCommentModalTriggBtnContent.closest('a').attr('data-tooltip', precommentCreationMsg);
        }
        $relatedCommentModalTriggBtnContent.closest('a').tooltip();
    })

    // Checking that a single leader is existing per stage
    $(document).on('change', 'input[type="checkbox"]', function() {
        if ($(this).val() == 1) {
            participantName = $(this).closest('.row').find('.participant-name select option:selected').text();
            $(this).closest('ul.stage-participants').find('input[type="checkbox"]').not($(this)).each(function(key, checkbox) {
                if ($(checkbox).is(':checked')) {
                    var stageIndex = $('ul.stage-participants').index($(this).closest('ul.stage-participants'));
                    var newLeaderIndex = $(this).closest('ul.stage-participants').find('.stage-participant').index($(this).closest('.stage-participant'));
                    var oldLeaderIndex = $(this).closest('ul.stage-participants').find('.stage-participant').index($(checkbox).closest('.stage-participant'));
                    $('.change-leader-button')
                        .data('stageIndex', stageIndex)
                        .data('newLeaderIndex', newLeaderIndex)
                        .data('oldLeaderIndex', oldLeaderIndex);
                    $('#changeLeader').modal('open').find('.sName').empty().append($(this).closest('.stage').find('.stage-name').text())
                    $('#changeLeader').find('#oldLeader').empty().append($(checkbox).closest('.row').find('.participant-name select option:selected').text())
                    $('#changeLeader').find('#newLeader').empty().append(participantName);
                }
            })
        }
    })

    $('.change-leader-button').on('click', function() {
        var $oldParticipantElmt = $('ul.stage-participants').eq($(this).data('stageIndex')).find('.stage-participant').eq($(this).data('oldLeaderIndex'));
        var $newParticipantElmt = $('ul.stage-participants').eq($(this).data('stageIndex')).find('.stage-participant').eq($(this).data('newLeaderIndex'));

        $oldParticipantElmt.find('input[type="checkbox"]').prop('checked', false);
        $oldParticipantElmt.find('.fa-star').parent().remove();
        $newParticipantElmt.find('input[type="checkbox"]').prop('checked', true);

    })

    $(document).on('change', '.participant-name select', function() {
        $(this).closest('.row').find('.participant-fullname').empty().append($(this).find('option:selected').text());
    })


    $(document).on('click', '.remove-element, [class*="insert-participant-btn"]', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // Gets the collection of participants (lying before insert btn)
        $collectionHolder = !$(this).hasClass('remove-element') ? $(this).parent().prev() : $(this).closest('ul.stage-participants');

        var total = $collectionHolder.data('total');
        var selectedIndex = !$(this).hasClass('remove-element') ? $collectionHolder.children().length : $collectionHolder.children().index($(this).closest('li'))+1;

        if ($(this).hasClass('remove-element')) {
            removedParticipantVal = $(this).closest('.row').find('.participant-name select').val();
            $(this).closest('.stage-participant').remove();
            $existingParticipantSelects = $collectionHolder.find('.participant-name select');

            $.each($existingParticipantSelects, function(key, selectElmt) {
                $(selectElmt).find('option[value="'+ removedParticipantVal +'"]').prop('disabled', false);
            })
            stylizeSelect();

        } else if (!$(this).hasClass('.remove-element')) {

            $pType = $(this).attr('class').split(' ').slice(-1)[0].split('-').slice(-1)[0];
 
            addParticipantForm($collectionHolder, $pType);
            stylizeSelect();
        }
    });


    function addParticipantForm($collectionHolder, $pType) {
        // Get the data-prototype


        /** @type {string} */
        const prototype = $collectionHolder.data($pType + 'prototype');
        const stageIndex = $('ul.stage-participants').index($collectionHolder);
        const participantIndex = $collectionHolder.find('.stage-participant').length;
        const $collectionHolderChildren = $collectionHolder.children();
        /*const nextIndex = !$collectionHolderChildren.length ? 0 : (
            +$collectionHolderChildren.last()
            .find('select').first().attr('id')
            .match(/uniqueParticipations_(\d+)_directUser/)[1]
        ) + 1;*/
        const newForm = prototype.replace(/__name__/g, participantIndex)
            .replace(/__stgNb__/g, stageIndex + 1);

        // Display the form in the page in an li, before the "Add a user" link li
        const $newFormLi = $('<li class="stage-participant"></li>').append(newForm);
        $newFormLi.find('.modal').modal();
        $newFormLi.find('.tooltipped').tooltip();

        const $stylizableSelects = $newFormLi.find('.input-field select');
        $stylizableSelects.find('option').each(function(_i, e) {
            e.innerHTML = e.innerHTML.trim()
        });
        $stylizableSelects.material_select();

        const $removableElmts = [];

        $existingParticipantSelects = $collectionHolder.find('.participant-name select');
        $participantHiddenSelect = $newFormLi.find('.participant-name select');

        // Retrieving all previous values of selected participants
        $.each($existingParticipantSelects, function() {$removableElmts.push($(this).val())});

        // Disable all previously inserted participant in new participant select
        $.each($removableElmts, function(key, value) {
            $participantHiddenSelect.find('option[value="'+value+'"]').prop('disabled', true);
        })

        selectedNewParticipantVal = $participantHiddenSelect.find('option:not(:disabled)').eq(0).val();

        // Disable new selected participant in all existing participant selects

        $.each($existingParticipantSelects, function(key, existingParticipantSelect) {
            $(existingParticipantSelect).find('option[value="'+selectedNewParticipantVal+'"]').prop('disabled', true);
        })

        // Change precomment modal trigger href & id
        urlToPieces = $newFormLi.find('[href*=precomment]').attr('href').split('_');
        urlToPieces[urlToPieces.length - 2] = stageIndex;
        urlToPieces[urlToPieces.length - 1] = participantIndex;
        $newFormLi.find('[href*=precomment]').attr('href', urlToPieces.join('_'));
        $newFormLi.find('[id*=precomment]').attr('id', urlToPieces.join('_').slice(1));

        // Inserting participant name in modal guidelines title
        $newFormLi.find('.participant-fullname').append($participantHiddenSelect.find('option:selected').text());

        $participantHiddenSelect.val(selectedNewParticipantVal);
        $collectionHolder.append($newFormLi);

    }

    const $theForm = $('form[name="manage_stage_participants_form"]');

    $theForm
    .on('click', '.next-button', function() {
        $theForm.data('next', true);
    })
    .on('submit', function(e) {
        const actionIsNext = $theForm.data('next');
        $theForm.data('next', false);

        if (actionIsNext) {
            const stageParticipantsLists = $('.stage-participants').get();

            if(elmt != 'template'){
                for (const e of stageParticipantsLists) {
                    const $e = $(e);

                    if ($e.find('[name*="leader"]:checked').length == 0 && $e.find('.stage-participant').length != 0) {
                        $('#missingLeader')
                        .modal('open')
                        .find('.sName').html(
                            $e.closest('.stage').find('.stage-name').text()
                        );
                        return false;
                    }
                }
            }

            // force confirming participants
            const $unconfirmedElems = $('[name="manage_stage_participants_form"] .fa.fa-check');
            if ($unconfirmedElems.length) {
                setTimeout(() => alert('Il reste des participations non confirmées. Vous devez au préalable les confirmer (ou les retirer).'));
                return false;
            }
        }
    });


    $('.delete-button').on('click', function(e) {
        e.preventDefault();
        eid = $(this).data('eid');
        sid = $('[data-eid="'+eid+'"]').closest('.stage-participants').data('sid');
        urlToPieces = window.location.pathname.split('/');
        //lastUrlElement = urlToPieces[urlToPieces.length - 1].slice(0,-1);
        urlToPieces[urlToPieces.length - 1] = 'stage';
        urlToPieces[urlToPieces.length] = sid;
        urlToPieces.push('participant','delete', eid);
        durl = urlToPieces.join('/');
        $.post(durl)
        .done(function(data) {
            removedParticipantVal = $('[data-eid='+eid+']').closest('.row').find('.participant-name select').val();
            $existingParticipantSelects = $('[data-eid='+eid+']').closest('ul.stage-participants').find('.participant-name select');

            $.each($existingParticipantSelects, function(key, selectElmt) {
                $(selectElmt).find('option[value="'+ removedParticipantVal +'"]').prop('disabled', false);
            })
            stylizeSelect();
            $('[data-eid=' + eid + ']').closest('.stage-participant').remove();
            console.log(data);
            if(!data.finalizable && !$('#activity_element_form_update').prop('disabled')){
                $('#activity_element_form_update').prop('disabled', true);
            }
        })
        .fail(function(data) {
            console.log(data)
        });
    });
});
