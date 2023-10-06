<?php

require_once('config.php');

# Ödeme bilgileri
$request = new \Iyzipay\Request\CreatePayWithIyzicoInitializeRequest();
$request->setLocale(\Iyzipay\Model\Locale::TR);
$request->setConversationId("$conversationId"); // Benzersiz bir id tanımlayıp veritabanında tutmayı unutmayın.
$request->setPrice("1"); 
$request->setPaidPrice("$odeme_tutar"); // Fiyat
$request->setCurrency(\Iyzipay\Model\Currency::TL);
// $request->setBasketId("B67832");
$request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
$request->setCallbackUrl("https:/www.urladresiniz.com/odeme-sayfasi/callback.php"); // Ödeme sonuçları için callback url adresiniz
$request->setEnabledInstallments(array(2, 3, 6, 9));

// Ödeme yapan kişinin bilgileri (index.php'ye çekildiğinden değişkenleri direkt oradan alacak)
$buyer = new \Iyzipay\Model\Buyer();
$buyer->setId($_SESSION['usern']);
$buyer->setName("$odeme_kisi_ad");
$buyer->setSurname("$odeme_kisi_soyad");
$buyer->setGsmNumber("+9$odeme_tel");
$buyer->setEmail("$odeme_email");
$buyer->setIdentityNumber("$odeme_tc");
$buyer->setLastLoginDate("2015-10-05 12:43:35");
$buyer->setRegistrationDate("2013-04-21 15:12:09");
$buyer->setRegistrationAddress("$odeme_fatura");
// Bu kısım için API ile ip ve konum buldurarak yazdırdım. Bu yöntemle veya kullanıcıdan veri isteyerek sağlayabilirsiniz. 
$buyer->setIp("$odeme_ip");
$buyer->setCity("$odeme_sehir");
$buyer->setCountry("$odeme_ulke");
$buyer->setZipCode("$odeme_posta_kodu");
$request->setBuyer($buyer);

$shippingAddress = new \Iyzipay\Model\Address();
$shippingAddress->setContactName("$odeme_kisi");
$shippingAddress->setCity("$odeme_sehir");
$shippingAddress->setCountry("$odeme_ulke");
$shippingAddress->setAddress("$odeme_fatura");
$shippingAddress->setZipCode("$odeme_posta_kodu");
$request->setShippingAddress($shippingAddress);

$billingAddress = new \Iyzipay\Model\Address();
$billingAddress->setContactName("$odeme_kisi");
$billingAddress->setCity("$odeme_sehir");
$billingAddress->setCountry("$odeme_ulke");
$billingAddress->setAddress("$odeme_fatura");
$billingAddress->setZipCode("$odeme_posta_kodu");
$request->setBillingAddress($billingAddress);

$basketItems = array();
$firstBasketItem = new \Iyzipay\Model\BasketItem();
$firstBasketItem->setId("BI101");
$firstBasketItem->setName("Binocular");
$firstBasketItem->setCategory1("Collectibles");
$firstBasketItem->setCategory2("Accessories");
$firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
$firstBasketItem->setPrice("0.3");
$basketItems[0] = $firstBasketItem;

$secondBasketItem = new \Iyzipay\Model\BasketItem();
$secondBasketItem->setId("BI102");
$secondBasketItem->setName("Game code");
$secondBasketItem->setCategory1("Game");
$secondBasketItem->setCategory2("Online Game Items");
$secondBasketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
$secondBasketItem->setPrice("0.5");
$basketItems[1] = $secondBasketItem;

$thirdBasketItem = new \Iyzipay\Model\BasketItem();
$thirdBasketItem->setId("BI103");
$thirdBasketItem->setName("Usb");
$thirdBasketItem->setCategory1("Electronics");
$thirdBasketItem->setCategory2("Usb / Cable");
$thirdBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
$thirdBasketItem->setPrice("0.2");
$basketItems[2] = $thirdBasketItem;
$request->setBasketItems($basketItems);

# make request
$payWithIyzicoInitialize = \Iyzipay\Model\PayWithIyzicoInitialize::create($request, Config::options());

# Sonuçları bastırıyoruz. Denemeler bittiğinde display none vererek kullanıcı ekranına anlık da olsa yansımasını engelliyorum.
echo "<pre style='display:none;'> ";
print_r($payWithIyzicoInitialize);
print_r($payWithIyzicoInitialize->getStatus());   // Sonuç döndürecek kod
print_r($payWithIyzicoInitialize->getErrorMessage());   // Hata mesajlarını tutacak kod
print_r($payWithIyzicoInitialize->getToken());  // Token'ı yazdıran kod
print_r($payWithIyzicoInitialize->getPayWithIyzicoPageUrl());   //Iyzico ödeme sayfasını açmayı sağlayan URL'yi veren kod
 // URL adresini ve tokenı oturuma alıp duruma göre callback sayfasında kullanacağım.
$_SESSION['payWithIyzicoPageUrl'] = $payWithIyzicoInitialize->getPayWithIyzicoPageUrl();   
$_SESSION['token'] = $payWithIyzicoInitialize->getToken();   
echo "</pre>";