<?php

//src/path_here/ChartService.php

namespace App\Services;

use App\Entity\Gut;
use App\Entity\Reaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartService
{

    private $builder;
    private $em;

    public function __construct(ChartBuilderInterface $builder, EntityManagerInterface $em)
    {
        $this->builder = $builder;
        $this->em = $em;
    }

    /*
     * Creates dataset for bar chart
     * 'labels' => "Week of " . $week[$weekNo]['firstDay']
     * 'datasets' => ['label' => $rxList[$i], 'data' =>
     */

    public function reactionSummaryChart(): Chart
    {
        $chart = $this->builder->createChart(Chart::TYPE_LINE);
//        $chart = $this->builder->createChart(Chart::TYPE_BAR);
        $reactions = $this->em->getRepository(Reaction::class)->findAll([], ['reaction', 'ASC']);
        $colors = [
            'Purple',
            'Crimson',
            'DarkSeaGreen',
            'BlanchedAlmond',
            'Violet',
            'ForestGreen',
            'Orange',
        ];
        $rxList = [];
        foreach ($reactions as $value) {
            $rxList[] = $value->getReaction();
        }
        foreach ($rxList as $malady) {
            $$malady = [];
            $$malady['label'] = $malady;
        }
        $today = new \DateTime('today');
        $qb = $this->em->createQueryBuilder('g')
                        ->select('g')
                        ->from('App\Entity\Gut', 'g')
                        ->orderBy('g.happened', 'ASC')
                        ->getQuery()->getArrayResult();
        $historyCount = \count($qb);
        $barMax = 0;
        $j = 0;
        for ($i = 0; $i <= $historyCount; $i++) {
            $weekNo = $qb[$i]['happened']->format("W");
            $year = $qb[$i]['happened']->format("Y");
            $week[$weekNo] = [];
            $firstDay = $today->setISODate($year, $weekNo, 0);
            $week[$weekNo]['firstDay'] = date_format($firstDay, 'm/d');
            foreach ($rxList as $value) {
                $week[$weekNo]['reaction'][$value] = 0;
            }

            while ($weekNo == $qb[$i]['happened']->format("W")) {
                $week[$weekNo]['reaction'][$qb[$i]['reaction']]++;
                $i++;
                if ($i === $historyCount) {
                    break;
                }
            }

            $j = 0;
            foreach ($week[$weekNo]['reaction'] as $key => $value) {
                $$key['data'][] = $value;
                if ($barMax < $value) {
                    $barMax = $value;
                }
                // for line chart
                $$key['borderColor'] = $colors[$j];
                $$key['fill'] = false;
                //for bar chart
                //  $$key['backgroundColor'] = $colors[$j];
                $j++;
            }
        }

        $datasets = [];
        foreach ($rxList as $value) {
            $datasets[] = $$value;
        }

        $chartLabels = [];
        foreach ($week as $value) {
            $chartLabels[] = $value['firstDay'];
        }

        $chart->setOptions([
            'plugins' => [
                'title' => [
                    'display' => true,
                    'text' => 'Reactions by Week'
                ],
            ],
            'scales' => [
                'y' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Instances',
                    ],
                    'suggestedMin' => 0,
                    'suggestedMax' => ceil(1.2 * $barMax),
                    'beginAtZero' => true,
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Week of',
                    ]
                ]
            ],
        ]);

        $chart->setData([
            'labels' => $chartLabels,
            'datasets' => $datasets
        ]);

        return $chart;
    }

}
