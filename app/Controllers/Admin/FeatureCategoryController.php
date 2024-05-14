<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\FeatureCategory;
use App\Services\Http\Request;
use App\Services\Http\Response;
use App\Services\Http\ResponseInterface;
use Exception;

class FeatureCategoryController extends Controller
{
    /** List of categories */
    public function index(): Response
    {
        $this->guard();
        $query = FeatureCategory::query()
            ->select([
                '*'
            ]);

        $name = Request::input('name');

        if (null !== $name) {
            $query->where('name', 'LIKE', '%' . Request::input('name') . '%');
        }

        if (null !== Request::input('orderBy') && null !== Request::input('direction')) {
            $orderBy = Request::input('orderBy');
            $direction = Request::input('direction');
        } else {
            $orderBy = 'name';
            $direction = 'ASC';
        }

        $categories = $query->order($orderBy, $direction)
            ->paginate(
                (int)Request::input('page', 1),
                (int)Request::input('perPage', 15),
            );

        return $this->view(
            'categories/index',
            [
                'total' => $categories['total'],
                'items' => $categories['items'],
                'totalPages' => $categories['totalPages'],
                'offset' => $categories['offset'],
                'limit' => $categories['limit'],
                'page' => $categories['page'],
                'perPage' => $categories['perPage'],
                'old' => [
                    ...Request::input(),
                    'perPage' => Request::input('perPage', 15),
                    'orderBy' => $orderBy,
                    'direction' => $direction,
                ],
            ]
        );
    }

    /** Form to create a categories GET */
    public function create(): Response
    {
        $this->guard(preventReadOnlyUser: true);

        return $this->view('categories/edit');
    }

    /** Storing a categories in the database POST */
    public function store(): ResponseInterface
    {
        $this->guard(preventReadOnlyUser: true);
        $errors = $this->validateStore();

        if (0 === count($errors)) {
            // SAVE
            try {
                (new FeatureCategory())
                    ->setName(Request::input('name'))
                    ->save();
            } catch (Exception $e) {
                dd($e);
            }

            // If no errors => redirect to category list
            return $this->redirect('/categories');
        } else {
            // Otherwise show the form with errors and the old input
            return $this->view(
                'categories/edit',
                [
                    'errors' => $errors,
                    'old' => Request::input(),
                ]
            );
        }
    }

    /** Validates the input before storing */
    private function validateStore(): array
    {
        $errors = [];
        $name = Request::input('name');

        if (null !== FeatureCategory::findByName($name)) {
            $errors['name'] = 'Dieser Name wird bereits verwendet.';
        }
        return $errors;
    }

    /** Shows the form to edit a category */
    public function edit(): ResponseInterface
    {
        $this->guard(preventReadOnlyUser: true);
        $id = (int)Request::input('id', 0);
        $category = FeatureCategory::find($id);

        if (null === $category) {
            return $this->redirectNotFound();
        }

        return $this->view(
            'categories/edit',
            [
                'id' => (int)Request::input('id'),
                'old' => (array)$category,
            ]
        );
    }

    /** Updates a categories */
    public function update(): ResponseInterface
    {
        $this->guard(preventReadOnlyUser: true);
        $errors = $this->validateEdit();

        if (0 === count($errors)) {
            // UPDATE
            try {
                $featureCategory = FeatureCategory::find((int)Request::input('id'));
                $featureCategory->update(Request::input());
            } catch (Exception $e) {
                dd($e);
            }

            // If no errors => redirect to category list
            return $this->redirect('/categories');
        } else {
            // Otherwise show the form with errors and the old input
            return $this->view(
                'categories/edit',
                [
                    'id' => (int)Request::input('id'),
                    'errors' => $errors,
                    'old' => Request::input(),
                ]
            );
        }
    }

    /** Validates the input before updating */
    private function validateEdit(): array
    {
        $errors = [];
        $name = Request::input('name');

        if (null !== FeatureCategory::findByName($name)) {
            $errors['name'] = 'Dieser Name wird bereits verwendet.';
        }
        return $errors;
    }

    /**
     * Deletes a category
     *
     * @throws Exception
     */
    public function delete(): ResponseInterface
    {
        $this->guard(preventReadOnlyUser: true);
        $id = (int)Request::input('id', 0);
        $category = FeatureCategory::find($id);
        $errors = $this->validateDelete($category);

        if (!empty($errors['id'])) {
            return $this->redirectNotFound();
        }

        if (0 === count($errors)) {
            $category->delete();
        }


        return $this->redirect('/categories');
    }

    /** Validates a category before deleting */
    private function validateDelete(?FeatureCategory $category): array
    {
        $errors = [];
        if (null === $category) {
            $errors['id'] = 'Es existiert keine Kategorie mit dieser ID.';
        }
        return $errors;
    }
}

