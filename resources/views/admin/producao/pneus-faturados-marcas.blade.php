@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Pesquisar por Periodo</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label>Datas</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="periodo" value="" autocomplete="off"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" id="submit-periodo" class="btn btn-primary pull-right">Pesquisar</button>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title title-search">Resultado Medida x Marca:
                            {{ formatDate($inicio_data) . ' - ' . formatDate($fim_data) }}</h3>                        
                    </div>
                    <div class="box-body">
                        <div id="search">
                            <table class="table table-bordered table-sm" id="table-gqc">
                                <thead>
                                    <tr>
                                        <th>Marca</th>
                                        <th>Sigla</th>
                                        <th>Medida</th>
                                        <th>Qtd</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-gqc">
                                </tbody>
                            </table>
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
    <script type="text/javascript">
        $(document).ready(function() {
            var inicioData = 0;
            var fimData = 0;
            $('#periodo').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                    'DD/MM/YYYY'));
                inicioData = picker.startDate.format('MM/DD/YYYY');
                fimData = picker.endDate.format('MM/DD/YYYY');
            });
            $('#periodo').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val("");
                inicioData = 0;
                fimData = 0;
            });
            $('#periodo').daterangepicker({
                autoUpdateInput: false,
            });

            $('#table-gqc').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                processing: true,
                //serverSide: true,
                autoWidth: false,
                "pageLength": 10,
                ajax: "{{ route('get-pneus-faturados-marca') }}",
                columns: [{
                        data: 'DSMARCA',
                        name: 'dsmarca',
                        //"width": '1%'
                    },
                    {
                        data: 'SIGLA',
                        name: 'sigla',
                    },
                    {
                        data: 'DSMEDIDAPNEU',
                        name: 'dsmedidapneu',
                    },
                    {
                        data: 'QTD',
                        name: 'qtd',
                    }
                ],
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                // columnDefs: [{
                //     "width": "10px",
                //     "targets": 0
                // }],

            });

            $('#submit-periodo').click(function() {
                if ($('#periodo').val() == "") {
                    alert("Periodo deve ser informado para consulta!");
                    return false;
                }
                $.ajax({
                    url: '{{ route('get-gqc-buscar-pneus-marca') }}',
                    method: 'GET',
                    data: {
                        inicio_data: inicioData,
                        fim_data: fimData,
                    },
                    beforeSend: function() {
                        $("#table-gqc").DataTable().destroy();
                        $("#loading").removeClass('hidden');
                        $('#table-gqc').remove();
                    },
                    success: function(result) {
                        $("#loading").addClass('hidden');
                        $('.title-search').text("Resultado Medida x Marca: " +
                            moment(inicioData, "MM-DD-YYYY").format('DD/MM/YYYY') + " - " +
                            moment(fimData, "MM-DD-YYYY").format('DD/MM/YYYY'))
                        $('#search').append(result);
                        $('#table-gqc').DataTable({
                            language: {
                                url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                            },
                            processing: true,
                            //serverSide: true,
                            autoWidth: false,
                            "pageLength": 10,
                            dom: 'Blfrtip',
                            buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                            ],
                        });
                    }
                });
            });

        });
    </script>
@endsection
