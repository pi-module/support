<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Support\Api;

use Pi;
use Pi\Application\Api\AbstractApi;
use Laminas\Db\Sql\Predicate\Expression;

/*
 * Pi::api('label', 'support')->getLabelList();
 * Pi::api('label', 'support')->updateCount($label);
 */

class Label extends AbstractApi
{
    public function getLabelList()
    {
        // Get info
        $list   = [];
        $where  = ['status' => 1];
        $order  = ['time_update DESC', 'id DESC'];
        $select = Pi::model('label', $this->getModule())->select()->where($where)->order($order);
        $rowSet = Pi::model('label', $this->getModule())->selectWith($select);
        // Make list
        foreach ($rowSet as $row) {
            $list[$row->id] = $row->toArray();
        }
        return $list;
    }

    public function updateCount($label)
    {
        // Get count
        $where  = ['label' => $label, 'mid' => 0];
        $count  = ['count' => new Expression('count(*)')];
        $select = Pi::model('ticket', $this->getModule())->select()->columns($count)->where($where);
        $count  = Pi::model('ticket', $this->getModule())->selectWith($select)->current()->count;
        // Update main ticket status
        Pi::model('label', $this->getModule())->update(
            ['ticket' => $count],
            ['id' => $label]
        );
    }
}
