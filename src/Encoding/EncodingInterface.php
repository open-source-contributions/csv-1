<?php

namespace Selective\Encoding;

interface EncodingInterface
{
    /**
     * Encode data.
     *
     * @param mixed $data Data to encode as string or array
     *
     * @return mixed Encoded string or array
     */
    public function encode($data);
}
