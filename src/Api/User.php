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
 * Pi::api('user', 'support')->updateUser($uid, $type);
 * Pi::api('user', 'support')->getUserList();
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

    public function getUserList()
    {
        // Get info
        $list = array();
        $where = array('ticket > ?' => 0);
        $order = array('time_update DESC', 'id DESC');
        $select = Pi::model('user', $this->getModule())->select()->where($where)->order($order);
        $rowset = Pi::model('user', $this->getModule())->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $user = Pi::user()->get($row->id, array('id', 'identity', 'name', 'email', 'first_name', 'last_name'));
            if (isset($user) && !empty($user)) {
                if (!empty($user['first_name']) && !empty($user['last_name'])) {
                    $user['display'] = sprintf('%s %s', $user['first_name'], $user['last_name']);
                } else {
                    $user['display'] = $user['name'];
                }
                $list[$row->id] = array(
                    'id' => $row->id,
                    'display' => $user['display'],
                    'identity' => $user['identity'],
                    'email' => $user['email'],
                    'ticket' => _number($row->ticket),
                    'reply' => _number($row->reply),
                    'time' => _date($row->time_update),
                );
            }
        }
        return $list;
    }
}