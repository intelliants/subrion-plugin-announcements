<?php
/******************************************************************************
 *
 * Subrion - open source content management system
 * Copyright (C) 2018 Intelliants, LLC <https://intelliants.com>
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

if (iaView::REQUEST_HTML == $iaView->getRequestType()) {
    if ($iaView->blockExists('announcements')) {
        $iaAnnouncements = $iaCore->factoryModule('announcement', 'announcements');

        $announcements = $this->iaCache->get('announcements', 24 * 3600, true, true);

        if (false === $announcements) {
            $announcements = $iaAnnouncements->get();

            $this->iaCache->write('announcements', $announcements, true);
        }

        $iaView->assign('announcements', $announcements);
        $iaView->add_css('_IA_URL_modules/announcements/templates/front/css/style');
    }
}
