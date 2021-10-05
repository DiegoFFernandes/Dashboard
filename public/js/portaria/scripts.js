$('.select2').select2({
    allowClear: true
})
$(document).ready(function() {
    $(document).on('click', 'li', function() {
        $('#placa').val($(this).text());
        $('#placaList').fadeOut();
    });

    $('.SearchPlaca').select2({
        placeholder: 'Selecione uma placa',
        minimumInputLength: 1,
        ajax: {
            url: 'http://producao.ivorecap.com.br/placa/search',
            dataType: 'json',
            //delay: 250,
            results: function(data) {
                console.log(data);
            },

            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.placa,
                            id: item.id,
                        }
                    }),

                };
            },
            cache: true
        }
    });

});