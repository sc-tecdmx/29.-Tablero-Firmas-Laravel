<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatTipoDocumento extends Model
{
    use HasFactory;

    protected $table = 'tab_cat_tipo_documento';

    protected $primaryKey = 'n_id_tipo_documento';
    public $timestamps = false;
    protected $fillable = ['n_id_tipo_documento','desc_tipo_documento','n_id_cat_area'];

    public function area()
    {
        return $this->belongsTo(CatAreas::class, 'n_id_cat_area');
    }
}
