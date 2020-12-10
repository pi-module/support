<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Support\Model;

use Pi\Application\Model\Model;

class Ticket extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $columns
        = [
            'id',
            'subject',
            'message',
            'uid',
            'status',
            'status_financial',
            'time_create',
            'time_update',
            'time_suggested',
            'time_execution',
            'ip',
            'mid',
            'label',
            'file_name',
            'file_path',
            'file_title',
            'file_type',
        ];
}
