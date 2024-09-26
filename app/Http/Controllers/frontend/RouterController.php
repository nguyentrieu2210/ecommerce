<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;

class RouterController extends Controller
{
    //

    protected $routerRepository;

    public function __construct(
        RouterRepository $routerRepository
    )
    {
        $this->routerRepository = $routerRepository;
    }

    public function index(string $canonical,Request $request, $page = 1) {
        $router = $this->routerRepository->findByCondition([['canonical', '=', $canonical]])->first();
        if(!is_null($router) && !empty($router)) {
            $method = 'index';
            $controller = 'App\Http\Controllers\Frontend\\' . $router->model . 'Controller';
            echo(app($controller)->{$method}($router->module_id, $request, $page));
        }
    }
}