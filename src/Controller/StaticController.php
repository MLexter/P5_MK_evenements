<?php

namespace App\Controller;

use App\Data\CartFunctions;
use Swift_Mailer;
use App\Entity\Article;
use App\Data\SearchData;
use App\Form\ContactType;
use App\Form\SearchFormType;
use App\Form\PerformancesType;
use App\Repository\ArticleRepository;
use App\Repository\GalleryRepository;
use App\Repository\PriceListRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class StaticController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('views/index.html.twig');
    }


    /**
     * @Route("/catalogue", name="catalogue_main")   
     */
    public function catalogMain(ArticleRepository $repo, Request $request, PaginatorInterface $paginator, CartFunctions $cartFunctions)
    {
        $data = new SearchData();

        $form = $this->createForm(SearchFormType::class, $data);
        $form->handleRequest($request);

        $articles = $paginator->paginate(
            $repo->findSearch($data),
            $request->query->getInt('page', 1),
            9
        );


        return $this->render('views/catalog.html.twig', [
            'articles' => $articles,
            'count' => $cartFunctions->getBadgeNumber(),
            'total' => $cartFunctions->getTotal(),
            'form' => $form->createView()
        ]);
    }


    /**
     *@Route("/catalogue/telecharger-grille-tarifaire", name="priceListDownload")
     */
    public function PriceListDl(PriceListRepository $priceListRepository)
    {
        $getPriceList = $priceListRepository->findPriceList();
        $uploads_directory = $this->getParameter('upload_directory');
        $filename = $uploads_directory . '/' . $getPriceList[0]['filename'];
        
        $response = new BinaryFileResponse($filename);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $getPriceList[0]['filename']);

        return $response;
    }

    /**
     *@Route("/galerie", name="gallery")
     * @return void
     */
    public function showGallery(GalleryRepository $galleryRepository)
    {
        $getImages = $galleryRepository->findAll();

        return $this->render('views/gallery.html.twig', [
            'images' => $getImages
        ]);
    }

    /**
     * @Route("catalogue/{id}", name="show_article")
     */
    public function showArticle(Article $article)
    {
        return $this->render('views/show_article.html.twig', [
            'article' => $article
        ]);
    }



    /**
     * @Route("/a-propos", name="about")
     */
    public function showAbout()
    {
        return $this->render('views/about.html.twig');
    }


    /**
     * @Route("/prestations", name="performances")
     */
    public function showPerformances(Request $request, Swift_Mailer $mailer)
    {
        $performance_form = $this->createForm(PerformancesType::class);
        $performance_form->handleRequest($request);

        $formData = [];


        if ($performance_form->isSubmitted() && $performance_form->isValid()) {

            foreach ($request->request->get('performances') as $key => $value) {
                $formData = [
                    'last_name' => $request->request->get('performances')['last_name'],
                    'first_name' => $request->request->get('performances')['first_name'],
                    'phoneNumber' => $request->request->get('performances')['phoneNumber'],
                    'email' => $request->request->get('performances')['email'],
                    'event_type' => $request->request->get('performances')["event_type"],
                    'location_name' => $request->request->get('performances')["location_name"],
                    'event_date' => $request->request->get('performances')["event_date"],
                    'hosts_number' => $request->request->get('performances')["hosts_number"],
                    'end_event_time' => $request->request->get('performances')["end_event_time"],
                    'celebration' => $request->request->get('performances')["celebration"],
                    'cocktail_location' => $request->request->get('performances')["cocktail_location"],
                    'diner_dancefloor_separated' => $request->request->get('performances')["diner_dancefloor_separated"],
                    'close_distant_spaces' => $request->request->get('performances')["close_distant_spaces"],
                    'perf_comment' => $request->request->get('performances')["perf_comment"]
                ];
            }

            // Formatage de l'heure
            if ($formData["end_event_time"]["hour"] >= 0 && $formData["end_event_time"]["hour"] <= 9) {
                $zero = 0;
                $formData["end_event_time"]["hour"] = $zero . $formData["end_event_time"]["hour"];
            }

            if ($formData["end_event_time"]["minute"] >= 0 && $formData["end_event_time"]["minute"] <= 9) {
                $zero = 0;
                $formData["end_event_time"]["minute"] = $zero . $formData["end_event_time"]["minute"];
            }

            // Formatage de la date
            if ($formData["event_date"]["day"] >= 0 && $formData["event_date"]["day"] <= 9) {
                $zero = 0;
                $formData["event_date"]["day"] = $zero . $formData["event_date"]["day"];
            }

            if ($formData["event_date"]["month"] >= 0 && $formData["event_date"]["month"] <= 9) {
                $zero = 0;
                $formData["event_date"]["month"] = $zero . $formData["event_date"]["month"];
            }


            // Création du mail à envoyer
            $mail = (new \Swift_Message('Demande d\'informations'))
                // Création de l'expéditeur
                ->setFrom($formData["email"])
                // Création du destinataire
                ->SetTo('contact@mk-evenements.com');

            $brandlogo = $mail->attach(\Swift_Attachment::fromPath('assets/images/brand/mkevents_logo.png')
                            ->setDisposition('inline'));

            // Création du message
            $mail->setBody(
                $this->render('mail/performances_mail.html.twig', [
                    'formData' => $formData,
                    'logo' => $brandlogo
                ]),
                'text/html'
            );

            $mailer->send($mail);
            $this->addFlash('success', 'Votre formulaire a bien été envoyé !');

            return $this->redirectToRoute('performances');
        }

        return $this->render('views/prestations.html.twig', [
            'formData' => $formData,
            'performance_form' => $performance_form->createView()
        ]);
    }



    /**
     * @Route("/contact", name="contact")
     */
    public function showContact(Request $request, Swift_Mailer $mailer)
    {
        $contact_form = $this->createForm(ContactType::class);
        $contact_form->handleRequest($request);

        $contactData = [];

        if ($contact_form->isSubmitted() && $contact_form->isValid()) {
            foreach ($_POST["contact"] as $key => $value) {
                $contactData = [
                    'last_name' => $request->request->get("contact")['last_name'],
                    'first_name' => $request->request->get("contact")['first_name'],
                    'phoneNumber' => $request->request->get("contact")['phoneNumber'],
                    'email' => $request->request->get("contact")['email'],
                    'subject' => $request->request->get("contact")['subject'],
                    'message' => $request->request->get("contact")["message"]
                ];
            }
            // Création du mail à envoyer
            $mail = (new \Swift_Message('Demande d\'informations'))
                // Création de l'expéditeur
                ->setFrom($contactData["email"])
                // Création du destinataire
                ->SetTo('contact@mk-evenements.com');

            // Création du message
            $mail->setBody(
                $this->render('mail/contact_mail.html.twig', [
                    'contactData' => $contactData,
                ]),
                'text/html'
            );

            $mailer->send($mail);

            $this->addFlash('success', 'Votre formulaire a bien été envoyé !');

            return $this->redirectToRoute('contact');
        }

        return $this->render('views/contact.html.twig', [
            'contact_form' => $contact_form->createView()
        ]);
    }

    /**
     * @Route("/mentions-legales", name="legal_notice")
     *
     * @return void
     */
    public function showLegalNotice() 
    {
        return $this->render('views/legal_notice.html.twig');
    }

}
