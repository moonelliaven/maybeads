<?php

/**
 * Realistic Image Processor for Maybeads Y2K Accessories
 * Wrapper that executes generate_unique_product_photos.py to ensure
 * all 32 products have unique primary photos and dedicated secondary photos.
 */

$pyScript = __DIR__ . '/generate_unique_product_photos.py';
if (file_exists($pyScript)) {
    exec("python3 " . escapeshellarg($pyScript) . " 2>&1", $output, $status);
    if ($status === 0) {
        echo implode("\n", $output) . "\n";
    }
}
