$(function(){

    $(document).on('mouseover','.event-type-zone',function(){
        var $this = $(this);
        $this.find('.event-type-act-btns').show();
      }).on('mouseleave','.event-type-zone',function(){  
        var $this = $(this);
        $this.find('.event-type-act-btns').hide();
      })
    
})