<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Users\Controller;

use App\Users\Model\UserModel;
use App\Users\View\User\UserHtmlView;
use JTracker\Controller\AbstractTrackerController;

/**
 * User controller class for the users component
 *
 * @since __DEPLOY_VERSION__
 */
class User extends AbstractTrackerController
{
    /**
    * View object
    *
    * @var    UserHtmlView
    * @since __DEPLOY_VERSION__
    */
    protected $view;

    /**
    * Model object
    *
    * @var    UserModel
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

		$id = $this->getContainer()->get('app')->input->getUint('id');

		if (!$id)
		{
			// If no ID is given, use the ID of the current user.
			$id = $this->getContainer()->get('app')->getUser()->id;

			if (!$id)
			{
				throw new \UnexpectedValueException('No logged in user.');
			}
		}

		$this->view->id = (int) $id;

		$this->model->setProject($this->getContainer()->get('app')->getProject());
	}
}
