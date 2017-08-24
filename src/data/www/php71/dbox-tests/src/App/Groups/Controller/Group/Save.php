<?php
/**
 * Part of the Joomla Tracker's Groups Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Groups\Controller\Group;

use App\Groups\Model\GroupModel;
use App\Groups\Table\GroupsTable;

use App\Groups\View\Group\GroupHtmlView;
use JTracker\Controller\AbstractTrackerController;

/**
 * Controller class to save a group.
 *
 * @since __DEPLOY_VERSION__
 */
class Save extends AbstractTrackerController
{
    /**
    * The default view for the app.
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $defaultView = 'groups';

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
    * @return  $this  Method allows chaining
    *
    * @since   __DEPLOY_VERSION__
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
    * @return  string  The rendered view.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function execute()
	{
		$group = $this->getContainer()->get('app')->input->get('group', [], 'array');

		(new GroupsTable($this->getContainer()->get('db')))
			->save($group);

		return parent::execute();
	}
}
