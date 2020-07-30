<?php

namespace App\Entity;

use App\Repository\PinsRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestampable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=PinsRepository::class)
 * @ORM\Table(name="pins")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class Pins
{
    use Timestampable;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le titre est obligatoire")
     * @Assert\Length(min=3, max=20, minMessage="Le titre doit contenir plus de {{ min }} caractères", maxMessage="Le titre doit contenir au plus {{ max }} caractères")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="La description est obligatoire")
     * @Assert\Length(min=5, max=255,  minMessage="La description doit contenir plus de {{ min }} caractères", maxMessage="La description doit contenir au plus {{ max }} caractères")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @Vich\UploadableField(mapping="pin_image", fileNameProperty="image")
     * @Assert\Image(maxSize="1M", maxSizeMessage="La taille de votre image ({{ size }}{{ suffix }}) est supérieur à {{ maxSize }}{{ suffix }}")
     * @var File|null
     */
    private $imageFile;


    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

}
