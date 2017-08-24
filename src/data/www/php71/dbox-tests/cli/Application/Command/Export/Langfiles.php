<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Export;

use Application\Command\TrackerCommandOption;

use ElKuKu\G11n\Language\Storage as g11nStorage;
use ElKuKu\G11n\Support\ExtensionHelper as g11nExtensionHelper;

use JTracker\Helper\LanguageHelper;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

/**
 * Class for retrieving avatars from GitHub for selected projects.
 *
 * @since __DEPLOY_VERSION__
 */
class Langfiles extends Export
{
    /**
    * List of supported languages.
    *
    * @var    array
    * @since __DEPLOY_VERSION__
    */
    private $languages = [];

    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		parent::__construct();

		$this->description = g11n3t('Backup language files to a given folder.');

		$this->addOption(
			new TrackerCommandOption(
				'templates', '',
				g11n3t('Export also language template files.')
			)
		);
	}

    /**
    * Set up the environment to run the command.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    */
    protected function setup()
	{
		parent::setup();

		$this->languages = LanguageHelper::getLanguageCodes();

		return $this;
	}

    /**
    * Execute the command.
    *
    * @throws \RuntimeException
    * @return  void
    *
    * @since   __DEPLOY_VERSION__
    */
    public function execute()
	{
		$this->getApplication()->outputTitle(g11n3t('Export language files'));

		$this->setup()
			->logOut(g11n3t('Start exporting language files.'))
			->exportFiles()
			->out()
			->logOut(g11n3t('Finished.'));
	}

    /**
    * Create list of files to export.
    *
    * @return $this
    *
    * @since   __DEPLOY_VERSION__
    */
    private function exportFiles()
	{
		LanguageHelper::addDomainPaths();

		$templates = (boolean) $this->getOption('templates');

		foreach (LanguageHelper::getScopes() as $domain => $extensions)
		{
			foreach ($extensions as $extension)
			{
				$this->processDomain($extension, $domain, $templates);
			}
		}

		return $this;
	}

    /**
    * Process language files for a domain.
    *
    * @param   string   $extension  Extension name.
    * @param   string   $domain     Extension domain.
    * @param   boolean  $templates  If templates should be exported.
    *
    * @throws \DomainException
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    */
    protected function processDomain($extension, $domain, $templates)
	{
		$domainBase = trim(str_replace(JPATH_ROOT, '', g11nExtensionHelper::getDomainPath($domain)), '/');
		$g11nPath = g11nExtensionHelper::$langDirName;

		$filesystem = new Filesystem(new Local($this->exportDir . '/' . $domainBase . '/' . $extension . '/' . $g11nPath));

		$this->out(g11n3t('Processing %domain% %extension%... ', ['%domain%' => $domain, '%extension%' => $extension]), false);

		// Process language templates
		if ($templates)
		{
			$this->out('templates... ', false);

			$path = g11nStorage::getTemplatePath($extension, $domain);

			$contents = (new Filesystem(new Local(dirname($path))))
				->read(basename($path));

			if (false === $filesystem->put('templates/' . basename($path), $contents))
			{
				throw new \DomainException('Can not write the file at: ' . $path);
			}
		}

		// Process language files
		foreach ($this->languages as $lang)
		{
			if ('en-GB' == $lang)
			{
				continue;
			}

			$this->out($lang . '... ', false);

			$path = g11nExtensionHelper::findLanguageFile($lang, $extension, $domain);

			if (!$path)
			{
				$this->out('<error> ' . $lang . ' NOT FOUND </error>... ', false);

				continue;
			}

			$contents = (new Filesystem(new Local(dirname($path))))
				->read(basename($path));

			if (false === $filesystem->put($lang . '/' . basename($path), $contents))
			{
				throw new \DomainException('Can not write the file: ' . basename($path));
			}
		}

		$this->outOK();

		return $this;
	}
}
