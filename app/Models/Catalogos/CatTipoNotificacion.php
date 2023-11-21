<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatTipoNotificacion extends Model
{
    use HasFactory;

    protected $table = 'tab_cat_tipo_notificacion';
    public $timestamps = false;
    protected $fillable = ['n_id_tipo_notif','desc_tipo','icon_tipo_notif'];
}
