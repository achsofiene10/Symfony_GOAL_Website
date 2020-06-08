<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Agence;
use App\Entity\Utilisateur;
use App\Entity\Terrain;
use App\Entity\Reservation;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;

class EspaceAgenceController extends AbstractController
{
    /**
     * @Route("/espace_agence", name="espace_agence")
     */
    public function index()
    {
        return $this->render('espace_agence/index.html.twig', [
            'controller_name' => 'EspaceAgenceController',
        ]);
    }

    /**
     * @Route("/espace_agence/modifier_adresse", name="espace_agence_modifier_adresse")
     */
    public function modifier_adresse(Request $request)
    {
        if($request->isMethod('post'))
        {
            $user = $this->getUser();
            $agenceRepo = $this->getDoctrine()->getRepository(Agence::class);

            $agence = $agenceRepo->find($user->getAgence()->getId());

            $agence->setLatitude($request->request->get('latitude'));
            $agence->setLongitude($request->request->get('longitude'));

            $this->getDoctrine()->getManager()->flush(); 
            return $this->render('espace_agence/modifier_adresse.html.twig',array('notification' => 'success','contenu'=>'modification terminée avec succès' ));

       
        }
        else
        {
            return $this->render('espace_agence/modifier_adresse.html.twig', [
                'controller_name' => 'EspaceAgenceController',
            ]);
        }

    }


    /**
     * @Route("/espace_agence/modifier_num_tel", name="espace_agence_modifier_num_tel")
     */
    public function modifier_num_tel(Request $request)
    {
        if($request->isMethod('post'))
        {
            $agence=$this->getUser()->getAgence();
                    
            $agence->setNumTel($request->request->get('num_tel'));           
            
            $this->getDoctrine()->getManager()->flush();   
                
            $contenu="photo chager avec success";
            return $this->render('espace_agence/modifier_num_tel.html.twig',array('notification' => 'success','contenu'=>'modification terminée avec succès' ));
               
        }
        else
        {
            return $this->render('espace_agence/modifier_num_tel.html.twig', [
                'controller_name' => 'EspaceAgenceController',
            ]);
        }
        
    }

   
    


     /**
     * @Route("/espace_agence/modifier_photo", name="espace_agence_modifier_photo")
     */
    public function modifier_photo(Request $request)
    {
        if($request->isMethod('post'))
        {
            $agence=$this->getUser()->getAgence();

            $file = $request->files->get('photo');
            
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory'),$fileName);
            $agence->setPhoto($fileName);

            $this->getDoctrine()->getManager()->flush();   
                
                

            return $this->render('espace_agence/modifier_photo.html.twig',array('notification' => 'success','contenu'=>'modification terminée avec succès' ));
          
        }
        else
        {
            return $this->render('espace_agence/modifier_photo.html.twig', [
                'controller_name' => 'EspaceAgenceController',
            ]);
        }
       
    }

    
    
      /**
     * @Route("/espace_agence/modifier_terrain", name="espace_agence_modifier_terrain")
     */
    public function modifier_terrain(Request $request)
    {
        
        $agence=$this->getUser()->getAgence();
        if ($request->isMethod('post')) {
            
            $entityManager = $this->getDoctrine()->getManager();
            $nom=$request->request->get('nom');
            $categorie=$request->request->get('categorie');
            $req='';
            if($nom != NULL &&  $categorie!= NULL)
            {
            $req=" SELECT p FROM App\Entity\Terrain p WHERE  p.nom LIKE :nom AND p.categorie LIKE :categorie AND p.agence= :agence";
            $query = $entityManager->createQuery($req)->setParameters(array('nom'=> '%'.$nom.'%', 'categorie' => $categorie,'agence'=>$agence));
            }
            elseif($nom != NULL &&  $categorie== NULL)
            {
                $req=" SELECT p FROM App\Entity\Terrain p WHERE  p.nom LIKE :nom AND p.agence= :agence";
                $query = $entityManager->createQuery($req)->setParameters(array('nom'=> '%'.$nom.'%','agence'=>$agence));
            }
            
            elseif($nom == NULL &&  $categorie!= NULL)
            {
                $req=" SELECT p FROM App\Entity\Terrain p WHERE  p.categorie LIKE :categorie AND p.agence= :agence";
                $query = $entityManager->createQuery($req)->setParameters(array('categorie' => $categorie,'agence'=>$agence));
            }
            else
            {
                
                $req=" SELECT p FROM App\Entity\Terrain p  where p.agence= :agence";
                $query = $entityManager->createQuery($req)->setParameters(array('agence'=>$agence));
            }



            $terrain= $query->execute();
        }
        else
        {
       
        $repository = $this->getDoctrine()->getRepository(Terrain::class);
        $terrain = $repository-> findBy(['agence' => $agence]);
        }

        return $this->render('espace_agence/modifier_terrain.html.twig', [
            'terrains' => $terrain,
        ]);
    }


      /**
     * @Route("/espace_agence/ajouter_terrain", name="espace_agence_ajouter_terrain")
     */
    public function ajouter_terrain(Request $request,EntityManagerInterface $manager)
    {   

        if($request->isMethod('post'))
        {
            $terrain=new Terrain();

            $terrain->setNom($request->request->get('nom'))
                    ->setLargeur($request->request->get('largeur'))
                    ->setLongueur($request->request->get('longueur'))
                    ->setCategorie($request->request->get('categorie'))
                    ->setPrix($request->request->get('prix'))
                    ->setPhoto($request->request->get('photo'))
                    ->setAgence($this->getUser()->getAgence());

            $file = $request->files->get('photo');
                    
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory'),$fileName);
            $terrain->setPhoto($fileName);
            
                
            $manager->persist($terrain);
            $manager->flush();  
                
                
                
            $contenu="ajout de terrain avec success";
                
                
            return $this->render('espace_agence/ajouter_terrain.html.twig',array('notification' => 'success','contenu'=>$contenu ));
          

        }
        else
        {
            return $this->render('espace_agence/ajouter_terrain.html.twig', [
                'controller_name' => 'EspaceAgenceController',
            ]);
        }
       
    }

      /**
     * @Route("/espace_agence/mes_reservation", name="espace_agence_mes_reservation")
     */
    public function mes_reservation(Request $request)
    {
            $entityManager = $this->getDoctrine()->getManager();
            $agence = $this->getUser()->getAgence();
        
            
            $req=" SELECT r FROM App\Entity\Reservation r, App\Entity\Terrain t, App\Entity\Agence a where t.agence= :agence AND r.terrain=t";
            $query = $entityManager->createQuery($req)->setParameters(array('agence'=>$agence));
                

            $reservations= $query->execute();

            return $this->render('espace_agence/mes_reservation.html.twig', [
                'reservations' => $reservations,
            ]);
    }
    
         /**
     * @Route("/espace_agence/accept_reservation/{id}", name="espace_agence_accept_reservation")
     */
    public function accept_reservation($id,Request $request)
    {
            $repository = $this->getDoctrine()->getRepository(Reservation::class);
            $reservation = $repository->find($id);

            $reservation->setStatus('accepted');
            $this->getDoctrine()->getManager()->flush(); 
            
                    
            return $this->redirectToRoute('espace_agence_mes_reservation');
    }

    
         /**
     * @Route("/espace_agence/refuse_reservation/{id}", name="espace_agence_refuse_reservation")
     */
    public function refuse_reservation($id,Request $request)
    {
            $repository = $this->getDoctrine()->getRepository(Reservation::class);
            $reservation = $repository->find($id);
            
            $reservation->setStatus('refused');
            $this->getDoctrine()->getManager()->flush(); 
            
                    
            return $this->redirectToRoute('espace_agence_mes_reservation');
    }


}
