<?php
/**
 * Part of the Joomla Tracker's Tracker Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Tracker\Controller\Upload\Ajax;

use JTracker\Controller\AbstractAjaxController;

/**
 * Delete file controller class.
 *
 * @since __DEPLOY_VERSION__
 */
class Delete extends AbstractAjaxController
{
    /**
    * Prepare the response.
    *
    * @return  mixed
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \RuntimeException
    */
    protected function prepareResponse()
	{
		/* @type \JTracker\Application $application */
		$application = $this->getContainer()->get('app');

		$file = $application->input->getCmd('file');

		if (!empty($file))
		{
			try
			{
				unlink(JPATH_THEMES . '/' . $application->get('system.upload_dir') . '/' . $application->getProject()->project_id . '/' . $file);
			}
			catch (\Exception $e)
			{
				throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
			}
		}
	}
}
