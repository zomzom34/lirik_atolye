# AI Yardım Kayıtları

Bu dosya, Web Tabanlı Programlama dersi kapsamında geliştirilen **LirikAtölye** projesinde yapay zeka aracından alınan yardımları göstermek amacıyla hazırlanmıştır.

Proje geliştirme sürecinde yapay zekadan; proje fikrinin ödev kurallarına uygunluğu, veritabanı yapısı, kullanıcı kayıt/giriş işlemleri, oturum kontrolü, CRUD işlemleri, arama özelliği, arayüz düzenlemeleri ve README hazırlığı konularında destek alınmıştır.

---

## 1. Proje Konusunun Kurallara Uygunluğu

### Kullanıcı

Şarkı taslağı takip sistemi yapmak istiyorum. Kullanıcılar kayıt olup giriş yaptıktan sonra kendi şarkı taslaklarını ekleyebilsin, listeleyebilsin, düzenleyebilsin ve silebilsin. Bu konu ödev kurallarına uygun olur mu?

### Yapay Zeka

Evet, bu konu ödev kurallarına uygundur. Çünkü projede kullanıcı kaydı, şifreli giriş, oturum açma/kapama, bilgi ekleme, listeleme, düzenleme ve silme işlemleri yapılabilir.

Ayrıca kullanıcı bilgileri ayrı bir tabloda, şarkı taslakları ise farklı bir tabloda tutulacağı için “kullanıcılar tablosundan farklı bir tabloya bilgi girişi” şartı da karşılanır.

---

## 2. Projenin Kapsamı

### Kullanıcı

Hocanın verdiği örnekler daha kapsamlı göründü. Benim projem eksik kalır mı?

### Yapay Zeka

Hayır, proje eksik kalmaz. Ödevin puanlama kriterleri temel olarak kullanıcı kaydı, giriş/çıkış, CRUD işlemleri, Bootstrap kullanımı, README, ekran görüntüleri, video ve hosting üzerinde çalışmaya odaklanıyor.

Şarkı taslağı takip sistemi bu kriterleri karşılamaya uygundur. Projede şarkı başlığı, tür, tema, ilham kaynağı, söz taslağı, beste notları, aşama, öncelik ve planlanan tarih gibi alanlar bulunduğu için basit bir not uygulamasından daha kapsamlı görünür.

---

## 3. Veritabanı Yapısı

### Kullanıcı

Bu proje için veritabanında hangi tablolar olmalı?

### Yapay Zeka

Projede iki tablo kullanılması uygun olur:

- `users`: kullanıcı bilgilerini tutar.
- `song_projects`: kullanıcılara ait şarkı taslaklarını tutar.

Bu yapı sayesinde kullanıcı bilgileri ve kullanıcının eklediği şarkı taslakları birbirinden ayrılmış olur. Ayrıca `song_projects` tablosunda `user_id` alanı kullanılarak her kaydın hangi kullanıcıya ait olduğu takip edilebilir.

---

## 4. Tablo ve Alan İsimleri

### Kullanıcı

Tablo ve sütun isimleri Türkçe mi İngilizce mi olsun?

### Yapay Zeka

Tablo ve sütun isimlerinin İngilizce olması daha uygun olur. PHP ve MySQL projelerinde İngilizce isimlendirme daha yaygın ve okunabilir bir kullanımdır.

Arayüz Türkçe olabilir. Örneğin veritabanında `title`, `genre`, `lyrics_draft` gibi alan adları kullanılırken kullanıcı arayüzünde bunlar “Şarkı Başlığı”, “Tür”, “Söz Taslağı” şeklinde gösterilebilir.

---

## 5. Veritabanı Bağlantısı

### Kullanıcı

PHP ile MySQL veritabanına nasıl bağlanmalıyım?

### Yapay Zeka

Veritabanı bağlantısı için `config/database.php` dosyası oluşturulabilir. Bu dosyada PDO kullanılarak MySQL bağlantısı yapılabilir.

PDO kullanmak, veritabanı işlemlerini daha düzenli yapmayı sağlar. Ayrıca `prepare` ve `execute` yapısı kullanılarak SQL sorguları daha güvenli hale getirilebilir.

---

## 6. Veritabanı Bağlantı Hatası

### Kullanıcı

Projeyi çalıştırınca veritabanı bağlantı hatası aldım. Access denied for user 'root'@'localhost' hatası çıktı. Bu neden olur?

### Yapay Zeka

Bu hata genellikle MySQL kullanıcı adı veya şifre bilgisinin yanlış olmasından kaynaklanır. `config/database.php` dosyasında bulunan veritabanı kullanıcı adı ve şifre bilgileri kontrol edilmelidir.

Eğer phpMyAdmin’e giriş yaparken root kullanıcısı için şifre kullanılıyorsa, aynı şifre `database.php` dosyasındaki `$password` alanına yazılmalıdır.

---

## 7. Ortak Dosya Yapısı

### Kullanıcı

Projede her sayfada tekrar eden navbar ve footer kısımlarını nasıl düzenlemeliyim?

### Yapay Zeka

Tekrar eden alanlar için `includes` klasörü içinde ortak dosyalar oluşturulabilir.

Önerilen dosyalar:

- `includes/header.php`
- `includes/footer.php`
- `includes/auth.php`

`header.php` dosyasında sayfanın üst kısmı ve navbar yer alabilir. `footer.php` dosyasında alt bilgi alanı bulunabilir. `auth.php` dosyası ise giriş yapmamış kullanıcıların panel sayfalarına erişmesini engellemek için kullanılabilir.

---

## 8. Dosya Uzantısı

### Kullanıcı

Header dosyasının içinde HTML çok fazla olduğu için editör HTML gibi algılıyor. Dosya `.html` mi olmalı, `.php` mi?

### Yapay Zeka

Dosya içinde HTML kodu fazla olsa da PHP kodları da bulunduğu için uzantısı `.php` olmalıdır. Çünkü session kontrolü ve PHP koşulları çalışabilmesi için dosyanın PHP olarak kaydedilmesi gerekir.

Doğru kullanım:

`header.php`

Yanlış kullanım:

`header.html`

---

## 9. Kullanıcı Kayıt Sistemi

### Kullanıcı

Kullanıcı kayıt sayfasında şifreleri nasıl saklamalıyım?

### Yapay Zeka

Şifreler veritabanına düz metin olarak kaydedilmemelidir. PHP’nin `password_hash()` fonksiyonu kullanılmalıdır.

Kayıt sırasında kullanıcının adı, e-posta adresi ve hashlenmiş şifresi `users` tablosuna kaydedilebilir. Böylece veritabanında gerçek şifreler açık şekilde tutulmaz.

---

## 10. Kullanıcı Giriş Sistemi

### Kullanıcı

Kullanıcı giriş işlemini nasıl yapmalıyım?

### Yapay Zeka

Giriş işleminde kullanıcının e-posta adresi veritabanında aranır. Kullanıcı bulunursa girilen şifre, veritabanındaki hashlenmiş şifreyle `password_verify()` kullanılarak karşılaştırılır.

Giriş başarılı olursa kullanıcının id, ad ve e-posta bilgileri session içine alınabilir. Böylece kullanıcı oturumu takip edilebilir.

---

## 11. Giriş Sonrası Yönlendirme

### Kullanıcı

Kullanıcı giriş yaptıktan sonra hangi sayfaya yönlendirilmelidir?

### Yapay Zeka

Kullanıcı giriş yaptıktan sonra şarkı taslaklarını görebileceği `dashboard.php` sayfasına yönlendirilebilir. Bu sayfa kullanıcının kendi kayıtlarını listelediği ana panel olarak kullanılabilir.

Giriş yapmamış kullanıcıların bu sayfaya erişmemesi için oturum kontrolü yapılmalıdır.

---

## 12. Oturum Kontrolü

### Kullanıcı

Giriş yapmayan kullanıcıların panel sayfalarına girmesini nasıl engellerim?

### Yapay Zeka

Bunun için `includes/auth.php` dosyası kullanılabilir. Bu dosya, session içinde kullanıcı bilgisi olup olmadığını kontrol eder. Eğer kullanıcı giriş yapmamışsa `login.php` sayfasına yönlendirilir.

Bu dosya `dashboard.php`, `song_add.php`, `song_edit.php` ve `song_delete.php` gibi giriş gerektiren sayfaların başında çağrılabilir.

Örnek oturum kontrolü:

```php
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
```

---

## 13. Şarkı Taslaklarını Listeleme

### Kullanıcı

Giriş yapan kullanıcının sadece kendi şarkı taslaklarını görmesini nasıl sağlarım?

### Yapay Zeka

Şarkı taslakları listelenirken sorguda `user_id` kontrolü yapılmalıdır. Böylece her kullanıcı sadece kendi eklediği kayıtları görebilir.

Bu yapı, kullanıcıların birbirlerinin kayıtlarına erişmesini engeller ve proje güvenliği açısından daha doğru bir kullanım sağlar.

---

## 14. Şarkı Taslağı Ekleme

### Kullanıcı

Kullanıcı yeni şarkı taslağı eklediğinde bu kaydı kullanıcıyla nasıl ilişkilendirmeliyim?

### Yapay Zeka

Kayıt eklenirken giriş yapan kullanıcının id bilgisi session üzerinden alınmalıdır. Bu id, `song_projects` tablosundaki `user_id` alanına kaydedilmelidir.

Böylece her şarkı taslağı hangi kullanıcı tarafından eklendiği bilgisiyle saklanır.

---

## 15. Şarkı Taslağı Düzenleme

### Kullanıcı

Kullanıcı sadece kendi eklediği şarkı taslağını düzenleyebilsin istiyorum. Bunu nasıl sağlarım?

### Yapay Zeka

Düzenleme işleminde yalnızca kayıt id değerini kontrol etmek yeterli değildir. Sorguda hem kayıt id değeri hem de giriş yapan kullanıcının id değeri kontrol edilmelidir.

Bu sayede kullanıcı başka bir kullanıcının kayıt id değerini URL üzerinden değiştirse bile o kayda erişemez.

---

## 16. Şarkı Taslağı Silme

### Kullanıcı

Şarkı taslağı silme işleminde güvenlik açısından neye dikkat etmeliyim?

### Yapay Zeka

Silme işleminde de düzenleme işleminde olduğu gibi `user_id` kontrolü yapılmalıdır. Silme sorgusunda hem silinecek kaydın id değeri hem de giriş yapan kullanıcının id değeri kullanılmalıdır.

Bu sayede kullanıcı yalnızca kendi şarkı taslaklarını silebilir.

Örnek silme sorgusu:

```php
$sql = "DELETE FROM song_projects WHERE id = ? AND user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$song_id, $user_id]);
```

---

## 17. Arama Özelliği

### Kullanıcı

Projeyi biraz daha kullanışlı yapmak için listeleme ekranına arama özelliği eklemek mantıklı olur mu?

### Yapay Zeka

Evet, arama özelliği eklemek mantıklıdır. Bu özellik sayesinde kullanıcılar kendi şarkı taslakları arasında başlık, tür veya aşamaya göre arama yapabilir.

Arama yapılırken yine `user_id` kontrolü korunmalıdır. Böylece kullanıcı sadece kendi kayıtları içinde arama yapar.

Örnek arama mantığı:

```php
$sql = "SELECT * FROM song_projects 
        WHERE user_id = ? 
        AND (
            title LIKE ? 
            OR genre LIKE ? 
            OR production_stage LIKE ?
        )
        ORDER BY created_at DESC";
```

---

## 18. Arayüz Tasarımı

### Kullanıcı

Siteyi biraz daha güzel hale getirmek istiyorum. Mavi tonlarda ve yıldızlı bir arka plan kullanabilir miyim?

### Yapay Zeka

Evet, Bootstrap yapısı korunarak özel CSS ile tasarım iyileştirilebilir. Arka planda gece temalı yıldız efekti, panel alanında mavi tonlar, yuvarlatılmış kartlar ve düzenli butonlar kullanılabilir.

Bu tasarım, projenin daha düzenli ve sunuma hazır görünmesine yardımcı olur.

---

## 19. README.md Hazırlığı

### Kullanıcı

README dosyam nasıl olmalı?

### Yapay Zeka

README dosyasında projenin adı, kısa açıklaması, amacı, özellikleri, kullanılan teknolojiler, veritabanı yapısı, sayfa yapısı, kurulum adımları, ekran görüntüleri ve tanıtım videosu bağlantısı bulunmalıdır.

Ayrıca ekran görüntüleri için proje içinde `screenshots` klasörü oluşturulabilir. Görseller README içinde bu klasördeki yollar kullanılarak gösterilebilir.

---

## 20. GitHub ve Hosting

### Kullanıcı

Projeyi GitHub’a ve hosting’e taşırken nelere dikkat etmeliyim?

### Yapay Zeka

GitHub’a yüklemeden önce hassas bilgiler kontrol edilmelidir. Hosting şifresi, FTP şifresi, MySQL şifresi veya gizli kod gibi bilgiler repoya yüklenmemelidir.

Hosting ortamına geçildiğinde `config/database.php` dosyasındaki veritabanı bilgileri, hosting tarafından verilen MySQL bilgilerine göre güncellenmelidir.

Ayrıca proje kurallarında belirtildiği için `.htaccess` dosyası kullanılmamalıdır.