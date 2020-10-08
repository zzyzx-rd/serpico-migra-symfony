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
        location.reload();
    });




   
});