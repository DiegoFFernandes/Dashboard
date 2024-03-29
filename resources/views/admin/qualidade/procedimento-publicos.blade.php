@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row" style="background: rgb(255, 255, 255); padding-top: 12px;">
            <div class="col-md-4 pull-right">
                @includeIf('admin.master.messages')
            </div>
            <div class="col-md-3">
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Filtrar</h3>
                       

                    </div>

                    <div class="box-body" style="">
                        <ul class="nav nav-stacked">
                           
                            <li><a data-id="all" class="option-proc"><i class="fa fa-folder-open-o text-yellow"></i> Todos<span class="pull-right badge bg-green">{{ $filtro->sum('qtd') }}</span></a></li>
                            
                            @foreach ( $filtro as $f)
                            <li><a data-id="{{ $f->id_setor }}" class="option-proc"><i class="fa fa-folder-open-o text-yellow"></i> {{ $f->nm_setor }}<span class="pull-right badge bg-blue">{{ $f->qtd }}</span></a></li>
                            @endforeach                          
                            
                        </ul>
                    </div>

                </div>

            </div>
            
            <div class="col-md-9">

                <div class="box-body">
                    <table id="table-procedimento" class="table" width="100%">
                        <thead>
                            <th>Cód.</th>
                            <th>Setor</th>
                            <th>Titulo</th>
                            <th>Descrição</th>
                            <th>Resp.</th>
                            <th>Versão</th>
                            <th>Acões</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal Revisão --}}
        <div class="modal fade" id="modal-revisar" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Revião procedimento</h4>
                    </div>
                    <div class="modal-body">
                        <form role="form">
                            <input type="number" class="hide" id="cd_procedimento" val="">
                            <div class="form-group">
                                <label for="title">Procedimento</label>
                                <input type="text" class="form-control" id="title-procedimento" val="" disabled>
                            </div>
                            <div class="form-group">
                                <label for="obs">Revisão/Notificar:</label>
                                <textarea class="form-control" rows="2" name="obs" id="obs"
                                    placeholder="Revisão ou Notificação, tente informar o local ou o paragrafo para sermos mais efetivo na melhoria."></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" id="saveNotify">Notificar</button>
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
            var dataTable;          

            initTable('table-procedimento', 'pub', 'all');            

            $('#table-procedimento').on('click', '.btn-notify', function() {
                var rowData = $('#table-procedimento').DataTable().row($(this).parents('tr')).data();
                if (rowData == undefined) {
                    var selected_row = $(this).parents('tr');
                    if (selected_row.hasClass('child')) {
                        selected_row = selected_row.prev();
                    }
                    rowData = $('#table-procedimento').DataTable().row(selected_row).data();
                }
                console.log(rowData);
                $('#title-procedimento').val(rowData.title);
                $('#cd_procedimento').val(rowData.id);

            });
            $("#saveNotify").click(function(e) {
                let id = $('#cd_procedimento').val();
                let revision = $('#obs').val();
                if (revision == "") {
                    msgToastr('Informe uma breve descrição para revisão!', 'warning');
                    return false;
                }
                $.ajax({
                    type: "GET",
                    url: "{{ route('revision-procedimento.publish') }}",
                    data: {
                        id: id,
                        revision: revision
                    },
                    success: function(response) {
                        if (response.success) {
                            msgToastr(response.success,
                                'success');
                        } else {
                            msgToastr(response.error,
                                'warning');
                        }
                    }
                });

            });

            function initTable(tableId, data, setor) {
                dataTable = $("#" + tableId).DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                    },
                    searchPanes: {
                        viewTotal: true,
                        columns: [1]
                    },
                    dom: 'Plfrtip',
                    pagingType: "simple",
                    processing: false,
                    responsive: true,
                    serverSide: false,
                    autoWidth: false,
                    order: [
                        [0, "desc"]
                    ],
                    "pageLength": 10,
                    ajax: {
                        url: "{{ route('get-procedimento.publish') }}",
                        data: {
                            public: data,
                            setor: setor
                        }
                    },
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'nm_setor',
                            name: 'nm_setor'
                        },
                        {
                            data: 'title',
                            name: 'title'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'criado',
                            name: 'criado'
                        },
                        {
                            data: 'Actions',
                            name: 'Actions',
                            orderable: false,
                            serachable: false,
                            sClass: 'text-center'
                        }
                    ],
                    columnDefs: [{
                            width: '1%',
                            targets: 0
                        },
                        {
                            width: '30%',
                            targets: 3
                        }

                    ],
                });
            }
            $('.option-proc').click(function(e){
                e.preventDefault();
                var setor = $(this).data('id');
                dataTable.destroy();
                initTable('table-procedimento', 'pub', setor); 
                
            });
            $(document).on('click', '.btn-download', function() {
                $("#loading").removeClass('hidden');
                // Inicia o download do arquivo
                $.get($(this).attr('href'), function(response) {
                    // Código para fazer o download do arquivo
                }).done(function() {
                    // Remove a classe "loading" quando o download for concluído
                    $("#loading").addClass('hidden');
                });
            });
        });
    </script>
@endsection
