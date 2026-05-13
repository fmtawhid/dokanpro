<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MediaRepository extends Repository
{
    public static function model()
    {
        return Media::class;
    }

    public static function storeByRequest(UploadedFile $file, string $path, string $type = 'Image'): Media
    {
        try {
            // Validate file is accessible
            if (!self::validateFile($file)) {
                throw new \Exception('Uploaded file is not accessible. File may be corrupted or too large.');
            }
            
            $extension = $file->extension();
            $savedPath = self::putFile($file, $path);
            
            if (!$savedPath) {
                throw new \Exception('Failed to save file to storage');
            }
            
            if (!$type) {
                $type = in_array($extension, ['jpg', 'png', 'jpeg', 'gif', 'webp']) ? 'Image' : $extension;
            }

            return self::create([
                'type' => $type,
                'src' => $savedPath,
                'extension' => $extension,
                'path' => $path,
            ]);
        } catch (\Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function updateByRequest(UploadedFile $file, string $path, string $type = 'Image', Media $media): Media
    {
        try {
            // Validate file is accessible
            if (!self::validateFile($file)) {
                throw new \Exception('Uploaded file is not accessible. File may be corrupted or too large.');
            }
            
            $extension = $file->extension();
            $savedPath = self::putFile($file, $path);
            
            if (!$savedPath) {
                throw new \Exception('Failed to save file to storage');
            }
            
            if (!$type) {
                $type = in_array($extension, ['jpg', 'png', 'jpeg', 'gif', 'webp']) ? 'Image' : $extension;
            }

            // Delete old file safely
            if ($media && $media->src && Storage::exists($media->src)) {
                try {
                    Storage::delete($media->src);
                } catch (\Exception $e) {
                    Log::warning('Failed to delete old image: ' . $e->getMessage());
                }
            }

            self::update($media, [
                'type' => $type,
                'src' => $savedPath,
                'extension' => $extension,
                'path' => $path,
            ]);
            return $media;
        } catch (\Exception $e) {
            Log::error('Image update failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function updateOrCreateByRequest(UploadedFile $file, string $path, string $type = 'Image', $media = null): Media
    {
        try {
            // Validate file is accessible
            if (!self::validateFile($file)) {
                throw new \Exception('Uploaded file is not accessible. File may be corrupted or too large.');
            }
            
            $extension = $file->extension();
            $savedPath = self::putFile($file, $path);
            
            if (!$savedPath) {
                throw new \Exception('Failed to save file to storage');
            }
            
            // Delete old file safely
            if ($media && $media->src && Storage::exists($media->src)) {
                try {
                    Storage::delete($media->src);
                } catch (\Exception $e) {
                    Log::warning('Failed to delete old image: ' . $e->getMessage());
                }
            }

            if (!$type) {
                $type = in_array($extension, ['jpg', 'png', 'jpeg', 'gif', 'webp']) ? 'Image' : $extension;
            }
            
            return self::query()->updateOrCreate([
                'id' => $media?->id ?? 0,
            ], [
                'type' => $type,
                'src' => $savedPath,
                'extension' => $extension,
                'path' => $path,
            ]);
        } catch (\Exception $e) {
            Log::error('Image updateOrCreate failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private static function validateFile(UploadedFile $file): bool
    {
        try {
            // Check if file has a valid path
            if (!$file->getPathname()) {
                return false;
            }
            
            // Check if file exists and is readable
            $path = $file->getPathname();
            if (!file_exists($path) || !is_readable($path)) {
                Log::warning("File not accessible: {$path}");
                return false;
            }
            
            // Check file size (min 1 byte, max 50MB)
            $size = filesize($path);
            if ($size === false || $size < 1 || $size > 52428800) {
                Log::warning("File size invalid: {$size} bytes");
                return false;
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('File validation error: ' . $e->getMessage());
            return false;
        }
    }

    private static function putFile(UploadedFile $file, string $path)
    {
        try {
            $path = trim($path, '/');
            $extension = $file->extension();
            $fileName = uniqid() . '_' . time() . '.' . $extension;
            
            // For public filesystem disk, store in public folder directly
            // This avoids symlink issues on Windows
            $location = config('filesystems.default');
            
            if ($location == 'public') {
                // Save directly to public folder for direct URL access
                $publicPath = public_path($path);
                
                // Ensure directory exists with proper permissions
                if (!is_dir($publicPath)) {
                    if (!mkdir($publicPath, 0755, true)) {
                        throw new \Exception('Failed to create directory: ' . $publicPath);
                    }
                }
                
                // Get the temporary file path before moving
                $tempPath = $file->getPathname();
                $destinationPath = $publicPath . DIRECTORY_SEPARATOR . $fileName;
                
                // Validate source file exists before moving
                if (!is_readable($tempPath)) {
                    throw new \Exception('Source file is not readable: ' . $tempPath);
                }
                
                // Use PHP's copy function for better reliability
                if (!copy($tempPath, $destinationPath)) {
                    throw new \Exception('Failed to copy file to: ' . $destinationPath);
                }
                
                // Set proper file permissions
                chmod($destinationPath, 0644);
                
                // Cleanup temporary file
                try {
                    if (file_exists($tempPath) && is_writable($tempPath)) {
                        unlink($tempPath);
                    }
                } catch (\Exception $e) {
                    Log::warning('Could not delete temp file: ' . $e->getMessage());
                }
                
                // Return path relative to public folder for direct URL access
                // This will be accessible as http://example.com/settings/filename.ext
                return '/' . $path . '/' . $fileName;
            } else {
                // Fallback: use storage folder with proper symlink
                $publicPath = public_path('uploaded/' . $path);
                
                // Ensure directory exists
                if (!is_dir($publicPath)) {
                    if (!mkdir($publicPath, 0755, true)) {
                        throw new \Exception('Failed to create directory: ' . $publicPath);
                    }
                }
                
                // Use copy for better reliability
                $tempPath = $file->getPathname();
                $destinationPath = $publicPath . DIRECTORY_SEPARATOR . $fileName;
                
                if (!is_readable($tempPath)) {
                    throw new \Exception('Source file is not readable: ' . $tempPath);
                }
                
                if (!copy($tempPath, $destinationPath)) {
                    throw new \Exception('Failed to copy file to: ' . $destinationPath);
                }
                
                chmod($destinationPath, 0644);
                
                return '/uploaded/' . $path . '/' . $fileName;
            }
        } catch (\Exception $e) {
            Log::error('putFile error: ' . $e->getMessage());
            throw $e;
        }
    }
}

