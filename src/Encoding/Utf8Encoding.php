<?php

namespace Selective\Encoding;

/**
 * Utf8 Encoding.
 */
final class Utf8Encoding implements EncodingInterface
{
    /**
     * Encodes an ISO-8859-1 string to UTF-8.
     *
     * @param string|null $data Data
     *
     * @return string|null Encoded data
     */
    public function encode(?string $data): ?string
    {
        if ($data === null) {
            return null;
        }

        return utf8_encode($data);
    }
}
