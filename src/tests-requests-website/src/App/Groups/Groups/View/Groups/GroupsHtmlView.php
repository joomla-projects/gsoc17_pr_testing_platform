<?php
/**
 * Part of the Joomla Tracker's Groups Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Groups\View\Groups;

use App\Groups\Model\GroupsModel;

use JTracker\View\AbstractTrackerHtmlView;

/**
 * The groups list view
 *
 * @since __DEPLOY_VERSION__
 */
class GroupsHtmlView extends AbstractTrackerHtmlView
{
    /**
    * Redefine the model so the correct type hinting is available.
    *
    * @var    GroupsModel
    * @since __DEPLOY_VERSION__
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
		$this->addData('items', $this->model->getItems());
		$this->addData('project', $this->getProject());

		return parent::render();
	}
}
