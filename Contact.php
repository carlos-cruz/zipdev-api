<?php

/**
 * 
 */
class Contact
{
	private $db;
	public $firstName;
	public $surName;


	function __construct(DB $db)
	{
		$this->db = $db;
	}

	function create(){
		try{
			$filext = $this->mimetoext($_FILES['picture']['type']);

			//Save contact names
			$contact_id = $this->db->save("INSERT INTO phonebook (first_name, sur_name, picture) VALUES (:first_name,:sur_name,:picture);",[
				'first_name' => $_POST['first_name'],
				'sur_name' => $_POST['sur_name'],
				'picture' => $filext
			]);
			

			//Save contact emails
			foreach ($_POST['emails'] as $email) {
				$this->db->save("INSERT INTO emails (phonebook_id,email) VALUES (:phoneid,:email);",[
					'phoneid' => $contact_id,
					'email' => $email,
				]);
			}
			
			//Save contact phones
			foreach ($_POST['phones'] as $email) {
				$this->db->save("INSERT INTO phones (phonebook_id,phone) VALUES (:phoneid,:phone);",[
					'phoneid' => $contact_id,
					'phone' => $email,
				]);
			}

			//Save contact picture
			if ($_FILES) {
				if ($filext) {
					
					$target_file = 'images/'.$contact_id.$filext;
					
					$file = getimagesize($_FILES['picture']['tmp_name']);
					if ($file !== false) {
						move_uploaded_file($_FILES['picture']['tmp_name'],$target_file);
					}
				}
			}

			return json_encode(['message' => 'Contact stored..']);
		}catch(Exception $e){
			return $e->getMessage();
		}

	}

	function read($id = null){
		if ($id !== null) {
			$contacts = $this->db->query('SELECT * FROM phonebook WHERE id='.$id);
		}else{
			$contacts = $this->db->query('SELECT * FROM phonebook');
		}

		if ($contacts) {
			foreach ($contacts as $key => $contact) {
				$contacts[$key]['emails'] = [];
				$contacts[$key]['phones'] = [];

				$emails = $this->db->query('SELECT email FROM emails WHERE phonebook_id = '.$contact['id']);
				foreach ($emails as $key2 => $value) {
					 array_push($contacts[$key]['emails'], $value['email']);
				}

				$phones = $this->db->query('SELECT phone FROM phones WHERE phonebook_id = '.$contact['id']);
				foreach ($phones as $key2 => $value) {
					 array_push($contacts[$key]['phones'], $value['phone']);
				}

				if ($contact['picture']) {
					$contacts[$key]['picture'] = 'http://'.$_SERVER['HTTP_HOST'].'/contacts/images/'.$contact['picture'];
				}
			}


			$response = ($id !== null) ?  json_encode($contacts[0]):json_encode($contacts);

			return $response;
		}else{
			return json_encode($contacts);
		}

	}

	function update($id){
		try{

			//Save contact names
			$this->db->save("UPDATE phonebook SET first_name=:first_name, sur_name=:sur_name WHERE id=:id",[
				'first_name' => $_POST['first_name'],
				'sur_name' => $_POST['sur_name'],
				'id' => $id
			]);

			//Save contact emails
			$this->db->save("DELETE FROM emails WHERE phonebook_id=:id",[
				'id' => $id
			]);

			foreach ($_POST['emails'] as $email) {
				$this->db->save("INSERT INTO emails (phonebook_id,email) VALUES (:phoneid,:email);",[
					'phoneid' => $id,
					'email' => $email,
				]);
			}
			
			//Save contact phones
			$this->db->save("DELETE FROM phones WHERE phonebook_id=:id",[
				'id' => $id
			]);
			foreach ($_POST['phones'] as $email) {
				$this->db->save("INSERT INTO phones (phonebook_id,phone) VALUES (:phoneid,:phone);",[
					'phoneid' => $id,
					'phone' => $email,
				]);
			}

			//Save contact picture
			if ($_FILES) {
				if ($ext = $this->mimetoext($_FILES['picture']['type'])) {
					$target_file = 'images/'.$id.$ext;
					
					$file = getimagesize($_FILES['picture']['tmp_name']);
					if ($file !== false) {
						move_uploaded_file($_FILES['picture']['tmp_name'],$target_file);
					}
				}
			}

			return json_encode(['message' => 'Contact updated..']);

		}catch(Exception $e){
			echo 'Error: '.$e->getMessage();
		}

	}

	function delete($id){
		try{

			$contact = $this->db->query('SELECT * FROM phonebook WHERE id='.$id);
			$this->db->save("DELETE FROM phonebook WHERE id=:id",[
				'id' => $id
			]);
			
			if ($contact['picture'] != null) {
				if (file_exists('images/'.$id.$contact[0]['picture'])) {
					unlink('images/'.$id.$contact[0]['picture']);
				}
			}

			return json_encode(['message' => 'Contact deleted...']);
		}catch(Exception $e){
			echo 'Error: '.$e->getMessage();
		}
	}

	function mimetoext($mime){
		$ext = array(
			'image/jpeg' => '.jpg',
			'image/png' => '.png'
		);

		return $ext[$mime];
	}

	function search($term){
		$emails = $this->db->query('SELECT phonebook_id FROM emails WHERE email like "%'.$term.'%"');
		$phones = $this->db->query('SELECT phonebook_id FROM phones WHERE phone like "%'.$term.'%"');

		$a = array($emails,$phones);
		$ids = array_values(array_unique(array_column(array_reduce($a, 'array_merge',array()),'phonebook_id')));

		$results = $this->db->query('SELECT * FROM phonebook WHERE first_name like "%'.$term.'%" OR sur_name like "%'.$term.'%" OR id IN('.implode(',', $ids).')');

		if ($results) {
			foreach ($results as $key => $contact) {
				$results[$key]['emails'] = [];
				$results[$key]['phones'] = [];

				$emails = $this->db->query('SELECT email FROM emails WHERE phonebook_id = '.$contact['id']);
				foreach ($emails as $key2 => $value) {
					 array_push($results[$key]['emails'], $value['email']);
				}

				$phones = $this->db->query('SELECT phone FROM phones WHERE phonebook_id = '.$contact['id']);
				foreach ($phones as $key2 => $value) {
					 array_push($results[$key]['phones'], $value['phone']);
				}

				if ($contact['picture']) {
					$results[$key]['picture'] = 'http://'.$_SERVER['HTTP_HOST'].'/contacts/images/'.$contact['picture'];
				}
			}

		}

		return json_encode($results);
	}
}

?>