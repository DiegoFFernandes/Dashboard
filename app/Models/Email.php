<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'email',  
        'cd_usuario',  
    ];    

    public function EmailAll(){
     return Email::select('id','email')->get();   
    
    }

    public function VerifyIfExists($email){
        return Email::where('email', $email)->exists();
    }

    public function storeData($input){
        return Email::create($input);
    }

    public function updateData($id, $input){
        return Email::find($id)->update($input);
    }

    public function destroyData($id){
        return Email::find($id)->delete();        
    }

    public function VerifyIfUse(){
        return Email::select('id','email')->whereNotIn('id', function($q){
            $q->select('cd_email')->from('pessoas');
        })->get();
    }

}
