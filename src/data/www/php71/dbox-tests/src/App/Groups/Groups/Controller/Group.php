<?php
/**
 * Part of the Joomla Tracker's Groups Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Groups\Controller;

use App\Groups\Model\GroupModel;
use App\Groups\View\Group\GroupHtmlView;

use JTracker\Controller\AbstractTrackerController;

/**
 * Controller class to manage a user group.
 *
 * @since __DEPLOY_VERSION__
 */
class Group extends AbstractTrackerController
{
    /**
    * The default layout for the app
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $defaultLayout = 'edit';

    /**
    * Model object
    *
    * @var    GroupModel
    * @since __DEPLOY_VERSION__
    */
    protected $model;

    /**
    * View object
    *
    * @var    GroupHtmlView
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
		$this->model->setGroupId($this->getContainer()->get('app')->input->getInt('group_id'));

		$this->view->setProject($this->getContainer()->get('app')->getProject());

		return $this;
	}
}
