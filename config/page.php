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
return [
    // Admin section
    'admin' => [
        [
            'title'      => _a('Ticket'),
            'controller' => 'ticket',
            'permission' => 'ticket',
        ],
        [
            'title'      => _a('User'),
            'controller' => 'user',
            'permission' => 'user',
        ],
        [
            'title'      => _a('Label'),
            'controller' => 'label',
            'permission' => 'label',
        ],
    ],
    // Front section
    'front' => [
        [
            'title'      => _a('Index page'),
            'controller' => 'index',
            'block'      => 1,
        ],
        [
            'title'      => _a('Ticket'),
            'controller' => 'ticket',
            'block'      => 1,
        ],
    ],
];
