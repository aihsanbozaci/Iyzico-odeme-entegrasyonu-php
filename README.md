# İyzico Ödeme Sistemi Entegrasyon Dokümantasyonu (PHP)
Bu dokümantasyon, PHP ile İyzico ödeme sistemi entegrasyonunu (payWithIyzico) nasıl yapacağınızı daha sade olarak açıklayacaktır. **(Sadece değişiklik yapılan dosyalar yüklenmiştir.)** 

## Gereksinimler

Bu projeyi kullanabilmek için aşağıdaki gereksinimlere ihtiyacınız var:
- PHP 5.6 ve üzeri
- İyzico hesabı
- [İyzico kurulum dosyaları](https://github.com/iyzico/iyzipay-php/releases)

## Kurulum

1. Proje dosyalarını indirin ve proje dizininize atın.
2. İyzico hesabınızı kullanarak API anahtarlarınızı alın. API anahtarınız sağ üstte profilinize tıkladıktan sonra Merchant Settings kısmındadır. [Deneme hesabı oluşturmak için tıklayın.](https://sandbox-merchant.iyzipay.com/auth/register).
3. API ve Secret anahtarlarınızı iyzipay-php/samples/config.php dosyasına kayıt edin.
4. PHP entegrasyonuna geçelim.


## Aşamalar

1. Iyzico'nun istediği gerekli bilgileri sağlayacağımız ödeme formumuzu oluşturalım. **index.php**
2. Callback sayfamızı oluşturalım. **callback.php**
3. Iyzico'nun parametrelerine formdan aldığımız bilgileri hatasız şekilde post edelim. **iyzipay-php/samples/initialize_pay_with_iyzico.php (index.php içine çekerek kullandım.)**
4. Iyzico'nun ödemenin gerçekleşip gerçekleşmediğine dair callback sayfasına gönderdiği mesajları değerlendirelim. **callback.php**
5. Eğer başarılıysa gerekli bilgileri veritabanımıza da aktaralım. **callback.php**
6. Ödemenin başarılı ve başarısız olma durumları için son kullanıcıya mesaj gösterelim. **callback.php->index.php**


