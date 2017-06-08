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
	public function generateDockerfiles(){
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
				$dockerfile = "FROM php:" . $phpVersion . "-apache\n\n";
				$dockerfile .= "# Enable Apache Rewrite Module\n";
				$dockerfile .= "RUN a2enmod rewrite\n\n";
				$dockerfile .= "# Install PHP extensions\n";
				$dockerfile .= "RUN apt-get update ; ";
				$dockerfile .= "apt-get install -y libpng12-dev libjpeg-dev libmcrypt-dev zip unzip git;";
				$dockerfile .= " \\ rm -rf /var/lib/apt/lists/* ;\\\n";
				$dockerfile .= "\tdocker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr; \\";
				$dockerfile .= "\n\tdocker-php-ext-install gd\n\n";
				$dockerfile .= "VOLUME /var/www/html\n\n";
				$dockerfile .= "# Download package and extract to web volume\n";
				$dockerfile .= "RUN mkdir /usr/src/joomla; \\\n";
				$dockerfile .= "\tgit clone --depth 1 -b " . $branch . " --single-branch ";
				$dockerfile .= "https://github.com/joomla/joomla-cms.git /usr/src/joomla ; \\\n";
				$dockerfile .= "\tchown -R www-data:www-data /usr/src/joomla\n\n";
				$dockerfile .= "# Copy init scripts and custom .htaccess\n";
				$dockerfile .= "COPY docker-entrypoint.sh /entrypoint.sh\n";
				$dockerfile .= "COPY makedb.php /makedb.php\n\n";
				$dockerfile .= "RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf\n\n";
				$dockerfile .= "ENTRYPOINT [\"/entrypoint.sh\"]\n";
				$dockerfile .= "CMD [\"apache2-foreground\"]";

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
