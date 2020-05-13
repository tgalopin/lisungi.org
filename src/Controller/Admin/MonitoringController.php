<?php

namespace App\Controller\Admin;

use App\Statistics\StatisticsAggregator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/monitoring")
 */
class MonitoringController extends AbstractController
{
    /**
     * @Route("/statistics", name="admin_statistics")
     */
    public function statistics(StatisticsAggregator $aggregator): Response
    {
        return $this->render('admin/statistics.html.twig', [
            'countTotalHelpersByDay' => $aggregator->countTotalHelpersByDay(),
            'countTotalOwnersByDay' => $aggregator->countTotalOwnersByDay(),

            'countTotalHelpers' => $aggregator->countTotalHelpers(),
            'countTotalOwners' => $aggregator->countTotalOwners(),
            'countTotalVolunteers' => $aggregator->countTotalVolunteers(),

            'countHelpersByDepartment' => $aggregator->countHelpersByDepartment(),
            'countOwnersByDepartment' => $aggregator->countOwnersByDepartment(),
        ]);
    }
}
