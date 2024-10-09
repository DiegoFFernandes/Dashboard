@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid">
                    <div class="box-body">
                        <div class="col-md-4">
                            <div class="box box-warning">
                                <div class="box-header with-border text-center">
                                    <h3 class="box-title">Produção Não Iniciada</h3>
                                </div>
                                <div class="box-body">
                                    <table class="table compact" id="prod-nao-iniciada">
                                        <thead>
                                            <tr>
                                                <th>Empresa</th>
                                                <th>Qtd</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th style="text-align:right">Total:</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="box box-default">
                                <div class="box-header with-border text-center">
                                    <h3 class="box-title">Coletas Hoje</h3>
                                </div>
                                <div class="box-body">
                                    <table class="table compact" id="coletas-hoje">
                                        <thead>
                                            <tr>
                                                <th>Empresa</th>
                                                <th>Qtd</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th style="text-align:right">Total:</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box box-default">
                                <div class="box-header with-border text-center">
                                    <h3 class="box-title">Coletas Ontem</h3>
                                </div>
                                <div class="box-body">
                                    <table class="table compact" id="coletas-ontem">
                                        <thead>
                                            <tr>
                                                <th>Empresa</th>
                                                <th>Qtd</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th style="text-align:right">Total:</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="box box-default">
                                <div class="box-header with-border text-center">
                                    <h3 class="box-title">Coleta Mês</h3>
                                </div>
                                <div class="box-body">
                                    <table class="table compact" id="coletas-mes">
                                        <thead>
                                            <tr>
                                                <th>Empresa</th>
                                                <th>Qtd</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th style="text-align:right">Total:</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box box-default">
                                <div class="box-header with-border text-center">
                                    <h3 class="box-title">Coleta Mês Anterior</h3>
                                </div>
                                <div class="box-body">
                                    <table class="table compact" id="coletas-mes-anterior">
                                        <thead>
                                            <tr>
                                                <th>Empresa</th>
                                                <th>Qtd</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th style="text-align:right">Total:</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="box box-default">
                                <div class="box-header with-border text-center">
                                    <h3 class="box-title">Coleta Ultimos 6 Meses</h3>
                                </div>
                                <div class="box-body">
                                    <canvas id="chart-6-meses"></canvas>
                                    <div class="overlay" id='load-chart'>
                                        <i class="fa fa-refresh fa-spin"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection
@section('scripts')
    @includeIf('admin.master.datatables')

    <script type="text/javascript">
        $(document).ready(function() {

            loadTable('coletas-mes', 1);
            loadTable('coletas-mes-anterior', 2);
            loadTable('coletas-ontem', 4);
            loadTable('coletas-hoje', 5);
            loadTable('prod-nao-iniciada', 6);

            function loadTable(IdTabela, Id) {
                $('#' + IdTabela).DataTable({
                    paging: false,
                    searching: false,
                    bInfo: false,
                    ajax: {
                        url: "{{ route('view.list-coleta-rede') }}",
                        data: {
                            id: Id
                        }
                    },
                    columns: [{
                            data: 'NM_EMPRESA',
                            name: 'NM_EMPRESA'
                        },
                        {
                            data: 'QTDE',
                            name: 'QTDE'
                        },
                    ],
                    footerCallback: function(row, data, start, end, display) {
                        var api = this.api();

                        // Função para converter em inteiro
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                // Remove símbolos e converte para número
                                typeof i === 'number' ?
                                i : 0;
                        };
                        total = api
                            .column(1)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        $(api.column(1).footer()).html(total);
                    }

                });
            }

            $.ajax({
                type: "GET",
                url: "{{ route('view.list-coleta-rede') }}",
                data: {
                    id: 3
                },
                success: function(response) {
                    console.log(response);
                    $('#load-chart').remove();
                    const ctx = document.getElementById('chart-6-meses');

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: response.labels,
                            datasets: [{
                                label: '# Qtd',
                                data: response.qtd,
                                borderWidth: 1,
                                backgroundColor: 'rgba(75,159,236,0.4)',
                            }]
                        },

                        options: {
                            plugins: {
                                datalabels: {
                                    color: 'black', // Cor do texto
                                    anchor: 'end', // Posição do texto (início, centro ou final da barra)
                                    align: 'end', // Alinhamento do texto em relação à barra
                                    formatter: function(value, context) {
                                        return value; // Exibe o valor da barra
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        },
                        plugins: [ChartDataLabels]
                    });
                }
            });







        });
    </script>
@endsection
