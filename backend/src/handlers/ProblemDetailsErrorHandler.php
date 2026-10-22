<?php

namespace App\Handlers;

use App\Responses\ProblemDetailsResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpException;
use Slim\Exception\HttpMethodNotAllowedException;
use Throwable;

class ProblemDetailsErrorHandler
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        $status = 500;
        $title = "Internal Server Error";
        $detail = "Une erreur interne est survenue.";

        if ($exception instanceof HttpException) {
            $exceptionStatus = $exception->getCode();

            if ($exceptionStatus >= 400 && $exceptionStatus <= 599) {
                $status = $exceptionStatus;
            }

            $title = $exception->getTitle() ?: "HTTP Error";
            $detail = $exception->getDescription() ?: $exception->getMessage();
        }

        if ($logErrors) {
            error_log((string) $exception);
        }

        $response = ProblemDetailsResponse::create(
            response: $this->responseFactory->createResponse(),
            status: $status,
            title: $title,
            detail: $detail
        );

        if (
            $exception instanceof HttpMethodNotAllowedException
            && $exception->getAllowedMethods() !== []
        ) {
            $response = $response->withHeader(
                "Allow",
                implode(", ", $exception->getAllowedMethods())
            );
        }

        return $response;
    }
}