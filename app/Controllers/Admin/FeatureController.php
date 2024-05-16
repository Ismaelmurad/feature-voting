<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Customer;
use App\Models\Feature;
use App\Models\FeatureCategory;
use App\Models\FeatureVoting;
use App\Services\Http\Request;
use App\Services\Http\Response;
use App\Services\Http\ResponseInterface;
use DateTime;
use Exception;

class FeatureController extends Controller
{
    /** List of features */
    public function index(): Response
    {
        $this->guard();

        $query = Feature::query()
            ->select([
                '*'
            ]);

        $name = Request::input('name');

        if (null !== $name) {
            $query->where('name', 'LIKE', '%' . Request::input('name') . '%');
        }

        $hasVotes = Request::input('has_votes');

        if (null !== $hasVotes) {
            if ('0' === $hasVotes) {
                $query->where('votes_total', '=', 0);
            } else {
                $query->where('votes_total', '>', 0);
            }
        }

        $hasComment = Request::input('has_comment');

        if (null !== $hasComment) {
            if ('0' === $hasComment) {
                $query->whereNull('comments');
            } else {
                $query->whereNotNull('comments');
            }
        }

        if (null !== Request::input('orderBy') && null !== Request::input('direction')) {
            $orderBy = Request::input('orderBy');
            $direction = Request::input('direction');
        } else {
            $orderBy = 'score';
            $direction = 'DESC';
        }

        if ('ASC' === $direction) {
            $arrow = '&#8595;';
        } elseif ('DESC' === $direction) {
            $arrow = '&#8593;';
        }

        $features = $query->order($orderBy, $direction)
            ->paginate(
                (int)Request::input('page', 1),
                (int)Request::input('perPage', 15),
            );

        return $this->view(
            'features/index',
            [
                'total' => $features['total'],
                'items' => $features['items'],
                'totalPages' => $features['totalPages'],
                'offset' => $features['offset'],
                'limit' => $features['limit'],
                'page' => $features['page'],
                'perPage' => $features['perPage'],
                'arrow' => $arrow,
                'old' => [
                    ...Request::input(),
                    'perPage' => Request::input('perPage', 15),
                    'orderBy' => $orderBy,
                    'direction' => $direction,
                ],
            ]
        );
    }

    /** Stores newly created feature in DB */
    public function store(): ResponseInterface
    {
        $this->guard(preventReadOnlyUser: true);
        $errors = $this->validateStore();

        if (0 === count($errors)) {
            $votingEndsAt = Request::input('voting_ends_at');
            // SAVE
            try {
                (new Feature())
                    ->setName(Request::input('name'))
                    ->setFeatureCategoryId((int)Request::input('feature_category_id'))
                    ->setDescription(Request::input('description', ''))
                    ->setVotingEndsAt($votingEndsAt)
                    ->save();
            } catch (Exception $e) {
                dd($e);
            }

            // If no errors => redirect to category list
            return $this->redirect('/features');
        } else {
            // Otherwise show the form with errors and the old input
            return $this->view(
                'features/edit',
                [
                    'errors' => $errors,
                    'old' => Request::input(),
                ]
            );
        }
    }

    /** Validates input before storing */
    private function validateStore(): array
    {
        $errors = [];
        $name = Request::input('name');

        if (null !== Feature::findByName($name)) {
            $errors['name'] = 'Dieser Name wird bereits verwendet.';
        }

        return $errors;
    }

    /** Returns the edit page */
    public function create(): Response
    {
        $this->guard(preventReadOnlyUser: true);

        $feature_categories = FeatureCategory::query()->order('name', 'asc')->get();

        return $this->view(
            'features/edit',
            [
                'feature_categories' => $feature_categories,
            ]
        );
    }

    /** Returns the detail page of a feature */
    public function info(): ResponseInterface
    {
        $this->guard();
        $id = (int)Request::input('id');
        $feature = Feature::find($id);

        if (null === $feature) {
            return $this->redirectNotFound();
        }

        if ($feature->feature_category_id) {
            $category = FeatureCategory::find($feature->feature_category_id)->name;
        } else {
            $category = 'N/A';
        }

        return $this->view('features/details/info', [
            'feature' => $feature,
            'category' => $category,
        ]);
    }

    public function comments(): ResponseInterface
    {
        $this->guard();
        $id = (int)Request::input('id');
        $feature = Feature::find($id);

        if (null === $feature) {
            return $this->redirectNotFound();
        }

        /** @var FeatureVoting[] $votings */
        $votings = FeatureVoting::query()
            ->select(['*'])
            ->where('feature_id', '=', $id)
            ->whereNotNull('comment')
            ->paginate((int)Request::input('page', 1), (int)Request::input('perPage', 15));

        $customerIds = [];

        foreach ($votings['items'] as $voting) {
            if (false === in_array($voting->getCustomerId(), $customerIds)) {
                $customerIds[] = $voting->getCustomerId();
            }
        }

        $customers = [];

        if (0 !== count($votings)) {
            $customers = Customer::query()
                ->whereIn('id', $customerIds)
                ->keyBy('id')
                ->get();
        }

        return $this->view(
            'features/details/comments',
            [
                'feature' => $feature,
                'votings' => array_reverse($votings['items']),
                'customers' => $customers,
                'total' => $votings['total'],
                'items' => $votings['items'],
                'totalPages' => $votings['totalPages'],
                'offset' => $votings['offset'],
                'limit' => $votings['limit'],
                'page' => $votings['page'],
                'perPage' => $votings['perPage'],
                'old' => [
                    ...Request::input(),
                    'perPage' => Request::input('perPage', 15),
                ]
            ]
        );
    }

    /** Returns the feature edit page */
    public function edit(): ResponseInterface
    {
        $this->guard(preventReadOnlyUser: true);
        $id = (int)Request::input('id', 0);

        /** @var Feature|null $feature */
        $feature = Feature::find($id);

        if (null === $feature) {
            return $this->redirectNotFound();
        }

        $feature_categories = FeatureCategory::query()->order('name', 'asc')->get();
        $feature->voting_ends_at = $feature->getVotingEndsAt()?->format('Y-m-d') ?? '';

        return $this->view(
            'features/edit',
            [
                'id' => (int)Request::input('id'),
                'old' => (array)$feature,
                'feature_categories' => $feature_categories,
            ]
        );
    }

    /** Updates the feature
     * @throws Exception
     */
    public function update(): ResponseInterface
    {
        $this->guard(preventReadOnlyUser: true);
        $feature = Feature::find((int)Request::input('id'));

        if (null === $feature) {
            return $this->redirectNotFound();
        }

        $errors = $this->validateEdit($feature);

        if (0 === count($errors)) {
            $votingEndsAt = Request::input('voting_ends_at');

            if (null !== $votingEndsAt) {
                $votingEndsAt = (new DateTime($votingEndsAt))
                    ->setTime(23, 59, 59)
                    ->format('Y-m-d H:i:s');
            }

            // UPDATE
            try {
                $feature->update([
                    ...Request::input(),
                    'voting_ends_at' => $votingEndsAt,
                ]);
            } catch (Exception $e) {
                dd($e);
            }

            // If no errors => redirect to features list
            return $this->redirect('/features');
        } else {
            // Otherwise show the form with errors and the old input
            return $this->view(
                'features/edit',
                [
                    'id' => (int)Request::input('id'),
                    'errors' => $errors,
                    'old' => Request::input(),
                ]
            );
        }
    }

    /** Validates input before updating */
    private function validateEdit(Feature $feature): array
    {
        $errors = [];
        $name = Request::input('name');

        if ($name !== $feature->getName() && null !== Feature::findByName($name)) {
            $errors['name'] = 'Dieser Name wird bereits verwendet.';
        }

        return $errors;
    }

    /** Deletes a feature */
    public function delete(): ResponseInterface
    {
        $this->guard(preventReadOnlyUser: true);
        $id = (int)Request::input('id', 0);
        $feature = Feature::find($id);

        if (null === $feature) {
            return $this->redirectNotFound();
        }

        $errors = $this->validateDelete($feature);

        if (0 === count($errors)) {
            $feature->delete();
        }

        return $this->redirect('/features');
    }

    /** Validates input before deletion */
    private function validateDelete(?Feature $feature): array
    {
        $errors = [];

        if (!($feature instanceof Feature)) {
            $errors['id'] = 'Es existiert keine Kategorie mit dieser ID.';
        }

        return $errors;
    }
}
