TeamUpp - PHP Sosyal Ag / Baglanti Uygulamasi
================================================
<img width="1896" height="912" alt="image" src="https://github.com/user-attachments/assets/99f180b6-091e-45e7-b4b1-3be8958e8f4d" />

not: bazı kodlar eksik yüklenmiştir. sebebi dıştan erişimi engellemek
v1.9
BU NEDIR?
---------
Login/kayit, kesif (explore), profil, baglanti istegi gonderme-kabul etme
(LinkedIn tarzi), sadece kabul edilen kisiyle mesajlasma, gonderi (post)
paylasma - gorselli, akis (flow) ve sohbetler (chats) bolumlerini iceren
tam calisan bir PHP uygulamasidir.

Veritabani olarak MySQL KULLANILMAZ. Tum veriler proje icindeki
data/ klasorunde JSON dosyalari olarak tutulur:
  data/users.json          -> kullanicilar
  data/connections.json    -> baglanti istekleri (pending/accepted/rejected)
  data/messages.json       -> mesajlar
  data/posts.json          -> gonderiler

Yuklenen gorseller:
  images/avatars/  -> profil fotograflari
  images/posts/    -> gonderi gorselleri

NASIL CALISTIRILIR? bu nasıl çalıştırılır kısmı bana sorarasn detaylı gösteririm burası karışık gelebilir
--------------------
PHP kurulu olan herhangi bir bilgisayarda (XAMPP, MAMP, Laragon veya
dogrudan PHP CLI ile) klasoru sunucuya koyman yeterli, ekstra kurulum
veya veritabani ayari YOK.

1) En basit yontem - PHP'nin kendi sunucusu ile:
   Klasorun icindeyken terminalde:
       php -S localhost:8000
   Sonra tarayicidan http://localhost:8000 adresini ac.

2) XAMPP/MAMP kullanacaksan:
   Klasoru htdocs (XAMPP) veya htdocs (MAMP) icine kopyala,
   sonra http://localhost/teamupp adresinden ac.

3) data/ ve images/ klasorlerinin PHP tarafindan yazilabilir olmasi
   gerekir (chmod 777 ya da sunucu kullanicisina yazma izni).

SAYFALAR
--------
index.php         -> Giris (login)
register.php      -> Kayit ol
explore.php       -> Tum kullanicilari kesfet, baglanti istegi gonder
requests.php      -> Sana gelen baglanti isteklerini kabul/red et
profile.php?id=   -> Profil goruntule (kendi veya baskasinin)
edit_profile.php  -> Profilini duzenle (foto, baslik, yasadigin yer,
                     gitmek istedigin yer, okul, diller, is yerleri,
                     biyografi, iletisim, uygulama dili)
posts.php         -> Gonderi paylas (metin + gorsel), kendi gonderilerin
flow.php          -> Herkesin gonderilerinin aktigi ana akis
chats.php         -> Baglantida oldugun kisilerle sohbet listesi
chat.php?id=      -> Bir kisiyle birebir mesajlasma (yalniz baglanti
                     kabul edilmisse acilir)

NASIL CALISIYOR (akis)?
------------------------
1. Kayit ol / giris yap.
2. Explore sayfasinda diger kullanicilari gorursun, birine "Baglanti
   Gonder" dersin.
3. Karsi taraf "Baglanti Istekleri" sayfasindan kabul/red eder.
4. Kabul ederse ikiniz de birbirinize "Mesaj Gonder" ile chat.php
   uzerinden yazisabilirsiniz (mesajlar 3 saniyede bir otomatik
   yenilenir, sayfa yeniden yuklenmeden).
5. Posts sayfasindan metin+gorsel gonderi paylasirsin, bu gonderiler
   hem senin profilinde hem herkesin Flow akisinda gorunur.
6. Profilini edit_profile.php ile duzenleyebilirsin (ekran
   goruntusundeki tum alanlar mevcuttur).

NOTLAR
------
- Sifreler password_hash() ile guvenli sekilde saklanir.
- Sadece izin verilen uzantidaki gorseller (jpg, jpeg, png, gif, webp)
  yuklenebilir.
- JSON dosyalarina yazarken dosya kilidi (flock) kullanilir, boylece
  ayni anda birden fazla istek veriyi bozmaz.
- Istersen data/*.json dosyalarini Excel'de acmak icin JSON->CSV
  donusturebilirsin; yapi basit oldugu icin kolayca disari aktarilir.
