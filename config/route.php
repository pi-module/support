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
    // route name
    'guide' => array(
        'name' => 'support',
        'type' => 'Module\Support\Route\Support',
        'options' => array(
            'route' => '/support',
            'defaults' => array(
                'module' => 'support',
                'controller' => 'index',
                'action' => 'index'
            )
        ),
    )
);