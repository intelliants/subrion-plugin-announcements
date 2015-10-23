<?php
//##copyright##

if (iaView::REQUEST_HTML == $iaView->getRequestType() && $iaView->blockExists('announcements'))
{
	// deactivate expired
	$iaDb->update(array('status' => iaCore::STATUS_INACTIVE), "`expire_date` < '" . date(iaDb::DATE_FORMAT) . "'", null, 'announcements');

	// initialize caching
	require_once IA_INCLUDES . 'phpfastcache' . IA_DS . 'phpfastcache.php';

	$iaCache = phpFastCache('auto', array('path' => IA_CACHEDIR));

	$entries = $iaCache->get('announcements_entries');
	if (null == $entries)
	{
		$order = ('date' == $iaCore->get('entries_order')) ? '`date`' : 'RAND()';
		$entries = $iaDb->all(iaDb::ALL_COLUMNS_SELECTION, "`status` = 'active' ORDER BY $order", 0, $iaCore->get('entries_limit'), 'announcements');
		$iaCache->set('announcements_entries', $entries, 24 * 3600);
	}
	$iaView->assign('entries', $entries);

	// add custom styles for decoration
	$iaView->add_css('_IA_URL_plugins/announcements/templates/front/css/style');
}