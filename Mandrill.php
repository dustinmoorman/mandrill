<?php
/**
 * Class Mandrill
 * @author Dustin Moorman <dustin.moorman@gmail.com>
 * @package RCSEmail\API
 * @desc This class shouldn't be complicated. All we need to do is
 * open an engine to send email transmissions from mailchimp.
 * @license MIT
 * @example
 *
 *  $m = new Mandrill(
 *      'Dustin Moorman',
 *      'noreply@example.com', 
 *      'dustin.moorman@gmail.com', 
 *      '55555555555ANJCuUF-qg'
 *  );
 *
 *  $m->setTransmission($html);
 *  $m->addRecipient('mistayayha@gmail.com', 'Yishai');
 *  $m->send();
 *
 */
class Mandrill {

    protected $title;
    protected $html;
    protected $from_name;
    protected $from_email;
    protected $reply_to;
    protected $api_key;
    protected $transmission = '';
    public $lastTransmissionPayload = '';

    public function __construct($from_name, $from_email, $reply_to, $api_key){
        $this->setFromName($from_name);
        $this->setFromEmail($from_email);
        $this->setReplyTo($reply_to);
        $this->setAPIKey($api_key);
    }

    public function send(){
        
        $transmission = array(
            'key' => $this->api_key,
            'message' => array(
                'html' => $this->html,
                'subject' => $this->title,
                'from_email' => $this->from_email,
                'from_name' => $this->from_name,
                'to' => $this->recipients,
                'headers' => array(
                    'Reply-To' => $this->reply_to
                ),
                'important' => false,
                'track_opens' => null,
                'track_clicks' => null,
                'auto_text' => null,
                'auto_html' => null,
                'inline_css' => null,
                'url_strip_qs' => null,
                'preserve_recipients' => null,
                'view_content_link' => null,
            ),
            'async' => false,
            'ip_pool' => 'Main Pool'
        );

        if(strlen($this->transmission) < 1) throw new \Exception('Transmission Not Set');
        $this->lastTransmissionPayload = $this->transmission;

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, "http://mandrillapp.com/api/1.0/messages/send.json");
        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($this->transmission));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen(json_encode($this->transmission))));

        $result = curl_exec($ch);
        curl_close($ch);
        $decoded = json_decode($result);

        return is_null($decoded) ? $result : $decoded;
    }

    public function addRecipient($email, $name = null){
        $this->recipients[] = array(
            'type' => 'to',
            'email' => $email,
            'name' => $name
        );
    }

    public function setTitle($title){
        $this->title = $title;
    }

    public function setHTML($html){
        $this->html = $html;
    }

    public function setFromName($f){
        $this->from_name = $f;
    }

    public function setFromEmail($e){
        $this->from_email = $e;
    }

    public function setAPIKey($a){
        $this->api_key = $a;
    }

    public function setReplyTo($r){
        $this->reply_to = $r;
    }

}