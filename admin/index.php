<?php
//##copyright##

$iaAnnouncement = $iaCore->factoryPlugin('announcements', iaCore::ADMIN, 'announcement');

$iaDb->setTable(iaAnnouncement::getTable());

if (iaView::REQUEST_JSON == $iaView->getRequestType())
{
	switch ($pageAction)
	{
		case iaCore::ACTION_READ:
			$output = $iaAnnouncement->gridRead($_GET,
				array('title', 'expire_date','date', 'status'),
				array('title' => 'like', 'status' => 'equal')
			);
			break;

		case iaCore::ACTION_EDIT:
			$output = $iaAnnouncement->gridUpdate($_POST);

			break;

		case iaCore::ACTION_DELETE:
			$output = $iaAnnouncement->gridDelete($_POST);
	}

	$iaView->assign($output);
}

if (iaView::REQUEST_HTML == $iaView->getRequestType())
{
	if (iaCore::ACTION_READ == $pageAction)
	{
		$iaView->grid('_IA_URL_plugins/announcements/js/admin/index');
	}
	elseif (in_array($pageAction, array(iaCore::ACTION_ADD, iaCore::ACTION_EDIT)))
	{
		$announcementEntry = array(
			'lang' => $iaView->language,
			'status' => iaCore::STATUS_ACTIVE
		);

		if (iaCore::ACTION_EDIT == $pageAction)
		{
			if (!isset($iaCore->requestPath[0]))
			{
				return iaView::errorPage(iaView::ERROR_NOT_FOUND);
			}

			$id = (int)$iaCore->requestPath[0];
			$announcementEntry = $iaDb->row(iaDb::ALL_COLUMNS_SELECTION, iaDb::convertIds($id));
			if (empty($announcementEntry))
			{
				return iaView::errorPage(iaView::ERROR_NOT_FOUND);
			}
		}

		$iaCore->factory('util');

		$announcementEntry = array(
			'id' => isset($id) ? $id : 0,
			'lang' => iaUtil::checkPostParam('lang', $announcementEntry),
			'title' => iaUtil::checkPostParam('title', $announcementEntry),
			'status' => iaUtil::checkPostParam('status', $announcementEntry),
			'body' => iaUtil::checkPostParam('body', $announcementEntry),
			'date' => iaUtil::checkPostParam('date', $announcementEntry),
			'expire_date' => iaUtil::checkPostParam('expire_date', $announcementEntry)
		);

		if (empty($announcementEntry['date']))
		{
			$announcementEntry['date'] = date(iaDb::DATETIME_FORMAT);
		}

		if (isset($_POST['save']))
		{
			iaUtil::loadUTF8Functions('ascii', 'validation', 'bad', 'utf8_to_ascii');

			$error = false;
			$messages = array();

			$announcementEntry['status'] = in_array($announcementEntry['status'], array(iaCore::STATUS_ACTIVE, iaCore::STATUS_INACTIVE)) ? $announcementEntry['status'] : iaCore::STATUS_INACTIVE;

			if (!array_key_exists($announcementEntry['lang'], $iaCore->languages))
			{
				$announcementEntry['lang'] = $iaView->language;
			}

			if (!utf8_is_valid($announcementEntry['title']))
			{
				$announcementEntry['title'] = utf8_bad_replace($announcementEntry['title']);
			}

			if (!utf8_is_valid($announcementEntry['body']))
			{
				$announcementEntry['body'] = utf8_bad_replace($announcementEntry['body']);
			}

			if ($announcementEntry['body']){
				$announcementEntry['body'] = iaSanitize::tags($announcementEntry['body']);
			}

			if (empty($announcementEntry['title']))
			{
				$error = true;
				$messages[] = iaLanguage::get('title_is_empty');
			}

			if (empty($announcementEntry['body']))
			{
				$error = true;
				$messages[] = iaLanguage::get('body_is_empty');
			}

			if (empty($announcementEntry['expire_date']) OR $announcementEntry['expire_date'] < date(iaDb::DATETIME_FORMAT))
			{
				$error = true;
				$messages[] = iaLanguage::get('choose_expire_date');
			}

			if (!$error)
			{
				if (iaCore::ACTION_EDIT == $pageAction)
				{
					$announcementEntry['id'] = (int)$iaCore->requestPath[0];
					$iaDb->update($announcementEntry);

					$messages[] = iaLanguage::get('saved');
				}
				else
				{
					$announcementEntry['id'] = $iaDb->insert($announcementEntry);
					$messages[] = iaLanguage::get('entry_added');
				}

				$iaView->setMessages($messages, iaView::SUCCESS);

				// clear cache on changes
				require_once IA_INCLUDES . 'phpfastcache' . IA_DS . 'phpfastcache.php';
				$iaCache = phpFastCache('auto', array('path' => IA_CACHEDIR));
				$iaCache->delete('announcements_entries');

				if (isset($_POST['goto']))
				{
					$url = IA_ADMIN_URL . 'announcements/';
					iaUtil::post_goto(array(
						'add' => $url . 'add/',
						'list' => $url,
						'stay' => $url . 'edit/' . $announcementEntry['id'],
					));
				}
				else
				{
					iaUtil::go_to(IA_ADMIN_URL . 'announcements/edit/' . $announcementEntry['id']);
				}
			}
			else
			{
				$iaView->setMessages($messages);
			}
		}

		$options = array('list' => 'go_to_list', 'add' => 'add_another_one', 'stay' => 'stay_here');
		$iaView->assign('goto', $options);

		$iaView->assign('entry', $announcementEntry);

		$iaView->display('manage');
	}
}
$iaDb->resetTable();