<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Agence;
use App\Entity\Utilisateur;
use App\Entity\Client;
use App\Entity\Terrain;
use App\Entity\Note;
use App\Repository\NoteRepository;
use Symfony\Flex\Response;

class EspacePublicController extends AbstractController
{   




      /**
     * @Route("/", name="accueil")
     */
    public function accueil()
    {
        return $this->render('espace_public/accueil.html.twig');
    }

    
    /**
     * @Route("/nos_terrains", name="espace_public_nos_terrains")
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


        
        
        return $this->render('espace_public/nos_terrains.html.twig', [
            'terrains' => $terrain,
        ]);
    }




     /**
     * @Route("/nos_agences", name="espace_public_nos_agences")
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
        
        return $this->render('espace_public/nos_agences.html.twig', [
            'agences' => $agence,
        ]);
    }




    
     /**
     * @Route("/nos_tournois", name="espace_public_nos_tournois")
     */
    public function nos_tournois()
    {
        return $this->render('espace_public/nos_tournois.html.twig');
    }
    
      /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription()
    {
        return $this->render('espace_public/inscription.html.twig');
    }


      /**
     * @Route("/inscription/inscription_client", name="espace_public_inscription_client")
     */
    public function inscription_client(Request $request,EntityManagerInterface $manager,UserPasswordEncoderInterface $passwordEncoder)
    {   
        if($request->isMethod('post'))
        {
             
            $repository=$this->getDoctrine()->getRepository(Utilisateur::class);
            $utilisateur1=$repository->findOneBy(['email' => ($request->request->get('email'))]);
            if($utilisateur1==null)
            {
                $utilisateur=new Utilisateur();
                $utilisateur->setEmail($request->request->get('email'))
                            ->setMotPasse($request->request->get('mot_passe'))
                            ->setRole('ROLE_CLIENT');
                    
                $client=new Client();
                
                $client->setPrenom($request->request->get('prenom'))
                        ->setNom($request->request->get('nom'))
                        ->setSexe($request->request->get('sexe'))
                        ->setDateNaissance(new \DateTime($request->request->get('date_naissance')));

                if($client->getSexe()=="homme")
                {
                    $client->setPhoto("boy.svg");
                }
                else
                {
                    $client->setPhoto("girl.svg");
                }

                $utilisateur->setMotPasse($passwordEncoder->encodePassword($utilisateur,$utilisateur->getMotPasse() )  );

                $utilisateur->setClient($client);
                $manager->persist($client);
                $manager->persist($utilisateur);
                $manager->flush();  
                
                $notification="success";
                $contenu="ajout avec success";
                
            }
            else
            {
                
                $notification="danger";
                $contenu="Email deja existe";         
            }
            return $this->render('espace_public/inscription_client.html.twig',array('notification' => $notification,'contenu'=>$contenu ));
        
        }
        else
        {
            return $this->render('espace_public/inscription_client.html.twig');
        }
    }


        /**
     * @Route("/inscription/inscription_agence", name="espace_public_inscription_agence")
     */
    public function inscription_agence(Request $request,EntityManagerInterface $manager,UserPasswordEncoderInterface $passwordEncoder)
    {
        if($request->isMethod('post'))
        {
            $repository=$this->getDoctrine()->getRepository(Utilisateur::class);
            $utilisateur1=$repository->findOneBy(['email' => ($request->request->get('email'))]);
    
            $repositorya=$this->getDoctrine()->getRepository(Agence::class);
            $agence1=$repositorya->findOneBy(['matriculeFiscale' => ($request->request->get('matricule_fiscale'))]);
    
            if($utilisateur1==null and $agence1==null)
            {
                $utilisateur=new Utilisateur();
                $utilisateur->setEmail($request->request->get('email'))
                            ->setMotPasse($request->request->get('mot_passe'))
                            ->setRole('ROLE_AGENCE');
    
                $agence=new Agence();
                $agence->setMatriculeFiscale($request->request->get('matricule_fiscale'));
                $agence->setNom($request->request->get('nom_agence'));
                
                $agence->setPhoto("agence_hover.jpg");
                $agence->setNumTel($request->request->get('num_tel'));
               
    
                $notification="wait";
                $contenu="Vous recevez un <a href='https://mail.google.com' target='_blank'>mail</a> lorsque les informations saisies sont v�rifi�es";
    
                
                $utilisateur->setMotPasse($passwordEncoder->encodePassword($utilisateur,$utilisateur->getMotPasse())  );
    
                $utilisateur->setAgence($agence);
    
                $manager->persist($agence);
                $manager->persist($utilisateur);
                
                $manager->flush();          
                
            }
            else
            {   
                
                $notification="danger";
    
                if($utilisateur1<>null)
                {
                    $contenu="Email deja existe";         
    
                }
                else
                {
                    $contenu="N° de matricule fiscale deja existe";  
                }               
            }
            
            return $this->render('espace_public/inscription_agence.html.twig',array('notification' => $notification,'contenu'=>$contenu ));  
        }
        else
        {
            return $this->render('espace_public/inscription_agence.html.twig');
        }
        
    }



    /**
     * @Route("/connexion", name="espace_public_connexion")
     */
    public function connexion(AuthenticationUtils $authenticationUtils)
    {
        
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('espace_public/connexion.html.twig', [
            'error' => $error
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login()
    {   
        
        return $this->redirectToRoute('espace_client');

    }

 /**
     * @Route("/after_login_route", name="after_login_route")
     */
    public function after_login_route_name()
    {   
        $user = $this->getUser();
        if($user->getRole()=="ROLE_CLIENT")
        {
            return $this->redirectToRoute('espace_client_nos_terrains');
        }
        else
        {
            if($user->getRole()=="ROLE_AGENCE")
            {
                return $this->redirectToRoute('espace_agence_modifier_terrain');
            }
            else
            {
                if($user->getRole()=="ROLE_ADMIN")
                {
                    return $this->redirectToRoute('easyadmin');
                } 
                else
                {
                    return $this->redirectToRoute('espace_public_connexion');
                }
            }   
        }

    }
       
    /**
     * @Route("/logout", name="logout")
     */
    public function logout(Request $request)
    {   

    }
    
    /**
     * @Route("/espace_public_votre_profile", name="espace_public_votre_profile")
     */
    public function votre_profile()
    {
        $role = $this->getUser()->getRole();
        if($role == "ROLE_CLIENT")
        {
            return $this->redirectToRoute('espace_client');
        }
        else
        {
            if($role == "ROLE_AGENCE")
            {
                return $this->redirectToRoute('espace_agence');
            }
            else
            {
                if($role == "ROLE_ADMIN")
                {
                    return $this->redirectToRoute('easyadmin');
                }

            }
        }
    }


    /**
     * @Route("/espace_public/{noteint}/{terrain}", name="espace_public_note")
     */

    public function note(Terrain $terrain,int $noteint,EntityManagerInterface $manager,NoteRepository $noteRepository,Request $request)
    {
        $user=$this->getUser()->getClient();
        $note=$noteRepository->findOneBy(['client'=>$user,'terrain'=>$terrain]);
        if($note != NULL)
        {
            $note->setNote($noteint);
            $manager->flush();
            
        return $this->json(['code'=>200,'message'=>'modification avec succees'],200);
        }
        else
        {
            $notes=new Note();
            $notes->setClient($user)
                  ->setTerrain($terrain)
                  ->setNote($noteint);
            $manager->persist($notes);
            $manager->flush();
            
        return $this->json(['code'=>200,'message'=>'ajout avec success'],200);

        }


    }    
}
