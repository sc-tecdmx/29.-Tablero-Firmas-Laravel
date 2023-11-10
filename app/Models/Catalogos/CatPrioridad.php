<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatPrioridad extends Model
{
    use HasFactory;

    protected $table = 'tab_cat_prioridad';
    protected $primaryKey = 'n_id_prioridad';
}
