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

namespace Module\Support\Controller\Admin;

use Pi;
use Pi\Mvc\Controller\ActionController;

class UserController extends ActionController
{
    public function indexAction()
    {
        // Get info
        $list = Pi::api('user', 'support')->getUserList();
        // Set view
        $this->view()->setTemplate('user-index');
        $this->view()->assign('list', $list);
    }
}
