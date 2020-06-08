<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Terrain;
use App\Entity\Reservation;
use App\Entity\Agence;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class EspaceClientController extends AbstractController
{
    /**
     * @Route("/espace_client", name="espace_client") 
     */

    public function index()
    {
        $user = $this->getUser();
        return $this->render('espace_client/index.html.twig', [
            'controller_name' => 'EspaceClientController','utilisateur'=>$user
        ]);
    }

    /**
     * @Route("/espace_client/reservation/{id}", name="espace_client_reservation")
     */

    public function reservation($id,Request $request,EntityManagerInterface $manager)
    {
        
        $terrain = $this->getDoctrine()
        ->getRepository(Terrain::class)
        ->find($id);
        
        $agence=$terrain->getAgence();
        if ($request->isMethod('post')) 
        {
            
            $date=$request->request->get('date');
            $heure=$request->request->get('heure');
            $dateh = new \DateTime($date.' '.$heure.':00:00');
            
            $reservation=new Reservation();
            
            $reservation->setDate($dateh)
                        ->setStatus('wait')
                        ->setTerrain($terrain)
                        ->setClient( $this->getUser()->getClient());
            $manager->persist($reservation);
            $manager->flush(); 
            return $this->render('espace_client/reservation.html.twig', [
                'terrain' => $terrain,'agence'=>$agence,'notification' => 'success','contenu'=>'Demande De Reservation effectuee' 
            ]);
          
        }
        else
        {

            return $this->render('espace_client/reservation.html.twig', [
                'terrain' => $terrain,'agence'=>$agence
            ]);
        }

    }
    /**
     * @Route("/espace_client/modifier_photo", name="espace_client_modifier_photo")
     */
    public function modifier_photo(Request $request)
    {
        if($request->isMethod('post'))
        {
            $client=$this->getUser()->getClient();

            $file = $request->files->get('photo');
            
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory'),$fileName);
            $client->setPhoto($fileName);
            $this->getDoctrine()->getManager()->flush();   
                

            return $this->render('espace_client/modifier_photo.html.twig',array('notification' => 'success','contenu'=>'modification terminée avec succès' ));
          
        }
        else
        {
            return $this->render('espace_client/modifier_photo.html.twig', [
                'controller_name' => 'EspaceAgenceController',
            ]);
        }
       
    }



        /**
     * @Route("/espace_client/mes_reservation", name="espace_client_mes_reservation")
     */
    public function mes_reservation(Request $request)
    {
            $entityManager = $this->getDoctrine()->getManager();
            $client = $this->getUser()->getClient();
        
            
            $req=" SELECT r FROM App\Entity\Reservation r where r.client= :client ";
            $query = $entityManager->createQuery($req)->setParameters(array('client'=>$client));
                

            $reservations= $query->execute();

            return $this->render('espace_client/mes_reservation.html.twig', [
                'reservations' => $reservations,
            ]);
    }



     /**
     * @Route("/espace_client/nos_agences", name="espace_client_nos_agences")
     */
    public function nos_agences(Request $request)
    {

        
        if ($request->isMethod('post')) {
            
            $entityManager = $this->getDoctrine()->getManager();
            $nom=$request->request->get('nom');
            $req='';
            if($nom != NULL )
            {
                $req=" SELECT p FROM App\Entity\Agence p WHERE  p.nom LIKE :nom ";
                $query = $entityManager->createQuery($req)->setParameters(array('nom'=> '%'.$nom.'%'));
            }
            else
            {
                
                $req=" SELECT p FROM App\Entity\Agence p  ";
                $query = $entityManager->createQuery($req);
            }

            $agence= $query->execute();
        }
        else
        {
            
        $repository = $this->getDoctrine()->getRepository(Agence::class);
        $agence = $repository->findAll();
        }        
        
        return $this->render('espace_client/nos_agences.html.twig', [
            'agences' => $agence,
        ]);
    }




      /**
     * @Route("/espace_client/nos_terrains", name="espace_client_nos_terrains")
     */
    public function nos_terrains(Request $request)
    {

        if ($request->isMethod('post')) {
            
            $entityManager = $this->getDoctrine()->getManager();
            $nom=$request->request->get('nom');
            $categorie=$request->request->get('categorie');
            $req='';
            if($nom != NULL &&  $categorie!= NULL)
            {
            $req=" SELECT p FROM App\Entity\Terrain p WHERE  p.nom LIKE :nom AND p.categorie LIKE :categorie ";
            $query = $entityManager->createQuery($req)->setParameters(array('nom'=> '%'.$nom.'%', 'categorie' => $categorie));
            }
            elseif($nom != NULL &&  $categorie== NULL)
            {
                $req=" SELECT p FROM App\Entity\Terrain p WHERE  p.nom LIKE :nom ";
                $query = $entityManager->createQuery($req)->setParameters(array('nom'=> '%'.$nom.'%'));
            }
            
            elseif($nom == NULL &&  $categorie!= NULL)
            {
                $req=" SELECT p FROM App\Entity\Terrain p WHERE  p.categorie LIKE :categorie ";
                $query = $entityManager->createQuery($req)->setParameters(array('categorie' => $categorie));
            }
            else
            {
                
                $req=" SELECT p FROM App\Entity\Terrain p  ";
                $query = $entityManager->createQuery($req);
            }



            $terrain= $query->execute();
        }
        else
        {
            $repository = $this->getDoctrine()->getRepository(Terrain::class);
            $terrain = $repository->findAll();
        }        
        return $this->render('espace_client/nos_terrains.html.twig', [
            'terrains' => $terrain,
        ]);
    }


}
