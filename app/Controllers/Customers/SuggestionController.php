<?php

declare(strict_types=1);

namespace App\Controllers\Customers;

use App\Controllers\Controller;
use App\Models\Picture;
use App\Models\Suggestion;
use App\Services\Container\App;
use App\Services\Http\Request;
use App\Services\Http\ResponseInterface;
use App\Services\Http\UploadedFile;
use Exception;
use Ramsey\Uuid\Uuid;

class SuggestionController extends Controller
{
    public function index(): ResponseInterface
    {
        $this->guardCustomer();

        return $this->view('votings/suggestion/form');
    }

    public function submit(): ResponseInterface
    {
        $this->guardCustomer();
        $errors = [];

        if (!Request::input('name')) {
            $errors['title']['missing'] = 'Bitte geben Sie einen Titel an.';
        }

        if (!Request::input('text')) {
            $errors['text']['missing'] = 'Bitte geben Sie einen Beschreibungstext ein.';
        }

        $pictures = Request::files('pictures');

        /** Return to form page if the number of pictures exceeds the limit */
        if (10 < count($pictures)) {
            $errors['file']['amount'] = 'Sie können maximal 10 Bilder hochladen.';

            return $this->view(
                'votings/suggestion/form',
                [
                    'errors' => $errors,
                    'old' => [
                        ...Request::input()
                    ],
                ]
            );
        }

        if ($pictures) {
            foreach ($pictures as $picture) {
                if ($this->validateFile($picture)) {
                    $errors['file'][$picture->getName()] = $this->validateFile($picture);
                }
            }
        }


        if (0 === count($errors)) {
            $suggestion = (new Suggestion())
                ->setName(Request::input('name'))
                ->setText(Request::input('text'))
                ->setCustomerId(
                    App::get('session')
                        ->customer()->id
                )
                ->save();

            if ($pictures) {
                foreach ($pictures as $picture) {
                    $this->savePicture(
                        suggestion: $suggestion,
                        picture: $picture
                    );
                }
            }
        } else {
            return $this->view(
                'votings/suggestion/form',
                [
                    'errors' => $errors,
                    'old' => [
                        ...Request::input()
                    ],
                ]
            );
        }

        return $this->view(
            'votings/suggestion/submitted',
            [
                'message' => 'Vielen Dank für Ihren Vorschlag!'
            ]
        );
    }

    /**
     *  Checks for mime type and file size, returns errors.
     *  Valid mime types and maximum file size can be configured in .env.
     */
    private function validateFile(UploadedFile $picture): ?string
    {
        $error = null;
        // Check mime type
        $allowedMimeTypes = explode(',', $_ENV['PICTURE_ALLOWED_MIME_TYPES']);

        if (false === in_array($picture->getType(), $allowedMimeTypes)) {
            $error = 'Unzulässiger Dateityp bei Datei ' . $picture->getName() . '.';
        }

        // Check file size
        $allowedFileSize = $_ENV['PICTURE_MAX_SIZE'];

        if ($picture->getSize() > $allowedFileSize) {
            $error = 'Die Datei ' . $picture->getName() . ' ist zu groß.';
        }

        return $error;
    }

    /**
     * Saves the given picture to both database and directory.
     * @param $suggestion
     * @param UploadedFile $picture
     * @return Picture|null
     * @throws Exception
     */
    private function savePicture($suggestion, UploadedFile $picture): ?Picture
    {
        $pictureSize = getimagesize($picture->getTempName());
        $uuid = Uuid::uuid4()->toString();

        // Save picture with its uuid name, in a folder that's named the same way
        $extension = pathinfo($picture->getName())['extension'];

        $isMoved = $picture->moveTo(
            'uploads/customers/' . $uuid . '/' . $uuid . '.' . $extension,
            true
        );

        if (false === $isMoved) {
            return null;
        }

        return (new Picture())
            ->setSuggestionId($suggestion->getId())
            ->setUuid($uuid)
            ->setName($uuid . '.' . $extension)
            ->setNameOriginal($picture->getName())
            ->setMimeType($picture->getType())
            ->setFilesize($picture->getSize())
            ->setWidth($pictureSize[0])
            ->setHeight($pictureSize[1])
            ->save();
    }
}


