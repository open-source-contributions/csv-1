<?php

namespace Selective\Encoding;

/**
 * ISO Encoding.
 */
final class IsoEncoding implements EncodingInterface
{
    /**
     * Returns a ISO-8859-1 encoded string or array.
     *
     * @param string|null $data Data
     *
     * @return string|null Encoded data
     */
    public function encode(?string $data): ?string
    {
        if ($data === null || $data === '') {
            return $data;
        }
        if (mb_check_encoding($data, 'UTF-8')) {
            return mb_convert_encoding($data, 'ISO-8859-1', 'auto');
        }

        return $data;
    }
}
