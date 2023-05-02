<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Fournisseur;
use App\Entity\Produit;
use App\Repository\CategorieRepository;
use App\Repository\FournisseurRepository;
use App\Repository\ProduitRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class ProjetController extends AbstractController
{
    #[Route('/projet/produit', name: 'app_projet_produits', methods:'GET')]
    public function index(ProduitRepository $produitRepository): JsonResponse
    {
        $produits = $produitRepository->findAll();
        $data = [];
  
        foreach ($produits as $produit) {
           $data[] = [
               'id' => $produit->getId(),
               'nom' => $produit->getNom(),
               'code' => $produit->getCode(),
               'prix_achat' => $produit->getPrixAchat(),
               'prix_vente' => $produit->getPrixVente(),
               'date_peremtion' => $produit->getDatePeremtion(),
               'categorie' => $produit->getCategorie()->getNom(),
               'fournisseur' => $produit->getFournisseur()->getNom()
           ];
        }
        return $this->json($data);
    }

    #[Route('/projet/categorie', name: 'app_projet_categories', methods:'GET')]
    public function index_cat(CategorieRepository $categorieRepository): JsonResponse
    {
        $categories = $categorieRepository->findAll();
        $data = [];
  
        foreach ($categories as $categorie) {
           $data[] = [
               'id' => $categorie->getId(),
               'nom' => $categorie->getNom()
           ];
        }
        return $this->json($data);
    }

    #[Route('/projet/fournisseur', name: 'app_projet_fournisseurs', methods:'GET')]
    public function indexFour(FournisseurRepository $fournisseurRepository): JsonResponse
    {
        $fournisseurs = $fournisseurRepository->findAll();
        $data = [];
  
        foreach ($fournisseurs as $fournisseur) {
           $data[] = [
               'id' => $fournisseur->getId(),
               'nom' => $fournisseur->getNom(),
               'adresse' => $fournisseur->getAdresse(),
               'telephone' => $fournisseur->getTelephone(),
           ];
        }
        return $this->json($data);
    }

    /**
     * @Route("/projet/produit", name="project_produit_new", methods={"POST"})
     */
    public function new(EntityManagerInterface $doctrine, Request $request, CategorieRepository $categorieRepository, FournisseurRepository $fournisseurRepository): Response
    {
  
        $produit = new Produit();
        $produit->setNom($request->request->get('nom'));
        $produit->setCode($request->request->get('code'));
        $produit->setPrixAchat(floatval($request->request->get('prix_achat')));
        $produit->setPrixVente($request->request->get('prix_vente'));
        $produit->setDatePeremtion(new \DateTime($request->request->get('date_peremption')));
        $produit->setDateFabrication(new \DateTime($request->request->get('date_fabrication')));
        $categorie = $categorieRepository->find($request->request->get('categorie'));
        $produit->setCategorie($categorie);
        $fournisseur = $fournisseurRepository->find($request->request->get('fournisseur'));
        $produit->setFournisseur($fournisseur);
        $doctrine->persist($produit);
        $doctrine->flush();
  
        return $this->json('Created new produit successfully with id ' . $produit->getId());
    }

    /**
     * @Route("/projet/categorie", name="project_categorie_new", methods={"POST"})
     */
    public function new_Categorie(EntityManagerInterface $doctrine, Request $request): Response
    {
  
        $categorie = new Categorie();
        $categorie->setNom($request->request->get('nom'));
  
        $doctrine->persist($categorie);
        $doctrine->flush();
  
        return $this->json('Created new catégorie successfully with id ' . $categorie->getId());
    }

    /**
     * @Route("/projet/fournisseur", name="project_fournisseur_new", methods={"POST"})
     */
    public function new_Fournisseur(EntityManagerInterface $doctrine, Request $request): Response
    {
  
        $fournisseur = new Fournisseur();
        $fournisseur->setNom($request->request->get('nom'));
        $fournisseur->setAdresse($request->request->get('adresse'));
        $fournisseur->setTelephone($request->request->get('telephone'));
  
        $doctrine->persist($fournisseur);
        $doctrine->flush();
  
        return $this->json('Created new fournissuer successfully with id ' . $fournisseur->getId());
    }

    /**
     * @Route("/projet/produit/{id}", name="project_show", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {
  
        if (!$produit) {
  
            return $this->json('No Produit found for id' . $produit, 404);
        }
  
        $data =  [
            'id' => $produit->getId(),
            'nom' => $produit->getNom(),
            'code' => $produit->getCode(),
            'prix_achat' => $produit->getPrixAchat(),
            'prix_vente' => $produit->getPrixVente(),
            'date_peremtion' => $produit->getDatePeremtion(),
            'date_fabrication' => $produit->getDateFabrication(),
            'categorie' => $produit->getCategorie()->getNom(),
            'fournisseur' => $produit->getFournisseur()->getNom(),
        ];
          
        return $this->json($data);
    }
  
    /**
     * @Route("/projet/categorie/{id}", name="project_categorie_show", methods={"GET"})
     */
    public function show_Categorie(Categorie $categorie): Response
    {
        //$project = $doctrine->getRepository(Project::class)->find($id);
  
        if (!$categorie) {
  
            return $this->json('No Catégorie found for id' . $categorie, 404);
        }
  
        $data =  [
            'id' => $categorie->getId(),
            'nom' => $categorie->getNom(),
        ];
          
        return $this->json($data);
    }
    /**
     * @Route("/projet/fournisseur/{id}", name="project_show_fournisseur", methods={"GET"})
     */
    public function show_Fournisseur(Fournisseur $fournisseur): Response
    {
        //$project = $doctrine->getRepository(Project::class)->find($id);
  
        if (!$fournisseur) {
  
            return $this->json('No Catégorie found for id' . $fournisseur, 404);
        }
  
        $data =  [
            'id' => $fournisseur->getId(),
            'nom' => $fournisseur->getNom(),
            'adresse' => $fournisseur->getAdresse(),
            'telephone' => $fournisseur->getTelephone(),
        ];
          
        return $this->json($data);
    }

    /**
     * @Route("/projet/produit/{id}", name="project_edit", methods={"PUT", "PATCH"})
     */
    public function edit(EntityManagerInterface $entityManager, Request $request, Produit $produit, CategorieRepository $categorieRepository, FournisseurRepository $fournisseurRepository): Response
    {
  
        if (!$produit) {
            return $this->json('No produit found for id' . $produit, 404);
        }
  
        $produit->setNom($request->request->get('nom'));
        $produit->setCode($request->request->get('code'));
        $produit->setPrixAchat(floatval($request->request->get('prix_achat')));
        $produit->setPrixVente($request->request->get('prix_vente'));
        $produit->setDatePeremtion(new \DateTime($request->request->get('date_peremption')));
        $produit->setDateFabrication(new \DateTime($request->request->get('date_fabrication')));
        $categorie = $categorieRepository->find($request->request->get('categorie'));
        $produit->setCategorie($categorie);
        $fournisseur = $fournisseurRepository->find($request->request->get('fournisseur'));
        $produit->setFournisseur($fournisseur);
        $entityManager->flush();
  
        $data =  [
            'id' => $produit->getId(),
            'nom' => $produit->getNom(),
            'code' => $produit->getCode(),
            'prix_achat' => $produit->getPrixAchat(),
            'prix_vente' => $produit->getPrixVente(),
            'date_peremtion' => $produit->getDatePeremtion(),
            'categorie' => $produit->getCategorie()->getNom(),
            'fournisseur' => $produit->getFournisseur()->getNom()
        ];
          
        return $this->json($data);
    }
    /**
     * @Route("/projet/categorie/{id}", name="project_edit_categorie", methods={"PUT", "PATCH"})
     */
    public function edit_categorie(EntityManagerInterface $entityManager, Request $request, Categorie $projet): Response
    {
  
        if (!$projet) {
            return $this->json('No project found for id' . $projet, 404);
        }
  
        $projet->setNom($request->request->get('nom'));
        $entityManager->flush();
  
        $data =  [
            'id' => $projet->getId(),
            'name' => $projet->getNom(),
        ];
          
        return $this->json($data);
    }

    /**
     * @Route("/projet/fournisseur/{id}", name="project_edit_categorie", methods={"PUT", "PATCH"})
     */
    public function edit_fournisseur(EntityManagerInterface $entityManager, Request $request, Fournisseur $projet): Response
    {
  
        if (!$projet) {
            return $this->json('No project found for id' . $projet, 404);
        }
  
        $projet->setNom($request->request->get('nom'));
        $projet->setAdresse($request->request->get('adresse'));
        $projet->setTelephone($request->request->get('telephone'));
        $entityManager->flush();
  
        $data =  [
            'id' => $projet->getId(),
            'nom' => $projet->getNom(),
            'adresse' => $projet->getAdresse(),
            'telephone' => $projet->getTelephone(),
        ];
          
        return $this->json($data);
    }
  
    /**
     * @Route("/projet/produit/{id}", name="project_delete_produit", methods={"DELETE"})
     */
    public function delete_produit(EntityManagerInterface $entityManager, Produit $projet): Response
    {
  
        if (!$projet) {
            return $this->json('No Produit found for id' . $projet, 404);
        }
  
        $entityManager->remove($projet);
        $entityManager->flush();
  
        return $this->json('Deleted a Produit successfully with id ' . $projet);
    }

    /**
     * @Route("/projet/categorie/{id}", name="project_delete_categorie", methods={"DELETE"})
     */
    public function delete_categorie(EntityManagerInterface $entityManager, Categorie $projet): Response
    {
  
        if (!$projet) {
            return $this->json('No project found for id' . $projet, 404);
        }
  
        $entityManager->remove($projet);
        $entityManager->flush();
  
        return $this->json('Deleted a Categorie successfully with id ' . $projet);
    }

    /**
     * @Route("/projet/fournisseur/{id}", name="project_delete_fournisseur", methods={"DELETE"})
     */
    public function delete_fournisseur(EntityManagerInterface $entityManager, Categorie $projet): Response
    {
  
        if (!$projet) {
            return $this->json('No project found for id' . $projet, 404);
        }
  
        $entityManager->remove($projet);
        $entityManager->flush();
  
        return $this->json('Deleted a Categorie successfully with id ' . $projet);
    }
}
