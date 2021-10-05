$(document).ready(function() {
    // init datatable.    
    var dataTable = $('.display').DataTable();

    // Edit Email
    $(document).on('click', '.btn-edit', function() {

        var rowData = dataTable.row($(this).parents('tr')).data();
        $('#id').val(rowData[0]);
        $("#descricao").val(rowData[1]);
    })

    $('#delete').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        console.log(id);
        var modal = $(this);
        modal.find('#cd_delete').val(id);
    })


});