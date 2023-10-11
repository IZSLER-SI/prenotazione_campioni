# prenotazione_campioni

Il modulo di prenotazione campioni è un applicativo per la prenotazione dei campioni presso IZSLER condivisa tra Istituto e cliente (ATS/AUSL, privati, ecc…) per tutte le finalità gestite all’interno dei laboratori di analisi. 

Per ogni prenotazione l’istituto può programmare l’attività interna e avere un calendario di tutte le attività da svolgere.

Sono presenti due sezioni:

* Front Office
* Back Office

### Front Office

Nella sezione Front Office il cliente puó decidere il tipo di percorso affinché possa effettuare una prenotazione.

L'accesso al front office avviene tramite SPID.

### Back Office

Nella sezione Back Office puó essere raggiunto eseguendo il login tramite Azure Active Directory oppure tramite login, in caso fosse stato attivato.

La sezione Back Office prevedere due tipi di utenti:

* Utente Laboratorio:
	* utente che puó vedere le prenotazioni fatte per il suo laboratorio, gestire le impostazioni legate al laboratorio a cui é stato assegnato, esportare le prenotazioni.
* Utente Accettazione:
	* utente che puó vedere tutte le prenotazioni dei laboratori presenti nel software, puó gestire gli utenti front office presenti, puó gestire le richieste di accesso da parte di utenti esterni, visualizzare in lettura i dati relativi al laboratorio selezionato.

	
### Dettagli Tecnici

Il software é predisposto per essere avviato in una composizione Docker, come da esempio:

```
version: "3.1"
services:
    prenotazione_campioni:
        image: php:7.4-apache
        container_name: prenotazione_campioni
        restart: always
        ports:
            - "80:80"
        environment:
            AMBIENTE: linux
            MYSQL_HOST: mysqlhost:port
            MYSQL_DATABASE: db_name
            MYSQL_USER: mysql_user
            MYSQL_PASSWORD: mysql_password
        volumes:
            - ./prenotazione_campioni:/var/www/html
```

La variabile d'ambiente AMBIENTE permette di creare configurazioni diverse per gestire:

* accesso via AAD
* parametri di configurazione tenant AAD
* email di invio, copia conoscenza, ricezione
* attivazione login back office

Le configurazioni sono salvate nella tabella `variabili`.

	
	  
 
