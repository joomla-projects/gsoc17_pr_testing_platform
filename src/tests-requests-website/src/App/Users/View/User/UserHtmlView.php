<?php
/**
 * Part of the Joomla Tracker's Users Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Users\View\User;

use App\Users\Model\UserModel;

use JTracker\View\AbstractTrackerHtmlView;

/**
 * User view class for the Users component
 *
 * @since __DEPLOY_VERSION__
 */
class UserHtmlView extends AbstractTrackerHtmlView
{
    /**
    * The model object.
    *
    * @var    UserModel
    * @since __DEPLOY_VERSION__
    */
    protected $model;

    /**
    * Item ID
    *
    * @var    integer
    * @since __DEPLOY_VERSION__
    */
    public $id = 0;

    /**
    * Method to render the view.
    *
    * @return  string  The rendered view.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function render()
	{
		$item = $this->model->getItem($this->id);

		$this->addData('item', $item)
			->addData('tz_offset', (new \DateTimeZone($item->params->get('timezone', 'UTC')))->getOffset(new \DateTime) / 3600);

		return parent::render();
	}
}
