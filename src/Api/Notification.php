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

namespace Module\Support\Api;

use Pi;
use Pi\Application\Api\AbstractApi;

/*
 * Pi::api('notification', 'support')->supportTicket($ticket, $type, $message);
 */

class Notification extends AbstractApi
{
    public function supportTicket($ticket, $type, $message = '')
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());

        // Set info
        $information = [
            'subject' => $ticket['subject'],
            'message' => $ticket['message'],
            'time'    => $ticket['time_create_view'],
            'url'     => $ticket['ticketUrl'],
            'message' => empty($message) ? $ticket['message'] : $message,
        ];

        // Set template
        switch ($type) {
            case 'reply':
                $templateAdmin = 'admin_reply_ticket';
                $templateUser  = 'user_reply_ticket';
                break;

            case 'open':
                $templateAdmin = 'admin_open_ticket';
                $templateUser  = 'user_open_ticket';
                break;

            case 'admin':
                $templateAdmin = 'admin_admin_ticket';
                $templateUser  = 'user_admin_ticket';
                break;
        }

        // Set to admin
        if (isset($config['admin_group']) && !empty($config['admin_group'])) {
            // Get admin list
            $uids   = [];
            $where  = ['role' => 'admin', 'section' => 'admin'];
            $select = Pi::model('user_role')->select()->where($where);
            $rowSet = Pi::model('user_role')->selectWith($select);
            foreach ($rowSet as $row) {
                $uids[$row->uid] = $row->uid;
            }
            $users = Pi::service('user')->mget($uids, ['uid', 'name', 'email', 'identity']);
            // Send email to admins
            foreach ($users as $user) {
                $toAdmin = [
                    $user['email'] => $user['name'],
                ];
                // Send mail to admin
                Pi::service('notification')->send(
                    $toAdmin,
                    $templateAdmin,
                    $information,
                    Pi::service('module')->current(),
                    $user['id']
                );
            }
        } else {
            $toAdmin = [
                Pi::config('adminmail') => Pi::config('adminname'),
            ];
            // Send mail to admin
            Pi::service('notification')->send(
                $toAdmin,
                $templateAdmin,
                $information,
                Pi::service('module')->current()
            );
        }

        // Set to user
        $toUser = [
            $ticket['user']['email'] => $ticket['user']['name'],
        ];

        // Send mail to user
        Pi::service('notification')->send(
            $toUser,
            $templateUser,
            $information,
            Pi::service('module')->current(),
            $ticket['uid']
        );
    }
}
