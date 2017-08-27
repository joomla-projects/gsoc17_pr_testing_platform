<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Instances\View\Instances;

use App\Instances\Model\InstancesModel;
use JTracker\View\AbstractTrackerHtmlView;

/**
 * The Instances list view
 *
 * @since __DEPLOY_VERSION__
 */
class InstancesHtmlView extends AbstractTrackerHtmlView
{
    /**
    * The model object.
    *
    * @var    InstancesModel
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
		$this->addData('instances', $this->model->getItems());

		return parent::render();
	}
}
