<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatInstruccion extends Model
{
    use HasFactory;

    protected $table = 'tab_cat_inst_firmantes';
    public $timestamps = false;
    protected $primaryKey = 'n_id_inst_firmante';
    protected $fillable = ['desc_instr_firmante','n_id_inst_firmante'];

}
