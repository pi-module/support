<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt BSD 3-Clause License
 * @package         Registry
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Support\Registry;

use Pi;
use Pi\Application\Registry\AbstractRegistry;

/**
 * LabelList list
 */
class LabelList extends AbstractRegistry
{
    /** @var string Module name */
    protected $module = 'support';

    /**
     * {@inheritDoc}
     */
    protected function loadDynamic($options = [])
    {
        $return = [];
        $where  = ['status' => 1];
        $order  = ['time_update DESC', 'id DESC'];
        $select = Pi::model('label', $this->module)->select()->where($where)->order($order);
        $rowSet = Pi::model('label', $this->module)->selectWith($select);
        foreach ($rowSet as $row) {
            $return[$row->id] = $row->toArray();
        }
        return $return;
    }

    /**
     * {@inheritDoc}
     * @param array
     */
    public function read()
    {
        $options = [];
        $result  = $this->loadData($options);

        return $result;
    }

    /**
     * {@inheritDoc}
     * @param bool $name
     */
    public function create()
    {
        $this->clear('');
        $this->read();

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function setNamespace($meta = '')
    {
        return parent::setNamespace('');
    }

    /**
     * {@inheritDoc}
     */
    public function flush()
    {
        return $this->clear('');
    }
}
