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

    public function index(UserServices $userServices)
    {
        $title_page   = 'Item Análise de Frota';
        $user_auth    = $this->user;
        $uri         = $this->request->route()->uri();
        // $uri          = $exploder[0] . '/' . $exploder[1];



         return $data = $this->nota->NotasEmitidas();

        $groupedData = array_values(array_reduce($data, function ($carry, $item) {
            $nrLancamento = $item['NR_LANCAMENTO'];

            if (!isset($carry[$nrLancamento])) {
                $carry[$nrLancamento] = [
                    'CD_EMPRESA' => $item['CD_EMPRESA'],
                    'NR_LANCAMENTO' => $item['NR_LANCAMENTO'],
                    'TP_NOTA' => $item['TP_NOTA'],
                    'CD_SERIE' => $item['CD_SERIE'],
                    'NM_USUARIO' => $item['NM_USUARIO'],                    
                    'ITEMS' => []
                ];
            }

            $carry[$nrLancamento]['ITEMS']['O_DS_ITEM'] = $item['O_DS_ITEM'];
            $carry[$nrLancamento]['ITEMS']['O_VL_UNITARIO'] = $item['O_VL_UNITARIO'];
            $carry[$nrLancamento]['ITEMS']['O_NR_DOT'] = $item['O_NR_DOT'];
            $carry[$nrLancamento]['ITEMS']['O_NR_SERIE'] = $item['O_NR_SERIE'];
            $carry[$nrLancamento]['ITEMS']['O_QT_DESCONTADA']  = $item['O_QT_DESCONTADA'];

            return $carry;
        }, []));

        return $groupedData;

        return view('admin.teste.index', compact(
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
