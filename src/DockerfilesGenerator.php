<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\PRTestingPlatform;

use Joomla\Github\Github;

/**
 * Class DockerImagesGenerator
 *
 * @package  Joomla\PRTestingPlatform
 * @since    __DEPLOY_VERSION__
 */

class DockerfilesGenerator
{
	/**
	 * @var string
	 */
	protected $phpVersionsFile;

	/**
	 * DockerImagesGenerator constructor.
	 *
	 * @param   string  $phpVersionsFile  Name of the xml file with the supported php versions
	 */
	public function __construct($phpVersionsFile)
	{
		$this->phpVersionsFile = $phpVersionsFile;
	}

	/**
	 * Gets the supported PHP versions
	 *
	 * @return  string[] Array with PHP versions
	 */
	private function getPhpVersions()
	{
		$phpVersions = [];

		$file = dirname($this->phpVersionsFile) . '/' . $this->phpVersionsFile;

		$xml = simplexml_load_file($file);

		foreach ($xml->children() as $child)
		{
			foreach ($child->attributes() as $key => $attribute)
			{
				array_push($phpVersions, (string) $attribute);
			}
		}

		return $phpVersions;
	}

	/**
	 * Gets all existent Joomla! CMS branches
	 *
	 * @return  string[] Array with Joomla! CMS branches
	 */
	private function getJoomlaCMSBranches()
	{
		$github = new Github;

		$repoBranches = $github->repositories->branches->getList("joomla", "joomla-cms");

		$branches = [];

		foreach ($repoBranches as $branch)
		{
			array_push($branches, ((array) $branch)['name']);
		}

		return $branches;
	}

	/**
	 * Generates the dockerfiles given the permutation of all
	 * existent Joomla! CMS branches and php versions
	 *
	 * @return  void
	 */
	public function generateDockerfiles()
	{
		$phpVersions = $this->getPhpVersions();
		$branches = $this->getJoomlaCMSBranches();

		$dir = getcwd() . '/../Dockerfiles/';

		if (!is_dir($dir))
		{
			mkdir($dir);
		}

		foreach ($branches as $branch)
		{
			foreach ($phpVersions as $phpVersion)
			{
				$dockerfile = file_get_contents($dir . "SampleDockerfile");
				$dockerfile = str_replace("{PHPVERSION}", $phpVersion, $dockerfile);
				$dockerfile = str_replace("{BRANCH}", $branch, $dockerfile);

				$subdir = $dir . $branch . "-" . "php" . $phpVersion . "/";

				if (!is_dir($subdir))
				{
					mkdir($subdir);
				}

				file_put_contents($subdir . "Dockerfile", $dockerfile);
			}
		}
	}
}
