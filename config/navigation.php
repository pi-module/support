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
    ),
);