<?php

namespace App\Controller;

use App\Entity\Helper;
use App\Form\HelperType;
use App\Repository\HelperRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/process")
 */
class HelperController extends AbstractController
{
    /**
     * @Route({
     *     "fr_CD": "/je-peux-aider"
     * }, name="process_helper")
     */
    public function helper(EntityManagerInterface $manager, HelperRepository $repository, Request $request, TranslatorInterface $translator, string $sender)
    {
        $helper = new Helper();

        $form = $this->createForm(HelperType::class, $helper);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $helper->email = strtolower($helper->email);
            $repository->removeHelpProposal($helper->email);

            $manager->persist($helper);
            $manager->flush();

            return $this->redirectToRoute('process_helper_view', [
                'uuid' => $helper->getUuid()->toString(),
                'success' => '1',
            ]);
        }

        return $this->render('process/helper.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route({
     *     "fr_CD": "/je-peux-aider/{uuid}"
     * }, name="process_helper_view")
     */
    public function view(Helper $helper, Request $request)
    {
        return $this->render('process/helper_view.html.twig', [
            'helper' => $helper,
            'success' => $request->query->getBoolean('success'),
        ]);
    }

    /**
     * @Route({
     *     "fr_CD": "/je-peux-aider/{uuid}/supprimer"
     * }, name="process_helper_delete_confirm")
     */
    public function deleteConfirm(Helper $helper)
    {
        return $this->render('process/helper_delete_confirm.html.twig', ['helper' => $helper]);
    }

    /**
     * @Route({
     *     "fr_CD": "/je-peux-aider/{uuid}/supprimer/do"
     * }, name="process_helper_delete_do")
     */
    public function deleteDo(HelperRepository $repository, Helper $helper, Request $request)
    {
        if (!$this->isCsrfTokenValid('helper_delete', $request->query->get('token'))) {
            throw $this->createNotFoundException();
        }

        $repository->removeHelpProposal($helper->email);

        return $this->redirectToRoute('process_helper_delete_done');
    }

    /**
     * @Route({
     *     "fr_CD": "/je-peux-aider/supprimer/effectue"
     * }, name="process_helper_delete_done")
     */
    public function deleted()
    {
        return $this->render('process/helper_delete_done.html.twig');
    }
}
