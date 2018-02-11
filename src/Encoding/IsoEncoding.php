<?php

namespace Odan\Encoding;

/**
 * Class IsoEncoding
 */
final class IsoEncoding implements EncodingInterface
{
    /**
     * Returns a ISO-8859-1 encoded string or array.
     *
     * @param mixed $data string or array
     * @return mixed string or array
     */
    public function encode($data)
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
