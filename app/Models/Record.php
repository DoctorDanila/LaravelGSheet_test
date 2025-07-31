<?php
namespace App\Models;

use App\Enums\RecordStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Record extends Model
{
    protected $fillable = ['text', 'status', 'comment'];

    protected $casts = [
        'status' => RecordStatus::class,
    ];

    public function scopeAllowed(Builder $query): Builder
    {
        return $query->where('status', RecordStatus::Allowed);
    }
}
