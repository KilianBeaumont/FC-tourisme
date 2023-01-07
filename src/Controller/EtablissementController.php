<?php

namespace App\Controller;

use App\Repository\EtablissementRepository;
use App\Repository\UserRepository;
use ContainerREZiCid\getKnpPaginatorService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtablissementController extends AbstractController
{
    private EtablissementRepository $etablissementRepository;

    /**
     * @param EtablissementRepository $etablissementRepository
     */
    public function __construct(EtablissementRepository $etablissementRepository)
    {
        $this->etablissementRepository = $etablissementRepository;
    }


    #[Route('/etablissements', name: 'app_etablissements')]
    public function allEtablissement(PaginatorInterface $paginator, Request $request): Response
    {

        $etablissements = $paginator->paginate(
            $this->etablissementRepository->findBy(["actif"=>'true'],['nom'=>'ASC']),
            $request->query->getInt("page",1),
            10
        );

        return $this->render('etablissement/index.html.twig', [
            'Etablissements' => $etablissements,
        ]);
    }
    #[Route('/etablissement/{slug}', name: 'app_etablissement_slug')]
    public function EtablissementSlug($slug): Response
    {
        $etablissement = $this->etablissementRepository->findOneBy(["slug"=>$slug]);
        return $this->render('etablissement/etablissement.html.twig', [
            "Etablissement" => $etablissement
        ]);

    }

    #[Route('/etablissement/favoris/{slug}', name: 'app_etablissement_favoris_slug')]
    public function EtablissementFavorisation($slug, UserRepository $userRepository, EntityManagerInterface $manager): Response
    {
        $utilisateur = $userRepository->find($this->getUser());
        $etablissement = $this->etablissementRepository->findOneBy(["slug"=>$slug]);
        if (in_array($etablissement,$utilisateur->getEtablissementsFavoris()->toArray())){
            $etablissement->removeUser($utilisateur);
        }else{
            $etablissement->addUser($utilisateur);
        }
        $manager->persist($etablissement);
        $manager->flush();
        return $this->redirectToRoute('app_etablissements');

    }



    #[Route('/etablissement/favoris', name: 'app_etablissement_favoris')]
    public function EtablissementFavoris(): Response
    {
        $etablissement = $this->etablissementRepository->findOneBy();
        return $this->render('etablissement/favoris.html.twig', [
            "Etablissement" => $etablissement
        ]);

    }
}
