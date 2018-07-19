<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Attachments\Models;

use FI\Events\AttachmentCreating;
use FI\Events\AttachmentDeleted;
use FI\Support\DateFormatter;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $table = 'attachments';

    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($attachment) {
            event(new AttachmentCreating($attachment));
        });

        static::deleted(function ($attachment) {
            event(new AttachmentDeleted($attachment));
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function attachable()
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

    public function getDownloadUrlAttribute()
    {
        return route('attachments.download', [$this->url_key]);
    }

    public function getFormattedCreatedAtAttribute()
    {
        return DateFormatter::format($this->created_at, true);
    }
}