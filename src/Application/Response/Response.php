<?php

namespace MVC\Application\Response;

use Exception;
use JsonException;
use Throwable;

class Response implements ResponseInterface
{
    protected int $statusCode = 200;
    protected array $headers = [];
    protected string|array|null $content = null;

    /**
     * @param string|array|null $content
     * @param array $headers
     * @param int $statusCode
     */
    public function __construct(string|array|null $content = null, array $headers = [], int $statusCode = 200)
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->content = $content;
    }

    /**
     * @return array
     *
     * @throws Throwable
     */
    public function prepare(): array
    {
        return [
            'statusCode' => $this->statusCode,
            'headers' => $this->headers,
            'content' => $this->prepareContent()
        ];
    }

    /**
     * @return string
     *
     * @throws JsonException|Exception
     */
    protected function prepareContent(): string
    {
        if (is_null($this->content)) {
            return '';
        }

        if (is_string($this->content)) {
            return $this->content;
        }

        if (is_array($this->content)) {
            return json_encode($this->content, JSON_THROW_ON_ERROR);
        }

        throw new Exception("Undefined response content type!");
    }
}