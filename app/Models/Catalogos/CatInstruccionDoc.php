<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatInstruccionDoc extends Model
{
    use HasFactory;

    protected $table = 'pki_cat_instruccion_doc';
    public $timestamps = false;
    protected $primaryKey = 'id_instruccion_doc';
    protected $fillable = ['id_instruccion_doc','desc_instruccion_doc'];
}
