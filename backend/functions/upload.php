<?php
/**
 * =====================================================
 * Secure File Upload Functions
 * =====================================================
 */

if (!defined('BVM_ROOT')) {
    die('Direct access not permitted.');
}

/**
 * Upload an image file securely
 *
 * @param array  $file        $_FILES element
 * @param string $destination Upload directory path
 * @param string $prefix      Filename prefix
 * @return array ['success' => bool, 'filename' => string, 'message' => string]
 */
function uploadImage(array $file, string $destination, string $prefix = 'img'): array
{
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors = [
            UPLOAD_ERR_INI_SIZE   => 'File exceeds server size limit.',
            UPLOAD_ERR_FORM_SIZE  => 'File exceeds form size limit.',
            UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded.',
            UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Server temporary folder missing.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
        ];
        return ['success' => false, 'filename' => '', 'message' => $errors[$file['error']] ?? 'Unknown upload error.'];
    }

    // Check file size
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        $maxMb = MAX_UPLOAD_SIZE / (1024 * 1024);
        return ['success' => false, 'filename' => '', 'message' => "File size exceeds {$maxMb}MB limit."];
    }

    // Verify MIME type
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
        return ['success' => false, 'filename' => '', 'message' => 'Invalid file type. Allowed: JPG, PNG, WebP, GIF.'];
    }

    // Verify extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ALLOWED_IMAGE_EXTENSIONS)) {
        return ['success' => false, 'filename' => '', 'message' => 'Invalid file extension.'];
    }

    // Verify it's actually an image
    $imageInfo = getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        return ['success' => false, 'filename' => '', 'message' => 'File is not a valid image.'];
    }

    // Create destination directory if it doesn't exist
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }

    // Generate unique filename
    $filename = $prefix . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
    $filepath = $destination . '/' . $filename;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => false, 'filename' => '', 'message' => 'Failed to move uploaded file.'];
    }

    return ['success' => true, 'filename' => $filename, 'message' => 'File uploaded successfully.'];
}

/**
 * Delete an uploaded file
 */
function deleteUploadedFile(string $filepath): bool
{
    if (file_exists($filepath) && is_file($filepath)) {
        return unlink($filepath);
    }
    return false;
}

/**
 * Create thumbnail from uploaded image
 */
function createThumbnail(string $source, string $destination, int $maxWidth = 300, int $maxHeight = 300): bool
{
    $imageInfo = getimagesize($source);
    if ($imageInfo === false) return false;

    [$origWidth, $origHeight, $type] = $imageInfo;

    // Calculate new dimensions
    $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
    $newWidth = (int) ($origWidth * $ratio);
    $newHeight = (int) ($origHeight * $ratio);

    // Create source image
    $srcImage = match ($type) {
        IMAGETYPE_JPEG => imagecreatefromjpeg($source),
        IMAGETYPE_PNG  => imagecreatefrompng($source),
        IMAGETYPE_WEBP => imagecreatefromwebp($source),
        IMAGETYPE_GIF  => imagecreatefromgif($source),
        default        => null,
    };

    if (!$srcImage) return false;

    // Create thumbnail
    $thumbImage = imagecreatetruecolor($newWidth, $newHeight);

    // Preserve transparency for PNG/GIF
    if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_GIF) {
        imagealphablending($thumbImage, false);
        imagesavealpha($thumbImage, true);
    }

    imagecopyresampled($thumbImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

    // Save thumbnail
    $dir = dirname($destination);
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $result = match ($type) {
        IMAGETYPE_JPEG => imagejpeg($thumbImage, $destination, 85),
        IMAGETYPE_PNG  => imagepng($thumbImage, $destination, 8),
        IMAGETYPE_WEBP => imagewebp($thumbImage, $destination, 85),
        IMAGETYPE_GIF  => imagegif($thumbImage, $destination),
        default        => false,
    };

    imagedestroy($srcImage);
    imagedestroy($thumbImage);

    return $result;
}
