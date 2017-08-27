<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Make;

use Application\Command\TrackerCommandOption;

use Clue\GraphComposer\Graph\GraphComposer;

/**
 * Class for generating a graphical representation of the scripts that
 * have been installed using Composer and their dependencies.
 *
 * @since __DEPLOY_VERSION__
 */
class Composergraph extends Make
{
    /**
    * The GraphComposer object.
    *
    * @var    GraphComposer
    * @since __DEPLOY_VERSION__
    */
    protected $graph = null;

    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		parent::__construct();

		$this->description = g11n3t("Graph visualisation for your project's composer.json and its dependencies.");

		$this
			->addOption(
				new TrackerCommandOption(
					'file', 'f',
					g11n3t('Write output to a file.')
				)
			)
			->addOption(
				new TrackerCommandOption(
					'format', '',
					g11n3t('The image type.')
				)
			);
	}

    /**
    * Execute the command.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    */
    public function execute()
	{
		$this->getApplication()->outputTitle(g11n3t('Make Composer graph'));

		$this->graph = new GraphComposer(JPATH_ROOT);

		$input = $this->getApplication()->input;

		$filePath = $input->get('file', $input->get('f', '', 'raw'), 'raw');
		$format   = $input->get('format');

		if ($filePath)
		{
			$this->export($filePath, $format);
		}
		else
		{
			$this->show($format);
		}

		return $this;
	}

    /**
    * Generate the graph and open it in the default system application.
    *
    * @param   string  $format  The image type (defaults to svg).
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    */
    private function show($format = '')
	{
		$this->setFormat($format);

		$this->graph->displayGraph();

		return $this->out(g11n3t('The graph has been created.'));
	}

    /**
    * Generate the graph and export it to a file.
    *
    * @param   string  $filePath  Path to the file to receive the export or a directory.
    * @param   string  $format    The image type (defaults to svg).
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    */
    private function export($filePath, $format = '')
	{
		if (is_dir($filePath))
		{
			// If a directory is given, use a default file name.
			$filePath = rtrim($filePath, '/') . '/graph-composer.' . ($format ? : 'svg');
		}

		$filename = basename($filePath);

		$pos = strrpos($filename, '.');

		if ($pos !== false && isset($filename[$pos + 1]))
		{
			// Extension found and not empty.
			$this->setFormat(substr($filename, $pos + 1));
		}

		// The "--format" option from the command line overrides the file extension.
		$this->setFormat($format);

		$path = $this->graph->getImagePath();

		rename($path, $filePath);

		return $this->out(sprintf(g11n3t('The file has been written to %s'), '<fg=green>' . realpath($filePath) . '</fg=green>'));
	}

    /**
    * Set the image type for the graph.
    *
    * @param   string  $format  The image type.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    */
    private function setFormat($format = '')
	{
		if ($format)
		{
			$this->graph->setFormat($format);
			$this->debugOut(sprintf(g11n3t('Format has been set to %s'), '<b>' . $format . '</b>'));
		}

		return $this;
	}
}
