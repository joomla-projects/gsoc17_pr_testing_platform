<?php
/**
 * Part of the Joomla Tracker's Debug Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Debug\Format\Html;

/**
 * Class SqlFormat
 *
 * @since __DEPLOY_VERSION__
 */
class SqlFormat
{
    /**
    * Simple highlight for SQL queries.
    *
    * @param   string  $query   The query to highlight
    * @param   string  $prefix  Table prefix.
    *
    * @return  string
    *
    * @since   __DEPLOY_VERSION__
    */
    public function highlightQuery($query, $prefix)
	{
		$newlineKeywords = '#\b(FROM|LEFT|INNER|OUTER|WHERE|SET|VALUES|ORDER|GROUP|HAVING|LIMIT|ON|AND|CASE)\b#i';

		$query = htmlspecialchars($query, ENT_QUOTES);

		$query = preg_replace($newlineKeywords, '<br />&#160;&#160;\\0', $query);

		$regex = [

			// Tables are identified by the prefix
			'/(=)/'
			=> '<span class="dbgOperator">$1</span>',

			// All uppercase words have a special meaning
			'/(?<!\w|>)([A-Z_]{2,})(?!\w)/x'
			=> '<span class="dbgCommand">$1</span>',

			// Tables are identified by the prefix
			'/(' . $prefix . '[a-z_0-9]+)/'
			=> '<span class="dbgTable">$1</span>',
		];

		$query = preg_replace(array_keys($regex), array_values($regex), $query);

		$query = str_replace('*', '<b style="color: red;">*</b>', $query);

		return $query;
	}
}
