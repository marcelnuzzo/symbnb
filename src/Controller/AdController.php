<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AdType;
use App\Form\ImageType;
use App\Repository\AdRepository;
use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
        ]);
    }

    /**
     * Permet de créer une annonce
     * 
     * @Route("/ads/new", name="ads_create")
     *
     * @return Response
     */
    public function create(EntityManagerInterface $manager, Request $request) {
        $ad = new Ad();

        $image = new Image();
        $image->setUrl('http://placehold.it/400x200')
              ->setCaption('Titre 1');
        $image2 = new Image();
        $image2->setUrl('http://placehold.it/400x200')
            ->setCaption('Titre 2');
        $ad->addImage($image);
        $ad->addImage($image2);
        
        $form = $this->createFormBuilder($ad)
                     ->add('title')
                     ->add('slug')
                     ->add('introduction', TextType::class)
                     ->add('content')
                     ->add('rooms')
                     ->add('price', MoneyType::class)
                     ->add('coverImage', UrlType::class)
                     ->add(
                         'images',
                         CollectionType::class,
                         [
                             'entry_type' => ImageType::class
                         ]
                     )
                     ->getForm();

                    $form->handleRequest($request);
               
                    if($form->isSubmitted() && $form->isValid()) {
                        
                        $manager->persist($ad);
                        $manager->flush();
                
                        $this->addFlash(
                            'success',
                            "L'annoce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
                        );
                           
                        return $this->redirectToRoute('ads_show', [
                                'slug' => $ad->getSlug()
                            ]);
                    }
        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Undocumented function
     * @Route("/ads/nouveau", name="ads_creation")
     * @return Response
     */
    public function creation (Request $request, EntityManagerInterface $manager) {
        $ad = new Ad();

        $form = $this->createFormBuilder($ad)
                     ->add('title')
                     ->add('slug')
                     ->add('introduction')
                     ->add('content')
                     ->add('coverImage')
                     ->add('price')
                     ->add('rooms')
                     ->getForm();

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()) {
                   
                    $manager->persist($ad);
                    $manager->flush();
            
                        return $this->redirectToRoute('ads_show',  ['id' => $ad->getId()
                        ]);
                }
                return $this->render('ad/nouveau.html.twig', [
                    'form' => $form->createView()
                ]);

    }

    /**
     * Permet d'afficher une seule annonce
     *
     * @Route("/ads/{slug}", name="ads_show")
     * 
     * @return Response
     */
    public function show(Ad $ad) {
        // Je récupère l'annonce qui correspond au slug
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }

}
