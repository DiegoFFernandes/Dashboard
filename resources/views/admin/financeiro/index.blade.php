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
                                    <option value="3">SUPER RODAS RECAPAGENS LTDA</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Periodo</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right periodo" id="daterange" value=""
                                        autocomplete="off">
                                </div>
                                <!-- /.input group -->
                            </div>
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
                    data: {
                        cd_empresa: cd_empresa,
                        dt_inicio: inicioData,
                        dt_fim: fimData,
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(data) {
                        $("#loading").addClass('hidden');
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(data);
                        link.download = nm_empresa + '-Conciliacao.xlsx';
                        link.click();
                    },
                    fail: function(data) {
                        alert('Not downloaded');
                        //console.log('fail',  data);
                    }
                });
            });

        });
    </script>
@endsection
