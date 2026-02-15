<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Condition;
use App\Models\Feature;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Categories
        $catElectronics = Category::firstOrCreate(['name' => 'Electronics']);
        $catPhones = Category::firstOrCreate(['name' => 'Phones']);
        $catAccessories = Category::firstOrCreate(['name' => 'Accessories']);

        // 2. Create Common Conditions
        $condNew = Condition::firstOrCreate(['name' => 'New']);
        $condUsed = Condition::firstOrCreate(['name' => 'Used']);
        $condRefurb = Condition::firstOrCreate(['name' => 'Refurbished']);

        // 3. Define Products Data (features separated)
        // 3. Create a Supplier
        $supplier = \App\Models\Supplier::firstOrCreate([
            'name' => 'Global Electronics Ltd',
            'location' => 'Germany, Berlin',
            'is_verified' => true,
            'has_worldwide_shipping' => true
        ]);

        // 4. Define Products Data
        $productsData = [
            [
                'name' => 'Canon EOS R6 Mark II',
                'price' => 2499.00,
                'old_price' => 2699.00,
                'brand' => 'Canon',
                'category_id' => $catElectronics->id,
                'supplier_id' => $supplier->id,
                'rating' => 4.8,
                'image' => 'sample.jpg',
                'description' => 'Professional mirrorless camera with high-speed performance.',
                'condition_id' => $condNew->id,
                'material' => 'Magnesium Alloy',
                'type' => 'Mirrorless',
                'model_number' => 'R6MK2',
                'size' => '138 x 98 x 88 mm',
                'warranty' => '2 Year Manufacturer Warranty',
                'features' => ['High Resolution', 'Weather Sealed', 'Dual Card Slots']
            ],
            [
                'name' => 'Samsung Galaxy S21 Ultra',
                'price' => 1199.00,
                'old_price' => 1299.00,
                'brand' => 'Samsung',
                'category_id' => $catPhones->id,
                'supplier_id' => $supplier->id,
                'rating' => 4.9,
                'image' => 'sample.jpg',
                'description' => 'The ultimate flagship smartphone from Samsung.',
                'condition_id' => $condNew->id,
                'material' => 'Gorilla Glass Victus',
                'type' => 'Smartphone',
                'memory' => '12GB RAM, 256GB Storage',
                'features' => ['120Hz Display', 'S-Pen Support', '108MP Camera']
            ],
        ];

        foreach ($productsData as $data) {
            $featuresList = $data['features'] ?? [];
            unset($data['features']);

            $product = Product::create($data);

            // Create Price Tiers
            $product->priceTiers()->createMany([
                ['min_qty' => 1, 'max_qty' => 10, 'price' => $product->price],
                ['min_qty' => 11, 'max_qty' => 50, 'price' => $product->price * 0.9],
                ['min_qty' => 51, 'max_qty' => null, 'price' => $product->price * 0.8],
            ]);

            foreach ($featuresList as $featureName) {
                $feature = Feature::firstOrCreate(['name' => $featureName]);
                $product->features()->attach($feature->id);
            }
        }
    }
}
