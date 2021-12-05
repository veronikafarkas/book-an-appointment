# book-an-appointment

Ez egy példa program, ahol oltásra lehet időpontot foglalni. Karanténban, még az oltások elkezdése előtt kellett beadandónak elkészíteni, így ez nem tükrözi a valós oltási rendszert. 

A főoldalra érkezve (index.php) megtekinthető egy rövid leírás és a jelentkezés menete. Az oldal alján a hónapban elérhető oltási napok és a rendelkezésre álló helyeket tekinthetjük meg. Ha egy napra betelt a létszám, pirossá változik az a nap, illetve a Jelentkezés link is letiltásra kerül.
A Jelentkezés linkre kattintva egy bejelentkező oldal lesz előttünk, ha rendelkezünk regisztrációval, beléphetünk, ellenkező esetben a Regisztráció-ra kattintva beregisztrálhatjuk magunkat, ahol az összes szövegmező kitöltése kötelező.  Sikeres regisztráció után újra a bejelentkező oldalra kerülünk, ahonnan mostmár bejelentkezhetünk az oldalra. 
Ha a jobb felső sarokban látható bejelentkezés/regisztráció gombra kattintva jelentkezünk be, akkor a főoldalra jutunk vissza, ha pedig valamelyik jelentkezés gombra kattintottunk, akkor kiugrik az időpontfoglalás oldala, az időpont és saját adatainkkal. Egy jelölőnégyzet bepipálásával és a Jelentkezés megerősítése gombra kattintva egy "Sikeres időpontfoglalás!" oldalra jutunk, ahonnan visszamehetünk a kezdőlapra. 
A kezdőlapon immár a foglalt időpontunk jelenik meg, és az időpontok táblázatánál letiltásra kerültek a jelentkezés gombok, hiszen már van időpontunk. Viszont továbbra is látható, hogy hány hely foglalt, hátha meggondoljuk magunkat. A foglalt időpontunknál találunk egy "Jelentkezés lemondása" gombot is, melyre kattintva lemondhatjuk a foglalásunkat egy kattintással, majd jelentkezhetünk egy új időpontra.
Lehetőség van még adminként is belépni az admin@nemkovid.hu e-mail címmel és az admin jelszóval. Adminként új időpontot hirdethetünk meg egy dátum, időpont és a helyek számának megadásával, itt is kötelező minden mező kitöltése. Az admin látja azt is, hogy a különböző időpontokra kik jelentkeztek, de ő csak a teljes nevüket, TAJ számukat és az e-mail címüket látja.

A naptár még átalakításra szorulna, ugyanis a hónapok között nem lehet váltani, és szebb megjelenést is lehetne neki adni.

Készítve: 2021.01.17.
