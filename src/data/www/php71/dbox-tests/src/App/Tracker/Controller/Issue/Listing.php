<?php
/**
 * Part of the Joomla Tracker's Tracker Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Tracker\Controller\Issue;

use App\Tracker\Controller\DefaultController;

/**
 * List controller class for the Tracker component.
 *
 * NOTE: This controller will only be called if the project changes,
 * otherwise the default controller is called.
 *
 * @since __DEPLOY_VERSION__
 */
class Listing extends DefaultController
{
    /**
    * Initialize the controller.
    *
    * @return  $this  Method supports chaining
    *
    * @since   __DEPLOY_VERSION__
    */
    public function initialize()
	{
		// Reload the project.
		$this->getContainer()->get('app')->getProject(true);

		parent::initialize();

		return $this;
	}
}
