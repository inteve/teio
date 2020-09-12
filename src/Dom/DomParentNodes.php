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
			$this->nodes = $nodes;
		}


		public function getNodes()
		{
			return $this->nodes;
		}
	}
