<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatDocConfiguracion extends Model
{
    use HasFactory;

    protected $table = 'tab_cat_doc_config';
    public $timestamps = false;
    protected $fillable = ['n_id_doc_config','s_valor','s_atributo'];
}
