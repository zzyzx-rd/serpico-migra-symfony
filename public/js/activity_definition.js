$(function(){
    $('.datepicker-start').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 5, // Creates a dropdown of 15 years to control year,
        clear: 'Clear',
        //close: 'Ok',
        min: new Date(),
        today:false,
        close:false,
        closeOnSelect: true,
        onClose: function(){

        }
    });

    $('.datepicker-start').on("change",function(){
        var startDate = new Date(Date.parse($('.datepicker-start').val()));
        $('.datepicker-end').pickadate('picker').set('min',$('.datepicker-start').pickadate('picker').get('select'));
        if(Date.parse($('.datepicker-start').val())>Date.parse($('.datepicker-end').val()))
        {
            $('.datepicker-end').val($('.datepicker-start').val());
        }
    });


    $('.datepicker-end').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 5, // Creates a dropdown of 15 years to control year,
        clear: 'Clear',
        today:false,
        close:false,
        //close: 'Ok',
        min: new Date(Date.parse($('.datepicker-start').val())),
        today:false,
        yearend : '31/12/2017',
        closeOnSelect: true // Close upon selecting a date,
    })

    }
);







