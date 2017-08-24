<?php
/**
 * Part of the Joomla Tracker's Debug Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Debug\Handler;

use \Whoops\Handler\Handler;

/**
 * Catches the Whoops! and simply displays the message.
 *
 * @since __DEPLOY_VERSION__
 */
class ProductionHandler extends Handler
{
    /**
    * Handle the Whoops!
    *
    * @return  integer
    *
    * @since   __DEPLOY_VERSION__
    */
    public function handle()
	{
		echo $this->getException()->getMessage();

		return Handler::QUIT;
	}
}
