<?php

namespace App\Http\Controllers\Admin\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\AdiantamentoDespesas;
use App\Models\Contas;
use App\Models\Empresa;
use App\Models\PictureComprovanteDespesa;
use Helper;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdiantamentoDespesasViagemController extends Controller
{
    public $request, $user, $empresa, $contas, $despesas, $picture;
    public function __construct(
        Request $request,
        Empresa $empresa,
        AdiantamentoDespesas $despesas,
        Contas $contas,
        PictureComprovanteDespesa $picture
    ) {
        $this->empresa = $empresa;
        $this->request = $request;
        $this->contas = $contas;
        $this->despesas = $despesas;
        $this->picture = $picture;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function index()
    {
        $title_page   = 'Comprovantes de Despesa';
        $user_auth    = $this->user;
        $uri          = $this->request->route()->uri();
        $empresas =  $this->empresa->EmpresaFiscalAll();

        return view('admin.financeiro.despesas-viagem', compact(
            'title_page',
            'user_auth',
            'uri',
            'empresas'
        ));
    }
    public function SaldoDespesa()
    {
        $vl_consumido = $this->despesas->SaldoUtilizado();
        $nr_cnpjcpf = $this->user->nr_cnpjcpf;

        $vl_adiantado_db = $this->contas->SaldoAdiantamento($nr_cnpjcpf);
        if (Helper::is_empty_object($vl_adiantado_db)) {
            $vl_adiantado = 0;
        } else {
            $vl_adiantado = (float) $vl_adiantado_db[0]['VL_SALDO'];
        }
        // Cálculo do valor a devolver
        $vl_devolver = $vl_adiantado - (float) $vl_consumido;

        // Formatação para exibição
        $vl_adiantado_formatado = number_format($vl_adiantado, 2, ',', '.');
        $vl_consumido_formatado = number_format($vl_consumido, 2, ',', '.');
        $vl_devolver_formatado = number_format($vl_devolver, 2, ',', '.');

        // Monta o array com os valores formatados
        $saldo = [
            'vl_adiantado' => $vl_adiantado_formatado,
            'vl_utilizado' => $vl_consumido_formatado,
            'vl_devolver' => $vl_devolver_formatado
        ];

        return response()->json(['saldo' => $saldo]);
    }
    public function StoreVlConsumido()
    {
        $this->request['cd_comprovante'] = 0;
        $input = self::__validate($this->request);

        if ($input->fails()) {
            $error = '<ul>';

            foreach ($input->errors()->all() as $e) {
                $error .= '<li>' . $e . '</li>';
            }
            $error .= '</ul>';

            return response()->json([
                'error' => $error
            ]);
        }
        try {
            //insere a despesa na tabela adiantamento_despesas
            $despesa = $this->despesas->storeDespesasConsumidas($input->validated());

            //insere as imagens na tabela pictures_despesas
            if ($this->request->has('pictures')) {
                foreach ($this->request->pictures as $r) {
                    $img = $r;
                    $folderPath = "comprovante_despesas/";

                    $image_parts = explode(";base64,", $img);
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];

                    $image_base64 = base64_decode($image_parts[1]);
                    $fileName = uniqid() . '.png';
                    $file = $folderPath . $fileName;

                    $picture = new PictureComprovanteDespesa();
                    $picture->cd_adiantamento = $despesa->id;
                    $picture->path = $file;
                    $picture->save();
                    Storage::put($file, $image_base64);
                }
            }
            return response()->json(['success' => 'Despesa incluida com sucesso!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'houve algum erro contate setor de TI!']);
        }
    }
    public function __validate()
    {
        return Validator::make(
            $this->request->all(),
            [
                'cd_comprovante' => ['integer'],
                'tp_despesa' => ['required', Rule::in(['C', 'A', 'O', 'H'])],
                'vl_consumido' => ['required', 'numeric'],
                'update_image' => [Rule::in(['0', '1'])],
                'ds_observacao' => 'max:2400'
            ],
            [
                'tp_despesa.required' => 'Tipo Despesa deve ser preechida!',
                'vl_consumido.required' => 'Valor Consumido deve ser preenchido!'
            ]
        );
    }
    public function listComprovantes()
    {
        if ($this->user->hasRole(['admin', 'controladoria'])) {
            $user = 1;
        } else {
            $user = 0;
        }
        $data = $this->despesas->listData($user);

        return DataTables::of($data)
            ->addColumn('actions', function ($d) {
                $buttons = "";
                if ($d->visto == 'N') {
                    if ($this->user->hasRole(['admin', 'controladoria'])) {
                        $buttons = '<button type="button" class="btn btn-primary btn-xs" id="visto-comprovante" data-id="' . $d->cd_adiantamento . '">Visto</button>';
                    }
                    $buttons .= ' <button type="button" class="btn btn-danger btn-xs" data-id="' . $d->cd_adiantamento . '" data-toggle="modal" data-target="#DeleteComprovante" id="getDeleteId">Excluir</button>';
                    $buttons .= ' <button type="button" class="btn btn-warning btn-xs" id="edit-comprovante" data-id="' . $d->cd_adiantamento . '">Editar</button>';
                }
                $buttons .= ' <button type="button" class="btn btn-success btn-xs" id="fotos-comprovante" data-id="' . $d->cd_adiantamento . '">Fotos</button>';
                return $buttons;
            })
            ->rawColumns(['actions'])
            ->make();
    }
    public function delete()
    {
        try {
            $data = $this->despesas::where('cd_adiantamento', $this->request->id)->firstOrFail();
            $this->despesas->DestroyData($data->cd_adiantamento);
            return response()->json(['success' => 'Comprovante deletado com sucesso!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Houve algum erro favor contactar setor de TI.']);
        }
    }
    public function vistoComprovante()
    {
        try {
            $data = $this->despesas::where('cd_adiantamento', $this->request->id)->firstOrFail();
            $this->despesas->updateVisto($data->cd_adiantamento);
            return response()->json(['success' => 'Comprovante visto com sucesso!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Houve algum erro favor contactar setor de TI.']);
        }
    }
    public function viewPictures()
    {
        $pictures = PictureComprovanteDespesa::where('cd_adiantamento', $this->request->id)->get();
        $html = '<div class="col-sm-12" id="pictures-img">';

        foreach ($pictures as $picture) {
            $html .= '<img class="img-responsive" src="' . asset('storage/' . $picture->path) . '" alt="Photo"> <br> ';
        }
        $html .= '</div>';

        return response()->json(['html' => $html]);
    }
    public function UpdateVlConsumido()
    {
        $item = $this->despesas::where('cd_adiantamento', $this->request->cd_comprovante)->firstOrFail();
        
        
        $validator = $this->__validate();
        $validate = $validator->validated();

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        if ($validate['update_image'] == 1) {
            $pictures = $this->picture->listPictures($item->cd_adiantamento);
            foreach ($pictures as $p) {
                if (Storage::exists($p->path)) {
                    Storage::delete($p->path);
                }
            }
            $this->picture->DestroyPicture($item->cd_adiantamento);

            if ($this->request->has('pictures')) {
                foreach ($this->request->pictures as $r) {
                    $img = $r;
                    $folderPath = "comprovante_despesas/";

                    $image_parts = explode(";base64,", $img);
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];

                    $image_base64 = base64_decode($image_parts[1]);
                    $fileName = uniqid() . '.png';
                    $file = $folderPath . $fileName;

                    $picture = new PictureComprovanteDespesa();
                    $picture->cd_adiantamento = $item->cd_adiantamento;
                    $picture->path = $file;
                    $picture->save();
                    Storage::put($file, $image_base64);
                }
            }
        }
        $input = $validator->validated();       

       $input['ds_observacao']= $item['ds_observacao'].' / '. $this->request->ds_observacao;

        $this->despesas->updateData($input);

        return response()->json(['success' => 'Item da análise, ' . $this->request->id . ', atualizado com sucesso!']);
    }
}
