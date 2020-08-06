let elementId;
let elementArray = [];
let elementIdArray = [];

$('[href="#institutionProcesses"]').on('click',function(){
    $('#processSelect').empty();
    oid = $(this).data('oid');
    urlToPieces = ipurl.split('/');
    urlToPieces[urlToPieces.length - 1] = oid;
    url = urlToPieces.join('/');
    $.post(url)
        .done(function(data){
            $.each($('.red-text'),function(){
                $(this).remove();
            });
            $.each(data.processes,function(key, process){
                $('#processSelect').append($('<option value="'+process.key+'">'+process.value+'</option>'));
            })
            console.log(data);
        })
        .fail(function(data){
            console.log(data);
        });
    $('#institutionProcesses').find('.process-select').data('oid',$(this).data('oid'));
});

$('.process-select').on('click',function(e){
    e.preventDefault();
    pid = $('#processSelect option:selected').val();
    urlToPieces = aurl.split('/');
    urlToPieces[urlToPieces.length - 1] = pid;
    params = {};
    params['fi'] = 0;
    params['n'] = $('#activity_name').val();
    url = urlToPieces.join('/');
    $.post(url, params)
        .done(function(data){
            $('#institutionProcesses').modal('close');
            $('#addUserProcessActivitySuccess').modal('open');
            console.log(data);
        })
        .fail(function(data){
            $('#institutionProcesses').find('input[type="text"]').after('<div class="red-text"><strong>' + data.responseJSON + '</strong></div>');
            console.log(data)
        });
});

// If user clicks in "Add activity" from one organization, we pass the org id in the modal request button
$('[href="#institutionProcesses"]').on('click',function(){
    $('#requestNewProcess .btn-large').data('oid',$(this).data('oid'));
})

$('.process-request').on('click',function(e){
    e.preventDefault();
    urlToPieces = cprurl.split('/');
    urlToPieces[urlToPieces.length - 1] = $(this).data('oid');
    url = urlToPieces.join('/');
    $.post(url,$('#requestNewProcess form').serialize())
        .done(function(data){
            $('#addUserProcessActivitySuccess').modal('open');
            console.log(data);
        })
        .fail(function(data){
            console.log(data)
        });
});