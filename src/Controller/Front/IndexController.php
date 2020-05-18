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

namespace Module\Support\Controller\Front;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Pi\Paginator\Paginator;
use Laminas\Db\Sql\Predicate\Expression;

class IndexController extends ActionController
{
    public function indexAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();

        // Get info from url
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Get user info
        $uid                = Pi::user()->getId();
        $user               = Pi::user()->get($uid, ['id', 'identity', 'name', 'email']);
        $user['avatar']     = Pi::service('user')->avatar($user['id'], 'medium', $user['name']);
        $user['profileUrl'] = Pi::url(
            Pi::service('user')->getUrl(
                'profile', [
                    'id' => $user['id'],
                ]
            )
        );
        $user['accountUrl'] = Pi::url(
            Pi::service('user')->getUrl(
                'user', ['controller' => 'account']
            )
        );

        // Get page
        $page = $this->params('page', 1);

        // Set info
        $ticket = [];
        $where  = ['mid' => 0, 'uid' => $uid];
        $order  = ['time_update DESC', 'id DESC'];
        $limit  = intval($this->config('view_perpage'));
        $offset = (int)($page - 1) * $limit;

        // Get info
        $select = $this->getModel('ticket')->select()->where($where)->order($order)->offset($offset)->limit($limit);
        $rowSet = $this->getModel('ticket')->selectWith($select);

        // Make list
        foreach ($rowSet as $row) {
            $ticket[$row->id] = Pi::api('ticket', 'support')->canonizeTicket($row);
        }

        // Set count
        $count  = ['count' => new Expression('count(*)')];
        $select = $this->getModel('ticket')->select()->columns($count)->where($where);
        $count  = $this->getModel('ticket')->selectWith($select)->current()->count;

        // Set paginator
        $paginator = Paginator::factory(intval($count));
        $paginator->setItemCountPerPage($this->config('view_perpage'));
        $paginator->setCurrentPageNumber($page);
        $paginator->setUrlOptions(
            [
                'router' => $this->getEvent()->getRouter(),
                'route'  => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
                'params' => array_filter(
                    [
                        'module'     => $this->getModule(),
                        'controller' => 'index',
                        'action'     => 'index',
                    ]
                ),
            ]
        );

        // Set submit link
        $submit = Pi::url(
            $this->url(
                '', [
                    'controller' => 'ticket',
                    'action'     => 'index',
                ]
            )
        );

        // Set view
        $this->view()->assign('tickets', $ticket);
        $this->view()->assign('paginator', $paginator);
        $this->view()->assign('user', $user);
        $this->view()->assign('submit', $submit);
        $this->view()->assign('config', $config);
    }
}
