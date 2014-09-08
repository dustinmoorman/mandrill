mandrill
========

Mandrill PHP client.

## Easy to use
```php

   include 'Mandrill.php';

   $m = new Mandrill(
       'Dustin Moorman',
       'from_address@example.com', 
       'reply_to_me@example.com', 
       '5555MandrillApiKey5555555ANJCuUF-qg'
   );
 
   $m->setTitle('Test Email');
   $m->setHTML('<div>Sending a test email.</div>');
   $m->addRecipient('mistayayha@gmail.com', 'Yishai');
   $m->send();
```
