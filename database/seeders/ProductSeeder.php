<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // ===== MESIN - OLI =====
            ['AHM OIL MPX 2 10W-30 SLMB', 'Mesin', 'Beat, Vario, Scoopy, Genio, PCX, ADV', 48000, 'Oli mesin matic original AHM.'],
            ['AHM OIL SPX 2 10W-30 SLMB 0.8L', 'Mesin', 'Beat, Vario, PCX, ADV', 55000, 'Oli mesin full synthetic original AHM untuk performa maksimal.'],
            ['AHM OIL MPX 1 10W-30 SLMB 0.8L', 'Mesin', 'Beat, Vario, Scoopy', 43000, 'Oli mesin original AHM untuk perawatan rutin.'],
            ['AHM OIL GARDAN 120ML', 'Mesin', 'Beat, Vario, Scoopy, Genio, PCX, ADV', 18000, 'Oli gardan original AHM untuk motor matic Honda.'],

            // ===== MESIN - BUSI =====
            ['SPARK PLUG CPR9EA-9 (NGK)', 'Mesin', 'Beat, Vario, Scoopy', 28000, 'Busi standar original NGK untuk motor matic Honda injeksi.'],
            ['SPARK PLUG MR9C-9N (NGK)', 'Mesin', 'Beat FI eSP, Genio', 32000, 'Busi standar original untuk Honda Beat FI eSP.'],
            ['SPARK PLUG MR8K-9 (NGK)', 'Mesin', 'PCX 150', 35000, 'Busi standar original untuk Honda PCX 150.'],
            ['SPARK PLUG CPR9EAIX-9 (NGK Iridium)', 'Mesin', 'Vario 125/150, PCX', 95000, 'Busi iridium NGK original, umur pakai lebih panjang dan performa lebih baik.'],

            // ===== MESIN - FILTER =====
            ['ELEMENT COMP, AIR/C (FILTER UDARA) - 17210K16900', 'Mesin', 'Beat FI', 65000, 'Filter udara original Honda untuk Beat FI.'],
            ['ELEMENT COMP, AIR/C (FILTER UDARA) - 17210KVB930', 'Mesin', 'Vario 110', 60000, 'Filter udara original Honda untuk Vario 110.'],
            ['ELEMENT COMP, AIR/C (FILTER UDARA) - 17210K97N00', 'Mesin', 'PCX 150', 85000, 'Filter udara original Honda untuk PCX 150.'],
            ['SCREEN OIL FILTER (SARINGAN OLI) - 15421KPL900', 'Mesin', 'Beat, Vario, Scoopy, PCX, ADV', 35000, 'Saringan oli original Honda untuk berbagai model matic.'],

            // ===== CVT - V-BELT =====
            ['V-BELT COMP DRIVE (23100-K44-1200)', 'CVT', 'Beat FI, Scoopy FI', 82000, 'V-belt original Honda untuk Beat FI dan Scoopy FI.'],
            ['V-BELT COMP DRIVE (23100-K35-1200)', 'CVT', 'Vario 125 eSP', 95000, 'V-belt original Honda untuk Vario 125 eSP.'],
            ['V-BELT COMP DRIVE (23100-K36-1200)', 'CVT', 'Vario 150 eSP', 105000, 'V-belt original Honda untuk Vario 150 eSP.'],
            ['V-BELT COMP DRIVE (23100-K97-601)', 'CVT', 'PCX 150', 125000, 'V-belt original Honda untuk PCX 150.'],
            ['V-BELT COMP DRIVE (23100-K0J-N01)', 'CVT', 'Beat Deluxe, Scoopy, Genio', 88000, 'V-belt original Honda untuk model terbaru Beat, Scoopy, dan Genio.'],
            ['V-BELT COMP DRIVE (23100-K1Z-N210-M1)', 'CVT', 'PCX 160, ADV 160', 145000, 'V-belt original Honda untuk PCX 160 dan ADV 160.'],

            // ===== CVT - ROLLER =====
            ['ROLLER WEIGHT SET (2212A-KVB-900)', 'CVT', 'Beat FI, Vario 110, Scoopy FI', 65000, 'Roller weight set original Honda.'],
            ['ROLLER WEIGHT SET (2212A-K44-V00)', 'CVT', 'Beat eSP K25, Vario 110 FI, Scoopy FI', 68000, 'Roller weight set original Honda 15 gram.'],
            ['ROLLER WEIGHT SET (22123-K1A-N00)', 'CVT', 'PCX 150, ADV 150', 75000, 'Roller weight set original Honda untuk PCX 150 dan ADV 150.'],
            ['ROLLER WEIGHT SET (22123-KWN-900)', 'CVT', 'Vario 125/150, PCX 125/150', 72000, 'Roller weight set original Honda untuk Vario dan PCX.'],

            // ===== CVT - KAMPAS GANDA =====
            ['KAMPAS GANDA (CLUTCH SHOE SET) - 06430-KZL-930', 'CVT', 'Beat FI, Vario, Scoopy, PCX', 95000, 'Kampas ganda original Honda.'],
            ['KAMPAS GANDA (CLUTCH SHOE SET) - 06430-KWN-900', 'CVT', 'Vario 125/150, PCX 150', 110000, 'Kampas ganda original Honda untuk Vario dan PCX.'],
            ['KAMPAS GANDA (CLUTCH SHOE SET) - 06430-K97-N01', 'CVT', 'PCX 160, ADV 160', 135000, 'Kampas ganda original Honda untuk PCX 160 dan ADV 160.'],

            // ===== PENGEREMAN - KAMPAS REM =====
            ['BRAKE PAD SET (KAMPAS REM DEPAN) - 06455-KVB-401', 'Pengereman', 'Vario 110, PCX 150/160, ADV 150', 85000, 'Kampas rem depan original Honda (disc brake).'],
            ['BRAKE PAD SET (KAMPAS REM DEPAN) - 06455-KVB-T01', 'Pengereman', 'Beat, Scoopy, Vario', 80000, 'Kampas rem depan original Honda (disc brake).'],
            ['BRAKE SHOE SET (KAMPAS REM BELAKANG) - 06430-KZL-930', 'Pengereman', 'Beat, Scoopy, Vario, PCX', 75000, 'Kampas rem belakang original Honda (drum brake).'],
            ['BRAKE SHOE SET (KAMPAS REM BELAKANG) - 06430-KWN-900', 'Pengereman', 'Vario 125/150, PCX 150', 80000, 'Kampas rem belakang original Honda (drum brake).'],
            ['BRAKE PAD SET (KAMPAS REM BELAKANG) - 06435-K97-N01', 'Pengereman', 'PCX 160, ADV 160', 95000, 'Kampas rem belakang original Honda (disc brake).'],

            // ===== KELISTRIKAN - AKI =====
            ['BATTERY (AKI) GTZ6V - 31500KZR602', 'Kelistrikan', 'Vario, Beat, Scoopy, Genio, PCX, ADV', 285000, 'Aki kering original Honda GTZ6V.'],
            ['BATTERY (AKI) GTZ5S - 31500KVB601', 'Kelistrikan', 'Beat, Vario 110', 195000, 'Aki kering original Honda GTZ5S.'],
            ['BATTERY (AKI) YTZ7S - 31500KWN901', 'Kelistrikan', 'Vario 150, PCX 150, ADV 150', 245000, 'Aki kering original Honda YTZ7S.'],

            // ===== KELISTRIKAN - LAMPU =====
            ['HEADLIGHT UNIT (LAMPU DEPAN LED) - 33100K97N01', 'Kelistrikan', 'PCX 150', 1850000, 'Lampu depan LED original Honda untuk PCX 150.'],
            ['HEADLIGHT UNIT (LAMPU DEPAN LED) - 33100K1ZJ11', 'Kelistrikan', 'PCX 160', 1950000, 'Lampu depan LED original Honda untuk PCX 160.'],
            ['LAMPU SEIN DEPAN (WINKER) - 33400KVB901', 'Kelistrikan', 'Beat, Vario', 55000, 'Lampu sein depan original Honda.'],
            ['LAMPU REM BELAKANG (TAILLIGHT) - 33700K1ZJ11', 'Kelistrikan', 'PCX 160', 850000, 'Lampu rem belakang LED original Honda.'],
            ['LAMPU SEIN BELAKANG (WINKER) - 33600KVB901', 'Kelistrikan', 'Beat, Vario', 50000, 'Lampu sein belakang original Honda.'],
        ];

        $partNumbers = [
            '08232-2MB-K0LZ1', '08234-2MB-K0LZ0', '08232-2MB-K0LZ0', '08294-M99-Z8YN1',
            '31926-KYT-901', '31926-KYT-901', '31926-KYT-901', '31926-KYT-901',
            '17210-K16-900', '17210-KVB-930', '17210-K97-N00', '15421-KPL-900',
            '23100-K44-1200', '23100-K35-1200', '23100-K36-1200', '23100-K97-601',
            '23100-K0J-N01', '23100-K1Z-N210-M1',
            '2212A-KVB-900', '2212A-K44-V00', '22123-K1A-N00', '22123-KWN-900',
            '06430-KZL-930', '06430-KWN-900', '06430-K97-N01',
            '06455-KVB-401', '06455-KVB-T01', '06430-KZL-930', '06430-KWN-900', '06435-K97-N01',
            '31500-KZR-602', '31500-KVB-601', '31500-KWN-901',
            '33100-K97-N01', '33100-K1Z-J11', '33400-KVB-901', '33700-K1Z-J11', '33600-KVB-901',
        ];

        foreach ($products as $index => $p) {
            $product = Product::create([
                'name' => $p[0],
                'slug' => Str::slug($p[0]) . '-' . ($index + 1),
                'part_number' => $partNumbers[$index] ?? 'PK-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'category' => $p[1],
                'motor_models' => $p[2],
                'price' => $p[3],
                'stock' => rand(5, 50),
                'description' => $p[4],
                'is_popular' => in_array($index, [0, 4, 12, 16, 19, 25, 30, 33]),
            ]);

                       // --- LOGIKA VARIAN UNTUK SEMUA PRODUK OLI ---
            // Index 0 = AHM OIL MPX 2
            if ($index === 0) {
                \App\Models\ProductVariant::create(['product_id' => $product->id, 'name' => '0.8 Liter', 'price' => 48000, 'stock' => 20]);
                \App\Models\ProductVariant::create(['product_id' => $product->id, 'name' => '0.65 Liter', 'price' => 42000, 'stock' => 15]);
            }
            
            // Index 1 = AHM OIL SPX 2
            if ($index === 1) {
                \App\Models\ProductVariant::create(['product_id' => $product->id, 'name' => '0.8 Liter', 'price' => 55000, 'stock' => 25]);
                \App\Models\ProductVariant::create(['product_id' => $product->id, 'name' => '0.65 Liter', 'price' => 48000, 'stock' => 10]);
            }

            // Index 2 = AHM OIL MPX 1
            if ($index === 2) {
                \App\Models\ProductVariant::create(['product_id' => $product->id, 'name' => '0.8 Liter', 'price' => 43000, 'stock' => 30]);
                \App\Models\ProductVariant::create(['product_id' => $product->id, 'name' => '0.65 Liter', 'price' => 38000, 'stock' => 12]);
            }
            // --------------------------------------------
        }
    }
}