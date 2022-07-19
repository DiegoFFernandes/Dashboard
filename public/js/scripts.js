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