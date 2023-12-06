<?php

namespace App\Models;

use App\Models\Catalogos\CatAreas;
use Illuminate\Database\Eloquent\Model;

class GrupoFirma extends Model
{
    protected $table = 'tab_doc_grupos_firmas';

    protected $primaryKey = 'n_id_grupo_firmas';
    public $timestamps = false;
    protected $fillable = ['n_id_grupo_firmas', 'n_id_cat_area', 'c_tipo_grupo', 's_nombre_gpo_firmante'];

    public function personas()
    {
        return $this->hasMany(GrupoFirmaPersonas::class, 'n_id_grupo_personas', 'n_id_grupo_firmas');
    }
    public function area()
    {
        return $this->belongsTo(CatAreas::class, 'n_id_cat_area', 'n_id_cat_area');
    }
}
