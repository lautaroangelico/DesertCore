# DesertCore CMS 1.2.0

Open source Content Management System for emulated Black Desert Online servers.

## Getting Started

These instructions will help you deploy your own copy of the CMS.

### Prerequisites

Here's what you need to run DesertCore CMS

* Apache mod_rewrite
* PHP 7.2 or higher
* PHP PDO sqlite
* mongodb Extension
* cURL Extension
* OpenSSL Extension
* JSON

### Installing

1. Upload and extract the release files to your web server
2. Edit the configuration file located at `includes/config.php` and fill out your MongoDB database connection information
3. Add the master cron job `/includes/cron/cron.php` to run `once per minute`

## Other Software

DesertCore CMS wouldn't be possible without the following awesome projects.

* [PHPMailer](https://github.com/PHPMailer/PHPMailer/)
* [Bootstrap](https://getbootstrap.com/)
* [jQuery](http://jquery.com/)
* [reCAPTCHA](https://github.com/google/recaptcha)
* [Hybridauth](https://github.com/hybridauth/hybridauth)

## Authors

* **Lautaro Angelico** - *Developer*

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details

## Support

### Official Discord Server
[DesertCore CMS Official Discord](https://discord.gg/aqzzqAp)

## Donating to the Project

A lot of time and effort has been made to make DesertCore CMS available as an open-source project. Any and all donations will be greatly appreciated and will help keep the project going! [DONATE HERE](https://desertcore.com/donate)