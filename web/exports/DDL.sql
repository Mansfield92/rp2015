SET FOREIGN_KEY_CHECKS=0;



DROP TABLE IF EXISTS Depo CASCADE
;
DROP TABLE IF EXISTS Kontrola CASCADE
;
DROP TABLE IF EXISTS Role CASCADE
;
DROP TABLE IF EXISTS Sklad CASCADE
;
DROP TABLE IF EXISTS Stanice CASCADE
;
DROP TABLE IF EXISTS StaniceTrasy CASCADE
;
DROP TABLE IF EXISTS Trasa CASCADE
;
DROP TABLE IF EXISTS Trat CASCADE
;
DROP TABLE IF EXISTS Vlak CASCADE
;
DROP TABLE IF EXISTS VlakJede CASCADE
;
DROP TABLE IF EXISTS Zamestnanec CASCADE
;

CREATE TABLE Depo
(
	id INT NOT NULL,
	nazev VARCHAR(100),
	GPS VARCHAR(100),
	mesto VARCHAR(50),
	adresa VARCHAR(100),
	cislo_popisne INT,
	stat VARCHAR(50),
	telefon VARCHAR(20),
	kapacita INT,
	PRIMARY KEY (id),
	UNIQUE UQ_Depo_id(id)

) 
;


CREATE TABLE Kontrola
(
	id INT NOT NULL,
	provedl INT,
	vlak VARCHAR(50),
	datum_expirace DATE,
	kontrola_od DATE,
	kontrola_do DATE,
	vysledek VARCHAR(1000),
	PRIMARY KEY (id),
	UNIQUE UQ_Kontrola_id(id),
	KEY (vlak),
	KEY (provedl)

) 
;


CREATE TABLE Role
(
	id INT NOT NULL AUTO_INCREMENT,
	nazev VARCHAR(50),
	PRIMARY KEY (id),
	UNIQUE UQ_Role_id(id)

) 
;


CREATE TABLE Sklad
(
	id INT NOT NULL,
	kapacita INT,
	depo INT NOT NULL,
	PRIMARY KEY (id),
	UNIQUE UQ_Sklad_id(id),
	KEY (depo)

) 
;


CREATE TABLE Stanice
(
	id INT NOT NULL,
	GPS VARCHAR(100),
	nazev VARCHAR(100),
	mesto VARCHAR(100),
	adresa VARCHAR(100),
	stat VARCHAR(100),
	PRIMARY KEY (id),
	UNIQUE UQ_Stanice_id(id)

) 
;


CREATE TABLE StaniceTrasy
(
	trasa INT NOT NULL,
	stanice INT NOT NULL,
	poradi INT NOT NULL,
	KEY (trasa),
	KEY (stanice)

) 
;


CREATE TABLE Trasa
(
	id INT NOT NULL,
	nazev_trasy VARCHAR(100),
	PRIMARY KEY (id),
	UNIQUE UQ_Trasa_id(id)

) 
;


CREATE TABLE Trat
(
	id INT NOT NULL,
	ohodnoceni FLOAT(5,2),
	vychozi_stanice INT NOT NULL,
	cilova_stanice INT NOT NULL,
	aktivni TINYINT NOT NULL DEFAULT true,
	PRIMARY KEY (id),
	UNIQUE UQ_Trat_id(id),
	KEY (vychozi_stanice),
	KEY (cilova_stanice)

) 
;


CREATE TABLE Vlak
(
	cislo_zkv VARCHAR(50) NOT NULL,
	rada VARCHAR(50),
	sokv VARCHAR(50),
	UIC_OLD VARCHAR(50),
	datum_preznaceni DATE,
	m_stav VARCHAR(20),
	flag_eko BOOL,
	elektromer INT,
	spotreba_nafty INT,
	ele_ohrev INT,
	vkv VARCHAR(256),
	vz VARCHAR(50),
	km_probeh_po VARCHAR(8),
	vmax VARCHAR(50),
	pocet_naprav INT,
	delka FLOAT(5,2),
	hmotnost INT,
	brvaha_g FLOAT(5,2),
	brvaha_p FLOAT(5,2),
	depo INT NOT NULL,
	PRIMARY KEY (cislo_zkv),
	UNIQUE UQ_Vlak_cislo_zkv(cislo_zkv),
	KEY (depo)

) 
;


CREATE TABLE VlakJede
(
	vlak VARCHAR(50) NOT NULL,
	trasa INT NOT NULL,
	pozice VARCHAR(100),
	KEY (trasa),
	KEY (vlak)

) 
;


CREATE TABLE Zamestnanec
(
	id INT NOT NULL,
	druh_pomeru VARCHAR(50),
	email VARCHAR(100),
	jmeno VARCHAR(50),
	prijmeni VARCHAR(50),
	telefon VARCHAR(50),
	mesto VARCHAR(50),
	adresa VARCHAR(100),
	cislo_popisne INT,
	stat VARCHAR(50),
	depo INT NOT NULL,
	role INT NOT NULL,
	smlouva_od DATE,
	login VARCHAR(50),
	password VARCHAR(60),
	smlouva_do DATE,
	PRIMARY KEY (id),
	UNIQUE UQ_Zamestnanec_id(id),
	KEY (role),
	KEY (depo)

) 
;



SET FOREIGN_KEY_CHECKS=1;


ALTER TABLE Kontrola ADD CONSTRAINT FK_Kontrola_Vlak 
	FOREIGN KEY (vlak) REFERENCES Vlak (cislo_zkv)
;

ALTER TABLE Kontrola ADD CONSTRAINT FK_Kontrola_Zamestnanec 
	FOREIGN KEY (provedl) REFERENCES Zamestnanec (id)
;

ALTER TABLE Sklad ADD CONSTRAINT FK_Sklad_Depo 
	FOREIGN KEY (depo) REFERENCES Depo (id)
	ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE StaniceTrasy ADD CONSTRAINT FK_StaniceTrasy_Trasa 
	FOREIGN KEY (trasa) REFERENCES Trasa (id)
	ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE StaniceTrasy ADD CONSTRAINT FK_StaniceTrasy_Stanice 
	FOREIGN KEY (stanice) REFERENCES Stanice (id)
	ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE Trat ADD CONSTRAINT FK_Trat_Stanice_02 
	FOREIGN KEY (vychozi_stanice) REFERENCES Stanice (id)
;

ALTER TABLE Trat ADD CONSTRAINT FK_Trat_Stanice_03 
	FOREIGN KEY (cilova_stanice) REFERENCES Stanice (id)
;

ALTER TABLE Vlak ADD CONSTRAINT FK_Vlak_Depo 
	FOREIGN KEY (depo) REFERENCES Depo (id)
	ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE VlakJede ADD CONSTRAINT FK_VlakJede_Trasa 
	FOREIGN KEY (trasa) REFERENCES Trasa (id)
	ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE VlakJede ADD CONSTRAINT FK_VlakJede_Vlak 
	FOREIGN KEY (vlak) REFERENCES Vlak (cislo_zkv)
	ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE Zamestnanec ADD CONSTRAINT FK_Zamestnanec_Role 
	FOREIGN KEY (role) REFERENCES Role (id)
;

ALTER TABLE Zamestnanec ADD CONSTRAINT FK_Zamestnanec_Depo 
	FOREIGN KEY (depo) REFERENCES Depo (id)
	ON DELETE RESTRICT ON UPDATE RESTRICT
;
