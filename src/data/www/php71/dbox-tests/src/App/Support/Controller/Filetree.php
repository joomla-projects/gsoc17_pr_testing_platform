<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Support\Controller;

use JTracker\Controller\AbstractTrackerController;

/**
 * Controller class for the developer documentation.
 *
 * @since __DEPLOY_VERSION__
 */
class Filetree extends AbstractTrackerController
{
    /**
    * Execute the controller.
    *
    * @return  string
    *
    * @since   __DEPLOY_VERSION__
    */
    public function execute()
	{
		// A full file system path.
		$path = $this->getContainer()->get('app')->input->get('dir', '', 'HTML');

		$docuBase = JPATH_ROOT . '/Documentation';

		$path = $path ? : $docuBase;

		// Dumb spoof check
		$path = str_replace('..', '', $path);

		$response = [];

		$files = scandir($path);

		natcasesort($files);

		if (count($files) > 2)
		{
			$response[] = '<ul class="jqueryFileTree" style="display: none;">';

			// All dirs
			foreach ($files as $file)
			{
				if ($file != '.' && $file != '..' && is_dir($path . '/' . $file))
				{
					// Dumb spoof check
					$file = str_replace('..', '', $file);

					$response[] = '<li class="directory collapsed">'
						. '<a href="javascript:;" rel="' . $path . '/' . $file . '">' . htmlentities($file) . '</a>'
						. '</li>';
				}
			}

			// All files
			foreach ($files as $file)
			{
				if (!is_dir($path . '/' . $file))
				{
					// Dumb spoof check
					$file = str_replace('..', '', $file);

					$subPath = trim(str_replace($docuBase, '', $path), '/');
					$page    = substr($file, 0, strrpos($file, '.'));
					$ext     = preg_replace('/^.*\./', '', $file);

					$fullPath = 'page=' . htmlentities($page) . ($subPath ? '&path=' . htmlentities($subPath) : '');

					$response[] = '<li class="file ext_' . $ext . '">'
						. '<a href="javascript:;" rel="' . $fullPath . '">'
						. $this->getTitle($file)
						. '</a>'
						. '</li>';
				}
			}

			$response[] = '</ul>';
		}

		return implode("\n", $response);
	}

    /**
    * Generate a nice title.
    *
    * @param   string  $file  A file name.
    *
    * @since   __DEPLOY_VERSION__
    * @return  string
    */
    private function getTitle($file)
	{
		$title = substr($file, 0, strrpos($file, '.'));

		$title = str_replace(['-', '_'], ' ', $title);

		$title = ucfirst($title);

		$title = htmlentities($title);

		return $title;
	}
}
