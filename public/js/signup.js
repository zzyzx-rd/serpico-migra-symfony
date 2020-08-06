$(function () {
  $('#definePwdSuccess').modal({
    complete: () => window.location = homeUrl
  });

  $('.fa-eye').on('click', function () {
    const $pwdElmt = $('#sign_up_form_password');
    const revealed = $pwdElmt.attr('type') != 'password';
    $pwdElmt.attr('type', revealed ? 'password' : 'text');
  });
});
