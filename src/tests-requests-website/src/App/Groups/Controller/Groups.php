<?php
/**
 * Part of the Joomla Tracker's Groups Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Groups\Controller;

use App\Groups\Model\GroupsModel;
use App\Groups\View\Groups\GroupsHtmlView;

use JTracker\Controller\AbstractTrackerController;

/**
 * Controller class to manage the application usergroups.
 *
 * @since __DEPLOY_VERSION__
 */
class Groups extends AbstractTrackerController
{
    /**
    * Model object
    *
    * @var    GroupsModel
    * @since __DEPLOY_VERSION__
    */
    protected $model;

    /**
    * View object
    *
    * @var    GroupsHtmlView
    * @since __DEPLOY_VERSION__
    */
    protected $view;

    /**
    * Initialize the controller.
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
}
