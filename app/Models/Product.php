<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'part_number', 'category', 'motor_models',
        'price', 'stock', 'description', 'image', 'is_popular',
    ];

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getMotorModelsArrayAttribute()
    {
        if (empty($this->motor_models)) {
            return [];
        }
        return array_map('trim', explode(',', $this->motor_models));
    }

    public function getImageUrlAttribute()
    {
        // Cek foto asli di folder public/images/products berdasarkan ID
        $extensions = ['jpg', 'jpeg', 'png', 'webp'];
        foreach ($extensions as $ext) {
            $path = public_path('images/products/' . $this->id . '.' . $ext);
            if (file_exists($path)) {
                return asset('images/products/' . $this->id . '.' . $ext);
            }
        }

        // Fallback ke Iconify jika foto tidak ada
        $name = strtolower($this->name);
        $mapping = [
            ['keywords' => ['oli', 'oil', 'gardan'],           'icon' => 'mdi:oil',              'color' => '8b4513'],
            ['keywords' => ['busi', 'spark plug'],             'icon' => 'mdi:flash',            'color' => 'ffc107'],
            ['keywords' => ['filter', 'air/c', 'screen'],      'icon' => 'mdi:air-filter',       'color' => '0d6efd'],
            ['keywords' => ['v-belt', 'belt'],                 'icon' => 'mdi:alpha-v-circle',   'color' => '198754'],
            ['keywords' => ['roller', 'weight'],               'icon' => 'mdi:circle-multiple',  'color' => '198754'],
            ['keywords' => ['kampas ganda', 'clutch shoe'],    'icon' => 'mdi:cog',              'color' => '20c997'],
            ['keywords' => ['kampas rem', 'brake pad', 'brake shoe'], 'icon' => 'mdi:car-brake-alert', 'color' => 'dc3545'],
            ['keywords' => ['aki', 'battery'],                 'icon' => 'mdi:car-battery',      'color' => '0066cc'],
            ['keywords' => ['lampu', 'headlight', 'winker', 'taillight'], 'icon' => 'mdi:lightbulb-on', 'color' => 'ffc107'],
        ];

        foreach ($mapping as $item) {
            foreach ($item['keywords'] as $keyword) {
                if (str_contains($name, $keyword)) {
                    return "https://api.iconify.design/{$item['icon']}.svg?color=%23{$item['color']}&width=200&height=200";
                }
            }
        }

        return "https://api.iconify.design/mdi:cog.svg?color=%23666666&width=200&height=200";
    }

    // Relasi ke tabel Review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Hitung rata-rata rating (misal: 4.5)
    public function getAverageRatingAttribute()
    {
        $avg = $this->reviews()->avg('rating');
        return $avg ? round($avg, 1) : 0;
    }

    // Hitung total ulasan
    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }
}