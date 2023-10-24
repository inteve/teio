<?php

	namespace Teio\Modules;

	use Nette\Utils\Html;
	use Nette\Utils\Strings;
	use Teio\Module;
	use Teio\Dom\Dom;
	use Teio\Dom\Node;


	class AutoLinkModule implements Module
	{
		public function process(Dom $dom): void
		{
			$dom->findTextNodes(function (Node $node) {
				$newContent = Html::el();
				$parts = Strings::split($node->getText(), '/(\s+)/');

				foreach ($parts as $part) {
					if (\Nette\Utils\Validators::isEmail($part)) {
						$newContent->create('a')
							->href('mailto:' . $part)
							->addHtml(strtr(htmlspecialchars($part, ENT_NOQUOTES, 'UTF-8'), [
								'@' => '&#64;<!-- -->',
							]));

					} elseif (\Nette\Utils\Validators::isUrl($part)) {
						$newContent->create('a')
							->href($part)
							->setText($part);

					} elseif (Strings::startsWith($part, 'www.') && \Nette\Utils\Validators::isUrl('http://' . $part)) {
						$newContent->create('a')
							->href('http://' . $part)
							->setText($part);

					} else {
						$newContent->addText($part);
					}
				}

				$node->replaceByHtml($newContent);
				$node->skipChildren();
			});
		}
	}
