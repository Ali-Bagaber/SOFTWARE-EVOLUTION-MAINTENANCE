<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $table = 'agencies';
    protected $primaryKey = 'agency_id';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'agency_name',
        'agency_email',
        'description',
        'agency_phone',
    ];

    /**
     * Get the users that belong to this agency.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'agency_id', 'agency_id');
    }
}