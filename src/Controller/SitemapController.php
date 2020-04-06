<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SitemapController extends AbstractController
{
    /**
     * @Route("/sitemap.xml", name="sitemap", defaults={"_format"="xml"})
     */
    public function getSitemap(Request $request, ArticleRepository $articleRepository)
    {
        $hostname = $request->getSchemeAndHttpHost();

        $urls = [];

        // Static URLs
        $urls[] = ['loc' => $this->generateUrl('index')];
        $urls[] = ['loc' => $this->generateUrl('catalogue_main')];
        $urls[] = ['loc' => $this->generateUrl('gallery')];
        $urls[] = ['loc' => $this->generateUrl('about')];
        $urls[] = ['loc' => $this->generateUrl('performances')];
        $urls[] = ['loc' => $this->generateUrl('contact')];


        // Dynamic URLs
        foreach ($articleRepository->findAll() as $article) {

            $images = [
                'loc' => '/assets/uploads/' . $article->getImage(), // URL to image
                'title' => $article->getTitle()    // Image description
            ];

            $urls[] = [
                'loc' => $this->generateUrl('show_article', [
                    'id' => $article->getId(),
                ]),
                'image' => $images
            ];
        }


        $response = new Response(
            $this->renderView(
                'sitemap/index.html.twig',
                [
                    'urls' => $urls,
                    'hostname' => $hostname
                ]),
                200
        );

        // Headers + Response
        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
