<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Tracker\View\Issues;

use App\Tracker\Model\IssuesModel;

use JTracker\View\AbstractTrackerHtmlView;

/**
 * The issues list view
 *
 * @since __DEPLOY_VERSION__
 */
class IssuesHtmlView extends AbstractTrackerHtmlView
{
    /**
    * Redefine the model so the correct type hinting is available.
    *
    * @var     IssuesModel
    * @since   __DEPLOY_VERSION__
    */
    protected $model;

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
		$this->setData(
			[
				'state'   => $this->model->getState(),
				'project' => $this->getProject(),
			]
		);

		return parent::render();
	}
}
