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

namespace Module\Support\Form\Element;

use Pi;
use Zend\Form\Element\Select;

class User extends Select
{
    /**
     * @return array
     */
    public function getValueOptions()
    {
        if (empty($this->valueOptions)) {
            $userList = Pi::api('user', 'support')->getUserList();
            $list[0] = __('All users');
            foreach ($userList as $user) {
                $list[$user['id']] = $user['display'];
            }
            $this->valueOptions = $list;
        }
        return $this->valueOptions;
    }
}