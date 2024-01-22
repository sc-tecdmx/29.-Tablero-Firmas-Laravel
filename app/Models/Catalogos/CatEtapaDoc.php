<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatEtapaDoc extends Model
{
    use HasFactory;

    protected $table = 'tab_cat_etapa_documento';
    protected $primaryKey = 'id_etapa_documento';
    public $timestamps = false;
    protected $fillable = ['id_etapa_documento','s_desc_etapa'];
}
