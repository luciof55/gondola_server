<?php

namespace App\Http\Helper;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class RequestHelper
{
    /**
     * Resolve is the current menu item based on menu name and url path.
     * @param "menu": menu name
     * @return boolean
     */
    public function isCurrentMenu(string $menuKey) {
      Log::info('menuKey: '.$menuKey);
      $routeCollection = Route::getRoutes();
      foreach ($routeCollection as $value) {
        //Log::info('POS: '.strpos($value->getName(), $menuKey));
        if ($value->getName() != '' && (strpos($value->getName(), $menuKey) !== false) && $value->matches(URL::getRequest())) {
            Log::info('Found: '.$value->uri().' - '.$value->getName());
            return true;
        }
      }
      return false;
    }

    /**
     * Create route for CRUD & exports.
     * @param "entity": entity key associated with the model
     * @param: "controller" : controller full qualified namespace
     * @param: "model" : model full qualified namespace
     * @param: "middlewares" : middlewares to add. Support: checkprivilege & owner.authorize
     * @return void
     */
    public function routeController(string $entity, string $controller, string $model, array $middlewares) {
      $addCheckPrivilege = false;
      $addOwnerAuthorize = false;

      $actions = ['export', 'index', 'create', 'store', 'update', 'edit', 'show', 'enable', 'delete'];

      foreach ($middlewares as $middleware) {
        if ($middleware == 'checkprivilege') {
          $addCheckPrivilege = true;
        }
        if ($middleware == 'owner.authorize') {
          $addOwnerAuthorize = true;
        }
      }

      foreach ($actions as $action) {
        if ($action == 'store') {
          $route = Route::post($this->getPath($action, $entity), $this->getController($action, $entity, $controller))->name($entity.'.'.$action);
        } else {
          if ($action == 'update') {
            $route = Route::put($this->getPath($action, $entity), $this->getController($action, $entity, $controller))->name($entity.'.'.$action);
          } else {
            $route = Route::get($this->getPath($action, $entity), $this->getController($action, $entity, $controller))->name($entity.'.'.$action);
          }
        }
        if ($addCheckPrivilege) {
          $route->middleware($this->getCheckPrivilege($action, $entity));
        }
        if ($addOwnerAuthorize && $action != 'index' && $action != 'create' && $action != 'store' && $action != 'update') {
          $route->middleware($this->getOwnerAuthorize($action, $model));
        }
      }
    }

    protected function getOwnerAuthorize($action, $model) {
      $actions = ['export' => 'export', 'index' => 'index', 'create' => 'create', 'store' => 'create', 'update' => 'edit'
      , 'edit' => 'edit', 'show' => 'show', 'enable' => 'enable', 'delete' => 'delete'];

        $value = array_get($actions, $action);

        return 'owner.authorize:'.$model.','.$value;
    }

    protected function getCheckPrivilege($action, $entity) {
      $actions = ['export' => '', 'index' => '', 'create' => 'create', 'store' => 'create', 'update' => 'edit'
      , 'edit' => 'edit', 'show' => 'view', 'enable' => 'enable', 'delete' => 'remove'];

        $value = array_get($actions, $action);
        if ($value != '') {
          return 'checkprivilege:'.$entity.'_'.$value;
        } else {
          return 'checkprivilege:'.$entity;
        }
    }

    protected function getController($action, $entity, $controller) {
      $actions = ['export' => 'export', 'index' => 'index', 'create' => 'create', 'store' => 'store', 'update' => 'update'
      , 'edit' => 'edit', 'show' => 'show', 'enable' => 'enable', 'delete' => 'destroy'];

      $value = array_get($actions, $action);

      return $controller.'@'.$value;
    }

    protected function getPath($action, $entity) {
      $actions = ['export' => '?/export', 'index' => '?', 'create' => '?/create', 'store' => '?/store', 'update' => '?/{id}'
      , 'edit' => '?/{id}/edit', 'show' => '?/{id}', 'enable' => '?/{id}/enable', 'delete' => '?/{id}/delete'];

      $value = array_get($actions, $action);

      $replaced = str_replace_array('?', [$entity], $value);

      return $replaced;
    }
}
