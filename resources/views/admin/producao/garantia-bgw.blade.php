@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                @includeIf('admin.master.messages')
                <div class="nav-tabs-custom" style="cursor: move;">
                    <ul class="nav nav-tabs pull-right ui-sortable-handle">
                        <li class="pull-left active"><a href="#pneus-bgw" data-toggle="tab" aria-expanded="true">Pneus</a>
                        </li>
                        <li class="pull-left"><a href="#logs-bgw" data-toggle="tab" aria-expanded="false">Logs</a>
                        </li>
                        <li class="header"><i class="fa fa-inbox"></i> Pneus Ouro - BGW</li>
                    </ul>
                    <div class="tab-content no-padding">
                        <div class="tab-pane active" id="pneus-bgw">
                            <table class="table table-striped table-bordered" id="table-bgw" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Ordem</th>
                                        <th>Cliente</th>
                                        <th>Medida</th>
                                        <th>Serie</th>
                                        <th>Fogo</th>
                                        <th>Dot</th>
                                        <th>Marca</th>
                                        <th>Ciclo</th>
                                        <th>Preço</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pneus as $p)
                                        <tr>
                                            <td>{{ $p->ORD_NUMERO }}</td>
                                            <td>{{ $p->CLI_NOME }}</td>
                                            <td>{{ $p->MEDIDA }}</td>
                                            <td>{{ $p->MATRICULA }}</td>
                                            <td>{{ $p->NUM_FOGO }}</td>
                                            <td>{{ $p->DOT }}</td>
                                            <td>{{ $p->MARCA }}</td>
                                            <td>{{ $p->COD_I_CICLO }}</td>
                                            <td>{{ $p->PRECO }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mx-auto">
                                <button class="btn btn-block btn-success" id="pross-pneus">Processar Pneus</button>
                            </div>
                        </div>
                        <div class="tab-pane log-bgw" id="logs-bgw">
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
            $('#table-bgw').DataTable({});
            $('#pross-pneus').click(function() {
                $.ajax({
                    url: '{{ route('NewAgecallXmlProcess') }}',
                    method: "GET",
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                        $("#pross-pneus").text('Processando...')
                    },
                    success: function(result) {
                        $("#pross-pneus").text('Processar Pneus')
                        $("#loading").addClass('hidden');
                        // console.log(result);
                        $("#table-log").remove();
                        $(".log-bgw").append(result);
                        $('.nav-tabs a[href="#logs-bgw"]').tab('show')
                        $('#table-log1').DataTable({
                            scrollY: "300px",
                            scrollX: true,
                            scrollCollapse: true,
                            paging: false,
                            columnDefs: [{
                                width: '20%',
                                targets: 2
                            }],
                            fixedColumns: true
                        });
                    }

                });
            });
        });
    </script>
@endsection
