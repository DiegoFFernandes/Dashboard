<?php

namespace App\Http\Controllers\Admin\Digisac;

use App\Http\Controllers\Controller;
use App\Models\Nota;
use App\Models\Pessoa;
use App\Services\UserServices;
use Digisac;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DigiSacController extends Controller
{
    public $pessoa, $nota, $user, $request;

    public function __construct(
        Request $request,
        Pessoa $pessoa,
        Nota $nota
    ) {
        $this->pessoa = $pessoa;
        $this->nota = $nota;
        $this->request = $request;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function notafiscal(UserServices $userServices)
    {
        $title_page   = 'Nota Fiscal de serviço';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();
        // $uri          = $exploder[0] . '/' . $exploder[1];



          $nota = $this->nota->NotasEmitidas();

        //  $nota_agrupado
    //     return $nota = array_values(array_reduce($data, function ($carry, $item) {
    //         $nrLancamento = $item['NR_LANCAMENTO'];

    //         if (!isset($carry[$nrLancamento])) {
    //             $carry[$nrLancamento] = [
    //                 'CD_EMPRESA' => $item['CD_EMPRESA'],
    //                 'NR_LANCAMENTO' => $item['NR_LANCAMENTO'],
    //                 'NM_EMPRESA' => $item['NM_EMPRESA'],
    //                 'NR_CNPJINSCEST' => $item['NR_CNPJINSCEST'],
    //                 'NR_INSCMUNEMPRESA' => $item['NR_INSCMUNEMPRESA'],
    //                 'DS_ENDERECOEMPRESA' => $item['DS_ENDERECOEMPRESA'],
    //                 'NR_FONEEMPRESA' => $item['NR_FONEEMPRESA'],
    //                 'NR_CEPEMPRESA' => $item['NR_CEPEMPRESA'],
    //                 'TP_NOTA' => $item['TP_NOTA'],
    //                 'CD_SERIE' => $item['CD_SERIE'],
    //                 'NR_NOTA' => $item['NR_NOTA'],
    //                 'NR_RPS' => $item['NR_RPS'],
    //                 'CD_AUTENTICACAO' => $item['CD_AUTENTICACAO'],
    //                 'DS_DTEMISSAO' => $item['DS_DTEMISSAO'],
    //                 'NM_USUARIO' => $item['NM_USUARIO'],
    //                 'NM_PESSOA' => $item['NM_PESSOA'],
    //                 'NR_CNPJCPF' => $item['NR_CNPJCPF'],
    //                 'DS_ENDERECOPESSOA' => $item['DS_ENDERECOPESSOA'],
    //                 'DS_MUNPESSOA' => $item['DS_MUNPESSOA'],
    //                 'NR_CEPPESSOA' => $item['NR_CEPPESSOA'],
    //                 'DS_EMAIL' => $item['DS_EMAIL'],
    //                 'NR_FONE' => $item['NR_FONE'],
    //                 'NR_INSCESTPESSOA' => $item['NR_INSCESTPESSOA'],
    //                 'NR_INSCMUN' => $item['NR_INSCMUN'],
    //                 'O_DS_CONDPAGTO' => $item['O_DS_CONDPAGTO'],
    //                 'DS_FORMAPAGTO' => $item['DS_FORMAPAGTO'],
    //                 'DS_CONDPAGTO' => $item['DS_CONDPAGTO'],
    //                 'ITEMS' => []
    //             ];
    //         }

    //         $carry[$nrLancamento]['ITEMS']['O_DS_ITEM'] = $item['O_DS_ITEM'];
    //         $carry[$nrLancamento]['ITEMS']['O_VL_UNITARIO'] = $item['O_VL_UNITARIO'];
    //         $carry[$nrLancamento]['ITEMS']['O_NR_DOT'] = $item['O_NR_DOT'];
    //         $carry[$nrLancamento]['ITEMS']['O_NR_SERIE'] = $item['O_NR_SERIE'];
    //         $carry[$nrLancamento]['ITEMS']['O_QT_DESCONTADA']  = $item['O_QT_DESCONTADA'];
    //         $carry[$nrLancamento]['ITEMS']['O_DS_MARCA']  = $item['O_DS_MARCA'];
    //         $carry[$nrLancamento]['ITEMS']['O_NR_FOGO']  = $item['O_NR_FOGO'];
    //         $carry[$nrLancamento]['ITEMS']['O_QTDE']  = $item['O_QTDE'];
    //         $carry[$nrLancamento]['ITEMS']['O_VL_TOTAL']  = $item['O_VL_TOTAL'];

    //         return $carry;
    //     }, []));


    //    $nota; //= $nota_agrupado[0];

        return view('admin.nota_boleto.notafiscal', compact(
            'nota',
            'uri',
            'user_auth',
            'title_page'
        ));
        // $pessoa = $this->pessoa::findPessoa();
        // $oauth = Digisac::OAuthToken();
        // $contact = json_decode(Digisac::AddContact($oauth, $pessoa), true);
        // $pessoa[0]->ID_USER = $contact['id'];


        // //  $list =  json_decode(Digisac::ListContacts($oauth), true);


        // // foreach ($list['data'] as $l) {
        // //     echo $l['name'] . '</br>';
        // //     echo 'contactId: ' . $l['id'] . '</br>';
        // //     echo $l['data']['number'] . '</br>';
        // //     echo '----------' . '<br>';
        // // }
        // // Digisac::SendMessage($oauth);
        // // Digisac::ContactExists($oauth, $pessoa[0]->NR_CELULAR);
        // $nr_celular_corrigido = Helper::RemoveSpecialChar($pessoa[0]->NR_CELULAR);
        // $pessoa[0]->NR_CELULAR = $nr_celular_corrigido;

        // $create = $userServices->create($pessoa);
        // if ($create == 1) {
        //     return redirect()->route('admin.usuarios.listar')->with('message', 'Usuário criado com sucesso!');
        // } elseif ($create == 3) {
        //     return redirect()->route('admin.usuarios.listar')->with('warning', 'Email já existe, favor cadastrar outro!');
        // };
    }
    public function StoreListContacts()
    {
    }
}
