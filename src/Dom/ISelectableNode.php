<?php

	namespace Teio\Dom;


	interface ISelectableNode
	{
		function isElement(): bool;


		function getName(): ?string;


		function hasAttribute(string $attr): bool;


		/**
		 * @return scalar|NULL|array<scalar|NULL>
		 */
		function getAttribute(string $attr);


		function hasClass(string $class): bool;


		function hasPosition(): bool;


		function isFirst(): bool;


		function isLast(): bool;
	}
