<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Support\Controller\Ajax\Documentation;

use App\Support\Model\DefaultModel;

use JTracker\Controller\AbstractAjaxController;

/**
 * Controller class to view documentation pages.
 *
 * @since __DEPLOY_VERSION__
 */
class Show extends AbstractAjaxController
{
    /**
    * Prepare the response.
    *
    * @return  void
    *
    * @since   __DEPLOY_VERSION__
    */
    protected function prepareResponse()
	{
		ob_start();

		/* @type $input \Joomla\Input\Input */
		$input = $this->getContainer()->get('app')->input;

		$page = $input->get('page');
		$path = $input->getPath('path');

		$base = $this->getContainer()->get('app')->get('uri')->base->path;

		$this->response->editLink = 'https://github.com/joomla/jissues/edit/master/Documentation/' . ($path ? $path . '/' : '') . $page . '.md';
		$this->response->permaLink = $base . 'documentation/view/?page=' . $page . ($path ? '&path=' . $path : '');

		$data = (new DefaultModel($this->getContainer()->get('db')))->getItem($page, $path)->text;

		$err = ob_get_clean();

		if ($err)
		{
			$this->response->error = $err;
		}
		else
		{
			$this->response->data = $data;
		}

		return;
	}
}
