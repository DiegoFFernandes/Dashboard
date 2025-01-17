<?php

namespace App\Http\Controllers\PowerBi;

use App\Http\Controllers\Controller;
use App\Models\Model_has_Permission;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PowerbiHelper;

use function PHPUnit\Framework\isNull;

class PowerBiEmbeddedController extends Controller
{
    public $request, $user, $permission;
    public function __construct(
        Request $request,
        Model_has_Permission $permission,

    ) {
        $this->request = $request;
        $this->permission = $permission;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index($id, $updatetoken)
    {
        $regiao = decrypt($id);
        $permissions = $this->user->getAllPermissions();
        $permissionPower = [];
        foreach ($permissions as $p) {
            if ($p->tp_permission == 'powerbi') {
                $permissionPower[] = $p->name;
            }
        }
        if (Helper::is_empty_object($permissionPower)) {
            return redirect()->route('admin.dashboard')->with('warning', 'Você não tem modulo de acesso para essa tela, entrar em contato com a TI');
        }

        $office360token = PowerbiHelper::getOffice360AccessToken();

        $groupID = env('GROUP_ID');
        if ($regiao == 'rede') {
            $reportID = env('REPORT_ID_REDE');
            $datasetID = env('DATASET_ID_REDE');
        } else {
            $reportID = env('REPORT_ID_SUL');
            $datasetID = env('DATASET_ID_SUL');
        }

        if (is_null($office360token)) {
            return redirect()->route('admin.dashboard')->with('warning', 'Houve algum problema ao carregar o token');
        }

        $content = $this->initToken($groupID, $reportID, $office360token, $permissionPower, $datasetID);

        if ($updatetoken == 1) {
            return response()->json($content);
        }

        $title_page   = 'Ivorecap - Rede';
        $user_auth    = $this->user;
        $exploder     = explode("/", $this->request->route()->uri());
        $uri = $exploder[0] . "/" . $exploder[1];
        return view(
            'admin.diretoria.diretoria-rede',
            compact(
                'content',
                'title_page',
                'uri',
                'groupID',
                'reportID',
                'user_auth',
                'title_page',
                'uri'

            )
        );
    }

    public function initToken($groupID, $reportID, $office360token, $permissionPower, $datasetID)
    {
        empty($this->user['email']) ? $username = env('POWERBI_USERNAME') : $username = $this->user['email'];

        $url = 'https://api.powerbi.com/v1.0/myorg/groups/%s/reports/%s/GenerateToken';
        $url = sprintf($url, $groupID, $reportID);
        // no workspace escolher o relatorio e pagar na url 
        //https://app.powerbi.com/groups/04d511b0-ded9-4952-9708-dfa331d1ee83/reports/8ee36fa6-5b54-49d4-8e66-713791f7fd3e/ReportSectiondecccb8482fa6cf4f797

        $permissionPower = json_encode($permissionPower);
        $reportID = env('REPORT_ID_REDE');
        $datasetID = env('DATASET_ID_REDE');

        $header = [
            "Authorization:{$office360token->token_type} {$office360token->access_token}",
            "content-type: application/json",
        ];
        $data = '{
                "accessLevel": "View",
                "allowSaveAs": "false",
                "identities": [{
                    "username": "' . $username . '",
                    "roles": ' . $permissionPower . ',
                    "datasets": ["' . $datasetID . '"]
                }]
            }';
        return PowerbiHelper::processPowerbiHttpRequest($url, $header, $data, 'POST');
    }

    public function indicadorInadimplencia()
    {
        $title_page   = 'Inadimplência - Rede';
        $user_auth    = $this->user;
        $exploder     = explode("/", $this->request->route()->uri());
        $uri = $exploder[0] . "/" . $exploder[1];

        $groupID = env('GROUP_ID');
        $reportID = env('REPORT_ID_REDE');
        $datasetID = env('DATASET_ID_REDE');
        $permissionPower = ['Diretoria'];

        $office360token = PowerbiHelper::getOffice360AccessToken();

        $content = $this->initToken($groupID, $reportID, $office360token, $permissionPower, $datasetID);


        return view(
            'admin.diretoria.inadimplencia',
            compact(
                'content',
                'title_page',
                'uri',
                'groupID',
                'reportID',
                'user_auth',
                'title_page',
                'uri'
            )
        );
    }

    public function updateTablesPowerBi()
    {
        $table = $this->request->table;
        $objects = Helper::VerifyTablesPowerBi($table);

        $response = PowerbiHelper::updateTablePowerBi($objects);

        return PowerbiHelper::handleResponse($response, 'admin.dashboard', 'admin.dashboard');
    }
}
