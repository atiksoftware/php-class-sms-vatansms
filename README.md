# VatanSMS PHP library

Its work with xml api for now
---

### Installation
```sh
composer require atiksoftware/php-class-sms-vatansms
```

### Usage example
```php
include "../vendor/autoload.php";
use Atiksoftware\SMS\VatanSMS;

$vatanSMS = new VatanSMS(
    "123456", // CustomerNo | Müşteri Numarası
    "atiksofware", // Username | Kullanıcı Adı
    "123456789" // Password | Şifre
);

// OR | yada

$vatanSMS = new VatanSMS();
$vatanSMS
    ->setCustomerNo("18018") // CustomerNo | Müşteri Numarası
    ->setUsername("atiksoftware") // Username | Kullanıcı Adı
    ->setPassword("123456789") // Password | Şifre
    ->setSmsType("Turkce") // Normal | Turkce
    ->setTitle("Minas Tirith"); // Title | Başlık


/** Clear list of created sms
 * Oluşturulmuş sms listesini temizle
 */
$vatanSMS->clear();

/** create a new sms and add to wait list for will send
 * thats will wait until use $vatanSMS->send(). because call oncle Api Service for all sms
 * 
 * yeni bir sms oluştur ve gönderilmek üzere listeye ekle
 * $vatanSMS->send() fonksiyonunu çağırana dek listede bekleyecektir. Tüm oluşturulmuş smsler için Api Servisi tek sefer çalıştırmak için. 
 */
$vatanSMS->sms(
    "Merhab Mansur\nVatanSMS e hoş geldin.", // SMS Text | SMS Metni
    "05556667788"  // Number(s) | Numara(lar)  
);






# SMS creating examples | SMS oluşturma yöntemleri

$vatanSMS->clear();
$vatanSMS->sms(
    "Merhab Mansur\nVatanSMS e hoş geldin.",
    "05556667788" 
);

$vatanSMS->clear();
$vatanSMS->sms(
    "Merhab Mansur\nVatanSMS e hoş geldin.",
    "05556667788,05445556622"
);

$vatanSMS->clear();
$vatanSMS->sms(
    "Merhab Mansur\nVatanSMS e hoş geldin.",
    ["05556667788","05445556622","05446852201,0385620000"]
);

/** send for all created sms
 * oluşturulmuş tüm smsleri gönder
 */
$vatanSMS->send();
```