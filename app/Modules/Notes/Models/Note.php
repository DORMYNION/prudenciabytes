<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Notes\Models;

use FI\Support\DateFormatter;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'notes';

    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function notable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('FI\Modules\Users\Models\User');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFormattedCreatedAtAttribute()
    {
        return DateFormatter::format($this->created_at, true);
    }

    public function getFormattedNoteAttribute()
    {
        return nl2br($this->note);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeProtect($query, $user)
    {
        if ($user->client_id) {
            return $query->where('private', 0);
        }

    }
}