<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Activity\Controller;

use App\Activity\View\DefaultHtmlView;

use JTracker\Controller\AbstractTrackerController;

/**
 * Base controller class for the Activity application.
 *
 * @since __DEPLOY_VERSION__
 */
abstract class AbstractBaseController extends AbstractTrackerController
{
    /**
    * View object
    *
    * @var    DefaultHtmlView
    * @since __DEPLOY_VERSION__
    */
    protected $view;

    /**
    * Initialize the controller.
    *
    * @return  $this  Method supports chaining
    *
    * @since   __DEPLOY_VERSION__
    */
    public function initialize()
	{
		parent::initialize();

		/* @type \JTracker\Application $application */
		$application = $this->getContainer()->get('app');

		$application->getUser()->authorize('view');

		$this->model->setProject($application->getProject());
		$this->view->setProject($application->getProject());

		return $this;
	}
}
