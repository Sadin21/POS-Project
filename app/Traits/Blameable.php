<?php

namespace App\Traits;

use App\Models\User;
use App\Observers\BlameableObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Blameable {

    public static function bootBlameable(): void{
        static::observe(BlameableObserver::class);
    }

    public function creator(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by')->select([ 'guid', 'email', 'name' ])->withTrashed();
    }

    public function deleter(): BelongsTo{
        return $this->belongsTo(User::class, 'deleted_by')->select([ 'guid', 'email', 'name' ])->withTrashed();
    }

    public function updater(): BelongsTo{
        return $this->belongsTo(User::class, 'updated_by')->select([ 'guid', 'email', 'name' ])->withTrashed();
    }
}
