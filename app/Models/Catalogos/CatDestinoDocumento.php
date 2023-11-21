<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatDestinoDocumento extends Model
{
    use HasFactory;

    protected $table = 'tab_cat_destino_documento';

    protected $primaryKey = 'n_id_tipo_destino';
    public $timestamps = false;
    protected $fillable = ['n_id_tipo_destino','desc_destino_documento'];
}
