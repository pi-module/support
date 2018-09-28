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
    // Admin section
    'admin' => array(
        array(
            'title' => _a('Ticket'),
            'controller' => 'ticket',
            'permission' => 'ticket',
        ),
        array(
            'title' => _a('User'),
            'controller' => 'user',
            'permission' => 'user',
        ),
        array(
            'title' => _a('Label'),
            'controller' => 'label',
            'permission' => 'label',
        ),
    ),
    // Front section
    'front' => array(
        array(
            'title' => _a('Index page'),
            'controller' => 'index',
            'block' => 1,
        ),
        array(
            'title' => _a('Ticket'),
            'controller' => 'ticket',
            'block' => 1,
        ),
    ),
);