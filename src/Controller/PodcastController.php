<?php

namespace App\Controller;
use App\Entity\Usuario;

use App\Entity\Podcast;
use App\Form\PodcastType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
class PodcastController extends AbstractController
{
    private $em;
    private $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }
    public function subirPodcast(Request $request): Response
    {
        $podcast = new Podcast();

$user = $this->security->getUser();
if ($user instanceof Usuario) {
    $nombreAutor = $user->getNombre();
    $podcast->setAutor($nombreAutor);
    $podcast->setIdAutor($user);
} else {
    // Manejo de errores o redireccionamiento
}
        
        $form = $this->createForm(PodcastType::class, $podcast);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $audioFile */
            $audioFile = $form->get('audio')->getData();
            
            if ($audioFile) {
                $originalAudioName = pathinfo($audioFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeAudioName = iconv('UTF-8', 'ASCII//TRANSLIT', $originalAudioName);
                $newAudioName = $safeAudioName.'-'.uniqid().'.'.$audioFile->guessExtension();
            
               
            
            
    
                try {
                    $audioFile->move(
                        $this->getParameter('app.audio_directory'),
                        $newAudioName
                    );
                    
                    $podcast->setAudio($newAudioName);
                } catch (FileException $e) {
                    // Manejo de errores al mover el archivo de audio
                }
            }
            
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('imagen')->getData();
            
            if ($imageFile) {
                $originalImageName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeImageName = iconv('UTF-8', 'ASCII//TRANSLIT', $originalImageName);
                $safeImageName = preg_replace('/[^A-Za-z0-9_]/', '', $safeImageName);
                $safeImageName = strtolower($safeImageName);
                $newImageName = $safeImageName . '-' . uniqid() . '.' . $imageFile->guessExtension();
                
                // Resto del código
            
            
                try {
                    $imageFile->move(
                        $this->getParameter('app.image_directory'),
                        $newImageName
                    );
                    
                    $podcast->setImagen($newImageName);
                } catch (FileException $e) {
                    // Manejo de errores al mover el archivo de imagen
                }
            }
            
            // Guardar el podcast en la base de datos
            $this->em->persist($podcast);
            $this->em->flush();
    
            return $this->redirectToRoute('perfil');
        }
    
        return $this->render('subir_podcast.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/tabla', name: 'table_podcast')]
    public function tabla(): Response
    {
        $podcasts = $this->em->getRepository(Podcast::class)->findAll();

        return $this->render('table_podcast.html.twig', [
            'podcasts' => $podcasts,
        ]);
    }

    #[Route('/podcasts/editar/{id}', name: 'editar_podcast')]
    #[Route('/podcasts/editar/{id}', name: 'editar_podcast')]
public function editar(Request $request, Podcast $podcast): Response
{
    $form = $this->createForm(PodcastType::class, $podcast);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        /** @var UploadedFile $imageFile */
        $imageFile = $form->get('imagen')->getData();

        if ($imageFile) {
            $originalImageName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeImageName = iconv('UTF-8', 'ASCII//TRANSLIT', $originalImageName);
            $safeImageName = preg_replace('/[^A-Za-z0-9_]/', '', $safeImageName);
            $safeImageName = strtolower($safeImageName);
            $newImageName = $safeImageName . '-' . uniqid() . '.' . $imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->getParameter('app.image_directory'),
                    $newImageName
                );

                $podcast->setImagen($newImageName);
            } catch (FileException $e) {
                // Manejo de errores al mover el archivo de imagen
            }
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $newAudioFile = $form->get('audio')->getData();
        if ($newAudioFile) {
            // Eliminar el archivo de audio anterior si existe
            if ($podcast->getAudio()) {
                $oldAudioPath = $this->getParameter('app.audio_directory') . '/' . $podcast->getAudio();
                if (file_exists($oldAudioPath)) {
                    unlink($oldAudioPath);
                }
            }

            // Subir y guardar el nuevo archivo de audio
            $originalAudioName = pathinfo($newAudioFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeAudioName = iconv('UTF-8', 'ASCII//TRANSLIT', $originalAudioName);
            $newAudioName = $safeAudioName . '-' . uniqid() . '.' . $newAudioFile->guessExtension();

            try {
                $newAudioFile->move(
                    $this->getParameter('app.audio_directory'),
                    $newAudioName
                );

                $podcast->setAudio($newAudioName);
            } catch (FileException $e) {
                // Manejo de errores al mover el archivo de audio
            }
        }
        $this->em->flush();

        $this->addFlash('success', 'El podcast ha sido actualizado correctamente.');

        return $this->redirectToRoute('perfil');
    }
}
    return $this->render('editar_podcast.html.twig', [
        'podcast' => $podcast,
        'form' => $form->createView(),
    ]);
}

public function editar_admin(Request $request, Podcast $podcast): Response
{
    $form = $this->createForm(PodcastType::class, $podcast);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        /** @var UploadedFile $imageFile */
        $imageFile = $form->get('imagen')->getData();

        if ($imageFile) {
            $originalImageName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeImageName = iconv('UTF-8', 'ASCII//TRANSLIT', $originalImageName);
            $safeImageName = preg_replace('/[^A-Za-z0-9_]/', '', $safeImageName);
            $safeImageName = strtolower($safeImageName);
            $newImageName = $safeImageName . '-' . uniqid() . '.' . $imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->getParameter('app.image_directory'),
                    $newImageName
                );

                $podcast->setImagen($newImageName);
            } catch (FileException $e) {
                // Manejo de errores al mover el archivo de imagen
            }
        }

        $newAudioFile = $form->get('audio')->getData();

        if ($newAudioFile) {
            // Eliminar el archivo de audio anterior si existe
            if ($podcast->getAudio()) {
                $oldAudioPath = $this->getParameter('app.audio_directory') . '/' . $podcast->getAudio();
                if (file_exists($oldAudioPath)) {
                    unlink($oldAudioPath);
                }
            }

            // Subir y guardar el nuevo archivo de audio
            $originalAudioName = pathinfo($newAudioFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeAudioName = iconv('UTF-8', 'ASCII//TRANSLIT', $originalAudioName);
            $newAudioName = $safeAudioName . '-' . uniqid() . '.' . $newAudioFile->guessExtension();

            try {
                $newAudioFile->move(
                    $this->getParameter('app.audio_directory'),
                    $newAudioName
                );

                $podcast->setAudio($newAudioName);
            } catch (FileException $e) {
                // Manejo de errores al mover el archivo de audio
            }
        }

        $this->em->flush();

        $this->addFlash('success', 'El podcast ha sido actualizado correctamente.');

        return $this->redirectToRoute('tabla_podcast');
    }

    return $this->render('editar_podcast.html.twig', [
        'podcast' => $podcast,
        'form' => $form->createView(),
    ]);
}
    #[Route('/podcasts/eliminar/{id}', name: 'eliminar_podcast')]
    public function eliminar(Request $request, Podcast $podcast): Response
    {
        if ($this->isCsrfTokenValid('eliminar_podcast' . $podcast->getId(), $request->request->get('_token'))) {
            $this->em->remove($podcast);
            $this->em->flush();

            $this->addFlash('success', 'El podcast ha sido eliminado correctamente.');
        }

        return $this->redirectToRoute('tabla');
    }

   
    

}

/*#[Route('/podcast/new', name: 'app_podcast_new')]   
public function new(Request $request, SluggerInterface $slugger): Response
  {
      $podcast = new Podcast();
      $form = $this->createForm(PodcastType::class, $podcast);
      $form->handleRequest($request);
  
      if ($form->isSubmitted() && $form->isValid()) {
          /** @var UploadedFile $audioFile 
          $audioFile = $form->get('audio')->getData();
          /** @var UploadedFile $imagenFile 
          $imagenFile = $form->get('imagen')->getData();
  
          // Procesar el archivo de audio
          if ($audioFile) {
              $originalFilename = pathinfo($audioFile->getClientOriginalName(), PATHINFO_FILENAME);
              $safeFilename = $slugger->slug($originalFilename);
              $newFilename = $safeFilename . '-' . uniqid() . '.' . $audioFile->guessExtension();
  
              try {
                  $audioFile->move(
                      $this->getParameter('audios_directory'),
                      $newFilename
                  );
              } catch (FileException $e) {
                  // Manejar la excepción si ocurre algún problema durante la subida del archivo
              }
  
              $podcast->setAudio($newFilename);
          }
  
          // Procesar el archivo de imagen
          if ($imagenFile) {
              $originalFilename = pathinfo($imagenFile->getClientOriginalName(), PATHINFO_FILENAME);
              $safeFilename = $slugger->slug($originalFilename);
              $newFilename = $safeFilename . '-' . uniqid() . '.' . $imagenFile->guessExtension();
  
              try {
                  $imagenFile->move(
                      $this->getParameter('imagenes_directory'),
                      $newFilename
                  );
              } catch (FileException $e) {
                  // Manejar la excepción si ocurre algún problema durante la subida del archivo
              }
  
              $podcast->setImagen($newFilename);
          }
  
          // Persistir el objeto $podcast u otras operaciones necesarias
  
          
      if ($form->isSubmitted() && $form->isValid()) {
          // ...
  
          // Persistir el objeto $podcast u otras operaciones necesarias
  
          $this->entityManager->persist($podcast);
          $this->entityManager->flush();
  
  
          return $this->redirectToRoute('perfil');
      }
  
      return $this->render('subir_podcast.html.twig', [
          'form' => $form->createView(),
      ]);
      }
  } */
/* #[Route('/subir-podcast', name: 'subir_podcast')]
public function subirPodcast(Request $request): Response
{
  $podcast = new Podcast();
  $form = $this->createForm(PodcastType::class, $podcast);

  $form->handleRequest($request);
  if ($form->isSubmitted() && $form->isValid()) {
      // Procesar y guardar los datos del podcast

      return $this->redirectToRoute('perfil');
  }

  return $this->render('subir_podcast.html.twig', [
      'form' => $form->createView(),
  ]);
} */