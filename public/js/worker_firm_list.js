$(function(){

    $('[name="countrySelector"]').on('change',function(){
        var $this = $(this);
        eraseCookie('wf_c');
        if($this.val()){
            setCookie('wf_c',$this.val(),365);
        }
        location.reload();
    });
    $('[name="stateSelector"]').on('change',function(){
        var $this = $(this);
        eraseCookie('wf_s');
        if($this.val()){
            setCookie('wf_s',$this.val(),365);
        }
        location.reload();
    });
    $('[name="citySelector"]').on('change',function(){
        var $this = $(this);
        eraseCookie('wf_cit');
        if($this.val()){
            setCookie('wf_cit',$this.val(),365);
        }
        location.reload();
    });

    $('[name="nbResultsSelector"]').on('change',function(){
        var $this = $(this);
        eraseCookie('wf_nb');
        setCookie('wf_nb',$this.val(),365);
        eraseCookie('wf_cp');
        setCookie('wf_cp',1,365);
        location.reload();
    });

    $('.prev-page, .next-page').on('click',function(){
        var $this = $(this);
        eraseCookie('wf_cp');
        setCookie('wf_cp',$this.hasClass('prev-page') ? parseInt($('.curr-page').text()) - 1 : parseInt($('.curr-page').text()) + 1, 365);
        location.reload();
    });

    $('.first-page').on('click',function(){
        eraseCookie('wf_cp');
        setCookie('wf_cp',1,365);
        location.reload();
    });

    $('.last-page').on('click',function(){
        eraseCookie('wf_cp');
        setCookie('wf_cp',parseInt($('.last-page').text()),365);
        location.reload();
    });




   
});