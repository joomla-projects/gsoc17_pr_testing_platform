<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Tracker\View\Categories;

use JTracker\View\AbstractTrackerHtmlView;
use App\Tracker\Model\CategoriesModel;

/**
 * The category list view
 *
 * @since __DEPLOY_VERSION__
 */
class CategoriesHtmlView extends AbstractTrackerHtmlView
{
    /**
    * Redefine the model so the correct type hinting is available.
    *
    * @var     CategoriesModel
    * @since   __DEPLOY_VERSION__
    */
    protected $model;

    /**
    * Method to render the view.
    *
    * @return  string  The rendered view.
    *
    * @throws  \RuntimeException
    * @since   __DEPLOY_VERSION__
    */
    public function render()
	{
		// Set the vars to the template.
		$this->addData('items', $this->model->getItems());
		$this->addData('pagination', $this->model->getPagination());
		$this->addData('state', $this->model->getState());
		$this->addData('project', $this->getProject());

		return parent::render();
	}
}
