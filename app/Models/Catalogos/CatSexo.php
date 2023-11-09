<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatSexo extends Model
{
    use HasFactory;

    protected $table = 'inst_cat_sexo';
    protected $primaryKey = 'id_sexo';

    protected $fillable = ['sexo_desc', 'sexo'];

    public $timestamps = false;
}
