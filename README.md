PHP API
========

This is a PHP API for the appzone.lk

Author : Pasindu De Silva ppsindu@live.com

Credits : Arunoda

AppZoneReciever class - decodes the POST message and return's it to the user

smssender class - Sends the message to the server


............................................................................................

<b>When creating the listener </b>

<code>$send= new smssender($server_url, $appid, $password)</code>

<b>Single sms can be sent by using the - sms methode </b>

<code> object->sms($message,$address) </code>

<b>Many sms can be sent by using the - many methode</b>

<code> object->many($message,address_array) </code>

<b>Broadcast message can be sent by using the - broadcast methode  </b>

<code>object->broadcast($message)</code>

