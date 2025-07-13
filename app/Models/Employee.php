<?php

namespace App\Models;

use App\Models\Division;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    public $incrementing = false;
    protected $keyType = 'uuid';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    protected $fillable = [
        'image',
        'name',
        'phone',
        'division_id',
        'position',
    ];

    // Relasi: pegawai milik satu divisi
    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }
}
