<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/autoloader.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/model/Comment.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/model/Image.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/view/View.php');

class ControllerAccueil {

	private $_imageManager;
	private $_userManager;
	private $_user;
	private $_view;
	private $_json;

	public function __construct($url) {
		if (!empty($url) && count($url) > 1)
			throw new Exception('Page Introuvable');
		else if ($this->_json = file_get_contents('php://input'))
			$this->likeComment();
		$this->gallery();
	}

	private function likeComment() {
		$this->_json = json_decode($this->_json, TRUE);
		$this->_userManager = new UserManager;
		$this->_user = ($this->_userManager->getUserByName($_SESSION['logged']))[0];
		if (isset($this->_json['imageIdLike'])) {
			$this->_user->like($this->_json['imageIdLike']);
		}
		else if (isset($this->_json['imageIdComment'])) {
			$this->_user->comment($this->_json['imageIdComment']);
		}
	}

	private function gallery() {
		$this->_imageManager = new ImageManager;
		$images = $this->_imageManager->getImages();
		$this->_view = new View('Accueil');
		$this->_view->generate(array('images' => $images));
	}

	//Page d'accueil = header (connexion etc) + gallerie + footer

}

?>
