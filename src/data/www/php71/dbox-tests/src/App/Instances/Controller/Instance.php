<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Instances\Controller;

use App\Instances\View\Instance\InstanceHtmlView;

use JTracker\Controller\AbstractTrackerController;

/**
 * Controller class for the joomla instance view
 *
 * @since __DEPLOY_VERSION__
 */
class Instance extends AbstractTrackerController
{
    /**
    * View object
    *
    * @var    InstanceHtmlView
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
		// Reload the project.
		$this->getContainer()->get('app')->getProject(true);

		parent::initialize();

		$this->view->setInstanceID($this->getContainer()->get('app')->input->get('instance_id'));

		return $this;
	}
}
