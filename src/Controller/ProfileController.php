<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/profile')]
class ProfileController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private ValidatorInterface $validator
    ) {}

    #[Route('/update', name: 'api_profile_update', methods: ['PUT'])]
    public function update(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        if (empty($data['name']) || empty($data['email'])) {
            return $this->json(['message' => 'Nombre y email son requeridos'], 400);
        }

        $existingUser = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $data['email']]);

        if ($existingUser && $existingUser->getId() !== $user->getId()) {
            return $this->json(['message' => 'Este email ya está en uso'], 400);
        }

        $user->setName($data['name']);
        $user->setEmail($data['email']);

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        $this->entityManager->flush();

        return $this->json([
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'roles' => $user->getRoles(),
            ]
        ]);
    }

    #[Route('/change-password', name: 'api_profile_change_password', methods: ['POST'])]
    public function changePassword(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        if (empty($data['currentPassword']) || empty($data['newPassword'])) {
            return $this->json(['message' => 'Todos los campos son requeridos'], 400);
        }

        if (!$this->passwordHasher->isPasswordValid($user, $data['currentPassword'])) {
            return $this->json(['message' => 'La contraseña actual no es correcta'], 400);
        }

        if (strlen($data['newPassword']) < 6) {
            return $this->json(['message' => 'La nueva contraseña debe tener al menos 6 caracteres'], 400);
        }

        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['newPassword']);
        $user->setPassword($hashedPassword);

        $this->entityManager->flush();

        return $this->json(['message' => 'Contraseña actualizada correctamente']);
    }


    #[Route('/upload-photo', name: 'api_profile_upload_photo', methods: ['POST'])]
    public function uploadPhoto(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var UploadedFile|null $photo */
        $photo = $request->files->get('photo');

        if (!$photo) {
            return $this->json(['message' => 'No se ha proporcionado ninguna imagen'], 400);
        }

        // Validar el tipo de archivo
        $mimeType = $photo->getMimeType();
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($mimeType, $allowedTypes)) {
            return $this->json([
                'message' => 'Formato de imagen no válido. Use JPG, PNG o WebP'
            ], 400);
        }

        // Validar tamaño (max 5MB)
        if ($photo->getSize() > 5 * 1024 * 1024) {
            return $this->json([
                'message' => 'La imagen no debe superar los 5MB'
            ], 400);
        }

        try {
            // Generar nombre único
            $fileName = sprintf(
                '%s-%s.%s',
                $user->getId(),
                uniqid(),
                $photo->guessExtension()
            );

            // Mover a directorio de uploads
            $photo->move(
                $this->getParameter('profile_photos_directory'),
                $fileName
            );

            // Actualizar usuario con nueva foto
            $user->setProfilePhoto($fileName);
            $this->entityManager->flush();

            return $this->json([
                'message' => 'Foto actualizada correctamente',
                'user' => [
                    'id' => $user->getId(),
                    'email' => $user->getEmail(),
                    'name' => $user->getName(),
                    'profilePhoto' => $fileName,
                    'roles' => $user->getRoles(),
                ]
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'message' => 'Error al subir la imagen'
            ], 500);
        }
    }
}