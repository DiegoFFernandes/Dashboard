$('.select2').select2({
    placeholder: "Selecione",
    allowClear: true
})

// CRUD Pessoa
$(document).ready(function() {
    // init datatable.    
    var dataTable = $('.display').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        pageLength: 5,
        // scrollX: true,
        //"order": [[0, "asc"]],
        "pageLength": 10,
        ajax: 'get-pessoa',
        //ajax: "{{ route('get-articles') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'cpf', name: 'cpf' },
            { data: 'cd_email', name: 'cd_email', visible: false },
            { data: 'email', name: 'email' },
            { data: 'cd_empresa', name: 'empresa' },
            { data: 'phone', name: 'phone', visible: false },
            { data: 'endereco', name: 'endereco', visible: false },
            { data: 'numero', name: 'numero', visible: false },
            { data: 'Actions', name: 'Actions', orderable: false, serachable: false, sClass: 'text-center' },
        ],

    });

    var btnAdd = $('.add'),
        btnUpdate = $('.btn-update'),
        btnCancel = $('.btn-cancel'),
        btnSave = $('.btn-save');
    var modal = $('.modal');
    var form = $('#formPessoa');

    btnAdd.click(function() {
        $("#cd_email").attr('readonly', false);
        form.trigger('reset');
        $('.modal-title').text('Adicionar Pessoa');
        btnSave.show();
        btnUpdate.hide();
    })


    // Cria Pessoa Ajax request.
    btnSave.click(function(e) {
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
                name: $('#name').val(),
                cpf: $('#cpf').val(),
                cd_email: $('#cd_email').val(),
                endereco: $('#endereco').val(),
                numero: $('#numero').val(),
                phone: $('#phone').val(),
                _token: $('#token').val(),
            },
            beforeSend: function() {
                $("#loading").removeClass('hidden');
            },
            success: function(result) {
                if (result.errors) {
                    $('.alert').html('');
                    $.each(result.errors, function(key, value) {
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
                    setInterval(function() {
                        $('.alert').addClass('hidden');
                        $('.alert').removeClass('alert-warning');
                    }, 5000);
                } else {
                    $('.alert').html('');
                    $("#loading").addClass('hidden');
                    $('.alert').removeClass('hidden');
                    $('.alert').addClass('alert-success');
                    $('.alert-success').append('<strong>' + result.success + '</strong>');
                    $('.display').DataTable().ajax.reload();
                    $('#cd_email').val(0).trigger('change');
                    $("#formPessoa")[0].reset();
                    setInterval(function() {
                        $('.alert').addClass('hidden');
                        $('.alert').removeClass('alert-success');
                    }, 5000);
                    location.reload();
                }
            }
        });
    });

    // Edit Pessoa
    $(document).on('click', '.btn-edit', function() {
        btnSave.hide();
        btnUpdate.show();

        modal.find('.modal-title').text('Atualizar Pessoa');
        modal.find('.modal-footer button[type="submit"]').text('Editar')

        var rowData = dataTable.row($(this).parents('tr')).data();


        $('#cd_email').val(rowData.cd_email).trigger('change');
        $("#cd_empresa").val(rowData.cd_empresa);
        form.find('input[name="name"]').val(rowData.name);
        form.find('input[name="cpf"]').val(rowData.cpf).attr('readonly', true)
        form.find('input[name="phone"]').val(rowData.phone);
        form.find('input[name="endereco"]').val(rowData.endereco);
        form.find('input[name="numero"]').val(rowData.numero);
        form.find('input[name="id"]').val(rowData.id);

        modal.modal();

    })

    btnUpdate.click(function() {
        if (!confirm("Você tem certeza que deseja atualizar?")) return;
        var formData = form.serialize() + '&_method=POST';
        var updateId = form.find('input[name="id"]').val();

        $.ajax({
            url: "edit/" + updateId + "/do",
            type: 'POST',
            data: formData,
            beforeSend: function() {
                $("#loading").removeClass('hidden');
            },
            success: function(result) {
                if (result.errors) {
                    $('.alert').html('');
                    $.each(result.errors, function(key, value) {
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
                    $('.display').DataTable().ajax.reload();
                    setInterval(function() {
                        $('.alert').addClass('hidden');
                        $('.alert').removeClass('alert-warning');
                    }, 10000);

                } else {
                    $('.alert').html('');
                    $("#loading").addClass('hidden');
                    $('.alert').removeClass('hidden');
                    $('.alert').addClass('alert-success');
                    $('.alert-success').append('<strong>' + result.success + '</strong>');
                    setInterval(function() {
                        $('.alert').addClass('hidden');
                        $('.alert').removeClass('alert-success');
                    }, 5000);
                    //location.reload();
                }
            }
        });

    })

    //Delete Pessoa
    var deleteId;
    $('body').on('click', '#getDeleteId', function() {
        deleteId = $(this).data('id');
        if (!confirm('Deseja realmente excluir o item ' + deleteId + ' ?')) return;
        console.log(deleteId);


        $.ajax({
            url: "delete/" + deleteId,
            method: 'DELETE',
            data: {
                "_token": $("[name=csrf-token]").attr("content"),
            },
            beforeSend: function() {
                $("#loading").removeClass('hidden');
            },
            success: function(result) {
                if (result.alert) {
                    $("#loading").addClass('hidden');
                    alert(result.alert);

                } else {
                    $("#loading").addClass('hidden');
                    $('.display').DataTable().ajax.reload();
                    alert(result.success);
                }
            }
        });
    });


});


// Mascaras input
$(document).ready(function() {
    $("#email").inputmask("email");
    $("#cpf").inputmask("999.999.999-99");
    $("#phone").inputmask("99 9.9999-9999")
});