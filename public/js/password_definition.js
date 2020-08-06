$(function() {
    $('.modal').modal();

    $('#definePwdSuccess').modal({
        dismissible: true,
        complete: function() {
            window.location = homeUrl;
        }
    });

    $('.fa-eye').on('click', function() {
        var pwdElmt = $(this).closest('.password').find('input');

        if (pwdElmt.attr('type') == 'password')
            pwdElmt.attr('type', 'text');
        else
            pwdElmt.attr('type', 'password');
    })

    $('input').on('cut copy paste selectstart drag drop', e => e.preventDefault());

    $('form').submit(function(e) {
        e.preventDefault();
        $('.red-text').empty();
        $.post(url, $(this).serialize())
        .done(function() {
            $('#definePwdSuccess').modal('open');
            setTimeout(() => window.location = homeUrl, 2000);
            return true;
        })
        .fail(function(_data) {
            const data = _data.responseJSON;
            /**
             * @type {{}}
             */
            const errorMessages = data.password;
            for (const field in errorMessages) {
                if (!errorMessages.hasOwnProperty(field)) continue;
                $(`[name$="[password][${field}]"]`).closest('li').children('.errors').html(
                    errorMessages[field]
                );
            }
        });
        return false;
    });

});