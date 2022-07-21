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

});

function msg(msg, classe, icon) {
    setTimeout(function() {
        $("#alert-msg").removeClass('hidden alert-success alert-warning').addClass(classe);
        $("#alert-msg p").text(msg + " ").append('<i class="fa-msg"></i>');
        $(".fa-msg").addClass(icon);
        // $(".alert p").text(msg);
    }, 400);

    window.setTimeout(function() {
        $("#alert-msg").addClass('hidden');
    }, 4000);
}