@extends('admin.master.simple')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box borda">
                    <div class="box-body">
                        <div class="col-md-12 col-xs-12" style="height: 100px; padding-left :0 !important ">
                            <div class="col-md-10 col-xs-10"
                                style="margin-bottom: 3px; height: 45px; padding-right: 0 !important;">
                                <div class="col-md-12 col-xs-12 borda" style="margin-top: 3px; margin-bottom: 3px;">
                                    <strong>Prezado:
                                    </strong>{{ $nota[0]['NM_PESSOA'] }} <br> Recebi de {{ $nota[0]['NM_EMPRESA'] }}
                                    os serviços indicados no RPS ao lado
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
                                    <strong>Nota: </strong>{{ $nota[0]['NR_DOCUMENTO'] }}<br>
                                    <strong>RPS: </strong>{{ $nota[0]['NR_RPS'] }}<br>
                                    <strong>Série: </strong>{{ $nota[0]['CD_SERIE'] }}<br>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <hr size="1" style="border:1px dashed; margin: 10px; margin-top: 10px;">
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <div class="" style=" padding:0">
                                <div class="col-md-12 col-xs-12" style="height: 100px; padding:3px !important ">
                                    <div class="col-md-2 col-xs-2 borda" style="padding:3px; height: 130px">
                                        <div
                                            style="display:flex; justify-content: center; align-items: center; height: 100%;">
                                            <img src="
                                                    @if ($nota[0]['CD_EMPRESA'] == 101) {{ asset('img/logos-empresa/brasaopvai.png') }}
                                                        @elseif($nota[0]['CD_EMPRESA'] == 108)
                                                            {{ asset('img/logos-empresa/brasaocampina.jpg') }} 
                                                        @elseif($nota[0]['CD_EMPRESA'] == 104)
                                                            {{ asset('img/logos-empresa/brasaoassis.jpg') }} 
                                                        @elseif($nota[0]['CD_EMPRESA'] == 102)
                                                            {{ asset('img/logos-empresa/brasaodourados.jpg') }}      
                                                        @elseif($nota[0]['CD_EMPRESA'] == 103)
                                                            {{ asset('img/logos-empresa/brasaoapucarana.jpg') }} 
                                                        @elseif($nota[0]['CD_EMPRESA'] == 109)
                                                            {{ asset('img/logos-empresa/brasaovitorino.jpg') }} @endif"
                                                class="img-fluid"
                                                style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-xs-4 borda" style="padding:3px; height: 130px">
                                        <div class="title_nfse">
                                            <p>Nota Fiscal de Serviço Eletrônica</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-6" style="padding:0px; height: 130px">
                                        <div class="col-md-4 col-xs-4 borda" style="padding:3px; height: 50px">
                                            <strong>Numero RPS:
                                            </strong>{{ $nota[0]['NR_RPS'] }}
                                        </div>
                                        <div class="col-md-4 col-xs-4 borda" style="padding:3px; height: 50px">
                                            <strong>Numero Nota:
                                            </strong>{{ $nota[0]['NR_NOTA'] }}
                                        </div>
                                        <div class="col-md-4 col-xs-4 borda" style="padding:3px; height: 50px"><strong>Data
                                                Emissão:
                                            </strong>{{ $nota[0]['DS_DTEMISSAO'] }}</div>
                                        <div class="col-md-12 col-xs-12 borda"
                                            style="height: 79px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                                            <strong>Codigo de Verificação: </strong>
                                            <div
                                                style="width: 100%; display: flex; justify-content: center; align-items: center;">
                                                {!! DNS1D::getBarcodeHTML($nota[0]['CD_AUTENTICACAO'], 'C128', 1, 33, 'black') !!}
                                            </div>
                                            <div
                                                style="width: 100%; display: flex; justify-content: center; align-items: center; font-size: 12px;">
                                                {{ $nota[0]['CD_AUTENTICACAO'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 col-xs-12 align-items-center"
                                    style="margin-top: 15px; text-align: center;">
                                    <div class="title_prestador"> Prestador de Serviços</div>
                                </div>


                                <div class="col-md-12 col-xs-12" style="padding:3px !important">
                                    <div class="col-md-2 col-xs-2 borda" style="padding:3px; height: 120px">
                                        <div style="display:flex; justify-content: center; align-items: center">
                                            <img src="{{ asset('img/logo-ivo.png') }}" class="img-fluid" style="">
                                        </div>
                                    </div>
                                    <div class="col-md-10 col-xs-10 borda" style="padding:3px; height: 120px">
                                        <div class="col-md-12 col-xs-12">
                                            <div class="col-md-8 col-xs-8 dados_prestador">
                                                <div><strong>Nome: </strong>{{ $nota[0]['NM_EMPRESA'] }}</div>
                                                <div><strong>CNPJ: </strong>{{ $nota[0]['NR_CNPJINSCEST'] }}</div>
                                                <div><strong>Insc Municipal: </strong>{{ $nota[0]['NR_INSCMUNEMPRESA'] }}
                                                </div>
                                                <div><strong>Endereço: </strong>{{ $nota[0]['DS_ENDERECOEMPRESA'] }}</div>
                                            </div>

                                            <div class="col-md-4 col-xs-4"><strong>Telefone:
                                                </strong>{{ $nota[0]['NR_FONEEMPRESA'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 col-xs-12">
                                    <div class="title_prestador align-items-center"
                                        style="margin-top: 0px; text-align: center;"> Tomador de Serviços</div>
                                </div>

                                <div class="col-md-12 col-xs-12 borda" style="padding:3px !important">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="col-md-8 col-xs-6"><strong>Nome: </strong>{{ $nota[0]['NM_PESSOA'] }}
                                        </div>
                                        <div class="col-md-4 col-xs-4"><strong>CPF/CNPJ:
                                            </strong>{{ $nota[0]['NR_CNPJCPF'] }}</div>
                                        <div class="col-md-1 col-xs-6"><strong>IE:
                                            </strong>{{ $nota[0]['NR_INSCESTPESSOA'] }}</div>
                                        <div class="col-md-1 col-xs-4"><strong>Insc. Muncipal:
                                            </strong>{{ $nota[0]['NR_INSCMUN'] }}</div>
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
                                <div class="col-md-12 col-xs-12 borda" style="height: 70px">
                                    <div class="col-md-6 col-xs-6" style="padding-top: 10px">
                                        <strong>Forma Pagto: </strong>{{ $nota[0]['DS_FORMAPAGTO'] }}
                                    </div>
                                    <div class="col-md-6 col-xs-6" style="padding-top: 10px">{{ $nota[0]['DS_CONDPAGTO'] }}
                                    </div>
                                    <div class="col-md-12 col-xs-12">
                                        <strong>Parcelas: </strong>{{ $nota[0]['O_DS_CONDPAGTO'] }}
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 borda" style="height: auto">
                                    <table class="table" style="font-size: 12px; width: 100%; border-collapse: collapse;">
                                        <thead>
                                            <tr style="">
                                                <th>Serviço</th>
                                                <th>Marca</th>
                                                <th>Serie</th>
                                                <th>Fogo</th>
                                                <th>DOT</th>
                                                <th>Qtde</th>
                                                <th>Vlr. Unitário</th>
                                                <th>Descto</th>
                                                <th>Vlr. Total</th>
                                            </tr>
                                        </thead>
                                        <tbody style="padding: 30px">
                                            @php
                                                $VL_TOTAL = 0;
                                                $QT_TOTAL = 0;
                                            @endphp
                                            @foreach ($nota[0]['ITEMS'] as $index => $n)
                                                <tr>
                                                    <td
                                                        style="padding-left: {{ $n['CD_SUBGRUPO'] == 304 ? '15px' : '5px' }};">
                                                        {{ $n['O_DS_ITEM'] }}</td>
                                                    <td>{{ $n['O_DS_MARCA'] }}</td>
                                                    <td>{{ $n['O_NR_SERIE'] }}</td>
                                                    <td>{{ $n['O_NR_FOGO'] }}</td>
                                                    <td>{{ $n['O_NR_DOT'] }}</td>
                                                    <td>{{ $n['O_QTDE'] }}</td>
                                                    <td>{{ number_format($n['O_VL_UNITARIO'], 2, ',', '.') }}</td>
                                                    <td>{{ $n['O_QT_DESCONTADA'] }}</td>
                                                    <td>{{ number_format($n['O_VL_TOTAL'], 2, ',', '.') }}</td>
                                                </tr>
                                                @php
                                                    $VL_TOTAL += $n['O_VL_TOTAL'];
                                                    if ($n['CD_SUBGRUPO'] == 304) {
                                                        $QT_TOTAL += 0;
                                                    } else {
                                                        $QT_TOTAL += $n['O_QTDE'];
                                                    }
                                                @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12 col-xs-12 ">
                                    <div class="totais_itens" style="margin-left: 60%">
                                        <strong>Qtde Total: {{ $QT_TOTAL }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 borda totais">
                                    <div class="col-md-12 col-xs-12">

                                        <div class="col-md-2 col-xs-2 borda"
                                            style="margin-top: 10px; margin-bottom: 1px; height:45px">
                                            <strong>Outros: 0,00</strong>
                                        </div>
                                        <div class="col-md-2 col-xs-2 borda"
                                            style="margin-top: 10px; margin-bottom: 1px; height:45px">
                                            <strong>Pis: 0,00</strong>
                                        </div>
                                        <div class="col-md-2 col-xs-2 borda"
                                            style="margin-top: 10px; margin-bottom: 1px; height:45px">
                                            <strong>Cofins: 0,00</strong>
                                        </div>
                                        <div class="col-md-2 col-xs-2 borda"
                                            style="margin-top: 10px; margin-bottom: 1px; height:45px">
                                            <strong>INSS: 0,00</strong>
                                        </div>
                                        <div class="col-md-2 col-xs-2 borda"
                                            style=" margin-top: 10px; margin-bottom: 1px; height:45px">
                                            <strong>IR: 0,00</strong>
                                        </div>
                                        <div class="col-md-2 col-xs-2 borda"
                                            style=" margin-top: 10px; margin-bottom: 1px; height:45px">
                                            <strong>CSLL: 0,00</strong>
                                        </div>
                                    </div>
                                    {{--  --}}
                                    <div class="col-md-12 col-xs-12">
                                        <div class="col-md-6 col-xs-6 borda" style="margin-bottom: 1px; height: 70px;">
                                            <strong>Código dos Serviços: 1404 - Recauchutagem ou regeneração de Pneus </br>
                                                CNAE: 2212-9/00 Reforma de Pneumáticos Usados</strong>
                                        </div>
                                        <div class="col-md-3 col-xs-3 borda" style="height: 70px;">
                                            <strong>Descto Incondicionado: 0,00</strong> {{-- inserir campo na query --}}
                                        </div>
                                        <div class="col-md-3 col-xs-3 borda" style="height: 70px;">
                                            <strong>Valor Total da Nota:
                                                {{ number_format($VL_TOTAL, 2, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                    {{--  --}}
                                    <div class="col-md-12 col-xs-12">
                                        <div class="col-md-2 col-xs-2 borda" style="margin-bottom: 10px; height:60px">
                                            <strong>Total Deduções: 0,00</strong> {{-- inserir campo na query --}}
                                        </div>
                                        <div class="col-md-2 col-xs-2 borda" style=" margin-bottom: 10px; height:60px">
                                            <strong>Base Calculo:
                                                {{ number_format($nota[0]['VL_CONTABIL'], 2, ',', '.') }}</strong>
                                        </div>
                                        <div class="col-md-2 col-xs-2 borda" style=" margin-bottom: 10px; height:60px">
                                            <strong>Aliquota:
                                                @if ($nota[0]['CD_EMPRESA'] == 101)
                                                    5%
                                                @elseif($nota[0]['CD_EMPRESA'] == 108)
                                                    5%
                                                @elseif($nota[0]['CD_EMPRESA'] == 104)
                                                    3,5%
                                                @elseif($nota[0]['CD_EMPRESA'] == 102)
                                                    5%
                                                @elseif($nota[0]['CD_EMPRESA'] == 103)
                                                    2,5%
                                                @elseif($nota[0]['CD_EMPRESA'] == 109)
                                                    5%
                                                @else
                                                    5%
                                                @endif
                                            </strong> {{-- inserir campo na query --}}
                                        </div>
                                        <div class="col-md-2 col-xs-2 borda" style=" margin-bottom: 10px; height:60px">
                                            <strong>Valor ISS:
                                                {{ number_format($nota[0]['VL_ISSQN'], 2, ',', '.') }}</strong>
                                        </div>
                                        <div class="col-md-2 col-xs-2 borda" style=" margin-bottom: 10px; height:60px">
                                            <strong>ISS Retido:
                                                {{ number_format($nota[0]['VL_ISSQN_RETIDO'], 2, ',', '.') }}</strong>
                                        </div>
                                        <div class="col-md-2 col-xs-2 borda" style=" margin-bottom: 10px; height:60px">
                                            <strong>Valor Liquido:
                                                {{ number_format($nota[0]['VL_CONTABIL'], 2, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 borda" style="height: 200px">
                                    <strong>{{ $nota[0]['DS_OBSNOTA'] }}</strong>
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
@endsection
