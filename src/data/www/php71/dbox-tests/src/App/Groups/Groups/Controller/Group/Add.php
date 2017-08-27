<?php
/**
 * Part of the Joomla Tracker's Groups Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Groups\Controller\Group;

use App\Groups\View\Group\GroupHtmlView;

use JTracker\Controller\AbstractTrackerController;

/**
 * Controller class to add a group.
 *
 * @since __DEPLOY_VERSION__
 */
class Add extends AbstractTrackerController
{
    /**
    * The default view for the app.
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $defaultView = 'group';

    /**
    * The default layout for the app.
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $defaultLayout = 'edit';

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

		$this->view->setProject($this->getContainer()->get('app')->getProject());

		return $this;
	}
}
