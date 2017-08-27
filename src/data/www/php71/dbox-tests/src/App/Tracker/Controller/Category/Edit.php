<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Tracker\Controller\Category;

use App\Tracker\Model\CategoryModel;
use App\Tracker\View\Category\CategoryHtmlView;

use JTracker\Controller\AbstractTrackerController;

/**
 * Controller class to edit an item of the category
 *
 * @since __DEPLOY_VERSION__
 */
class Edit extends AbstractTrackerController
{
    /**
    * The default view for the component.
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $defaultView = 'category';

    /**
    * The default layout for the component.
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $defaultLayout = 'edit';

    /**
    * View object
    *
    * @var    CategoryHtmlView
    * @since __DEPLOY_VERSION__
    */
    protected $view = null;

    /**
    * Model object
    *
    * @var    CategoryModel
    * @since __DEPLOY_VERSION__
    */
    protected $model = null;

    /**
    * Execute the controller.
    *
    * @return  string
    *
    * @since   __DEPLOY_VERSION__
    */
    public function execute()
	{
		/* @type \JTracker\Application $application */
		$application = $this->getContainer()->get('app');
		$application->getUser()->authorize('manage');

		$item = $this->model->getItem($application->input->getUint('id'));
		$this->view->setProject($application->getProject());
		$this->model->setProject($application->getProject());
		$this->view->setItem($item);

		return parent::execute();
	}
}
