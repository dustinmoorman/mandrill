mandrill
========

Mandrill PHP client.

## Easy to use
```php
   $m = new Mandrill(
       'Dustin Moorman',
       'noreply@example.com', 
       'dustin.moorman@gmail.com', 
       '55555555555ANJCuUF-qg'
   );
 
   $m->setHTML($html);
   $m->addRecipient('mistayayha@gmail.com', 'Yishai');
   $m->send();
```
