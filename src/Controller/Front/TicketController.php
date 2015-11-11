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
        $user['avatar'] = Pi::service('user')->avatar($user['id'], 'medium', $user['name']);
        $user['profileUrl'] = Pi::url(Pi::service('user')->getUrl('profile', array(
            'id' => $user['id'],
        )));
        $user['accountUrl'] = Pi::url(Pi::service('user')->getUrl(
            'user', array('controller' => 'account')
        ));
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
                $values['mid'] = 0;
                $values['status'] = 2;
                // Save
                $row = $this->getModel('ticket')->createRow();
                $row->assign($values);
                $row->save();
                // Jump
                $message = __('Your support ticket submit successfully, we will answer you very soon');
                $url = array('controller' => 'index', 'action' => 'index');
                $this->jump($url, $message);
            }
        }
        // Set view
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('ddd'));
    }
}