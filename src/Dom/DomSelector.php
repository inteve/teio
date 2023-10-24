<?php

	namespace Teio\Dom;


	class DomSelector
	{
		/** @var DomSelectorGroup[] */
		private $groups = [];


		public function addGroup()
		{
			return $this->groups[] = new DomSelectorGroup;
		}


		/**
		 * @return bool
		 */
		public function matchNode(ISelectableNode $node)
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
