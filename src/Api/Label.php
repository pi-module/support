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

/*
 * Pi::api('label', 'support')->getLabelList();
 * Pi::api('label', 'support')->updateCount($label);
 */

class Label extends AbstractApi
{
    public function getLabelList()
    {
        // Get info
        $list = array();
        $where = array('status' => 1);
        $order = array('time_update DESC', 'id DESC');
        $select = Pi::model('label', $this->getModule())->select()->where($where)->order($order);
        $rowset = Pi::model('label', $this->getModule())->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $list[$row->id] = $row->toArray();
        }
        return $list;
    }

    public function updateCount($label)
    {
        // Get count
        $where = array('label' => $label, 'mid' => 0);
        $count = array('count' => new Expression('count(*)'));
        $select = Pi::model('ticket', $this->getModule())->select()->columns($count)->where($where);
        $count = Pi::model('ticket', $this->getModule())->selectWith($select)->current()->count;
        // Update main ticket status
        Pi::model('label', $this->getModule())->update(
            array('ticket' => $count),
            array('id' => $label)
        );
    }
}