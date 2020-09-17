<?php
/**
 * DesertCore CMS
 * https://desertcore.com/
 * 
 * @version 1.2.0
 * @author Lautaro Angelico <https://lautaroangelico.com/>
 * @copyright (c) 2018-2019 Lautaro Angelico, All Rights Reserved
 */

if(!@include_once(__PATH_CLASSES__ . 'phpmailer/PHPMailer.php')) throw new Exception('Could not load class (phpmailer.PHPMailer).');
if(!@include_once(__PATH_CLASSES__ . 'phpmailer/SMTP.php')) throw new Exception('Could not load class (phpmailer.SMTP).');
if(!@include_once(__PATH_CLASSES__ . 'phpmailer/Exception.php')) throw new Exception('Could not load class (phpmailer.Exception).');