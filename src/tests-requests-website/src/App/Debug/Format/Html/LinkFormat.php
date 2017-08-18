<?php
/**
 * Part of the Joomla Tracker's Debug Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Debug\Format\Html;

/**
 * Class LinkFormat
 *
 * @since __DEPLOY_VERSION__
 */
class LinkFormat
{
    /**
    * The format used to format links.
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    * @link   https://xdebug.org/docs/all_settings#file_link_format
    */
    private $linkFormat;

    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		$this->linkFormat = ini_get('xdebug.file_link_format');
	}

    /**
    * Format a link.
    *
    * @param   string  $file  The file.
    * @param   string  $line  The line number.
    *
    * @return  string
    *
    * @since   __DEPLOY_VERSION__
    */
    public function formatLink($file, $line = '')
	{
		$link = basename($file);
		$link .= ($line) ? ':' . $line : '';

		if ($this->linkFormat)
		{
			$href = $this->linkFormat;
			$href = str_replace('%f', $file, $href);
			$href = str_replace('%l', $line, $href);

			$html = '<a href="' . $href . '">' . $link . '</a>';
		}
		else
		{
			$html = str_replace(JPATH_ROOT, 'JROOT', $file);
		}

		return $html;
	}
}
