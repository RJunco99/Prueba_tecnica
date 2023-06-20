<?php

namespace App\Entity;

use App\Repository\PodcastRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PodcastRepository::class)]
class Podcast
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $titulo = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $fechaSubida = null;

    #[ORM\Column]
    private ?string $descripcion = null;

    #[ORM\Column]
    private ?string $audio = null;

    #[ORM\Column]
    private ?string $imagen = null;


    #[ORM\Column(nullable: false)]
    private ?string $autor = null;
    
    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'id_autor', referencedColumnName: 'id')]

    private ?Usuario $id_autor = null;

    public function getIdAutor(): ?Usuario
    {
        return $this->id_autor;
    }

    public function setIdAutor(?Usuario $id_autor): self
    {
        $this->id_autor = $id_autor;

        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getFechaSubida(): ?\DateTimeInterface
    {
        return $this->fechaSubida;
    }

    public function setFechaSubida(\DateTimeInterface $fechaSubida): self
    {
        $this->fechaSubida = $fechaSubida;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getAudio(): ?string
    {
        return $this->audio;
    }

    public function setAudio(string $audio): self
    {
        $this->audio = $audio;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    public function getAutor(): ?string
    {
        return $this->autor;
    }
    
    public function setAutor(?string $autor): self
    {
        $this->autor = $autor;
    
        return $this;
    }
    

    // ...
}
