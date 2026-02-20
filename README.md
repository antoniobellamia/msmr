# MSMR - Management System for Mail & Routing 📦🚀

Benvenuti nel repository di **MSMR**, il nostro progetto per l'A.S. 2024/2025 (tematica "Servizi web per le imprese"). 

Abbiamo sviluppato questa piattaforma web per gestire l'intero ciclo di vita delle spedizioni. L'idea di base è semplice: creare un unico hub dove mittenti, destinatari, corrieri e amministratori possano interagire e tracciare i pacchi senza impazzire.

## Cosa fa MSMR?
* **Tracking e Gestione:** Motore di ricerca per tracciare i pacchi in tempo reale (gestiamo 13 stati di spedizione diversi, da "Ordine ricevuto" a "Reso effettuato").
* **Algoritmo di Smistamento:** Assegna automaticamente i pacchi ai corrieri basandosi sul CAP di destinazione e cercando di bilanciare il carico di lavoro.
* **Abbonamenti:** Per spedire serve un piano (Basic, Standard o Unlimited).
* **Mobile-First:** Interfaccia ultra-leggera e responsive basata su PureCSS e W3.CSS, pensata per i corrieri che usano il sito da smartphone.
* **Mappe Integrate:** I corrieri hanno Google Maps integrato nella loro dashboard per calcolare il percorso verso il destinatario.

## I ruoli utente all'interno della piattaforma
* **Clienti (Utenti):** Si registrano, scelgono un piano, spediscono e tracciano la loro roba.
* **Corrieri:** Hanno un'area dedicata con le consegne da fare oggi nella loro zona e lo storico di quelle completate.
* **Admin Corrieri:** I "capo-area". Monitorano i magazzini, gestiscono la flotta dei corrieri e assegnano le zone di competenza.
* **Super Admin:** Accesso completo. Vedono tutto, gestiscono tutti gli utenti e hanno l'accesso diretto al DB (tramite phpMyAdmin).

## Setup in locale

1. Clona il repo:
   ```bash
   git clone https://github.com/tuo-utente/msmr.git
   ```
2. Importa il file del database `msmr_db.sql` nel tuo server MySQL.
3. Configura le credenziali del DB.
4. Avvia il server locale e vai su `http://localhost/msmr/`.

## Esempio di test
1. Clicca su **Sign-Up** e crea un utente (inserisci un indirizzo italiano valido).
2. Dalla tua Dashboard, simula l'acquisto di un abbonamento (es. Basic a €6,00).
3. Clicca su **Spedisci Adesso** e crea il tuo primo ordine.
4. Fai il login con l'account admin per vedere come il pacco viene smistato!

## FAQ & Troubleshooting
Se qualcosa va storto, ecco i problemi più comuni:
* **Errore 404 (Pagina non trovata):** Probabilmente un ID spedizione o utente errato.
* **Errore 403 (Accesso Negato):** Stai cercando di entrare in una sezione per cui non hai i permessi (es. area Admin con un account normale).
* **Errore 500 (Internal Server Error):** Il server o il DB hanno avuto problemi.
Controlla i log.
* **Password dimenticata / Account bloccato?** Contatta l'Assistenza Clienti dalla barra di navigazione.

## 📚 Documentazione Completa
Per capire esattamente come funzionano i flussi, le regole di assegnazione dei magazzini e vedere tutte le schermate, apri il nostro:
👉 **[Manuale Utente MSMR (PDF)](Documentazione/Manuale_Utente_piattaforma_MSMR.pdf)**

## 💻 Crediti e sviluppatori
Questo software è stato sviluppato nell'ambito dei lavori dell'area di progetto dell'A.S. 2024/2025 ("Servizi web per le imprese").

**Autori**: Bellamia Antonio, Bruno Giuseppe e Sardone Vincenzo (**I.I.S. G.B. Pentasuglia di Matera**).
