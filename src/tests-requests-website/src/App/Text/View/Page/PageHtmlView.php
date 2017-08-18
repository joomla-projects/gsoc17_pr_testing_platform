<?php
/**
 * Part of the Joomla Tracker's Text Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Text\View\Page;

use App\Text\Model\PageModel;

use JTracker\Router\Exception\RoutingException;
use JTracker\View\AbstractTrackerHtmlView;

/**
 * Page view class
 *
 * @since __DEPLOY_VERSION__
 */
class PageHtmlView extends AbstractTrackerHtmlView
{
    /**
    * Redefine the model so the correct type hinting is available.
    *
    * @var    PageModel
    * @since __DEPLOY_VERSION__
    */
    protected $model;

    /**
    * The page alias.
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
    * @throws  RoutingException
    */
    public function render()
	{
		try
		{
			$item = $this->model->getItem($this->getAlias());
		}
		catch (\RuntimeException $e)
		{
			throw new RoutingException($this->getAlias(), $e);
		}

		$this->addData('page', $item->getIterator());

		return parent::render();
	}

    /**
    * Get the page alias.
    *
    * @return  string
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \RuntimeException
    */
    public function getAlias()
	{
		if ('' == $this->alias)
		{
			throw new \RuntimeException('Alias not set.');
		}

		return $this->alias;
	}

    /**
    * Set the page alias.
    *
    * @param   string  $alias  The page alias.
    *
    * @return  $this  Method supports chaining
    *
    * @since   __DEPLOY_VERSION__
    */
    public function setAlias($alias)
	{
		$this->alias = $alias;

		return $this;
	}
}
