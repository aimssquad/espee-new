<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Shape;
use App\Models\Color;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sunglassesCategory = Category::where('slug', 'sunglasses')->first();
        $framesCategory = Category::where('slug', 'frames')->first();
        $shapes = Shape::all();
        $colors = Color::all();

        // Sunglasses products
        $sunglassesProducts = [
            [
                'name' => 'Classic Aviator Pro',
                'model_no' => 'ESP-AV-001',
                'description' => 'Timeless aviator design with modern UV protection. Perfect for pilots and style enthusiasts alike.',
                'base_price' => 189.99,
                'shape' => 'Aviator'
            ],
            [
                'name' => 'Urban Square Elite',
                'model_no' => 'ESP-SQ-002',
                'description' => 'Bold square frames for the modern urbanite. Makes a statement while protecting your eyes.',
                'base_price' => 159.99,
                'shape' => 'Square'
            ],
            [
                'name' => 'Retro Round Classic',
                'model_no' => 'ESP-RD-003',
                'description' => 'Vintage-inspired round frames with contemporary lens technology.',
                'base_price' => 149.99,
                'shape' => 'Round'
            ],
            [
                'name' => 'Cat Eye Glamour',
                'model_no' => 'ESP-CE-004',
                'description' => 'Elegant cat eye design perfect for adding a touch of sophistication to any outfit.',
                'base_price' => 179.99,
                'shape' => 'Cat Eye'
            ],
            [
                'name' => 'Sport Performance X',
                'model_no' => 'ESP-SP-005',
                'description' => 'High-performance sports sunglasses with wrap-around design for maximum protection.',
                'base_price' => 199.99,
                'shape' => 'Rectangle'
            ],
            [
                'name' => 'Minimal Oval',
                'model_no' => 'ESP-OV-006',
                'description' => 'Minimalist oval frames for understated elegance.',
                'base_price' => 139.99,
                'shape' => 'Oval'
            ],
            [
                'name' => 'Navigator Premium',
                'model_no' => 'ESP-NV-007',
                'description' => 'Premium navigator style with anti-reflective coating.',
                'base_price' => 219.99,
                'shape' => 'Aviator'
            ],
            [
                'name' => 'Bold Square Max',
                'model_no' => 'ESP-BSQ-008',
                'description' => 'Oversized square frames for maximum sun protection and style.',
                'base_price' => 169.99,
                'shape' => 'Square'
            ],
            [
                'name' => 'Vintage Circle',
                'model_no' => 'ESP-VC-009',
                'description' => 'Perfectly round vintage frames with modern lens technology.',
                'base_price' => 159.99,
                'shape' => 'Round'
            ],
            [
                'name' => 'Fashion Cat Eye Pro',
                'model_no' => 'ESP-FCE-010',
                'description' => 'High-fashion cat eye frames with gradient lenses.',
                'base_price' => 189.99,
                'shape' => 'Cat Eye'
            ]
        ];

        // Frames products
        $framesProducts = [
            [
                'name' => 'Executive Square',
                'model_no' => 'ESP-EXS-011',
                'description' => 'Professional square frames perfect for the boardroom.',
                'base_price' => 129.99,
                'shape' => 'Square'
            ],
            [
                'name' => 'Scholar Round',
                'model_no' => 'ESP-SCR-012',
                'description' => 'Intellectual round frames for the modern thinker.',
                'base_price' => 119.99,
                'shape' => 'Round'
            ],
            [
                'name' => 'Modern Rectangle',
                'model_no' => 'ESP-MR-013',
                'description' => 'Contemporary rectangular frames with adjustable nose pads.',
                'base_price' => 139.99,
                'shape' => 'Rectangle'
            ],
            [
                'name' => 'Elegant Oval',
                'model_no' => 'ESP-EO-014',
                'description' => 'Sophisticated oval frames for everyday elegance.',
                'base_price' => 149.99,
                'shape' => 'Oval'
            ],
            [
                'name' => 'Retro Cat Eye Frame',
                'model_no' => 'ESP-RCF-015',
                'description' => 'Vintage-inspired cat eye optical frames.',
                'base_price' => 159.99,
                'shape' => 'Cat Eye'
            ],
            [
                'name' => 'Tech Square Pro',
                'model_no' => 'ESP-TSP-016',
                'description' => 'Blue light blocking frames for digital professionals.',
                'base_price' => 169.99,
                'shape' => 'Square'
            ],
            [
                'name' => 'Classic Round Metal',
                'model_no' => 'ESP-CRM-017',
                'description' => 'Timeless metal round frames with spring hinges.',
                'base_price' => 179.99,
                'shape' => 'Round'
            ],
            [
                'name' => 'Business Rectangle',
                'model_no' => 'ESP-BR-018',
                'description' => 'Professional rectangular frames for the modern executive.',
                'base_price' => 149.99,
                'shape' => 'Rectangle'
            ],
            [
                'name' => 'Soft Oval Classic',
                'model_no' => 'ESP-SOC-019',
                'description' => 'Comfortable oval frames with hypoallergenic materials.',
                'base_price' => 139.99,
                'shape' => 'Oval'
            ],
            [
                'name' => 'Designer Square Limited',
                'model_no' => 'ESP-DSL-020',
                'description' => 'Limited edition designer square frames.',
                'base_price' => 299.99,
                'shape' => 'Square'
            ],
            [
                'name' => 'Wayfarer Classic Pro',
                'model_no' => 'ESP-WF-021',
                'description' => 'Iconic wayfarer sunglasses with premium lenses.',
                'base_price' => 229.99,
                'shape' => 'Wayfarer'
            ],
            [
                'name' => 'Clubmaster Elite',
                'model_no' => 'ESP-CM-022',
                'description' => 'Sophisticated clubmaster style with metal accents.',
                'base_price' => 199.99,
                'shape' => 'Clubmaster'
            ],
            [
                'name' => 'Browline Executive',
                'model_no' => 'ESP-BE-023',
                'description' => 'Professional browline frames for business settings.',
                'base_price' => 179.99,
                'shape' => 'Browline'
            ],
            [
                'name' => 'Oversized Glamour',
                'model_no' => 'ESP-OG-024',
                'description' => 'Dramatic oversized frames for maximum style impact.',
                'base_price' => 189.99,
                'shape' => 'Oversized'
            ],
            [
                'name' => 'Sport Endurance',
                'model_no' => 'ESP-SE-025',
                'description' => 'High-performance sport sunglasses for athletes.',
                'base_price' => 249.99,
                'shape' => 'Sport'
            ],
            [
                'name' => 'Vintage Heritage',
                'model_no' => 'ESP-VH-026',
                'description' => 'Classic vintage-inspired frames with modern comfort.',
                'base_price' => 169.99,
                'shape' => 'Vintage'
            ],
            [
                'name' => 'Computer Vision Pro',
                'model_no' => 'ESP-CVP-027',
                'description' => 'Blue light blocking frames for digital eye strain relief.',
                'base_price' => 159.99,
                'shape' => 'Rectangle'
            ],
            [
                'name' => 'Reading Comfort Plus',
                'model_no' => 'ESP-RCP-028',
                'description' => 'Comfortable reading glasses with progressive lenses.',
                'base_price' => 129.99,
                'shape' => 'Oval'
            ],
            [
                'name' => 'Kids Adventure',
                'model_no' => 'ESP-KA-029',
                'description' => 'Durable and fun frames designed for active kids.',
                'base_price' => 89.99,
                'shape' => 'Round'
            ],
            [
                'name' => 'Luxury Gold Edition',
                'model_no' => 'ESP-LGE-030',
                'description' => 'Premium gold-plated frames for special occasions.',
                'base_price' => 399.99,
                'shape' => 'Square'
            ]
        ];

        // Create sunglasses
        foreach ($sunglassesProducts as $productData) {
            $shape = $shapes->where('name', $productData['shape'])->first();
            $subcategory = $sunglassesCategory->subcategories->random();

            $product = Product::create([
                'category_id' => $sunglassesCategory->id,
                'subcategory_id' => $subcategory->id,
                'shape_id' => $shape->id,
                'name' => $productData['name'],
                'model_no' => $productData['model_no'],
                'description' => $productData['description'],
                'base_price' => $productData['base_price']
            ]);

            // Create 5-10 variants for each product
            $variantCount = rand(5, 10);
            $selectedColors = $colors->random($variantCount);

            foreach ($selectedColors as $index => $color) {
                $priceVariation = rand(-20, 30); // Price can vary ±$20-30 from base
                $price = $product->base_price + $priceVariation;
                $stock = rand(0, 50); // Random stock between 0-50

                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $product->model_no . '-' . strtoupper(substr($color->name, 0, 3)) . '-' . ($index + 1),
                    'color_id' => $color->id,
                    'price' => $price,
                    'stock' => $stock,
                    'image' => 'products/dummy-' . $product->model_no . '-' . $color->name . '.jpg'
                ]);
            }
        }

        // Create frames
        foreach ($framesProducts as $productData) {
            $shape = $shapes->where('name', $productData['shape'])->first();
            $subcategory = $framesCategory->subcategories->random();

            $product = Product::create([
                'category_id' => $framesCategory->id,
                'subcategory_id' => $subcategory->id,
                'shape_id' => $shape->id,
                'name' => $productData['name'],
                'model_no' => $productData['model_no'],
                'description' => $productData['description'],
                'base_price' => $productData['base_price']
            ]);

            // Create 5-10 variants for each product
            $variantCount = rand(5, 10);
            $selectedColors = $colors->random($variantCount);

            foreach ($selectedColors as $index => $color) {
                $priceVariation = rand(-15, 25); // Price can vary ±$15-25 from base
                $price = $product->base_price + $priceVariation;
                $stock = rand(0, 40); // Random stock between 0-40

                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $product->model_no . '-' . strtoupper(substr($color->name, 0, 3)) . '-' . ($index + 1),
                    'color_id' => $color->id,
                    'price' => $price,
                    'stock' => $stock,
                    'image' => 'products/dummy-' . $product->model_no . '-' . $color->name . '.jpg'
                ]);
            }
        }
    }
}
