# Il Dottore Artificiale

L'applicazione realizza, in maniera semplificata, un sistema esperto in ambito medico basato su regole, con lo scopo di identificare le malattie di cui un paziente può essere affetto partendo dai sintomi che presenta.

L'identificazione dei sintomi avviene attraverso un 'dialogo' tra applicazione e paziente in linguaggio naturale.
Il 'dialogo' sarà caratterizzato da una serie di domande molto specifiche a cui il paziente dovrà rispondere con una breve frase.
Le domande devono essere poste con lo scopo di identificare la malattia di cui il paziente è affetto con il minor numero di domande necessario.
Le risposte ottenute dal paziente vengono analizzate  attraverso un ampio uso delle espressioni regolari, allo scopo di capire se il paziente è affetto o meno da quel sintomo.

## Interfaccia utente

L’utente comunica attraverso un dialogo con il Dottore Artificiale, che gli pone una serie di domande.
La visita terminerà quando si riesce ad escludere tutte le malattie ad eccezione di una.

È possibile rispondere alle domande all'interno dell'apposita textarea.
Al fine di garantire la corretta interpretazione della risposta è preferibile inserire periodi semplici privi di subordinate.

## Base di conoscenza

È dove il sistema esperto memorizza le regole deduttive.
Si tratta di un database relazionale nel quale sono inseriti tutti i dati necessari al corretto funzionamento del sistema esperto.

## Motore inferenziale

Rappresenta il centro nevralgico di tutto il sistema ed è realizzato in PHP.

I sistemi esperti in genere vengono realizzati in linguaggi specifici che integrano nel loro funzionamento di base associazioni logiche tra stringhe e una gestione delle regole ben più astratta di quanto offerto da PHP, tuttavia a livello esemplificativo le funzionalità di PHP sono più che sufficienti.

La pagina che si occupa di analizzare i messaggi immessi dall’utente è [analizza_msg.php](code/analizza_msg.php).
Questa può assumere tre diversi stati:

- inizio della visita, quando l’utente comunica il codice fiscale del paziente che si vuole visitare
- selezione della domanda da sottoporre all’utente
- riconoscimento dei sintomi in base all'analisi della risposta fornita dall'utente mediante l'uso di espressioni regolari

### Inizio della visita

Prende il messaggio ricevuto dall’utente e lo confronta con delle espressioni regolari generate dinamicamente a partire dai codici fiscali dei pazienti che l'utente ha in cura.
Verifica così se nella risposta è presente un codice fiscale valido.
Qualora fosse questo il caso, la visita viene intestata al paziente, altrimenti viene richiesto nuovamente l'inserimento del codice fiscale.

### Selezione della domanda

Quest'ultima fase si occupa di scegliere il sintomo logicamente migliore, cioè quello che sia nel caso in cui il paziente ne sia affetto, sia nel caso in cui non lo sia, permetta di eliminare il maggior numero di malattie.
Nel caso ottimo, questo permette di escludere il 50% delle malattie, permettendo di ottimizzare il numero di domande sottoposte all’utente.
Nel caso limite in cui tutte le risposte permettono di dimezzare il numero di malattie da considerare, il numero di domande a cui viene sottoposto il paziente è di log<sub>2</sub>(N° malattie).

Il processo viene svolto creando una tabella temporanea contenente i sintomi che può valer la pena valutare che viene rimossa al termine della visita.

Ad ogni sintomo vengono associati due numeri:

- `Affetto`: il numero di malattie (tra le restanti) identificate da quel sintomo se il paziente ne fosse affetto
- `Non Affetto`: il numero di malattie (tra le restanti) che vado ad escludere se il paziente non fosse affetto da quel sintomo

Il sintomo su cui effettuare la domanda sarà quello che rende minima la differenza tra il valore nella colonna `Affetto` e `Non affetto`. Questo permette la scelta dei sintomi logicamente migliori.

### Riconoscimento dei sintomi

Confronta il messaggio inserito dall'utente con le espressioni regolari legate al sintomo da cui è stata scelta la domanda, attraverso la funzione `preg_match`.
Assumo che solo un espressione regolare potrà soddisfare la condizione e pertanto appena si trova la prima occorrenza la ricerca si di un match si interrompe.
Viene di conseguenza aggiornato lo stato della visita, aggiungendo l'informazione appena carpita dalla risposta del paziente.
Questo permette di restringere le malattie possibili e memorizzare i sintomi riconosciuti durante una visita così da visualizzare nella pagina delle visite l’anamnesi del paziente.

Se a seguito della riduzione del dominio delle malattie di cui il paziente può essere affetto, ne rimane una sola, la visita termina e il risultato della stessa viene comunicato ad utente e paziente.
