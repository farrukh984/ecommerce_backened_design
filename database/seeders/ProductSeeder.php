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
        $productsData = [
            [
                'name' => 'Samsung Galaxy S21',
                'price' => 799,
                'brand' => 'Samsung',
                'category_id' => $catPhones->id,
                'rating' => 5,
                'image' => 'sample.jpg',
                'description' => 'Flagship Android phone',
                'condition_id' => $condNew->id,
                'features' => ['High Resolution', 'Fast Charging', 'Premium Design']
            ],
            [
                'name' => 'Apple iPhone 13',
                'price' => 899,
                'brand' => 'Apple',
                'category_id' => $catPhones->id,
                'rating' => 5,
                'image' => 'sample.jpg',
                'description' => 'Latest Apple smartphone',
                'condition_id' => $condNew->id,
                'features' => ['High Resolution', 'Long Battery Life', 'Wireless']
            ],
            [
                'name' => 'Huawei P40 Pro',
                'price' => 749,
                'brand' => 'Huawei',
                'category_id' => $catPhones->id,
                'rating' => 4,
                'image' => 'sample.jpg',
                'description' => 'Powerful Huawei device',
                'condition_id' => $condNew->id,
                'features' => ['Water Resistant', 'Fast Charging', 'Premium Design']
            ],
            // More products...
             [
                'name' => 'Poco X3',
                'price' => 299,
                'brand' => 'Poco',
                'category_id' => $catPhones->id,
                'rating' => 4,
                'image' => 'sample.jpg',
                'description' => 'Budget-friendly phone',
                'condition_id' => $condNew->id,
                'features' => ['Long Battery Life', 'Lightweight']
            ],
        ];

        foreach ($productsData as $data) {
            // Extract features to handle separately
            $featuresList = $data['features'] ?? [];
            unset($data['features']); // Remove from array so create() doesn't fail

            // Create Product
            $product = Product::create($data);

            // Create & Attach Features
            foreach ($featuresList as $featureName) {
                $feature = Feature::firstOrCreate(['name' => $featureName]);
                $product->features()->attach($feature->id);
            }
        }
    }
}
