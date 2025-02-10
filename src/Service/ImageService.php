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

    public function getUploadDir(): string
    {
        return $this->uploadDir;
    }

    // Compression sans redimensionnement
    public function compressOnly(string $imagePath, int $quality = 75): ?string
    {
        $fullPath = $this->uploadDir . $imagePath;

        // Vérifier si le fichier existe
        if (!file_exists($fullPath)) {
            return null;
        }

        // Ouvrir l'image en fonction du type
        $imageInfo = getimagesize($fullPath);
        if (!$imageInfo) {
            return null; // Si l'image est invalide
        }

        $mime = $imageInfo['mime'];
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($fullPath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($fullPath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($fullPath);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($fullPath);
                break;
            default:
                return null; // Format non supporté
        }

        // Compression de l'image sans redimensionner
        $compressedPath = $this->uploadDir . 'compressed_' . $imagePath;
        switch ($mime) {
            case 'image/jpeg':
                imagejpeg($image, $compressedPath, $quality);
                break;
            case 'image/png':
                imagepng($image, $compressedPath, round($quality / 10)); // 0 à 9 pour PNG
                break;
            case 'image/gif':
                imagegif($image, $compressedPath);
                break;
            case 'image/webp':
                imagewebp($image, $compressedPath, $quality);
                break;
        }

        // Libérer la mémoire
        imagedestroy($image);

        return $compressedPath; // Retourne le chemin du fichier compressé
    }
}
