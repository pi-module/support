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
return array(
    'front' => array(
        'category' => array(
            'label' => _a('Submit ticket'),
            'route' => 'support',
            'module' => 'support',
            'controller' => 'ticket',
        ),
    ),
    'admin' => array(
        'ticket' => array(
            'label' => _a('Ticket'),
            'permission' => array(
                'resource' => 'ticket',
            ),
            'route' => 'admin',
            'module' => 'support',
            'controller' => 'ticket',
            'action' => 'index',
        ),
        'user' => array(
            'label' => _a('User'),
            'permission' => array(
                'resource' => 'user',
            ),
            'route' => 'admin',
            'module' => 'support',
            'controller' => 'user',
            'action' => 'index',
        ),
        'label' => array(
            'label' => _a('Label'),
            'permission' => array(
                'resource' => 'label',
            ),
            'route' => 'admin',
            'module' => 'support',
            'controller' => 'label',
            'action' => 'index',
        ),
    ),
);