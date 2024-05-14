<?php

/**
 * Dumps variables as preformatted text and dies.
 *
 * @param ...$args
 * @return void
 */
function dd(...$args): void
{
    dump(...$args);
    die();
}

/**
 * Dumps variables as preformatted text.
 *
 * @param ...$args
 * @return void
 */
function dump(...$args): void
{
    print '<pre>';
    var_dump(...$args);
    print '</pre>';
}

function formatDate(string|null $date, string $format = 'Y-m-d H:i:s'): ?string
{
    if (null === $date) {
        return null;
    }

    $date = trim($date);

    if (in_array($date, ['', '0000-00-00 00:00:00', null])) {
        return null;
    }

    return (DateTime::createFromFormat('Y-m-d H:i:s', $date, new DateTimeZone('UTC')))
        ->format($format);
}

/**
 * @throws Exception
 */
function now(): DateTime
{
    return (new \DateTime('now', new \DateTimeZone('utc')));
}
