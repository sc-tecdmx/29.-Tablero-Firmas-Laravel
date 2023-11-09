<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatEmpleados extends Model
{
    use HasFactory;

    protected $table = 'inst_empleado';

    public function sexo()
    {
        return $this->belongsTo(CatSexo::class, 'id_sexo');
    }
}
