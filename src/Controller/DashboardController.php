<?php

namespace App\Controller;

use App\Repository\BurialPlotRepository;
use App\Repository\BurialRecordRepository;
use App\Repository\DeceasedRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        BurialPlotRepository $plotRepo,
        BurialRecordRepository $recordRepo,
        DeceasedRepository $deceasedRepo
    ): Response {

        // ---------------------------------------------------------
        // BURIAL PLOT STATS
        // ---------------------------------------------------------
        $totalPlots = $plotRepo->count([]);
        $occupiedPlots = $plotRepo->count(['is_occupied' => true]);
        $availablePlots = $totalPlots - $occupiedPlots;
        $occupiedPercentage = $totalPlots > 0 ? round(($occupiedPlots / $totalPlots) * 100, 1) : 0;

        // ---------------------------------------------------------
        // BURIAL RECORD STATS
        // ---------------------------------------------------------

        $totalBurials = $recordRepo->count([]);

        // Year range for current year stats
        $startOfYear = new \DateTime(date('Y-01-01 00:00:00'));
        $endOfYear = new \DateTime(date('Y-12-31 23:59:59'));

        // Burials THIS YEAR
        $burialsCurrentYear = $recordRepo->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->where('b.burial_date BETWEEN :start AND :end')
            ->setParameter('start', $startOfYear)
            ->setParameter('end', $endOfYear)
            ->getQuery()
            ->getSingleScalarResult();

        // Burials by month (fetch all burials this year and group in PHP)
        $burialDates = $recordRepo->createQueryBuilder('b')
            ->select('b.burial_date')
            ->where('b.burial_date BETWEEN :start AND :end')
            ->setParameter('start', $startOfYear)
            ->setParameter('end', $endOfYear)
            ->getQuery()
            ->getScalarResult();

        // Initialize months
        $burialsByMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $burialsByMonth[$m] = 0;
        }

        // Group by month
        foreach ($burialDates as $row) {
            $month = (int)$row['burial_date']->format('n');
            $burialsByMonth[$month]++;
        }

        // Convert for Twig chart
        $burialsByMonth = array_map(
            fn($month, $total) => ['month' => $month, 'total' => $total],
            array_keys($burialsByMonth),
            $burialsByMonth
        );

        // Top funeral homes
        $topFuneralHomes = $recordRepo->createQueryBuilder('b')
            ->select('b.funeral_home AS name, COUNT(b.id) AS total')
            ->groupBy('b.funeral_home')
            ->orderBy('total', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getArrayResult();

        // ---------------------------------------------------------
        // DECEASED STATS
        // ---------------------------------------------------------

        // Gender distribution
        $genderDistribution = $deceasedRepo->createQueryBuilder('d')
            ->select('d.gender AS gender, COUNT(d.id) AS total')
            ->groupBy('d.gender')
            ->getQuery()
            ->getArrayResult();

        // Common causes of death
        $commonCauses = $deceasedRepo->createQueryBuilder('d')
            ->select('d.cause_of_death AS cause, COUNT(d.id) AS total')
            ->groupBy('d.cause_of_death')
            ->orderBy('total', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getArrayResult();

        // Average age at death
        $allDeceased = $deceasedRepo->findAll();
        $avgAge = 0;

        if (count($allDeceased) > 0) {
            $ages = [];
            foreach ($allDeceased as $person) {
                $dob = $person->getDateOfBirth();
                $dod = $person->getDateOfDeath();
                if ($dob && $dod) {
                    $ages[] = $dob->diff($dod)->y;
                }
            }
            if (count($ages) > 0) {
                $avgAge = round(array_sum($ages) / count($ages), 1);
            }
        }


        // ---------------------------------------------------------
        // RENDER VIEW
        // ---------------------------------------------------------
        return $this->render('dashboard/index.html.twig', [
            'stats' => [
                // Burial plots
                'total_plots' => $totalPlots,
                'occupied_plots' => $occupiedPlots,
                'available_plots' => $availablePlots,
                'occupied_percentage' => $occupiedPercentage,

                // Burial records
                'total_burials' => $totalBurials,
                'burials_current_year' => $burialsCurrentYear,
                'burials_by_month' => $burialsByMonth,
                'top_funeral_homes' => $topFuneralHomes,

                // Deceased
                'gender_distribution' => $genderDistribution,
                'common_causes' => $commonCauses,
                'avg_age' => $avgAge,
            ]
        ]);
    }
}
