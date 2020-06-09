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

namespace Module\Support\Form\Element;

use Pi;
use Laminas\Form\Element\Select;

class User extends Select
{
    /**
     * @return array
     */
    public function getValueOptions()
    {
        if (empty($this->valueOptions)) {
            $userList = Pi::api('user', 'support')->getUserList();
            $list[0]  = __('All users');
            foreach ($userList as $user) {
                $list[$user['id']] = $user['display'];
            }
            $this->valueOptions = $list;
        }
        return $this->valueOptions;
    }
}
