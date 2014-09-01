mandrill
========

Mandrill PHP client.

## Easy to use
```php

   include 'Mandrill.php';

   $m = new Mandrill(
       'Dustin Moorman',
       'from_me@example.com', 
       'reply_to_me@example.com', 
       '5555MandrillApiKey5555555ANJCuUF-qg'
   );
 
   $m->setHTML($html);
   $m->addRecipient('mistayayha@gmail.com', 'Yishai');
   $m->send();
```
