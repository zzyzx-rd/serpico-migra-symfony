

$(function(){

    $('.modal').modal();

    $('input[type="checkbox"]').on('change',function(){
        if($(this).is(':checked')){
            $(this).closest('.collapsible-header').removeClass('lighten-5').addClass('blue darken-4 white-text');
        } else {
            $(this).closest('.collapsible-header').removeClass('blue darken-4 white-text').addClass('lighten-5');
        }
    });

    $('.collapsible-header').on('click',function(){
        if(!$(this).find('input[type="checkbox"]').is(':checked')){
            $(this).find('input[type="checkbox"]').prop('checked',true);
            $(this).removeClass('lighten-5').addClass('blue darken-4 white-text');
        } else {
            $(this).find('input[type="checkbox"]').prop('checked',false);
            $(this).removeClass('blue darken-4 white-text').addClass('lighten-5');
        }
    });


    $('label').each(function(){
        if($(this).text().indexOf('(T)')> 0){
            $(this).prev().attr('checked',true);
        }
    })

    $('.submit-btn').on('click',function(e){
        e.preventDefault();
        $.post(window.location.pathname,$('form').serialize())
            .done(function(data){
                if(data.hasOwnProperty("incompleteTeam")){
                    $('#incompleteTeam').modal('open');
                } else if(data.hasOwnProperty("missingName")) {
                    $('#missingName').modal('open');
                } else if(data.hasOwnProperty("duplicateTeamName")) {
                    $('#duplicateTeamName').modal('open');
                } else {
                    urlToPieces = window.location.pathname.split('/');
                    urlToPieces[urlToPieces.length - 2] = 'users';
                    window.location = urlToPieces.slice(0,urlToPieces.length-1).join('/');
                }
            })
            .fail(function(data){
                console.log(data)
            })
    })

    $('.update-btn').on('click',function(e){
        e.preventDefault();

        $.post(uurl,$('form').serialize())
            .done(function(data){
                if(data.hasOwnProperty("incompleteTeam")){
                    $('#incompleteTeam').modal('open');
                } else if(data.hasOwnProperty("missingName")) {
                    $('#missingName').modal('open');
                } else {
                    urlToPieces = window.location.pathname.split('/');
                    urlToPieces[urlToPieces.length - 2] = 'users';
                    window.location = urlToPieces.slice(0,urlToPieces.length-1).join('/');
                }
            })
            .fail(function(data){
                console.log(data)
            })
    })

    $('.delete-btn').on('click',function(e){
        e.preventDefault();
        $.post(deleteTeamUrl,null)
            .done(function(data){
                window.location = manageUsersUrl;
            })
            .fail(function(data){
                console.log(data)
            })
    })

    handleTUSelectElems();

    $(document).on(
        'click', '.btn-add-user-i, .btn-add-user-e',
        function() {
          const $this = $(this);
          const type = $this.hasClass('btn-add-user-i') ? 'i' : 'e';
          const $section = $('section');
          const $teamUsersList = $section.children('ul.teamusers-list');
      
          if ($teamUsersList.children('.new').length) {
            return;
          }
      
          if($teamUsersList.find(`.teamusers-list--item[type="${type}"]`).length && $teamUsersList.find(`.teamusers-list--item[mode="${type}"]`).find('select[name*="user"] option:not(:disabled)').length == $teamUsersList.children().length){
            $('#noRemainingParticipant').modal('open');
            return false;
          };
      
          /** @type {HTMLTemplateElement} */
          const proto = $(`template.teamusers-list--item__proto-${type}`)[0];
          protoHtml = proto.innerHTML.trim();
      
          protoHtml = type == 'i' ? 
            protoHtml.replace(/__intTUIndex__/g, $teamUsersList.find(`.teamusers-list--item[mode="${type}"]`).length) :
            protoHtml.replace(/__extTUIndex__/g, $teamUsersList.find(`.teamusers-list--item[mode="${type}"]`).length);
            
          $newProtoHtml = $(protoHtml);
          $newProtoHtml.attr('type',type);
          $teamUsersList.append($newProtoHtml);
          handleTUSelectElems($newProtoHtml);

        }
    ).on(
        'click', '.edit-user-btn',
        function() {
          const $this = $(this);
          const $participantItem = $this.closest('.teamusers-list--item');
          $participantItem.addClass('edit-mode');
        }
    ).on(
    'click', '.edit-mode .edit-user-btn', // edit button, only when in edit mode (check icon is shown)
    async function () {
        const $this = $(this);
        const $teamUserItem = $this.closest('.teamusers-list--item');
        /** @type {JQuery<HTMLSelectElement>} */
        const $userSelect = $teamUserItem.find('select.user-select');
        const $userName = $teamUserItem.find('.user-name');
        /** @type {JQuery<HTMLInputElement>} */
        const $userIsOwner = $teamUserItem.find('.user-is-owner');
        const userIsOwner = $userIsOwner.length > 0 ? $userIsOwner[0].checked : null;
    
        if(!$('.add-owner-lose-setup,.change-owner-button').hasClass('clicked')){   
        // Change ownership management
        $potentialDifferentLeader = $teamUserItem.closest('.participants-list').find('.badge-participation-l:visible');
        
        if(userIsOwner == true){
    
            if($.inArray(+usrRole,[2,3]) !== -1 
            && !$potentialDifferentLeader.length && $userSelect.val() != usrId
            || $potentialDifferentLeader.length && $potentialDifferentLeader.closest('.participants-list--item').find('select[name*="user"]').val() == usrId){
            $('#setOwnershipLoseSetup').modal('open').data('id',$this.closest('.stage').data('id'));
            return false;
            } else if($potentialDifferentLeader.length){
            $('#changeOwner').find('.sName').empty().append($this.closest('.stage').find('.stage-name-field').text())
            $('#changeOwner').find('#oldLeader').empty().append($potentialDifferentLeader.closest('.participants-list--item').find('select[name*="user"] option:selected').text())
            $('#changeOwner').find('#newLeader').empty().append($userName.text());
            $('#changeOwner').modal('open').data('id',$this.closest('.stage').data('id'));
            return false;
            }
    
        }
        }
    
        const url = validateTeamUserUrl
        .replace('__teaId__', $('section').attr('data-id'))
        .replace('__tusId__', $teamUserItem.data('id') || 0)
    
        $userName.html(
        $userSelect.children(':checked').first().html()
        );
    
        const params = {
        user: $userSelect[0].value,
        };
    
        if (userIsOwner) {
        params.leader = true;
        }
    
        if(!$this.hasClass('warned') && $teamUserItem.closest('.participants-list').find('.badge-participation-validated').length){
        
        if(userParticipantType != 0 && ($teamUserItem.hasClass('new') || $userParticipantType.find('option[selected="selected"]').val() == 0)){
            
            $('#unvalidatingOutput').modal('open');
            $('.unvalidate-btn').addClass('p-validate').removeClass('c-validate');
            $('.unvalidate-btn').removeData()
                .data('pid',$teamUserItem.closest('.participants-list--item').data('id'))
            
            $(document).on('click','.p-validate',function(){
                $clickingBtn = $(this).data('pid') ? 
                $(`.participants-list--item[data-id="${$(this).data('pid')}"]`).find('.edit-user-btn') :
                $('.participants-list--item.new').find('.edit-user-btn');
                $clickingBtn.addClass('warned').click();
            })
            return false;
        }
        }
    
        const { tid, eid, user, canSetup } = await $.post(url, params);
    
        if($this.hasClass('warned')){
        $teamUserItem.closest('.participants-list').find('.badge-participation-validated').attr('style','display:none;');
        $this.removeClass('warned');
        }
    
        if(!canSetup){
            window.location = $('.back-btn').attr('href');
        }
    
        $('section').attr('data-id', tid);

        $teamUserItem
        .removeClass('edit-mode new')
        .attr('data-id', eid)
        .attr('is-leader', userIsOwner)
        .find('img.user-picture').prop('src', `/lib/img/${user.picture}`);
        
        $tuElmt = $teamUserItem;
        //$partElmt = $(this).closest('.participants-list--item');
        //if(!$partElmt.hasClass('edit-mode')){
        if($tuElmt.find('.remove-team-user-btn').length){
            $tuElmt.find('.remove-team-user-btn').removeClass('remove-team-user-btn').addClass('modal-trigger').attr('href','#deleteTeamUser');
        }
        $badges = $tuElmt.find('.badges');
        $badges.children().attr('style','display:none;');
        
        if($tuElmt.find('select[name*="teamExtUsers"]').length){
            $badges.find('.badge-team-user-e').removeAttr('style');
        }
        if($tuElmt.find('input[name*="leader"]').is(':checked')){
            $badges.find('.badge-team-user-l').removeAttr('style');
        }
        handleTUSelectElems($tuElmt);   
    })

/**
 * Disables options in team user selects as appropriate
 * @param {JQuery|HTMLElement} [target]
 */
function handleTUSelectElems (target) {
    const isCName = (_i, e) => /_user/gi.test(e.id);
    const $TUElems = $('.teamusers-list');
    const $selects = (target == null) ? $TUElems.find('select').filter(isCName) : target.find('select').filter(isCName);
    const $extSelects = (target == null) ? [$TUElems.find('select[name*="ExtUsers"]')] : [target.find('select[name*="ExtUsers"]')];
    $selects.find('option:not(.synth)').prop('disabled', false);
    //$selects.find('option.synth').prop('disabled', true);
  
    for (const TUElem of $TUElems) {
      $TUElem = $(TUElem);
      const $options =  $TUElem.find('select').filter(isCName).find('option');
      const inUse = $options.filter(':selected').get().map(e => e.value);
      $optionsToDisable = $options.filter((_i, e) => inUse.includes(e.value) && !e.selected)
      $optionsToDisable.each((_i ,e) => $(e).prop('disabled', true));
      if(target && target.hasClass('new')){
        $targetTUSelect = target.find('select').filter(isCName);
        potentialDuplicate = inUse.reduce((acc, v, i, arr) => arr.indexOf(v) !== i && acc.indexOf(v) === -1 ? acc.concat(v) : acc, [])
        if(potentialDuplicate.length){
          $targetTUSelect.find(`option[value="${potentialDuplicate[0]}"]`).prop('disabled',true);
          $targetTUSelect.find('option').each(function(i,e){
            if(!inUse.includes($(e).val())){
              $targetTUSelect.val($(e).val());
              return false;
            }
          })
        }
        $targetTUSelect.val($targetTUSelect.find('option:not(:disabled)').eq(0));
      }
    }
    
    for(const $extSelect of $extSelects){
        newIndex = -1;
        $extSelect.find('option.synth').each(function(i,e){
            $option = $(e);
            $(e).attr('value',""); 
            $options = $option.closest('select').find('option');  
            index = $options.index($option);
            $(e).remove();
            newIndex == -1 ? $extSelect.find('option').eq(0).before($option) : $extSelect.find('option').eq(newIndex).after($option);
            newIndex = index;
            $option.prop('disabled',true);
        })

        $extSelect.find('option:not(.synth)').each(function(i,e){
            content = $(e).text();
            $(e).empty().append(`&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;${content}`);
        })
    }

    $TUElems.find('select').material_select();

    for(const $extSelect of $extSelects){

        $extSelect.parent().find('.select-dropdown>li').each(function(i,e){
            if($(e).parent().next().find('option').eq(i).hasClass('synth')){
                $(e).find('span').css({'color':'slategrey','font-style':'italic'});
            }
        })
    }
  }


    $(document).on('click','.team-name:not(.edit) > .btn-edit',function(){
        $('.team-name').addClass('edit');
      });
      
      $(document).on('click','.team-name.edit > .btn-edit',function(){
        const name = $('.custom-input').text().trim();
        const params = {name: name};
        if(name != $('.team-name input').attr('value')){
          $.post(vnurl,params)
            .fail(function(data) {
              $('#duplicateElementName').modal('open');
            })
            .done(function(data){
              $('.team-name input').attr('value',name);
              $('.team-name .show').empty().append(name);
              $('.team-name').removeClass('edit');
              if(data.elmtId){
                  $('section').attr('data-id',data.elmtId);
              }
            });
        } else {
            $('.team-name').removeClass('edit');
        }
      });
    
    $(document).on('click','[href="#deleteTeamUser"]',function(e){
        const $this = $(this);
        const $teamUserItem = $this.closest('.teamusers-list--item');
        const $modalDeletionBtn = $('#deleteTeamUser .remove-team-user-btn');
        $modalDeletionBtn.data('id',$teamUserItem.data('id'));
        $(document).on('click','.remove-team-user-btn',function(e){
            e.preventDefault();
            urlToPieces = deleteTeamUserUrl.split('/');
            urlToPieces[urlToPieces.length - 1] = $(this).data('id');
            url = urlToPieces.join('/');
            $.post(url,null)
                .done(function(data){
                    $teamUserItem.remove();
                })
                .fail(function(data){
                    console.log(data);
                })
        })
    });




});







