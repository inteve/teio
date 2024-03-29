<?php

	declare(strict_types=1);

	namespace Teio\Dom;


	class DomSelector
	{
		/** @var DomSelectorGroup[] */
		private $groups = [];


		public function addGroup(): DomSelectorGroup
		{
			return $this->groups[] = new DomSelectorGroup;
		}


		/**
		 * @return bool
		 */
		public function matchNode(SelectableNode $node)
		{
			foreach ($this->groups as $group) {
				if (!$node->isElement()) {
					continue;
				}

				if ($group->matchNode($node)) {
					return TRUE;
				}
			}

			return FALSE;
		}
	}
