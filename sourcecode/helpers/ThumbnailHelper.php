<?php

namespace fredyns\suite\helpers;

use yii\imagine\Image;

/**
 * Description of ThumbnailHelper
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class ThumbnailHelper
{

    public static function crop($sourceFile, $targetFile = null, $size = 256)
    {
        if (empty($targetFile))
        {
            $targetFile = $sourceFile;
        }

        $imageSize = getimagesize($sourceFile);

        if (!$imageSize)
        {
            return;
        }
        elseif ($imageSize[0] < $size && $imageSize[1] < $size && $imageSize[0] == $imageSize[1])
        {
            return;
        }

        $long = [
            $imageSize[0],
            $imageSize[1],
            $size
        ];
        $size = min($long);

        Image::thumbnail($sourceFile, $size, $size)
            ->save($targetFile, ['quality' => 80]);
    }

}