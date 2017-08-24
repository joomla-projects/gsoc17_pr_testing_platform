<?php
/**
 * Part of the Joomla Tracker's Tracker Application
 *
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App\Instances\Controller\Instance;

use App\Instances\Model\InstanceModel;

use JTracker\Controller\AbstractTrackerController;

/**
 *  Controller class to add a Joomla! instance
 *
 * @since __DEPLOY_VERSION__
 */
class Add extends AbstractTrackerController
{
	/**
	 * The default view for the component
	 *
	 * @var    string
	 * @since __DEPLOY_VERSION__
	 */
	protected $defaultView = 'instance';

	/**
	 * Model object
	 *
	 * @var    InstanceModel
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
		$db = $this->getContainer()->get('db');

		try
		{
			$data = $app->input->get('instance', [], 'array');

			//$this->model->save($data);

			(new InstanceModel($db))
				->add($data);

			$app->enqueueMessage('A Joomla! instance has been generated.', 'success');
		}
		catch (\Exception $exception)
		{
			$app->enqueueMessage($exception->getMessage(), 'error');
		}

		$app->redirect($app->get('uri.base.path') . 'instances/');

		return parent::execute();
	}
}

