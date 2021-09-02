<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;
    protected $table = 'pessoas';
    protected $fillable = [
        'cd_empresa',
        'name',
        'cpf',
        'cd_email',
        'phone',
        'endereco',
        'numero',
        'cd_usuario'
    ];

    public function PessoasAll()
    {
        return Pessoa::select('pessoas.id', 'pessoas.cd_empresa', 'pessoas.name', 'pessoas.phone' ,'pessoas.cpf', 
        'pessoas.cd_email', 'emails.email', 'pessoas.endereco', 'pessoas.numero')
            ->join('emails', 'emails.id', 'pessoas.cd_email')
            ->orderBy('pessoas.id')
            ->get();
    }

    public function verifyIfExists($cpf){
        return Pessoa::where('cpf', $cpf)            
            ->exists();
    }

    public function storeData($input)
    { 
    	return Pessoa::create($input);
    }

    public function updateData($id, $input){
        return Pessoa::find($id)->update($input);
    }

    public function destroyData($id){
        return Pessoa::find($id)->delete();        
    }

    public function findEmail($inputEmail){
        return Pessoa::where('cd_email', $inputEmail)->exists();
    }
}
