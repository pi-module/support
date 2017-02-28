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

            case 6:
                $ticket['status_view'] = __('Hold');
                $ticket['status_class'] = 'label-info';
                $ticket['status_btn'] = 'btn-info';
                break;

            case 7:
                $ticket['status_view'] = __('Development');
                $ticket['status_class'] = 'label-success';
                $ticket['status_btn'] = 'btn-success';
                break;

            case 8:
                $ticket['status_view'] = __('Support');
                $ticket['status_class'] = 'label-danger';
                $ticket['status_btn'] = 'btn-danger';
                break;

            case 9:
                $ticket['status_view'] = __('Financial');
                $ticket['status_class'] = 'label-danger';
                $ticket['status_btn'] = 'btn-danger';
                break;

            case 10:
                $ticket['status_view'] = __('Follow up');
                $ticket['status_class'] = 'label-danger';
                $ticket['status_btn'] = 'btn-danger';
                break;
        }

        return $ticket;
    }

    public function statusFinancial($statusFinancial)
    {
        $ticket = array();
        switch ($statusFinancial) {
            case 0:
                $ticket['status_financial_view'] = __('Not defined');
                $ticket['status_financial_class'] = 'label-warning';
                $ticket['status_financial_btn'] = 'btn-warning';
                break;
                
            case 1:
                $ticket['status_financial_view'] = __('Paid');
                $ticket['status_financial_class'] = 'label-success';
                $ticket['status_financial_btn'] = 'btn-success';
                break;

            case 2:
                $ticket['status_financial_view'] = __('Not paid');
                $ticket['status_financial_class'] = 'label-danger';
                $ticket['status_financial_btn'] = 'btn-danger';
                break;

            case 3:
                $ticket['status_financial_view'] = __('Including contract');
                $ticket['status_financial_class'] = 'label-success';
                $ticket['status_financial_btn'] = 'btn-success';
                break;

            case 4:
                $ticket['status_financial_view'] = __('Free');
                $ticket['status_financial_class'] = 'label-success';
                $ticket['status_financial_btn'] = 'btn-success';
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
                'label_title' => '',
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
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
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
        $ticket['time_suggested_view'] = ($ticket['time_suggested'] > 0) ? sprintf('%s %s', _number($ticket['time_suggested']), __('minute')) : '-';
        $ticket['time_execution_view'] = ($ticket['time_execution'] > 0) ? sprintf('%s %s', _number($ticket['time_execution']), __('minute')) : '-';
        // Set file
        if (!empty($ticket['file_path']) && !empty($ticket['file_name']) && $config['file_active']) {
            $ticket['file_title_view'] = sprintf('%s : %s', __('Attache file'), $ticket['file_title']);
            $ticket['file_url_admin'] = Pi::url(Pi::service('url')->assemble('admin', array(
                'module' => $this->getModule(),
                'controller' => 'ticket',
                'action' => 'download',
                'id' => $ticket['id'],
            )));
            $ticket['file_url_user'] = Pi::url(Pi::service('url')->assemble('support', array(
                'module' => $this->getModule(),
                'controller' => 'ticket',
                'action' => 'download',
                'id' => $ticket['id'],
            )));
        }
        // Set extra information
        $status = $this->status($ticket['status']);
        $statusFinancial = $this->statusFinancial($ticket['status_financial']);
        $label = $this->label($ticket['label']);
        $ticket = array_merge($ticket, $status, $statusFinancial, $label);

        return $ticket;
    }
}