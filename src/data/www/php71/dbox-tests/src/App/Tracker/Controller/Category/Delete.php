<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Tracker\Controller\Category;

use App\Tracker\Model\CategoryModel;
use App\Tracker\View\Categories\CategoriesHtmlView;

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
    protected $defaultView = 'category';

    /**
    * Model object
    *
    * @var    CategoryModel
    * @since __DEPLOY_VERSION__
    */
    protected $model;

    /**
    * View object
    *
    * @var    CategoriesHtmlView
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
		parent::initialize();
		$this->getContainer()->get('app')->getUser()->authorize('manage');
		$this->model->setProject($this->getContainer()->get('app')->getProject());
		$this->view->setProject($this->getContainer()->get('app')->getProject());

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
		/* @type \JTracker\Application $application */
		$application = $this->getContainer()->get('app');

		try
		{
			$this->model->delete($application->input->get('id'));
			$application->enqueueMessage(g11n3t('The category has been deleted'), 'success');
		}
		catch (\Exception $exception)
		{
			$application->enqueueMessage($exception->getMessage(), 'error');
		}

		$application->redirect($application->get('uri.base.path') . 'category/' . $application->getProject()->alias);

		return parent::execute();
	}
}
