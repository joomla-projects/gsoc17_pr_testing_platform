<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Users\Controller\Ajax;

use JTracker\Controller\AbstractAjaxController;

/**
 * Default controller class for the Users component.
 *
 * @since __DEPLOY_VERSION__
 */
class Listing extends AbstractAjaxController
{
    /**
    * Prepare the response.
    *
    * @return  void
    *
    * @since   __DEPLOY_VERSION__
    */
    protected function prepareResponse()
	{
		/* @type \JTracker\Application $application */
		$application = $this->getContainer()->get('app');

		$application->getUser()->authorize('manage');

		$groupId = $application->input->getInt('group_id');

		$users = [];

		if ($groupId)
		{
			$db = $this->getContainer()->get('db');

			$query = $db->getQuery(true)
				->select($db->quoteName(['u.id', 'u.username']))
				->from($db->quoteName('#__users', 'u'))
				->where($db->quoteName('m.group_id') . ' = ' . (int) $groupId)
				->leftJoin(
					$db->quoteName('#__user_accessgroup_map', 'm')
					. ' ON ' . $db->quoteName('m.user_id')
					. ' = ' . $db->quoteName('u.id')
				);

			$users = $db->setQuery($query)
				->loadAssocList();
		}

		$this->response->data->options = $users ? : [];
	}
}
