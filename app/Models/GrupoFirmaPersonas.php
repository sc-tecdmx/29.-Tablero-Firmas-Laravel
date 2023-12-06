<?php

namespace App\Models;

use App\Models\Catalogos\CatEmpleados;
use Illuminate\Database\Eloquent\Model;

class GrupoFirmaPersonas extends Model
{
    protected $table = 'tab_doc_grupo_firmas_personas';
    protected $primaryKey = 'n_id_grupo_personas';
    public $timestamps = false;
    protected $fillable = ['n_id_grupo_personas','n_id_num_empleado','n_id_inst_firmante','n_id_inst_destinatario'];

    public function empleado()
{
    return $this->belongsTo(CatEmpleados::class, 'n_id_num_empleado', 'n_id_num_empleado');
}

}
