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
}
