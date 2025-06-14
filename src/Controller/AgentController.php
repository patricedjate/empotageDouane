<?php

namespace App\Controller;

use App\Entity\Fiche;

use App\Entity\RapportEmpotage;
use App\Form\RapportEmpotageType;
use App\Repository\FicheRepository;
use App\Repository\RapportEmpotageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/agent')]
class AgentController extends AbstractController
{
    #[isGranted('ROLE_AGENT')]
    #[Security("is_granted('ROLE_AGENT')")]
    #[Route('/', name: 'agentIndex')]
    public function index(FicheRepository $ficheRepository): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $nombreTraite = count($ficheRepository->findAllFicheEffectueByUser($this->getUser()));
        $nombreNonTraite = count($ficheRepository->findAllFicheByUser($this->getUser()));
        return $this->render('agent/index.html.twig',[
            "nombreFicheTraite"=>$nombreTraite,
            "nombreFicheNonTraite"=>$nombreNonTraite,
        ]);
    }
    #[Security("is_granted('ROLE_AGENT')")]
    #[isGranted('ROLE_AGENT')]
    #[Route('/ListeFiche', name: 'agent_ListeFiche')]
    public function agent(FicheRepository $repository): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $fiches = $repository->findAllFicheByUser($this->getUser());
        return $this->render('agent/ListeFiche.html.twig',[
            'fiches'=>$fiches
        ]);
    }
    #[isGranted('ROLE_AGENT')]
    #[Security("is_granted('ROLE_AGENT')")]
    #[Route('/ListeFicheEffectue', name: 'agent_ListeFicheEffectue')]
    public function effectue(FicheRepository $repository): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $fiches = $repository->findAllFicheEffectueByUser($this->getUser()); 
        return $this->render('agent/ListeFicheEffectue.html.twig',[
            'fiches'=>$fiches
        ]);
    }
    #[isGranted('ROLE_AGENT')]
    #[Security("is_granted('ROLE_AGENT')")]
    #[Route('/{id}', name: 'fiche_show')]
    public function show(Fiche $fiche): Response
    {
        return $this->render('agent/fiche/show.html.twig', [
            'fiche' => $fiche,
        ]);
    }
    #[isGranted('ROLE_AGENT')]
    #[Security("is_granted('ROLE_AGENT')")]
    #[Route('/RapportEmpotage/edit/{id}', name:'edit_RapportEmpotage')]
    public function edit(Request $request,SluggerInterface $slugger, RapportEmpotage $Rapport, EntityManagerInterface $manager, FicheRepository $repository): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $fiche = $repository->findFiche($request->attributes->get('id'));
        $form = $this->createForm(RapportEmpotageType::class, $Rapport);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
             $imageFiles = $form->get('images')->getData();
        $imageNames = [];

        if ($imageFiles) {
            foreach ($imageFiles as $image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('uploads_directory'), // définis dans services.yaml
                        $newFilename
                    );
                    $imageNames[] = $newFilename;
                } catch (FileException $e) {
                    // Gère l'erreur
                }
            }
        }
            
            $rapport = $form->getData();
            $rapport->setImages($imageNames);
            $fiche->setStatut(true);
            $rapport->setDate(new \DateTimeImmutable);
            $manager->persist($rapport);
            $manager->flush();
            $this->addFlash('success', 'Rapport redigé avec succès!');
            return $this->redirectToRoute('agent_ListeFicheEffectue');
        }
        return $this->render('agent/rapport.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
   


