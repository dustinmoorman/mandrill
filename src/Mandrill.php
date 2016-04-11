<?php

namespace Dustinmoorman\Mandrill;

/**
 * Class Mandrill
 * @author Dustin Moorman <dustin.moorman@gmail.com>
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
 *  $m->setTitle('Test Email');
 *  $m->setHTML($html);
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

    public function __construct($from_name, $from_email, $reply_to, $api_key = null) {
        $this->setFromName($from_name);
        $this->setFromEmail($from_email);
        $this->setReplyTo($reply_to);

        if (!empty($api_key)) {
            $this->setAPIKey($api_key);
        }
    }

    public function send() {

        $this->validateRequiredMailSettings();

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
                'tracking_domain' => null,
                'signing_domain' => null,
                'return_path_domain' => null
            ),
            'async' => false,
            'ip_pool' => 'Main Pool'
        );

        $ch = \curl_init();
        \curl_setopt($ch, CURLOPT_URL, 'http://mandrillapp.com/api/1.0/messages/send.json');
        \curl_setopt($ch, CURLOPT_POST, 1);
        \curl_setopt($ch, CURLOPT_POSTFIELDS, \json_encode($transmission));
        \curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . \strlen(\json_encode($transmission))
        ));

        $result = \curl_exec($ch);
        \curl_close($ch);
        $decoded = \json_decode($result);

        return \is_null($decoded) ? $result : $decoded;
    }

    /**
     * Validates required mail settings for API transmission. Throws
     * an error message detailing what is missing.
     * 
     * @throws \Exception
     */
    protected function validateRequiredMailSettings()
    {
        try {
            if (empty($this->api_key)) {
                throw new \Exception("No API Key provided.");
            }
        } catch (\Exception $e) {
            throw new \Exception("Error - Unable to send: {$e->getMessage()}");
        }
    }

    public function addRecipient($email, $name = null) {
        $this->recipients[] = array(
            'type' => 'to',
            'email' => $email,
            'name' => $name
        );
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setHTML($html) {
        $this->html = $html;
    }

    public function setFromName($fromName) {
        $this->from_name = $fromName;
    }

    public function setFromEmail($fromEmail) {
        $this->from_email = $fromEmail;
    }

    public function setAPIKey($apiKey) {
        $this->api_key = $apiKey;
    }

    public function setReplyTo($replyTo) {
        $this->reply_to = $replyTo;
    }
}
