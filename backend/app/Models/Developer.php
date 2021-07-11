<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class Developer extends Model
{
    use HasFactory;

    protected $table = 'developers';

    protected $fillable = [
        'nome',
        'sexo',
        'idade',
        'hobby',
        'data_nascimento'
    ];

    protected $appends = [
        'sexo_extenso',
        'data_nascimento_br'
    ];

    public function setNomeAttribute($value)
    {
        $this->attributes['nome'] = Str::title(trim($value));
    }

    public function setSexoAttribute($value)
    {
        $value = strtoupper($value);
        $this->attributes['sexo'] = in_array($value, ['M', 'F']) ? $value : null;
    }

    public function getSexoExtensoAttribute()
    {
        return $this->sexo ? ($this->sexo === 'M' ? 'Masculino' : 'Feminino') : 'Não informado';
    }

    public function getDataNascimentoBrAttribute()
    {
        return $this->data_nascimento;
        Date::createFromFormat('Y-m-d', $this->data_nascimento)->format('d/m/Y');
    }

}
