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
 * @link https://subrion.org/
 *
 ******************************************************************************/

class iaAnnouncement extends abstractModuleFront
{
    protected static $_table = 'announcements';

    protected $_itemName = 'announcement';

    protected $_moduleName = 'announcements';

    public $coreSearchEnabled = true;
    public $coreSearchOptions = [
        'regularSearchFields' => ['title', 'body'],
    ];

    public function get()
    {
        $date = date(iaDb::DATETIME_FORMAT);
        $sql = 'SELECT * FROM `sbr420_announcements`  WHERE `status` = "active" AND `date_expire` >= "'.$date.'" ORDER BY `date_expire` ASC';

        $rows = $this->iaDb->getAll($sql);
        $this->_processValues($rows);

        return $rows;
    }
}