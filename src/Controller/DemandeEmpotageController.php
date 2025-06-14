<?php

namespace App\Controller;

use App\Entity\AgentVisite;
use App\Entity\RapportEmpotage;
use doctrine;
use App\Entity\User;
use App\Entity\Fiche;
use App\Service\PdfService;
use App\Form\FicheType;
use App\Repository\FicheRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
#[Route('/cda')]
class DemandeEmpotageController extends AbstractController
{
    #[Route('/demandeEmpotage', name: 'demandeEmpotage')]
    public function index(Request $request, 
    EntityManagerInterface $manager,
     UserRepository $repository,
     MailerInterface $mailer): Response
    {
        $fiche = new Fiche();
        $rapport = new RapportEmpotage;
        $agent = $repository->findOneByNombreDossier();
        $agentNbre = $agent->getNombreDossier();
        $NbreDossier =   $agentNbre+1;
        $nomAgent = $agent->getNom();
        $prenomAgent = $agent->getPrenom();
        $contactAgent = $agent->getContact();
        $agentEmailAddress = $agent->getEmail();
        $NbreDossier = strval($NbreDossier);
        $email = (new TemplatedEmail())
        ->from(new Address('patricedjate8@gmail.com', 'Empotage Douanes Ivoiriennes'))
        ->to($agentEmailAddress)
        ->subject('Transmission d’un nouveau dossier de suivi d’empotage')
        ->text('Bonjour, ceci est un email de test.')
        ->html('<p>
Monsieur / Madame  ,
</p>

<p>
Je vous informe que vous venez de recevoir un nouveau dossier relatif à une <strong>demande de suivi d’empotage</strong>.
</p>

<p>
Vous êtes prié(e) de bien vouloir en assurer le traitement dans les meilleurs délais, conformément aux procédures en vigueur.
</p>

<p>
Je reste à votre disposition pour tout renseignement complémentaire.
</p>

<p>
Cordialement,<br>
<strong>Direction des Systèmes d"Information</strong><br>
Bureau Etude et Developpement<br>
Douanes Ivoiriennes<br>
+225 27 20 25 15 00
</p>');
        $numfiche = "fiche".strval(random_int(1,10000));
        $form = $this->createForm(FicheType::class, $fiche);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fichiers = $form->get('fichiers')->getData();
        $nomsFichiers = [];
         foreach ($fichiers as $fichier) {
            $nom = uniqid() . '.' . $fichier->guessExtension();
            $fichier->move($this->getParameter('upload_directory'), $nom);
            $nomsFichiers[] = $nom;
        }
         $fiche->setFichiers($nomsFichiers);
            $fiche = $form->getData();
            $fiche->setUser($agent);
            $fiche->setStatut(false);
            $fiche->setCda($this->getUser());
            $fiche ->setNumFiche($numfiche);
            $agent->setNombreDossier($NbreDossier);
            $manager->persist($fiche);
            $rapport->setFiche($fiche);
            $rapport->setUser($agent);
            $rapport->setImages([""]);
            $manager->persist($rapport);
  $mailer->send($email);
            $manager->flush();
        
    }
    return $this->render('demande_empotage/index.html.twig',[
        'form' => $form->createView(),
    ]);
}
 #[Route('/ficheempotage', name: 'liste_fiche_cda')]
    public function liste(FicheRepository $ficheRepository): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $fiches = $ficheRepository->findAllFicheByCda($this->getUser());
        return $this->render('demande_empotage/listefiche.html.twig', [
            'fiches'=>$fiches
        ]);
    }
     #[Route('/imprimer-formulaire/{id}', name: 'app_imprimer_formulaire')]
    public function imprimerFormulaire(Fiche $fiche, PdfService $pdfService): Response
    {
        return $pdfService->generatePdf('/pdf.html.twig', [
            'fiche' => $fiche,
        ]);
    }

 #[isGranted('ROLE_CDA')]
    #[Security("is_granted('ROLE_CDA')")]
    #[Route('/{id}', name: 'cda_fiche_show')]
    public function show(Fiche $fiche): Response
    {
        return $this->render('demande_empotage/fiche.html.twig', [
            'fiche' => $fiche,
        ]);
    }
}
