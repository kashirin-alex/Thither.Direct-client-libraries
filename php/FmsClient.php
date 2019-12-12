<?php
/*
 * Author Kashirin Alex (kashirin.alex@gmail.com)
 * THITHER.DIRECT
 * */


class FmsClient {

  private $fid = "YourFlowId";
  private $https =  true;
  private $pass_phrase = "YourPassPhrase";
  private $cipher = "";
  private $keep_alive =  true;
  private $json =  true;

  private $api_version =  "v201807";
  private $root_url =  "://thither.direct/api/fms-";
  
  private $url_post =  "";
  private $url_get =  "";

  protected static $errors = array(
    0 => "param_empty",
    1 => "list_empty",
    2 => "csv_data_empty",
    5 => "bad_definition_type",
    6 => "bad_timestamps",
    7 => "bad_kwarg_value",
    8 => "bad_time_format",
  );
  
  function __construct($fid, $https, $pass_phrase, $cipher="", $keep_alive=false, $json=false) {
    $this->fid = $fid;
    $this->https = $https;
    $this->pass_phrase = $pass_phrase;
    $this->cipher = $cipher;
    $this->keep_alive = $keep_alive;
    $this->json = $json;
    $this->base_uri = "http" .($this->https? "s" : "") .$this->root_url .$this->api_version;
    $this->url_post = $this->base_uri . "/post/";
    $this->url_get  = $this->base_uri . "/get/";
  }
  
  function __destruct() {
    //$this->commit();

    //curl_close($Session);
  }

  public function utc_seconds() {
    return time();
  }

  private function bad_request($code) {
    return array("status" => "bad_request", "msg" => $this->errors[$code] );
  }


  private function set_params($params) {
    $params["fid"] = $this->fid;
    if(empty($this->cipher)) {
      $params["ps"] = $this->pass_phrase;
    
    } else if($this->cipher == "AES") {
      $params["token"] = "fdgfdgfdg";
      /*
      cipher = AES.new(b'' + self.ps.encode("utf-8"), AES.MODE_EAX)
      crp, tag = cipher.encrypt_and_digest(
          '|'.join([''.join(random.choice(chars) for _ in range(pads_len)),
                    str(self.utc_seconds()),
                    self.fid,
                    str(pads_len),
                    ''.join(random.choice(chars) for _ in range(pads_len))]
                   ).encode("utf-8"))

      $params["token"] = '|'.join([base64.b64encode(cipher.nonce), base64.b64encode(tag), base64.b64encode(crp)])
      */
    }
    return $params;
  }

  private function set_compression($params) {
    if(function_exists("gzcompress")) {
      $params['comp'] = function ($data) { return gzcompress($data); };
    }
    return $params;
  }

  private function post($url, $data) {
    //var_dump($url);
    //var_dump($data);

    $session = curl_init(); 
    curl_setopt($session, CURLOPT_URL, $url);
    curl_setopt($session, CURLOPT_POST, true);   
		curl_setopt($session, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($session, CURLOPT_POSTFIELDS, $data);
    curl_setopt($session, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($session, CURLOPT_CAINFO, $_ENV["CURL_CA_BUNDLE"]); // "/etc/ssl/certs/certs.pem"
    
    $result = curl_exec($session);
    curl_close($session);
   
    /*
    if (curl_error($session)) {
      trigger_error('Curl Error:' . curl_error($session));
    }
    */
    return json_decode($result);
  }

  public function push_single($mid, $dt, $v) {
    /*
        Push a single item to the server.

        Parameters
        ----------
        mid : str
            Your MetricID
        dt : str/int
            Unix Timestamp
            or
            Date and time in format '%Y-%m-%d %H:%M:%S' unless otherwise specified on the metric configurations
        v : str/int
            value positive, negative or =equal

        Returns
        -------
        array('status' => 'bad_request', 'error' => desc)
    */
    if(empty($mid) || empty($dt) || (empty($v) || $v == 0)) {
      return $this->bad_request(0);
    }
    $params = array(
      "mid" => $mid,
      "dt" => $dt,
      "v" => $v 
    );
    return $this->post($this->url_post . "stats/item", $this->set_params($params));
  }
  


/*
push_list
push_csv_data
post
get_definitions(self, typ='', **kwargs):
get_stats(self, mid, from_ts, to_ts, **kwargs):
*/
};



// var_dump(openssl_get_cipher_methods()); missing MODE_EAX

/*
/// INITIALIZE-CLIENT
$client = new FmsClient("YourFlowId", true, "Passphrase");

/// PUSH SOME METRIC DATA
for($i = 0; $i <= 1000; $i++) {
  $rsp = $client->push_single("4", FmsClient::utc_seconds()-$i, $i);
  echo ("status=" .$rsp->status ." msg=" .$rsp->msg ."\n");
  
  //var_dump($client->push_single("3", FmsClient::utc_seconds()-$i, $i*2));
}
*/

?>