<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Shape;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// Simple CSV/Excel processing without external packages
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ExcelUploadController extends Controller
{

    public function index()
    {
        return view('admin.excel-upload.index');
    }

    public function downloadTemplate()
    {
        $templateData = [
            [
                'Product Name',
                'Model Number',
                'Description',
                'Category',
                'Subcategory',
                'Shape',
                'Gender',
                'Base Price',
                'SKU',
                'Color',
                'Price',
                'Stock',
                'Image URLs (comma separated)'
            ],
            [
                'Sample Product 1',
                'MODEL001',
                'This is a sample product description',
                'Eyewear',
                'Sunglasses',
                'Aviator',
                'unisex',
                '1500.00',
                'SKU001',
                'Black',
                '1500.00',
                '100',
                'https://example.com/image1.jpg,https://example.com/image2.jpg'
            ],
            [
                'Sample Product 2',
                'MODEL002',
                'Another sample product description',
                'Eyewear',
                'Prescription Glasses',
                'Round',
                'women',
                '2000.00',
                'SKU002',
                'Brown',
                '2000.00',
                '50',
                'https://example.com/image3.jpg'
            ]
        ];

        $filename = 'product_upload_template.csv';
        $file = fopen('php://temp', 'w+');

        foreach ($templateData as $row) {
            fputcsv($file, $row);
        }

        rewind($file);
        $content = stream_get_contents($file);
        fclose($file);

        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => [
                'required',
                'file',
                'max:10240',
                function ($attribute, $value, $fail) {
                    $allowedMimes = ['text/csv', 'application/csv', 'text/plain'];
                    $allowedExtensions = ['csv'];

                    $mimeType = $value->getMimeType();
                    $extension = strtolower($value->getClientOriginalExtension());

                    // Log for debugging
                    Log::info('File upload validation:', [
                        'filename' => $value->getClientOriginalName(),
                        'mime_type' => $mimeType,
                        'extension' => $extension,
                        'size' => $value->getSize()
                    ]);

                    if (!in_array($mimeType, $allowedMimes) && !in_array($extension, $allowedExtensions)) {
                        $fail("The file must be a CSV file. Detected MIME type: {$mimeType}, Extension: {$extension}");
                    }
                }
            ]
        ]);

        Log::info('CSV file validation passed, starting processing...');

        try {
            DB::beginTransaction();

            $file = $request->file('excel_file');
            $rows = $this->parseCsvFile($file);
            $processedCount = $this->processProducts($rows);

            DB::commit();

            return redirect()->route('admin.excel-upload.index')
                ->with('success', 'Products uploaded successfully! ' . $processedCount . ' products processed.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CSV upload error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error uploading file: ' . $e->getMessage())
                ->withInput();
        }
    }

    private function parseCsvFile($file)
    {
        $rows = [];
        $handle = fopen($file->getPathname(), 'r');

        // Read header row
        $header = fgetcsv($handle);
        Log::info('CSV Header: ' . json_encode($header));

        $rowCount = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $rowCount++;
            Log::info("Row {$rowCount}: " . json_encode($row));
            Log::info("Row {$rowCount} column count: " . count($row));

            // Make it more flexible - accept rows with at least 5 columns (minimum required)
            if (count($row) >= 5) {
                $rows[] = [
                    'product_name' => $row[0] ?? '',
                    'model_number' => $row[1] ?? '',
                    'description' => $row[2] ?? '',
                    'category' => $row[3] ?? '',
                    'subcategory' => $row[4] ?? '',
                    'shape' => $row[5] ?? '',
                    'gender' => $row[6] ?? 'unisex',
                    'base_price' => $row[7] ?? 0,
                    'sku' => $row[8] ?? '',
                    'color' => $row[9] ?? '',
                    'price' => $row[10] ?? 0,
                    'stock' => $row[11] ?? 0,
                    'image_urls_comma_separated' => $row[12] ?? '',
                ];
            } else {
                Log::warning("Row {$rowCount} has insufficient columns: " . count($row) . " (minimum required: 5)");
            }
        }

        fclose($handle);
        Log::info('Total rows parsed: ' . count($rows));
        return $rows;
    }

    private function processProducts($rows)
    {
        $processedCount = 0;
        $client = new Client(['timeout' => 30, 'verify' => false]);

        Log::info('Processing ' . count($rows) . ' rows');
        Log::info('First row data: ' . json_encode($rows[0] ?? 'No rows found'));

        foreach ($rows as $index => $row) {
            try {
                Log::info("Processing row " . ($index + 1) . ": " . json_encode($row));

                // Skip empty rows
                if (empty($row['product_name']) || empty($row['model_number'])) {
                    Log::info("Skipping row " . ($index + 1) . " - empty product name or model number");
                    Log::info("Product name: '" . $row['product_name'] . "', Model number: '" . $row['model_number'] . "'");
                    continue;
                }

                // Validate required fields
                if (empty($row['category']) || empty($row['color']) || empty($row['sku'])) {
                    Log::info("Skipping row " . ($index + 1) . " - missing required fields (category, color, or sku)");
                    Log::info("Category: '" . $row['category'] . "', Color: '" . $row['color'] . "', SKU: '" . $row['sku'] . "'");
                    continue;
                }

                // Find or create category
                $category = Category::firstOrCreate(
                    ['name' => $row['category']],
                    [
                        'name' => $row['category'],
                        'slug' => \Str::slug($row['category'])
                    ]
                );

                // Find or create subcategory
                $subcategory = null;
                if (!empty($row['subcategory'])) {
                    $subcategory = Subcategory::firstOrCreate(
                        ['name' => $row['subcategory'], 'category_id' => $category->id],
                        [
                            'name' => $row['subcategory'],
                            'category_id' => $category->id,
                            'slug' => \Str::slug($row['subcategory'])
                        ]
                    );
                }

                // Find or create shape
                $shape = null;
                if (!empty($row['shape'])) {
                    $shape = Shape::firstOrCreate(
                        ['name' => $row['shape']],
                        ['name' => $row['shape']]
                    );
                }

                // Find or create color
                $color = Color::firstOrCreate(
                    ['name' => $row['color']],
                    ['name' => $row['color']]
                );

                // Create or update product
                $product = Product::updateOrCreate(
                    ['model_no' => $row['model_number']],
                    [
                        'category_id' => $category->id,
                        'subcategory_id' => $subcategory?->id,
                        'shape_id' => $shape?->id,
                        'gender' => $row['gender'] ?? 'unisex',
                        'name' => $row['product_name'],
                        'model_no' => $row['model_number'],
                        'description' => $row['description'] ?? '',
                        'base_price' => $row['base_price'] ?? 0,
                    ]
                );

                // Create or update variant
                $variant = ProductVariant::updateOrCreate(
                    ['sku' => $row['sku']],
                    [
                        'product_id' => $product->id,
                        'sku' => $row['sku'],
                        'color_id' => $color->id,
                        'price' => $row['price'] ?? $product->base_price,
                        'stock' => $row['stock'] ?? 0,
                    ]
                );

                // Handle image URLs
                if (!empty($row['image_urls_comma_separated'])) {
                    $this->processImages($variant, $row['image_urls_comma_separated'], $client);
                }

                $processedCount++;
                Log::info("Successfully processed row " . ($index + 1) . " - Product: " . $row['product_name']);

            } catch (\Exception $e) {
                Log::error('Error processing row ' . ($index + 1) . ': ' . $e->getMessage());
                // Continue with next row
            }
        }

        Log::info("Total processed count: " . $processedCount);
        return $processedCount;
    }

    private function processImages(ProductVariant $variant, string $imageUrls, Client $client)
    {
        $urls = array_map('trim', explode(',', $imageUrls));

        // Delete existing images for this variant
        foreach ($variant->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        foreach ($urls as $index => $url) {
            if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
                continue;
            }

            try {
                $this->downloadAndStoreImage($variant, $url, $index, $client);
            } catch (\Exception $e) {
                Log::warning("Failed to download image from {$url}: " . $e->getMessage());
                // Continue with other images even if one fails
            }
        }
    }

    private function downloadAndStoreImage(ProductVariant $variant, string $url, int $sortOrder, Client $client)
    {
        try {
            $response = $client->get($url);
            $imageContent = $response->getBody()->getContents();

            // Get file extension from URL or content type
            $extension = $this->getImageExtension($url, $response->getHeader('Content-Type')[0] ?? '');

            // Generate unique filename
            $filename = 'product_' . $variant->id . '_' . time() . '_' . $sortOrder . '.' . $extension;
            $path = 'products/' . $filename;

            // Store the image
            Storage::disk('public')->put($path, $imageContent);

            // Create database record
            ProductImage::create([
                'product_variant_id' => $variant->id,
                'image_path' => $path,
                'sort_order' => $sortOrder,
                'is_primary' => $sortOrder === 0, // First image is primary
            ]);

        } catch (RequestException $e) {
            Log::error("HTTP error downloading image from {$url}: " . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            Log::error("Error downloading image from {$url}: " . $e->getMessage());
            throw $e;
        }
    }

    private function getImageExtension(string $url, string $contentType): string
    {
        // Try to get extension from URL
        $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));
        if (!empty($pathInfo['extension'])) {
            return strtolower($pathInfo['extension']);
        }

        // Try to get extension from content type
        if (preg_match('/image\/(\w+)/', $contentType, $matches)) {
            $ext = strtolower($matches[1]);
            // Convert some common MIME types to file extensions
            $extensions = [
                'jpeg' => 'jpg',
                'png' => 'png',
                'gif' => 'gif',
                'webp' => 'webp',
                'bmp' => 'bmp',
            ];
            return $extensions[$ext] ?? 'jpg';
        }

        // Default to jpg
        return 'jpg';
    }
}
