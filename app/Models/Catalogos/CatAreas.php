<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmpleadoPuesto;

class CatAreas extends Model
{
    use HasFactory;

    protected $table = 'inst_cat_areas';

    protected $primaryKey = 'n_id_cat_area';
    protected $fillable = ['n_id_cat_area', 'n_id_u_adscripcion', 's_desc_area', 's_abrev_area', 'n_id_cat_area_padre'];

    public $timestamps = false;
    public function adscripcion()
    {
        return $this->belongsTo(CatUAdscripcion::class, 'n_id_u_adscripcion');
    }

    public function areaPadre()
    {
        return $this->belongsTo(CatAreas::class, 'n_id_cat_area_padre');
    }

    public function empleadosArea()
    {
        return $this->hasMany(EmpleadoPuesto::class, 'n_id_cat_area');
    }

    public function children()
    {
        return $this->hasMany(CatAreas::class, 'n_id_cat_area_padre', 'n_id_cat_area');
    }

}
