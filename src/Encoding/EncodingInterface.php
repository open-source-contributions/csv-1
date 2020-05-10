<?php

namespace Selective\Encoding;

/**
 * The Encoding Interface
 */
interface EncodingInterface
{
    /**
     * Encode data.
     *
     * @param string|null $data Data to encode as string or array
     *
     * @return string|null Encoded string or array
     */
    public function encode(?string $data): ?string;
}
