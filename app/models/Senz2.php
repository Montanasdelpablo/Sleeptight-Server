<?php



class Senz2 {

    private $username = "p.cleij@st.hanze.nl";
    private $password = "wachtwoord" ;
    private $client_secret = "uxyjgknjsys4ow0swockcc0g0gsgckcco0co4ks8owg4o8g4w";
    private $client_id = "2_115y6b025c740ssw4c4w0k0s8o00g0wsg4ggo4coc4g0o48ckk";

    public function __construct()
    {

    }

    public function getToken(){
      // Set up post data
      $data = array('client_id' => $this->client_id,
      			 'client_secret' => $this->client_secret,
      			 'username' => $this->username,
      			 'password' => $this->password,
      			 'grant_type' => 'password');

      // Encode data to json
      $data_string = json_encode($data);

      // Get cURL resource
      $curl = curl_init();

      // Set some options - we are passing in a useragent too here
      curl_setopt_array($curl, array(
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_URL => 'https://apiv1.makesenz2.nl/oauth/v2/token',
          CURLOPT_USERAGENT => 'Sample cURL Request',
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_POST => 1,
          CURLOPT_POSTFIELDS => $data
      ));

      // Send the request & save response to $resp
      $resp = curl_exec($curl);

      // Curl error handling
      if(!curl_exec($curl)){
          die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
      }

      // Close request to clear up some resources
      curl_close($curl);

      $resp = json_decode($resp);

      //$resp->access_token
      //$resp->refresh_token

      return $resp;
    }


}

?>