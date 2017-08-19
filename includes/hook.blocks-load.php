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

if (iaView::REQUEST_HTML == $iaView->getRequestType() && $iaView->blockExists('announcements')) {
    // deactivate expired
    // TODO consider moving it to cron
    $iaDb->update(['status' => iaCore::STATUS_INACTIVE], "`date_expire` < '" . date(iaDb::DATE_FORMAT) . "'", null, 'announcements');

    $iaCache = $iaCore->factory('cache');

    $announcements = $iaCache->get('announcements', 24 * 3600, true);
    if (empty($announcements)) {
        $order = ('date' == $iaCore->get('announcements_order')) ? '`date`' : 'RAND()';
        $stmt = "`status` = :status ORDER BY :order";
        $iaDb->bind($stmt, ['status' => iaCore::STATUS_ACTIVE, 'order' => $order]);
        $announcements = $iaDb->all(iaDb::ALL_COLUMNS_SELECTION, $stmt, 0, $iaCore->get('announcements_limit'), 'announcements');
        $iaCache->write('announcements', $announcements);
    }

    $entries = [];
    foreach ($announcements as $announcement) {
        if ($iaView->language == $announcement['lang'])
        $entries[] = $announcement;
    }
    $iaView->assign('announcements', $entries);

    $iaView->add_css('_IA_URL_modules/announcements/templates/front/css/style');
}