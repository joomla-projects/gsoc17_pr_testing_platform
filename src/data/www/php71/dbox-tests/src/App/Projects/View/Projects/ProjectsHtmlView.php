<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Projects\View\Projects;

use App\Projects\Model\ProjectsModel;
use JTracker\View\AbstractTrackerHtmlView;

/**
 * The projects list view
 *
 * @since __DEPLOY_VERSION__
 */
class ProjectsHtmlView extends AbstractTrackerHtmlView
{
    /**
    * The model object.
    *
    * @var    ProjectsModel
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
		$this->addData('projects', $this->model->getItems());

		return parent::render();
	}
}
