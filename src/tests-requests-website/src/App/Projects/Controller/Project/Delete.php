<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Projects\Controller\Project;

use App\Projects\Model\ProjectModel;
use App\Projects\Model\ProjectsModel;

use JTracker\Controller\AbstractTrackerController;

/**
 * Controller class to delete a project.
 *
 * @since __DEPLOY_VERSION__
 */
class Delete extends AbstractTrackerController
{
    /**
    * The default view for the component
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $defaultView = 'projects';

    /**
    * Model object
    *
    * @var    ProjectsModel
    * @since __DEPLOY_VERSION__
    */
    protected $model;

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
		parent::initialize();

		$this->getContainer()->get('app')->getUser()->authorize('admin');

		$this->model->setUser($this->getContainer()->get('app')->getUser());
	}

    /**
    * Execute the controller.
    *
    * @return  string
    *
    * @since   __DEPLOY_VERSION__
    */
    public function execute()
	{
		(new ProjectModel($this->getContainer()->get('db')))
			->delete($this->getContainer()->get('app')->input->get('project_alias'));

		// Reload the project
		$this->getContainer()->get('app')->getProject(true);

		return parent::execute();
	}
}
