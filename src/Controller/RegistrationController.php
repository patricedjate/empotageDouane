<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, 
    UserAuthenticator $authenticator, EntityManagerInterface $entityManager,
    MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
              // ✅ Récupérer l'email de l'utilisateur
              $emailAddress = $user->getEmail();
            $email = (new TemplatedEmail())
            ->from(new Address('patricedjate8@gmail.com', 'Empotage Douanes Ivoiriennes'))
            ->to($emailAddress)
            ->subject('Confirmation d’inscription à la plateforme de gestion de la procédure d’empotage')
            ->text('Bonjour, ceci est un email de test.')
            ->html('<p>Madame, Monsieur,</p>

  <p>
    Nous avons le plaisir de vous confirmer votre inscription sur la plateforme de la Douane dédiée à la gestion de la procédure d’empotage.
  </p>

  <p>
    Pour l’attribution de vos droits d’accès, nous vous invitons à vous rapprocher de la
    <strong>Direction des Systèmes d’Information (DSI)</strong>, située à
    <strong>Treichville, en face du Commissariat Spécial du Port</strong>.
  </p>

  <p>
    L’équipe de la DSI se tient à votre disposition pour toute assistance technique relative à votre profil d’accès.
  </p>

  <p>
    Nous vous remercions pour votre collaboration et restons à votre écoute pour toute information complémentaire.
  </p>

  <p>
    Cordialement,<br>
    <strong>Direction des Systèmes d"Information</strong><br>
    Bureau Etude et Developpement<br>
    Douanes Ivoiriennes<br>
   +225 27 20 25 15 00
  </p>');
            $user->setNombreDossier(0);
            $entityManager->persist($user);
            $mailer->send($email);
            $entityManager->flush();
            
            $this->addFlash('success', 'Inscription effectué avec succèss. veuillez contacter l\'administrateur
             pour l\'attribution de vos droits d\'accès');
             return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
       
    }
}
