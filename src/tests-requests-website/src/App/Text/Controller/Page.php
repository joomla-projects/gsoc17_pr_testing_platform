<?php
/**
 * Part of the Joomla Tracker's Text Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Text\Controller;

use App\Text\View\Page\PageHtmlView;

use JTracker\Controller\AbstractTrackerController;

/**
 * Controller class for the Text component
 *
 * @since __DEPLOY_VERSION__
 */
class Page extends AbstractTrackerController
{
    /**
    * View object
    *
    * @var    PageHtmlView
    * @since __DEPLOY_VERSION__
    */
    protected $view = null;

    /**
    * Initialize the controller.
    *
    * This will set up default model and view classes.
    *
    * @return  $this  Method supports chaining
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \RuntimeException
    */
    public function initialize()
	{
		parent::initialize();

		$this->view->setAlias($this->getContainer()->get('app')->input->getCmd('alias'));

		return $this;
	}
}
