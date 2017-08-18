<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Projects\Controller;

use App\Projects\View\Project\ProjectHtmlView;

use JTracker\Controller\AbstractTrackerController;

/**
 * Controller class for the project view
 *
 * @since __DEPLOY_VERSION__
 */
class Project extends AbstractTrackerController
{
    /**
    * View object
    *
    * @var    ProjectHtmlView
    * @since __DEPLOY_VERSION__
    */
    protected $view;

    /**
    * Initialize the controller.
    *
    * This will set up default model and view classes.
    *
    * @return  $this  Method allows chaining
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \RuntimeException
    */
    public function initialize()
	{
		// Reload the project.
		$this->getContainer()->get('app')->getProject(true);

		parent::initialize();

		$this->view->setAlias($this->getContainer()->get('app')->input->get('project_alias'));

		return $this;
	}
}
