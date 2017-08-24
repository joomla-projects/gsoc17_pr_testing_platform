<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Tracker\Controller\Category;

use App\Tracker\Model\CategoriesModel;
use App\Tracker\View\Categories\CategoriesHtmlView;

use JTracker\Controller\AbstractTrackerListController;

/**
 * List controller class for category.
 *
 * @since __DEPLOY_VERSION__
 */
class Listing extends AbstractTrackerListController
{
    /**
    * View object
    *
    * @var   CategoriesHtmlView
    * @since __DEPLOY_VERSION__
    */
    protected $view;

    /**
    * The default view for the app
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $defaultView = 'categories';

    /**
    * Model object
    *
    * @var    CategoriesModel
    * @since __DEPLOY_VERSION__
    */
    protected $model;

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
		$application->getUser()->authorize('manage');

		$this->model->setProject($this->getContainer()->get('app')->getProject(true));
		$this->view->setProject($this->getContainer()->get('app')->getProject());

		return $this;
	}
}
