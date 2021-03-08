$(function () {

  $('#definePwdSuccess').modal({
    dismissible: true,
    complete: function() {
        window.location = homeUrl;
    }
  });

  $('.fa-eye').on('mousedown', function() {  
    $pwdElmt = $(this).closest('.password').find('input');
    $pwdElmt.attr('type') == 'password' ? $pwdElmt.attr('type', 'text') : '';
  }).on('mouseup',function(){
    $pwdElmt = $(this).closest('.password').find('input');
    $pwdElmt.attr('type') == 'text' ? $pwdElmt.attr('type', 'password') : '';
  })


$('input').on('cut copy paste selectstart drag drop', e => e.preventDefault());

});
