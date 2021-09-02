$('.select2').select2()

// CRUD Motorista Veiculo
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
        ajax: 'get-motorista-veiculos',
        //ajax: "{{ route('get-articles') }}",
        columns: [            
            { data: 'cd_empresa', name: 'cd_empresa' },
            { data: 'cd_pessoa', name: 'cd_pessoa' },
            { data: 'name', name: 'name' },
            { data: 'placa', name: 'placa' },
            { data: 'cor', name: 'cor' },
            { data: 'cd_frota', name: 'cd_frota', visible: false},
            { data: 'dsfrota', name: 'dsfrota' },
            { data: 'cd_marca', name: 'cd_marca', visible: false},
            { data: 'dsmarca', name: 'dsmarca' },
            { data: 'cd_modelo', name: 'cd_modelo', visible: false},
            { data: 'dsmodelo', name: 'dsmodelo' },
            { data: 'ano', name: 'ano' },
            { data: 'cd_tipoveiculo', name: 'cd_tipoveiculo', visible: false},
            { data: 'dstipo', name: 'dstipo' },
            { data: 'ativo', name: 'ativo' },
            { data: 'Actions', name: 'Actions', orderable: false, serachable: false, sClass: 'text-center' },
        ], 
        

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
                cd_empresa: $('#cd_empresa').val(),
                cd_pessoa: $('#cd_pessoa').val(),
                placa: $('#placa').val(),
                cor: $('#cor').val(),
                cd_marcamodelofrota: $('#cd_marcamodelofrota').val(),
                ano: $('#ano').val(),
                cd_tipoveiculo: $('#cd_tipoveiculo').val(),                
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
                    $('#cd_pessoa').val(0).trigger('change');
                    $('#cd_tipoveiculo').val(0).trigger('change');
                    $('#cd_marcamodelofrota').val(0).trigger('change');
                    $("#formMotoristaVeiculo")[0].reset();
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
        $('#EditMotoristaVeiculoModal').modal('hide');
    });
    var id;
    $('body').on('click', '#getEditMotoristaVeiculo', function (e) {
        e.preventDefault();
        $('.alert-danger').html('');
        $('.alert-danger').addClass('hidden');
        id = $(this).data('id');
        $('#EditMotoristaVeiculoModal').modal('show');
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
                $('#EditMotoristaVeiculoModal').show();
                $('#id_empresa').val(result.cd_empresa);
                $('#id_pessoa').val(result.cd_pessoa).trigger('change');
                $('#id_placa').val(result.placa);
                $('#id_ano').val(result.ano);
                $('#id_cor').val(result.cor);
                $('#id_marcamodelofrota').val(result.cd_marcamodelofrota).trigger('change');;                
                $('#id_tipoveiculo').val(result.cd_tipoveiculo).trigger('change');
                $("#loading").addClass('hidden');
                }            
        });
    });

    // Update Marca Modelo.
    $('#SubmitCreateMotoristaVeiculoForm').click(function (e) {
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
                cd_empresa: $('#id_empresa').val(),
                cd_pessoa: $('#id_pessoa').val(),
                placa: $('#id_placa').val(),
                ano: $('#id_ano').val(),
                cor: $('#id_cor').val(),
                cd_marcamodelofrota: $('#id_marcamodelofrota').val(),               
                cd_tipoveiculo: $('#id_tipoveiculo').val(),
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
                    }, 10000);
                } else {
                    $('.alert').html('');
                    $("#loading").addClass('hidden');
                    $('.alert').removeClass('hidden');
                    $('.alert').addClass('alert-success');
                    $('.alert-success').append('<strong>' + result.success + '</strong>');
                    $('.datatable').DataTable().ajax.reload();                    
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
        $('#DeleteMotoristaVeiculo').modal('hide');
        $('.alert-danger').html('');
        $('.alert-danger').addClass('hidden');
        //$('#DeleteMotoristaVeiculo').modal('show');
        $('#SubmitDeleteMarcaModeloForm').removeClass('hidden');
    });
    var deleteId;
    $('body').on('click', '#getDeleteId', function () {
        deleteId = $(this).data('id');
        $("#btnCancel").text('Cancelar').removeClass('btn-success').addClass('btn-default');
        $('#SubmitDeleteMotoristaVeiculoForm').removeClass('hidden'); 
        $('#deleteMsg').removeClass('hidden');
        $('#deleteMsg').html('');
        $('.alert').addClass('hidden');
        $('#deleteMsg').append('<h4> Deseja  realmente excluir o item '+ deleteId +' ?</h4>');
        console.log(deleteId);
    })
    $('#SubmitDeleteMotoristaVeiculoForm').click(function (e) {
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
                    $('#SubmitDeleteMotoristaVeiculoForm').addClass('hidden');

                } else {
                    $("#loading").addClass('hidden');
                    $("#btnCancel").text('Sair').removeClass('btn-default').addClass('btn-success');
                    $("#deleteMsg").addClass('hidden'); 
                    $('.alert').html('');
                    $('.alert').removeClass('hidden');
                    $('.alert').addClass('alert-success');
                    $('.alert-success').append('<strong>' + result.success + '</strong>');                  
                    $('#SubmitDeleteMotoristaVeiculoForm').addClass('hidden');
                    $('.datatable').DataTable().ajax.reload();                   
                   
                }
            }
        });

    })
});


// Mascaras input
$(document).ready(function () {
    $("#placa").inputmask({ mask: ['AAA9999', 'AAA9A99'] });
    $("#ano").inputmask("9999");
    //$("#cor").inputmask("AAAAAAAAAAAA");
});

