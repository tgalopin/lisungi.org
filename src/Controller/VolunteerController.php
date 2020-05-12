<?php

namespace App\Controller;

use App\Entity\Helper;
use App\Entity\Volunteer;
use App\Form\VolunteerType;
use App\Repository\VolunteerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/process")
 */
class VolunteerController extends AbstractController
{
    /**
     * @Route({
     *     "fr_CD": "/benevole"
     * }, name="process_volunteer")
     */
    public function volunteer(EntityManagerInterface $manager, VolunteerRepository $repository, Request $request, TranslatorInterface $translator, string $sender)
    {
        $volunteer = new Volunteer();

        $form = $this->createForm(VolunteerType::class, $volunteer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $volunteer->email = strtolower($volunteer->email);
            $repository->removeHelpProposal($volunteer->email);

            $manager->persist($volunteer);
            $manager->flush();

            return $this->redirectToRoute('process_volunteer_view', [
                'uuid' => $volunteer->getUuid()->toString(),
                'success' => '1',
            ]);
        }

        return $this->render('process/volunteer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route({
     *     "fr_CD": "/je-peux-aider/{uuid}"
     * }, name="process_volunteer_view")
     */
    public function view(Helper $volunteer, Request $request)
    {
        return $this->render('process/volunteer_view.html.twig', [
            'volunteer' => $volunteer,
            'success' => $request->query->getBoolean('success'),
        ]);
    }

    /**
     * @Route({
     *     "fr_CD": "/je-peux-aider/{uuid}/supprimer"
     * }, name="process_volunteer_delete_confirm")
     */
    public function deleteConfirm(Helper $volunteer)
    {
        return $this->render('process/volunteer_delete_confirm.html.twig', ['volunteer' => $volunteer]);
    }

    /**
     * @Route({
     *     "fr_CD": "/je-peux-aider/{uuid}/supprimer/do"
     * }, name="process_volunteer_delete_do")
     */
    public function deleteDo(VolunteerRepository $repository, Volunteer $volunteer, Request $request)
    {
        if (!$this->isCsrfTokenValid('volunteer_delete', $request->query->get('token'))) {
            throw $this->createNotFoundException();
        }

        $repository->removeHelpProposal($volunteer->email);

        return $this->redirectToRoute('process_volunteer_delete_done');
    }

    /**
     * @Route({
     *     "fr_CD": "/je-peux-aider/supprimer/effectue"
     * }, name="process_volunteer_delete_done")
     */
    public function deleted()
    {
        return $this->render('process/volunteer_delete_done.html.twig');
    }
}
