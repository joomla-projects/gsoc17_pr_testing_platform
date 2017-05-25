<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\PRTestingPlatform;

/**
 * Class DockerSwarmHandler
 *
 * @package  Joomla\PRTestingPlatform
 * @since    __DEPLOY_VERSION__
 */

class DockerSwarmHandler
{
	/**
	 * @var string The name of the swarm to be managed
	 */
	private $swarmname;

	/**
	 * @var integer Number of manager nodes for the swarm
	 */
	private $managerNodes;

	/**
	 * @var integer Number of nodes in the swarm (managers + workers)
	 */
	private $totalNodes;

	/**
	 * DockerSwarmHandler constructor.
	 *
	 * @param   string  $swarmname  The name of the swarm to be managed
	 */
	public function __construct($swarmname)
	{
		$this->swarmname = $swarmname;
		$this->managerNodes = 3;
		$this->totalNodes = 3;
	}

	/**
	 * Function to generate the bash script to initialize the swarm with its manager nodes
	 *
	 * @return void
	 */
	public function generateSwarmInitScript()
	{
		$dir = getcwd() . '/../bash/';

		if (!is_dir($dir))
		{
			mkdir($dir);
		}

		$file = $dir . $this->swarmname . "-swarm-init.sh";

		file_put_contents($file, "#!/usr/bin/env bash\n\n");


		// Create the manager nodes
		$content = "for i in $(seq 1 " . $this->managerNodes . "); do\n";

		file_put_contents($file, $content, FILE_APPEND | LOCK_EX);

		$content = "\tdocker-machine create -d virtualbox " . $this->swarmname . "-node-\$i\ndone\n";

		file_put_contents($file, $content, FILE_APPEND | LOCK_EX);

		// Initialize the first manager node which will be the leader node
		$content = "\neval $(docker-machine env " . $this->swarmname . "-node-1)\n";

		file_put_contents($file, $content, FILE_APPEND | LOCK_EX);

		$content = "\ndocker swarm init --advertise-addr $(docker-machine ip " . $this->swarmname . "-node-1)";

		file_put_contents($file, $content, FILE_APPEND | LOCK_EX);

		$content = "\n\nTOKEN=$(docker swarm join-token -q manager)";

		file_put_contents($file, $content, FILE_APPEND | LOCK_EX);

		// Initialize the remaining manager nodes and make them join the swarm
		$content = "\n\nfor i in $(seq 2 " . $this->managerNodes . "); do\n";
		$content = $content . "\teval $(docker-machine env " . $this->swarmname . "-node-\$i)\n";

		file_put_contents($file, $content, FILE_APPEND | LOCK_EX);

		$content = "\n\tdocker swarm join \\
		--token \$TOKEN \\
		--advertise-addr $(docker-machine ip " . $this->swarmname . "-node-\$i) \\
		 $(docker-machine ip " . $this->swarmname . "-node-1):2377\n";

		$content = $content . "done";

		file_put_contents($file, $content, FILE_APPEND | LOCK_EX);

		$content = "\n\nfor i in $(seq 1 " . $this->managerNodes . "); do\n";
		$content = $content . "\teval $(docker-machine env " . $this->swarmname . "-node-\$i)\n";

		file_put_contents($file, $content, FILE_APPEND | LOCK_EX);

		$content = "\n\tdocker node update \\
		--label-add env=prod \\
		" . $this->swarmname . "-node-\$i";

		$content = $content . "\ndone\n\n";

		file_put_contents($file, $content, FILE_APPEND | LOCK_EX);

		$content = "echo \"The swarm cluster has been initialized and is up and running!\"";

		file_put_contents($file, $content, FILE_APPEND | LOCK_EX);

		chmod($file, 0770);
	}

	/**
	 * Function to run the generated script to initialize the swarm
	 *
	 * @return void
	 */
	public function initSwarm()
	{
		exec("../bash/" . $this->swarmname . "-swarm-init.sh");
	}

	/**
	 * Function to clean/delete the entire swarm
	 *
	 * @return void
	 */
	public function cleanSwarm()
	{
		$command = "docker-machine rm -f";

		for ($i = 1; $i <= $this->totalNodes; $i++)
		{
			$command = $command . " " . $this->swarmname . "-node-" . $i;
		}

		exec($command);
	}

	/**
	 * Function to add a worker node to the swarm
	 * (STILL NOT WORKING)
	 *
	 * @return void
	 */
	public function addWorkerNode()
	{
		$this->totalNodes++;
		$nodeName = $this->swarmname . "-node-" . $this->totalNodes;


		$commands = "docker-machine create -d virtualbox " . $nodeName;

		exec($commands);

		$commands = "eval $(docker-machine env " . $nodeName . ") && ";
		$commands = $commands . "docker swarm join \\
		--token $(docker swarm join-token -q worker) \\
		--advertise-addr $(docker-machine ip " . $nodeName . ") \\
		$(docker-machine ip " . $nodeName . "):2377";

		exec($commands);

		$commands = $commands = "eval $(docker-machine env " . $this->swarmname . "-node-1) && ";
		$commands = $commands . "docker node update --label-add env=prod " . $nodeName;

		exec($commands);
	}
}

