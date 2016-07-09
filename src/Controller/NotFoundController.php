<?php
declare(strict_types = 1);

namespace Zortje\MVC\Controller;

// @todo custom, should not be in this repo

/**
 * Class NotFoundController
 *
 * @package Zortje\MVC\Controller
 */
class NotFoundController extends Controller
{

    protected $access = [
        'index' => Controller::ACTION_PUBLIC
    ];

    protected function index()
    {
        $this->setResponseCode(404);
    }
}
