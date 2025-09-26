<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaxMaster;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Str;

class TaxMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data to prevent duplicates on re-run
        TaxMaster::truncate();

        // Get categories and subcategories
        $sunglassesCategory = Category::where('name', 'like', '%sunglass%')->first();
        $framesCategory = Category::where('name', 'like', '%frame%')->first();

        $sunglassesSubcategory = null;
        $framesSubcategory = null;

        if ($sunglassesCategory) {
            $sunglassesSubcategory = Subcategory::where('category_id', $sunglassesCategory->id)->first();
        }

        if ($framesCategory) {
            $framesSubcategory = Subcategory::where('category_id', $framesCategory->id)->first();
        }

        $taxRates = [
            [
                'name' => 'Sunglasses Tax',
                'description' => 'GST tax rate for sunglasses and related products',
                'tax_rate' => 18.00,
                'tax_type' => 'gst',
                'category_id' => $sunglassesCategory?->id,
                'subcategory_id' => $sunglassesSubcategory?->id,
                'hsn_code' => '9004',
                'sac_code' => null,
                'cgst_rate' => 9.00,
                'sgst_rate' => 9.00,
                'igst_rate' => 18.00,
                'cess_rate' => 0.00,
                'company_state' => 'West Bengal',
                'company_state_code' => 'WB',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Frames Tax',
                'description' => 'GST tax rate for eyeglass frames and related products',
                'tax_rate' => 5.00,
                'tax_type' => 'gst',
                'category_id' => $framesCategory?->id,
                'subcategory_id' => $framesSubcategory?->id,
                'hsn_code' => '9003',
                'sac_code' => null,
                'cgst_rate' => 2.50,
                'sgst_rate' => 2.50,
                'igst_rate' => 5.00,
                'cess_rate' => 0.00,
                'company_state' => 'West Bengal',
                'company_state_code' => 'WB',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Default Tax',
                'description' => 'Default GST tax rate for products without specific tax category',
                'tax_rate' => 18.00,
                'tax_type' => 'gst',
                'category_id' => null,
                'subcategory_id' => null,
                'hsn_code' => '9999',
                'sac_code' => null,
                'cgst_rate' => 9.00,
                'sgst_rate' => 9.00,
                'igst_rate' => 18.00,
                'cess_rate' => 0.00,
                'company_state' => 'West Bengal',
                'company_state_code' => 'WB',
                'is_active' => true,
                'sort_order' => 99,
            ],
        ];

        foreach ($taxRates as $taxData) {
            TaxMaster::create(array_merge($taxData, [
                'slug' => Str::slug($taxData['name']),
            ]));
        }

        $this->command->info('Tax Master data seeded successfully!');
        $this->command->info('Created tax rates:');
        foreach ($taxRates as $tax) {
            $this->command->info("- {$tax['name']}: {$tax['tax_rate']}%");
        }
    }
}
