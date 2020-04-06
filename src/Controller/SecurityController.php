<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminDataType;
use App\Form\ConnexionType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/connexion/create_admin", name="admin_creation")
     */
    public function adminCreation(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {

        $user = new User();
        $form = $this->createForm(ConnexionType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) 
        {
            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('admin_connexion');
        }

        return $this->render('views/admin_creation.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/connexion", name="admin_connexion")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        
        $lastUserLog = $authenticationUtils->getLastUsername();
     
            return $this->render('views/connexion.html.twig', [
                'last_user' => $lastUserLog,
                'error' => $error,
            ]);
    }


    /**
     * @Route("/admin/main", name="mainAdmin")
     */
    public function mainAdmin()
    {
        return $this->render('admin/main_admin.html.twig');
    }


    /**
     *@Route("/admin/changer-mot-de-passe", name="change_password")
     * @param Request $request
     * @param User $user
     * @return void
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
    {

        $user = $this->getUser();

    	$adminForm = $this->createForm(AdminDataType::class, $user);
        $adminForm->handleRequest($request);

        if ($adminForm->isSubmitted() && $adminForm->isValid()) 
        {

            $oldPassword = $request->request->get('admin_data')['old_password'];

            if ($encoder->isPasswordValid($user, $oldPassword)) 
            {
                $newEncodedPassword = $encoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($newEncodedPassword);   

                $manager->persist($user);
                $manager->flush();

                $this->addFlash('notice', 'Votre mot de passe à bien été modifié');

            } else {

                $adminForm->addError(new FormError('Erreur d\'identification'));

            }

        }
 
        return $this->render('admin/manage_user_data.html.twig', [
            'adminForm' => $adminForm->createView()
        ]);
    }

    
    /**
     * @Route("/deconnexion", name="admin_deconnexion")
     */
    public function logout() {}
    
}
