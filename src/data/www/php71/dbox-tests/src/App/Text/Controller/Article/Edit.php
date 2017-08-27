<?php
/**
 * Part of the Joomla Tracker's Text Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Text\Controller\Article;

use App\Text\Model\ArticleModel;
use App\Text\View\Article\ArticleHtmlView;

use JTracker\Controller\AbstractTrackerController;

/**
 * Controller class to edit an article.
 *
 * @since __DEPLOY_VERSION__
 */
class Edit extends AbstractTrackerController
{
    /**
    * The default view for the component
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $defaultView = 'article';

    /**
    * The default view for the component
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $defaultLayout = 'edit';

    /**
    * View object
    *
    * @var    ArticleHtmlView
    * @since __DEPLOY_VERSION__
    */
    protected $view;

    /**
    * Model object
    *
    * @var    ArticleModel
    * @since __DEPLOY_VERSION__
    */
    protected $model;

    /**
    * Initialize the controller.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    */
    public function initialize()
	{
		parent::initialize();

		$this->getContainer()->get('app')->getUser()->authorize('admin');

		$this->view->setItem($this->model->getItem($this->getContainer()->get('app')->input->getInt('id')));

		return $this;
	}
}
