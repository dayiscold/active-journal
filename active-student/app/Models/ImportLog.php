<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'filename', 'status', 'records_processed', 'error_message'];

    protected $casts = [
        'records_processed' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
