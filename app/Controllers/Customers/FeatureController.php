<?php

declare(strict_types=1);

namespace App\Controllers\Customers;

use App\Controllers\Controller;
use App\Models\Customer;
use App\Models\Feature;
use App\Models\FeatureVoting;
use App\Services\Container\App;
use App\Services\Http\Request;
use App\Services\Http\ResponseInterface;
use DateTime;
use DateTimeZone;
use Exception;

class FeatureController extends Controller
{
    /** Returns every feature the customer didn't vote on yet */
    public function latest(): ResponseInterface
    {
        $this->guardCustomer();

        /** @var Customer|null $customer */
        $customer = App::get('session')->customer();

        if (null === $customer) {
            return $this->redirect('/login');
        }

        $votedFeatureIds = FeatureVoting::query()
            ->select([
                'feature_id',
            ])
            ->where('customer_id', '=', $customer->getId())
            ->getColumn();

        if (0 !== count($votedFeatureIds)) {
            $features = Feature::query()
                ->whereNotIn('id', $votedFeatureIds)
                ->get();
        } else {
            $now = new DateTime('now');
            $features = Feature::query()
                ->select([
                    '*',
                ])
                ->whereNull('voting_ends_at')
                ->orWhere('voting_ends_at', '>', $now->format('Y-m-d H:i:s'))
                ->get();
        }

        shuffle($features);

        return $this->view(
            'votings/latest',
            [
                'features' => $features
            ]
        );
    }

    /** Returns every feature the customer did vote on */
    public function history(): ResponseInterface
    {
        $this->guardCustomer();

        /** @var Customer|null $customer */
        $customer = App::get('session')->customer();

        if (null === $customer) {
            return $this->redirect('/login');
        }

        $votedFeatureIds = FeatureVoting::query()
            ->select([
                'feature_id',
            ])
            ->where('customer_id', '=', $customer->getId())
            ->getColumn();

        $features = [];

        if (0 !== count($votedFeatureIds)) {
            $features = Feature::query()
                ->whereIn('id', $votedFeatureIds)
                ->get();
        }

        $featureVotings = FeatureVoting::query()
            ->select([
                'feature_id',
                'value',
                'created_at',
                'comment',
            ])
            ->where('customer_id', '=', $customer->getId())
            ->keyBy('feature_id')
            ->get();

        $votings = [];

        foreach ($featureVotings as $voting) {
            $votings[$voting->feature_id] = $voting;
        }

        if (!isset($features)) {
            $features = [];
        }

        if (0 !== count($votings)) {
            foreach ($features as $feature) {
                $vote = $votings[$feature->id] ?? null;

                if (null !== $vote) {
                    $feature->voting = $vote->value;
                    $feature->voting_at = $vote->created_at;
                }
            }
        } else {
            foreach ($features as $feature) {
                $feature->voting = null;
                $feature->voting_at = null;
            }
        }

        $utc = new DateTimeZone('UTC');

        if (null !== $features) {
            usort(
                $features,
                function ($a, $b) use ($utc) {
                    $dateA = DateTime::createFromFormat('Y-m-d H:i:s', $a->voting_at, $utc);
                    $dateB = DateTime::createFromFormat('Y-m-d H:i:s', $b->voting_at, $utc);

                    if ($dateA === $dateB) {
                        return 0;
                    }

                    return $dateA > $dateB ? -1 : 1;
                }
            );
        }

        return $this->view(
            'votings/history',
            [
                'features' => $features ?? [],
                'featureVotings' => $featureVotings,
            ]
        );
    }

    public function confirmation(): ResponseInterface
    {
        $this->guardCustomer();

        $featureId = (int)Request::input('id');
        $choice = (int)Request::input('choice');

        /** @var Feature|null $feature */
        $feature = Feature::find($featureId);
        $now = new DateTime('now');

        if (null !== $feature->getVotingEndsAt() && $feature->getVotingEndsAt()->getTimestamp() <= $now->getTimestamp()) {
            return $this->redirect('/vote/expired/?id=' . $feature->getId());
        }

        return $this->view(
            'votings/confirmation',
            [
                'feature' => $feature,
                'choice' => $choice,
            ]
        );
    }

    public function expired(): ResponseInterface
    {
        $this->guardCustomer();
        $featureId = (int)Request::input('id');

        /** @var Feature|null $feature */
        $feature = Feature::find($featureId);

        if (null === $feature) {
            return $this->redirectNotFound();
        }

        return $this->view(
            'votings/expired',
            [
                'feature' => $feature,
            ]
        );
    }

    /**
     * Stores a vote for a customer.
     *
     * @return ResponseInterface
     * @throws Exception
     */
    public function submit(): ResponseInterface
    {
        $this->guardCustomer();

        $customerId = App::get('session')->customer()->id;
        $featureId = (int)Request::input('id');
        $choice = (int)Request::input('choice');
        $comment = Request::input('comment');

        /** @var Feature|null $feature */
        $feature = Feature::find($featureId);

        if (
            null === $feature ||
            false === $this->isValidVote($featureId, $customerId)
        ) {
            return $this->redirectNotFound();
        }

        if (0 === $choice) {
            /** @var Customer $customer */
            $customer = App::get('session')->customer();
            $customer->setVotesDown($customer->getVotesDown() + 1)
                ->setVotesTotal($customer->getVotesTotal() + 1)
                ->save();

            ($feature)
                ->setVotesDown($feature->getVotesDown() + 1)
                ->setVotesTotal($feature->getVotesTotal() + 1)
                ->save();
        } elseif (1 === $choice) {
            /** @var Customer $customer */
            $customer = App::get('session')->customer();
            $customer->setVotesUp($customer->getVotesUp() + 1)
                ->setVotesTotal($customer->getVotesTotal() + 1)
                ->save();
            ($feature)
                ->setVotesUp($feature->getVotesUp() + 1)
                ->setVotesTotal($feature->getVotesTotal() + 1)
                ->save();
        }

        ($feature)
            ->setScore(
                ($feature->getVotesUp() * 2) - ($feature->getVotesDown())
            )
            ->save();

        if (null !== $comment) {
            ($feature)
                ->setComments($feature->getComments() + 1)
                ->save();
        }

        (new FeatureVoting())
            ->setFeatureId($featureId)
            ->setCustomerId($customerId)
            ->setValue($choice)
            ->setComment($comment)
            ->save();

        return $this->redirect('/vote');
    }

    /** Checks whether the choice input is either 0 or 1
     *  and
     *  checks whether the customer has already voted on this feature
     */
    private function isValidVote(int $featureId, int $customerId): bool
    {
        if (false === in_array(Request::input('choice'), ['0', '1'])) {
            return false;
        }

        $feature = Feature::find($featureId);

        if (null === $feature) {
            return false;
        }

        $now = new DateTime('now');

        if (null !== $feature->getVotingEndsAt() && $feature->getVotingEndsAt()->getTimestamp() <= $now->getTimestamp()) {
            return false;
        }

        $existingVotes = FeatureVoting::query()
            ->select(['*'])
            ->where('feature_id', '=', $featureId)
            ->where('customer_id', '=', $customerId)
            ->limit(1)
            ->count();

        if (0 !== $existingVotes) {
            return false;
        }
        return true;
    }
}
