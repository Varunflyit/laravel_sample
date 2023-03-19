<?php

namespace Ecommify\Platform\Exceptions;

use RuntimeException;

class ApiRequestException extends RuntimeException
{
    /**
     * API response
     *
     * @var array
     */
    protected $response;

    /**
     * Set response
     *
     * @param array $response
     * @return $this
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get response
     *
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }
}
