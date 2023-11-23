<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatFirmaAplicada extends Model
{
    use HasFactory;

    protected $table = 'pki_cat_firma_aplicada';
    public $timestamps = false;
    protected $primaryKey = 'id_firma_aplicada';
    protected $fillable = ['id_firma_aplicada','desc_firma_aplicada'];
}
