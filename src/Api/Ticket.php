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
 * Pi::api('ticket', 'support')->canonizeTicket($ticket);
 */

class Ticket extends AbstractApi
{
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

        return $ticket;
    }
}