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
