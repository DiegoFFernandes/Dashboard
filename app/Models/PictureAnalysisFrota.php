<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PictureAnalysisFrota extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_item_analysis',
        'path',
        'created_at',
        'updated_at'
    ];
    public function listPictures($id){
        return PictureAnalysisFrota::where('id_item_analysis', $id)->get();
    }

    public function DestroyPicture($id){
        return PictureAnalysisFrota::where('id_item_analysis', $id)->delete();
    }
}
