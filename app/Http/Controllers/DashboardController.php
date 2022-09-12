<?php

namespace App\Http\Controllers;

use DB;
use Throwable;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Comment;
use App\Models\Mobile;
use Illuminate\View\View;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return View
     * @throws Throwable
     */
    public function index(Request $request)
    {
        $data = [];
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');;
        $previousStartDate = $request->input('previousStartDate');
        $previousEndDate = $request->input('previousEndDate');

        if ($request->ajax()) {
            $data['dayList'] = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'];
            $data['timeList'] = [
                '10-12p', '12-2p', '2-4p', '4-6', '6-8p', '8-10p',
                '10-12a', '12-2a', '2-4a', '4-6a', '6-8a', '8-10a'
            ];

            // New tickets
            $newTickets = Ticket::withCount('comments')->where(function ($q) use ($startDate, $endDate) {
                $q->where('status', 'Open');
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })->having('comments_count', '=', 0)->get()->count();

            $previousNewTickets = Ticket::withCount('comments')
                ->where(function ($q) use ($previousStartDate, $previousEndDate) {
                    $q->where('status', 'Open');
                    $q->whereBetween('created_at', [$previousStartDate, $previousEndDate]);
                })->having('comments_count', '=', 0)->get()->count();

            $data['newTickets'] = CommonHelper::numberFormatIndia($newTickets);
            $data['ntUpDownPercent'] = CommonHelper::getComparePercent($newTickets, $previousNewTickets);

            // Closed tickets
            $closedTickets = Ticket::where(function ($q) use ($startDate, $endDate) {
                $q->where('status', 'Closed');
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })->count();

            $previousClosedTickets = Ticket::where(function ($q) use ($previousStartDate, $previousEndDate) {
                $q->where('status', 'Closed');
                $q->whereBetween('created_at', [$previousStartDate, $previousEndDate]);
            })->count();

            $data['closedTickets'] = CommonHelper::numberFormatIndia($closedTickets);
            $data['ctUpDownPercent'] = CommonHelper::getComparePercent($closedTickets, $previousClosedTickets);

            // New contacts
            $newContacts = User::where('status', 'Active')
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'Customer');
                })
                ->whereBetween('created_at', [$startDate, $endDate])->count();

            $previousNewContacts = User::where('status', 'Active')
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'Customer');
                })
                ->whereBetween('created_at', [$previousStartDate, $previousEndDate])->count();

            $data['newContacts'] = CommonHelper::numberFormatIndia($newContacts);
            $data['ncUpDownPercent'] = CommonHelper::getComparePercent($newContacts, $previousNewContacts);

            // Total Replies
            $repliesCount = Comment::whereBetween('created_at', [$startDate, $endDate])->count();
            $previousRepliesCount = Comment::whereBetween('created_at', [$previousStartDate, $previousEndDate])->count();
            $data['repliesCount'] = CommonHelper::numberFormatIndia($repliesCount);
            $data['repliesUpDownPercent'] = CommonHelper::getComparePercent($repliesCount, $previousRepliesCount);

            // Average first response time
            $firstAvgResponseTime = Ticket::getAvgFirstTimeResponse($startDate, $endDate);
            $previousFirstAvgResponseTime = Ticket::getAvgFirstTimeResponse($previousStartDate, $previousEndDate);
            $data['firstAvgResponseTime'] = CommonHelper::getFormattedAverageTime($firstAvgResponseTime);
            $data['fartUpDownPercent'] = CommonHelper::getComparePercent($firstAvgResponseTime, $previousFirstAvgResponseTime);

            // Average Ticket close time
            $avgCloseTime = Ticket::getAvgCloseTime($startDate, $endDate);
            $previousAvgCloseTime = Ticket::getAvgCloseTime($previousStartDate, $previousEndDate);
            $data['avgCloseTime'] = CommonHelper::getFormattedAverageTime($avgCloseTime);
            $data['actUpDownPercent'] = CommonHelper::getComparePercent($avgCloseTime, $previousAvgCloseTime);

            // Get line chart data for open tickets
            $data['lineChartData'] = $this->getLineChart($startDate, $endDate);

            // Get pie chart and also update ticket count and color for departments
            $departments = [];
            $data['pieChart'] = $this->getPieChart($startDate, $endDate, $departments);
            $data['departments'] = [];

            // Busiest time chart
            $data['busiestTimeChart'] = $this->getBusiestTimeChart($startDate, $endDate);

            // Progress bar chart data for testimonials
            $result = $this->getTestimonialBoxDetails($startDate, $endDate);
            $data['testimonials'] = $result['testimonials'];
            $data['totalTestimonialCount'] = $result['totalTestimonialCount'];
            $data['selectedCircleClass'] = $result['selectedCircleClass'];

            // Customers list
            $data['customers'] = User::where('status', 'Active')->whereHas('roles', function ($q) {
                $q->where('name', 'Customer');
            })->orderBy('name', 'ASC')->take(8)->get();

            $dateBefore30 = date('Y-m-d', strtotime('today - 30 days'));
            $data['latestCustomer'] = User::where('created_at', '>', $dateBefore30)
                ->where('status', 'Active')
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'Customer');
                })
                ->count();

            $view = view('dashboard.content')->with($data);
            return $view->render();
        }

        // Info box section
        $totalTickets = Ticket::all()->count();
        $totalDepartments = 0;
        $totalTestimonials = Testimonial::where('status', 'Active')->count();
        $totalCustomers = User::where('status', 'Active')->whereHas('roles', function ($q) {
            $q->where('name', 'Customer');
        })->count();

        $totalMobiles = Mobile::count();
        $totalRumoredMobiles = Mobile::where([
            ['status', 'LIKE', '%Rumored%'],
        ])->count();
        $totalComingSoonMobiles = Mobile::where([
            ['status', 'LIKE', '%Coming soon%'],
        ])->count();

        $totalAvailableMobiles = Mobile::where([
            ['status', 'LIKE', '%Available%'],
        ])->count();

        $totalDiscontinuedMobiles = Mobile::where([
            ['status', 'LIKE', '%Discontinued%'],
        ])->count();

        $data['totalTickets'] = CommonHelper::numberFormatIndia($totalTickets);
        $data['totalDepartments'] = CommonHelper::numberFormatIndia($totalDepartments);
        $data['totalCustomers'] = CommonHelper::numberFormatIndia($totalCustomers);
        $data['totalTestimonials'] = CommonHelper::numberFormatIndia($totalTestimonials);
        $data['totalMobiles'] = CommonHelper::numberFormatIndia($totalMobiles);
        $data['totalRumoredMobiles'] = CommonHelper::numberFormatIndia($totalRumoredMobiles);
        $data['totalComingSoonMobiles'] = CommonHelper::numberFormatIndia($totalComingSoonMobiles);
        $data['totalAvailableMobiles'] = CommonHelper::numberFormatIndia($totalAvailableMobiles);
        $data['totalDiscontinuedMobiles'] = CommonHelper::numberFormatIndia($totalDiscontinuedMobiles);

        return view('dashboard.main')->with($data);
    }

    public function getTestimonialBoxDetails($startDate, $endDate)
    {
        $testimonials = [];
        $maxTestimonialCount = 0;
        $totalTestimonialCount = 0;

        $circleClasses = [
            'circle-100-success',
            'circle-100-primary',
            'circle-100-info',
            'circle-100-warning',
            'circle-100-danger'
        ];

        $progressBarClasses = [
            'progress-bar-success',
            'progress-bar-primary',
            'progress-bar-info',
            'progress-bar-warning',
            'progress-bar-danger'
        ];

        for ($i = 5; $i >= 1; $i--) {
            $className = $progressBarClasses[5 - $i];
            $testimonialCount = Testimonial::where('status', 'Active')
                ->where('rating', $i)
                ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                ->count();

            $totalTestimonialCount += $testimonialCount;

            if ($testimonialCount > $maxTestimonialCount) {
                $maxTestimonialCount = $testimonialCount;
            }

            if ($i == 1) {
                $name = '1 Star';
            } else {
                $name = $i . ' Stars';
            }

            $testimonials[] = (object)[
                'name' => $name,
                'className' => $className,
                'count' => $testimonialCount,
            ];
        }

        $selectedCircleClass = '';
        for ($i = 0; $i < 5; $i++) {
            if (empty($selectedCircleClass) && $testimonials[$i]->count == $maxTestimonialCount) {
                $selectedCircleClass = $circleClasses[$i];
            }

            if ($totalTestimonialCount > 0) {
                $testimonials[$i]->percent = intval($testimonials[$i]->count / $totalTestimonialCount * 100);
            } else {
                $testimonials[$i]->percent = 0;
            }
        }

        return [
            'testimonials' => $testimonials,
            'totalTestimonialCount' => $totalTestimonialCount,
            'selectedCircleClass' => $selectedCircleClass,
        ];
    }

    public function getPieChart($startDate, $endDate, $departments)
    {
        $cnt = 0;
        $pieChart = [];
        $colors = [
            '#f56954',
            '#00a65a',
            '#f39c12',
            '#00c0ef',
            '#3c8dbc',
            '#d2d6de',
            '#5E85F3',
            '#F5A8C1',
            '#82A69D',
            '#F3CCC5',
            '#BAEFEC',
            '#DEB0DE'
        ];

        foreach ($departments as $department) {
            $color = $colors[$cnt % 12];
            $department->color = $color;
            $department->ticket_count = Ticket::where('department_id', $department->id)
                ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                ->count();

            $pieChart[] = (object)[
                'value' => $department->ticket_count,
                'color' => $color,
                'highlight' => $color,
                'label' => $department->name
            ];

            $cnt++;
        }

        return json_encode($pieChart);
    }

    public function getLineChart($startDate, $endDate)
    {
        $diff = abs(strtotime($endDate) - strtotime($startDate));
        $days = intval($diff / (60 * 60 * 24));
        $lastDistance = 0;
        $lineChartData = [];

        $distance = intval(ceil($days / 12));
        $max = intval(floor($days / $distance));

        if ($max * $distance < $days) {
            $lastDistance = $days - ($max * $distance);
        }

        for ($i = 0; $i <= $max; $i++) {
            if ($i < $max) {
                $date1 = date('Y-m-d', strtotime($startDate. ' + ' . ($i * $distance) . ' day'));
                $date2 = date('Y-m-d', strtotime($startDate. ' + ' . (($i + 1) * $distance) . ' day'));
            } else {
                if ($lastDistance == 0) {
                    continue;
                }

                $date1 = date('Y-m-d', strtotime($startDate. ' + ' . $max . ' day'));
                $date2 = date('Y-m-d', strtotime($startDate. ' + ' . ($max + $lastDistance) . ' day'));
            }

            $newTickets = Ticket::where('status', 'Open')
                ->where('agent_action', 'New')
                ->whereBetween(DB::raw('DATE(created_at)'), [$date1, $date2])
                ->count();

            $answered = Ticket::where('status', 'Open')
                ->where('agent_action', 'Answered')
                ->whereBetween(DB::raw('DATE(created_at)'), [$date1, $date2])
                ->count();

            $notAnswered = Ticket::where('status', 'Open')
                ->where('agent_action', 'Not Answered')
                ->whereBetween(DB::raw('DATE(created_at)'), [$date1, $date2])
                ->count();

            $lineChartData[] = (object) [
                'y' => $date2,
                'item1' => $newTickets,
                'item2' => $answered,
                'item3' => $notAnswered,
            ];
        }

        return json_encode($lineChartData);
    }

    public function getBusiestTimeChart($startDate, $endDate)
    {
        $ticketCountList = [];
        $timeSlot = [10, 12, 14, 16, 18, 20, 22, 24, 2, 4, 6, 8];
        for ($i = 0; $i < 7; $i++) {

            $sortableList = [];
            for ($j = 0; $j < 12; $j++) {
                $startHour = $timeSlot[$j] % 24;
                $closeHour = $timeSlot[($j + 1) % 12];

                $ticketCount = Ticket::where(DB::raw('WEEKDAY(created_at)'), '=', $i)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                    ->whereTime('created_at', '>=', $startHour .':00:00')
                    ->whereTime('created_at', '<', $closeHour .':00:00')
                    ->count();

                $sortableList[] = $ticketCount;
                $ticketCountList[$i][$j]['ticketCount'] = $ticketCount;
            }

            rsort($sortableList);
            for ($j = 0; $j < 12; $j++) {
                $ticketCount = $ticketCountList[$i][$j]['ticketCount'];
                if ($ticketCount == 0) {
                    $ticketCountList[$i][$j]['colorClass'] = 'green0';
                } else {
                    $index = array_search($ticketCount, $sortableList, true);
                    if ($index == 0) {
                        $ticketCountList[$i][$j]['colorClass'] = 'green900';
                    } else if ($index == 1) {
                        $ticketCountList[$i][$j]['colorClass'] = 'green800';
                    } else if ($index == 2) {
                        $ticketCountList[$i][$j]['colorClass'] = 'green700';
                    } else if ($index == 3) {
                        $ticketCountList[$i][$j]['colorClass'] = 'green600';
                    } else if ($index == 4) {
                        $ticketCountList[$i][$j]['colorClass'] = 'green500';
                    } else if ($index == 5) {
                        $ticketCountList[$i][$j]['colorClass'] = 'green400';
                    } else if ($index == 6) {
                        $ticketCountList[$i][$j]['colorClass'] = 'green300';
                    } else if ($index == 7) {
                        $ticketCountList[$i][$j]['colorClass'] = 'green200';
                    } else if ($index == 8) {
                        $ticketCountList[$i][$j]['colorClass'] = 'green100';
                    } else if ($index == 9 || $index == 10 || $index == 11) {
                        $ticketCountList[$i][$j]['colorClass'] = 'green50';
                    }
                }
            }
        }

        return $ticketCountList;
    }
}
