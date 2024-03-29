<?php
declare(strict_types=1);

/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\Filter\Rule\Sanitize;

use Psr\Http\Message\UploadedFileInterface;

/**
 *
 * Sets value to null if not an uploaded file
 *
 * @package Aura.Filter
 *
 */
class UploadedFileOrNull
{
    /**
     *
     * Forces the value to null if not an uploaded file.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke(object $subject, string $field): bool
    {
        $value = $subject->$field;

        if (! $value instanceof UploadedFileInterface
            || $value->getError() !==  UPLOAD_ERR_OK
        ) {
            $subject->$field = null;
        }

        return true;
    }
}
