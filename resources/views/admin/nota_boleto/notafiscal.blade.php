@extends('admin.master.master')

@section('content')
    <!-- Main content -->

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid borda" style="margin-top: 10px;">
                    <div class="box-body ">
                        <div class="col-md-12 col-xs-12" style="height: 100px; padding-left :0 !important ">
                            <div class="col-md-10 col-xs-10 " style="margin-bottom: 3px; height: 45px; padding-right: 0 !important;">
                                <div class="col-md-12 col-xs-12 borda" style="margin-top: 3px; margin-bottom: 3px;">
                                    <strong>Prezado:
                                    </strong>Evandro Leonardo dos Santos <br> Recebi de Robercap Recauchutagem de Pneus Ltda
                                    os
                                    serviços indicados no RPS ao lado
                                </div>


                                <div class="col-md-3 col-xs-3 borda" style="margin-bottom: 3px; height: 45px"><strong>Data
                                        Recebimento:</strong>
                                </div>
                                <div class="col-md-9 col-xs-9 borda" style="margin-bottom: 3px; height: 45px"><strong>Identificação e
                                        Assinatura do Recebedor:</strong>
                                </div>
                            </div>
                            <div class="col-md-2 col-xs-2 " style="margin-top: 3px; height: 45px; padding: 0 !important;">
                                <div class="col-md-12 col-xs-12 borda" style="height: 90px">
                                    <strong>Nota: </strong>9999<br>
                                    <strong>RPS: </strong>9999<br>
                                    <strong>Série: </strong>E<br>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <hr size="1" style="border:1px dashed; margin: 10px; margin-top: 10px;">
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <div class="nota" style=" padding:0">
                                <div class="col-md-12 col-xs-12" style="height: 100px; padding:3px !important ">
                                    <div class="col-md-2 col-xs-2 borda" style="padding:3px; height: 92px">
                                        <div style="display:flex; justify-content: center; align-items: center"><img
                                                src="{{ asset('img/logos/brasaopvai.png') }}" style="width:60px"></div>
                                    </div>
                                    <div class="col-md-4 col-xs-4 borda" style="padding:3px; height: 92px">
                                        <div class="title_nfse">
                                            <p>Nota Fiscal de Serviço Eletrônica</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-6" style="padding:0px; height: 92px">
                                        <div class="col-md-4 col-xs-4 borda" style="padding:3px; height: 40px"><Strong>Numero RPS:
                                            </Strong>9999</div>
                                        <div class="col-md-4 col-xs-4 borda" style="padding:3px; height: 40px"><Strong>Numero Nota:
                                            </Strong>9999</div>
                                        <div class="col-md-4 col-xs-4 borda" style="padding:3px; height: 40px"><Strong>Data Emissão:
                                            </Strong>30/01/2024</div>
                                        <div class="col-md-12 col-xs-12 borda" style="height: 52px;">
                                            <Strong>CodigoVerificação:</Strong>
                                            <div>
                                                358853358
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <div class="title_prestador"> Prestador de Serviços</div>
                            </div>
                        </div>

                        <div class="col-md-12 col-xs-12">
                            <div class="col-md-2 col-xs-2 borda" style="padding:3px; height: 92px">
                                <div class="logo_ivo"><img src="{{ asset('img/logo-ivo.png') }}" alt="IVO RECAP"
                                        style="width:90px; margin: 10px; margin-left: 35px"></div>
                            </div>
                            <div class="col-md-10 col-xs-10" style="padding:3px; height: 92px" >
                                <div class="col-md-12 col-xs-12 borda" >
                                    <div class="col-md-10 col-xs-10 dados_prestador" >
                                       <div><Strong>Nome: </Strong>ROBERCAP RECAUCHUTAGEM DE PNEUS LTDA</div>
                                       <div><strong>CNPJ: </strong>05.389.334/0001-12</div>
                                       <div><strong>IM: </strong>10682</div>
                                       <div><strong>Endereço:</strong>Rua Frei Boa Ventura Einberger S/N CEP: 87.720-115 Bairro: Distrito Industrial Sumaré - Paranavaí-PR</div>
                                    </div>
                            </div>

                        </div>



                    </div>
                </div>

            </div>
        </div>
        </div>

        </div>



    </section>
@endsection




@section('scripts')
    @includeIf('admin.master.datatables')
    <script src="{{ asset('js/scripts.js') }}"></script>

    <script type="text/javascript"></script>
@endsection
