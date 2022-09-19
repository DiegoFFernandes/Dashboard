@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Buscar:</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="cd_empresa">Empresa</label>
                                <select class="form-control select2" name="cd_empresa" id="cd_empresa" style="width: 100%;">
                                    <option value="0" selected="selected">Selecione</option>
                                    <option value="1">AM MORENO PNEUS LTDA</option>
                                    <option value="21">EMAX RECAPAGENS EIRELI</option>
                                    <option value="3">SUPER RODAS - CAMPINA</option>
                                    <option value="4">SUPER RODAS - GUARAPUAVA</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            @includeIf('admin.master.filtro-data')
                        </div>
                        <div class="col-md-2" align="center" style="padding-top: 24px">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-block" id="submit-seach">
                                    <i class="fa fa-download"></i> Gerar Arquivo</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    @includeIf('admin.master.datatables')
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var inicioData = 0;
            var fimData = 0;
            $('#daterange').daterangepicker({
                autoUpdateInput: false,
            });
            $('#daterange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                    'DD/MM/YYYY'));
                inicioData = picker.startDate.format('MM/DD/YYYY');
                fimData = picker.endDate.format('MM/DD/YYYY');
            });
            $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val("");
                inicioData = 0;
                fimData = 0;
            });
            $('#submit-seach').click(function() {
                let cd_empresa = $("#cd_empresa").val();
                let nm_empresa = $("#cd_empresa :selected").text();

                if (cd_empresa == 0) {
                    $('#cd_empresa').attr('title', 'Empresa é obrigatório!').tooltip('show');
                    return false;
                } else if (inicioData == "") {
                    alert('Período deve ser preenchida!');
                    $('#daterange').attr('title', 'Período é obrigatório!').tooltip('show');
                    return false;
                }

                $.ajax({
                    url: "{{ route('get-conciliacao') }}",
                    method: "GET",
                    cache: false,
                    data: {
                        cd_empresa: cd_empresa,
                        dt_inicio: inicioData,
                        dt_fim: fimData,
                        nm_empresa: nm_empresa
                    },                    
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(response) {
                        $("#loading").addClass('hidden');
                        var link = document.createElement('a');
                        link.href = response.file;                        
                        link.download = response.name;
                        document.body.appendChild(link);
                        link.click();
                        link.remove();
                    },
                    error: function(ajaxContext) {
                        $("#loading").addClass('hidden');
                        alert('Erro ao fazer download, contate o setor de TI.');                        
                    }
                });
            });

        });
    </script>
@endsection
