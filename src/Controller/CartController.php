<?php

namespace App\Controller;

use App\Form\RentalType;
use App\Entity\Customer;
use App\Data\CartFunctions;
use Swift_Mailer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CartController extends AbstractController
{
    
    /**
     * @Route("/panier", name="cart_index")
     */
    public function cartIndex(CartFunctions $cartFunctions, Request $request)
    {

        $customer = new Customer();

        $addRentalForm = $this->createForm(RentalType::class, $customer);

        $addRentalForm->handleRequest($request);
     

        return $this->render('views/cart_index.html.twig', [
            'items' => $cartFunctions->getFullCart(),
            'total' => $cartFunctions->getTotal(),
            'rentalForm' => $addRentalForm->createView()
        ]);
    }


    /**
     * @Route("/panier/ajouter/{id}", name="cart_add")
     */
    public function addToCart($id, CartFunctions $cartFunctions)
    {
        $cartFunctions->addToCart($id);

        $this->addFlash('success', 'L\'article a bien été ajouté à votre panier');

        return $this->redirectToRoute('catalogue_main');
    }


    /**
     *@Route("/panier/retirer/{id}", name="cart_remove")
     */
    public function removeFromCart($id, CartFunctions $cartFunctions)
    {
        $cartFunctions->removeFromCart($id);

        return $this->redirectToRoute("cart_index");
    }

    /**
     *@Route("/panier/vider-panier", name="cart_clear")
     * @param CartFunctions $cartFunctions
     * @return void
     */    
    public function cleanCartAction(CartFunctions $cartFunctions)
    {
        $cartFunctions->emptyCartAction();

        return $this->redirectToRoute('cart_index');
    }

    /**
     *@Route("/panier/retirer-quantite/{id}", name="cart_remove_one")
     */
    public function removeOneFromCart($id, CartFunctions $cartFunctions)
    {
        $cartFunctions->removeOneFromCart($id);

        return $this->redirectToRoute("cart_index");
    }


    /**
     *@Route("/panier/ajouter-article/{id}", name="cart_add_one")
     */ 
    public function addOneToCart($id, CartFunctions $cartFunctions)
    {
        $cartFunctions->addOneToCart($id);

        return $this->redirectToRoute('cart_index');
    }


    /**
     *@Route("/panier/envoi-reservation", name="rental_validation")
     */
    public function rentalValidation(CartFunctions $cartFunctions, Swift_Mailer $mailer)
    {
        $rental_date_regex = "#^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$#";
        $phone_regex = "#^[0-9-+.]{10,15}$#";
        
        
        if (!preg_match($phone_regex, $_POST["rental"]['phoneNumber']))
        {    
            dd('Le numéro de téléphone saisi n\est pas valide !');
            
        } elseif (!preg_match($rental_date_regex, $_POST["rental"]['rentalDate']))
        {
            dd('La date n\'est pas valide !');
            
        } else {

            $customerData = $cartFunctions->retrieveCustomerData();

            // Création du mail à envoyer au client
            $customerMail = (new \Swift_Message('Nouvelle demande de réservation'))
            // Création de l'expéditeur
            ->setFrom('contact@mk-evenements.com')
            // Création du destinataire
            ->SetTo($customerData["email"])
            // Insertion du Logo MK
            
            // Création du message
            ->setBody(
                $this->renderView('mail/rental_customer_mail.html.twig', [
                    'customerData' => $customerData,
                    'items' => $cartFunctions->getFullCart(),
                    'totalCart' => $cartFunctions->getTotal()
                    ]),
                    'text/html'
                    )
                ;
            $mailer->send($customerMail);


            // Création du mail à envoyer à l'admin
            $mkMail = (new \Swift_Message('Nouvelle demande de réservation'))
            // Création de l'expéditeur
            ->setFrom($customerData["email"])
            // Création du destinataire
            ->SetTo('contact@mk-evenements.com')
            // Insertion du Logo MK
            
            // Création du message
            ->setBody(
                $this->renderView('mail/rental_mk_mail.html.twig', [
                    'customerData' => $customerData,
                    'items' => $cartFunctions->getFullCart(),
                    'totalCart' => $cartFunctions->getTotal()
                    ]),
                    'text/html'
                    )
                ;
            $mailer->send($mkMail);

            return $this->render('views/cart_validation.html.twig', [
                'customerData' => $customerData,
                'items' => $cartFunctions->getFullCart(),
                'totalCart' => $cartFunctions->getTotal(),
                'emptyCart' => $cartFunctions->emptyCartAction()
                ]);
        }
    }
    
}
