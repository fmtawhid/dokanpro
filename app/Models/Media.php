<?php

namespace App\Models;

use App\Enums\FileTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\File;

class Media extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'type' => FileTypes::class,
    ];

    public function file(): Attribute
    {
        $defualt=  File::exists(public_path($this->src)) ? asset(path: $this->src) : (Storage::exists($this->src) ? Storage::url($this->src) : asset('defualt/defualt.jpg'));
        return Attribute::make(
            get: fn () => $defualt,
        );
    }
}
