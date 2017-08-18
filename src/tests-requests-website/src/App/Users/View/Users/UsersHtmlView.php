<?php
/**
 * Part of the Joomla Tracker's Users Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Users\View\Users;

use App\Users\Model\UsersModel;

use JTracker\View\AbstractTrackerHtmlView;

/**
 * Users view class for the Users component
 *
 * @since __DEPLOY_VERSION__
 */
class UsersHtmlView extends AbstractTrackerHtmlView
{
    /**
    * The model object.
    *
    * @var    UsersModel
    * @since __DEPLOY_VERSION__
    */
    protected $model;

    /**
    * Method to render the view.
    *
    * @return  string  The rendered view.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function render()
	{
		$this->addData('items', $this->model->getItems());
		$this->addData('pagination', $this->model->getPagination());
		$this->addData('state', $this->model->getState());

		return parent::render();
	}
}
