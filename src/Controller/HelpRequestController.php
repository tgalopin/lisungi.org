<?php

namespace App\Controller;

use App\Form\CompositeHelpRequestType;
use App\Model\CompositeHelpRequest;
use App\Repository\HelpRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/process")
 */
class HelpRequestController extends AbstractController
{
    /**
     * @Route({
     *     "fr_CD": "/j-ai-besoin-d-aide"
     * }, name="process_request")
     */
    public function request(EntityManagerInterface $manager, HelpRequestRepository $repository, Request $request, TranslatorInterface $translator, string $sender)
    {
        $helpRequest = new CompositeHelpRequest();

        $form = $this->createForm(CompositeHelpRequestType::class, $helpRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->clearOldOwnerRequests($helpRequest->email);

            $ownerId = Uuid::uuid4();
            foreach ($helpRequest->createStandaloneRequests($ownerId) as $standaloneRequest) {
                $manager->persist($standaloneRequest);
            }

            $manager->flush();

            return $this->redirectToRoute('process_requester_view', [
                'ownerUuid' => $ownerId->toString(),
                'success' => '1',
            ]);
        }

        return $this->render('process/request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route({
     *     "fr_CD": "/j-ai-besoin-d-aide/{ownerUuid}"
     * }, name="process_requester_view")
     */
    public function view(HelpRequestRepository $repository, Request $request, string $ownerUuid)
    {
        $needs = $repository->findBy(['ownerUuid' => $ownerUuid], ['createdAt' => 'DESC']);
        if (!$needs) {
            throw $this->createNotFoundException();
        }

        return $this->render('process/request_owner_view.html.twig', [
            'needs' => $repository->findBy(['ownerUuid' => $ownerUuid], ['createdAt' => 'DESC']),
            'success' => $request->query->getBoolean('success'),
        ]);
    }

    /**
     * @Route({
     *     "fr_CD": "/j-ai-besoin-d-aide/{ownerUuid}/supprimer"
     * }, name="process_requester_delete_confirm")
     */
    public function deleteConfirm(string $ownerUuid)
    {
        return $this->render('process/request_owner_delete_confirm.html.twig', ['ownerUuid' => $ownerUuid]);
    }

    /**
     * @Route({
     *     "fr_CD": "/j-ai-besoin-d-aide/{ownerUuid}/supprimer/do"
     * }, name="process_requester_delete_do")
     */
    public function deleteDo(HelpRequestRepository $repository, Request $request, string $ownerUuid)
    {
        if (!$this->isCsrfTokenValid('requester_delete', $request->query->get('token'))) {
            throw $this->createNotFoundException();
        }

        $repository->clearOwnerRequestsByUuid($ownerUuid);

        return $this->redirectToRoute('process_requester_delete_done');
    }

    /**
     * @Route({
     *     "fr_CD": "/j-ai-besoin-d-aide/supprimer/effectue"
     * }, name="process_requester_delete_done")
     */
    public function deleted()
    {
        return $this->render('process/request_owner_delete_done.html.twig');
    }
}
