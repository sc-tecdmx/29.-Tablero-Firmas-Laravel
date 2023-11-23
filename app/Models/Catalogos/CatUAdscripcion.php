<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatUAdscripcion extends Model
{
    use HasFactory;

    protected $table = 'inst_u_adscripcion';
    protected $primaryKey = 'n_id_u_adscripcion';

    public $timestamps = false;

    protected $fillable = [
        'n_id_u_adscripcion',
        's_desc_unidad',
        's_abrev_unidad'
    ];
}
