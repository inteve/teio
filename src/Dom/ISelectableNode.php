<?php

	namespace Teio\Dom;


	interface ISelectableNode
	{
		function isElement();


		function getName();


		function hasAttribute($attr);


		function getAttribute($attr);


		function hasClass($class);


		function hasPosition();


		function isFirst();


		function isLast();
	}
