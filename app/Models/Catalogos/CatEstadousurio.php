<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatEstadousurio extends Model
{
    use HasFactory;

    protected $table = 'seg_cat_estado_usuario';
    public $timestamps = false;
    protected $primaryKey = 'n_id_estado_usuario';
    protected $fillable = ['n_id_estado_usuario','s_descripcion'];
}
