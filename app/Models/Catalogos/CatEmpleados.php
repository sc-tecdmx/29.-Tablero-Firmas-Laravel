<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmpleadoPuesto;

class CatEmpleados extends Model
{
    use HasFactory;

    protected $table = 'inst_empleado';

    protected $primaryKey = 'n_id_num_empleado';
    public function empleadoPuesto()
    {
        return $this->hasOne(EmpleadoPuesto::class, 'n_id_num_empleado', 'n_id_num_empleado');
    }
    public function area()
    {
        return $this->belongsTo(CatAreas::class, 'n_id_cat_area');
    }
    public function sexo()
    {
        return $this->belongsTo(CatSexo::class, 'id_sexo');
    }
}
