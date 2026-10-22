<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ThinkOceanPartner extends Model
{
    protected $fillable = ['name', 'logo_path', 'website_url', 'is_published', 'sort_order'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort_order')->orderBy('name');
    }

    public function logoUrl(): ?string
    {
        if (! $this->logo_path) {
            return null;
        }

        // Logos bundled with the site live under public/images; CMS uploads live under public/uploads.
        return asset(str_starts_with($this->logo_path, 'images/') ? $this->logo_path : 'uploads/think-ocean/'.$this->logo_path);
    }
}
