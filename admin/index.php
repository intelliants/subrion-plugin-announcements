<?php
/******************************************************************************
 *
 * Subrion - open source content management system
 * Copyright (C) 2017 Intelliants, LLC <https://intelliants.com>
 *
 * This file is part of Subrion.
 *
 * Subrion is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Subrion is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Subrion. If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @package Subrion\Plugin\Blog\Admin
 * @link https://subrion.org/
 * @author https://intelliants.com/ <support@subrion.org>
 * @license https://subrion.org/license.html
 *
 ******************************************************************************/

class iaBackendController extends iaAbstractControllerModuleBackend
{
    protected $_name = 'announcement';

    protected $_table = 'announcements';

    protected $_phraseAddSuccess = 'announcement_added';

    protected $_gridColumns = ['title', 'date_added', 'date_expire', 'status'];

    public function __construct()
    {
        parent::__construct();
        $this->_path = IA_ADMIN_URL . 'announcements' . IA_URL_DELIMITER;
    }

    protected function _indexPage(&$iaView)
    {
        $iaView->grid('_IA_URL_modules/' . $this->getModuleName() . '/js/admin/index');
    }

    protected function _setDefaultValues(array &$entry)
    {
        $entry['title'] = $entry['body'] = '';
        $entry['lang'] = $this->_iaCore->iaView->language;
        $entry['date_added'] = $entry['date_expire'] = date(iaDb::DATETIME_FORMAT);
        $entry['status'] = iaCore::STATUS_ACTIVE;
        $entry['member_id'] = iaUsers::getIdentity()->id;
    }

    protected function _preSaveEntry(array &$entry, array $data, $action)
    {
        parent::_preSaveEntry($entry, $data, $action);

        iaUtil::loadUTF8Functions('ascii', 'validation', 'bad', 'utf8_to_ascii');

        if (!array_key_exists($entry['lang'], $this->_iaCore->languages)) {
            $entry['lang'] = $this->_iaCore->iaView->language;
        }

        if (!utf8_is_valid($entry['title'])) {
            $entry['title'] = utf8_bad_replace($entry['title']);
        }

        if (!utf8_is_valid($entry['body'])) {
            $entry['body'] = utf8_bad_replace($entry['body']);
        }

        if ($entry['body']) {
            $entry['body'] = iaSanitize::tags($entry['body']);
        }

        if (empty($entry['title'])) {
            $this->addMessage('title_is_empty');
        }

        if (empty($entry['body'])) {
            $this->addMessage(iaLanguage::getf('field_is_empty', ['field' => iaLanguage::get('body')]), false);
        }

        if (empty($entry['date_expire']) || $entry['date_expire'] < date(iaDb::DATETIME_FORMAT)) {
            $this->addMessage('choose_date_expire');
        }

        if (empty($entry['date_added'])) {
            $entry['date_added'] = date(iaDb::DATETIME_FORMAT);
        }

        unset($entry['owner']);

        return !$this->getMessages();
    }

    protected function _postSaveEntry(array &$entry, array $data, $action)
    {
        $iaCache = $this->_iaCore->factory('cache');
        $iaCache->remove('announcements');

        $iaLog = $this->_iaCore->factory('log');

        $actionCode = (iaCore::ACTION_ADD == $action)
            ? iaLog::ACTION_CREATE
            : iaLog::ACTION_UPDATE;
        $params = [
            'module' => 'announcements',
            'item' => 'announcement',
            'name' => $entry['title'],
            'id' => $this->getEntryId(),
        ];

        $iaLog->write($actionCode, $params);
    }

    protected function _assignValues(&$iaView, array &$entryData)
    {
        $iaUsers = $this->_iaCore->factory('users');
        $owner = empty($entryData['member_id']) ? iaUsers::getIdentity(true) : $iaUsers->getInfo($entryData['member_id']);

        $entryData['owner'] = $owner['fullname'] . " ({$owner['email']})";
    }
}
