<?php

declare(strict_types=1);

namespace App\Services\Http;

class Request
{
    public static function uri(): string
    {
        return trim(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
            '/'
        );
    }

    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Returns a parameter from GET or POST payload.
     * The optional default is returned, if the parameter is not found.
     *
     * @param string|null $key
     * @param mixed|null $default
     * @return string|array|null|int
     */
    public static function input(?string $key = null, mixed $default = null): string|array|null|int
    {
        if (null === $key) {
            $values = array_merge(
                $_GET,
                $_POST
            );

            foreach ($values as $key => $value) {
                $values[$key] = self::formatInputValue($value);
            }

            return $values;
        }

        if (true === array_key_exists($key, $_GET)) {
            return self::formatInputValue($_GET[$key]);
        }

        if (true === array_key_exists($key, $_POST)) {
            return self::formatInputValue($_POST[$key]);
        }

        return $default;
    }

    /**
     * Returns the list of uploaded files, optionally filtered by a given key (input name).
     *
     * @param string|null $key
     * @return UploadedFile[]
     */
    public static function files(string $key = null): array
    {
        if (null !== $key && !array_key_exists($key, $_FILES)) {
            return [];
        }

        if (null !== $key) {
            return self::buildFileArrayByKey($key);
        }

        $files = array_map(
            function ($key) {
                return self::buildFileArrayByKey($key);
            },
            array_keys($_FILES)
        );

        return array_fill_keys(array_keys($_FILES), $files);
    }

    /**
     * Rearranges a file array, to get meaningful file elements.
     *
     * @param string $key
     * @return UploadedFile[]
     */
    private static function buildFileArrayByKey(string $key): array
    {
        if (!array_key_exists($key, $_FILES)) {
            return [];
        }

        $files = $_FILES[$key];

        if (0 !== strlen($files['tmp_name'][0])) {
            return array_map(
                function ($index) use ($files) {
                    return (new UploadedFile())
                        ->setName($files['name'][$index])
                        ->setTempName($files['tmp_name'][$index])
                        ->setPath($files['full_path'][$index])
                        ->setType($files['type'][$index])
                        ->setSize($files['size'][$index])
                        ->setError($files['error'][$index]);
                },
                array_keys($_FILES[$key]['name'])
            );
        }

        return [];
    }

    /**
     * Returns a correct type for floats, integers, etc. coming as GET or POST parameters.
     *
     * @param mixed $value
     * @return float|int|string|null
     */
    private static function formatInputValue(mixed $value): float|int|string|null
    {
        $value = trim($value);

        if ($value === '') {
            $value = null;
        }
//
//        if (is_numeric($value) && !str_starts_with($value, '0')) {
//            if (str_contains($value, '.')) {
//                $value = (float)$value;
//            } else {
//                $value = (int)$value;
//            }
//        }

        return $value;
    }
}
