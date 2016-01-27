<?php
namespace MOC\Redirects\Routing;

use Doctrine\ORM\QueryBuilder;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Core\Bootstrap;
use TYPO3\Flow\Http\Uri;
use TYPO3\Flow\Mvc\Routing\DynamicRoutePart;
use TYPO3\TYPO3CR\Domain\Model\NodeData;

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
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    function endsWith($haystack, $needle) {
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
    }

    /**
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    function startsWith($haystack, $needle) {
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
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
        if($this->startsWith($relativeUrl, '/')){
            $tempRelativeUrl = substr($relativeUrl, 1);
        }
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
            ->from('TYPO3\TYPO3CR\Domain\Model\NodeData', 'n')
            ->where('n.workspace = :workspace')
            ->setParameter('workspace', 'live')
            ->andWhere('n.properties LIKE :relativeUrl')
            ->setParameter('relativeUrl', '%"redirectUrl"%' . str_replace('/', '\\\\\\/', $tempRelativeUrl) . '%');
        $query = $queryBuilder->getQuery();
        $nodes = $query->getResult();
        if (empty($nodes)) {
            return false;
        }

        foreach ($nodes as $node) {
            /** @var NodeData $node */
            // Prevent partial matches
            $redirectUrl = preg_replace('#^https?://#', '', $node->getProperty('redirectUrl'));
            if($this->endsWith($redirectUrl,'/') === TRUE){
                $redirectUrl = substr($redirectUrl, 0, -1);
            }
            if($this->startsWith($relativeUrl,'/') === TRUE){
                $relativeUrl = substr($relativeUrl,1);
            }

            if (in_array($redirectUrl,
                array($relativeUrl, $relativeUrlWithQueryString, $absoluteUrl, $absoluteUrlWithQueryString), true)) {

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
