<?php

namespace App\Dashboard\Profile\Infrastructure\Controller;

use App\Dashboard\Profile\Application\ChangePassword\ChangePasswordCommand;
use App\Dashboard\Profile\Application\ChangePassword\ChangePasswordHandler;
use App\Dashboard\Profile\Application\UpdateProfile\UpdateProfileCommand;
use App\Dashboard\Profile\Application\UpdateProfile\UpdateProfileHandler;
use App\Dashboard\Profile\Application\UploadPhoto\UploadPhotoCommand;
use App\Dashboard\Profile\Application\UploadPhoto\UploadPhotoHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/profile')]
final class ProfileController
{
    public function __construct(
        private readonly UpdateProfileHandler $updateProfileHandler,
        private readonly ChangePasswordHandler $changePasswordHandler,
        private readonly UploadPhotoHandler $uploadPhotoHandler,
        private readonly TokenStorageInterface $tokenStorage
    ) {}

    private function getCurrentUserId(): int
    {
        $token = $this->tokenStorage->getToken();
        if (null === $token) {
            throw new \DomainException('User not authenticated');
        }

        return $token->getUser()->id()->value();
    }


    #[Route('/update', name: 'api_profile_update', methods: ['PUT'])]
    public function update(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $command = new UpdateProfileCommand(
                $this->getCurrentUserId(),
                $data['name'] ?? '',
                $data['email'] ?? ''
            );

            $response = ($this->updateProfileHandler)($command);

            return new JsonResponse([
                'message' => 'Profile updated successfully',
                'user' => [
                    'id' => $response->id,
                    'email' => $response->email,
                    'name' => $response->name,
                    'roles' => $response->roles,
                    'profilePhoto' => $response->profilePhoto
                ]
            ]);
        } catch (\DomainException $e) {
            return new JsonResponse(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'An error occurred'], 500);
        }
    }

    #[Route('/change-password', name: 'api_profile_change_password', methods: ['POST'])]
    public function changePassword(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $command = new ChangePasswordCommand(
                $this->getCurrentUserId(),
                $data['currentPassword'] ?? '',
                $data['newPassword'] ?? ''
            );

            $response = ($this->changePasswordHandler)($command);

            return new JsonResponse([
                'message' => 'Password updated successfully',
                'user' => [
                    'id' => $response->id,
                    'email' => $response->email,
                    'name' => $response->name,
                    'roles' => $response->roles,
                    'profilePhoto' => $response->profilePhoto
                ]
            ]);
        } catch (\DomainException $e) {
            return new JsonResponse(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'An error occurred'], 500);
        }
    }

    #[Route('/upload-photo', name: 'api_profile_upload_photo', methods: ['POST'])]
    public function uploadPhoto(Request $request): JsonResponse
    {
        try {
            $photo = $request->files->get('photo');
            if (!$photo) {
                throw new \DomainException('No image provided');
            }

            $command = new UploadPhotoCommand(
                $this->getCurrentUserId(),
                $photo
            );

            $response = ($this->uploadPhotoHandler)($command);

            return new JsonResponse([
                'message' => 'Photo uploaded successfully',
                'user' => [
                    'id' => $response->id,
                    'email' => $response->email,
                    'name' => $response->name,
                    'roles' => $response->roles,
                    'profilePhoto' => $response->profilePhoto
                ]
            ]);
        } catch (\DomainException $e) {
            return new JsonResponse(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'An error occurred'], 500);
        }
    }
}