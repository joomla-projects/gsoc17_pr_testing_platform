<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Update;

use Application\Command\TrackerCommandOption;

use ElKuKu\Crowdin\Languagefile;

use ElKuKu\G11n\Language\Storage;
use ElKuKu\G11n\Support\ExtensionHelper;

use Joomla\Filter\OutputFilter;

use JTracker\Helper\LanguageHelper;

/**
 * Class for updating resources on a translation service.
 *
 * @since __DEPLOY_VERSION__
 */
class Languagefiles extends Update
{
    /**
    * Array containing application languages.
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

		$this->description = g11n3t('Updates language files on a translation service.');

		$this->addOption(
			new TrackerCommandOption(
				'provider', '',
				g11n3t('The translation service provider to use.')
			)
		)
			->addOption(
				new TrackerCommandOption(
					'create', '',
					g11n3t('Flag if resources should be created.')
				)
			)
			->addOption(
				new TrackerCommandOption(
					'translations', '',
					g11n3t('Flag if translations should be uploaded.')
				)
			);
	}

    /**
    * Execute the command.
    *
    * @return  void
    *
    * @since   __DEPLOY_VERSION__
    */
    public function execute()
	{
		$this->getApplication()->outputTitle(g11n3t('Update Translations'));

		$this->languages = LanguageHelper::getLanguageCodes();

		$this->logOut('Start pushing translations.')
			->setupLanguageProvider()
			->uploadTemplates()
			->uploadTranslations()
			->out()
			->logOut(g11n3t('Finished.'));
	}

    /**
    * Push translation templates.
    *
    * @return  $this
    *
    * @throws \DomainException
    * @since   __DEPLOY_VERSION__
    */
    private function uploadTemplates()
	{
		$create = $this->getOption('create');

		defined('JDEBUG') || define('JDEBUG', 0);

		LanguageHelper::addDomainPaths();

		foreach (LanguageHelper::getScopes() as $domain => $extensions)
		{
			foreach ($extensions as $extension)
			{
				$name  = $extension . ' ' . $domain;

				$alias = OutputFilter::stringUrlUnicodeSlug($name);

				$this->out('Processing: ' . $name . ' - ' . $alias);

				$templatePath = Storage::getTemplatePath($extension, $domain);

				if (false === file_exists($templatePath))
				{
					throw new \DomainException(sprintf('Language template for %s not found.', $name));
				}

				$this->out($templatePath);

				try
				{
					if ($create)
					{
						switch ($this->languageProvider)
						{
							case 'transifex':
								$this->transifex->resources->createResource(
									$this->getApplication()->get('transifex.project'), $name, $alias, 'PO', ['file' => $templatePath]
								);

								break;

							case 'crowdin':
								$fileName = $this->getApplication()->get('crowdin.filepath', '') . $alias . '_en.po';
								$this->crowdin->file->add(new Languagefile($templatePath, $fileName));

								break;
						}

						$this->out('<ok>Resource created successfully</ok>');
					}
					else
					{
						switch ($this->languageProvider)
						{
							case 'transifex':
								$this->transifex->resources->updateResourceContent(
									$this->getApplication()->get('transifex.project'), $alias, $templatePath, 'file'
								);

								break;

							case 'crowdin':
								$fileName = $this->getApplication()->get('crowdin.filepath', '') . $alias . '_en.po';
								$this->crowdin->file->update(new Languagefile($templatePath, $fileName));

								break;
						}

						$this->out('<ok>Resource updated successfully</ok>');
					}
				}
				catch (\Exception $e)
				{
					$this->out('<error>' . $e->getMessage() . '</error>');
				}

				$this->out();
			}
		}

		return $this;
	}

    /**
    * Push translations.
    *
    * @return $this
    *
    * @since   __DEPLOY_VERSION__
    */
    private function uploadTranslations()
	{
		if ('crowdin' != $this->languageProvider)
		{
			// Currently this is only enabled for Crowdin.
			return $this;
		}

		if (!$this->getOption('translations'))
		{
			return $this;
		}

		defined('JDEBUG') || define('JDEBUG', 0);

		LanguageHelper::addDomainPaths();

		foreach (LanguageHelper::getScopes() as $domain => $extensions)
		{
			$scopePath = ExtensionHelper::getDomainPath($domain);

			foreach ($extensions as $extension)
			{
				$extensionPath = ExtensionHelper::getExtensionLanguagePath($extension);

				$this->out(sprintf('Processing: %s %s... ', $domain, $extension), false);

				foreach ($this->languages as $language)
				{
					if ('en-GB' == $language)
					{
						continue;
					}

					$this->out($language . '... ', false);

					$fileName = $this->getApplication()->get('crowdin.filepath', '')
						. strtolower(str_replace('.', '-', $extension)) . '-' . strtolower($domain) . '_en.po';

					// Get the "Sink"
					$path = $scopePath . '/' . $extensionPath . '/' . $language . '/' . $language . '.' . $extension . '.po';

					if (false === is_dir(dirname($path)))
					{
						$this->out('<info>NOT FOUND</info>... ', false);

						continue;
					}

					// Call out to Crowdin
					try
					{
						$this->crowdin->translation->upload(new Languagefile($path, $fileName), LanguageHelper::getCrowdinLanguageTag($language), true, true);

						$this->out('ok... ', false);
					}
					catch (\Exception $e)
					{
						$this->out('<error>' . $e->getMessage() . '</error>');
					}
				}

				$this->out();
			}
		}

		return $this;
	}
}
