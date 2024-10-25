<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PictureComprovanteDespesa extends Model
{
    use HasFactory;
    protected $table = 'picture_despesas';
    protected $fillable = [
        'cd_adiantamento',
        'path',
        'created_at',
        'updated_at'
    ];

    public function listPictures($id){
        return PictureComprovanteDespesa::where('cd_adiantamento', $id)->get();
    }

    public function DestroyPicture($id){
        return PictureComprovanteDespesa::where('cd_adiantamento', $id)->delete();
    }

    
}
