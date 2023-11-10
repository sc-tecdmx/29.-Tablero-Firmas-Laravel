<?php

namespace App\Models;

use App\Models\Catalogos\CatAreas;
use App\Models\Catalogos\CatPuesto;
use App\Models\Catalogos\CatEmpleados;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpleadoPuesto extends Model
{
    use HasFactory;

    protected $table = 'inst_empleado_puesto';

    protected $primaryKey ='n_id_empleado_puesto';

    public function puesto()
    {
        return $this->belongsTo(CatPuesto::class, 'n_id_puesto');
    }

    public function area()
    {
        return $this->belongsTo(CatAreas::class, 'n_id_cat_area');
    }

    public function empleado()
    {
        return $this->belongsTo(CatEmpleados::class, 'n_id_num_empleado');
    }
}

