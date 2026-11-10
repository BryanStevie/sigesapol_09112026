<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ConfigOption extends Model
{
  
    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'config_options';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'option_name',
        'option_label',
        'option_type',
        'option_value',
        'autoload',
        'option_grupo',
        'option_dependiente'
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'autoload' => 'boolean',
        'option_grupo' => 'boolean',
        'option_dependiente' => 'integer'
    ];

    /**
     * Indica si el modelo debe tener marcas de tiempo.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Obtener el grupo al que pertenece esta opción.
     */
    public function grupo()
    {
        return $this->belongsTo(ConfigOption::class, 'option_dependiente');
    }

    /**
     * Obtener las opciones dependientes de este grupo.
     */
    public function dependientes()
    {
        return $this->hasMany(ConfigOption::class, 'option_dependiente');
    }
}