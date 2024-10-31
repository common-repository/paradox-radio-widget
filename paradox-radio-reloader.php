<?php
/*
Author: Arkadius Jonczek
Author URI: http://www.jonczek.de
*/

define("HOST", "streamplus54.leonex.de");     // Der Host Ihres Servers (finden Sie unter Server -> Server-Daten -> Streamen als Moderator)
define("PORT", "11596");                    // Der Port Ihres Servers (finden Sie unter Server -> Server-Daten -> Streamen als Moderator)
define("ADMIN_PASSWORT", "pwujrmln");   // Das Admin-Passwort Ihres Servers (finden Sie unter Server -> Optionen)



function between($von,$bis,$string)
{
	$a = explode($von,$string);
    if (count($a) >= 2)
    {
        $b = explode($bis,$a[1]);
        return $b[0];
    }
    return false;
}


// Verbindung mit dem Shoutcast - Server und Download der XML Datei

$xml = '';
$fp = fsockopen(HOST, PORT, $errno, $errstr, 5);
if ($fp)
{
    $send = "GET /admin.cgi?pass=".ADMIN_PASSWORT."&mode=viewxml&page=0 HTTP/1.1\r\n"
          . "Host: ".HOST.":".PORT."\r\n"
          . "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.1.3) Gecko/20090824 Firefox/3.5.3 (.NET CLR 3.5.30729)\r\n"
          . "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n"
          . "Accept-Language: de-de,de;q=0.8,en-us;q=0.5,en;q=0.3\r\n"
          . "Accept-Encoding: gzip,deflate\r\n"
          . "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n"
          . "Keep-Alive: 300\r\n"
          . "Connection: keep-alive\r\n\r\n";
    fwrite($fp, $send);
    while(!feof($fp))
    {
        $xml .= fgets($fp, 1024);
    }
    fclose($fp);
}



/*
 * In den folgenden Variablen stehen die wichtigsten Informationen wie: 
 * das aktuelle Lied, der Status des Servers, wie viele Zuhoerer gerade online sind und wie der Server ueberhaupt heisst
 */

$server_titel =		between("<SERVERTITLE>", "</SERVERTITLE>", $xml);
$server_genre =		between("<SERVERGENRE>", "</SERVERGENRE>", $xml);
$server_url =		between("<SERVERURL>", "</SERVERURL>", $xml);
$stream_status =	(int)between("<STREAMSTATUS>", "</STREAMSTATUS>", $xml);
$zuhoerer = 		(int)between("<CURRENTLISTENERS>", "</CURRENTLISTENERS>", $xml);
$max_zuhoerer = 	(int)between("<MAXLISTENERS>", "</MAXLISTENERS>", $xml);
$song_titel = 		between("<SONGTITLE>", "</SONGTITLE>", $xml);

echo $song_titel;
