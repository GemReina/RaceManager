--Afegir taules noves

PROMPT Borrant les taules Factures i Parametres 
DROP TABLE Factures;
DROP TABLE Parametres;


PROMPT Creant nova taula Factures
CREATE TABLE Factures(
numero DECIMAL(5,0),
vehicle VARCHAR2(10),
propietari VARCHAR2(15),
cursa VARCHAR(15),
temps DECIMAL(6,3),
combustible DECIMAL(4,2),
servei DECIMAL(6,2),
iva DECIMAL(6,2),
total DECIMAL(6,2),
CONSTRAINT CP_Factures PRIMARY KEY (numero, vehicle),
CONSTRAINT CF_propietari FOREIGN KEY (propietari) REFERENCES Usuaris(alias),
CONSTRAINT CF_vehicle FOREIGN KEY (vehicle) REFERENCES Vehicles(codi),
CONSTRAINT CF_cursa FOREIGN KEY (cursa) REFERENCES Curses(codi)
);

PROMPT Creant nova taula Parametres
CREATE TABLE Parametres(
nom VARCHAR2(15) PRIMARY KEY,
valor DECIMAL(6,2)
);

PROMPT Afegint els parametres...

PROMPT Parametre amb nom IVA y valor 21
INSERT INTO Parametres VALUES('IVA', 21);

PROMPT Parametre amb nom preuServei i valor 10
INSERT INTO Parametres VALUES('preuServei', 10);

COMMIT;
PROMPT Proces finalitzat.

SELECT * FROM Parametres;