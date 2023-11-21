<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatExpedientes extends Model
{
    use HasFactory;

    protected $table = 'tab_expedientes';
    protected $primaryKey = 'n_num_expediente';
    public $timestamps = false;
    protected $fillable = [
        's_num_expediente',
        'n_num_expediente',
        's_descripcion',
        'n_id_usuario_creador'
    ];
}
