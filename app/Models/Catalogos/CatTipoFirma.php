<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatTipoFirma extends Model
{
    use HasFactory;

    protected $table = 'pki_cat_tipo_firma';
    public $timestamps = false;
    protected $primaryKey = 'id_tipo_firma';
    protected $fillable = ['id_tipo_firma','desc_tipo_firma'];
}
