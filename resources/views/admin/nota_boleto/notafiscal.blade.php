@extends('admin.master.master')

@section('content')
    <!-- Main content -->

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid borda" style="margin-top: 10px;">
                    <div class="box-body ">
                        <div class="col-md-12 col-xs-12" style="height: 100px; padding-left :0 !important ">
                            <div class="col-md-10 col-xs-10 "
                                style="margin-bottom: 3px; height: 45px; padding-right: 0 !important;">
                                <div class="col-md-12 col-xs-12 borda" style="margin-top: 3px; margin-bottom: 3px;">
                                    <strong>Prezado:
                                    </strong>Evandro Leonardo dos Santos <br> Recebi de {{ $nota[0]['NM_EMPRESA'] }}
                                    os
                                    serviços indicados no RPS ao lado
                                </div>


                                <div class="col-md-3 col-xs-3 borda" style="margin-bottom: 3px; height: 45px"><strong>Data
                                        Recebimento:</strong>
                                </div>
                                <div class="col-md-9 col-xs-9 borda" style="margin-bottom: 3px; height: 45px">
                                    <strong>Identificação e
                                        Assinatura do Recebedor:</strong>
                                </div>
                            </div>
                            <div class="col-md-2 col-xs-2 " style="margin-top: 3px; height: 45px; padding: 0 !important;">
                                <div class="col-md-12 col-xs-12 borda" style="height: 90px">
                                    <strong>Nota: </strong>{{ $nota[0]['NR_NOTA'] }}<br>
                                    <strong>RPS: </strong>{{ $nota[0]['NR_RPS'] }}<br>
                                    <strong>Série: </strong>{{ $nota[0]['CD_SERIE'] }}<br>
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
                                        <div class="col-md-4 col-xs-4 borda" style="padding:3px; height: 40px">
                                            <strong>Numero RPS:
                                            </strong>{{ $nota[0]['NR_RPS'] }}
                                        </div>
                                        <div class="col-md-4 col-xs-4 borda" style="padding:3px; height: 40px">
                                            <strong>Numero Nota:
                                            </strong>{{ $nota[0]['NR_NOTA'] }}
                                        </div>
                                        <div class="col-md-4 col-xs-4 borda" style="padding:3px; height: 40px"><strong>Data
                                                Emissão:
                                            </strong>{{ $nota[0]['DS_DTEMISSAO'] }}</div>
                                        <div class="col-md-12 col-xs-12 borda" style="height: 52px;">
                                            <strong>Codigo de Verificação:</strong>
                                            <div class="codigobarras">
                                                {{-- <svg id="barcode"></svg>
                                                <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
                                                <script>
                                                    document.addEventListener("DOMContentLoaded", function() {
                                                        JsBarcode("#barcode", "{{ $nota[0]['CD_AUTENTICACAO'] }}", {
                                                            format: "CODE128",
                                                            lineColor: "#000",
                                                            width: 2,
                                                            height: 30,
                                                            displayValue: true
                                                        });
                                                    });
                                                </script> --}}
                                                {{-- {!! QrCode::generate($nota[0]['CD_AUTENTICACAO']) !!} --}}
                                                {{ $nota[0]['CD_AUTENTICACAO'] }}
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                {{-- aquiiiiii --}}
                                <div class="col-md-12 col-xs-12">
                                    <div class="title_prestador"> Prestador de Serviços</div>
                                </div>


                                <div class="col-md-12 col-xs-12" style="padding:3px !important">
                                    <div class="col-md-2 col-xs-2 borda" style="padding:3px; height: 92px">
                                        <div class="logo_ivo"><img src="{{ asset('img/logo-ivo.png') }}" alt="IVO RECAP"
                                                style="width:90px; margin: 10px; margin-left: 35px"></div>
                                    </div>
                                    <div class="col-md-10 col-xs-10" style="padding:3px; height: 92px">
                                        <div class="col-md-12 col-xs-12 borda">
                                            <div class="col-md-9 col-xs-9 dados_prestador">
                                                <div><strong>Nome: </strong>{{ $nota[0]['NM_EMPRESA'] }}</div>
                                                <div><strong>CNPJ: </strong>{{ $nota[0]['NR_CNPJINSCEST'] }}</div>
                                                <div><strong>Insc Municipal: </strong>{{ $nota[0]['NR_INSCMUNEMPRESA'] }}
                                                </div>
                                                <div><strong>Endereço:</strong>{{ $nota[0]['DS_ENDERECOEMPRESA'] }}</div>
                                            </div>

                                            <div class="col-md-3 col-xs-3"><strong>Telefone:
                                                </strong>{{ $nota[0]['NR_FONEEMPRESA'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 col-xs-12">
                                    <div class="title_prestador"> Tomador de Serviços</div>
                                </div>
                                <div class="col-md-12 col-xs-12 borda" style="padding:3px !important">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="col-md-5 col-xs-4"><strong>Nome: </strong>{{ $nota[0]['NM_PESSOA'] }}
                                        </div>
                                        <div class="col-md-3 col-xs-4"><strong>CPF/CNPJ:
                                            </strong>{{ $nota[0]['NR_CNPJCPF'] }}
                                        </div>
                                        <div class="col-md-2 col-xs-4"><strong>IE:
                                            </strong>{{ $nota[0]['NR_INSCESTPESSOA'] }}
                                        </div>
                                        <div class="col-md-2 col-xs-4"><strong>Insc. Muncipal:
                                            </strong>{{ $nota[0]['NR_INSCMUN'] }}
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12">
                                        <div class="col-md-6 col-xs-6"><strong>Endereço:
                                            </strong>{{ $nota[0]['DS_ENDERECOPESSOA'] }}</div>
                                        <div class="col-md-4 col-xs-4"><strong>Municipio:
                                            </strong>{{ $nota[0]['DS_MUNPESSOA'] }}
                                        </div>
                                        <div class="col-md-4 col-xs-4"><strong>CEP: </strong>{{ $nota[0]['NR_CEPPESSOA'] }}
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12">
                                        <div class="col-md-6 col-xs-6 "><strong>E-mail: </strong>{{ $nota[0]['DS_EMAIL'] }}
                                        </div>
                                        <div class="col-md-6 col-xs-6 "><strong>Telefone:
                                            </strong>{{ $nota[0]['NR_FONE'] }}
                                        </div>
                                    </div>


                                </div>
                                <div class="col-md-12 col-xs-12 borda">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="col-md-6 col-xs-6"><strong>Forma Pagto:
                                            </strong>{{ $nota[0]['DS_FORMAPAGTO'] }}</div>
                                        <div class="col-md-6 col-xs-6">{{ $nota[0]['DS_CONDPAGTO'] }}</div>
                                    </div>
                                    <div class="col-md-12 col-xs-12"><strong>Parcelas:
                                        </strong>{{ $nota[0]['O_DS_CONDPAGTO'] }}</div>

                                </div>

                                {{-- TABELA --}}
                                <div class="col-md-12 col-xs-12 invoice-col borda" style="height: 600px">
                                    <div class="col-xs-12">
                                        <table class="table table-sm" style="font-size: 14px">
                                            <thead>
                                                <tr>
                                                    <th>Serviço</th>
                                                    <th>Marca</th>
                                                    <th>Serie</th>
                                                    <th>Fogo</th>
                                                    <th>DOT</th>
                                                    <th>Qtde</th>
                                                    <th>Vlr. Unitário</th>
                                                    <th>Dscto</th>
                                                    <th>Vlr. Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($nota as $n)
                                                    <tr>
                                                        <td>{{ $n['O_DS_ITEM'] }}</td>
                                                        <td>{{ $n['O_DS_MARCA'] }}</td>
                                                        <td>{{ $n['O_NR_SERIE'] }}</td>
                                                        <td>{{ $n['O_NR_FOGO'] }}</td>
                                                        <td>{{ $n['O_NR_DOT'] }}</td>
                                                        <td>{{ $n['O_QTDE'] }}</td>
                                                        <td>{{ $n['O_VL_UNITARIO'] }}</td>
                                                        <td>{{ $n['O_QT_DESCONTADA'] }}</td>
                                                        <td>{{ $n['O_VL_TOTAL'] }}</td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 ">
                                    <div class="totais_itens" style="margin-left: 60%">
                                        <strong>Qtde Total: {{ $nota[0]['O_QTDE'] }}</strong>
                                    </div>
                                </div>

                                <div class="col-md-12 col-xs-12 borda">

                                    <div class="col-md-2 col-xs-2 borda" style="margin-right: 10px; margin-top: 10px">
                                        <Strong>Pis: 0,00</Strong>
                                    </div>
                                    <div class="col-md-2 col-xs-2 borda" style="margin: 10px">
                                        <Strong>Cofins: 0,00</Strong>
                                    </div>
                                    <div class="col-md-2 col-xs-2 borda" style="margin: 10px">
                                        <Strong>INSS: 0,00</Strong>
                                    </div>
                                    <div class="col-md-2 col-xs-2 borda" style="margin: 10px">
                                        <Strong>IR: 0,00</Strong>
                                    </div>
                                    <div class="col-md-2 col-xs-2 borda" style="margin: 10px">
                                        <Strong>CSLL: 0,00</Strong>
                                    </div>

                                    <div class="col-md-6 col-xs-6 borda">
                                       <strong>Código dos Serviços: 1404 - Recauchutagem ou regeneração de Pneus </br>
                                        CNAE: 2212-9/00 Reforma de Pneumáticos Usados</strong>
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
