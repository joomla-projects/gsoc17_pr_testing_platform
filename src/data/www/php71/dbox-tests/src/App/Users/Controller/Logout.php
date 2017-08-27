<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Users\Controller;

use JTracker\Application;
use JTracker\Controller\AbstractTrackerController;

/**
 * Logout controller class for the users component
 *
 * @since __DEPLOY_VERSION__
 */
class Logout extends AbstractTrackerController
{
    /**
    * Execute the controller.
    *
    * @return  void
    *
    * @since   __DEPLOY_VERSION__
    */
    public function execute()
	{
		/* @type Application $application */
		$application = $this->getContainer()->get('app');

		// Invalidate the session
		$application->getSession()->invalidate();

		$application
			// Logout the user.
			->setUser(null)
			// Delete the "remember me" cookie
			->setRememberMe(false)
			// Redirect to the "home" page
			->redirect(' ');
	}
}
