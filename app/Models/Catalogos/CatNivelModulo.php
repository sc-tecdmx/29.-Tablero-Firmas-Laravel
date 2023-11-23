<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatNivelModulo extends Model
{
    use HasFactory;

    protected $table = 'seg_cat_nivel_modulo';
    public $timestamps = false;
    protected $primaryKey = 'n_id_nivel';
    protected $fillable = ['n_id_nivel','desc_nivel'];
}
