<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Instances\Controller\Instance;

use App\Instances\Model\InstanceModel;

use App\Instances\Table\InstancesTable;
use App\Instances\View\Instances\InstancesHtmlView;

use JTracker\Controller\AbstractTrackerController;

/**
 * Controller class to delete a Joomla! instance
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
    protected $defaultView = 'instances';

    /**
    * Model object
    *
    * @var    InstanceModel
    * @since __DEPLOY_VERSION__
    */
    protected $model;

    /**
    * View object
    *
    * @var    InstancesHtmlView
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

		$this->model->setUser($this->getContainer()->get('app')->getUser());
		$this->model->setProject($this->getContainer()->get('app')->getProject());
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
		$application = $this->getContainer()->get('app');
		$database = $this->getContainer()->get('db');

		try
		{
			$instanceID = $application->input->get('instance_id');

			(new InstanceModel($database))
				->delete($instanceID);

			$application->enqueueMessage(g11n3t('Joomla! instance #' . $instanceID . ' has been discarded.'), 'success');
		}
		catch (\Exception $exception)
		{
			$application->enqueueMessage($exception->getMessage(), 'error');
		}

		$application->redirect($application->get('uri.base.path') . 'instances/');

		return parent::execute();

	}
}

