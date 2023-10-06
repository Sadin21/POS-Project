<?php

namespace App\Observers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

class BlameableObserver {
    
    public function creating(Model $model): void{
        $model->created_by = auth()->user()->id?? null;
        $model->updated_by = auth()->user()->id?? null;
    }

    public function updating(Model $model): void{
        $model->updated_by = auth()->user()->id?? null;
    }

    public function deleting(Model $model): void{
        $model->deleted_by = auth()->user()->id?? null;
        $model->save();
    }

    static function blameableSchema(Blueprint $table): void{
        $table->foreignId('created_by')->nullable()->constrained('users', 'id')->onDelete('cascade');
        $table->foreignId('updated_by')->nullable()->constrained('users', 'id')->onDelete('cascade');
        $table->foreignId('deleted_by')->nullable()->constrained('users', 'id')->onDelete('cascade');
    }
}
