<?php

namespace App\Models\V1\Image;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class UseImage
{
    public static function getAsset(array|string $data, ?int $width = null, ?int $height = null): ?string
    {

        if (is_string($data) && Str::isJson($data)) {
            if (!$data = json_decode($data, true)) {
                return null;
            }
        }

        if (empty($data['cache']) || !is_array($data['cache'])) {
            return null;
        }

        $dirname = Carbon::parse($data['time'])->format('Y/m/d');

        if ($width || $height) {
            $dimension = "{$width}x$height";

            if (in_array($dimension, $data['cache']) && ResImage::isFile("$dirname/{$data['id']}_$dimension.{$data['ext']}")) {

                return self::_asset("$dirname/{$data['id']}_$dimension.{$data['ext']}");
            } else {
                return ResImage::resize("$dirname/{$data['id']}.{$data['ext']}", $width, $height);
            }
        }

        return self::_asset("$dirname/{$data['id']}.{$data['ext']}");
    }

    public static function getAssets(array|string $data): ?array
    {
        if (is_string($data) && Str::isJson($data)) {
            if (!$data = json_decode($data, true)) {
                return null;
            }
        }

        if (empty($data['cache']) || !is_array($data['cache'])) {
            return null;
        }

        $dirname = Carbon::parse($data['timestamp'])->format('Y/m/d');

        $list = [
            'orig' => self::_asset("$dirname/{$data['id']}.{$data['extension']}")
        ];

        foreach ($data['cache'] as $dimension) {
            $list[$dimension] = self::_asset("$dirname/{$data['id']}_$dimension.{$data['extension']}");
        }

        return $list;
    }

    protected static function _asset(?string $uri = null): string
    {
        return cdn_sc_asset($uri);
    }
}
