<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt BSD 3-Clause License
 */

namespace Module\Support\Controller\Api;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Zend\Db\Sql\Predicate\Expression;

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
class TicketController extends ActionController
{
    public function listAction()
    {
        // Set default result
        $result = [
            'result' => false,
            'data'   => [],
            'error'  => [
                'code'        => 1,
                'message'     => __('Nothing selected'),
                'messageFlag' => false,
            ],
        ];

        // Get info from url
        $token = $this->params('token');

        // Check token
        $check = Pi::api('token', 'tools')->check($token, true);
        if ($check['status'] == 1) {

            // Get page
            $page = $this->params('page', 1);

            // Set info
            $tickets = [];
            $where   = ['mid' => 0, 'uid' => $check['uid']];
            $order   = ['time_update DESC', 'id DESC'];
            $limit   = intval($this->config('view_perpage'));
            $offset  = (int)($page - 1) * $limit;

            // Get info
            $select = $this->getModel('ticket')->select()->where($where)->order($order)->offset($offset)->limit($limit);
            $rowSet = $this->getModel('ticket')->selectWith($select);

            // Make list
            foreach ($rowSet as $row) {
                $tickets[$row->id] = Pi::api('ticket', 'support')->canonizeTicket($row);
            }

            // Set count
            $count  = ['count' => new Expression('count(*)')];
            $select = $this->getModel('ticket')->select()->columns($count)->where($where);
            $count  = $this->getModel('ticket')->selectWith($select)->current()->count;

            // Set default result
            $result = [
                'result' => true,
                'data'   => [
                    'ticket'    => array_values($tickets),
                    'paginator' => [
                        'count' => $count,
                        'limit' => $limit,
                        'page'  => $page,
                    ],
                    'condition' => [
                        'title' => __('List of your submitted tickets'),
                    ],
                ],
                'error'  => [
                    'code'    => 0,
                    'message' => '',
                ],
            ];
        } else {
            // Set error
            $result['error'] = [
                'code'    => $check['code'],
                'message' => $check['message'],
            ];
        }

        return $result;
    }

    public function detailAction()
    {
        // Set default result
        $result = [
            'result' => false,
            'data'   => [],
            'error'  => [
                'code'        => 1,
                'message'     => __('Nothing selected'),
                'messageFlag' => false,
            ],
        ];

        // Get info from url
        $token = $this->params('token');

        // Check token
        $check = Pi::api('token', 'tools')->check($token, true);
        if ($check['status'] == 1) {

            // Get ticket id
            $ticketId = $this->params('ticket_id');

            // Check
            if ($ticketId && intval($ticketId) > 0) {

                // Get main ticket
                $ticketMain = Pi::api('ticket', 'support')->getTicket($ticketId);

                // Check user
                if ($ticketMain['uid'] == $check['uid']) {

                    // Get list of replies
                    $tickets = [];
                    $where   = ['mid' => $ticketId];
                    $order   = ['time_create ASC', 'id ASC'];

                    // Get info
                    $select = $this->getModel('ticket')->select()->where($where)->order($order);
                    $rowSet = $this->getModel('ticket')->selectWith($select);

                    // Make list
                    foreach ($rowSet as $row) {
                        $tickets[$row->id] = Pi::api('ticket', 'support')->canonizeTicket($row);
                    }

                    // Set count
                    $count  = ['count' => new Expression('count(*)')];
                    $select = $this->getModel('ticket')->select()->columns($count)->where($where);
                    $count  = $this->getModel('ticket')->selectWith($select)->current()->count;

                    // Set default result
                    $result = [
                        'result' => true,
                        'data'   => [
                            'ticket'    => array_values($tickets),
                            'paginator' => [
                                'count' => $count,
                                'limit' => $count,
                                'page'  => 1,
                            ],
                            'condition' => [
                                'title' => __('Ticket conversation'),
                            ],
                        ],
                        'error'  => [
                            'code'    => 0,
                            'message' => '',
                        ],
                    ];
                } else {
                    // Set error
                    $result['error'] = [
                        'code'    => 1,
                        'message' => __('This is not your ticket'),
                    ];
                }
            } else {
                // Set error
                $result['error'] = [
                    'code'    => 1,
                    'message' => __('Please set true ticket id'),
                ];
            }
        } else {
            // Set error
            $result['error'] = [
                'code'    => $check['code'],
                'message' => $check['message'],
            ];
        }

        return $result;
    }

    public function submitAction()
    {
        // Set default result
        $result = [
            'result' => false,
            'data'   => [],
            'error'  => [
                'code'        => 1,
                'message'     => __('Nothing selected'),
                'messageFlag' => false,
            ],
        ];

        // Get info from url
        $token = $this->params('token');

        // Check token
        $check = Pi::api('token', 'tools')->check($token, true);
        if ($check['status'] == 1) {

            // Get ticket id
            $ticketId = $this->params('ticket_id');
            $subject  = $this->params('subject');
            $message  = $this->params('message');

            // Check
            if (!empty($subject) && !empty($message)) {
                // Set info
                $mid        = 0;
                $status     = 1;
                $ticketMain = [];

                // Check
                if ($ticketId && intval($ticketId) > 0) {

                    // Get main ticket
                    $ticketMain = Pi::api('ticket', 'support')->getTicket($ticketId);

                    // Check user
                    if ($ticketMain['uid'] == $check['uid']) {
                        $mid    = $ticketId;
                        $status = 0;
                    }
                }

                // Set value
                $values = [
                    'uid'         => $check['uid'],
                    'time_create' => time(),
                    'time_update' => time(),
                    'ip'          => Pi::user()->getIp(),
                    'mid'         => $mid,
                    'status'      => $status,
                    'subject'     => _escape($subject),
                    'message'     => _escape($message),
                ];

                // Save
                $row = $this->getModel('ticket')->createRow();
                $row->assign($values);
                $row->save();

                // Update main ticket status
                if (isset($ticketMain['id']) && $ticketMain['id'] > 0) {
                    Pi::model('ticket', $this->getModule())->update(
                        [
                            'status'      => 3,
                            'time_update' => time(),
                        ],
                        ['id' => $ticketMain['id']]
                    );

                    // User ticket
                    $ticketUser = Pi::api('ticket', 'support')->canonizeTicket($row);

                    // Send notification
                    Pi::api('notification', 'support')->supportTicket($ticketMain, 'reply', $ticketUser['message']);

                    // Update user info
                    Pi::api('user', 'support')->updateUser($ticketMain['uid'], 'reply');
                } else {
                    // Set ticket
                    $ticketMain         = Pi::api('ticket', 'support')->canonizeTicket($row);
                    $ticketMain['user'] = Pi::user()->get($ticketMain['uid'], ['id', 'identity', 'name', 'email']);

                    // Send notification
                    Pi::api('notification', 'support')->supportTicket($ticketMain, 'open');

                    // Update user info
                    Pi::api('user', 'support')->updateUser($ticketMain['uid'], 'ticket');
                }

                // Set default result
                $result = [
                    'result' => true,
                    'data'   => [
                        'message' => __('Your support ticket submit successfully, we will answer you very soon'),
                    ],
                    'error'  => [
                        'code'    => 0,
                        'message' => '',
                    ],
                ];
            } else {
                // Set error
                $result['error'] = [
                    'code'    => 1,
                    'message' => __('You need set both of subject and message'),
                ];
            }
        } else {
            // Set error
            $result['error'] = [
                'code'    => $check['code'],
                'message' => $check['message'],
            ];
        }

        return $result;
    }
}
