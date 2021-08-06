
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

