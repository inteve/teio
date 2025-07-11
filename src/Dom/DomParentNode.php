<?php

	declare(strict_types=1);

	namespace Teio\Dom;

	use Nette\Utils\Html;
	use Teio\Helpers;


	class DomParentNode implements SelectableNode
	{
		/** @var Html */
		private $node;

		/** @var DomPosition|NULL */
		private $position;


		public function __construct(Html $node, ?DomPosition $position = NULL)
		{
			$this->node = $node;
			$this->position = $position;
		}


		public function isElement(): bool
		{
			return TRUE;
		}


		public function getName(): ?string
		{
			$name = $this->node->getName();
			return !Helpers::isNameEmpty($name) ? $name : NULL;
		}


		public function hasAttribute(string $attr): bool
		{
			return isset($this->node->attrs[$attr]);
		}


		public function getAttribute(string $attr)
		{
			$value = $this->node->getAttribute($attr);
			assert(is_array($value) || is_scalar($value));
			return $value;
		}


		public function hasClass(string $class): bool
		{
			if (!$this->hasAttribute('class')) {
				return FALSE;
			}

			$value = Helpers::formatAttributeValue($this->getAttribute('class'));
			return strpos(" $value ", " $class ") !== FALSE;
		}


		/**
		 * @phpstan-assert-if-true !NULL $this->position
		 */
		public function hasPosition(): bool
		{
			return $this->position !== NULL;
		}


		public function isFirst(): bool
		{
			if (!$this->hasPosition()) {
				throw new \Teio\InvalidStateException('ParentNode has not position.');
			}

			return $this->position->isFirst();
		}


		public function isLast(): bool
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
