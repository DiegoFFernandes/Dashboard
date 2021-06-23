//$('#meuModal').modal('toggle')

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
                'id_marca': idMarca,
            },
            success: function (data) {
                $("#id_modelo").html(data);
            }
        });
    });
});

$(document).ready(function () {
    $("#placa").inputmask({ mask: ['AAA9999', 'AAA9A99'] });
    $("#ano").inputmask("9999");
    $("#cor").inputmask("AAAAAAAAAAAA");
});

// Marca Veiculo
// Deletar Marca veiculo
$('#deleteMarcaVeiculo').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var recipientId = button.data('id');
    var modal = $(this);
    modal.find('#cd_delete_marca').val(recipientId);
})
// Fim Deletar

//Editar Marca Veiculo
$(document).ready(function () {
    var table = $('#mveiculodatatable').DataTable();

    table.on('click', '.edit', function () {
        $tr = $(this).closest('tr');
        if ($($tr).hasClass('child')) {
            $tr = $tr.prev('.parent');
        }
        var data = table.row($tr).data();
        console.log(data);

        $('#id').val(data[0]);
        $('#edit_marca').val(data[1]);
        $('#dsmarca').val(data[2])

        var $frotaveiculos = $("<option selected='selected'></option>").val(data[3]).text(data[4])
        $("#frotaveiculos").append($frotaveiculos).trigger('change');

    })
})

//Fim Editar

//Fim Marca Veiculo

// $(document).ready(function () {
//     var button = document.getElementById("btn-hide");

//     button.addEventListener("click", fnToggle);

//     function fnToggle() {
//         var mostrar = document.getElementById("lote-pcp");
//         mostrar.classList.toggle("hidden");

//         if (mostrar) {
//             button.innerHTML = "Fechar Lotes"
//         } else {
//             button.innerHTML = "Abrir Lotes"
//         };
//     }
// })

