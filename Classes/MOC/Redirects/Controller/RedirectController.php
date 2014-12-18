<?php
namespace MOC\Redirects\Controller;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Neos\Service\LinkingService;
use TYPO3\TYPO3CR\Domain\Model\Node;

class RedirectController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @var LinkingService
	 */
	protected $linkingService;

	/**
	 * @param Node $node
	 * @return void
	 *
	 * @Flow\IgnoreValidation("node")
	 */
	public function showAction(Node $node) {
		$this->redirectToUri($this->linkingService->createNodeUri($this->controllerContext, $node));
	}

}