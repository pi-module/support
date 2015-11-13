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

namespace Module\Support\Controller\Front;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Module\Support\Form\TicketForm;
use Module\Support\Form\TicketFilter;

class TicketController extends ActionController
{
    public function indexAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Get user info
        $uid = Pi::user()->getId();
        $user = Pi::user()->get($uid, array('id', 'identity', 'name', 'email'));
        $user['avatar'] = Pi::service('user')->avatar($user['id'], 'small', $user['name']);
        $user['profileUrl'] = Pi::url(Pi::service('user')->getUrl('profile', array(
            'id' => $user['id'],
        )));
        $user['accountUrl'] = Pi::url(Pi::service('user')->getUrl(
            'user', array('controller' => 'account')
        ));

        // Get id
        $id = $this->params('id');
        if ($id) {
            // Get main ticket
            $ticket = Pi::api('ticket', 'support')->getTicket($id);
            if ($uid == $ticket['uid']) {
                $ticket['user'] = $user;
            } else {
                $ticket['user'] = Pi::user()->get($ticket['uid'], array('id', 'identity', 'name', 'email'));
                $ticket['user']['avatar'] = Pi::service('user')->avatar($ticket['uid'], 'small', $ticket['user']['name']);
            }
            // Get list of replies
            $tickets = array();
            $where = array('mid' => $id);
            $order = array('time_create ASC', 'id ASC');
            // Get info
            $select = $this->getModel('ticket')->select()->where($where)->order($order);
            $rowset = $this->getModel('ticket')->selectWith($select);
            // Make list
            foreach ($rowset as $row) {
                $tickets[$row->id] = Pi::api('ticket', 'support')->canonizeTicket($row);
                if ($uid == $row->uid) {
                    $tickets[$row->id]['user'] = $user;
                } else {
                    $tickets[$row->id]['user'] = Pi::user()->get($row->uid, array('id', 'identity', 'name', 'email'));
                    $tickets[$row->id]['user']['avatar'] = Pi::service('user')->avatar($row->uid, 'small', $tickets[$row->id]['user']['name']);
                }
            }
            // Set view
            $this->view()->assign('ticket', $ticket);
            $this->view()->assign('tickets', $tickets);
            // Set info
            $title = $ticket['subject'];
            $mid = $ticket['id'];
            $status = 0;
        } else {
            // Set info
            $title = __('Open new support ticket');
            $mid = 0;
            $status = 1;
        }

        // Set form
        $form = new TicketForm('ticket');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new TicketFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Set values
                $values['uid'] = $uid;
                $values['time_create'] = time();
                $values['ip'] = Pi::user()->getIp();
                $values['mid'] = $mid;
                $values['status'] = $status;
                // Save
                $row = $this->getModel('ticket')->createRow();
                $row->assign($values);
                $row->save();
                // Update main ticket status
                if (isset($ticket['id']) && $id > 0) {
                    Pi::model('ticket', $this->getModule())->update(array('status' => 3), array('id' => $ticket['id']));
                }
                // Jump
                $message = __('Your support ticket submit successfully, we will answer you very soon');
                $url = array('controller' => 'index', 'action' => 'index');
                $this->jump($url, $message);
            }
        } elseif (isset($ticket) && !empty($ticket)) {
            $values = array(
                'subject' => sprintf('Re : %s', $ticket['subject']),
            );
            $form->setData($values);
        }
        // Set view
        $this->view()->assign('form', $form);
        $this->view()->assign('title', $title);
        $this->view()->assign('user', $user);
    }
}