<?php

namespace App\Controller;
use App\Entity\Podcast;
use App\Entity\Usuario;
use App\Form\UsuarioType;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class UsuarioController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
  

    #[Route('/perfil', name: 'perfil')]
    public function perfil(): Response
    {
        $user = $this->getUser();
        $isAdmin = in_array('ROLE_ADMIN', $user->getRoles());
    
        $podcastRepository = $this->entityManager->getRepository(Podcast::class);
        $podcasts = $isAdmin ? $podcastRepository->findAll() : $podcastRepository->findBy(['id_autor' => $user]);
    
        return $this->render('perfil.html.twig', [
            'podcasts' => $podcasts,
        ]);
    }
    



    #[Route('/register', name: 'register')]
   
     
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new Usuario();
        $form = $this->createForm(UsuarioType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encriptar la contraseña
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            // Asignar el rol de usuario
            $user->setRoles(['ROLE_USER']);

            // Guardar el usuario en la base de datos
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            // Redireccionar a la página de inicio de sesión
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registrar.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
#[Route('/usuarios/editar/{id}', name: 'editar_usuario')]
#[Route('/usuarios/{id}/editar', name: 'editar_usuario')]
public function editar(Request $request, Usuario $usuario): Response
{
    $form = $this->createForm(UsuarioType::class, $usuario);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $this->entityManager->flush();

        $this->addFlash('success', 'El usuario ha sido actualizado correctamente.');

        return $this->redirectToRoute('usuarios');
    }

    return $this->render('editar.html.twig', [
        'usuario' => $usuario,
        'form' => $form->createView(),
    ]);
}


public function eliminar(Request $request, $id): Response
{
    $usuario = $this->entityManager->getRepository(Usuario::class)->find($id);

    // Verificar si el usuario existe
    if (!$usuario) {
        throw $this->createNotFoundException('Usuario no encontrado');
    }
    
    // Realizar la lógica de eliminación del usuario
    $this->entityManager->remove($usuario);
    
    // Guardar los cambios en la base de datos
    $this->entityManager->flush();
    
    return $this->redirectToRoute('usuarios');
}



    #[Route('/usuarios', name: 'usuarios')]
    public function index(UsuarioRepository $usuarioRepository): Response
    {
        $users = $usuarioRepository->findAll();

        return $this->render('table_user.html.twig', [
            'users' => $users,
        ]);
    }
   
}
