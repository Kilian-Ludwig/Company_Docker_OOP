<?php

class PageNotFoundController implements ControllerInterface
{

    public function show(int $id): void
    {
        http_response_code(404);
        echo 'Pagenotfound';

    }

    public function showall(): void
    {
        http_response_code(404);
        echo 'Pagenotfound';
    }

    public function create(): void
    {
        http_response_code(404);
        echo 'Pagenotfound';
    }

    public function update(int $id): void
    {
        http_response_code(404);
        echo 'Pagenotfound';
    }

    public function delete(int $id): void
    {
        http_response_code(404);
        echo 'Pagenotfound';
    }
}