<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Customer;
use App\Models\Feature;
use App\Models\Picture;
use App\Models\Suggestion;
use App\Services\Http\Request;
use App\Services\Http\Response;
use App\Services\Http\ResponseInterface;
use DateTime;
use DateTimeZone;

class SuggestionController extends Controller
{
    /** list of suggestions*/
    public function index(): Response
    {
        $this->guard();

        $query = Suggestion::query()
            ->select([
                '*'
            ]);

        $name = Request::input('name');

        if (null !== $name) {
            $query->where('name', 'LIKE', '%' . Request::input('name') . '%');
        }

        $status = Request::input('status');

        if (null !== $status) {
            if ('0' === $status) {
                $query->whereNotNull('declined_at');
            } elseif ('1' === $status) {
                $query->whereNotNull('accepted_at');
            } elseif ('2' === $status) {
                $query->whereNull('accepted_at');
                $query->whereNull('declined_at');
            }
        }

        if (null !== Request::input('orderBy') && null !== Request::input('direction')) {
            $orderBy = Request::input('orderBy');
            $direction = Request::input('direction');
        } else {
            $orderBy = 'created_at';
            $direction = 'DESC';
        }

        $suggestions = $query->order($orderBy, $direction)
            ->paginate(
                (int)Request::input('page', 1),
                (int)Request::input('perPage', 15),
            );

        // Binds each suggestion item to its customer.
        $customerIds = [];

        foreach ($suggestions['items'] as $suggestion) {
            if (false === in_array($suggestion->getCustomerId(), $customerIds)) {
                $customerIds[] = $suggestion->getCustomerId();
            }
        }

        $customers = [];

        if (0 !== count($suggestions)) {
            $customers = Customer::query()
                ->whereIn('id', $customerIds)
                ->keyBy('id')
                ->get();
        }

        return $this->view(
            '/suggestions/index',
            [
                'customers' => $customers,
                'total' => $suggestions['total'],
                'items' => $suggestions['items'],
                'totalPages' => $suggestions['totalPages'],
                'offset' => $suggestions['offset'],
                'limit' => $suggestions['limit'],
                'page' => $suggestions['page'],
                'perPage' => $suggestions['perPage'],
                'old' => [
                    ...Request::input(),
                    'perPage' => Request::input('perPage', 15),
                    'orderBy' => $orderBy,
                    'direction' => $direction,
                    'status' => Request::input('status'),
                ]
            ]
        );
    }


    public function accept(): ResponseInterface
    {
        $this->guard(preventReadOnlyUser: true);
        $suggestion = Suggestion::find((int)Request::input('id'));

        if (null === $suggestion) {
            return $this->redirectNotFound();
        }

        $feature = (new Feature())
            ->setName($suggestion->getName())
            ->setDescription($suggestion->getText())
            ->setSuggestionId($suggestion->getId())
            ->save();

        $now = new DateTime('now', new DateTimeZone('UTC'));
        $suggestion
            ->setAcceptedAt($now->format('Y-m-d H:i:s'))
            ->setFeatureId($feature->getId())
            ->save();

        return $this->redirect('/suggestions');
    }

    public function decline(): ResponseInterface
    {
        $this->guard(preventReadOnlyUser: true);

        $suggestion = Suggestion::find((int)Request::input('id'));
        $now = new DateTime('now', new DateTimeZone('UTC'));

        ($suggestion)->setDeclinedAt($now->format('Y-m-d H:i:s'))->save();

        return $this->redirect('/suggestions');
    }

    public function info(): ResponseInterface
    {
        $this->guard();

        $suggestion = Suggestion::find((int)Request::input('id'));
        $customer = Customer::find($suggestion->getCustomerId());
        $pictures = Picture::query()
            ->select(['*'])
            ->where('suggestion_id', '=', $suggestion->getId())
            ->get();

        return $this->view(
            'suggestions/details/info',
            [
                'suggestion' => $suggestion,
                'customer' => $customer,
                'pictures' => $pictures,
            ]
        );
    }

    public function pictures(): ResponseInterface
    {
        $this->guard();
        $suggestion = Suggestion::find((int)Request::input('id'));

        /** @var Picture $picture */
        $pictures = Picture::query()
            ->select(['*'])
            ->where('suggestion_id', '=', $suggestion->getId())
            ->get();

        $base64 = [];

        foreach ($pictures as $picture) {
            $base64[] = self::getBase64(
                folder: 'customers',
                picture: $picture
            );
        }

        return $this->view(
            'suggestions/details/pictures',
            [
                'suggestion' => $suggestion,
                'base64' => $base64,
            ]
        );
    }

    /**
     * Returns an image for a feature suggestion.
     *
     * @return ResponseInterface
     */
    public function picture(): ResponseInterface
    {
        $this->guard();
        $name = Request::input('id');

        if (null === $name) {
            return $this->redirectNotFound();
        }

        $picture = Picture::findBy('uuid', $name);

        if (null === $picture) {
            return $this->redirectNotFound();
        }

        $pictureFile = file_get_contents(
            'uploads/customers'
            . '/'
            . $picture->getUuid()
            . '/'
            . $picture->getName()
        );

        return (new Response())
            ->setContent($pictureFile)
            ->setContentType($picture->getMimeType())
            ->setStatusCode(200);
    }
}
