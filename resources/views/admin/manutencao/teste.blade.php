@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#new-ticket" data-toggle="tab" aria-expanded="true">Criar Chamado</a>
                        </li>
                        <li class="">
                            <a href="#tickets-existing" data-toggle="tab" aria-expanded="false">Chamados</a>
                        </li>
                    </ul>
                    <div class="tab-content no-padding">
                        <div class="tab-pane active" id="new-ticket">
                            <div class="col-md-12">
                                <label for="cd_etapa">Cód. Equipamento:</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name='cd_maq' id="cd_maq" width="100"
                                        placeholder="1111">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="qr-reader" style="width: 600px;"></div>
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
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // Handle on success condition with the decoded text or result.
            console.log(`Scan result: ${decodedText}`, decodedResult);
        }

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", {
                fps: 10,
                qrbox: 250
            });
        html5QrcodeScanner.render(onScanSuccess);
    </script>
@endsection
