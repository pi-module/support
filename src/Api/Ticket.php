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
 * Pi::api('ticket', 'support')->getTicket($parameter, $type = 'id');
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

    public function canonizeTicket($ticket)
    {
        // Check
        if (empty($ticket)) {
            return '';
        }
        // object to array
        $ticket = $ticket->toArray();
        // Set item url
        $ticket['ticketUrl'] = Pi::url(Pi::service('url')->assemble('support', array(
            'module' => $this->getModule(),
            'controller' => 'ticket',
            'id' => $ticket['id'],
        )));
        // Set time
        $ticket['time_create_view'] = _date($ticket['time_create']);
        // Set status view
        switch ($ticket['status']) {
            case 1:
                $ticket['status_view'] = __('Open');
                $ticket['status_class'] = 'label-success';
                break;

            case 2:
                $ticket['status_view'] = __('Answered');
                $ticket['status_class'] = 'label-primary';
                break;

            case 3:
                $ticket['status_view'] = __('Customer-Reply');
                $ticket['status_class'] = 'label-warning';
                break;

            case 4:
                $ticket['status_view'] = __('In Progress');
                $ticket['status_class'] = 'label-danger';
                break;

            case 5:
                $ticket['status_view'] = __('Closed');
                $ticket['status_class'] = 'label-default';
                break;
        }

        return $ticket;
    }
}