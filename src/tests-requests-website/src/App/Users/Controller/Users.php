<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Users\Controller;

use JTracker\Controller\AbstractTrackerListController;

/**
 * Users controller class for the users component
 *
 * @since __DEPLOY_VERSION__
 */
class Users extends AbstractTrackerListController
{
    /**
    * Initialize the controller.
    *
    * @return  $this  Method allows chaining
    *
    * @since   __DEPLOY_VERSION__
    */
    public function initialize()
	{
		parent::initialize();

		/* @type \JTracker\Application $application */
		$application = $this->getContainer()->get('app');

		$state = $this->model->getState();

		$state->set('filter.search-user',
			$application->getUserStateFromRequest('filter.search-user', 'search-user', '', 'string')
		);

		return $this;
	}
}
