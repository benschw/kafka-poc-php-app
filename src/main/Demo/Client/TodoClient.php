<?php

namespace Demo\Client;

use GuzzleHttp\Client;
use Demo\Api\Todo;

class TodoClient {
	private $client;

	public function __construct(Client $client) {
		$this->client = $client;
	}

	public function getAll() {
		$resp = $this->client->get("/todo");

		$body = json_decode($resp->getBody(), true);
		return Todo::unmarshalCollection($body);
	}

	public function add(Todo $todo) {
		$resp = $this->client->post("/todo", ['json' => $todo->marshal()]);
		
		$body = json_decode($resp->getBody(), true);
		return Todo::unmarshal($body);
	}

	public function get($id) {
		$resp = $this->client->get("/todo/".$id);

		$body = json_decode($resp->getBody(), true);
		return Todo::unmarshal($body);
	}

	public function delete($id) {
		$resp = $this->client->delete("/todo/".$id);
	}
	
}