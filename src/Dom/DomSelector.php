<?php

	namespace Teio\Dom;


	class DomSelector
	{
		/** @var DomSelectorGroup */
		private $groups = [];


		public function addGroup()
		{
			return $this->groups[] = new DomSelectorGroup($this);
		}


		/**
		 * @return bool
		 */
		public function matchPath(DomPath $path)
		{
			foreach ($this->groups as $group) {
				if ($group->matchPath($path)) {
					return TRUE;
				}
			}

			return FALSE;
		}
	}
