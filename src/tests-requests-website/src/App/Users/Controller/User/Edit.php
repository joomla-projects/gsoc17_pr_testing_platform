<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Users\Controller\User;

use App\Users\Model\UserModel;
use App\Users\View\User\UserHtmlView;
use JTracker\Controller\AbstractTrackerController;

/**
 * Edit controller class for the users component
 *
 * @since __DEPLOY_VERSION__
 */
class Edit extends AbstractTrackerController
{
    /**
    * The default view for the component
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $defaultView = 'user';

    /**
    * The default view for the component
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $defaultLayout = 'edit';

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
    * @throws  \UnexpectedValueException
    */
    public function initialize()
	{
		parent::initialize();

		/* @type \JTracker\Application $application */
		$application = $this->getContainer()->get('app');

		$id = $application->input->getUint('id');

		if (!$id)
		{
			throw new \UnexpectedValueException('No id given', 404);
		}

		if (!$application->getUser()->check('admin'))
		{
			if ($application->getUser()->id != $id)
			{
				$application->enqueueMessage(
					g11n3t('You are not authorised to edit this user.'), 'error'
				);

				$application->redirect(
					$application->get('uri.base.path') . 'users'
				);
			}
		}

		$this->view->id = $id;

		$this->model->setProject($this->getContainer()->get('app')->getProject());
	}
}
