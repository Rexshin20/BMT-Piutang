<?php

namespace App\Http\Controllers;

class ErrorController extends Controller
{
    public function show(string $code)
    {
        $pages = [
            '401' => [
                'title' => 'Unauthorized Access',
                'description' => 'Please log in with the appropriate credentials to access this resource.',
                'status' => 401,
                'actions' => ['back', 'home'],
            ],
            '403' => [
                'title' => 'Access Forbidden',
                'description' => "You don't have necessary permission to view this resource.",
                'status' => 403,
                'actions' => ['back', 'home'],
            ],
            '404' => [
                'title' => 'Oops! Page Not Found!',
                'description' => "It seems like the page you're looking for does not exist or might have been removed.",
                'status' => 404,
                'actions' => ['back', 'home'],
            ],
            '500' => [
                'title' => 'Oops! Something went wrong :\')',
                'description' => 'We apologize for the inconvenience. Please try again later.',
                'status' => 500,
                'actions' => ['back', 'home'],
            ],
            '503' => [
                'title' => 'Website is under maintenance!',
                'description' => "The site is not available at the moment. We'll be back online shortly.",
                'status' => 503,
                'actions' => ['learn-more'],
            ],
        ];

        if (! isset($pages[$code])) {
            abort(404);
        }

        $page = $pages[$code];

        return response()->view('errors.show', [
            'code' => $code,
            'title' => $page['title'],
            'description' => $page['description'],
            'actions' => $page['actions'],
        ], $page['status']);
    }
}
