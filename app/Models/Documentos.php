<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Catalogos\CatDestinoDocumento;
use App\Models\Catalogos\CatTipoDocumento;
use App\Models\Catalogos\CatEmpleados;
use App\Models\Catalogos\CatExpedientes;
use App\Models\Catalogos\CatPrioridad;

class Documentos extends Model
{
    use HasFactory;

    protected $table = 'tab_documentos';

    protected $primaryKey = 'n_id_documento';

    protected $dates = ['creacion_documento_fecha', 'd_fecha_limite_firma'];

    public function scopeSearch($query, $term)
    {
        return $query->where('folio_documento', 'LIKE', "%{$term}%")
            ->orWhere('folio_especial', 'LIKE', "%{$term}%")
            ->orWhere('s_asunto', 'LIKE', "%{$term}%")
            ->orWhere('s_contenido', 'LIKE', "%{$term}%")
            ->orWhere('creacion_documento_fecha', 'LIKE', "%{$term}%")
            ->orWhereHas('firmantes.empleado', function ($q) use ($term) {
                $q->where('nombre', 'LIKE', "%{$term}%")
                    ->orWhere('apellido1', 'LIKE', "%{$term}%")
                    ->orWhere('apellido2', 'LIKE', "%{$term}%");
            })
            ->orWhereHas('destinatarios.empleado', function ($q) use ($term) {
                $q->where('nombre', 'LIKE', "%{$term}%")
                    ->orWhere('apellido1', 'LIKE', "%{$term}%")
                    ->orWhere('apellido2', 'LIKE', "%{$term}%");
            });
    }
    public function docConfiguracion()
    {
        return $this->hasMany(DocConfiguracion::class, 'n_id_documento');
    }
    public function destino()
    {
        return $this->belongsTo(CatDestinoDocumento::class, 'n_id_tipo_destino');
    }
    public function tipoDocumento()
    {
        return $this->belongsTo(CatTipoDocumento::class, 'n_id_tipo_documento');
    }
    public function empleado()
    {
        return $this->belongsTo(CatEmpleados::class, 'n_id_num_empleado_creador');
    }
    public function expediente()
    {
        return $this->belongsTo(CatExpedientes::class, 'n_num_expediente');
    }
    public function prioridad()
    {
        return $this->belongsTo(CatPrioridad::class, 'n_id_prioridad');
    }

    public function firmantes()
    {
        return $this->hasMany(Firmantes::class, 'n_id_documento');
    }

    public function destinatarios()
    {
        return $this->hasMany(Destinatarios::class, 'n_id_documento');
    }
    public function documentosAdjuntos()
    {
        return $this->hasMany(DocumentoAdjunto::class, 'id_document', 'n_id_documento');
    }
    public function workflowUltimaEtapa()
    {
        return $this->hasOne(DocumentoWorkflow::class, 'id_document', 'n_id_documento')
                    ->latest('ult_actualizacion');
    }

}
