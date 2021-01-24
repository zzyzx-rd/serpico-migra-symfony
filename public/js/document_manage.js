$(function(){

    $('[href="#addDocument"]').on('click',function(){

        $('#addDocument').find('.dropify').dropify({
            messages: {
              'default' : msgDocInsert,
              'replace' : msgDocReplace,
              'remove' : msgDocRemove,
              'error': msgDocError,
            },

            tpl: {
                message: '<div class="dropify-message"><p class="flex-center"><i class="fa fa-file icon-element xm-right"></i><span style="text-align:left">{{ default }}</span></p></div>',
            }
          });

        $('.dropify-message')
        $('#addDocumentForm').find('.dropify-message').addClass('flex-center');
        $('#addDocumentForm').find('.dropify-infos-message').addClass('no-margin');

    })

    $(document).on('mouseover','.e-document',function(){
        //if(!$modal.find('.doc-name-validate:visible, input:not(.select-dropdown):visible, textarea:visible').length){
          $(this).find('.doc-actions').css('visibility','');
        //}
    }).on('mouseleave','.e-document',function(){
        $(this).find('.doc-actions').css('visibility','hidden');
    })

    $(document).on('click','.doc-upload-validate',function(e){
        e.preventDefault();
        const $this = $(this);
        const $docHolder = $('.e-documents');
        $docElmt = $($docHolder.data('prototype-existing'));
        const $modal = $('#addDocument');
        const $docFile = $modal.find('.dropify');
        var form = new FormData();
        form.append("file",$docFile[0].files[0]);
        form.append("title",$modal.find('input[type="text"]').val());
        isExisting = false;

        var xhr = new XMLHttpRequest();
        xhr.open("POST",udcurl);
        xhr.onload = function () {
          if (xhr.status === 200) {
              d = JSON.parse(xhr.response);
              console.log('upload success',xhr.responseText);
              $docElmt.find('.e-doc-ext').empty().append(d.type);
              $docElmt.find('.fa-file-download').attr({
                'data-path' : d.path,
                'data-mime' : d.mime,
                'data-title' : d.title,
              });
              if(!isExisting){
                $docElmt.find('.e-doc-title').append($modal.find('input[type="text"]').val());
                $('.documents-number').empty().append($('.e-document').length);
              }
              
              $docElmt.find('.e-doc-size').empty().append(`${Math.round(d.size/1000)} Ko`);
              $docElmt.find('.doc-actions').show();
              $docElmt.attr('data-id',d.did).show();
              $docHolder.append($docElmt);
              $modal.modal('close');
          } else {
              console.log("Error " + xhr.status + " occurred when trying to upload your file.<br \/>");
          }
        };
        xhr.send(form);
    });

    $(document).on('click','[href="#deleteDocument"]',function(){
        $('.e-doc-delete').attr('data-id', $(this).closest('.e-document').data('id'));
    });
    
    $(document).on('click','.e-doc-delete',function(){
    
        urlToPieces = ddocurl.split('/');
        id = $(this).attr('data-id');
        urlToPieces[urlToPieces.length - 1] = id;
        url = urlToPieces.join('/');
        $.delete(url,null)
        .done(function(){
            $(`.e-document[data-id="${id}"]`).remove();
            var nbDocs = parseInt($('.documents-number').text());
            $('.documents-number').empty().append(nbDocs ? `(${$('.documents-number').data('none')})` : nbDocs - 1); 
        })
        
    });

})