<?php

namespace MaiorADV\Qapla;

class Qapla
{
	protected $client;
	protected $container;
	protected $error;
	
	
	public function __construct($container)
	{
		try {
			
			$this->container = $container;				
			$this->client = new \GuzzleHttp\Client();
			
		} catch (\Exception $ex) {
			$this->error = $ex->getMessage();
		}	
	}
	
	
	/**
	 * deleteOrder permette di eliminare un ordine.
	 * @param $reference = riferimento alfanumerico dell'ordine
	 *
	 * @return bool
	 */
	public function deleteOrder($reference)
	{	
		try {	
			$res = $this->client->delete($this->container['url'].'deleteOrder/', ['json' =>  [
				'apiKey'    => $this->container['privateApiKey'],
				'reference' => $reference
				]
			]);
			
			$result = \GuzzleHttp\json_decode($res->getBody());
			if($result->deleteOrder->result == 'KO') { throw new \Exception($result->deleteOrder->error); }
			return true;
			
		} catch (\Exception $ex) {
			$this->error = $ex->getMessage();
			return false;
		}	
	}
	
	
	/**
	 * undeleteOrder permette di ripristinare un ordine eliminato.
	 * @param $reference = riferimento alfanumerico dell'ordine
	 *
	 * @return bool
	 */
	public function undeleteOrder($reference){
		
		try {
			$res = $this->client->patch($this->container['url'].'undeleteOrder/', ['json' =>  [			
				'apiKey'    => $this->container['privateApiKey'],
				'reference' => $reference				
				]
			]);
			
			$result = \GuzzleHttp\json_decode($res->getBody());
			if($result->undeleteOrder->result == 'KO') { throw new \Exception($result->undeleteOrder->error); }
			return true;
		} catch (\Exception $ex) {
			$this->error = $ex->getMessage();
			return false;
		}	
	}
	
	
	/**
	 * getOrder permette di recuperare un ordine.
	 * @param $reference = riferimento alfanumerico dell'ordine
	 *
	 * @return ordine
	 */
	public function getOrder($reference)
	{
		try{			
			$res = $this->client->get($this->container['url'].'getOrder/', ['query' =>  [
				'apiKey'    => $this->container['privateApiKey'],
				'reference' => $reference
				]
			]);
			$result = \GuzzleHttp\json_decode($res->getBody());
			
			if($result->getOrder->result == 'KO') { throw new \Exception($result->getOrder->error); }
			return $result->getOrder->orders;
			
		} catch (\Exception $ex) {
			$this->error = $ex->getMessage();
			return array();
		}
	}
	
	
	/**
	 * getOrders permette di recuperare un ordine.
	 * @param $date_or_days = data dell'ordine YYYY-MM-DD
	 * @param $type può avere i seguenti valori: updatedAt (data aggiornamento ordine), createdAt(data ordine), dateIns(data caricamento ordine)
	 *
	 * @return orders
	 */
	public function getOrders($date_or_days = null, $type = null)
	{
		try{
			$param_name = $type;
			$date_or_days ?: $date_or_days = $this->container['default_order_data'];
			if(strtotime($date_or_days) === false) $param_name = 'days';
			
			$res = $this->client->get($this->container['url'].'getOrders/', ['query' =>  [
				'apiKey'    => $this->container['privateApiKey'],
				$param_name => $date_or_days
				]
			]);
			$result = \GuzzleHttp\json_decode($res->getBody());
			
			if($result->getOrders->result == 'KO') { throw new \Exception($result->getOrders->error); }
			return $result->getOrders->orders;
			
		} catch (\Exception $ex) {
			$this->error = $ex->getMessage();
			return array();
		}
	}
	
	/**
	 * pushShipment permette di caricare una o più spedizioni tramite una POST dei dati in formato JSON.
	 * @param $data = elenco di spedizioni da inviare
	 *
	 * @return bool
	 */
	
	public function pushOrder(array $data)
	{		
		try {
			$json ='{
				"apiKey": "'.$this->container['privateApiKey'].'",
				"pushOrder": '.json_encode($data).'
			}';
			
			$res = $this->client->post($this->container['url'].'pushOrder/', [
				'headers' => ['content-type' => 'application/json'],
				'body' => $json
			]);
			$result = \GuzzleHttp\json_decode($res->getBody());
			
			if($result->pushOrder->result == 'KO') { throw new \Exception($result->pushOrder->error); }
			return true;
		} catch (\Exception $ex) {
			$this->error = $ex->getMessage();
			return false;
		}	
	}
	
	/**
	 * getCredits permette di ottenere i crediti rimanenti sul proprio account premium.
	 *
	 * @return numero crediti
	 */
	public function getCredits()
	{	
		try{
			$res = $this->client->get($this->container['url'].'getCredits/', ['query' =>  [
				'apiKey' => $this->container['privateApiKey']]
			]);
			$result = \GuzzleHttp\json_decode($res->getBody());
			if($result->getCredits->result == 'KO') { throw new \Exception($result->getCredits->error); }
			return $result->getCredits->credits;
			
		} catch (\Exception $ex) {
			$this->error = $ex->getMessage();
			return array();
		}
	}
	
	
	/**
	 * getChannel permette di ottenere informazioni sul canale collegato all'API Key e alla azienda che lo ha creato.
	 *
	 * @return channel
	 */
	public function getChannel()
	{	
		try{
			$res = $this->client->get($this->container['url'].'getChannel/', ['query' =>  [
				'apiKey' => $this->container['privateApiKey']]
			]);
			$result = \GuzzleHttp\json_decode($res->getBody());
			if($result->getChannel->result == 'KO') { throw new \Exception($result->getChannel->error); }
			return $result->getChannel;
			
		} catch (\Exception $ex) {
			$this->error = $ex->getMessage();
			return array();
		}
	}
	
	
	/**
	 * getQaplaStatus permette di ottenere l'elenco dettagliato degli stati spedizione Qapla'.
	 * @param $lang
	 * @param $id
	 *
	 * @return QaplaStatus
	 */
	public function getQaplaStatus($lang = 'it', $id = null)
	{	
		try{
			$option = array('apiKey' => $this->container['privateApiKey'], 'lang' => $lang );
			$res = $this->client->get($this->container['url'].'getQaplaStatus/', ['query' =>  [
				'apiKey' => $this->container['privateApiKey'],
				'lang' => $lang,
				'id' => $id ?: $id
				]
			]);
			$result = \GuzzleHttp\json_decode($res->getBody());
			if($result->getQaplaStatus->result == 'KO') { throw new \Exception($result->getQaplaStatus->error); }
			return $result->getQaplaStatus;
		} catch (\Exception $ex) {
			$this->error = $ex->getMessage();
			return array();
		}
	}
	
	
	/**
	 * getCouriers permette di richiedere l'elenco dei corrieri sia totale, sia per singola nazione /ragione.
	 *
	 * @return Couriers
	 */
	public function getCouriers()
	{	
		try {
			$res = $this->client->get($this->container['url'].'getCouriers/', ['query' =>  [
				'apiKey'    => $this->container['privateApiKey'],
				]
			]);
			$result = \GuzzleHttp\json_decode($res->getBody());
			if($result->getCouriers->result == 'KO') { throw new \Exception($result->getCouriers->error); }
			return $result->getCouriers;
			
		} catch (\Exception $ex) {
			$this->error = $ex->getMessage();
			return array();
		}
	}
	
	/**
	 * detectCourier cerca di determinare il corriere dal tracking number fornito, rispondendo con un elenco di corrieri.
	 *
	 * @return Couriers
	 */
	public function detectCourier($trackingNumber){
		try {
			 $res = $this->client->get($this->container['url'].'detectCourier/', ['query' =>  [
				'apiKey'    => $this->container['privateApiKey'],
				'trackingNumber' => $trackingNumber
				]
			]);
			$result = \GuzzleHttp\json_decode($res->getBody());
			if($result->detectCourier->result == 'KO') { throw new \Exception($result->detectCourier->error); }
			return $result->detectCourier->couriers;
		} catch (\Exception $ex) {
			$this->error = $ex->getMessage();
			return array();
		}	
	}
	
	
	/**
	 * pushShipment permette di caricare una o più spedizioni tramite una POST dei dati in formato JSON.
	 * @param $data = elenco di spedizioni da inviare
	 *
	 * @return bool
	 */
	
	public function pushShipment(array $data)
	{
		try {
			$json ='{
				"apiKey": "'.$this->container['privateApiKey'].'",
				"pushShipment": '.json_encode($data).'
			}';
			
			$res = $this->client->post($this->container['url'].'pushShipment/', [
				'headers' => ['content-type' => 'application/json'],
				'body' => $json
			]);
			$result = \GuzzleHttp\json_decode($res->getBody());
			
			if($result->pushShipment->result == 'KO') { throw new \Exception($result->pushShipment->error); }
			return true;
		} catch (\Exception $ex) {
			$this->error = $ex->getMessage();
			return false;
		}	
	}
	
	
	/**
	 * getShipment permette di leggere lo stato di una spedizione tramite il tracking number, il riferimento ordine o l'ID.
	 * @param $type = può contenere reference (il riferimento ordine), id (id della spedizione) o trackingNumber
	 * @param $lang = La lingua dei nomi degli stati Qapla' (it, en, es), default: it.
	 * @param $data = Il flag data specifica quali e quanti dati vogliamo ricevere, di default torna dei dati minimi sullo stato di avanzamento della spedizione
	 * Può avere i seguenti valori:
	 * - all: dati minimi + tutti i dati a seguire
	 * - consignee: dati minimi + i dati del destinatario
	 *  - children: dati minimi + i dati delle spedizioni figlio, se esistenti
	 *  - parent: dati minimi + i dati della spedizione padre, se esistente
	 *  - flag: dati minimi + i dati relativi alla segnalazione della spedizione, se esistenti
	 *  - notifications: dati minimi + i dati delle notifiche (email sms, webhook, qaplAPP)
	 *  - history: dati minimi + la tracking history della spedizione	   
	 * Il parametro può essere combinato separato da virgole, ad esempio data=consignee,history
	 *  Se non viene specificato nessun parametro verranno ritornati i dati minimi
	 *
	 * @return ordine
	 */
	public function getShipment($type = null, $lang = 'it', $data = null)
	{
		try{
			$options = array(
				'apiKey' => $this->container['privateApiKey'],
				'lang' => $lang
			);
			
			if( $type != null ){
				$options['type'] = $type;
			}
			if( $data != null ){
				$options['data'] = $data;
			}
			
			$res = $this->client->get($this->container['url'].'getShipment/', ['query' =>  $options
			]);
			$result = \GuzzleHttp\json_decode($res->getBody());
			
			if($result->getShipment->result == 'KO') { throw new \Exception($result->getShipment->error); }
			return $result->getShipment;
			
		} catch (\Exception $ex) {
			$this->error = $ex->getMessage();
			return array();
		}
	}
	
	
	/**
	 * getShipments permette di ricevere la lista delle spedizioni importate da Qapla' per data di inserimento, data di spedizione, data ordine.
	 * @param $date_or_days = data dell'ordine YYYY-MM-DD
	 * @param $type può avere i seguenti valori: shipDate (data spedizione), orderDate(data ordine), dateIns(data caricamento)
	 *
	 * @return Shipments
	 */
	public function getShipments($date_or_days = null, $type = null)
	{
		try{
			$param_name = $type;
			$date_or_days ?: $date_or_days = $this->container['default_order_data'];
			if(strtotime($date_or_days) === false) $param_name = 'days';
			
			$res = $this->client->get($this->container['url'].'getShipments/', ['query' =>  [
				'apiKey'    => $this->container['privateApiKey'],
				$param_name => $date_or_days
				]
			]);
			$result = \GuzzleHttp\json_decode($res->getBody());
			
			if($result->getShipments->result == 'KO') { throw new \Exception($result->getShipments->error); }
			return $result->getShipments->shipments;
		} catch (\Exception $ex) {
			$this->error = $ex->getMessage();
			return array();
		}
	}
	
	
	/**
	 * deleteShipment permette di eliminare una spedizione.
	 * @param $courier = il codice corriere della spedizione(verificare i codici dalla documentazione https://api.qapla.it/docs/#deleteShipment)
	 * @param $trackingNumber = il tracking number della spedizione
	 *
	 * @return bool
	 */
	public function deleteShipment($courier, $trackingNumber)
	{		
		try {
			$res = $this->client->delete($this->container['url'].'deleteShipment/', ['json' =>  [
				'apiKey'    => $this->container['privateApiKey'],
				'courier' => $courier,
				'trackingNumber' => $trackingNumber
				]
			]);
			
			$result = \GuzzleHttp\json_decode($res->getBody());		
			if($result->deleteShipment->result == 'KO') { throw new \Exception($result->deleteShipment->error); }
			return true;
		} catch (\Exception $ex) {
			$this->error = $ex->getMessage();
			return false;
		}	
	}
	
	
	/**
	 * updateShipment permette di aggiornare una spedizione.
	 * @param $courier = il codice corriere della spedizione(verificare i codici dalla documentazione https://api.qapla.it/docs/#deleteShipment)
	 * @param $trackingNumber = il tracking number della spedizione
	 *
	 * @return bool
	 */
	public function updateShipment($courier, $trackingNumber){
		try {
			$res = $this->client->put($this->container['url'].'updateShipment/', ['json' =>  [
				'apiKey'    => $this->container['privateApiKey'],
				'courier' => $courier,
				'trackingNumber' => $trackingNumber
				]
			]);
			
			$result = \GuzzleHttp\json_decode($res->getBody());		
			if($result->updateShipment->result == 'KO') { throw new \Exception($result->updateShipment->error); }
			return true;
		} catch (\Exception $ex) {
			$this->error = $ex->getMessage();
			return false;
		}
	}
	
	
	public function getError(){
		return $this->error;
	}
	
}

?>
