<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['sub_image', 'is_main', 'products_id'];

    protected $appends = ['image_url'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->sub_image) {
            return null;
        }

        if (filter_var($this->sub_image, FILTER_VALIDATE_URL) || Str::startsWith($this->sub_image, ['//'])) {
            return $this->sub_image;
        }

        return Storage::url($this->sub_image);
    }
}
