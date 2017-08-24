<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Projects\Controller\Project;

use App\Projects\Model\ProjectsModel;
use App\Projects\Table\ProjectsTable;

use JTracker\Controller\AbstractTrackerController;

/**
 * Controller class to save a project.
 *
 * @since __DEPLOY_VERSION__
 */
class Save extends AbstractTrackerController
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

		$this->model->setUser($this ->getContainer()->get('app')->getUser());

		return $this;
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
		$app = $this->getContainer()->get('app');

		$app->getUser()->authorize('admin');

		(new ProjectsTable($this->getContainer()->get('db')))
			->save($app->input->get('project', [], 'array'));

		// Reload the project.
		$app->getProject(true);

		return parent::execute();
	}
}
