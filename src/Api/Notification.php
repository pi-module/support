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
        // Check notification module
        if (!Pi::service('module')->isActive('notification')) {
            return false;
        }

        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());

        // Set to admin
        if (isset($config['admin_email']) && !empty($config['admin_email'])) {
            $toAdmin = array(
                $config['admin_email'] => Pi::config('adminname'),
            );
        } else {
            $toAdmin = array(
                Pi::config('adminmail') => Pi::config('adminname'),
            );
        }

        // Set to user
        $toUser = array(
            $ticket['user']['email'] => $ticket['user']['name'],
        );

        // Set info
        $information = array(
            'subject' => $ticket['subject'],
            'message' => $ticket['message'],
            'time' => $ticket['time_create_view'],
            'url' => $ticket['ticketUrl'],
            'message' => empty($message) ? $ticket['message'] : $message,
        );

        // Set template
        switch ($type) {
            case 'reply':
                $templateAdmin = 'admin_reply_ticket';
                $templateUser = 'user_reply_ticket';
                break;

            case 'open':
                $templateAdmin = 'admin_open_ticket';
                $templateUser = 'user_open_ticket';
                break;

            case 'admin':
                $templateAdmin = 'admin_admin_ticket';
                $templateUser = 'user_admin_ticket';
                break;
        }

        // Send mail to admin
        Pi::api('mail', 'notification')->send(
            $toAdmin,
            $templateAdmin,
            $information,
            Pi::service('module')->current()
        );

        // Send mail to user
        Pi::api('mail', 'notification')->send(
            $toUser,
            $templateUser,
            $information,
            Pi::service('module')->current(),
            $ticket['uid']
        );
    }
}