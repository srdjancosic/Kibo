DROP TABLE admins;--and of expresion

CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `actions` longtext CHARACTER SET utf8 NOT NULL,
  `check_code` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;--and of expresion

INSERT INTO admins VALUES("2","srdjan","a2cf7a33dad62c102a1fdfc8eaf832c4","{\"categories\":{\"1\":\"add\",\"2\":\"edit\",\"3\":\"delete\"},\"content\":{\"1\":\"add\",\"2\":\"edit\",\"3\":\"delete\"},\"elements\":{\"1\":\"add\",\"2\":\"edit\",\"3\":\"delete\"},\"pages\":{\"1\":\"add\",\"2\":\"edit\",\"3\":\"delete\"},\"settings\":{\"1\":\"add\",\"2\":\"edit\",\"3\":\"delete\",\"4\":\"settings\",\"5\":\"view\"},\"admins\":{\"1\":\"add\",\"2\":\"edit\",\"3\":\"delete\",\"4\":\"view\"},\"html\":{\"1\":\"edit\"},\"menu\":{\"1\":\"edit\"},\"user_groups\":{\"2\":\"edit\",\"1\":\"add\",\"3\":\"delete\"},\"forms\":{\"2\":\"edit\",\"1\":\"add\",\"3\":\"delete\"},\"database\":{\"2\":\"export\",\"1\":\"import\",\"3\":\"empty\"},\"tables\":{\"2\":\"edit\",\"1\":\"add\",\"3\":\"delete\",\"4\":\"view\"},\"code_editor\":{\"1\":\"edit\"}}","7251008639");--and of expresion
INSERT INTO admins VALUES("4","ivan","202cb962ac59075b964b07152d234b70","{\"categories\":{\"1\":\"add\",\"2\":\"edit\",\"3\":\"delete\"},\"content\":{\"1\":\"add\",\"2\":\"edit\",\"3\":\"delete\"},\"elements\":{\"1\":\"add\",\"2\":\"edit\",\"3\":\"delete\"},\"pages\":{\"1\":\"add\",\"2\":\"edit\",\"3\":\"delete\"},\"settings\":{\"1\":\"add\",\"2\":\"edit\",\"3\":\"delete\",\"4\":\"settings\",\"5\":\"view\"},\"admins\":{\"1\":\"add\",\"2\":\"edit\",\"3\":\"delete\",\"4\":\"view\"},\"html\":{\"1\":\"edit\"},\"menu\":{\"1\":\"edit\"},\"user_groups\":{\"2\":\"edit\",\"1\":\"add\",\"3\":\"delete\"},\"forms\":{\"2\":\"edit\",\"1\":\"add\",\"3\":\"delete\"},\"database\":{\"2\":\"export\",\"1\":\"import\",\"3\":\"empty\"},\"tables\":{\"2\":\"edit\",\"1\":\"add\",\"3\":\"delete\",\"4\":\"view\"},\"code_editor\":{\"1\":\"edit\"}}","2534918706");--and of expresion



DROP TABLE c_komentari;--and of expresion

CREATE TABLE `c_komentari` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_id` int(11) NOT NULL,
  `autor` int(11) NOT NULL,
  `komentar` longtext CHARACTER SET utf8 NOT NULL,
  `datum_postavljanja` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;--and of expresion

INSERT INTO c_komentari VALUES("1","4","1","zavrseno","2012-01-19 23:24:16");--and of expresion
INSERT INTO c_komentari VALUES("2","2","2","čekamo još na dozvole da nam pošalju","2012-01-20 12:52:56");--and of expresion
INSERT INTO c_komentari VALUES("3","7","1","Dovrsiti:\n1. postaviti div koji ce da obuhvata sve nodove koji se stampaju zajedno (naravno uz odgovarajuci css i id).. \n2. istestirati sve vezeano za ovaj tip elementa..","2012-01-20 22:08:47");--and of expresion
INSERT INTO c_komentari VALUES("4","2","1","u css promeniti:\n\n.blue-box p {\n    font-size: 16px;\n    margin: 0;\n}\nu :\n.blue-box p {\n    font-size: 15px;\n    margin: 0;\n} \njer na ff probija i na jednom i na drugom jeziku","2012-01-20 22:14:45");--and of expresion
INSERT INTO c_komentari VALUES("5","9","2","RECENICU PRVU PUT STE NA FASH SHOP ISPAVI NA PRVI PUT STE NA SAJTU SAVE STOJKOVA.\n\npromeni recenicu ukoliko zelite da otkazete porudzbinu pozovite telefon 062 332 337\n\npovezi mi kada ljudi poruce sliku kako da ja to vidim?\n\nkako da vrsim zamenu slike koje ubacujem nove i na koji nacin mogu da stavim da je prodata neka od slika?","2012-01-23 07:19:41");--and of expresion
INSERT INTO c_komentari VALUES("6","9","2","Razmisli gde na sajtu bi mogli da stavimo fotografije zidova legata i otvaranja. Moglo bi negde da stoji rubrika gde ce uci ljudi i videti izgled legata, raznih priloga o stojkovu i video zapise.\n\nStavi preuzimanje kataloga.","2012-01-23 07:20:18");--and of expresion
INSERT INTO c_komentari VALUES("7","7","1","zavrseno :)","2012-01-23 14:46:13");--and of expresion
INSERT INTO c_komentari VALUES("8","9","1","proveriti kao radi active klasa kod menija kada je aktivna prijava ili registracija ili tako nesto...","2012-01-23 15:46:51");--and of expresion
INSERT INTO c_komentari VALUES("9","9","1","odradjeno","2012-01-23 16:07:20");--and of expresion
INSERT INTO c_komentari VALUES("10","9","1","MONATIPIJE ispraviti u MONOTIPIJE ->done\nNAPISI VECIM SLOVIMA U NASLOVU 20-30% POPUSTA levo od newsletera->done\nkontakt strana->done\nRECENICU PRVU PUT STE NA FASH SHOP ISPAVI NA PRVI PUT STE NA SAJTU SAVE STOJKOVA.->done\npromeni recenicu ukoliko zelite da otkazete porudzbinu pozovite telefon 062 332 337->done\nkad neko rezervise salje se mail njima->done","2012-01-23 21:55:10");--and of expresion
INSERT INTO c_komentari VALUES("12","10","2","sredjeno","2012-01-25 10:34:22");--and of expresion
INSERT INTO c_komentari VALUES("13","15","1","sredjeno","2012-01-25 15:24:58");--and of expresion
INSERT INTO c_komentari VALUES("14","15","1","","2012-01-25 15:25:06");--and of expresion
INSERT INTO c_komentari VALUES("15","14","1","sredjeno","2012-01-25 15:31:04");--and of expresion



DROP TABLE c_log;--and of expresion

CREATE TABLE `c_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `naslov` varchar(255) CHARACTER SET utf8 NOT NULL,
  `opis` longtext CHARACTER SET utf8 NOT NULL,
  `prioritet` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `autor` int(11) NOT NULL,
  `datum_postavljanja` datetime NOT NULL,
  `kome` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;--and of expresion

INSERT INTO c_log VALUES("1","Dizajn za Oblak Internacional","Napraviti dizajn","3","1","2","2012-01-19 23:12:24","2");--and of expresion
INSERT INTO c_log VALUES("2","oblakinternacional.com","Pristupni parametri:\nDomen: oblakinternacional.com\nKorisničko ime: oblakint\nLozinka: 80bx14VBur\n\npostaviti cms i instalirati bazu i na osnovu strukture koju ujutru budemo definisali, napraviti potrebne elemente i kad dizajn bude gotov, napraviti odgovarajuci css","2","0","2","2012-01-19 23:14:23","1");--and of expresion
INSERT INTO c_log VALUES("3","Crop u filemanageru","Umesto postojece fje za imageresize ubaciti fju za kropovanje, kako bismo dobili zeljene dimenzije","2","0","2","2012-01-19 23:16:00","1:2");--and of expresion
INSERT INTO c_log VALUES("4","Promena redosleda prikaza u Log sistemu","E treba promeniti da posle statusa, sortira po prioritetu, pa tek onda po datumu..","3","1","2","2012-01-19 23:17:16","1");--and of expresion
INSERT INTO c_log VALUES("7","Tagovi na frontu","Kreirati element modele \\\"Tags view\\\" i \\\"Tag cloud\\\", prvi se odnosi na jednu vest i ispisuje tagove ispod nje, a drugi na najcesce koriscene tagove.. Potrebna i strana za pretragu po odredjenom tagu..","1","1","2","2012-01-20 00:22:57","1:2");--and of expresion
INSERT INTO c_log VALUES("8","Komentari","Napraviti element model za prikaz komentara, slicno kao sto se radi za articles, znaci kako se prikazuje svaki komentar i naslov da li ima i da li se prikazuje","1","0","2","2012-01-20 09:19:54","1:2");--and of expresion
INSERT INTO c_log VALUES("9","savastojkov.rs","MONATIPIJE ispraviti u MONOTIPIJE\n\nNAPISI VECIM SLOVIMA U NASLOVU 20-30% POPUSTA levo od newsletera\n\n \nispravi vas e-mail za newsleter na vas e-mail za registraciju\n\nstavi KONTAKT obavezno\n\n+381 62 332 337\n+381 63 8944 824\n\ngalerijaslika@yahoo.com\nwww.savastojkov.rs\nwww.galerijaslika.rs\n\nSredacka 10a, Beograd\ni mapu grada\n\nSTAVI SLIKU SAVE STOJKOVA NEGDE. MNOGO MI JE BELILA U POZADINI PA PROBAJ NESTO DA IMPROVIZUJES.","2","0","2","2012-01-23 07:19:06","1:2:3");--and of expresion
INSERT INTO c_log VALUES("10","Redizajn forma na Happymedia.rs","Postaviti polje za broj telefona koje nije obavezno i dodati mejl covicmarko@yahoo.com kako ne bi forward-ovali svaki put mejlove.","1","1","3","2012-01-24 10:29:19","1:2");--and of expresion
INSERT INTO c_log VALUES("11","Blog Happymedia.rs","Napraviti blog kako bi ubacili teme - online shop internet prodavnica , grupna kupovina , i druge ključne reči ......","1","1","3","2012-01-24 10:31:09","1:2");--and of expresion
INSERT INTO c_log VALUES("14","Na CMS ispravka","Ispraviti da kod elemenata za parent nudi samo elemente na odgovarajucem jeziku","2","1","1","2012-01-25 13:04:48","1");--and of expresion
INSERT INTO c_log VALUES("15","CMS articles ispravka","Kada se kreira novi article u odredjenoj kategoriji, vraca se na index, a treba na sadrzaj te kategorije da te vrati","3","1","1","2012-01-25 14:42:44","1");--and of expresion
INSERT INTO c_log VALUES("13","Izmene beleski","U email-u koji se salje, trebalo bi da se ispise i sadrzaj email-a i trebalo bi razviti sistem za reply sa email-a odmah..","1","0","2","2012-01-24 10:42:26","1:2");--and of expresion
INSERT INTO c_log VALUES("21","Izmene na kibu","1. Kod editovanja strane, omoguciti da se dodaju/obrisu kategorije za koje je ta strana šablon (category view, single page view)\n\n2. export database\n\n3. prepraviti dodavanje/brisanje jezika (srediti onaj skarabudženi kod)\n\n4. ubaciti DB_PREFIX kod svih SQL upita.. *Ja sam ubacio na frontu*\n\n5. instaliranje baze","2","0","2","2012-01-30 00:59:13","1:2");--and of expresion



DROP TABLE c_users;--and of expresion

CREATE TABLE `c_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `approved` int(11) NOT NULL,
  `code` varchar(255) CHARACTER SET utf8 NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;--and of expresion

INSERT INTO c_users VALUES("1","1","","Srdjan Cosic","srdjancosic1987@gmail.com","a2cf7a33dad62c102a1fdfc8eaf832c4","1");--and of expresion
INSERT INTO c_users VALUES("2","1","","Ivan Bajalovic","bajalovic@gmail.com","202cb962ac59075b964b07152d234b70","1");--and of expresion
INSERT INTO c_users VALUES("3","1","","Marko Covic","covicmarko@yahoo.com","202cb962ac59075b964b07152d234b70","1");--and of expresion



DROP TABLE category;--and of expresion

CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'rewrite link',
  `href` varchar(255) CHARACTER SET utf8 NOT NULL,
  `parent` int(11) NOT NULL COMMENT 'parent category id',
  `is_parent` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL COMMENT 'language id',
  `page_id` varchar(255) CHARACTER SET utf8 NOT NULL,
  `page_single` varchar(500) CHARACTER SET utf8 NOT NULL,
  `kiboeasy` int(11) NOT NULL,
  `has_dimensions` int(11) NOT NULL,
  `ref_id` int(11) NOT NULL,
  `category_description` longtext CHARACTER SET utf8 NOT NULL,
  `category_keywords` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;--and of expresion

INSERT INTO category VALUES("1","registracija","registracija","","0","1","1","registracija-sve","greska","0","0","0","","");--and of expresion
INSERT INTO category VALUES("4","test category 1","test-category-1","","1","0","1","0","0","0","0","0","","");--and of expresion



DROP TABLE category_custom_fields;--and of expresion

CREATE TABLE `category_custom_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `type` varchar(255) CHARACTER SET utf8 NOT NULL,
  `category` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;--and of expresion




DROP TABLE config;--and of expresion

CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unlogged_user_group` int(11) NOT NULL,
  `wait_for_approval` int(11) NOT NULL,
  `allow_fb_registration` int(11) NOT NULL,
  `site_email` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;--and of expresion

INSERT INTO config VALUES("1","2","1","0","mrs_office@srdja.com");--and of expresion



DROP TABLE constants;--and of expresion

CREATE TABLE `constants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;--and of expresion

INSERT INTO constants VALUES("1","CATEGORY_ID");--and of expresion
INSERT INTO constants VALUES("2","CONTENT_ID");--and of expresion
INSERT INTO constants VALUES("3","USER_ID");--and of expresion
INSERT INTO constants VALUES("4","USER_GROUP_ID");--and of expresion
INSERT INTO constants VALUES("5","DATE");--and of expresion
INSERT INTO constants VALUES("6","DATE_TIME");--and of expresion
INSERT INTO constants VALUES("7","LANG_ID");--and of expresion



DROP TABLE custom_fields;--and of expresion

CREATE TABLE `custom_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `value` longtext CHARACTER SET utf8 NOT NULL,
  `node` int(11) NOT NULL COMMENT 'node id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;--and of expresion




DROP TABLE field_types;--and of expresion

CREATE TABLE `field_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;--and of expresion

INSERT INTO field_types VALUES("1","Text box","text");--and of expresion
INSERT INTO field_types VALUES("2","Password","password");--and of expresion
INSERT INTO field_types VALUES("3","Text body","textarea ");--and of expresion
INSERT INTO field_types VALUES("4","Dropdown menu","select");--and of expresion
INSERT INTO field_types VALUES("5","Multiple dropdown menu","select_multiple");--and of expresion
INSERT INTO field_types VALUES("6","Checkbox","checkbox");--and of expresion
INSERT INTO field_types VALUES("7","Radiobutton","radiobutton");--and of expresion
INSERT INTO field_types VALUES("8","Button","button");--and of expresion
INSERT INTO field_types VALUES("9","Hidden field","hidden");--and of expresion
INSERT INTO field_types VALUES("10","Date picker","datapicker");--and of expresion
INSERT INTO field_types VALUES("11","Color picker","colorpicker");--and of expresion
INSERT INTO field_types VALUES("12","File select","fileupload");--and of expresion



DROP TABLE form_fields;--and of expresion

CREATE TABLE `form_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) NOT NULL,
  `field_type` varchar(255) NOT NULL,
  `table_field` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `required` int(11) NOT NULL,
  `validation` varchar(255) NOT NULL,
  `error_message` varchar(1000) NOT NULL,
  `value` longtext NOT NULL,
  `from_table` int(11) NOT NULL,
  `selected_value` varchar(255) NOT NULL,
  `constant` varchar(255) NOT NULL,
  `identificator` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `hint` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;--and of expresion

INSERT INTO form_fields VALUES("1","1","text","naslov","0","naslov","Naslov","1","","","","0","","","","medium","");--and of expresion
INSERT INTO form_fields VALUES("2","1","textarea","opis","1","opis","Detaljnije","0","","","","0","","","","medium","");--and of expresion
INSERT INTO form_fields VALUES("3","1","hidden","autor","4","autor","","0","","","","0","","USER_ID","","","");--and of expresion
INSERT INTO form_fields VALUES("4","1","select","prioritet","2","prioritet","Prioritet","0","","","1;Nizak\n2;Srednji\n3;Visok","0","","","","medium","");--and of expresion
INSERT INTO form_fields VALUES("5","1","select_multiple","kome","3","kome","Za","1","","","c_users\nid\nname","1","","","","medium","");--and of expresion
INSERT INTO form_fields VALUES("8","2","text","0","0","email","Email","0","","","","0","","","","","");--and of expresion
INSERT INTO form_fields VALUES("7","1","hidden","0","5","action","","0","","","","0","addTask","","","","");--and of expresion
INSERT INTO form_fields VALUES("9","2","password","0","0","password","Password","0","","","","0","","","","","");--and of expresion
INSERT INTO form_fields VALUES("10","2","hidden","0","0","action","","0","","","","0","login","","","","");--and of expresion
INSERT INTO form_fields VALUES("11","3","hidden","0","0","action","","0","","","","0","logout","","","","");--and of expresion



DROP TABLE forms;--and of expresion

CREATE TABLE `forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `identificator` varchar(255) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `submit_value` varchar(255) NOT NULL,
  `submit_class` varchar(255) NOT NULL,
  `submit_id` varchar(255) NOT NULL,
  `file_upload` int(11) NOT NULL COMMENT '0 or 1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;--and of expresion

INSERT INTO forms VALUES("1","log","","c_log","/work.php","Dodaj","btn primary","","0");--and of expresion
INSERT INTO forms VALUES("2","login","","c_users","/work.php","Uloguj se","btn primary","","0");--and of expresion
INSERT INTO forms VALUES("3","logout","","c_users","/work.php","Izloguj se","btn primary","","0");--and of expresion



DROP TABLE languages;--and of expresion

CREATE TABLE `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `lang_code` varchar(3) NOT NULL COMMENT '3 char ANSII standard',
  `active` int(11) NOT NULL COMMENT '1 - installed; 0 - not installed',
  `default` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;--and of expresion

INSERT INTO languages VALUES("1","latinica","lat","1","1");--and of expresion



DROP TABLE leaves;--and of expresion

CREATE TABLE `leaves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `css_class` varchar(255) CHARACTER SET utf8 NOT NULL,
  `css_id` varchar(255) CHARACTER SET utf8 NOT NULL,
  `lang_id` int(3) NOT NULL,
  `parent` int(11) NOT NULL,
  `content` longtext CHARACTER SET utf8 NOT NULL,
  `content_type` varchar(255) CHARACTER SET utf8 NOT NULL,
  `ref_id` int(11) NOT NULL,
  `user_group` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;--and of expresion

INSERT INTO leaves VALUES("1","header","container-fluid","","1","16","","","0","0","0");--and of expresion
INSERT INTO leaves VALUES("3","container-fluid","container-fluid","","1","0","","","0","0","0");--and of expresion
INSERT INTO leaves VALUES("5","forma","well","","1","0","1","form","0","1","0");--and of expresion
INSERT INTO leaves VALUES("13","login","login","","1","1","2","form","0","2","0");--and of expresion
INSERT INTO leaves VALUES("7","content","content","","1","3","","","0","0","1");--and of expresion
INSERT INTO leaves VALUES("15","home","home","","1","1","|:|undefined|:||:|<a class=\\\"btn primary\\\" title=\\\"home\\\" href=\\\"/\\\">Home</a>","html","0","1","0");--and of expresion
INSERT INTO leaves VALUES("9","sidebar","sidebar","","1","3","","","0","0","0");--and of expresion
INSERT INTO leaves VALUES("14","logout","logout","","1","1","3","form","0","1","0");--and of expresion
INSERT INTO leaves VALUES("11","listing","hero-unit","","1","0","listTasks|:|site","pluginView","0","1","0");--and of expresion
INSERT INTO leaves VALUES("16","header-holder","topbar-inner","","1","0","","","0","0","0");--and of expresion
INSERT INTO leaves VALUES("17","forma title","forma-title","","1","0","|:|undefined|:||:|<h1>Nova beleška</h1>","html","0","1","0");--and of expresion
INSERT INTO leaves VALUES("18","greska","greska","","1","0","|:||:||:||:|<p>*Uneli ste pogresnu email adresu, i/ili lozinku...</p>|:|1|:|id|:|desc|:|","node","0","0","0");--and of expresion
INSERT INTO leaves VALUES("23","test plugin","test-plugin","","1","0","|:||:||:|test","plugin","0","0","0");--and of expresion
INSERT INTO leaves VALUES("24","prikaz kategorije","prikaz-kategorije","","1","0","|:||:||:|1|:|<h1>f:name</h1>\nf:url|:|100|:|id|:|desc|:|","node","0","0","0");--and of expresion
INSERT INTO leaves VALUES("25","probni element","probni-element","","1","0","","","0","0","0");--and of expresion



DROP TABLE node;--and of expresion

CREATE TABLE `node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `picture` varchar(255) CHARACTER SET utf8 NOT NULL,
  `short_desc` longtext CHARACTER SET utf8 NOT NULL,
  `long_desc` longtext CHARACTER SET utf8 NOT NULL,
  `category` int(11) NOT NULL COMMENT 'category id',
  `date` datetime NOT NULL,
  `lang_id` int(11) NOT NULL COMMENT 'language id',
  `url` varchar(255) NOT NULL COMMENT 'rewrite link',
  `ref_id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `node_keywords` varchar(255) CHARACTER SET utf8 NOT NULL,
  `node_description` longtext CHARACTER SET utf8 NOT NULL,
  `num_views` int(11) NOT NULL,
  `num_comments` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `votes` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;--and of expresion

INSERT INTO node VALUES("1","greska","","","","1","2012-01-19 09:08:20","1","greska","0","0","","","0","0","0","0");--and of expresion
INSERT INTO node VALUES("3","nesto","","","sadfasdf","2","2012-01-20 14:26:24","1","nesto","0","0","","","0","0","0","0");--and of expresion
INSERT INTO node VALUES("6","nesto","","","","4","2012-01-30 15:36:48","1","nesto","0","0","","","0","0","0","0");--and of expresion



DROP TABLE pages;--and of expresion

CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `category` varchar(255) CHARACTER SET utf8 NOT NULL,
  `lang_id` int(11) NOT NULL COMMENT 'language id',
  `header` int(11) NOT NULL COMMENT 'leaf id',
  `footer` int(11) NOT NULL COMMENT 'leaf id',
  `content` int(11) NOT NULL COMMENT 'leaf id',
  `ref_id` int(11) NOT NULL,
  `add_footer` longtext CHARACTER SET utf8 NOT NULL,
  `add_head` longtext CHARACTER SET utf8 NOT NULL,
  `page_title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `page_description` longtext CHARACTER SET utf8 NOT NULL,
  `page_keywords` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;--and of expresion

INSERT INTO pages VALUES("1","index","index","","1","16","0","3","0","","","","","");--and of expresion
INSERT INTO pages VALUES("8","registracija sve","registracija-sve","","1","1","0","3","0","","","","","");--and of expresion
INSERT INTO pages VALUES("7","plugin test","plugin-test","","1","1","0","3","0","","","","","");--and of expresion
INSERT INTO pages VALUES("4","beleska","beleska","","1","16","0","3","0","","","","","");--and of expresion
INSERT INTO pages VALUES("5","greska","greska","","1","16","0","3","0","","","","","");--and of expresion
INSERT INTO pages VALUES("9","testing page","testing-page","","1","16","0","3","0","","","","","");--and of expresion



DROP TABLE pages_leaves;--and of expresion

CREATE TABLE `pages_leaves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `leaf_id` int(11) NOT NULL,
  `leaf_destination` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;--and of expresion

INSERT INTO pages_leaves VALUES("1","1","5","9","1");--and of expresion
INSERT INTO pages_leaves VALUES("4","1","17","9","0");--and of expresion
INSERT INTO pages_leaves VALUES("3","1","11","7","0");--and of expresion
INSERT INTO pages_leaves VALUES("19","8","24","7","0");--and of expresion
INSERT INTO pages_leaves VALUES("8","4","17","9","0");--and of expresion
INSERT INTO pages_leaves VALUES("9","4","5","9","0");--and of expresion
INSERT INTO pages_leaves VALUES("10","5","18","7","0");--and of expresion
INSERT INTO pages_leaves VALUES("18","8","17","9","0");--and of expresion
INSERT INTO pages_leaves VALUES("17","7","23","9","0");--and of expresion



DROP TABLE plugin_views;--and of expresion

CREATE TABLE `plugin_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin` varchar(500) CHARACTER SET utf8 NOT NULL,
  `view_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `method_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;--and of expresion




DROP TABLE plugins;--and of expresion

CREATE TABLE `plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `plugin_name` varchar(500) CHARACTER SET utf8 NOT NULL,
  `category` int(11) NOT NULL,
  `node` int(11) NOT NULL,
  `url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `status` int(11) NOT NULL,
  `admin` int(11) NOT NULL,
  `easy` int(11) NOT NULL,
  `front` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;--and of expresion




DROP TABLE settings;--and of expresion

CREATE TABLE `settings` (
  `site_title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `site_keywords` varchar(255) CHARACTER SET utf8 NOT NULL,
  `site_description` longtext CHARACTER SET utf8 NOT NULL,
  `head_js` longtext CHARACTER SET utf8 NOT NULL,
  `footer_js` longtext CHARACTER SET utf8 NOT NULL,
  `pagination_url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `lang_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;--and of expresion

INSERT INTO settings VALUES("Log sistem","log sistem","","<script src=\\\"/js/jquery-1.6.2.min.js\\\"></script>\n\n<script src=\\\"/skripta.js\\\">\n</script>\n\n<link rel=\\\"stylesheet\\\" href=\\\"http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css\\\">\n\n<script>\njQuery.expr[\\\':\\\'].containsi = function(a, i, m) {\nreturn jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;\n};\n</script>","","strana","1");--and of expresion



DROP TABLE tags;--and of expresion

CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;--and of expresion

INSERT INTO tags VALUES("9","1","nesto","nesto");--and of expresion



DROP TABLE user_groups;--and of expresion

CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;--and of expresion

INSERT INTO user_groups VALUES("1","registrovani");--and of expresion
INSERT INTO user_groups VALUES("2","neregistrovani");--and of expresion



