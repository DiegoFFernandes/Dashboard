// Toggle Mostrar PCP
$(document).ready(function() {

    var btnHide = $('#btn-hide'),
        lotePcp = $('#lote-pcp'),
        btnClicked;


    btnHide.click(function() {
        if (btnClicked != true) {
            btnClicked = true;
            lotePcp.removeClass('hidden');
            btnHide.html('Fechar Lote');
        } else {
            btnClicked = false;
            lotePcp.addClass('hidden');
            btnHide.html('Abrir Lote');
        }

    });
});
// Fim Toggle Mostrar PCP