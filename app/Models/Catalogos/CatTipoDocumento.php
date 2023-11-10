<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatTipoDocumento extends Model
{
    use HasFactory;

    protected $table = 'tab_cat_tipo_documento';

    protected $primaryKey = 'n_id_tipo_documento';

    public function area()
    {
        return $this->belongsTo(CatAreas::class, 'n_id_cat_area');
    }
}
