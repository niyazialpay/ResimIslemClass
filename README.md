ResimIslemClass
===============

Resim Upload ve Boyutlandırma Sınıfı

Class içerisindeki ilk fonksiyonumuz yüklenen dosyanın uzantısını bulmak için. İkinci fonksiyonumuz resmi yeniden boyutlandırmak için. Genelde resim boyutlandırmada png resimlerin transparent yerleri siyahlaşıyor veya daha farklı şekilde bozuluyor, buradaki boyutlandırma fonksiyonunda resmin transparent özelliği korunuyor. Fonksiyon dışarıdan dosya bilgisi, maximum yükseklik - genişlik ve uzantı bilgilerini alıp ona göre işlem yapıyor. Üçüncü fonksiyonumuz dosyayı yeniden adlandırmak için, dosya isminde Türkçe karakter varsa onları temizlemek için. Son fonksiyonumuz ise resmi upload etmek için. Fonksiyona sırasıyla post ile gelen dosyayı, dosyanın yükleneceği klasör veya yolu, dosyanın yeni ismi, dosyanın yükseklik ve genişlik değerleri giriliyor. Bu fonksiyon resmi upload ettikten sonra class içindeki diğer fonksiyonları kullanarak uzantıyı buluyor, yeniden adlandırıyor, uzantı eğer bir resim dosyasıysa (jpg, jpeg, png ve gif) upload işlemini gerçekleştirip yeniden boyutlandırma yapıyor ve orijinal dosyayı siliyor.

Resim yeniden boyutlandırılmasın orijinal hali yüklensin isteyenler vardır. Fakat yeniden boyutlandırmanın yararlarından biri; dosya uzantısı jpeg yapılarak sisteme zararlı bir dosya yüklenebilir yeniden boyutlandırma sırasında dosya gerçekten resim dosyası değilse boyutu 0 byte olarak kaydediliyor yani içeriği siliniyor.

Classın kullanımı ise şöyle; bu kodları ResimIslem.php olarak kaydedin, daha sonra bu dosyayı resmi post ettiğiniz dosyaya include edin ve şu kodları yazın

---------
$dosya  =  new ResimIslem();
$upload  =  $dosya->resim_upload($_FILES["formdan_gelen_resim"]["name"],$_FILES["formdan_gelen_resim"]["tmp_name"]$_FILES["formdan_gelen_resim"]["error"],'yuklenecek/dizin','dosyanın adı', 350, 250);
echo $upload.' Dosyası başarıyla yüklendi.';


Post ile gelen dosyanın 3 farklı parametresi var dosya yüklenirken ilk önce serverda temp dizinine atılır oradan belirlediğimiz klasöre aktarılır; post ile gelen dosyanın name bilgisi tmp bilgisi, yükleme esnasında oluşabilecek olası hataları görebilmemiz için error parametrelerini, dizin, dosya adı ve boyutlandırma bilgilerini girerek dosyayı yüklemiş olduk. Fonksiyon dosyanın adını da yükleme işleminden sonra dışarıya gönderiyor. Bu sayede de upload işleminden sonra dosya adını veritabanına kaydedebilir ya da <img src="" ile ekranda da resmi gösterebilirsiniz.
