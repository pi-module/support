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
use Zend\Db\Sql\Predicate\Expression;
use Zend\Json\Json;

/*
 * Pi::api('ticket', 'support')->getTicket($parameter, $type = 'id');
 * Pi::api('ticket', 'support')->getCount($uid);
 * Pi::api('ticket', 'support')->status($status);
 * Pi::api('ticket', 'support')->label($label);
 * Pi::api('ticket', 'support')->canonizeTicket($ticket);
 */

class Ticket extends AbstractApi
{
    public function getTicket($parameter, $type = 'id')
    {
        $ticket = Pi::model('ticket', $this->getModule())->find($parameter, $type);
        $ticket = $this->canonizeTicket($ticket);
        return $ticket;
    }

    public function getCount($uid = '')
    {
        // Get user id if not set
        if (empty($uid)) {
            $uid = Pi::user()->getId();
        }
        // Check user id
        if (!$uid || $uid == 0) {
            return array();
        }

        $where = array(
            'uid' => $uid,
            'mid' => 0,
            'status' => array(2, 4),
        );
        $columns = array('count' => new Expression('count(*)'));

        $select = Pi::model('ticket', $this->getModule())->select()->columns($columns)->where($where);
        $count = Pi::model('ticket', $this->getModule())->selectWith($select)->current()->count;
        return $count;
    }

    public function status($status) {
        $ticket = array();
        switch ($status) {
            case 1:
                $ticket['status_view'] = __('Open');
                $ticket['status_class'] = 'label-info';
                $ticket['status_btn'] = 'btn-info';
                break;

            case 2:
                $ticket['status_view'] = __('Answered');
                $ticket['status_class'] = 'label-primary';
                $ticket['status_btn'] = 'btn-primary';
                break;

            case 3:
                $ticket['status_view'] = __('Customer-Reply');
                $ticket['status_class'] = 'label-warning';
                $ticket['status_btn'] = 'btn-warning';
                break;

            case 4:
                $ticket['status_view'] = __('In Progress');
                $ticket['status_class'] = 'label-danger';
                $ticket['status_btn'] = 'btn-danger';
                break;

            case 5:
                $ticket['status_view'] = __('Finished');
                $ticket['status_class'] = 'label-success';
                $ticket['status_btn'] = 'btn-success';
                break;
        }

        return $ticket;
    }
    
    public function label($label)
    {
        if ($label > 0) {
            $labelList = Pi::registry('labelList', 'support')->read();
            return array(
                'label_title' => $labelList[$label]['title'],
                'label_color' => $labelList[$label]['color'],
            );
        } else {
            return array(
                'label_title' => __(''),
                'label_color' => 'inherit',
            );
        }
    }

    public function canonizeTicket($ticket)
    {
        // Check
        if (empty($ticket)) {
            return '';
        }
        // object to array
        $ticket = $ticket->toArray();
        // Set message
        $ticket['message'] = Pi::service('markup')->render($ticket['message'], 'html', 'text');
        // Set item url
        $ticket['ticketUrl'] = Pi::url(Pi::service('url')->assemble('support', array(
            'module' => $this->getModule(),
            'controller' => 'ticket',
            'id' => $ticket['id'],
        )));
        // Set time
        $ticket['time_create_view'] = _date($ticket['time_create']);
        $ticket['time_update_view'] = _date($ticket['time_update']);
        $status = $this->status($ticket['status']);
        $label = $this->label($ticket['label']);
        $ticket = array_merge($ticket, $status, $label);

        return $ticket;
    }
}