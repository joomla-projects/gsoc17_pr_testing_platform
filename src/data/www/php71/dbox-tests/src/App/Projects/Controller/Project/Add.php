<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Projects\Controller\Project;

use JTracker\Controller\AbstractTrackerController;

/**
 * Controller class to add a project.
 *
 * @since __DEPLOY_VERSION__
 */
class Add extends AbstractTrackerController
{
    /**
    * The default view for the component
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $defaultView = 'project';

    /**
    * The default layout for the app.
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $defaultLayout = 'edit';

    /**
    * Execute the controller.
    *
    * @return  string
    *
    * @since   __DEPLOY_VERSION__
    */
    public function execute()
	{
		$this->getContainer()->get('app')->getUser()->authorize('admin');

		return parent::execute();
	}
}
