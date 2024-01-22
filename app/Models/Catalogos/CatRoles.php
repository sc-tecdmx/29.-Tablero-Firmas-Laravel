<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatRoles extends Model
{
    use HasFactory;

    protected $table = 'seg_org_roles';

    protected $primaryKey = 'n_id_rol';
    public $timestamps = false;

    protected $fillable = [
        'n_id_rol',
        'n_id_rol_padre',
        's_etiqueta_rol',
        's_descripcion',
        'n_rec_activo'
    ];
    public function rolPadre()
    {
        return $this->belongsTo(CatRoles::class, 'n_id_rol_padre');
    }
}
