<?php
/**
 * Part of the Joomla Tracker Model Package
 *
 * @copyright  Copyright (C) 2014 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Tracker\Controller\Fetch\Ajax;

use JTracker\Controller\AbstractAjaxController;

/**
 * Controller to respond AJAX request.
 *
 * @since __DEPLOY_VERSION__
 */
class Users extends AbstractAjaxController
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

		/* @type \Joomla\Database\DatabaseDriver $db */
		$db = $this->getContainer()->get('db');

		$username = $application->input->getCmd('q');

		if ($username)
		{
			$this->response->data = $db
				->setQuery(
					$db->getQuery(true)
						->select($db->quoteName(['username', 'name']))
						->from($db->quoteName('#__users'))
						->where($db->quoteName('username') . " LIKE '%" . $username . "%'"),
					0, 10
				)
				->loadAssocList();
		}
	}
}
