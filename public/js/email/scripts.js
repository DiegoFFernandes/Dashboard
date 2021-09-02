$('.select2').select2()

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
        ajax: 'get-email',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'email', name: 'name' },
            { data: 'Actions', name: 'Actions', orderable: false, serachable: false, sClass: 'text-center' },
        ],

    });

    var btnAdd = $('.add'),
        btnUpdate = $('.btn-update'),
        btnSave = $('.btn-save');
    var modal = $('.modal');
    var form = $('#formEmail');

    btnAdd.click(function() {
        form.trigger('reset');
        $('.modal-title').text('Adicionar Email');
        btnSave.show();
        btnUpdate.hide();
    })


    // Cria Email Ajax request.
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
                email: $('#email').val(),
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
                    //$('.display').DataTable().ajax.reload();
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
                    //$('#email').val(0).trigger('change');
                    $("#formEmail")[0].reset();
                    setInterval(function() {
                        $('.alert').addClass('hidden');
                        $('.alert').removeClass('alert-success');
                    }, 5000);
                }
            }
        });
    });

    // Edit Email
    $(document).on('click', '.btn-edit', function() {
        btnSave.hide();
        btnUpdate.show();

        modal.find('.modal-title').text('Atualizar Email');
        modal.find('.modal-footer button[type="submit"]').text('Editar')

        var rowData = dataTable.row($(this).parents('tr')).data();
        $("#id").val(rowData.id);
        $("#email").val(rowData.email);

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
                    $('.display').DataTable().ajax.reload();
                    setInterval(function() {
                        $('.alert').addClass('hidden');
                        $('.alert').removeClass('alert-success');
                    }, 5000);
                }
            }
        });

    })

    //Delete Email
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
                    alert(result.success);
                    $('.display').DataTable().ajax.reload();

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