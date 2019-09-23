# Qapla
Integrazione dell'API Qapla per la tua applicazione web.

![MaiorADVQapla](img/logo_maioradv.png)


## Installazione via Composer
```sh
composer require maioradv/qapla
```

## Configurazione
```php
use MaiorADV\Qapla\Qapla;

$config = array(
	'url' => 'https://api.qapla.it/1.2/',
	'privateApiKey' => 'inserire chiave privata'
);

$qapla = new Qapla($config);
```

## Dopo l'inizializzazione possono essere utilizzati i seguenti metodi

### pushShipment()
Permette di caricare una o più spedizioni<br>
L'array data deve seguire le specifiche reperibili qui: https://api.qapla.it/docs/#pushShipment
```php
$data = array(...);
$result = $qapla->pushShipment($data);
```
### getShipment()
Permette di leggere lo stato di una spedizione tramite il tracking number, il riferimento ordine o l'ID.
Per i riferimenti del metodo, segui le specifiche reperibili qui: https://api.qapla.it/docs/#getShipment

```php
$result = $qapla->getShipment('reference', 'it', 'consignee,history');
```

### getShipments()
Permette di ricevere la lista delle spedizioni importate da Qapla' per data di inserimento, data di spedizione, data ordine.
Per i riferimenti del metodo, segui le specifiche reperibili qui: https://api.qapla.it/docs/#getShipments
```php
$result = $qapla->getShipments('2019-09-01', 'shipDate');
```

### deleteShipment()
Permette di eliminare una spedizione
Per i riferimenti del metodo, segui le specifiche reperibili qui: https://api.qapla.it/docs/#deleteShipment
```php
$result = $qapla->deleteShipment('BRT', '039000000000282');
```

### updateShipment()
Permette di aggiornare una spedizione
Per i riferimenti del metodo, segui le specifiche reperibili qui: https://api.qapla.it/docs/#updateShipment
```php
$result = $qapla->updateShipment('BRT', '039000000000282');
```

### pushOrder()
Permette di caricare uno o più ordini tramite un array
Per i riferimenti del metodo, segui le specifiche reperibili qui: https://api.qapla.it/docs/#pushOrder
```php
$data = array(...);
$result = $qapla->pushOrder($data);
```

### getOrder()
Permette di recuperare un ordine
Per i riferimenti del metodo, segui le specifiche reperibili qui: https://api.qapla.it/docs/#getOrder
```php
$result = $qapla->getOrder('123456');
```

### getOrders()
Permette di ricevere la lista degli ordini importati da Qapla'.
Per i riferimenti del metodo, segui le specifiche reperibili qui: https://api.qapla.it/docs/#getOrders
```php
$result = $qapla->getOrders('2019-09-01', 'dataIns');
```

### deleteOrder()
Permette di eliminare un ordine.
Per i riferimenti del metodo, segui le specifiche reperibili qui: https://api.qapla.it/docs/#deleteOrder
```php
$result = $qapla->deleteOrder('123456');
```

### undeleteOrder()
Permette di ripristinare un ordine eliminato.
Per i riferimenti del metodo, segui le specifiche reperibili qui: https://api.qapla.it/docs/#undeleteOrder
```php
$result = $qapla->undeleteOrder('123456');
```

### getCouriers()
Permette di richiedere l'elenco dei corrieri sia totale, sia per singola nazione /ragione.
Per i riferimenti del metodo, segui le specifiche reperibili qui: https://api.qapla.it/docs/#getCouriers
```php
$result = $qapla->getCouriers();
```

### detectCourier()
Cerca di determinare il corriere dal tracking number fornito, rispondendo con un elenco di corrieri.
Per i riferimenti del metodo, segui le specifiche reperibili qui: https://api.qapla.it/docs/#detectCourier
```php
$result = $qapla->detectCourier('039000000000282');
```

### getChannel()
Permette di ottenere informazioni sul canale collegato all'API Key e alla azienda che lo ha creato.
Per i riferimenti del metodo, segui le specifiche reperibili qui: https://api.qapla.it/docs/#getChannel
```php
$result = $qapla->getChannel();
```

### getCredits()
Permette di ottenere i crediti rimanenti sul proprio account premium.
Per i riferimenti del metodo, segui le specifiche reperibili qui: https://api.qapla.it/docs/#getCredits
```php
$result = $qapla->getCredits();
```

### getQaplaStatus()
Permette di ottenere l'elenco dettagliato degli stati spedizione Qapla'.
Per i riferimenti del metodo, segui le specifiche reperibili qui: https://api.qapla.it/docs/#getQaplaStatus
```php
$result = $qapla->getQaplaStatus('it', 3);
```
