----- | KURULUM

/Script dizini altındaki tüm dosyalar public_html veya ilgili alt klasöre koplalanmalıdır

_cache/ -> 0777 Chmod uygulanmalıdır

/lib/lib_con.php 	-> Veritabanı bağlantısı içindir, kurulumdan sırasında el ile düzenlenmelidir
/lib/lib_desc.php 	-> Sitenin kimi sabit değerleri içindir, kendinize göre özelleştirmeniz gerekebilir

Kurulum, ana dizin dışında bir dizin altına yapılacak ise

/lib/lib_define.php dosyasındaki şu değerler düzenlenmelidir

$sitelink = 'http://'.$_SERVER['HTTP_HOST']; $sitelink = trim($sitelink);
//$sitelink = 'http://'.$_SERVER['HTTP_HOST'].'/subdir';

----- | VERİTABANI

Veritabanı dosyaları SQL dizini içindedir; Hepsi çalıştırılmalıdır; 

yönetici adı      : admin
yönetici parolası : admin

şeklinde düzenlenmiştir...

Bu şekilde giriş yaptıktan sonra kullanıcı bilgilerinizi güncelleyiniz...

----- | RSS KAYNAKLARI

Linuxhaber.com yayın yaptığı süre boyunca derlenen ve oluşturulan tüm RSS kaynakları 
RSS dizini içinde OPML dosyası içinde bulunmaktadır... 
Tercihen RSSowl programı ile kullanabilirsiniz...
