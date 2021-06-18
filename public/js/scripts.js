//$('#meuModal').modal('toggle')

// var button = document.getElementById("btn-hide");


$(document).ready(function () {
    $('table.display').DataTable({
        "language": {
            "emptyTable": "Nenhum registro encontrado",
            "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 até 0 de 0 registros",
            "infoFiltered": "(Filtrados de _MAX_ registros)",
            "infoThousands": ".",
            "loadingRecords": "Carregando...",
            "processing": "Processando...",
            "zeroRecords": "Nenhum registro encontrado",
            "search": "Pesquisar",
            "paginate": {
                "next": "Próximo",
                "previous": "Anterior",
                "first": "Primeiro",
                "last": "Último"
            },
        },
        "scrollX": true,
        "pageLength": 50,
        "columnDefs": [
            {
                "targets": [7],
                "visible": false,
            }
        ]
    });
});

$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()
});

$(document).ready(function () {
    $('#id_frotaveiculo').change(function () {
        const url = $('#MotoristaForm').attr("data-marcas-url");
        const idFrota = $(this).val();       
        $.ajax({
            url: url,
            data: {
                'id_frotaveiculo': idFrota,
            },
            success: function (data) {
                $("#id_marca").html(data);
            }
        });
    });

    $('#id_marca').change(function () {
        const url = $('#MotoristaForm').attr("data-modelos-url");
        const idMarca = $(this).val();    
        const idFrota = $('#id_frotaveiculo').val();  
           
        $.ajax({
            url: url,
            data: {
                'id_frotaveiculo': idFrota,
                'id_marca' : idMarca,
            },
            success: function (data) {
                $("#id_modelo").html(data);
            }
        });
    });
});

$(document).ready(function(){    
    $("#placa").inputmask({mask: ['AAA9999','AAA9A99']});
    $("#ano").inputmask("9999");
    $("#cor").inputmask("AAAAAAAAAAAA");
});

// var button = document.getElementById("btn-hide");

// button.addEventListener("click", fnToggle);

// function fnToggle() {
//     var mostrar = document.getElementById("lote-pcp");
//     mostrar.classList.toggle("hidden");

//     if (mostrar) {
//         button.innerHTML = "Fechar Lotes"
//     } else {
//         button.innerHTML = "Abrir Lotes"
//     };
// }

