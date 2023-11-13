<?php

namespace App\Models\V1\Image;

use Illuminate\Support\Facades\File;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;

class ResImage
{
    public static function resize(string $filename, ?int $width = null, ?int $height = null): ?string
    {
        if (!self::isFile($filename)) {
            return null;
        }

        if (!$width && !$height) {
            return null;
        }

        $path_info = pathinfo($filename);

        $old_image = $filename;
        $new_image = "{$path_info['dirname']}/{$path_info['filename']}_{$width}x$height.{$path_info['extension']}";

        if (
            !File::exists(self::getPath($new_image))
            || (filectime(self::getPath($old_image)) > filectime(self::getPath($new_image)))
        ) {
            [$width_orig, $height_orig] = getimagesize(self::getPath($old_image));

            if ($width_orig != $width || $height_orig != $height) {
                // open an image file
                $image = Image::make(self::getPath($old_image));

                if ($width && $height) {
                    $image->fit($width, $height, function (Constraint $constraint) {
                        $constraint->upsize();
                    });
                } else {
                    $image->resize($width, $height, function (Constraint $constraint) {
                        $constraint->aspectRatio();
                    });
                }

                $image->save(self::getPath($new_image));
            } else {
                File::copy(self::getPath($old_image), self::getPath($new_image));
            }
        }

        return self::getAsset($new_image);
    }

    public static function isFile($filename): bool
    {
        if (str_starts_with($filename, 'http')) {
            return true;
        } else {
            return File::exists(self::getPath($filename));
        }
    }

    public static function getPath(?string $uri = null): string
    {
        return cdn_sc_path_files($uri);
    }

    public static function getAsset(?string $uri = null): string
    {
        return cdn_sc_asset($uri);
    }

}
