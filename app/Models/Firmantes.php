<?php

namespace App\Models;


use App\Models\Catalogos\CatEmpleados;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Firmantes extends Model
{
    protected $table = 'tab_docs_firmantes';

    public function empleado()
    {
        return $this->belongsTo(CatEmpleados::class, 'n_id_num_empleado', 'n_id_num_empleado');
    }
    public function empleadoPuesto()
    {
        return $this->hasOne(EmpleadoPuesto::class, 'n_id_num_empleado', 'n_id_num_empleado');
    }
}
