<?php

namespace App\Controllers;

class Controller {

  // Container vanuit de app
  protected $container;
  // Masterkey
  protected $masterkey = "YOUR_SECRET_CODE";

  public function __construct($container){
    $this->container = $container;
  }

  public function __get($property){
    // Als de property bestaat
    if($this->container->{$property}){
      // Return de property
      return $this->container->{$property};
    }
  }

  public function index($request, $response){
    // Sample data
    $data = array('name' => 'Pablo', 'age' => 22);
    // Response in JSON
    $newResponse = $response->withJson($data);
    // Return de response
    return $newResponse;
  }

  public function hello($request, $response, $args){
    // Sample data vanuit url
    $data = array('intent' => 'Hello', 'to' => $args['name']);
    // Response in JSON
    $newResponse = $response->withJson($data);
    // Return de response
    return $newResponse;
  }

  public function action($request, $response, $args){
    // Sample data vanuit url
    $data = array('intent' => $args['intent'], 'who' => $args['uid']);
    // Response in JSON
    $newResponse = $response->withJson($data);
    // Return de response
    return $newResponse;
  }

  public function gebruikers($request, $response){
    // Sample data vanuit url
    $data = $this->db->select("SELECT * FROM gebruiker");
    // Response in JSON
    $newResponse = $response->withJson($data);
    // Return de response
    return $newResponse;
  }

  public function gebruiker($request, $response, $args){
    // Query gebruiker_id vanuit url
    $data = $this->db->select("SELECT * FROM gebruiker WHERE gebruiker_id = :id", array(':id' => $args['uid']), true);
    // Response in JSON
    $newResponse = $response->withJson($data);
    // Return de response
    return $newResponse;
  }

  public function auth($request, $response, $args){
    // Query gebruiker_id vanuit url
    if ($request->isPost()){
      $parsedBody = $request->getParsedBody();
      $username = strtolower($parsedBody['username']);
      $password = strtolower($parsedBody['password']);

      $arr = $this->db->select("SELECT * FROM gebruiker WHERE username = :username AND password = :password", array(':username' => $username, ':password' => $password), true);
      if(!empty($arr)){
        $data = array("status" => "Success", "user" => array('id' => $arr['id'], 'token' => $arr['token'], 'username' => $arr['username'], 'name' => $arr['name'], 'surname' => $arr['surname']));
        $newResponse = $response->withJson($data);
        return $newResponse;
      } else {
        $data = array("error" => "404", "status" => "No users found for that credentials");
        $newResponse = $response->withJson($data);
        return $newResponse;
      }
    } else {
      $data = array("error" => "Not a post method");
      $newResponse = $response->withJson($data);
      return $newResponse;
    }

  }

  public function protect($request, $response, $args){
    // Check voor meegegeven key
    if ($args['key'] == $this->masterkey){
      // Data als key zelfde als masterkey is
      $data = array('success' => true, 'connected' => true, 'errors' => false);
      // Response in JSON
      $newResponse = $response->withJson($data);
      // Return de response
      return $newResponse;
    } else {
      // Data als key niet zelfde als masterkey is
      $data = array('success' => false, 'connected' => false, 'errors' => ['key' => 'Wrong master key']);
      // Response in JSON
      $newResponse = $response->withJson($data);
      // Return de response
      return $newResponse;
    }

  }

}

?>
