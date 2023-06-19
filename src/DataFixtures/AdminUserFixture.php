<?php


namespace App\DataFixtures;

use App\Entity\Usuario;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
// src/DataFixtures/AdminUserFixture.php
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class AdminUserFixture extends Fixture
{
  
private $passwordHasher;

public function __construct(UserPasswordHasherInterface $passwordHasher)
{
    $this->passwordHasher = $passwordHasher;
}


    // src/DataFixtures/AdminUserFixture.php
public function load(ObjectManager $manager)
{
    // Crear un usuario administrador
    $admin = new Usuario();
    $admin->setNombre('Admin');
    $admin->setApellidos('Admin');
    $admin->setEmail('admin@example.com');
    $admin->setRoles(['ROLE_ADMIN']);

    // Encriptar la contraseña
    $password = $this->passwordHasher->hashPassword($admin, 'password');
    $admin->setPassword($password);

    // Guardar el usuario en la base de datos
    $manager->persist($admin);
    $manager->flush();
}

}
