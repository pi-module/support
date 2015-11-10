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
    // Admin section
    'admin' => array(
        array(
            'title' => _a('Ticket'),
            'controller' => 'ticket',
            'permission' => 'ticket',
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