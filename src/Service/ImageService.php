<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

class ImageService
{
    private string $uploadDir;
    private Filesystem $filesystem;

    public function __construct(KernelInterface $kernel)
    {
        $this->uploadDir = $kernel->getProjectDir() . '/public/uploads/images/';
        $this->filesystem = new Filesystem();

        // Vérifier et créer le dossier d'upload s'il n'existe pas
        if (!$this->filesystem->exists($this->uploadDir)) {
            $this->filesystem->mkdir($this->uploadDir, 0775);
        }
    }

    public function compressAndResize(string $imagePath, int $newWidth, int $newHeight, int $quality = 80): ?string
    {
        $fullPath = $this->uploadDir . $imagePath;
        
        if (!$this->filesystem->exists($fullPath)) {
            return null; // L'image n'existe pas
        }

        $imageInfo = getimagesize($fullPath);
        if (!$imageInfo) {
            return null; // Fichier non valide
        }

        // Détecter le type de l'image
        $mime = $imageInfo['mime'];
        switch ($mime) {
            case 'image/jpeg':
                $image = \imagecreatefromjpeg($fullPath);
                break;
            case 'image/png':
                $image = \imagecreatefrompng($fullPath);
                break;
            case 'image/gif':
                $image = \imagecreatefromgif($fullPath);
                break;
            case 'image/webp':
                $image = \imagecreatefromwebp($fullPath);
                break;
            default:
                return null; // Format non supporté
        }

        // Redimensionner l'image
        $resizedImage = imagescale($image, $newWidth, $newHeight);

        // Sauvegarder l'image compressée
        $compressedPath = $this->uploadDir . 'compressed_' . $imagePath;
        
        switch ($mime) {
            case 'image/jpeg':
                imagejpeg($resizedImage, $compressedPath, $quality);
                break;
            case 'image/png':
                imagepng($resizedImage, $compressedPath, round($quality / 10)); // 0 à 9 pour PNG
                break;
            case 'image/gif':
                imagegif($resizedImage, $compressedPath);
                break;
            case 'image/webp':
                imagewebp($resizedImage, $compressedPath, $quality);
                break;
        }

        imagedestroy($image);
        imagedestroy($resizedImage);

        return 'compressed_' . $imagePath; // Retourner le chemin de l'image compressée
    }
}
