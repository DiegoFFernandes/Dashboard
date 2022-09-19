//Initialize Select2 Elements
$(function() {
    $('.select2').select2()
});


// Mascaras input
$(document).ready(function() {
    $("#placa").inputmask({ mask: ['AAA9999', 'AAA9A99'] });
    $("#ano").inputmask("9999");
    $("#peso").inputmask("99,99");
    $("#cpf_cnpj").inputmask({ mask: ['999.999.999-99', '99.999.999/9999-99'] });
    $(".alert-geral").fadeTo(2000, 500).slideUp(500, function() {
        $(".alert-geral").slideUp(500);
    });

    var inicioData = 0;
    var fimData = 0;
    $('#daterange').daterangepicker({
        autoUpdateInput: false,
    });
    $('#daterange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
            'DD/MM/YYYY'));
        inicioData = picker.startDate.format('MM/DD/YYYY');
        fimData = picker.endDate.format('MM/DD/YYYY');
    });
    $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val("");
        inicioData = 0;
        fimData = 0;
    });

});

function msg(msg, classe, icon) {
    setTimeout(function() {
        $("#alert-msg").removeClass('hidden alert-success alert-warning').addClass(classe);
        $("#alert-msg p").text(msg);
        // $(".fa-msg").addClass(icon);
        // $(".alert p").text(msg);
    }, 400);

    window.setTimeout(function() {
        $("#alert-msg").addClass('hidden');
    }, 4000);
}