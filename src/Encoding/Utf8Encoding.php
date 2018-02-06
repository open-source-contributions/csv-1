<?php

namespace Odan\Encoding;

/**
 * Class Utf8Encoding
 */
final class Utf8Encoding implements EncodingInterface
{
    /**
     * Encodes an ISO-8859-1 string to UTF-8.
     *
     * @param mixed $data string or array
     * @return mixed string or array
     */
    public function encode($data)
    {
        return utf8_encode($data);
    }
}
