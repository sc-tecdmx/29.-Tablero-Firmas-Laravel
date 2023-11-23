<?php

namespace App\Models;
use App\Models\Catalogos\CatDocConfiguracion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocConfiguracion extends Model
{
    protected $table = 'tab_doc_config';

    public function configuracion()
    {
        return $this->belongsTo(CatDocConfiguracion::class, 'n_id_doc_config');
    }


}
