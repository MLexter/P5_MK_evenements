<?php
namespace App\Controller;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Gallery;
use App\Data\SearchData;
use App\Entity\PriceList;
use App\Form\SearchFormType;
use App\Form\ArticleType;
use App\Form\GalleryType;
use App\Form\AdminDataType;
use App\Form\PriceListType;
use App\Repository\ArticleRepository;
use App\Repository\GalleryRepository;
use App\Repository\PriceListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin")
     */
    public function getAdminPanel()
    {
        return $this->render('admin/main_admin.html.twig');
    }

    /**
     * @Route("admin/gerer_catalogue", name="catalogManagement")
     */
    public function manageCatalogItems(ArticleRepository $repo, Request $request, PaginatorInterface $paginator, PriceListRepository $priceListRepository, PriceList $priceList = null, EntityManagerInterface $entityManager)
    {
        
        $articles = $repo->findAll();
        $data = new SearchData();
        $filesystem = new Filesystem();
        
        $searchForm = $this->createForm(SearchFormType::class, $data);
        $searchForm->handleRequest($request);
          
        $priceListForm = $this->createForm(PriceListType::class, $priceList);
        $priceListForm->handleRequest($request);
        
        if ($priceListForm->isSubmitted() && $priceListForm->isValid()) 
        {  
            $uploads_directory = $this->getParameter('upload_directory');
            $fileList = $request->files->get('price_list')["filename"];
            $filename = md5(uniqid()) . '.' . $fileList->guessExtension();
            $getPriceListinDatabase = $priceListRepository->findAll();
            $lastPdf = null;

            if (count($getPriceListinDatabase) === 0) 
            {
                $lastPdf = new PriceList();

            } else {

                $lastPdf = array_pop($getPriceListinDatabase);
                $filesystem->remove($uploads_directory.'/'.$lastPdf->getFilename());

            }

            $fileList->move($uploads_directory, $filename);
            $lastPdf->setFilename($filename);

            $entityManager->persist($lastPdf);
            $entityManager->flush();

            $this->addFlash('success', 'La grille tarifaire a bien été mise à jour.');
        }


        $articles = $paginator->paginate(
            $repo->findSearch($data),
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('admin/manage_articles.html.twig', [
            'articles' => $articles,
            'priceListForm' => $priceListForm->createView(),
            'form' => $searchForm->createView()
        ]);
    }


    /**
     * @Route("admin/create_article", name="articleCreation")
     * @Route("admin/{id}/edit", name="articleEdit")
     */
    public function addArticle(Article $article = null, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$article) {
            $article = new Article();
        }

        $addArticleForm = $this->createForm(ArticleType::class, $article);


        $addArticleForm->handleRequest($request);

        if ($addArticleForm->isSubmitted() && $addArticleForm->isValid()) {
            $uploads_directory = $this->getParameter('upload_directory');
            $filename = $uploads_directory . '/' . $article->getImage();
            $file = $request->files->get('article')["image"];

            if (($file) && ($article->getImage() !== $file)) {
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($uploads_directory, $filename);
                $article->setImage($filename);
            }

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'L\'article a été ajouté avec succès !');

            return $this->redirectToRoute('catalogManagement');
        }

        return $this->render('admin/create_article.html.twig', [
            'addArticleForm' => $addArticleForm->createView(),
            'editMode' => $article->getId() !== null,
            'image_path' => $article->getImage()
        ]);
    }

    /**
     * @Route("/admin/gerer_catalogue/delete/{id}", name="articleDelete" )
     */
    public function removeArticle(Article $article = null, EntityManagerInterface $entityManager, Filesystem $filesystem)
    {
        $filesystem = new Filesystem();
        $uploaded_file = $this->getParameter('upload_directory') . '/' . $article->getImage();

        $this->addFlash('success', 'L\'article a bien été supprimé.');

        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('catalogManagement');
    }


    /**
     * @Route("/admin/modifier-identifiants", name="manage_user")
     * @param User $user
     * @param Request $request
     * @return void
     */
    public function manageIDs(Request $request)
    {

        $adminForm = $this->createForm(AdminDataType::class);
        $adminForm->handleRequest($request);

        return $this->render('admin/manage_user_data.html.twig', [
            'adminForm' => $adminForm->createView()
        ]);
    }


    /**
     *@Route("/admin/gerer-galerie", name="manage_gallery")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Gallery $gallery
     * @return void
     */
    public function createGalleryItem(Request $request, EntityManagerInterface $entityManager, Gallery $gallery = null, GalleryRepository $galleryRepository)
    {

        $galleryForm = $this->createForm(GalleryType::class, $gallery);
        $galleryForm->handleRequest($request);

        if ($galleryForm->isSubmitted() && $galleryForm->isValid()) {

            $uploads_directory = $this->getParameter('upload_directory');

            $imagesData = $request->files->get("gallery")["images"];


            foreach ($imagesData as $imageData) {
                $gallery = new Gallery();
                $finalFile = md5(uniqid()) . "." . $imageData->guessExtension();
                $filename = $uploads_directory . '/' . $finalFile;

                $imageData->move($uploads_directory, $finalFile);
                $gallery->setImages($finalFile);

                $entityManager->persist($gallery);
                $entityManager->flush();
            }

            $this->addFlash('success', 'L\'ajout à la galerie a bien été effectué.');

            $this->redirectToRoute('gallery');
        }


        return $this->render('admin/manage_gallery.html.twig', [
            'galleryForm' => $galleryForm->createView(),
            'images' => $galleryRepository->findAll()
        ]);
    }

    /**
     * @Route("/admin/gerer-galerie/supprimer/{id}", name="removeFromGallery")
     *
     */
    public function removeFromGallery(Gallery $gallery = null, EntityManagerInterface $entityManager, Filesystem $filesystem)
    {
        $filesystem = new Filesystem();
        $uploaded_file = $this->getParameter('upload_directory') . '/' . $gallery->getImages();

        $entityManager->remove($gallery);
        $entityManager->flush();

        $this->addFlash('success', 'L\'image a bien été supprimée.');

        return $this->redirectToRoute('manage_gallery');
    }
}
