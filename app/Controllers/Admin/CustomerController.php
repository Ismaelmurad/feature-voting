<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Customer;
use App\Models\EmailLog;
use App\Services\Http\Request;
use App\Services\Http\Response;
use App\Services\Http\ResponseInterface;
use DateTime;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;


class CustomerController extends Controller
{
    /**
     * Returns the list of customers.
     *
     * @return Response
     */
    public function index(): Response
    {
        $this->guard();

        $query = Customer::query()
            ->select([
                'id',
                'name',
                'email',
                'votes_up',
                'votes_down',
                'votes_total',
                'created_at',
                'updated_at',
                'first_visit_at',
                'email_sent_at',
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

        $hasVisited = Request::input('has_visited');

        if (null !== $hasVisited) {
            if ('0' === $hasVisited) {
                $query->where('first_visit_at', '<=>', null);
            } elseif ('1' === $hasVisited) {
                $query->where('first_visit_at', '!=', 'NULL');
            }
        }

        if (null !== Request::input('orderBy') && null !== Request::input('direction')) {
            $orderBy = Request::input('orderBy');
            $direction = Request::input('direction');
        } else {
            $orderBy = 'name';
            $direction = 'ASC';
        }

        $customers = $query->order(
            $orderBy,
            $direction
        )
            ->paginate(
                (int)Request::input('page', 1),
                (int)Request::input('perPage', 15),
            );

        $mailedCustomer = Request::input('mailSucceedId') ? Customer::find((int)Request::input('mailSucceedId')) : null;

        return $this->view(
            'customers/index',
            [
                'total' => $customers['total'],
                'customers' => $customers['items'],
                'totalPages' => $customers['totalPages'],
                'offset' => $customers['offset'],
                'limit' => $customers['limit'],
                'page' => $customers['page'],
                'perPage' => $customers['perPage'],
                'direction' => $direction,
                'mailedCustomer' => $mailedCustomer,
                'old' => [
                    ...Request::input(),
                    'perPage' => Request::input('perPage', 15),
                    'orderBy' => $orderBy,
                    'direction' => $direction,
                ],
            ]
        );
    }

    /**
     * Returns the form to create new customers.
     *
     * @return Response
     */
    public function create(): Response
    {
        $this->guard(preventReadOnlyUser: true);

        return $this->view('customers/edit');
    }

    /**
     * Creates a new customer and redirects to the list of customers.
     * Shows the form to create the customer again on error.
     *
     * @return ResponseInterface
     */
    public function store(): ResponseInterface
    {
        $this->guard(preventReadOnlyUser: true);
        $errors = $this->validateStore();

        if (0 === count($errors)) {
            // SAVE
            try {
                (new Customer())
                    ->setName(Request::input('name'))
                    ->setHash(Customer::generateHash())
                    ->setContactPerson(Request::input('contact_person'))
                    ->setPhone(Request::input('phone'))
                    ->setEmail(Request::input('email'))
                    ->setAmountStaff((int)Request::input('amount_staff'))
                    ->setMonthlySales((int)Request::input('monthly_sales'))
                    ->save();
            } catch (Exception $e) {
                dd($e);
            }

            // If no errors => redirect to category list
            return $this->redirect('/customers');
        } else {
            // Otherwise show the form with errors and the old input
            return $this->view(
                'customers/edit',
                [
                    'errors' => $errors,
                    'old' => Request::input(),
                ]
            );
        }
    }

    /**
     * Validates the POST data for creating a new user.
     *
     * @return array
     */
    private function validateStore(): array
    {
        $errors = [];
        $name = Request::input('name');
        $email = Request::input('email');
        $phone = Request::input('phone');

        if (null !== $email && false === $this->validateEmail($email)) {
            $errors['email'] = 'Die angegebene E-Mail ist ungültig';
        }

        if (null !== $phone && false === $this->validatePhone($phone)) {
            $errors['phone'] = 'Die angegebene Telefonnummer ist ungültig';
        }

        if (null !== Customer::findByName($name)) {
            $errors['name'] = 'Dieser Name wird bereits verwendet.';
        }

        return $errors;
    }

    /**
     * Returns if the given string is a valid email address.
     *
     * @param string $email
     * @return bool
     */
    private function validateEmail(string $email): bool
    {
        $regex = '/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/';

        if (!preg_match($regex, $email)) {
            return false;
        }
        return true;
    }

    /**
     * Returns if the given string is a valid phone number.
     *
     * @param string $phone
     * @return bool
     */
    private function validatePhone(string $phone): bool
    {
        $regex = '/^\+?[0-9][0-9 \-]{7,32}$/';

        if (!preg_match($regex, $phone)) {
            return false;
        }

        return true;
    }

    /**
     * Returns the detail page of a single customer.
     *
     * @return ResponseInterface
     * @throws Exception
     */
    public function show(): ResponseInterface
    {
        $this->guard();

        $id = (int)Request::input('id');
        $customer = Customer::find($id);

        if (null === $customer) {
            return $this->redirectNotFound();
        }

        return $this->view(
            'customers/details',
            [
                'customer' => $customer,
                'qrCode' => $customer->getQrCode(),
            ]
        );
    }

    /**
     * Returns the edit form to change a customer.
     *
     * @return ResponseInterface
     * @throws Exception
     */
    public function edit(): ResponseInterface
    {
        $this->guard(preventReadOnlyUser: true);
        $id = (int)Request::input('id', 0);
        $customer = Customer::find($id);

        if (null === $customer) {
            return $this->redirectNotFound();
        }

        return $this->view(
            'customers/edit',
            [
                'id' => (int)Request::input('id'),
                'old' => (array)$customer,
            ]
        );
    }

    /**
     * Updates a customer entity and redirects to the list of customers.
     * Shows the edit form again, if the passed data is invalid.
     *
     * @return ResponseInterface
     */
    public function update(): ResponseInterface
    {
        $this->guard(preventReadOnlyUser: true);
        $errors = $this->validateEdit();

        if (0 === count($errors)) {
            // UPDATE
            try {
                $customer = Customer::find((int)Request::input('id'));
                $customer->update(Request::input());
            } catch (Exception $e) {
                dd($e);
            }

            // If no errors => redirect to features list
            return $this->redirect('/customers');
        } else {
            // Otherwise show the form with errors and the old input
            return $this->view(
                'customers/edit',
                [
                    'id' => (int)Request::input('id'),
                    'errors' => $errors,
                    'old' => Request::input(),
                ]
            );
        }
    }

    /**
     * Validates the POST data on editing a user.
     *
     * @return array
     */
    private function validateEdit(): array
    {
        $email = Request::input('email');
        $phone = Request::input('phone');
        $errors = [];

        if (null !== $email && false === $this->validateEmail($email)) {
            $errors['email'] = 'Die angegebene E-Mail ist ungültig';
        }

        if (null !== $phone && false === $this->validatePhone($phone)) {
            $errors['phone'] = 'Die angegebene Telefonnummer ist ungültig';
        }

        return $errors;
    }

    /**
     * Deletes a customer entity and redirects to the list of customers.
     *
     * @return ResponseInterface
     * @throws Exception
     */
    public function delete(): ResponseInterface
    {
        $this->guard(preventReadOnlyUser: true);
        $id = (int)Request::input('id', 0);
        $customer = Customer::find($id);
        $errors = $this->validateDelete($customer);

        if ($errors['id'] ?? null) {
            return $this->redirectNotFound();
        }

        if (0 === count($errors)) {
            $customer->delete();
        }

        return $this->redirect('/customers');
    }

    /**
     * @param Customer|null $customer
     * @return array
     */
    private function validateDelete(?Customer $customer): array
    {
        $errors = [];

        if (null === $customer) {
            $errors['id'] = 'Es existiert kein Kunde mit dieser ID.';
        }

        return $errors;
    }

    public function mail(): ResponseInterface
    {
        $customer = Customer::find((int)Request::input('id'));

        /** If an Email was already sent to this customer, redirect to confirmation page
         *  except if confirmation was already done.
         */
        if (EmailLog::query()
                ->select(['*'])
                ->where('customer_id', '=', $customer->getId())
                ->whereNull('error')
                ->get()
            &&
            '1' !== Request::input('confirm')
        ) {
            return $this->view(
                'email/customers/confirmation',
                [
                    'customer' => $customer,
                ]
            );
        }

        $errors = [];

        try {
            $this->sendMail($customer);
        } catch (Exception $exception) {
            $errors['message'] = $exception->getMessage();
            $errors['code'] = $exception->getCode();
        }

        /** Overwrite errors if there's no email */
        if (!$customer->getEmail()) {
            $errors['message'] = 'Für diesen Kunden wurde keine gültige E-Mail Adresse hinterlegt.';
            $errors['code'] = 'MAIL';
        }

        if ($errors) {
            return $this->view(
                'email/customers/failed',
                [
                    'customer' => $customer,
                    'errors' => [
                        'message' => $errors['message'],
                        'code' => $errors['code'],
                    ],
                ]
            );
        } else {
            return $this->redirect('/customers?mailSucceedId=' . $customer->getId());
        }
    }

    /**
     * @param Customer $customer
     * @return void
     * @throws \PHPMailer\PHPMailer\Exception
     */
    private function sendMail(Customer $customer): void
    {
        $mail = new PHPMailer(false);
        $mail->CharSet = 'UTF-8';
        $subject = 'Einladung zum Feature-Voting';
        $html = $this->view(
            'email/customers/invitation-html',
            [
                'customer' => $customer,
            ]
        )
            ->getContent();

        $text = $this->view(
            'email/customers/invitation-text',
            [
                'customer' => $customer,
            ]
        )
            ->getContent();

        $now = new DateTime('now');
        $log = (new EmailLog())
            ->setSubject($subject)
            ->setHtml($html)
            ->setText($text)
            ->setRecipient($customer->getName() . ' <' . $customer->getEmail() . '>')
            ->setSentAt($now->format('Y-m-d H:i:s'))
            ->setCustomerId($customer->getId());

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->Port = $_ENV['MAIL_PORT'];

            if (empty($_ENV['MAIL_USERNAME'])) {
                $mail->SMTPAuth = false;
            } else {
                $mail->SMTPAuth = true;
                $mail->Username = $_ENV['MAIL_USERNAME'];
                $mail->Password = $_ENV['MAIL_PASSWORD'];
            }

            $mail->SMTPSecure = match ($_ENV['MAIL_ENCRYPTION']) {
                'tls' => PHPMailer::ENCRYPTION_STARTTLS,
                'ssl' => PHPMailer::ENCRYPTION_SMTPS,
                default => null,
            };

            // Sender
            $mail->setFrom(
                $_ENV['MAIL_FROM_ADDRESS'],
                $_ENV['MAIL_FROM_NAME']
            );

            // Recipient
            $mail->addAddress(
                $customer->getEmail(),
                $customer->getContactPerson()
            );

            // Content
            $mail->isHTML();
            $mail->Subject = $subject;
            $mail->Body = $html;
            $mail->AltBody = $text;


            $mail->send();

            // Save log entry
            $log
                ->setSentAt($now->format('Y-m-d H:i:s'))
                ->save();

            // Update customer
            $customer
                ->setEmailSentAt($now->format('Y-m-d H:i:s'))
                ->save();
        } catch (\PHPMailer\PHPMailer\Exception $exception) {
            $log
                ->setError($exception->getMessage())
                ->save();

            throw $exception;
        }
    }
}

