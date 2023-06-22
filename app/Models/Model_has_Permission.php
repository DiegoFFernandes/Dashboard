<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Model_has_Permission extends Model
{
    use HasFactory;
    protected $table = 'model_has_permissions';

    public function AllPermissionsGetUSer($id){
        return Model_has_Permission::select('p.name', 'p.tp_permission')
        ->join('permissions as p', 'p.id', 'model_has_permissions.permission_id')
        ->where('model_has_permissions.model_id', $id)
        ->get();
    }
}
