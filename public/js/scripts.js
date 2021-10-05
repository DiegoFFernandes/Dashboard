$(document).ready(function() {
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
        "columnDefs": [{
            "targets": [7],
            "visible": false,
        }]
    });
});

//Initialize Select2 Elements
$(function() {
    $('.select2').select2()
});

// $(document).ready(function () {
//     $('#id_frotaveiculo').change(function () {
//         const url = $('#MotoristaForm').attr("data-marcas-url");
//         const idFrota = $(this).val();
//         $.ajax({
//             url: url,
//             data: {
//                 'id_frotaveiculo': idFrota,
//             },
//             success: function (data) {
//                 $("#id_marca").html(data);
//             }
//         });
//     });

//     $('#id_marca').change(function () {
//         const url = $('#MotoristaForm').attr("data-modelos-url");
//         const idMarca = $(this).val();
//         const idFrota = $('#id_frotaveiculo').val();

//         $.ajax({
//             url: url,
//             data: {
//                 'id_frotaveiculo': idFrota,
//                 'id_marca': idMarca,
//             },
//             success: function (data) {
//                 $("#id_modelo").html(data);
//             }
//         });
//     });
// });

// Mascaras input
$(document).ready(function() {
    $("#placa").inputmask({ mask: ['AAA9999', 'AAA9A99'] });
    $("#ano").inputmask("9999");

});
//Fim Mascara Input

// Marca Veiculo
// Deletar *Marca veiculo
// $('#delete').on('show.bs.modal', function(event) {
//         var button = $(event.relatedTarget);
//         var id = button.data('id');
//         console.log(id);
//         var modal = $(this);
//         modal.find('#cd_delete').val(id);
//     })
// Fim Deletar
// Editar *Marca Veiculo
// $(document).ready(function() {
//         var table = $('#mveiculodatatable').DataTable();

//         table.on('click', '.edit', function() {
//             $tr = $(this).closest('tr');
//             if ($($tr).hasClass('child')) {
//                 $tr = $tr.prev('.parent');
//             }
//             var data = table.row($tr).data();
//             console.log(data);
//             $('#id').val(data[0]);
//             $('#descricao').val(data[1]);

//             // var $frotaveiculos = $("<option selected='selected'></option>").val(data[3]).text(data[4])
//             // $("#frotaveiculos").append($frotaveiculos).trigger('change');
//         })
//     })
// Fim Editar
// Fim Marca Veiculo

// // Editar *Marca Modelo Veiculo
// $(document).ready(function() {
//     $('#marcamodelodatatable').DataTable({
//         responsive: {
//             details: {
//                 display: $.fn.dataTable.Responsive.display.modal({
//                     header: function(row) {
//                         var data = row.data();
//                         return 'Details for ' + data[0] + ' ' + data[1];
//                     }
//                 }),
//                 renderer: $.fn.dataTable.Responsive.renderer.tableAll({
//                     tableClass: 'table'
//                 })
//             }
//         }
//     });
// });
// // Fim Editar