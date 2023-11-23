<?php

namespace App\Models;

use App\Models\Catalogos\CatInstruccionDest;
use Illuminate\Database\Eloquent\Model;
use App\Models\Catalogos\CatEmpleados;

class Destinatarios extends Model
{
    protected $table = 'tab_doc_destinatarios';

    public function empleado()
    {
        return $this->belongsTo(CatEmpleados::class, 'n_id_num_empleado', 'n_id_num_empleado');
    }

    public function empleadoPuesto()
    {
        return $this->hasOne(EmpleadoPuesto::class, 'n_id_num_empleado', 'n_id_num_empleado');
    }
    public function instruccion()
    {
        return $this->hasOne(CatInstruccionDest::class, 'n_id_inst_dest', 'n_id_inst_dest');
    }
}
