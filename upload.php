<?php
if (isset($_POST['submit'])) {
    $images = array_slice($_FILES['images']['tmp_name'], 0, 10); // Limite à 10 images
    processImageUpload($images);
    header('Location: index.php');
}

function processImageUpload($images) {
    $uploadDir = 'uploads/';
    ensureUploadDirExists($uploadDir);

    foreach ($images as $tmpFilePath) {
        if ($tmpFilePath) {
            $newFilePath = generateNewFilePath($uploadDir);
            if (isValidImage($tmpFilePath)) {
                resizeAndSaveImage($tmpFilePath, $newFilePath);
            } else {
                echo "Le fichier n'est pas une image valide.";
            }
        }
    }
}

function ensureUploadDirExists($dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
}

function generateNewFilePath($dir) {
    return $dir . uniqid() . '.png';
}

function isValidImage($filePath) {
    $validTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileType = mime_content_type($filePath);
    return in_array($fileType, $validTypes);
}

function resizeAndSaveImage($sourcePath, $destinationPath) {
    list($srcWidth, $srcHeight) = getimagesize($sourcePath);
    $size = min($srcWidth, $srcHeight);
    // Ajuster pour recadrer à partir du coin supérieur droit
    $srcX = $srcWidth - $size;
    $srcY = 0;

    $image = imagecreatetruecolor(1000, 1000);
    $sourceImageType = exif_imagetype($sourcePath);

    switch ($sourceImageType) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        default:
            echo "Type de fichier non pris en charge.";
            return; // Sortie précoce si le type de fichier n'est pas pris en charge
    }

    imagecopyresampled($image, $sourceImage, 0, 0, $srcX, $srcY, 1000, 1000, $size, $size);

    // Appliquer la compression et enregistrer l'image
    if ($sourceImageType === IMAGETYPE_JPEG) {
        imagejpeg($image, $destinationPath, 75); // Compression JPEG avec une qualité de 75
    } elseif ($sourceImageType === IMAGETYPE_PNG) {
        imagepng($image, $destinationPath, 6); // Compression PNG avec un niveau de 6
    } elseif ($sourceImageType === IMAGETYPE_GIF) {
        imagegif($image, $destinationPath); // Les GIFs ne sont généralement pas compressés de la même manière
    }

    imagedestroy($image);
    imagedestroy($sourceImage);
}
?>
