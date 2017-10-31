$(function () {
    $('.input-group.date').datepicker({
        format: 'yyyy-mm-dd',
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true
    });
    $(".touchspin1").TouchSpin({
        min: 0,
        max: 9999999999,
        buttondown_class: 'btn btn-white',
        buttonup_class: 'btn btn-white'
    });

    $(".touchspin2").TouchSpin({
        min: 0,
        max: 9999999999,
        step: 0.01,
        decimals: 2,
        boostat: 5,
        maxboostedstep: 10,
    });
});