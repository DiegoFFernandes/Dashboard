
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

//Initialize Select2 Elements
$(function () {
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
$(document).ready(function () {
    $("#placa").inputmask({ mask: ['AAA9999', 'AAA9A99'] });
    $("#ano").inputmask("9999");
    //$("#cor").inputmask("AAAAAAAAAAAA");
});
//Fim Mascara Input

// Marca Veiculo
// Deletar *Marca veiculo
$('#delete').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    console.log(id);
    var modal = $(this);
    modal.find('#cd_delete').val(id);
})
// Fim Deletar
// Editar *Marca Veiculo
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
        $('#descricao').val(data[1]);

        // var $frotaveiculos = $("<option selected='selected'></option>").val(data[3]).text(data[4])
        // $("#frotaveiculos").append($frotaveiculos).trigger('change');
    })
})
// Fim Editar
// Fim Marca Veiculo

// Editar *Marca Modelo Veiculo
$(document).ready(function () {
    $('#marcamodelodatatable').DataTable({
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function (row) {
                        var data = row.data();
                        return 'Details for ' + data[0] + ' ' + data[1];
                    }
                }),
                renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                    tableClass: 'table'
                })
            }
        }
    });
});
// Fim Editar

// Toggle Mostrar PCP
$(document).ready(function () {
    var button = document.getElementById("btn-hide");

    button.addEventListener("click", fnToggle);

    function fnToggle() {
        var mostrar = document.getElementById("lote-pcp");
        mostrar.classList.toggle("hidden");

        if (mostrar) {
            button.innerHTML = "Fechar Lotes"
        } else {
            button.innerHTML = "Abrir Lotes"
        };
    }
})
// Fim Toggle Mostrar PCP


$(document).ready(function () {
    // init datatable.    
    var dataTable = $('.datatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        pageLength: 5,
        // scrollX: true,
        "order": [[0, "asc"]],
        "pageLength": 10,
        ajax: 'get-marca-modelos',
        //ajax: "{{ route('get-articles') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'cd_marca', name: 'cd_marca' },
            { data: 'dsmarca', name: 'dsmarca' },
            { data: 'cd_modelo', name: 'cd_modelo' },
            { data: 'dsmodelo', name: 'dsmodelo' },
            { data: 'cd_frota', name: 'cd_frota' },
            { data: 'dsfrota', name: 'dsfrota' },
            { data: 'Actions', name: 'Actions', orderable: false, serachable: false, sClass: 'text-center' },
        ]

    });

    // Cria Marca Modelo Ajax request.
    $('#SubmitCreateMarcaModeloForm').click(function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "store",
            method: 'post',
            data: {
                cd_marca: $('#cd_marca').val(),
                cd_modelo: $('#cd_modelo').val(),
                cd_frota: $('#cd_frota').val(),
                _token: $('#token').val(),
            },
            beforeSend: function () {
                $("#loading").removeClass('hidden');
            },
            success: function (result) {
                if (result.errors) {
                    $('.alert').html('');
                    $.each(result.errors, function (key, value) {
                        $('.alert').removeClass('hidden');
                        $('.alert').addClass('alert-danger');
                        $('.alert-danger').append('<strong><li>' + value + '</li></strong>');
                        $('#loading').addClass('hidden');
                    });
                } else if (result.alert) {
                    $('.alert').html('');
                    $("#loading").addClass('hidden');
                    $('.alert').removeClass('hidden');
                    $('.alert').addClass('alert-warning');
                    $('.alert-warning').append('<strong>' + result.alert + '</strong>');
                    $('.datatable').DataTable().ajax.reload();                    
                    setInterval(function () {
                        $('.alert').addClass('hidden');
                        $('.alert').removeClass('alert-warning');
                    }, 5000);
                } else {
                    $('.alert').html('');
                    $("#loading").addClass('hidden');
                    $('.alert').removeClass('hidden');
                    $('.alert').addClass('alert-success');
                    $('.alert-success').append('<strong>' + result.success + '</strong>');
                    $('.datatable').DataTable().ajax.reload();
                    $("#formMarcaModelo").trigger('reset');
                    setInterval(function () {
                        $('.alert').addClass('hidden');
                        $('.alert').removeClass('alert-success');
                    }, 5000);
                }
            }
        });
    });

    // Edit Marca Modelo
    $('.modelClose').on('click', function () {
        $('#EditarModeloVeiculo').modal('hide');
    });
    var id;
    $('body').on('click', '#getEditMarcaModeloData', function (e) {
        e.preventDefault();
        $('.alert-danger').html('');
        $('.alert-danger').addClass('hidden');
        id = $(this).data('id');
        $('#EditarModeloVeiculo').modal('show');
        $.ajax({
            url: "edit/" + id + "",
            method: 'GET',
            data: {
                id: id,
            },
            beforeSend: function () {                
                $("#loading").removeClass('hidden');
            },
            success: function (result) {
                $('#EditarModeloVeiculo').show();
                $('#id_marca').val(result.cd_marca)
                $('#id_modelo').val(result.cd_modelo)
                $('#id_frota').val(result.cd_frota)
                $("#loading").addClass('hidden');
            }
        });
    });

    // Update Marca Modelo.
    $('#SubmitEditMarcaModeloForm').click(function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "edit/" + id + "/do",
            method: 'POST',
            data: {
                cd_marca: $('#id_marca').val(),
                cd_modelo: $('#id_modelo').val(),
                cd_frota: $('#id_frota').val(),
            },
            beforeSend: function () {                
                $("#loading").removeClass('hidden');
            },
            success: function (result) {
                if (result.errors) {
                    $('.alert').html('');
                    $.each(result.errors, function (key, value) {
                        $('.alert').removeClass('hidden');
                        $('.alert').addClass('alert-danger');
                        $('.alert-danger').append('<strong><li>' + value + '</li></strong>');
                        $('#loading').addClass('hidden');
                    });
                } else if (result.alert) {
                    $('.alert').html('');
                    $("#loading").addClass('hidden');
                    $('.alert').removeClass('hidden');
                    $('.alert').addClass('alert-warning');
                    $('.alert-warning').append('<strong>' + result.alert + '</strong>');
                    $('.datatable').DataTable().ajax.reload();                    
                    setInterval(function () {
                        $('.alert').addClass('hidden');
                        $('.alert').removeClass('alert-warning');
                    }, 5000);
                } else {
                    $('.alert').html('');
                    $("#loading").addClass('hidden');
                    $('.alert').removeClass('hidden');
                    $('.alert').addClass('alert-success');
                    $('.alert-success').append('<strong>' + result.success + '</strong>');
                    $('.datatable').DataTable().ajax.reload();
                    $("#formMarcaModelo").trigger('reset');
                    setInterval(function () {
                        $('.alert').addClass('hidden');
                        $('.alert').removeClass('alert-success');
                    }, 5000);
                }
            }
        });
    });

    //Delete Marca Modelo
    $('.modelClose').on('click', function () {
        $('#DeleteMarcaModeloVeiculo').modal('hide');
        $('.alert-danger').html('');
        $('.alert-danger').addClass('hidden');
        //$('#DeleteMarcaModeloVeiculo').modal('show');
        $('#SubmitDeleteMarcaModeloForm').removeClass('hidden');
    });
    var deleteId;
    $('body').on('click', '#getDeleteId', function () {
        deleteId = $(this).data('id');
        $('#deleteMsg').removeClass('hidden');
        $('#deleteMsg').html('');
        $('.alert').addClass('hidden');
        $('#deleteMsg').append('<h4> Deseja  realmente excluir o item '+ deleteId +' ?</h4>');
        console.log(deleteId);
    })
    $('#SubmitDeleteMarcaModeloForm').click(function (e) {
        e.preventDefault();
        var id = deleteId;        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "delete/" + id,
            method: 'DELETE',
            data: {
                "_token": $("[name=csrf-token]").attr("content"),
            },
            beforeSend: function () {
                $("#loading").removeClass('hidden');                
            },
            success: function (result) {
                if (result.errors) {
                    $("#loading").addClass('hidden');    
                    $("#deleteMsg").addClass('hidden');                  
                    $('.alert').html('');
                    $('.alert').removeClass('hidden');
                    $('.alert').addClass('alert-danger');                    
                    $('.alert-danger').append('<strong>' + result.errors + '</strong>');
                    $('#SubmitDeleteMarcaModeloForm').addClass('hidden');

                } else {
                    $("#loading").addClass('hidden');
                    $("#deleteMsg").addClass('hidden'); 
                    $('.alert').html('');
                    $('.alert').removeClass('hidden');
                    $('.alert').addClass('alert-success');
                    $('.alert-success').append('<strong>' + result.success + '</strong>');                  
                    $('#SubmitDeleteMarcaModeloForm').addClass('hidden');
                    $('.datatable').DataTable().ajax.reload();                   
                   
                }
            }
        });

    })
});

