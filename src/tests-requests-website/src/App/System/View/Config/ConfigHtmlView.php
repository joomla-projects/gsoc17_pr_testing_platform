<?php
/**
 * Part of the Joomla Tracker's Support Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\System\View\Config;

use JTracker\View\AbstractTrackerHtmlView;

/**
 * System configuration view.
 *
 * @since __DEPLOY_VERSION__
 */
class ConfigHtmlView extends AbstractTrackerHtmlView
{
    /**
    * Method to render the view.
    *
    * @return  string  The rendered view.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function render()
	{
		$type = trim(getenv('JTRACKER_ENVIRONMENT'));

		$fileName = ($type) ? 'config.' . $type . '.json' : 'config.json';

		$config = json_decode(file_get_contents(JPATH_CONFIGURATION . '/' . $fileName), true);

		$this->addData('config', $config);
		$this->addData('configFile', $fileName);

		return parent::render();
	}
}
