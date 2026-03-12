<?php

namespace App\Services;
use Cloudinary\Cloudinary;

class CloudinaryService
{
    /**
     * Create a new class instance.
     */
    protected Cloudinary $cloudinary;
    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
                'secure'     => env('CLOUDINARY_SECURE', true),
            ],
        ]);
    }

    public function uploadImage(string $path, string $folder = 'products', ?string $publicId = null, ?string $uploadPreset = null): array
    {
        $options = [
            'folder' => $folder,
        ];

        if ($publicId) {
            $options['public_id'] = $publicId;
            $options['overwrite'] = true;
        }

        if ($uploadPreset) {
            $options['upload_preset'] = $uploadPreset;
        }

        $upload = $this->cloudinary->uploadApi()->upload($path, $options);

        return [
            'url'       => $upload['secure_url'],
            'public_id' => $upload['public_id'],
        ];
    }

    public function deleteImage(?string $publicId): void
    {
        if ($publicId) {
            $this->cloudinary->uploadApi()->destroy($publicId);
        }
    }
}
