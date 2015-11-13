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

namespace Module\Support\Controller\Admin;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Pi\Paginator\Paginator;
use Zend\Db\Sql\Predicate\Expression;
use Module\Support\Form\TicketForm;
use Module\Support\Form\TicketFilter;

class TicketController extends ActionController
{
    public function indexAction()
    {
        // Get page
        $page = $this->params('page', 1);
        // Set info
        $ticket = array();
        $where = array('mid' => 0);
        $order = array('id DESC', 'time_create DESC');
        $offset = (int)($page - 1) * $this->config('admin_perpage');
        $limit = intval($this->config('admin_perpage'));
        // Get info
        $select = $this->getModel('ticket')->select()->where($where)->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('ticket')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $ticket[$row->id] = Pi::api('ticket', 'support')->canonizeTicket($row);
            $ticket[$row->id]['user'] = Pi::user()->get($row->uid, array('id', 'identity', 'name', 'email'));
            $ticket[$row->id]['ticketUrl'] = Pi::url($this->url('', array(
                'controller' => 'ticket',
                'action' => 'detail',
                'id' => $row->id,
            )));
        }
        // Set paginator
        $count = array('count' => new Expression('count(*)'));
        $select = $this->getModel('ticket')->select()->columns($count)->where($where);
        $count = $this->getModel('ticket')->selectWith($select)->current()->count;
        $paginator = Paginator::factory(intval($count));
        $paginator->setItemCountPerPage($this->config('admin_perpage'));
        $paginator->setCurrentPageNumber($page);
        $paginator->setUrlOptions(array(
            'router'    => $this->getEvent()->getRouter(),
            'route'     => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            'params'    => array_filter(array(
                'module'        => $this->getModule(),
                'controller'    => 'index',
                'action'        => 'index',
            )),
        ));
        // Set view
        $this->view()->assign('tickets', $ticket);
        $this->view()->assign('paginator', $paginator);
    }

    public function detailAction()
    {
        // Get id
        $id = $this->params('id');
        if ($id) {
            // Get main ticket
            $ticket = Pi::api('ticket', 'support')->getTicket($id);
            $ticket['user'] = Pi::user()->get($ticket['uid'], array('id', 'identity', 'name', 'email'));
            $ticket['user']['avatar'] = Pi::service('user')->avatar($ticket['uid'], 'small', $ticket['user']['name']);
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
                $tickets[$row->id]['user'] = Pi::user()->get($row->uid, array('id', 'identity', 'name', 'email'));
                $tickets[$row->id]['user']['avatar'] = Pi::service('user')->avatar($row->uid, 'small', $tickets[$row->id]['user']['name']);
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
                $values['uid'] = Pi::user()->getId();
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
                    Pi::model('ticket', $this->getModule())->update(array('status' => 2), array('id' => $ticket['id']));
                }
                // Jump
                $message = __('Your answer user support ticket successfully');
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
    }
}