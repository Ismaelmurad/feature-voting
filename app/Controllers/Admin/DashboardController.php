<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Customer;
use App\Models\Feature;
use App\Models\FeatureVoting;
use App\Services\Http\Response;

class DashboardController extends Controller
{
    /** Returns dashboard data */
    public function index(): Response
    {
        $this->guard();

        $amountPositive = FeatureVoting::query()
            ->select(['*'])
            ->where('value', '=', 1)
            ->count();

        $amountNegative = FeatureVoting::query()
            ->select(['*'])
            ->where('value', '=', 0)
            ->count();

        $amountCustomersVisitedEver = Customer::query()
            ->whereNotNull('first_visit_at')
            ->count();

        $interval = \DateInterval::createFromDateString('30 days');
        $oneMonthAgo = now()->sub($interval)->format('Y-m-d H:i:s');
        $amountCustomersVisitedLastMonth = Customer::query()
            ->where('last_visit_at', '>', $oneMonthAgo)
            ->count();

        $amountCustomersWhoVoted = Customer::query()
            ->where('votes_total', '>', '0')
            ->count();

        return $this->view(
            'dashboard/index',
            [
                'amountCustomers' => Customer::query()->count(),
                'amountFeatures' => Feature::query()->count(),
                'amountVotes' => FeatureVoting::query()->count(),
                'amountPositive' => $amountPositive,
                'amountNegative' => $amountNegative,
                'amountCustomersVisitedEver' => $amountCustomersVisitedEver,
                'amountCustomersVisitedLastMonth' => $amountCustomersVisitedLastMonth,
                'amountCustomersWhoVoted' => $amountCustomersWhoVoted,
            ]
        );
    }
}
