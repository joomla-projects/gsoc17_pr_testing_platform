<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Activity\Controller\Ajax;

use App\Activity\Model\SnapshotModel;

use JTracker\Controller\AbstractAjaxController;

/**
 * Controller class to handle AJAX requests for the activity snapshot data
 *
 * @property-read   SnapshotModel  $model  Model object
 *
 * @since __DEPLOY_VERSION__
 */
class ActivitySnapshot extends AbstractAjaxController
{
    /**
    * Initialize the controller.
    *
    * This will set up default model and view classes.
    *
    * @return  $this  Method allows chiaining
    *
    * @since   __DEPLOY_VERSION__
    */
    public function initialize()
	{
		$application = $this->getContainer()->get('app');

		// Setup the model to query our data
		$this->model = new SnapshotModel($this->getContainer()->get('db'));
		$this->model->setProject($application->getProject());

		return $this;
	}

    /**
    * Prepare the response.
    *
    * @return  void
    *
    * @since   __DEPLOY_VERSION__
    */
    protected function prepareResponse()
	{
		$statusLabels = [
			1 => g11n3t('New'),
			2 => g11n3t('Confirmed'),
			3 => g11n3t('Pending'),
			4 => g11n3t('Ready To Commit'),
			6 => g11n3t('Needs Review'),
			7 => g11n3t('Information Required'),
			14 => g11n3t('Discussion'),
		];

		$iterator = $this->model->getOpenIssues();

		$title = g11n3t('Total Open Issues By Status');

		$ticks  = [];
		$counts = [];

		foreach ($iterator as $item)
		{
			// Create the status' container if it hasn't already been
			if (!isset($counts[$statusLabels[$item->status]]))
			{
				$counts[$statusLabels[$item->status]] = [0];
			}

			$counts[$statusLabels[$item->status]][0]++;
		}

		$dataByStatus = array_values($counts);
		$ticks        = array_keys($counts);
		$labels       = [];

		foreach ($ticks as $type)
		{
			$object        = new \stdClass;
			$object->label = $type;
			$labels[]      = $object;
		}

		$data         = [];
		$internalData = [];

		for ($i = 0; $i < count($counts); $i++)
		{
			$internalData[] = 0;
		}

		foreach ($dataByStatus as $key => $dataForOneStatus)
		{
			$row       = $internalData;
			$row[$key] = $dataForOneStatus[0];
			$data[]    = $row;
		}

		// Setup the response data
		$this->response->data = [$data, $ticks, $labels, $title];
	}
}
