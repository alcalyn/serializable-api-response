<?php

namespace Alcalyn\SerializableApiResponse;

class ApiResponse
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var array
     */
    private $headers;

    /**
     * @param mixed $data
     * @param int $statusCode
     * @param array $headers
     */
    public function __construct($data = null, $statusCode = 200, array $headers = array())
    {
        $this->data = $data;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     *
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     *
     * @return self
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     *
     * @return self
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }
}
