<?php 


/**
 * 
 */
class DB
{
	private $connection;
	protected $query;

	function __construct(Array $config)
	{
		try{
			$this->connection = new PDO('mysql:host='.$config["host"].';dbname='.$config["database"], $config['user'], $config['password']);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	function query($q){
		$stmt = $this->connection->query($q);
		$stmt->setFetchMode(PDO::FETCH_ASSOC); 
		$result = $stmt->fetchAll();

		return $result;
	}

	function save(String $query,Array $values){
		try{
			$stmt = $this->connection->prepare($query);
			$stmt->execute($values);

			return $this->connection->lastInsertId();
			
		}catch(PDOException $e){
			echo 'Error: '.$e->getMessage();
		}
	}
}

?>