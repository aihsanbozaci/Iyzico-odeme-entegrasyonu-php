<?php
ob_start();
require_once('iyzipay-php/samples/config.php'); // API anahtarlarınızı tanımlayacağınız sayfa. İndirdiğiniz Iyzico paketinden düzenleyiniz.
$token = $_POST['token']; // Iyzico'dan dönen token

require_once('iyzipay-php/samples/retrieve_pay_with_iyzico_result.php'); // Ödeme sonuçlarının print_r ile döneceği sayfa
if($status == "failure"){ // Başarısız ise hata mesajı verdirdiğim URL'e yönlendirme kodu
    echo '<script>
    window.location.href = "https://www.urladresiniz.com/odeme-sayfasi/index.php?status=err";
  </script>';
    exit;
}

    if($payWithIyzico->getStatus() === "failure"){

        $errMsg = $payWithIyzico->getErrorMessage();
        $hata_mesaji = "Hata kodu: " . $errMsg;
        echo '<script>
              window.location.href = "https://www.urladresiniz.com/odeme-sayfasi/index.php?status=err";
            </script>';
        exit;
    }
    elseif($payWithIyzico->getPaymentStatus() === "SUCCESS"){  // Ödeme başarılıysa gerçekleşecek işlemler

        try {
          require_once('../class/db.php');
          $db = new PdoDB();  // Veritabanı bağlantısı
          date_default_timezone_set('Europe/Istanbul'); // Türkiye saati olarak kaydediyorum.

            // Kart ve ödeme bilgilerini API'den dönen sonuçlara göre tutuyorum
            $paymentId = $payWithIyzico->getPaymentId();
            $odeme_tutar = $payWithIyzico->getPaidPrice();
            $para_birimi = $payWithIyzico->getCurrency();
            $taksit = $payWithIyzico->getInstallment();
            $kart_son_4_hane = $payWithIyzico->getLastFourDigits();
            $tarih = date('Y-m-d H:i:s');

            // Veritabanına kayıt işlemleri
            $query = "INSERT INTO IYZICO (odeme_conversation_id, odeme_payment_id,
             odeme_tutar, odeme_para_birimi, odeme_taksit, odeme_kart_son_4_hane, odeme_tarih) VALUES 
             (:odeme_conversation_id, :odeme_payment_id, :odeme_tutar, 
             :odeme_para_birimi, :odeme_taksit, :odeme_kart_son_4_hane, :odeme_tarih)";
            $params = [
                ':odeme_conversation_id' => $conversationId,
                ':odeme_payment_id' => $paymentId,
                ':odeme_tutar' =>$odeme_tutar,
                ':odeme_para_birimi' => $para_birimi,
                ':odeme_taksit' =>$taksit,
                ':odeme_kart_son_4_hane' => $kart_son_4_hane,
                ':odeme_tarih' => $tarih,
              ];
              $query = $db->query($query, $params);

              // Kayıt işleminin başarılı olup olmadığına göre hata ve başarı mesajlarına yönlendirme yapan kodlar adres
              if($query){
                echo '<script>
                  window.location.href = "https://www.urladresiniz.com/odeme-sayfasi/index.php?status=success";
                </script>';
                exit;
              }else{
                echo '<script>
                window.location.href = "https://www.urladresiniz.com/odeme-sayfasi/index.php?status=err";
              </script>';
          exit;
              }
              
          } catch (PDOException $e) {
              die("Veritabanına kaydedilirken bir hata oluştu: " . $e->getMessage());
              
          }
    }
    ob_end_flush();
?>