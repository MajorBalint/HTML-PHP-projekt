# Házi feladat specifikáció: Autó konfigurátor vásárláshoz

Név: Major Bálint
Neptun-kód: TMJP5V
A bemutató videó URL-je:

# Feladat leírása
A weboldal célja, hogy a felhasználók (vásárlók) egy kiválasztott autótípust összetudjanak állítani, ki tudják majd választani a színét, a motor típusát és a kívánt extrákat. A végén egy végleges árat is kiír majd a program. Két felhasználó típus lesz, a kereskedő és a vásárló (és egy admin, akinek az id-ja az 1-es). A kereskedő tud majd új típusokat, extrákat, színeket és motorokat hozzáadni. A vásárló pedig összeállítani a kilistázott extrákból, színekből és motorokból a kívánt autót.

# Megvalósítandó funkciók

* Autók módosítása, listázása
  * Ehhez csak a kereskedő jogosultságú felhasználók férnek majd hozzá
  * Felvett modellek listázása
  * Felvett modellek szerkesztése
  * Új modellek felvétele az adatbázisba

* Extrák módosítása, listázása
  * Ehhez csak a kereskedő jogosultságú felhasználók férnek majd hozzá
  * Felvett extrák listázása
  * Felvett extrák szerkesztése
  * Új extrák felvétele az adatbátisba

* Színek módosítása, listázása
  * Ehhez csak a kereskedő jogosultságú felhasználók férnek majd hozzá
  * Felvett színek listázása
  * Felvett színek szerkesztése
  * Új színek felvétele az adatbázisba

* Regisztráció
  * Új felhasználók tudnak majd regisztrálni
  * Egy külön azonosító beírásával kaphatnak kereskedői jogkört, e nélkkül minden új felhasználó vásárló jogkört kap
  * A felhasználók adatainak, jogkörének módosítása

* Autók konfigurálása
  * Alapvetően a vásárló jogkörű felhasználók száméra, de mindenki elérheti
  * Ki lehet majd választani minden autóhoz:
    * Egy színt
    * Egy motort
    * Több extrát (vásárlói igényeknek megfelelően)
  * Kész konfiguráció törlése
  * Kész konfiguráció szerkesztése

# Főoldalról elérhető oldalak
* Bejelentkezés
* Konfigurátor
* Felhasználói jogkör beállítása
* Autók, extrák, színek, motorok módosítása

# Adatbázis sémája
![image](https://github.com/BME-VIK-Informatika2/hf-MajorBalint/assets/126571805/da2e2963-d7d3-4141-83d4-a46cb7bb11d8)
