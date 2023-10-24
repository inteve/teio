<?php

	namespace Teio\Dom;

	use Nette\Utils\Html;
	use Teio\Helpers;


	class DomParentNode implements ISelectableNode
	{
		/** @var Html */
		private $node;

		/** @var DomPosition|NULL */
		private $position;


		public function __construct(Html $node, DomPosition $position = NULL)
		{
			$this->node = $node;
			$this->position = $position;
		}


		public function isElement()
		{
			return TRUE;
		}


		/**
		 * @return string|NULL
		 */
		public function getName()
		{
			$name = $this->node->getName();
			return !Helpers::isNameEmpty($name) ? $name : NULL;
		}


		public function hasAttribute($attr)
		{
			return isset($this->node->attrs[$attr]);
		}


		public function getAttribute($attr)
		{
			return $this->node->getAttribute($attr);
		}


		public function hasClass($class)
		{
			if (!$this->hasAttribute('class')) {
				return FALSE;
			}

			$value = Helpers::formatAttributeValue($this->getAttribute('class'));
			return strpos(" $value ", " $class ") !== FALSE;
		}


		public function hasPosition()
		{
			return $this->position !== NULL;
		}


		public function isFirst()
		{
			if (!$this->hasPosition()) {
				throw new \Teio\InvalidStateException('ParentNode has not position.');
			}

			return $this->position->isFirst();
		}


		public function isLast()
		{
			if (!$this->hasPosition()) {
				throw new \Teio\InvalidStateException('ParentNode has not position.');
			}

			return $this->position->isLast();
		}


		/**
		 * @param  Html|string $html
		 * @return void
		 */
		public function appendChild($html)
		{
			$this->node->addHtml($html);
		}


		/**
		 * @return Html
		 */
		public function cloneTag()
		{
			return Html::el($this->node->getName(), $this->node->attrs);
		}
	}
