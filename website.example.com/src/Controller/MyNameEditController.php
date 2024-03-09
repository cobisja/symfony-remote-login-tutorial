<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\MyNameType;
use App\Repository\RemoteUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MyNameEditController extends AbstractController
{
    public function __construct(private readonly RemoteUserRepository $remoteUserRepository)
    {
    }

    #[Route('/editmyname', name: 'app_edit_my_name', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): RedirectResponse|Response
    {
        $form = $this->createForm(MyNameType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $myName = $form->getData()['my_name'];

            $this->remoteUserRepository->updateName($user, $myName);

            return $this->redirectToRoute('app_home_index');
        }

        return $this->render('users/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}