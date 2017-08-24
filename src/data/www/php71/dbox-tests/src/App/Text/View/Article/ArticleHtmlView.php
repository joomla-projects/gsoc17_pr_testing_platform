<?php
/**
 * Part of the Joomla Tracker's Text Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Text\View\Article;

use App\Text\Model\ArticleModel;
use App\Text\Table\ArticlesTable;

use JTracker\View\AbstractTrackerHtmlView;

/**
 * Article view class
 *
 * @since __DEPLOY_VERSION__
 */
class ArticleHtmlView extends AbstractTrackerHtmlView
{
    /**
    * Redefine the model so the correct type hinting is available.
    *
    * @var     ArticleModel
    * @since   __DEPLOY_VERSION__
    */
    protected $model;

    /**
    * Table object with article data
    *
    * @var    ArticlesTable
    * @since __DEPLOY_VERSION__
    */
    protected $item = null;

    /**
    * Method to render the view.
    *
    * @return  string  The rendered view.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function render()
	{
		$this->addData('item', $this->getItem());

		return parent::render();
	}

    /**
    * Get the item.
    *
    * @return  ArticlesTable
    *
    * @since   __DEPLOY_VERSION__
    */
    public function getItem()
	{
		return $this->item;
	}

    /**
    * Set the item.
    *
    * @param   ArticlesTable  $item  The item.
    *
    * @return  $this  Method supports chaining
    *
    * @since   __DEPLOY_VERSION__
    */
    public function setItem($item)
	{
		$this->item = $item;

		return $this;
	}
}
