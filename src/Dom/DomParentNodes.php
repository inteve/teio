<?php

	namespace Teio\Dom;

	use Nette\Utils\Html;
	use Teio\Helpers;


	class DomParentNodes
	{
		/** @var DomParentNode[] */
		private $nodes;


		public function __construct(array $nodes)
		{
			if (empty($nodes)) {
				throw new \Teio\InvalidArgumentException('Nodes cannot be empty.');
			}

			$this->nodes = $nodes;
		}


		public function getNodes()
		{
			return $this->nodes;
		}
	}
