<?php

/**
 * Y2K Image Generator for Maybeads
 * Generates aesthetic Y2K accessories graphics for categories and products using PHP GD.
 */

$fontBold = '/usr/share/fonts/google-carlito-fonts/Carlito-Bold.ttf';
$fontRegular = '/usr/share/fonts/google-carlito-fonts/Carlito-Regular.ttf';

$targetDirs = [
    __DIR__ . '/../../storage/app/public/categories',
    __DIR__ . '/../../storage/app/public/category',
    __DIR__ . '/../../storage/app/public/product',
    __DIR__ . '/../../public/images/products',
];

foreach ($targetDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

/**
 * Helper: draw filled 4-point Y2K star
 */
function drawY2KStar($img, $cx, $cy, $outerR, $innerR, $color) {
    $points = [];
    for ($i = 0; $i < 8; $i++) {
        $angle = $i * M_PI / 4;
        $r = ($i % 2 === 0) ? $outerR : $innerR;
        $points[] = $cx + (int)($r * cos($angle));
        $points[] = $cy + (int)($r * sin($angle));
    }
    imagefilledpolygon($img, $points, $color);
}

/**
 * Helper: draw glossy spherical bead
 */
function drawGlossyBead($img, $cx, $cy, $radius, $mainRgb, $highlightRgb = [255, 255, 255]) {
    // base shadow circle
    $shadowCol = imagecolorallocatealpha($img, max(0, $mainRgb[0] - 60), max(0, $mainRgb[1] - 60), max(0, $mainRgb[2] - 60), 30);
    imagefilledellipse($img, $cx + 2, $cy + 3, $radius * 2, $radius * 2, $shadowCol);

    // main bead circle
    $baseCol = imagecolorallocate($img, $mainRgb[0], $mainRgb[1], $mainRgb[2]);
    imagefilledellipse($img, $cx, $cy, $radius * 2, $radius * 2, $baseCol);

    // inner light gradient
    $glowCol = imagecolorallocatealpha($img, min(255, $mainRgb[0] + 40), min(255, $mainRgb[1] + 40), min(255, $mainRgb[2] + 40), 50);
    imagefilledellipse($img, $cx - (int)($radius * 0.2), $cy - (int)($radius * 0.2), (int)($radius * 1.3), (int)($radius * 1.3), $glowCol);

    // specular highlight
    $hiCol = imagecolorallocatealpha($img, $highlightRgb[0], $highlightRgb[1], $highlightRgb[2], 20);
    imagefilledellipse($img, $cx - (int)($radius * 0.4), $cy - (int)($radius * 0.4), (int)($radius * 0.6), (int)($radius * 0.5), $hiCol);

    $whiteCol = imagecolorallocatealpha($img, 255, 255, 255, 10);
    imagefilledellipse($img, $cx - (int)($radius * 0.45), $cy - (int)($radius * 0.45), (int)($radius * 0.25), (int)($radius * 0.2), $whiteCol);
}

/**
 * Helper: draw Y2K butterfly silhouette
 */
function drawY2KButterfly($img, $cx, $cy, $size, $color1, $color2) {
    // 4 wings
    $wTop = (int)($size * 0.9);
    $hTop = (int)($size * 0.7);
    $wBtm = (int)($size * 0.65);
    $hBtm = (int)($size * 0.55);

    // Top left & right wings
    imagefilledellipse($img, $cx - (int)($size * 0.55), $cy - (int)($size * 0.35), $wTop, $hTop, $color1);
    imagefilledellipse($img, $cx + (int)($size * 0.55), $cy - (int)($size * 0.35), $wTop, $hTop, $color1);

    // Bottom left & right wings
    imagefilledellipse($img, $cx - (int)($size * 0.4), $cy + (int)($size * 0.35), $wBtm, $hBtm, $color2);
    imagefilledellipse($img, $cx + (int)($size * 0.4), $cy + (int)($size * 0.35), $wBtm, $hBtm, $color2);

    // Inner wing glow
    $innerCol = imagecolorallocatealpha($img, 255, 255, 255, 70);
    imagefilledellipse($img, $cx - (int)($size * 0.55), $cy - (int)($size * 0.35), (int)($wTop * 0.5), (int)($hTop * 0.5), $innerCol);
    imagefilledellipse($img, $cx + (int)($size * 0.55), $cy - (int)($size * 0.35), (int)($wTop * 0.5), (int)($hTop * 0.5), $innerCol);

    // Butterfly body & head
    $bodyCol = imagecolorallocate($img, 36, 65, 115); // Navy Maybeads
    imagefilledellipse($img, $cx, $cy, (int)($size * 0.2), (int)($size * 0.9), $bodyCol);
    imagefilledellipse($img, $cx, $cy - (int)($size * 0.5), (int)($size * 0.22), (int)($size * 0.22), $bodyCol);
}

/**
 * Generate a single aesthetic Y2K image
 */
function createY2KImage($config, $fontBold, $fontRegular) {
    $w = 600;
    $h = 600;
    $img = imagecreatetruecolor($w, $h);
    imagesavealpha($img, true);

    // 1. Draw gradient background
    $topColor = $config['bgTop'];
    $btmColor = $config['bgBtm'];

    for ($y = 0; $y < $h; $y++) {
        $ratio = $y / (float)$h;
        $r = (int)($topColor[0] + ($btmColor[0] - $topColor[0]) * $ratio);
        $g = (int)($topColor[1] + ($btmColor[1] - $topColor[1]) * $ratio);
        $b = (int)($topColor[2] + ($btmColor[2] - $topColor[2]) * $ratio);
        $lineColor = imagecolorallocate($img, $r, $g, $b);
        imageline($img, 0, $y, $w, $y, $lineColor);
    }

    // 2. Draw Y2K retro grid / pattern subtle
    $gridCol = imagecolorallocatealpha($img, 255, 255, 255, 110);
    for ($gx = 30; $gx < $w; $gx += 50) {
        imageline($img, $gx, 0, $gx, $h, $gridCol);
    }
    for ($gy = 30; $gy < $h; $gy += 50) {
        imageline($img, 0, $gy, $w, $gy, $gridCol);
    }

    // 3. Central decorative glow aura
    $auraCol = imagecolorallocatealpha($img, 255, 255, 255, 85);
    imagefilledellipse($img, 300, 270, 420, 420, $auraCol);
    $auraCol2 = imagecolorallocatealpha($img, 255, 255, 255, 95);
    imagefilledellipse($img, 300, 270, 320, 320, $auraCol2);

    // 4. Draw sparkles (4-point stars) in background
    $sparkleCol = imagecolorallocatealpha($img, 255, 255, 255, 15);
    $silverSparkle = imagecolorallocatealpha($img, 220, 230, 245, 10);
    $accentCol = imagecolorallocatealpha($img, $config['accentRgb'][0], $config['accentRgb'][1], $config['accentRgb'][2], 20);

    drawY2KStar($img, 80, 100, 26, 6, $sparkleCol);
    drawY2KStar($img, 520, 120, 32, 8, $sparkleCol);
    drawY2KStar($img, 110, 420, 22, 5, $sparkleCol);
    drawY2KStar($img, 500, 400, 28, 7, $accentCol);
    drawY2KStar($img, 460, 230, 18, 4, $silverSparkle);
    drawY2KStar($img, 140, 220, 20, 5, $silverSparkle);

    // 5. Draw the item illustration based on type
    $type = $config['type'] ?? 'necklace';
    $mainRgb = $config['beadRgb'] ?? [255, 182, 193];
    $secRgb = $config['secBeadRgb'] ?? [186, 225, 255];

    if ($type === 'hairclip') {
        // Draw Y2K Hair Clip / Snap clips
        $clipCol = imagecolorallocate($img, 215, 225, 235);
        $clipBorder = imagecolorallocate($img, 160, 175, 200);

        // Clip 1 (slanted)
        imagefilledpolygon($img, [180, 200, 400, 240, 420, 270, 190, 240], $clipCol);
        imagepolygon($img, [180, 200, 400, 240, 420, 270, 190, 240], $clipBorder);

        // Clip 2 (slanted other way)
        imagefilledpolygon($img, [190, 300, 410, 260, 430, 290, 200, 330], $clipCol);
        imagepolygon($img, [190, 300, 410, 260, 430, 290, 200, 330], $clipBorder);

        // Chrome stars on the clips
        drawY2KStar($img, 230, 215, 36, 10, $sparkleCol);
        drawY2KStar($img, 370, 275, 42, 12, $accentCol);

        // Beads along the top clip
        for ($i = 0; $i < 5; $i++) {
            $bx = 270 + $i * 25;
            $by = 225 + $i * 5;
            drawGlossyBead($img, $bx, $by, 8, ($i % 2 === 0 ? $mainRgb : $secRgb));
        }
    } elseif ($type === 'ring') {
        // Draw 3D chunky Y2K Ring
        $ringOuterR = 85;
        $ringInnerR = 48;
        $ringCol = imagecolorallocate($img, $mainRgb[0], $mainRgb[1], $mainRgb[2]);
        $ringHi = imagecolorallocatealpha($img, 255, 255, 255, 40);

        imagefilledellipse($img, 300, 260, $ringOuterR * 2, $ringOuterR * 2, $ringCol);
        imagefilledellipse($img, 285, 245, (int)($ringOuterR * 1.5), (int)($ringOuterR * 1.4), $ringHi);

        // Ring hole
        $holeCol = imagecolorallocatealpha($img, 245, 247, 252, 0);
        imagefilledellipse($img, 300, 260, $ringInnerR * 2, $ringInnerR * 2, $holeCol);

        // Gem / Charm on top of the ring
        if (($config['charm'] ?? 'heart') === 'butterfly') {
            $w1 = imagecolorallocate($img, $secRgb[0], $secRgb[1], $secRgb[2]);
            $w2 = imagecolorallocate($img, 255, 255, 255);
            drawY2KButterfly($img, 300, 185, 36, $w1, $w2);
        } elseif (($config['charm'] ?? 'heart') === 'star') {
            drawY2KStar($img, 300, 185, 38, 12, $sparkleCol);
        } else {
            // Chunky Heart
            $heartCol = imagecolorallocate($img, min(255, $mainRgb[0] + 30), max(0, $mainRgb[1] - 20), min(255, $mainRgb[2] + 20));
            imagefilledellipse($img, 285, 180, 36, 36, $heartCol);
            imagefilledellipse($img, 315, 180, 36, 36, $heartCol);
            imagefilledpolygon($img, [270, 187, 330, 187, 300, 215], $heartCol);
            drawY2KStar($img, 300, 185, 15, 4, $sparkleCol);
        }
    } elseif ($type === 'bracelet') {
        // Draw Beaded Bracelet in a circle loop
        $numBeads = 18;
        $rx = 115;
        $ry = 95;
        $cx = 300;
        $cy = 250;

        for ($i = 0; $i < $numBeads; $i++) {
            $angle = $i * (2 * M_PI / $numBeads);
            $bx = (int)($cx + $rx * cos($angle));
            $by = (int)($cy + $ry * sin($angle));
            $beadColor = ($i % 2 === 0) ? $mainRgb : $secRgb;
            $radius = ($i % 3 === 0) ? 14 : 11;
            drawGlossyBead($img, $bx, $by, $radius, $beadColor);
        }

        // Central hanging charm
        if (($config['charm'] ?? 'star') === 'butterfly') {
            $w1 = imagecolorallocate($img, $secRgb[0], $secRgb[1], $secRgb[2]);
            $w2 = imagecolorallocate($img, $mainRgb[0], $mainRgb[1], $mainRgb[2]);
            drawY2KButterfly($img, 300, 345, 34, $w1, $w2);
        } else {
            drawY2KStar($img, 300, 345, 32, 10, $sparkleCol);
        }
    } elseif ($type === 'necklace') {
        // Draw Beaded Choker / Necklace curve
        $cx = 300;
        $cy = 160;
        $numBeads = 21;

        for ($i = 0; $i < $numBeads; $i++) {
            $t = ($i / ($numBeads - 1)) * M_PI; // 0 to PI
            $bx = (int)($cx + 145 * cos($t - M_PI));
            $by = (int)($cy + 125 * sin($t));
            $beadColor = ($i % 3 === 0) ? [240, 240, 250] : (($i % 2 === 0) ? $mainRgb : $secRgb);
            $radius = ($i === (int)($numBeads / 2)) ? 15 : (($i % 2 === 0) ? 12 : 9);
            drawGlossyBead($img, $bx, $by, $radius, $beadColor);
        }

        // Statement Center Pendant
        $pendantY = 285;
        $linkCol = imagecolorallocate($img, 180, 190, 205);
        imageline($img, 300, 285, 300, 310, $linkCol);

        if (($config['charm'] ?? 'star') === 'butterfly') {
            $w1 = imagecolorallocate($img, $mainRgb[0], $mainRgb[1], $mainRgb[2]);
            $w2 = imagecolorallocate($img, $secRgb[0], $secRgb[1], $secRgb[2]);
            drawY2KButterfly($img, 300, 335, 42, $w1, $w2);
        } elseif (($config['charm'] ?? 'star') === 'heart') {
            $heartCol = imagecolorallocate($img, 240, 100, 140);
            imagefilledellipse($img, 285, 325, 40, 40, $heartCol);
            imagefilledellipse($img, 315, 325, 40, 40, $heartCol);
            imagefilledpolygon($img, [267, 332, 333, 332, 300, 365], $heartCol);
            drawY2KStar($img, 300, 330, 16, 5, $sparkleCol);
        } else {
            drawY2KStar($img, 300, 335, 42, 13, $sparkleCol);
        }
    } elseif ($type === 'strap') {
        // Draw Phone Strap / Keychain lanyard
        $strapCord = imagecolorallocate($img, 45, 55, 75);
        // Cord loop at top
        imagearc($img, 300, 150, 40, 60, 180, 360, $strapCord);
        imageline($img, 280, 150, 300, 180, $strapCord);
        imageline($img, 320, 150, 300, 180, $strapCord);

        // String of diverse colorful beads hanging down
        $sy = 195;
        for ($i = 0; $i < 9; $i++) {
            $bx = 300 + (int)(sin($i * 0.8) * 8);
            $by = $sy + ($i * 20);
            $beadCol = ($i % 3 === 0) ? $mainRgb : (($i % 3 === 1) ? $secRgb : [255, 255, 255]);
            drawGlossyBead($img, $bx, $by, 11, $beadCol);
        }

        // Big charm at bottom
        $charmY = $sy + (9 * 20) + 15;
        if (($config['charm'] ?? 'star') === 'heart') {
            $heartCol = imagecolorallocate($img, 255, 120, 160);
            imagefilledellipse($img, 288, $charmY, 32, 32, $heartCol);
            imagefilledellipse($img, 312, $charmY, 32, 32, $heartCol);
            imagefilledpolygon($img, [274, $charmY + 5, 326, $charmY + 5, 300, $charmY + 30], $heartCol);
            drawY2KStar($img, 300, $charmY + 5, 14, 4, $sparkleCol);
        } else {
            drawY2KStar($img, 300, $charmY + 10, 38, 12, $sparkleCol);
        }
    }

    // 6. Header Tag (TOP BADGE)
    $badgeBg = imagecolorallocatealpha($img, 255, 255, 255, 25);
    $badgeBorder = imagecolorallocatealpha($img, 255, 255, 255, 60);
    $badgeTextCol = imagecolorallocate($img, 36, 65, 115); // Navy Maybeads

    imagefilledrectangle($img, 160, 24, 440, 56, $badgeBg);
    imagerectangle($img, 160, 24, 440, 56, $badgeBorder);
    $topTitle = "MAYBEADS ★ Y2K STUDIO";
    $bbox = imagettfbbox(12, 0, $fontBold, $topTitle);
    $textW = $bbox[2] - $bbox[0];
    imagettftext($img, 12, 0, (int)(300 - $textW / 2), 44, $badgeTextCol, $fontBold, $topTitle);

    // 7. Bottom Card Showcase
    $cardBg = imagecolorallocatealpha($img, 255, 255, 255, 20); // semi-translucent frosted glass
    $cardBorder = imagecolorallocatealpha($img, 255, 255, 255, 45);
    $textColor = imagecolorallocate($img, 26, 43, 76); // deep navy
    $catTextColor = imagecolorallocate($img, 100, 116, 139); // slate-500
    $priceColor = imagecolorallocate($img, 225, 29, 72); // rose red

    // Card frame
    imagefilledrectangle($img, 30, 470, 570, 570, $cardBg);
    imagerectangle($img, 30, 470, 570, 570, $cardBorder);

    // Category / Tag text
    $catText = strtoupper($config['categoryTag'] ?? 'Y2K ACCESSORY');
    imagettftext($img, 10, 0, 50, 498, $catTextColor, $fontBold, $catText);

    // Product Title
    $title = $config['title'] ?? 'Aesthetic Accessory';
    // Truncate if too long
    if (strlen($title) > 34) {
        $title = substr($title, 0, 32) . '..';
    }
    imagettftext($img, 15, 0, 50, 526, $textColor, $fontBold, $title);

    // Price tag or subtitle
    $sub = $config['subtitle'] ?? 'Handmade Beads Collection';
    imagettftext($img, 12, 0, 50, 552, $priceColor, $fontBold, $sub);

    // Corner Y2K Star decoration
    drawY2KStar($img, 535, 520, 22, 6, $accentCol);

    return $img;
}

echo "Starting generation of Y2K images...\n";

// -------------------------------------------------------------
// A. GENERATE 5 MAIN CATEGORIES
// -------------------------------------------------------------
$categoriesConfig = [
    [
        'filename'    => 'cat-hairclip.png',
        'title'       => 'Jepit Rambut',
        'categoryTag' => 'CATEGORY 01',
        'subtitle'    => 'Hair Clips & Star Barrettes',
        'type'        => 'hairclip',
        'charm'       => 'star',
        'bgTop'       => [255, 220, 230], // soft pink
        'bgBtm'       => [230, 215, 255], // soft lilac
        'beadRgb'     => [255, 140, 180],
        'secBeadRgb'  => [180, 210, 255],
        'accentRgb'   => [255, 105, 180],
    ],
    [
        'filename'    => 'cat-ring.png',
        'title'       => 'Cincin Y2K',
        'categoryTag' => 'CATEGORY 02',
        'subtitle'    => 'Resin & Cyber Chrome Rings',
        'type'        => 'ring',
        'charm'       => 'heart',
        'bgTop'       => [225, 240, 255], // ice blue
        'bgBtm'       => [240, 225, 255], // purple
        'beadRgb'     => [160, 200, 255],
        'secBeadRgb'  => [255, 180, 220],
        'accentRgb'   => [100, 149, 237],
    ],
    [
        'filename'    => 'cat-bracelet.png',
        'title'       => 'Gelang Manik',
        'categoryTag' => 'CATEGORY 03',
        'subtitle'    => 'Beaded Bracelets & Bangles',
        'type'        => 'bracelet',
        'charm'       => 'butterfly',
        'bgTop'       => [220, 255, 245], // mint pastel
        'bgBtm'       => [215, 235, 255], // sky blue
        'beadRgb'     => [120, 220, 200],
        'secBeadRgb'  => [255, 190, 220],
        'accentRgb'   => [72, 209, 204],
    ],
    [
        'filename'    => 'cat-necklace.png',
        'title'       => 'Kalung Manik',
        'categoryTag' => 'CATEGORY 04',
        'subtitle'    => 'Chokers & Beaded Necklaces',
        'type'        => 'necklace',
        'charm'       => 'star',
        'bgTop'       => [245, 225, 255], // lilac
        'bgBtm'       => [255, 230, 240], // rose
        'beadRgb'     => [210, 150, 255],
        'secBeadRgb'  => [255, 215, 0],
        'accentRgb'   => [186, 85, 211],
    ],
    [
        'filename'    => 'cat-strap.png',
        'title'       => 'Phone Strap & Keychain',
        'categoryTag' => 'CATEGORY 05',
        'subtitle'    => 'Charms & Lanyards Y2K',
        'type'        => 'strap',
        'charm'       => 'heart',
        'bgTop'       => [255, 245, 215], // vanilla
        'bgBtm'       => [255, 220, 235], // strawberry
        'beadRgb'     => [255, 165, 0],
        'secBeadRgb'  => [255, 105, 180],
        'accentRgb'   => [255, 99, 71],
    ],
];

foreach ($categoriesConfig as $cat) {
    $img = createY2KImage($cat, $fontBold, $fontRegular);
    $filename = $cat['filename'];

    // Save into categories, category, and public/images/products
    imagepng($img, __DIR__ . '/../../storage/app/public/categories/' . $filename);
    imagepng($img, __DIR__ . '/../../storage/app/public/category/' . $filename);
    imagepng($img, __DIR__ . '/../../public/images/products/' . $filename);
    imagedestroy($img);
    echo "Generated category image: $filename\n";
}

// -------------------------------------------------------------
// B. GENERATE 32 PRODUCT IMAGES
// -------------------------------------------------------------
$productsConfig = [
    // 1-6 Jepit Rambut
    [
        'id' => 1, 'filename' => 'y2k-prod-01.png', 'type' => 'hairclip', 'charm' => 'star',
        'title' => 'Star Hairclip Chrome Y2K', 'categoryTag' => 'JEPIT RAMBUT', 'subtitle' => 'Rp 35.000',
        'bgTop' => [255, 225, 235], 'bgBtm' => [235, 215, 255], 'beadRgb' => [255, 140, 180], 'secBeadRgb' => [200, 220, 255], 'accentRgb' => [255, 105, 180]
    ],
    [
        'id' => 2, 'filename' => 'y2k-prod-02.png', 'type' => 'hairclip', 'charm' => 'butterfly',
        'title' => 'Butterfly Metallic Snap Clip', 'categoryTag' => 'JEPIT RAMBUT', 'subtitle' => 'Rp 28.000',
        'bgTop' => [230, 220, 255], 'bgBtm' => [215, 235, 255], 'beadRgb' => [180, 160, 255], 'secBeadRgb' => [255, 180, 210], 'accentRgb' => [147, 112, 219]
    ],
    [
        'id' => 3, 'filename' => 'y2k-prod-03.png', 'type' => 'hairclip', 'charm' => 'heart',
        'title' => 'Cyber Glitz Heart Hairpin', 'categoryTag' => 'JEPIT RAMBUT', 'subtitle' => 'Rp 32.000',
        'bgTop' => [255, 215, 240], 'bgBtm' => [255, 235, 215], 'beadRgb' => [255, 100, 150], 'secBeadRgb' => [255, 210, 140], 'accentRgb' => [255, 20, 147]
    ],
    [
        'id' => 4, 'filename' => 'y2k-prod-04.png', 'type' => 'hairclip', 'charm' => 'star',
        'title' => 'Pastel Beads Bobby Pin Set', 'categoryTag' => 'JEPIT RAMBUT', 'subtitle' => 'Rp 24.000',
        'bgTop' => [225, 255, 240], 'bgBtm' => [235, 230, 255], 'beadRgb' => [140, 230, 200], 'secBeadRgb' => [200, 180, 255], 'accentRgb' => [60, 179, 113]
    ],
    [
        'id' => 5, 'filename' => 'y2k-prod-05.png', 'type' => 'hairclip', 'charm' => 'star',
        'title' => 'Y2K Hologram Star Barrette', 'categoryTag' => 'JEPIT RAMBUT', 'subtitle' => 'Rp 36.000',
        'bgTop' => [220, 240, 255], 'bgBtm' => [255, 220, 250], 'beadRgb' => [130, 180, 255], 'secBeadRgb' => [255, 140, 220], 'accentRgb' => [30, 144, 255]
    ],
    [
        'id' => 6, 'filename' => 'y2k-prod-06.png', 'type' => 'hairclip', 'charm' => 'butterfly',
        'title' => 'Angel Wings Mini Claw Clip', 'categoryTag' => 'JEPIT RAMBUT', 'subtitle' => 'Rp 27.000',
        'bgTop' => [245, 240, 255], 'bgBtm' => [230, 245, 255], 'beadRgb' => [220, 210, 255], 'secBeadRgb' => [180, 225, 255], 'accentRgb' => [138, 43, 226]
    ],

    // 7-12 Cincin Y2K
    [
        'id' => 7, 'filename' => 'y2k-prod-07.png', 'type' => 'ring', 'charm' => 'star',
        'title' => 'Cyber Cross Silver Ring', 'categoryTag' => 'CINCIN', 'subtitle' => 'Rp 45.000',
        'bgTop' => [220, 230, 245], 'bgBtm' => [240, 230, 255], 'beadRgb' => [190, 205, 225], 'secBeadRgb' => [230, 235, 245], 'accentRgb' => [70, 130, 180]
    ],
    [
        'id' => 8, 'filename' => 'y2k-prod-08.png', 'type' => 'ring', 'charm' => 'heart',
        'title' => 'Chunky Resin Heart Ring', 'categoryTag' => 'CINCIN', 'subtitle' => 'Rp 25.000',
        'bgTop' => [255, 220, 240], 'bgBtm' => [255, 240, 220], 'beadRgb' => [255, 120, 170], 'secBeadRgb' => [255, 200, 130], 'accentRgb' => [255, 69, 0]
    ],
    [
        'id' => 9, 'filename' => 'y2k-prod-09.png', 'type' => 'ring', 'charm' => 'star',
        'title' => 'Metallic Thorn Band Ring', 'categoryTag' => 'CINCIN', 'subtitle' => 'Rp 48.000',
        'bgTop' => [215, 225, 240], 'bgBtm' => [225, 215, 235], 'beadRgb' => [170, 185, 210], 'secBeadRgb' => [210, 210, 225], 'accentRgb' => [105, 105, 105]
    ],
    [
        'id' => 10, 'filename' => 'y2k-prod-10.png', 'type' => 'ring', 'charm' => 'heart',
        'title' => 'Pastel Daisy Beaded Ring', 'categoryTag' => 'CINCIN', 'subtitle' => 'Rp 18.000',
        'bgTop' => [255, 250, 225], 'bgBtm' => [235, 255, 235], 'beadRgb' => [255, 215, 80], 'secBeadRgb' => [150, 230, 170], 'accentRgb' => [255, 165, 0]
    ],
    [
        'id' => 11, 'filename' => 'y2k-prod-11.png', 'type' => 'ring', 'charm' => 'butterfly',
        'title' => 'Alien Glow Mood Ring', 'categoryTag' => 'CINCIN', 'subtitle' => 'Rp 38.000',
        'bgTop' => [215, 255, 235], 'bgBtm' => [210, 235, 255], 'beadRgb' => [90, 220, 180], 'secBeadRgb' => [100, 190, 255], 'accentRgb' => [0, 206, 209]
    ],
    [
        'id' => 12, 'filename' => 'y2k-prod-12.png', 'type' => 'ring', 'charm' => 'star',
        'title' => 'Silver Liquid Drip Ring', 'categoryTag' => 'CINCIN', 'subtitle' => 'Rp 52.000',
        'bgTop' => [235, 240, 250], 'bgBtm' => [220, 225, 240], 'beadRgb' => [195, 205, 220], 'secBeadRgb' => [150, 165, 185], 'accentRgb' => [112, 128, 144]
    ],

    // 13-18 Gelang Manik
    [
        'id' => 13, 'filename' => 'y2k-prod-13.png', 'type' => 'bracelet', 'charm' => 'butterfly',
        'title' => 'Butterfly Glass Beads Bracelet', 'categoryTag' => 'GELANG MANIK', 'subtitle' => 'Rp 42.000',
        'bgTop' => [225, 245, 255], 'bgBtm' => [245, 225, 255], 'beadRgb' => [130, 195, 255], 'secBeadRgb' => [255, 160, 210], 'accentRgb' => [65, 105, 225]
    ],
    [
        'id' => 14, 'filename' => 'y2k-prod-14.png', 'type' => 'bracelet', 'charm' => 'star',
        'title' => 'Cyber Star Beaded Bracelet', 'categoryTag' => 'GELANG MANIK', 'subtitle' => 'Rp 39.000',
        'bgTop' => [230, 230, 250], 'bgBtm' => [215, 240, 255], 'beadRgb' => [150, 150, 240], 'secBeadRgb' => [200, 225, 255], 'accentRgb' => [106, 90, 205]
    ],
    [
        'id' => 15, 'filename' => 'y2k-prod-15.png', 'type' => 'bracelet', 'charm' => 'star',
        'title' => 'Pastel Pearl Smile Charm', 'categoryTag' => 'GELANG MANIK', 'subtitle' => 'Rp 34.000',
        'bgTop' => [255, 245, 230], 'bgBtm' => [255, 225, 240], 'beadRgb' => [255, 200, 110], 'secBeadRgb' => [255, 160, 190], 'accentRgb' => [255, 140, 0]
    ],
    [
        'id' => 16, 'filename' => 'y2k-prod-16.png', 'type' => 'bracelet', 'charm' => 'butterfly',
        'title' => 'Fairycore Crystal Beads Bangle', 'categoryTag' => 'GELANG MANIK', 'subtitle' => 'Rp 46.000',
        'bgTop' => [240, 225, 255], 'bgBtm' => [220, 250, 240], 'beadRgb' => [195, 150, 255], 'secBeadRgb' => [140, 230, 200], 'accentRgb' => [148, 0, 211]
    ],
    [
        'id' => 17, 'filename' => 'y2k-prod-17.png', 'type' => 'bracelet', 'charm' => 'star',
        'title' => 'Retro Pixel Heart Bracelet', 'categoryTag' => 'GELANG MANIK', 'subtitle' => 'Rp 33.000',
        'bgTop' => [255, 220, 225], 'bgBtm' => [235, 225, 250], 'beadRgb' => [255, 90, 120], 'secBeadRgb' => [190, 160, 240], 'accentRgb' => [220, 20, 60]
    ],
    [
        'id' => 18, 'filename' => 'y2k-prod-18.png', 'type' => 'bracelet', 'charm' => 'star',
        'title' => 'Galaxy Glow Beaded Armband', 'categoryTag' => 'GELANG MANIK', 'subtitle' => 'Rp 40.000',
        'bgTop' => [215, 225, 255], 'bgBtm' => [210, 245, 240], 'beadRgb' => [100, 160, 255], 'secBeadRgb' => [120, 240, 210], 'accentRgb' => [30, 144, 255]
    ],

    // 19-24 Kalung Manik
    [
        'id' => 19, 'filename' => 'y2k-prod-19.png', 'type' => 'necklace', 'charm' => 'star',
        'title' => 'Y2K Silver Star Choker', 'categoryTag' => 'KALUNG MANIK', 'subtitle' => 'Rp 58.000',
        'bgTop' => [235, 235, 250], 'bgBtm' => [250, 225, 240], 'beadRgb' => [180, 195, 225], 'secBeadRgb' => [240, 170, 210], 'accentRgb' => [70, 130, 180]
    ],
    [
        'id' => 20, 'filename' => 'y2k-prod-20.png', 'type' => 'necklace', 'charm' => 'heart',
        'title' => 'Candy Heart Pastel Necklace', 'categoryTag' => 'KALUNG MANIK', 'subtitle' => 'Rp 49.000',
        'bgTop' => [255, 235, 245], 'bgBtm' => [255, 245, 225], 'beadRgb' => [255, 140, 185], 'secBeadRgb' => [255, 210, 120], 'accentRgb' => [255, 20, 147]
    ],
    [
        'id' => 21, 'filename' => 'y2k-prod-21.png', 'type' => 'necklace', 'charm' => 'star',
        'title' => 'Cyberpunk Cross Pearl Choker', 'categoryTag' => 'KALUNG MANIK', 'subtitle' => 'Rp 65.000',
        'bgTop' => [225, 230, 245], 'bgBtm' => [215, 220, 235], 'beadRgb' => [245, 245, 255], 'secBeadRgb' => [140, 150, 175], 'accentRgb' => [47, 79, 79]
    ],
    [
        'id' => 22, 'filename' => 'y2k-prod-22.png', 'type' => 'necklace', 'charm' => 'butterfly',
        'title' => 'Butterfly Garden Beads Choker', 'categoryTag' => 'KALUNG MANIK', 'subtitle' => 'Rp 54.000',
        'bgTop' => [240, 230, 255], 'bgBtm' => [230, 250, 240], 'beadRgb' => [190, 150, 250], 'secBeadRgb' => [150, 230, 190], 'accentRgb' => [138, 43, 226]
    ],
    [
        'id' => 23, 'filename' => 'y2k-prod-23.png', 'type' => 'necklace', 'charm' => 'heart',
        'title' => 'Liquid Metal Heart Choker', 'categoryTag' => 'KALUNG MANIK', 'subtitle' => 'Rp 62.000',
        'bgTop' => [245, 230, 240], 'bgBtm' => [225, 230, 245], 'beadRgb' => [215, 190, 215], 'secBeadRgb' => [165, 180, 215], 'accentRgb' => [199, 21, 133]
    ],
    [
        'id' => 24, 'filename' => 'y2k-prod-24.png', 'type' => 'necklace', 'charm' => 'star',
        'title' => 'Iridescent Star Pearl Necklace', 'categoryTag' => 'KALUNG MANIK', 'subtitle' => 'Rp 56.000',
        'bgTop' => [230, 245, 255], 'bgBtm' => [255, 235, 245], 'beadRgb' => [255, 255, 255], 'secBeadRgb' => [170, 215, 255], 'accentRgb' => [0, 191, 255]
    ],

    // 25-32 Phone Strap & Keychain
    [
        'id' => 25, 'filename' => 'y2k-prod-25.png', 'type' => 'strap', 'charm' => 'star',
        'title' => 'Metallic Star Cyber Keychain', 'categoryTag' => 'KEYCHAIN & STRAP', 'subtitle' => 'Rp 38.000',
        'bgTop' => [225, 235, 245], 'bgBtm' => [240, 230, 255], 'beadRgb' => [190, 205, 230], 'secBeadRgb' => [225, 230, 240], 'accentRgb' => [70, 130, 180]
    ],
    [
        'id' => 26, 'filename' => 'y2k-prod-26.png', 'type' => 'strap', 'charm' => 'heart',
        'title' => 'Y2K Angel Heart Phone Strap', 'categoryTag' => 'KEYCHAIN & STRAP', 'subtitle' => 'Rp 32.000',
        'bgTop' => [255, 235, 240], 'bgBtm' => [240, 240, 255], 'beadRgb' => [255, 160, 190], 'secBeadRgb' => [215, 220, 255], 'accentRgb' => [255, 20, 147]
    ],
    [
        'id' => 27, 'filename' => 'y2k-prod-27.png', 'type' => 'strap', 'charm' => 'heart',
        'title' => 'Kawaii Gummy Bear Phone Strap', 'categoryTag' => 'KEYCHAIN & STRAP', 'subtitle' => 'Rp 29.000',
        'bgTop' => [255, 245, 220], 'bgBtm' => [255, 220, 235], 'beadRgb' => [255, 170, 80], 'secBeadRgb' => [255, 120, 170], 'accentRgb' => [255, 69, 0]
    ],
    [
        'id' => 28, 'filename' => 'y2k-prod-28.png', 'type' => 'strap', 'charm' => 'star',
        'title' => 'Holographic Butterfly Keychain', 'categoryTag' => 'KEYCHAIN & STRAP', 'subtitle' => 'Rp 35.000',
        'bgTop' => [230, 245, 255], 'bgBtm' => [250, 225, 255], 'beadRgb' => [140, 200, 255], 'secBeadRgb' => [225, 160, 255], 'accentRgb' => [138, 43, 226]
    ],
    [
        'id' => 29, 'filename' => 'y2k-prod-29.png', 'type' => 'strap', 'charm' => 'star',
        'title' => 'Cyber Matrix Beaded Lanyard', 'categoryTag' => 'KEYCHAIN & STRAP', 'subtitle' => 'Rp 45.000',
        'bgTop' => [215, 250, 235], 'bgBtm' => [220, 235, 250], 'beadRgb' => [90, 215, 175], 'secBeadRgb' => [150, 190, 240], 'accentRgb' => [0, 128, 128]
    ],
    [
        'id' => 30, 'filename' => 'y2k-prod-30.png', 'type' => 'strap', 'charm' => 'star',
        'title' => 'Glow In Dark Star Charm', 'categoryTag' => 'KEYCHAIN & STRAP', 'subtitle' => 'Rp 31.000',
        'bgTop' => [230, 255, 235], 'bgBtm' => [215, 240, 255], 'beadRgb' => [150, 250, 180], 'secBeadRgb' => [140, 210, 255], 'accentRgb' => [50, 205, 50]
    ],
    [
        'id' => 31, 'filename' => 'y2k-prod-31.png', 'type' => 'strap', 'charm' => 'heart',
        'title' => 'Bubblegum Heart Beaded Strap', 'categoryTag' => 'KEYCHAIN & STRAP', 'subtitle' => 'Rp 33.000',
        'bgTop' => [255, 225, 245], 'bgBtm' => [245, 230, 255], 'beadRgb' => [255, 130, 190], 'secBeadRgb' => [210, 170, 250], 'accentRgb' => [255, 20, 147]
    ],
    [
        'id' => 32, 'filename' => 'y2k-prod-32.png', 'type' => 'strap', 'charm' => 'star',
        'title' => 'Retro Tamagotchi Keychain', 'categoryTag' => 'KEYCHAIN & STRAP', 'subtitle' => 'Rp 44.000',
        'bgTop' => [255, 250, 225], 'bgBtm' => [220, 245, 255], 'beadRgb' => [255, 210, 90], 'secBeadRgb' => [130, 205, 255], 'accentRgb' => [255, 140, 0]
    ],
];

foreach ($productsConfig as $prod) {
    $img = createY2KImage($prod, $fontBold, $fontRegular);
    $filename = $prod['filename'];

    // Save to product storage and public/images/products
    imagepng($img, __DIR__ . '/../../storage/app/public/product/' . $filename);
    imagepng($img, __DIR__ . '/../../public/images/products/' . $filename);
    imagedestroy($img);
    echo "Generated product image: $filename\n";
}

echo "All 5 category images and 32 product images successfully generated!\n";

