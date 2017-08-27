<?php
/**
 * Part of the Joomla Tracker's Text Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Text\View\Articles;

use App\Text\Model\ArticlesModel;
use JTracker\View\AbstractTrackerHtmlView;

/**
 * Articles view class
 *
 * @since __DEPLOY_VERSION__
 */
class ArticlesHtmlView extends AbstractTrackerHtmlView
{
    /**
    * Redefine the model so the correct type hinting is available.
    *
    * @var    ArticlesModel
    * @since __DEPLOY_VERSION__
    */
    protected $model;

    /**
    * Method to render the view.
    *
    * @return  string  The rendered view.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function render()
	{
		$this->addData('items', $this->model->getItems());

		return parent::render();
	}
}
