1. Požadavky na hostingové služby

K provozu této aplikace je vyžadována funkční instalace PHP; úspěšně testo-
váno na PHP 5, starší verze by však mohly postačovat také.


2. Základní nastavení

Ačkoli je pidiblog plně funkční už ve výchozím nastavení, nachází se v kon-
figuračním souboru skupina voleb, jako je uživatelské jméno či přihlašovací
heslo, které by pravděpodobně neměly uniknout Vaší pozornosti.  Ve svém ob-
líbeném textovém editoru proto otevřete soubor config.php a poblíž jeho za-
čátku najděte následující oddíl:

  // change your pidiblog settings here:
  $blogname = "Your blog name :)"; // your pidiblog name
  $title    = "";                  // when empty, use blogname instead
  $password = "your_password";     // password to log in
  $about    = "pidiblog";          // blog name in about section
  $language = "en.php";            // name of language file in lang/ folder
  $name     = "your_nickname";     // if logged use this name in comments
  $email    = "your_email";        // if logged use this email in comments
  $apiCode  = "your_api_code";     // special code to authorize the soft...

Pro změnu nastavení  upravujte vždy  jen hodnotu v uvozovkách.  Aby se pře-
dešlo případným pozdějším obtížím s nekompatibilním kódováním, doporučujeme
použití UTF-8.

Název svého  pidiblogu  nastavíte úpravou volby  $blogname -- tato  hodnota
bude následně použita i pro titul stránky, nestanovíte-li jinak na následu-
jícím řádku.  Prostřednictvím volby  $password  nastavíte přístupové heslo,
které Vám později umožní  vkládat nové příspěvky.  Hodnota $about  obsahuje
řetězec, který bude použit v horním menu na místě odkazu na informace o Va-
šem pidiblogu.  $language obsahuje název souboru s lokalizací do daného ja-
zyka,  tento soubor se přitom musí nacházet v adresáři  lang/;  pro češtinu
je to "cs.php".  Volby $name a $email pak obsahují Vaši přezdívku a e-mail,
které budou použity kdykoli budete jako přihlášený uživatel  komentovat své
příspěvky.


3. Další nastavení

Vedle těchto  základních nastavení  poskytuje pidiblog  také několik voleb,
které Vám umožní uzpůsobit jej ještě více Vašim potřebám:

  $fullDate = 1;                   // 1 or 0 - whether to use 1 December...
  $showmenu = 1;                   // 1 or 0 - whether to show menu bar
  $showrss  = 1;                   // 1 or 0 - whether to list RSS in menu
  $enableComments  = 1;            // 1 or 0 - enable or disable comments
  $commentsPerPage = 5;            // number of comments per page
  $minimalisticComments     = 0;   // 1 or 0 - enable or disable minimal...
  $minimalisticCommentsForm = 0;   // 1 or 0 - enable or disable minimal...
  $colorCommentsLinkHref      = '#494343'; // colour of comments count b...
  $colorCommentsLinkHrefHover = '#ccc';    // colour of comments count b...
  $emailNotification = '0';        // 0 or your email - send email if so...
  $api      = 0;                   // 1 or 0 - enable or disable the API


3.1 Horní menu

První věc,  již byste mohl/mohla chtít poupravit,  je vzhled hlavní nabídky
v záhlaví stránky.  Za tímto účelem jsou v konfiguračním souboru  v součas-
nosti k dispozici dvě volby:  zda zobrazovat odkaz na  RSS  a především zda
zobrazovat  nabídku jako  takovou.  Přejete-li si libovolný z těchto  prvků
vypnout, změňte hodnotu $showmenu a/nebo $showrss na 0.


3.2 Komentáře k příspěvkům

Ačkoli má  pidiblog zabudovanou podporu uživatelských komentářů  k příspěv-
kům, zdaleka ne každý ji shledává tak potřebnou,  jako její tvůrci, nebo je
spokojen s jejich výchozím vzhledem. Změnou hodnoty $commentsPerPage můžete
ovlivnit počet komentářů, které jsou zobrazeny na jedné stránce.

Změna hodnoty  $minimalisticComments  na 1 zapříčiní  poněkud  kompaktnější
styl výpisu, tj. zobrazení každého komentáře  na jednom řádku a zejména vy-
pnutí zobrazování avatarů.  Podobně $minimalisticCommentsForm umístí na je-
den řádek všechna vstupní pole formuláře.

Volby  $colorCommentsLinkHref a $colorCommentsLinkHrefHover ovlivňují barvu
rámu okolo výpisu počtu komentářů k danému příspěvku  a jeho zvýrazněné va-
rianty;  povoleny jsou všechny  normalizované formáty barev  upravené stan-
dardem W3C CSS.

Konečně, změna hodnoty $enableComments na 0 vypne zobrazování uživatelských
komentářů úplně.


3.3 Výpis příspěvků

Poslední věcí,  již byste  mohl/mohla chtít změnit,  je způsob,  jakým jsou
jednotlivé  příspěvky vypisovány,  či přinejmenším formát kalendářních dat.
Ve výchozím nastavení je zobrazováno plné datum  se slovem rozepsaným měsí-
cem, tedy např. 24. prosince 2008. Preferujete-li číselný formát, například
24. 12. 2008, změňte hodnotu $fullDate na 0.


4. Instalace na server

Instalace pidiblogu je snadná a rychlá:  jednoduše zkopírujte obsah zdrojo-
vého balíku  na vzdálený server  a změňte práva adresáře txts/ na 777, tedy
aby z něj mohl každý číst, zapisovat do něj a přistupovat k jeho obsahu.


5. Použití API

Ve snaze učinit blogování co možná nejpříjemnějším zážitkem nabízí pidiblog
jednoduché  API pracující prostřednictvím  HTTP požadavku GET  a připravené
k použití v software třetích stran. Požadavek má následující tvar:

  http://example.com/index.php?menu=api&apiCode=&password=&text=

Dostupné volby jsou následující:

 ----------+--------------------------------------------------------------
  Volba    | Význam
 ----------+--------------------------------------------------------------
  apiCode  | platný autorizační kód nastavený v konfiguraci
  password | MD5 součet platného přístupového hesla
  text     | text zprávy; mezery nahraďte symbolem +, např. Ahoj+Světe!
 ----------+--------------------------------------------------------------

Možné návratové hodnoty:

 ----------+--------------------------------------------------------------
  Hodnota  | Význam
 ----------+--------------------------------------------------------------
  0        | vše je v pořádku, zpráva byla úspěšně přidána
  1        | neplatný apiCode
  2        | neplatné přístupové heslo
  3        | prázdný nebo chybějící text zprávy
  4        | nebylo možno přidat zprávu; ověřte prosím funkčnost z webové
           | stránky, neboť je možné, že máte chybně nastavená přístupová
           | práva k adresáři txts/
 ----------+--------------------------------------------------------------


6. Chyby

Naleznete-li v programu  jakékoli chyby,  navštivte prosím příslušnou sekci
projektových stránek na adrese <http://code.google.com/p/pidiblog/issues/>.
Jejich nahlášením nám velice pomůžete k jeho zkvalitnění.


7. Autor

Autorem tohoto souboru je Jaromír Hradílek <jhradilek@gmail.com>  ve spolu-
práci s Jindřichem Skácelem, autorem popsaného software.

Tento dokument lze kopírovat, šířit a/nebo upravovat v souladu s podmínkami
GNU Free Documentation License,  verze 1.3 či  jakékoli další verze  vydané
Free Software Foundation.

Pro více informací navštivte <http://www.gnu.org/licenses/>.


8. Copyright

Copyright (C) 2008 Jindřich Skácel

Tento program je svobodný software;  viz soubor LICENCE  pro podmínky  jeho
užití. Je šířen s nadějí, že bude užitečný, avšak BEZ JAKÉKOLI ZÁRUKY, a to
ani záruky jeho PRODEJNOSTI či VHODNOSTI PRO KONKRÉTNÍ ÚČELY.
