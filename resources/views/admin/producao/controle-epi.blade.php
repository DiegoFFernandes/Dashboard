@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-6">
                @includeIf('admin.master.messages')
                <div class="nav-tabs-custom" style="cursor: move;">
                    <ul class="nav nav-tabs pull-right ui-sortable-handle">
                        <li class="pull-left active"><a href="#operador-epi" data-toggle="tab" aria-expanded="true">Operador
                                x Epi</a>
                        </li>
                        <li class="pull-left"><a href="#relatorio-epi" data-toggle="tab"
                                aria-expanded="false">Relatório</a>
                        </li>

                        {{-- <li class="header"><i class="fa fa-inbox"></i> Controle Epi</li> --}}
                    </ul>
                    <div class="tab-content no-padding">
                        <div class="tab-pane active" id="operador-epi">
                            <div class="box-body" id="body-select">
                                @includeIf('admin.master.executores')
                                @includeIf('admin.master.etapas-produtivas')
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-success hidden" id="btn-save">Salvar</button>
                            </div>
                        </div>
                        <div class="tab-pane" id="relatorio-epi">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="etapas">Etapa Produtiva:</label>    
                                    <select class="form-control select2" id="list-epis" style="width: 100%;">    
                                        <option value="0">Selecione uma etapa</option>
                                        @foreach ($etapas as $e)
                                            <option value="{{ $e->id }}">{{ $e->dsetapaempresa }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="list-epis">Epis:</label>
                                    <select class="form-control select2" id="list-epis" style="width: 100%;">
                                        <option selected="selected" value="0">Selecione Epi</option>
                                        @foreach ($epis as $e)
                                            <option value="{{ $e->id }}">{{ $e->ds_epi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="periodo">Data:</label>
                                    <input type="text" class="form-control pull-right" id="daterange" value=""
                                        autocomplete="off">
                                </div>                                
                            </div>
                            <div class="box-footer">
                                <button type="button" class="btn btn-primary mb-2" id="btn-search">Consultar</button>
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
            var executor, etapa;
            $('#executor').select2();
            $('.etapas').select2();
            $('#list-epis').select2();            
            var inicioData = 0;
            var fimData = 0;
            $('#daterange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm') + ' - ' + picker.endDate.format(
                    'DD/MM/YYYY HH:mm'));
                inicioData = picker.startDate.format('MM/DD/YYYY HH:mm');
                fimData = picker.endDate.format('MM/DD/YYYY HH:mm');
            });
            $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val("");
                inicioData = 0;
                fimData = 0;
            });
            $('#daterange').daterangepicker({
                //opens: 'left',
                autoUpdateInput: false,
                // timePicker: true,
                //timePickerIncrement: 30,
                // locale: {
                //     format: 'MM/DD/YYYY HH:mm',

                // }
            });
            $('.nav-tabs a[href="#relatorio-epi"]').on('click', function() {
                $('.etapas').select2();
            });
            $(".etapas").change(function() {
                var id_etapa = $(this).val();
                $.ajax({
                    url: '{{ route('search-etapas-producao') }}',
                    method: 'GET',
                    data: {
                        idetapa: id_etapa,
                    },
                    beforeSend: function() {
                        $('#epis-etapa').remove();
                    },
                    success: function(result) {
                        $('#btn-save').removeClass('hidden');
                        $('#body-select').append(result.html);
                    }
                });
            });
            $('#btn-save').click(function() {
                var epis = new Array();
                $('input[name="epis[]"]:checked').each(function() {
                    epis.push($(this).val());
                });
                $.ajax({
                    url: '{{ route('save-epi-etapas-operador') }}',
                    method: 'GET',
                    data: {
                        executor: $('#executor').val(),
                        etapa: $('.etapas').val(),
                        epis: epis,
                    },
                    beforeSend: function() {
                        // $('#epis-etapa').remove();
                    },
                    success: function(result) {
                        // alert(result);
                        setTimeout(function() {
                            $(".alert").removeClass('hidden');
                            $(".alert p").text(result);
                        }, 400);
                        window.setTimeout(function() {
                            $(".alert").addClass('hidden');
                        }, 2000);
                        // $('#btn-save').removeClass('hidden');
                        // $('#body-select').append(result.html);
                    }
                });
            });
        });
    </script>
@endsection
