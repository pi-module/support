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
namespace Module\Support\Installer\Action;

use Pi;
use Pi\Application\Installer\Action\Update as BasicUpdate;
use Pi\Application\Installer\SqlSchema;
use Zend\EventManager\Event;
use Zend\Json\Json;

class Update extends BasicUpdate
{
    /**
     * {@inheritDoc}
     */
    protected function attachDefaultListeners()
    {
        $events = $this->events;
        $events->attach('update.pre', array($this, 'updateSchema'));
        parent::attachDefaultListeners();

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function updateSchema(Event $e)
    {
        $moduleVersion = $e->getParam('version');

        // Set ticket model
        $ticketModel = Pi::model('ticket', $this->module);
        $ticketTable = $ticketModel->getTable();
        $ticketAdapter = $ticketModel->getAdapter();

        // Set user model
        $userModel = Pi::model('user', $this->module);
        $userTable = $userModel->getTable();
        $userAdapter = $userModel->getAdapter();

        // Update to version 0.0.3
        if (version_compare($moduleVersion, '0.0.3', '<')) {
            // Alter table field `type`
            $sql = sprintf("ALTER TABLE %s ADD `time_update` INT(10) UNSIGNED NOT NULL DEFAULT '0'", $ticketTable);
            try {
                $ticketAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
        }

        // Update to version 0.0.4
        if (version_compare($moduleVersion, '0.0.4', '<')) {
            // Add table of user
            $sql = <<<'EOD'
CREATE TABLE `{user}` (
  `id`          INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticket`      INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `reply`       INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `time_update` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ticket` (`ticket`),
  KEY `reply` (`reply`),
  KEY `time_update` (`time_update`)
);
EOD;
            SqlSchema::setType($this->module);
            $sqlHandler = new SqlSchema;
            try {
                $sqlHandler->queryContent($sql);
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'SQL schema query for author table failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }

            // Update user table
            $list = array();
            $order = array('time_update DESC');

            $where = array('mid' => 0);
            $select = $ticketModel->select()->where($where)->order($order);
            $rowset = $ticketModel->selectWith($select);
            foreach ($rowset as $row) {
                if (isset($list[$row->uid])) {
                    $list[$row->uid]['ticket'] = $list[$row->uid]['ticket'] + 1;
                    $list[$row->uid]['time_update'] = $row->time_create;
                } else {
                    $list[$row->uid] = array(
                        'id' => $row->uid,
                        'ticket' => 1,
                        'reply' => 0,
                        'time_update' => $row->time_create,
                    );
                }
            }

            $where = array('mid != ?' => 0);
            $select = $ticketModel->select()->where($where)->order($order);
            $rowset = $ticketModel->selectWith($select);
            foreach ($rowset as $row) {
                if (isset($list[$row->uid])) {
                    $list[$row->uid]['reply'] = $list[$row->uid]['reply'] + 1;
                    $list[$row->uid]['time_update'] = $row->time_create;
                }
            }

            foreach($list as $single) {
                if (isset($single['id']) && $single['id'] > 0) {
                    $user = $userModel->createRow();
                    $user->id = $single['id'];
                    $user->ticket = $single['ticket'];
                    $user->reply = $single['reply'];
                    $user->time_update = $single['time_update'];
                    $user->save();
                }
            }
        }

        // Update to version 0.1.0
        if (version_compare($moduleVersion, '0.1.0', '<')) {
            // Add table of label
            $sql = <<<'EOD'
CREATE TABLE `{label}` (
  `id`          INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(255)        NOT NULL DEFAULT '',
  `ticket`      INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `status`      TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `color`       VARCHAR(8)          NOT NULL DEFAULT '',
  `time_update` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ticket` (`ticket`),
  KEY `status` (`status`),
  KEY `time_update` (`time_update`)
);

EOD;
            SqlSchema::setType($this->module);
            $sqlHandler = new SqlSchema;
            try {
                $sqlHandler->queryContent($sql);
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'SQL schema query for author table failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }

            // Alter table field `label`
            $sql = sprintf("ALTER TABLE %s ADD `label` INT(10) UNSIGNED NOT NULL DEFAULT '0'", $ticketTable);
            try {
                $ticketAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
        }

        // Update to version 0.1.2
        if (version_compare($moduleVersion, '0.1.2', '<')) {
            // Alter table field `status_financial`
            $sql = sprintf("ALTER TABLE %s ADD `status_financial` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'", $ticketTable);
            try {
                $ticketAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
            // Alter table field `time_suggested`
            $sql = sprintf("ALTER TABLE %s ADD `time_suggested` INT(10) UNSIGNED NOT NULL DEFAULT '0'", $ticketTable);
            try {
                $ticketAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
            // Alter table field `time_execution`
            $sql = sprintf("ALTER TABLE %s ADD `time_execution` INT(10) UNSIGNED NOT NULL DEFAULT '0'", $ticketTable);
            try {
                $ticketAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
        }

        return true;
    }
}