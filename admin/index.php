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
    protected $_name = 'announcements';

    protected $_helperName = 'announcement';

    protected $_phraseAddSuccess = 'announcement_added';

    protected $_gridColumns = ['title', 'date_added', 'date_expire', 'status'];
    protected $_gridFilters = ['title' => self::LIKE, 'status' => self::EQUAL];

    public function __construct()
    {
        parent::__construct();
        $this->_path = IA_ADMIN_URL . 'announcements' . IA_URL_DELIMITER;
    }

    protected function _indexPage(&$iaView)
    {
        $iaView->grid('_IA_URL_modules/' . $this->getModuleName() . '/js/admin/index');
    }

    protected function _entryAdd(array $entryData)
    {
        $entryData['date_added'] = date(iaDb::DATETIME_FORMAT);

        return parent::_entryAdd($entryData);
    }
 
    public function updateCounters($entryId, array $entryData, $action, $previousData = null)
    {
        $this->_iaCore->factory('cache')->remove('announcements');
    }

    protected function _setDefaultValues(array &$entry)
    { 
        $entry['title'] = $entry['body'] = '';
        $entry['date_added'] = date(iaDb::DATETIME_FORMAT);
        $entry['date_expire'] = date(iaDb::DATETIME_FORMAT, time() + 86400);
        $entry['member_id'] = iaUsers::getIdentity() -> id;
        $entry['status'] = iaCore::STATUS_ACTIVE;
    }

    protected function _assignValues(&$iaView, array &$entryData)
    {
        parent::_assignValues($iaView, $entryData);

        unset($entryData['date_added']);
    }
}
