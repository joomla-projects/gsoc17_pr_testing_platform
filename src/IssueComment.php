<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Testing\PRTestingPlatform;

use Joomla\Github\Github;
use Joomla\Registry\Registry;

/**
 * Class IssueComment
 *
 * @package  Joomla\Testing\PRTestingPlatform
 * @since    __DEPLOY_VERSION__
 */

class IssueComment
{
	/**
	 * @var string
	 */
	protected $owner;

	/**
	 * @var string
	 */
	protected $repo;

	/**
	 * @var integer
	 */
	protected $prid;

	/**
	 * IssueComment constructor.
	 *
	 * @param   string  $owner The name of the owner of the GitHub repository.
	 * @param   string  $repo  The name of the GitHub repository.
	 * @param   integer $prid  ID of the Pull Request
	 */
	public function __construct($owner, $repo, $prid)
	{
		$this->owner = $owner;
		$this->repo = $repo;
		$this->prid = $prid;
	}

	/**
	 * Creates a comment on a PR/issue
	 *
	 * @param   string  $ghUsername  Github username
	 * @param   string  $ghPassword  Github user password
	 * @param   string  $commentMsg  Message to be included in the PR comment
	 *
	 * @return  void
	 */
	public function createComment($ghUsername, $ghPassword, $commentMsg)
	{
		$options = new Registry;
		$options->set('api.username', $ghUsername);
		$options->set('api.password', $ghPassword);

		$github = new Github($options);

		$github->issues->comments->create($this->owner, $this->repo, $this->prid, $commentMsg);

	}
}
