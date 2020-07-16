<?php

	namespace Teio\Dom;

	use Nette\Utils\Html;


	class DomPathPart
	{
		/** @var Html */
		private $element;

		/** @var int */
		private $index;

		/** @var bool */
		private $isLast;


		public function __construct(Html $element, $index, $isLast)
		{
			$this->element = $element;
			$this->index = $index;
			$this->isLast = $isLast;
		}


		public function isElement()
		{
			return $this->element->getName() !== NULL;
		}


		public function isTag($tagName)
		{
			return $this->element->getName() === $tagName;
		}


		public function hasId($id)
		{
			return isset($this->element->attrs['id']) && $this->element->attrs['id'] === $id;
		}



		public function hasClass($class)
		{
			if (!isset($this->element->attrs['class'])) {
				return FALSE;
			}
			$value = $this->formatAttributeValue($this->element->attrs['class']);
			return strpos(" $value ", " $class ") !== FALSE;
		}


		public function isFirst()
		{
			return $this->index === 0;
		}


		public function isLast()
		{
			return $this->isLast;
		}


		/**
		 * @param  mixed
		 * @return string
		 */
		private function formatAttributeValue($value)
		{
			if (is_array($value)) {
				$tmp = null;
				foreach ($value as $k => $v) {
					if ($v != null) { // intentionally ==, skip nulls & empty string
						// composite 'style' vs. 'others'
						$tmp[] = $v === true ? $k : (is_string($k) ? $k . ':' . $v : $v);
					}
				}

				if ($tmp === null) {
					return '';
				}

				$value = implode(' ', $tmp);
			}

			return (string) $value;
		}
	}
