<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Instances\View\Instance;

use JTracker\View\AbstractTrackerHtmlView;

/**
 * The instances item view
 *
 * @since __DEPLOY_VERSION__
 */
class InstanceHtmlView extends AbstractTrackerHtmlView
{
    /**
    * The model object.
    *
    * @var    \App\Instances\Model\InstanceModel
    * @since __DEPLOY_VERSION__
    */
    protected $model;

    /**
    * JoomlaInstance ID
    *
    * @var    integer
    * @since __DEPLOY_VERSION__
    */
    protected $instance_id = 0;


    /**
    * Get the instance ID.
    *
    * @return  integer
    *
    * @since   __DEPLOY_VERSION__
    */
    public function getInstanceID()
	{
		if ('' == $this->instance_id)
		{
			// New record.
		}

		return $this->instance_id;
	}

    /**
    * Set the instance ID.
    *
    * @param   string  $instance_id  The instance ID.
    *
    * @return  $this  Method allows chaining
    *
    * @since   __DEPLOY_VERSION__
    */
    public function setInstanceID($instance_id)
	{
		$this->instance_id = $instance_id;

		return $this;
	}

    /**
    * Method to render the view.
    *
    * @return  string  The rendered view.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function render()
	{
		$this->addData('instance', $this->model->getByInstanceID($this->getInstanceID()));

		return parent::render();
	}

}
