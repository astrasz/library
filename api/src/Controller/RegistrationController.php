<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends BaseController
{
    #[Route('/api/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $form->submit($request->toArray());

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->createApiResponse(sprintf('User %s has been created succesfully', $user->getEmail()), JsonResponse::HTTP_CREATED);
        }

        $errorsArray = [];
        $errors = $form->getErrors(true, true);
        foreach ($errors as $error) {
            $errorsArray[$error->getOrigin()->getName()][] = $error->getMessage();
        }

        return $this->createApiResponse($errors, JsonResponse::HTTP_BAD_REQUEST);
    }
}
