<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmpleadoPuesto;

class CatPuesto extends Model
{
    use HasFactory;

    protected $table = 'inst_cat_puestos';

    protected $primaryKey ='n_id_puesto';

    public $timestamps = false;

    protected $fillable = [
        'n_id_puesto',
        's_desc_nombramiento',
        'n_tipo_usuario'
    ];
    public function empleadosPuesto()
    {
        return $this->hasMany(EmpleadoPuesto::class, 'n_id_puesto');
    }
}
