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
    'front' => [
        'category' => [
            'label'      => _a('Submit ticket'),
            'route'      => 'support',
            'module'     => 'support',
            'controller' => 'ticket',
        ],
    ],
    'admin' => [
        'ticket' => [
            'label'      => _a('Ticket'),
            'permission' => [
                'resource' => 'ticket',
            ],
            'route'      => 'admin',
            'module'     => 'support',
            'controller' => 'ticket',
            'action'     => 'index',
        ],
        'user'   => [
            'label'      => _a('User'),
            'permission' => [
                'resource' => 'user',
            ],
            'route'      => 'admin',
            'module'     => 'support',
            'controller' => 'user',
            'action'     => 'index',
        ],
        'label'  => [
            'label'      => _a('Label'),
            'permission' => [
                'resource' => 'label',
            ],
            'route'      => 'admin',
            'module'     => 'support',
            'controller' => 'label',
            'action'     => 'index',
        ],
    ],
];
