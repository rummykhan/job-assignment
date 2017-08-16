<?php

namespace AppBundle\Controller;


use AppBundle\Infrastructure\JsonResponseHandler;
use AppBundle\Infrastructure\JsonResponseInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class FeedController extends Controller implements JsonResponseInterface
{
    /**
     * A Trait that can be used to centralize JSON response.
     */
    use JsonResponseHandler;

    protected $desiredAttributes = [
        'productID',
        'name',
        'description',
        'price',
        'currency',
        'categories',
        'category',
        'productURL',
        'imageURL'
    ];

    /**
     * @Route("/feed", name="get_feed")
     * @Method({"POST"})
     */
    public function feedAction(Request $request)
    {
        // Get JSON content from the request.
        $postData = $this->get('json.parser')
            ->setRequest($request)
            ->getContent();

        // get URL from post data.
        $url = $postData['url'] ?? null;

        // If user want to load data from cache or not
        $forceRefresh = $postData['forceRefresh'] ?? false;

        // Validate User Input
        $violations = $this->validate($url);

        // If validation is failed send failed validation response.
        if (0 !== count($violations)) {
            return $this->sendValidationFailedResponse($violations);
        }

        // parse URL (Add Skip and Limit based on Page)
        $parsedUrl = $this->get('url.decorator')
            ->setPostData($postData)
            ->decorate();

        // get cache service.
        $cache = $this->get('doctrine_cache.providers.tradetracker_cache');

        // If user has enabled Force refresh then don't get the results from cache.
        // if results are already cached then get from the cache.
        if (!$forceRefresh && $cache->contains(md5($parsedUrl))) {
            $results = $cache->fetch(md5($parsedUrl));

            return $this->sendJsonResponse(true, 200, $results);
        }

        // Request Feed
        $xml = $this->get('web.client')
            ->setMethod('GET')
            ->setUrl($parsedUrl)
            ->fetch();

        // Parse Feed
        $feed = $this->get('tradetracker.feed.parser')
            ->setXML($xml)
            ->setDesiredAttributes($this->desiredAttributes)
            ->parse();

        // Cache feed on key = md5(url)
        $cache->save(md5($parsedUrl), $feed);

        return $this->sendJsonResponse(true, 200, $feed);
    }

    /**
     * Validate incoming URL.
     *
     * @param $url
     * @return ConstraintViolationListInterface
     */
    protected function validate($url)
    {
        $validator = $this->get('validator');

        return $validator->validate($url, [
            new NotBlank(),
            new Url()
        ]);
    }

    /**
     * Send validation failed response.
     *
     * @param ConstraintViolationListInterface $violations
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function sendValidationFailedResponse(ConstraintViolationListInterface $violations)
    {
        $errors = [];
        foreach ($violations as $violation) {
            $errors[] = $violation->getMessage();
        }

        return $this->sendJsonResponse(false, 422, $errors);
    }
}