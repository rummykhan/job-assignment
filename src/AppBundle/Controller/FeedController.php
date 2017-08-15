<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class FeedController extends Controller
{
    protected $attributes = [
        'productID',
        'name',
        'description',
        'price',
        'currency',
        'categories',
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
        $postData = $this->get('tradetracker.json.parser')
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
        $parsedUrl = $this->get('tradetracker.url.decorator')
            ->setPostData($postData)
            ->decorate();

        // get Configured cache service.
        $cache = $this->get('doctrine_cache.providers.tradetracker_cache');

        // If user enabled Force refresh don't get the results from cache.
        // if results are already cached then get from the cache.
        if (!$forceRefresh && $cache->contains(md5($parsedUrl))) {
            $results = $this->get(md5($parsedUrl));

            return $this->sendJsonResponse(true, 200, $results);
        }

        // Request Feed
        $xml = $this->get('tradetracker.web.client.service')
            ->setMethod('GET')
            ->setUrl($parsedUrl)
            ->fetch();

        // Parse Feed
        $parser = $this->get('tradetracker.xml.parser')
            ->setXML($xml)
            ->setAttributes($this->attributes)
            ->parse();

        /*$crawler = (new Crawler($xml))->filterXPath('products')->children();
        foreach ($crawler as $item) {
            dump($item->childNodes[0], $item->childNodes[1]);
        }
        exit;*/

        // Create Paginator
        // Cache feed on key = md5(url)

        // Send Response
    }

    protected function validate($url)
    {
        $validator = $this->get('validator');

        return $validator->validate($url, [
            new NotBlank(),
            new Url()
        ]);
    }

    protected function sendValidationFailedResponse(ConstraintViolationListInterface $violations)
    {
        $errors = [];
        foreach ($violations as $violation) {
            $errors[] = $violation->getMessage();
        }

        return $this->sendJsonResponse(false, 422, $errors);
    }

    protected function sendJsonResponse($success = true, $status = 200, array $data = [])
    {
        $response = [
            'success' => $success
        ];

        if (count($data)) {
            $response['data'] = $data;
        }

        return $this->json($response, $status);
    }
}