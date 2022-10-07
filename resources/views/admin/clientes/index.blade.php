@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#boletos" data-toggle="tab" aria-expanded="true">Boletos</a>
                        </li>
                        <li class="">
                            <a href="#notafiscal" data-toggle="tab" aria-expanded="false">Nota Fiscal</a>
                        </li>
                    </ul>
                    <div class="tab-content no-padding">
                        <div class="tab-pane active" id="boletos">
                            <div class="box-body">
                                <div class="col-md-12" style="">
                                    <div style="padding-bottom: 15px">
                                        <div class="box-header with-border" style="border-bottom: 1px solid #d1d1d1;">
                                            <h3 class="box-title" style="text-align: center">Pagamentos Pendentes
                                            </h3>
                                        </div>
                                    </div>
                                    <table class="table compact" id="table-tickets-pendents">
                                        <thead>
                                            <th>Beneficiário</th>
                                            <th>Cliente</th>
                                            <th>Documento</th>
                                            <th>Valor</th>
                                            <th>Vencimento</th>
                                            <th>Status</th>
                                            <th>2º Via</th>
                                        </thead>
                                    </table>

                                </div>

                            </div>
                        </div>
                        <div class="tab-pane" id="notafiscal">
                            {{-- <input id="token" name="_token" type="hidden" value="{{ csrf_token() }}"> --}}
                            <div class="box-body">
                                <div class="col-md-12" style="background-color: #ecf0f5">
                                    <div style="padding-bottom: 15px">
                                        <div class="box-header with-border" style="border-bottom: 1px solid #d1d1d1;">
                                            <h3 class="box-title" style="text-align: center;">Notas Fiscais Emitidas
                                            </h3>
                                        </div>
                                    </div>
                                    <table class="table compact" id="">
                                        <thead>
                                            <th>Ações</th>
                                            <th>Cod.</th>
                                            <th>Cliente</th>
                                            <th>Email</th>
                                            <th>Modulo</th>
                                        </thead>
                                    </table>
                                </div>
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
    <script src="https://cdn.datatables.net/plug-ins/1.10.19/dataRender/datetime.js"></script>
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
            $('body').on('click', '#btnDoc', function(e) {
                var documento = $(this).data('documento');
                $.ajax({
                    type: "GET",
                    url: "{{ route('client-save-tickets') }}",
                    data: {
                        doc: documento
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(response) {
                        $("#loading").addClass('hidden');
                        if(response.error){
                            alert(response.error);
                            return false;
                        }else{
                            window.open(response.url, '_blank');   
                        }
                                             
                    }
                });
            });

            $('#table-tickets-pendents').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                pagingType: "simple",
                processing: false,
                ajax: {
                    url: "{{ route('client.tickets-pendents-enterprise') }}"
                },
                columns: [{
                        data: 'EMPRESA',
                        name: 'EMPRESA',

                    },
                    {
                        data: 'PESSOA',
                        name: 'PESSOA',

                    },
                    {
                        data: 'NR_DOCUMENTO',
                        name: 'NR_DOCUMENTO',

                    },
                    {
                        data: 'VALOR',
                        name: 'VALOR',
                    },
                    {
                        data: 'DT_VENCIMENTO',
                        name: 'DT_VENCIMENTO',
                    },
                    {
                        data: 'STATUS',
                        name: 'STATUS',
                    },
                    {
                        data: 'action',
                        name: 'action',

                    },
                ],
                columnDefs: [{
                    targets: [4],
                    render: $.fn.dataTable.render.moment('DD/MM/YYYY')
                }],
                order: [4, 'asc']

            });

        });
    </script>
@endsection
