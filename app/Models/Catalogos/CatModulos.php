<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class CatModulos extends Model
{
    use HasFactory;

    protected $table = 'seg_org_modulos';

    protected $primaryKey ='n_id_modulo';

    public $timestamps = false;

}
