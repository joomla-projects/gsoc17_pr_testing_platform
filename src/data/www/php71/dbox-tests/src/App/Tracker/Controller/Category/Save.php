<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Tracker\Controller\Category;

use App\Tracker\Model\CategoryModel;

use JTracker\Controller\AbstractTrackerController;

/**
 * Controller class to save an item to the categories.
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
    protected $defaultView = 'category';

    /**
    * Model object
    *
    * @var    CategoryModel
    * @since __DEPLOY_VERSION__
    */
    protected $model;

    /**
    * Execute the controller.
    *
    * @return  string
    *
    * @since   __DEPLOY_VERSION__
    */
    public function execute()
	{
		/* @type \JTracker\Application $app */
		$app = $this->getContainer()->get('app');

		$app->getUser()->authorize('manage');
		$project = $app->getProject();

		try
		{
			$this->model->setProject($project);
			$data = $app->input->get('category', [], 'array');

			if (isset($data['id']))
			{
				$this->model->save($data);
			}
			else
			{
				$this->model->add($data);
			}

			// Reload the project.
			$this->model->setProject($project);

			$app->enqueueMessage('The changes have been saved.', 'success');
			$app->redirect($app->get('uri.base.path') . 'category/' . $project->alias);
		}
		catch (\Exception $exception)
		{
			$app->enqueueMessage($exception->getMessage(), 'error');

			if ($app->input->get('id'))
			{
				$app->redirect($app->get('uri.base.path') . 'category/' . $project->alias . '/' . $app->input->get('id') . '/edit');
			}
			else
			{
				$app->redirect($app->get('uri.base.path') . 'category/' . $project->alias . '/add');
			}
		}

		parent::execute();
	}
}
