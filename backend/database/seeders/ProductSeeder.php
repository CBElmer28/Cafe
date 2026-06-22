<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Café Colombia Origen Huila',
                'description' => 'Un café balanceado con notas dulces a caramelo, chocolate y una acidez cítrica brillante. Cultivado a 1800 msnm.',
                'price' => 14.99,
                'stock' => 50,
                'origin' => 'Colombia',
                'roast_level' => 'medio',
                'grind_type' => 'grano',
                'image_url' => '/images/products/colombia_huila.png',
            ],
            [
                'name' => 'Etiopía Yirgacheffe G1',
                'description' => 'Exótico y floral. Notas pronunciadas a jazmín, té negro y ralladura de limón, con un cuerpo ligero y sedoso.',
                'price' => 17.50,
                'stock' => 35,
                'origin' => 'Etiopía',
                'roast_level' => 'Claro',
                'grind_type' => 'grano',
                'image_url' => '/images/products/etiopia_yirgacheffe.png',
            ],
            [
                'name' => 'Kenia AA Mount Kenya',
                'description' => 'Café complejo de alta montaña. Notas intensas a frutos rojos (moras, grosellas) y una acidez jugosa característica.',
                'price' => 19.20,
                'stock' => 20,
                'origin' => 'Kenia',
                'roast_level' => 'Claro',
                'grind_type' => 'medio',
                'image_url' => '/images/products/kenia_aa.png',
            ],
            [
                'name' => 'Costa Rica Tarrazú Honey',
                'description' => 'Procesado con el método honey. Notas a miel de caña, manzana roja y un postgusto cremoso muy prolongado.',
                'price' => 16.80,
                'stock' => 40,
                'origin' => 'Costa Rica',
                'roast_level' => 'medio',
                'grind_type' => 'grano',
                'image_url' => '/images/products/costarica_tarrazu.png',
            ],
            [
                'name' => 'Brasil Cerrado Dulce',
                'description' => 'Ideal para espresso. Notas intensas a frutos secos, avellana y cacao amargo, con cuerpo denso y baja acidez.',
                'price' => 12.50,
                'stock' => 60,
                'origin' => 'Brasil',
                'roast_level' => 'Oscuro',
                'grind_type' => 'grano',
                'image_url' => '/images/products/brasil_cerrado.png',
            ],
            [
                'name' => 'Café Espresso Blend Premium',
                'description' => 'Mezcla exclusiva de granos arábigos de altura. Perfecto para preparar capuchinos y lattes cremosos.',
                'price' => 15.00,
                'stock' => 80,
                'origin' => 'Multiorigen',
                'roast_level' => 'Oscuro',
                'grind_type' => 'fino',
                'image_url' => '/images/products/espresso_blend.png',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
