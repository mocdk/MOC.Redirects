<?php
namespace MOC\Redirects\Routing;

use Doctrine\ORM\QueryBuilder;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Http\Uri;
use Neos\Flow\Mvc\Routing\DynamicRoutePart;
use Neos\ContentRepository\Domain\Model\NodeData;

/**
 * A route part handler for finding nodes specifically in the website's frontend.
 */
class RedirectFrontendNodeRoutePartHandler extends DynamicRoutePart
{

    /**
     * @Flow\Inject
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $entityManager;

    /**
     * @Flow\Inject
     * @var Bootstrap
     */
    protected $bootstrap;

    /**
     * @param string $requestPath The request path to be matched
     * @return string value to match
     */
    protected function findValueToMatch($requestPath)
    {
        return $requestPath;
    }

    /**
     * @param string $requestPath
     * @return boolean TRUE if the $requestPath could be matched, otherwise FALSE
     */
    protected function matchValue($requestPath)
    {
        /** @var Uri $uri */
        $uri = $this->bootstrap->getActiveRequestHandler()->getHttpRequest()->getUri();
        $relativeUrl = rtrim($uri->getPath(), '/');
        $relativeUrlWithQueryString = $relativeUrl . ($uri->getQuery() ? '?' . $uri->getQuery() : '');
        $absoluteUrl = $uri->getHost() . $relativeUrl;
        $absoluteUrlWithQueryString = $uri->getHost() . $relativeUrlWithQueryString;

        if (empty($relativeUrl)) {
            return false;
        }

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('n')
            ->distinct()
            ->from('Neos\ContentRepository\Domain\Model\NodeData', 'n')
            ->where('n.workspace = :workspace')
            ->setParameter('workspace', 'live')
            ->andWhere('n.properties LIKE :relativeUrl')
            ->setParameter('relativeUrl', '%"redirectUrl"%' . str_replace('/', '\\\\\\/', ltrim($relativeUrl, '/')) . '%');

        $query = $queryBuilder->getQuery();
        $nodes = $query->getResult();
        if (empty($nodes)) {
            return false;
        }

        foreach ($nodes as $node) {
            /** @var NodeData $node */
            // Prevent partial matches
            $redirectUrl = trim(preg_replace('#^https?://#', '', $node->getProperty('redirectUrl')), '/');
            if (in_array($redirectUrl, [$relativeUrl, $relativeUrlWithQueryString, $absoluteUrl, $absoluteUrlWithQueryString], true)) {
                $matchingNode = $node;
                break;
            }
        }

        if (!isset($matchingNode)) {
            return false;
        }

        $this->setName('node');
        $this->value = $matchingNode->getPath();

        return true;
    }

    /**
     * @param string $value value to resolve
     * @return boolean TRUE if value could be resolved successfully, otherwise FALSE.
     */
    protected function resolveValue($value)
    {
        return false;
    }

}
