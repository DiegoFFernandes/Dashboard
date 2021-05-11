<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Empresa extends Model
{
 use HasFactory;

 public function empresa()
 {
  $sql = "SELECT CD_EMPRESA, NM_EMPRESA FROM EMPRESA";
  return DB::connection('firebird')->select($sql);
 }
}
