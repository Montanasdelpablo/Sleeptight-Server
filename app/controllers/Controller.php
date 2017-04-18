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
    // Sample data vanuit url
    $data = array('status' => 'server up and running!');

    $resp = $this->senz2->getToken();
    // Response in JSON
    $newResponse = $response->withJson($resp);
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
    $data = $this->db->select("SELECT * FROM gebruiker WHERE id = :id", array(':id' => $args['uid']), true);
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
      $password = $parsedBody['password'];

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

  public function register($request, $response, $args){
    // Is request a post method?
    if ($request->isPost()){
      $parsedBody = $request->getParsedBody();
      $activation = $parsedBody['activation'];
      $username = strtolower($parsedBody['username']);
      $password = $parsedBody['password'];
      $name = empty($parsedBody['name']) ? '' : $parsedBody['name'];
      $surname = empty($parsedBody['surname']) ? '' : $parsedBody['surname'];
      $email = empty($parsedBody['email']) ? '' : $parsedBody['email'];

      // Check if activation code is in table
      $arr = $this->db->select("SELECT * FROM codes WHERE code = :code", array(':code' => $activation));

      if(!empty($arr)){

        // Create token for user
        $arr2 = $this->db->select("SELECT * FROM token WHERE used = 0 LIMIT 1", array(), true);
        $token = $arr2['token'];
        $affectedrows = $this->db->update('token', array('used' => 1), array('token' => $token));
        if (!$affectedrows){
          $data = array("error" => "404", "status" => "Token not updated correctly");
          $newResponse = $response->withJson($data);
          return $newResponse;
        }
        $this->db->insert('token', array('token' => md5(uniqid(rand(), true))));
        // Prepare user with details to register
        $user = array("username" => $username, "password" => $password, "name" => $name, "surname" => $surname, "email" => $email, "token" => $token);

        // Insert into db
        $id = $this->db->insert("gebruiker", $user);

        // If returned id
        if($id){
          $data = array("status" => "Success");
          $newResponse = $response->withJson($data);
          return $newResponse;
        }
        // Inserting went wrong
        else {
          $data = array("error" => "404", "status" => "Inserting went wrong");
          $newResponse = $response->withJson($data);
          return $newResponse;
        }
      } else {
        // Return false because no valid activation code
        $data = array("error" => "404", "status" => "Not a valid activation code");
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
