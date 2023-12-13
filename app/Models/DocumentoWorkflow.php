<?php

namespace App\Models;


use App\Models\Catalogos\CatEtapaDoc;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Catalogos\CatDestinoDocumento;
use App\Models\Catalogos\CatTipoDocumento;
use App\Models\Catalogos\CatEmpleados;
use App\Models\Catalogos\CatExpedientes;
use App\Models\Catalogos\CatPrioridad;

class DocumentoWorkflow extends Model
{
    use HasFactory;

    protected $table = 'tab_documento_workflow';

    protected $primaryKey = 'id_documento_workflow';

    protected $dates = ['ult_actualizacion', 'workflow_fecha'];


    public function documento()
    {
        return $this->belongsTo(Documentos::class, 'id_document', 'n_id_documento');
    }
    public function etapaDocumento()
    {
        return $this->belongsTo(CatEtapaDoc::class, 'id_etapa_documento', 'id_etapa_documento'); // Asume que 'id' es la clave primaria en tab_cat_etapa_documento
    }


}
