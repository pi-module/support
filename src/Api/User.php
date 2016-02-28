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
use Zend\Json\Json;

/*
 * Pi::api('user', 'support')->updateUser($uid, $type);
 */

class User extends AbstractApi
{
    public function updateUser($uid, $type = 'ticket')
    {
        $user = Pi::model('user', $this->getModule())->find($uid);
        if (empty($user)) {
            $user = Pi::model('user', $this->getModule())->createRow();
            $user->id = $uid;
            if ($type == 'ticket') {
                $user->ticket = 1;
            }
            if ($type == 'reply') {
                $user->reply = 1;
            }
        } else {
            if ($type == 'ticket') {
                $user->ticket = $user->ticket + 1;
            }
            if ($type == 'reply') {
                $user->reply = $user->reply + 1;
            }
        }
        $user->time_update = time();
        $user->save();
    }
}