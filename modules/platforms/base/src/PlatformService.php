<?php

namespace Ecommify\Platform;

use Ecommify\Core\Models\Platform;

class PlatformService
{
    /**
     * Get Platform data from Platform Name
     *
     * @param string $platform
     * @return array
     */
    public static function getPlatformDataByPlatform($platform)
    {
        $data = Platform::where('platform_name', $platform)->first();

        if (!empty($data)) {
            return $data->toArray();
        }

        return [];
    }

    /**
     * Get Platform data from Platform Name
     *
     * @param string $platform
     * @param json $data
     * @return bool
     */
    public static function addUpdatePlatformDataByPlatform($platform, $data)
    {
        $platformData =  PlatformService::getPlatformDataByPlatform($platform);

        if (empty($platformData)) {
            Platform::create([
                'platform_name' => $platform,
                'value_lists' => $data,
            ]);

            return true;
        }

        Platform::where('id', $platformData['id'])
            ->update(['value_lists' => $data]);

        return true;
    }
}
