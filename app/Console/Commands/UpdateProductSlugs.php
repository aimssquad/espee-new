<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Str;

class UpdateProductSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:update-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update product slugs for SEO-friendly URLs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $products = Product::all();

        foreach ($products as $product) {
            if (empty($product->slug)) {
                $slug = Str::slug($product->name);
                $originalSlug = $slug;
                $counter = 1;

                // Ensure unique slug
                while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }

                $product->slug = $slug;
                $product->save();

                $this->info("Updated product '{$product->name}' with slug '{$slug}'");
            }
        }

        $this->info('All product slugs updated successfully!');
    }
}
