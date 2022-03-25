<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AreaComercial extends Model
{
    use HasFactory;
    protected $table = 'area_comercial';
    protected $connection;
    protected $fillable = [
        'cd_usuario',
        'cd_areacomercial',
        'ds_areacomercial',
        'cd_cadusuario'
    ];

    public function __construct()
    {
        $this->connection = 'mysql';
    }
    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }
    public function areaAll()
    {
        $query = "select ac.cd_areacomercial, cast(ac.ds_areacomercial as varchar(40) character set utf8) ds_areacomercial
        from areacomercial ac";
        $key = "area_comercial". Auth::user()->id;

        return Cache::remember($key, now()->addMinutes(60), function () use($query){
            return DB::connection($this->setConnet())->select($query);
        });        
    }
    public function showUserArea($cd_empresa)
    {
        $this->connection = 'mysql';
        return AreaComercial::select('area_comercial.id', 'users.id as cd_usuario', 'users.name', 'area_comercial.cd_areacomercial', 'area_comercial.ds_areacomercial')
            ->join('users', 'users.id', 'area_comercial.cd_usuario')
            ->whereIn('users.empresa', $cd_empresa)
            ->orderBy('users.name')
            ->get();
    }
    public function verifyIfExists($input)
    {
        $this->connection = 'mysql';
        return AreaComercial::where('cd_usuario', $input['cd_usuario'])
            ->where('cd_areacomercial', $input['cd_areacomercial'])
            ->exists();
    }
    public function verifyIfExistsArea($cd_areacomercial, $cd_empresa)
    {
        $this->connection = 'mysql';
        return AreaComercial::join('users', 'users.id', 'area_comercial.cd_usuario')
            ->where('cd_areacomercial', $cd_areacomercial)
            ->whereIn('users.empresa', $cd_empresa)
            ->exists();
    }
    public function verifyIfExistsUser($cd_usuario, $cd_empresa)
    {
        $this->connection = 'mysql';
        return AreaComercial::join('users', 'users.id', 'area_comercial.cd_usuario')
            ->where('area_comercial.cd_usuario', $cd_usuario)
            ->whereIn('users.empresa', $cd_empresa)
            ->exists();
    }
    public function storeData($input)
    {
        $this->connection = 'mysql';
        //return $input;
        return AreaComercial::insert([
            'cd_usuario' => $input['cd_usuario'],
            'cd_areacomercial' => $input['cd_areacomercial'],
            'ds_areacomercial' => $input['ds_areacomercial'],
            'cd_cadusuario' => $input['cd_cadusuario'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    public function updateData($input)
    {
        AreaComercial::find($input->id)
            ->update([
                'cd_usuario' => $input->cd_usuario,
                'cd_areacomercial' => $input->areacomercial,
                'ds_areacomercial' => $input->areacomercial,
                'updated_at' => now(),
            ]);
        return response()->json(['success' => 'Região atualizada para usúario!']);
    }
    public function destroyData($id)
    {
        return AreaComercial::find($id)->delete();
    }
    public function findAreaUser($cd_usuario)
    {
        $this->connection = 'mysql';
        return AreaComercial::select('cd_areacomercial')->where('cd_usuario', $cd_usuario)->get();
    }
}
