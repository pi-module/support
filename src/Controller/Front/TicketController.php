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
use Pi\File\Transfer\Upload;
use Module\Support\Form\TicketForm;
use Module\Support\Form\TicketFilter;

class TicketController extends ActionController
{
    public function indexAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Get info from url
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
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
            $ticketMain = Pi::api('ticket', 'support')->getTicket($id);
            if ($uid == $ticketMain['uid']) {
                $ticketMain['user'] = $user;
            } else {
                $ticketMain['user'] = Pi::user()->get($ticketMain['uid'], array('id', 'identity', 'name', 'email'));
                $ticketMain['user']['avatar'] = Pi::service('user')->avatar($ticketMain['uid'], 'small', $ticketMain['user']['name']);
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
            $this->view()->assign('ticketMain', $ticketMain);
            $this->view()->assign('tickets', $tickets);
            // Set info
            $title = $ticketMain['subject'];
            $mid = $ticketMain['id'];
            $status = 0;
        } else {
            // Set info
            $title = __('Open new support ticket');
            $mid = 0;
            $status = 1;
        }

        // Set option
        $option = array(
            'attach' => $config['file_active'],
        );
        // Set form
        $form = new TicketForm('ticket', $option);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $file = $this->request->getFiles();
            $form->setInputFilter(new TicketFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // upload file
                if (!empty($file['attach']['name']) && $config['file_active']) {
                    // Set upload path
                    $values['file_path'] = sprintf('%s/%s', date('Y'), date('m'));
                    $path = Pi::path(sprintf('upload/support/%s', $values['file_path']));
                    // Upload
                    $uploader = new Upload;
                    $uploader->setDestination($path);
                    $uploader->setRename('%random%');
                    $uploader->setExtension($this->config('file_extension'));
                    $uploader->setSize($this->config('file_size'));
                    if ($uploader->isValid()) {
                        $uploader->receive();
                        $values['file_title'] = $file['attach']['name'];
                        $values['file_type'] = Pi::api('file', 'support')->getType($file['attach']['name']);
                        $values['file_name'] = $uploader->getUploaded('attach');
                    }
                }
                // Set values
                $values['uid'] = $uid;
                $values['time_create'] = time();
                $values['time_update'] = time();
                $values['ip'] = Pi::user()->getIp();
                $values['mid'] = $mid;
                $values['status'] = $status;
                // Save
                $row = $this->getModel('ticket')->createRow();
                $row->assign($values);
                $row->save();
                // Update main ticket status
                if (isset($ticketMain['id']) && $id > 0) {
                    Pi::model('ticket', $this->getModule())->update(
                        array(
                            'status' => 3,
                            'time_update' => time(),
                        ),
                        array('id' => $ticketMain['id'])
                    );
                    // User ticket
                    $ticketUser = Pi::api('ticket', 'support')->canonizeTicket($row);
                    // Send notification
                    Pi::api('notification', 'support')->supportTicket($ticketMain, 'reply', $ticketUser['message']);
                    // Update user info
                    Pi::api('user', 'support')->updateUser($uid, 'reply');
                } else {
                    // Set ticket
                    $ticketMain = Pi::api('ticket', 'support')->canonizeTicket($row);
                    $ticketMain['user'] = $user;
                    // Send notification
                    Pi::api('notification', 'support')->supportTicket($ticketMain, 'open');
                    // Update user info
                    Pi::api('user', 'support')->updateUser($uid, 'ticket');
                }
                // Jump
                $message = __('Your support ticket submit successfully, we will answer you very soon');
                $url = array('controller' => 'index', 'action' => 'index');
                $this->jump($url, $message);
            }
        } elseif (isset($ticketMain) && !empty($ticketMain)) {
            $values = array(
                'subject' => sprintf('Re : %s', $ticketMain['subject']),
            );
            $form->setData($values);
        }
        // Set view
        $this->view()->assign('form', $form);
        $this->view()->assign('title', $title);
        $this->view()->assign('user', $user);
        $this->view()->assign('config', $config);
    }

    public function downloadAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Get info from url
        $module = $this->params('module');
        $id = $this->params('id');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Get user info
        $uid = Pi::user()->getId();
        // Check id
        if ($id) {
            $ticket = Pi::api('ticket', 'support')->getTicket($id);
            // Get user
            if ($ticket['mid'] > 0) {
                $ticketMain = Pi::api('ticket', 'support')->getTicket($ticket['mid']);
                $user = $ticketMain['uid'];
            } else {
                $user = $ticket['uid'];
            }
            // Check user
            if ($uid != $user) {
                $this->getResponse()->setStatusCode(404);
                $this->terminate(__('The media not set.'), '', 'error-404');
                $this->view()->setLayout('layout-simple');
                return;
            }
            // Check file set
            if (!empty($ticket['file_path']) && !empty($ticket['file_name'])) {
                $source = Pi::path(sprintf(
                    'upload/support/%s/%s'                    ,
                    $ticket['file_path'],
                    $ticket['file_name']
                ));
                $options = array(
                    'filename' => $ticket['file_title']
                );
                return Pi::service('file')->download($source, $options);
            } else {
                $this->getResponse()->setStatusCode(404);
                $this->terminate(__('The media not set.'), '', 'error-404');
                $this->view()->setLayout('layout-simple');
                return;
            }
        } else {
            $this->getResponse()->setStatusCode(404);
            $this->terminate(__('The media not set.'), '', 'error-404');
            $this->view()->setLayout('layout-simple');
            return;
        }
    }
}