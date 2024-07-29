//Initialize Select2 Elements
$(function() {
    $('.select2').select2({
        placeholder: "Selecione",
        allowClear: true
    })
    $('.empresas').select2();
    $('.etapas').select2();
});



// Mascaras input
$(document).ready(function() {
    $("#placa").inputmask({ mask: ['AAA9999', 'AAA9A99'] });
    $("#ano").inputmask("9999");
    $("#peso").inputmask("99,99");
    $("#cpf_cnpj").inputmask({ mask: ['999.999.999-99', '99.999.999/9999-99'] });
    $('#phone').inputmask({mask:['(99)9999-9999', '(99)99999-9999']});
    $('#valor').inputmask("99999,99");

    $(".alert-geral").fadeTo(2000, 500).slideUp(500, function() {
        $(".alert-geral").slideUp(500);
    });

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "30000",
        "hideDuration": "1000",
        "timeOut": "3000",
        "extendedTimeOut": "3000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    

});

function msgToastr(msg, classe){

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "30000",
        "hideDuration": "1000",
        "timeOut": "3000",
        "extendedTimeOut": "3000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    toastr[classe](msg);
}

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

function ArrCheck() {
    var arr = new Array();
    $.each($("input[name='status']:checked"), function() {
        arr.push($(this).val());
    });
    console.log(arr);
    return arr;    
}

function ClickSelect(table){    
    var status = ArrCheck();        
        if (status.length === 0) {
            msgToastr('Algum campo de status deve estar preenchido', 'warning');
            event.preventDefault();
        } else {
            $(table).DataTable().clear().destroy();            
            initTable(status);
        }
}

function CheckedClick(){
    $('.minimal').click(function() {
        
    });
}