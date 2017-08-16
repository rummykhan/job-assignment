<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/tmp")
     */
    public function tmpAction()
    {
        $postData = [
          'url' => 'http://pf.tradetracker.net/?aid=1&type=xml&encoding=utf-8&fid=251713&categoryType=2&additionalType=2'
        ];

        // parse URL (Add Skip and Limit based on Page)
        $parsedUrl = $this->get('url.decorator')
            ->setPostData($postData)
            ->decorate();

        // Request Feed
        $xml = $this->get('web.client')
            ->setMethod('GET')
            ->setUrl($parsedUrl)
            ->fetch();

        // Parse Feed
        $products = $this->get('tradetracker.feed.parser')
            ->setXML($xml)
            ->setDesiredAttributes([
                'productID',
                'name',
                'description',
                'price',
                'currency',
                'categories', // root
                'category', // child
                'productURL',
                'imageURL'
            ])
            ->parse();

        $paginator = $this->get('tradetracker.paginator')
            ->setCurrentPage(1)
            ->setData($products)
            ->setUrl($parsedUrl)
            ->getPaginator();

        return $this->render("default/index.html.twig");
    }
}
