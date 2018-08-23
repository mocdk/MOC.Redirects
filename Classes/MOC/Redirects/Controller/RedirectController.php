<?php
namespace MOC\Redirects\Controller;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Neos\Service\LinkingService;
use Neos\ContentRepository\Domain\Model\Node;

class RedirectController extends ActionController
{

    /**
     * @Flow\Inject
     * @var LinkingService
     */
    protected $linkingService;

    /**
     * @param Node $node
     * @return void
     *
     * @Flow\IgnoreValidation("node")
     */
    public function redirectAction(Node $node)
    {
        $this->redirectToUri(
            $this->linkingService->createNodeUri($this->controllerContext, $node, null, null, true, [], '', true),
            0,
            301
        );
    }

}
