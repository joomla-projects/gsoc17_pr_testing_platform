<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Tracker\View\Issue;

use App\Tracker\Model\IssueModel;
use App\Tracker\Table\IssuesTable;

use JTracker\View\AbstractTrackerHtmlView;

/**
 * The issues item view
 *
 * @since __DEPLOY_VERSION__
 */
class IssueHtmlView extends AbstractTrackerHtmlView
{
    /**
    * Redefine the model so the correct type hinting is available.
    *
    * @var     IssueModel
    * @since   __DEPLOY_VERSION__
    */
    protected $model;

    /**
    * Item object
    *
    * @var    IssuesTable
    * @since __DEPLOY_VERSION__
    */
    protected $item = null;

    /**
    * If the user has "edit own" rights.
    *
    * @var    boolean
    * @since __DEPLOY_VERSION__
    */
    protected $editOwn = false;

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
		$this->addData('item', $this->getItem());
		$this->addData('project', $this->getProject());
		$this->addData('statuses', $this->model->getStatuses());
		$this->addData('canEditOwn', $this->canEditOwn());

		return parent::render();
	}

    /**
    * Get the item.
    *
    * @throws \RuntimeException
    * @return IssuesTable
    *
    * @since   __DEPLOY_VERSION__
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
    * Set the item.
    *
    * @param   IssuesTable  $item  The item to set.
    *
    * @return $this
    *
    * @since   __DEPLOY_VERSION__
    */
    public function setItem($item)
	{
		$this->item = $item;

		return $this;
	}

    /**
    * Check if the user is allowed to edit her own issues.
    *
    * @return  boolean
    *
    * @since   __DEPLOY_VERSION__
    */
    public function canEditOwn()
	{
		return $this->editOwn;
	}

    /**
    * Set if the user is allowed to edit her own issues.
    *
    * @param   boolean  $editOwn  If the user is allowed.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    */
    public function setEditOwn($editOwn)
	{
		$this->editOwn = (bool) $editOwn;

		return $this;
	}
}
