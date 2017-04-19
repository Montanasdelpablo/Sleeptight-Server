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
    $data = array('status' => 'server up and running!', 'curl' => curl_version());
    // Response in JSON
    $newResponse = $response->withJson($data);
    // Return de response
    return $newResponse;
  }

  public function client($request, $response, $args) {
    // Get id from url param
    $clientid = $args['id'];

    // Get token through Senz2
    $resp = $this->senz2->getToken();

    // Use token to fetch client data with client id
    $resp2 = $this->senz2->getClientData($clientid, $resp->access_token);

    // Response in JSON
    $newResponse = $response->withJson($resp2);

    // Return de response
    return $newResponse;
  }

  public function sensor($request, $response, $args) {
    // Get id from url param
    $sensorid = $args['id'];

    // Get token through Senz2
    $resp = $this->senz2->getToken();

    // Use token to fetch client data with client id ( sensor id, access_token, from and to ('2016-01-02 14:00:00', '2018-04-02 14:00:00'))
    $resp2 = $this->senz2->getSensorData($sensorid, $resp->access_token);

    // Response in JSON
    $newResponse = $response->withJson($resp2);

    // Return de response
    return $newResponse;
  }

  public function lastweek($request, $response, $args) {
    // Get id from url param
    $sensorid = $args['id'];

    // Get token through Senz2
    $resp = $this->senz2->getToken();

    // Use token to fetch client data with client id ( sensor id, access_token, from and to ('2016-01-02 14:00:00', '2018-04-02 14:00:00'))
    $resp2 = $this->senz2->getSensorLastWeek($sensorid, $resp->access_token);

    // Response in JSON
    $newResponse = $response->withJson($resp2);

    // Return de response
    return $newResponse;
  }

  public function lastday($request, $response, $args) {
    // Get id from url param
    $sensorid = $args['id'];

    // Get token through Senz2
    $resp = $this->senz2->getToken();

    // Use token to fetch client data with client id ( sensor id, access_token, from and to ('2016-01-02 14:00:00', '2018-04-02 14:00:00'))
    $resp2 = $this->senz2->getSensorLastDay($sensorid, $resp->access_token);

    // Response in JSON
    $newResponse = $response->withJson($resp2);

    // Return de response
    return $newResponse;
  }

  public function sensorbytime($request, $response, $args) {
    // Get id from url param
    $sensorid = $args['id'];
    $from = $args['from'];
    $to = $args['to'];

    // Get token through Senz2
    $resp = $this->senz2->getToken();

    // Use token to fetch client data with client id ( sensor id, access_token, from and to ('2016-01-02 14:00:00', '2018-04-02 14:00:00'))
    $resp2 = $this->senz2->getSensorData($sensorid, $resp->access_token, $from, $to);

    // Response in JSON
    $newResponse = $response->withJson($resp2);

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
    // Als request een post request is
    if ($request->isPost()){
      // Post data
      $parsedBody = $request->getParsedBody();

      // Username en password vanuit post data
      $username = strtolower($parsedBody['username']);
      $password = $parsedBody['password'];

      // Kijk of combinatie van username en password bestaat in de database
      $arr = $this->db->select("SELECT * FROM gebruiker WHERE username = :username AND password = :password", array(':username' => $username, ':password' => $password), true);
      // Als array niet leeg is
      if(!empty($arr)){
        // Set up data object met de benodigde informatie
        $data = array("status" => "Success", "user" => array('id' => $arr['id'], 'token' => $arr['token'], 'username' => $arr['username'], 'name' => $arr['name'], 'surname' => $arr['surname']));
        // Maak json van data arraay
        $newResponse = $response->withJson($data);
        // Return response(JSON)
        return $newResponse;
      } else {
        // Set up data object met de benodige informatie
        $data = array("error" => "404", "status" => "No users found for that credentials");
        // Maak json van data array
        $newResponse = $response->withJson($data);
        // Return response(JSON)
        return $newResponse;
      }
    }
    // Als request niet post request is
    else {
      // Set up data object
      $data = array("error" => "Not a post method");
      // Create JSON from data object
      $newResponse = $response->withJson($data);
      // Return response (JSON)
      return $newResponse;
    }

  }

  public function register($request, $response, $args){
    // Is request a post method?
    if ($request->isPost()){
      // Krijgt post data vanuit de body
      $parsedBody = $request->getParsedBody();

      // Pakt benodigde informatie (Activatiecode, Username, Password, Naam (optioneel), Surname (optioneel), Email (optioneel) )
      $activation = $parsedBody['activation'];
      $username = strtolower($parsedBody['username']);
      $password = $parsedBody['password'];
      $name = empty($parsedBody['name']) ? '' : $parsedBody['name'];
      $surname = empty($parsedBody['surname']) ? '' : $parsedBody['surname'];
      $email = empty($parsedBody['email']) ? '' : $parsedBody['email'];

      // Check if activation code is in table
      $arr = $this->db->select("SELECT * FROM codes WHERE code = :code", array(':code' => $activation));

      // Als array niet leeg is
      if(!empty($arr)){
        // Create token for user
        $arr2 = $this->db->select("SELECT * FROM token WHERE used = 0 LIMIT 1", array(), true);
        // Token in variabel
        $token = $arr2['token'];

        // Update de token table dat de token in use is (UPDATE functie returned hoeveel rows aangepast zijn)
        $affectedrows = $this->db->update('token', array('used' => 1), array('token' => $token));

        // Als er geen affected rows zijn
        if (!$affectedrows){
          // Maak data object
          $data = array("error" => "404", "status" => "Token not updated correctly");
          // Maak json van data object
          $newResponse = $response->withJson($data);
          // Return de response (JSON)
          return $newResponse;
        }
        // Insert nieuwe token voor andere nieuwe gebruikers
        $this->db->insert('token', array('token' => md5(uniqid(rand(), true))));

        // Prepare user with details to register
        $user = array("username" => $username, "password" => $password, "name" => $name, "surname" => $surname, "email" => $email, "token" => $token);

        // Insert into db
        $id = $this->db->insert("gebruiker", $user);

        // If returned id
        if($id){
          // Creer data array
          $data = array("status" => "Success");
          // Creer JSON vanuit data array
          $newResponse = $response->withJson($data);
          // Returned response (JSON)
          return $newResponse;
        }
        // Inserting went wrong
        else {
          // Set up data array
          $data = array("error" => "404", "status" => "Inserting went wrong");
          // Creer JSON vanuit data array
          $newResponse = $response->withJson($data);
          // Returned the response (JSON)
          return $newResponse;
        }
      } else {
        // Return false because no valid activation code
        // Set up data array
        $data = array("error" => "404", "status" => "Not a valid activation code");
        // Convert data array into JSON
        $newResponse = $response->withJson($data);
        // Return response (JSON)
        return $newResponse;
      }
    }
    // Als geen post request is
    else {
      // set up data array
      $data = array("error" => "Not a post method");
      // Convert data array into JSON
      $newResponse = $response->withJson($data);
      // Return response (JSON)
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
