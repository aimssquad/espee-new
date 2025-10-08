<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Shape;
use Illuminate\Support\Str;

class UpdateShapeSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shapes:update-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update shape slugs for SEO-friendly URLs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $shapes = Shape::all();

        foreach ($shapes as $shape) {
            $slug = Str::slug($shape->name);
            $originalSlug = $slug;
            $counter = 1;

            // Ensure unique slug
            while (Shape::where('slug', $slug)->where('id', '!=', $shape->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $shape->slug = $slug;
            $shape->save();

            $this->info("Updated shape '{$shape->name}' with slug '{$slug}'");
        }

        $this->info('All shape slugs updated successfully!');
    }
}
