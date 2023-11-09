<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatRoles extends Model
{
    use HasFactory;

    protected $table = 'seg_org_roles';

    protected $primaryKey = 'n_id_rol';

    public function rolPadre()
    {
        return $this->belongsTo(CatRoles::class, 'n_id_rol_padre');
    }
}
