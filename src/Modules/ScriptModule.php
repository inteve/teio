<?php

	namespace Teio\Modules;

	use Nette\Utils\Html;
	use Nette\Utils\Strings;
	use Teio\Dom\Dom;
	use Teio\Dom\Node;
	use Teio\IModule;


	class ScriptModule implements IModule
	{
		/** @var array<string, callable> */
		private $handlers;


		/**
		 * @param array<string, callable> $handlers
		 */
		public function __construct(array $handlers)
		{
			$this->handlers = $handlers;
		}


		public function process(Dom $dom): void
		{
			$dom->findTextNodes(function (Node $node) {
				$newContent = Html::el();
				$text = $node->getText();
				$matches = Strings::matchAll($text, '#\{\{((?:[^}]++|[}])+)\}\}()#U', PREG_OFFSET_CAPTURE);
				$length = strlen($text);
				$lastOffset = 0;
				$matchOffset = 0;
				$isReplaced = FALSE;

				foreach ($matches as $match) {
					$matchOffset = $match[0][1];

					if ($lastOffset < $matchOffset) {
						$newContent->addText(substr($text, $lastOffset, $matchOffset - $lastOffset));
						$lastOffset = $matchOffset;
					}


					$content = $match[1][0];
					$cmd = trim($content);

					if ($cmd === '') {
						continue;
					}

					$raw = null;
					$args = [];

					// function (arg, arg, ...) or function: arg, arg
					if ($bodyMatch = Strings::match($cmd, '#^([a-z_][a-z0-9_-]*)\s*(?:\(([^()]*)\)|:(.*))$#iu')) {
						$cmd = $bodyMatch[1];
						$raw = trim($bodyMatch[3] ?? $bodyMatch[2]);

						if ($raw !== '') {
							$args = Strings::split($raw, '#\s*,\s*#u');
						}
					}

					if (isset($this->handlers[$cmd])) {
						$lastOffset += strlen($match[0][0]);
						$cb = $this->handlers[$cmd];
						$result = $cb($cmd, $args);
						$isReplaced = TRUE;

						if ($result === NULL) { // no output
							// nothing

						} elseif (is_string($result)) { // Html instance or HTML string
							$newContent->addText($result);

						} elseif ($result instanceof Html) {
							$newContent->addHtml($result);

						} else {
							throw new \Teio\InvalidStateException('Invalid handler result, result must be Html, string or NULL.');
						}
					}
				}

				if (!$isReplaced) {
					return;
				}

				if ($lastOffset < $length) {
					$newContent->addText(substr($text, $lastOffset));
				}

				$node->replaceByHtml($newContent);
				$node->skipChildren();
			});
		}
	}
