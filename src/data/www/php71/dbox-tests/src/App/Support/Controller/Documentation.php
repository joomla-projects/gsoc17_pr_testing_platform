<?php
/**
 * Part of the Joomla Tracker's Support Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Support\Controller;

use App\Support\View\Documentation\DocumentationHtmlView;

use JTracker\Controller\AbstractTrackerController;

/**
 * Controller class for the developer documentation.
 *
 * @since __DEPLOY_VERSION__
 */
class Documentation extends AbstractTrackerController
{
    /**
    * Initialize the controller.
    *
    * @return  $this  Method supports chaining
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \RuntimeException
    */
    public function initialize()
	{
		parent::initialize();

		/* @type $input \Joomla\Input\Input */
		$input = $this->getContainer()->get('app')->input;

		$path = $input->getPath('path');
		$page = $input->getCmd('page');

		if ($page)
		{
			$fullPath = 'page=' . $page . ($path ? '&path=' . $path : '');

			$this->view->addData('fullPath', $fullPath);
		}
	}
}
