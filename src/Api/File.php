<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
namespace Module\Support\Api;

use Pi;
use Pi\Application\Api\AbstractApi;

/*
 * Pi::api('file', 'support')->getType($file);
 */

class File extends AbstractApi
{
    public function getType($file)
    {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $extension = strtolower($extension);
        switch ($extension) {
            case 'zip':
            case 'rar':
            case 'tar':
                $type = 'archive';
                break;

            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                $type = 'image';
                break;

            case 'avi':
            case 'flv':
            case 'mp4':
            case 'webm':
            case 'ogv':
                $type = 'video';
                break;

            case 'mp3':
            case 'ogg':
                $type = 'audio';
                break;

            case 'pdf':
                $type = 'pdf';
                break;

            case 'doc':
            case 'docx':
            case 'xls':
            case 'xlsx':
                $type = 'office';
                break;

            default:
                $type = 'other';
                break;
        }
        // return
        return $type;
    }
}