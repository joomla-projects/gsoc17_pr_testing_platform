<?php
/**
 * Part of the Joomla Tracker's Activity Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Activity\View;

use JTracker\View\AbstractTrackerHtmlView;

/**
 * The activity chart view
 *
 * @since __DEPLOY_VERSION__
 */
class DefaultHtmlView extends AbstractTrackerHtmlView
{
    /**
    * Method to render the view.
    *
    * @return  string  The rendered view.
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \RuntimeException
    */
    public function render()
	{
		// Set the vars to the template.
		$this->addData('project', $this->getProject());

		return parent::render();
	}
}
