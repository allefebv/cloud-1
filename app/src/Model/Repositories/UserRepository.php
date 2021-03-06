<?php

namespace Camagru\Model\Repositories;

use Camagru\Model\Entities\User;

require("config/database.php");

class UserRepository extends BaseRepository {

	//table DB 'users' et entité 'User'
	public function getUsers() {
		return $this->getAll('user', User::class);
	}

	public function getUserByName($username) {
		return $this->getByKey('user', User::class, 'username', $username);
	}

	public function getUserById($id) {
		return $this->getByKey('user', User::class, 'id', $id);
	}

	public function getUserByEmail($email) {
		return $this->getByKey('user', User::class, 'email', $email);
	}

	public function getUserByActivationKey($activationKey) {
		return $this->getByKey('user', User::class, 'activationKey', $activationKey);
	}

	public function add(User $user) {
		$req = $this->getDb()->prepare('INSERT INTO user(username, `password`, email, activationKey) VALUES(:username, :password, :email, :activationKey)');
		$req->execute(array(
			'username'	=> $user->username(),
			'password'	=> $user->password(),
			'email' 	=> $user->email(),
			'activationKey'		=> $user->activationKey()));
	}

	public function delete(User $user) {
		return $this->deleteEntry('user', 'id', $user->id());
	}

	public function update(User $user, $updateFieldKey, $updateFieldValue) {
		if ($updateFieldKey === 'password') {
			return $this->updateEntry('user', $updateFieldKey, hash('whirlpool', $updateFieldValue), 'id', $user->id());
		}
		else {
			return $this->updateEntry('user', $updateFieldKey, $updateFieldValue, 'id', $user->id());
		}
	}
}

?>
