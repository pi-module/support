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
    'category' => [
        [
            'title' => _a('Admin'),
            'name'  => 'admin',
        ],
        [
            'title' => _a('General'),
            'name'  => 'general',
        ],
        [
            'title' => _a('View'),
            'name'  => 'view',
        ],
        [
            'title' => _a('File'),
            'name'  => 'file',
        ],
    ],
    'item'     => [
        // Admin
        'admin_perpage'  => [
            'category'    => 'admin',
            'title'       => _a('Perpage'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 50,
        ],
        'admin_group'    => [
            'category'    => 'view',
            'title'       => _a('Send notification to admin group'),
            'description' => _a('If not checked send notification to website admin email and if checked send to all admin users'),
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],
        // View
        'view_perpage'   => [
            'category'    => 'view',
            'title'       => _a('Perpage'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 50,
        ],
        'view_timing'    => [
            'category'    => 'view',
            'title'       => _a('Show timing'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],
        // File
        'file_active'    => [
            'category'    => 'view',
            'title'       => _a('Active attach file'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],
        'file_size'      => [
            'category'    => 'file',
            'title'       => _a('File Size'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 1000000,
        ],
        'file_extension' => [
            'category'    => 'file',
            'title'       => _a('File Extension'),
            'description' => '',
            'edit'        => 'textarea',
            'filter'      => 'string',
            'value'       => 'jpg,jpeg,png,gif,avi,flv,mp3,mp4,pdf,docs,xdocs,zip,rar',
        ],

        'create_msg' => [
            'category'    => 'general',
            'title'       => _a('Instructions to display above any new issue form'),
            'description' => _a(
                'Display instructions above the form when a new ticket is created , like minimum information to give, clear use case, process to replay the issue, etc.'
            ),
            'edit'        => 'textarea',
            'filter'      => 'string',
        ],
    ],
];
