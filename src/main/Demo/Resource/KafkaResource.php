<?php
namespace Demo\Resource;


use Fliglio\Web\PathParam;
use Fliglio\Web\GetParam;
use Fliglio\Web\Entity;

use Fliglio\Http\ResponseWriter;
use Fliglio\Http\Http;
use Fliglio\Http\Exceptions\NotFoundException;

use Demo\Api\Todo;
use Demo\Db\TodoDbm;

use Demo\Weather\Api\Weather;
use Demo\Weather\Client\WeatherClient;

class KafkaResource {

	private $db;
	private $k;

	private $schema = [
		'name' => 'member',
		'type' => 'record',
		'fields' => [[
			'name' => 'message',
			'type' => 'string'
		]]
	];


	public function __construct(TodoDbm $db, \Kafka\Produce $k) {
		$this->db = $db;
		$this->k = $k;
	}
	
	// GET /kafka/:msg
	public function produce(PathParam $msg = null) {
		$this->k->setRequireAck(-1);
		$this->k->setMessages('test', 0, array($msg->get()));
		$result = $this->k->send();
		
		
		$record = array('message' => $msg->get());

		$io = new \AvroStringIO();
		$writersSchema = \AvroSchema::parse(json_encode($this->schema));
		$writer = new \AvroIODatumWriter($writersSchema);
		$dataWriter = new \AvroDataIOWriter($io, $writer, $writersSchema);
		$dataWriter->append($record);
		$dataWriter->close();

		$this->k->setRequireAck(-1);
		$this->k->setMessages('avro-test', 0, array($io->string()));
		$result2 = $this->k->send();


		return [$result, $result2];
	}


}
