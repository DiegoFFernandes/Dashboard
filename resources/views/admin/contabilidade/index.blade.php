@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Preencha as informações:</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Data:</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" id="datepicker">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>Status:</label>
                            <!-- radio -->
                            <div class="form-group">
                                <label>
                                    <input type="radio" name="r1" value="S" class="minimal">
                                    Ativado
                                </label>
                                <label>
                                    <input type="radio" name="r1" value="N" class="minimal">
                                    Desativado
                                </label>

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-block" id="submit-save">
                                    <i class="fa"></i>Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Parametros do Junsoft</h3>
                        <div class="box-tools">
                            <div class="input-group input-group-sm hidden-xs" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control pull-right"
                                    placeholder="Search">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Empresa</th>
                                    <th>Bloqueio Contabil</th>
                                    <th>Bloqueio Financeiro</th>
                                    <th>Bloqueio Estoque</th>
                                    <th>Bloqueio Caixa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $l)
                                    <tr>
                                        <td>{{ $l->CD_EMPRESA }}</td>
                                        <td>{{ !empty($l->DT_BLOQCONTABIL) ? \Carbon\Carbon::parse($l->DT_BLOQCONTABIL)->format('d/m/Y') : '' }}
                                        </td>
                                        <td>{{ !empty($l->DT_BLOQFINANCEIRO) ? \Carbon\Carbon::parse($l->DT_BLOQFINANCEIRO)->format('d/m/Y') : '' }}
                                        </td>
                                        <td>{{ !empty($l->DT_BLOQESTOQUE) ? \Carbon\Carbon::parse($l->DT_BLOQESTOQUE)->format('d/m/Y') : '' }}
                                        </td>
                                        <td>{{ !empty($l->DT_BLOQCAIXA) ? \Carbon\Carbon::parse($l->DT_BLOQCAIXA)->format('d/m/Y') : '' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
        $.ajax({
            type: "get",
            url: "{{ route('status-gerenciador-contabil') }}",
            success: function(response) {
                if (response.status == 'N') {
                    $('input[name="r1"][value="N"]').iCheck('check');
                }
                $('input[name="r1"][value="S"]').iCheck('check');
            }
        });

        //iCheck for checkbox and radio inputs
        $('input[type="radio"].minimal').iCheck({
            radioClass: 'iradio_minimal-blue'
        });

        $('input[type="radio"].minimal').on('ifClicked', function(event) {
            status = $(this).val();
        });


        $('#datepicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 2023,
            maxYear: parseInt(moment().format('YYYY'), 10),
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
        var date = $('#datepicker').val();

        $('#datepicker').on('apply.daterangepicker', function(ev, picker) {
            date = picker.startDate.format('DD/MM/YYYY');
        });
        $("#submit-save").click(function(e) {
            $.ajax({
                url: "{{ route('parm-contabilidade.store-date') }}",
                method: "get",
                cache: false,
                data: {
                    date: date,
                    status: status
                },
                beforeSend: function() {
                    $("#loading").removeClass('hidden');
                },
                success: function(response) {
                    $("#loading").addClass('hidden');
                    msgToastr(response.success, 'success');
                }
            });
        });
    </script>
@endsection
