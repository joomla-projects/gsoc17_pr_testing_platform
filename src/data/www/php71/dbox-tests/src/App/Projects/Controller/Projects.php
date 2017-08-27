<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Projects\Controller;

use App\Projects\Model\ProjectsModel;

use JTracker\Controller\AbstractTrackerController;

/**
 * Controller class for the projects view
 *
 * @since __DEPLOY_VERSION__
 */
class Projects extends AbstractTrackerController
{
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

		$this->model->setUser($this->getContainer()->get('app')->getUser());

		return $this;
	}
}
