<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'image', 'parent_id'];

    protected $appends = ['image_url'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    public function getAllChildIds()
    {
        $childIds = [];
        $children = $this->allChildren()->get();

        foreach ($children as $child) {
            $childIds[] = $child->id;
            $childIds = array_merge($childIds, $child->getAllChildIds());
        }

        return $childIds;
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL) || Str::startsWith($this->image, ['//'])) {
            return $this->image;
        }

        return Storage::url($this->image);
    }
}
