<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Projects\View\Project;

use JTracker\View\AbstractTrackerHtmlView;

/**
 * The projects item view
 *
 * @since __DEPLOY_VERSION__
 */
class ProjectHtmlView extends AbstractTrackerHtmlView
{
    /**
    * The model object.
    *
    * @var    \App\Projects\Model\ProjectModel
    * @since __DEPLOY_VERSION__
    */
    protected $model;

    /**
    * Project alias
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $alias = '';

    /**
    * Method to render the view.
    *
    * @return  string  The rendered view.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function render()
	{
		$this->addData('project', $this->model->getByAlias($this->getAlias()));

		return parent::render();
	}

    /**
    * Get the alias.
    *
    * @return  string
    *
    * @since   __DEPLOY_VERSION__
    */
    public function getAlias()
	{
		if ('' == $this->alias)
		{
			// New record.
		}

		return $this->alias;
	}

    /**
    * Set the alias.
    *
    * @param   string  $alias  The alias.
    *
    * @return  $this  Method allows chaining
    *
    * @since   __DEPLOY_VERSION__
    */
    public function setAlias($alias)
	{
		$this->alias = $alias;

		return $this;
	}
}
