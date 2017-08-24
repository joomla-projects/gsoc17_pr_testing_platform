<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Instances\Table;

use Joomla\Database\DatabaseDriver;

use JTracker\Database\AbstractDatabaseTable;

/**
 * Table interface class for the #__instances table
 *
 * @property   integer  $instance_id       PK
 * @property   integer  $php_version       PHP version
 * @property   integer  $user_id           Github user ID
 * @property   integer  $pr_id             Pull Request ID
 * @property   string   $requested_at      Date and time when instance was requested
 * @property   string   $target_branch     PR target branch
 *
 * @since __DEPLOY_VERSION__
 */
class InstancesTable extends AbstractDatabaseTable
{
	/**
	 * Constructor
	 *
	 * @param   DatabaseDriver  $db  A database connector object
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __construct(DatabaseDriver $db)
	{
		parent::__construct('#__instances', 'instance_id', $db);
	}

	/**
	 * This method processes a string and replaces all accented UTF-8 characters by unaccented
	 * ASCII-7 "equivalents", whitespaces are replaced by hyphens and the string is lowercase.
	 *
	 * @param   string  $string  String to process
	 *
	 * @return  string  Processed string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function stringURLSafe($string)
	{
		// Remove any '-' from the string since they will be used as concatenators
		$str = str_replace('-', ' ', $string);

		// $lang = Language::getInstance();
		// $str = $lang->transliterate($str);

		// Trim white spaces at beginning and end of alias and make lowercase
		$str = trim(strtolower($str));

		// Remove any duplicate whitespace, and ensure all characters are alphanumeric
		$str = preg_replace('/(\s|[^A-Za-z0-9\-])+/', '-', $str);

		// Trim dashes at beginning and end of alias
		$str = trim($str, '-');

		return $str;
	}


	/**
	 * Method to delete a row from the database table by primary key value.
	 *
	 * @param   mixed  $pKey  An optional primary key value to delete.  If not set the instance property value is used.
	 *
	 * @return  AbstractDatabaseTable
	 *
	 * @since   __DEPLOY_VERSION__
	 * @throws  \UnexpectedValueException
	 */
	public function delete($pKey = null)
	{

		parent::delete($pKey);

		// Delete the entries in the map table.
		$this->db->setQuery(
			$this->db->getQuery(true)
				->delete($this->db->quoteName('#__instances'))
				->where($this->db->quoteName('instance_id') . ' = ' . (int) $this->instance_id)
		)->execute();

		return $this;
	}
}
