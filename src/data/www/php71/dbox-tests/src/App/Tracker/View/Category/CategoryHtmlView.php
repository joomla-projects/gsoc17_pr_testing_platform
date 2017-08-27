<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Tracker\View\Category;

use JTracker\View\AbstractTrackerHtmlView;
use App\Tracker\Model\CategoryModel;
use App\Tracker\Table\CategoryTable;

/**
 * The category view
 *
 * @since __DEPLOY_VERSION__
 */
class CategoryHtmlView extends AbstractTrackerHtmlView
{
    /**
    * Redefine the model so the correct type hinting is available.
    *
    * @var     CategoryModel
    * @since   __DEPLOY_VERSION__
    */
    protected $model;

    /**
    * Item object
    *
    * @var CategoryTable
    *
    * @since 1.0
    */
    protected $item = null;

    /**
    * Set the item
    *
    * @param   CategoryTable  $item  The item to set
    *
    * @return  $this    Method allows chaining
    *
    * @since __DEPLOY_VERSION__
    */
    public function setItem($item)
	{
		$this->item = $item;

		return $this;
	}

    /**
    * Get the item
    *
    * @throws \RuntimeException
    *
    * @return CategoryTable
    *
    * @since __DEPLOY_VERSION__
    */
    public function getItem()
	{
		if (is_null($this->item))
		{
			throw new \RuntimeException('Item not set.');
		}

		return $this->item;
	}

    /**
    * Method to render the view.
    *
    * @return  string  The rendered view.
    *
    * @throws  \RuntimeException
    *
    * @since __DEPLOY_VERSION__
    */
    public function render()
	{
		// Set the vars to the template.
		$this->addData('state', $this->model->getState());
		$this->addData('project', $this->getProject());
		$this->addData('item', $this->getItem());

		return parent::render();
	}
}
