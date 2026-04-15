<?php

use App\Services\api\ApiResponseService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 
    })
    ->withExceptions(function (Exceptions $exceptions): void {

      $exceptions->render(function(AuthenticationException $e,Request $request){
        if($request->is("api/*")){
            return  ApiResponseService::response(403,"unauthenticated");
        }

      });

      $exceptions->render(function ( NotFoundHttpException $e,Request $request){

       if($request->is("api/*")){
            return  ApiResponseService::response(404,"not found");
        }
             
    });


      $exceptions->render(function ( AccessDeniedHttpException $e,Request $request){

       if($request->is("api/*")){
            return  ApiResponseService::response(403,"unauthorized");
        }
             
    });

      
        //
    })->create();
