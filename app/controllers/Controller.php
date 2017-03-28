<?php

namespace App\Controllers;

class Controller {

  protected $container;
  protected $masterkey = "YOUR_SECRET_CODE";

  public function __construct($container){
    $this->container = $container;
  }

  public function __get($property){
    if($this->container->{$property}){
      return $this->container->{$property};
    }
  }

  public function index($request, $response){
    // Sample data
    $data = array('name' => 'Pablo', 'age' => 22);
    $newResponse = $response->withJson($data);
    return $newResponse;
    //return $this->view->render($response, 'index.html', [
    //    'name' => '',
    //]);
  }

  public function hello($request, $response, $args){
    // Sample data
    $data = array('intent' => 'Hello', 'to' => $args['name']);
    $newResponse = $response->withJson($data);
    return $newResponse;
    //return $this->view->render($response, 'index.html', [
    //    'name' => '',
    //]);
  }

  public function action($request, $response, $args){
    // Sample data
    $data = array('intent' => $args['intent'], 'who' => $args['uid']);
    $newResponse = $response->withJson($data);
    return $newResponse;
    //return $this->view->render($response, 'index.html', [
    //    'name' => '',
    //]);
  }

  public function protected($request, $response, $args){
    // Sample data
    if ($args['key'] == $this->masterkey){
      $data = array('success' => true, 'connected' => true, 'errors' => false);
      $newResponse = $response->withJson($data);
      return $newResponse;
    } else {
      $data = array('success' => false, 'connected' => false, 'errors' => ['key' => 'Wrong master key']);
      $newResponse = $response->withJson($data);
      return $newResponse;
    }


    //return $this->view->render($response, 'index.html', [
    //    'name' => '',
    //]);
  }

}

?>
