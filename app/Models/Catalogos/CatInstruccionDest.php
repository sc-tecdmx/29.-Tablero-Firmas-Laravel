<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatInstruccionDest extends Model
{
    use HasFactory;

    protected $table = 'tab_cat_inst_dest';
    public $timestamps = false;
    protected $primaryKey = 'n_id_inst_dest';
    protected $fillable = ['desc_inst_dest','n_id_inst_dest'];
}
