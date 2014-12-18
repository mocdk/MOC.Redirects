<?php
namespace MOC\Redirects\Routing;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Routing\DynamicRoutePart;
use TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository;
use TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository;

/**
 * A route part handler for finding nodes specifically in the website's frontend.
 */
class RedirectFrontendNodeRoutePartHandler extends DynamicRoutePart {

	/**
	 * @var WorkspaceRepository
	 */
	protected $workspaceRepository;

	/**
	 * @var NodeDataRepository
	 */
	protected $nodeDataRepository;

	/**
	 * Matches a frontend URI pointing to a node (for example a page).
	 *
	 * This function tries to find a matching node by the given request path. If one was found, its
	 * absolute context node path is set in $this->value and TRUE is returned.
	 *
	 * Note that this matcher does not check if access to the resolved workspace or node is allowed because at the point
	 * in time the route part handler is invoked, the security framework is not yet fully initialized.
	 *
	 * @param string $requestPath The request path (without leading "/", relative to the current Site Node)
	 * @return boolean TRUE if the $requestPath could be matched, otherwise FALSE
	 */
	protected function matchValue($requestPath) {
		$matchingNodes = $this->nodeDataRepository->findByProperties($requestPath, '', $this->workspaceRepository->findOneByName('live'), array());
		if (empty($matches)) {
			return FALSE;
		}

		$this->value = $matchingNodes[0]->getContextPath();

		return TRUE;
	}

	/**
	 * @param string $value value to resolve
	 * @return boolean TRUE if value could be resolved successfully, otherwise FALSE.
	 */
	protected function resolveValue($value) {
		return FALSE;
	}

}