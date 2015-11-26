<?php
class login {
	var $login_name,$login_pw,$is_logged,$checktimelimit,$session_string,$bug,$role;
    private $users = array(array('name'=>'Neo','pw'=>'lamer','role'=>1),array('name'=>'admin','pw'=>'lamer','role'=>3));
	private $roles = array('Neo'=>1,'admin'=>3);

	function login(){
		$this->checktimelimit=(15*60);
		$this->is_logged = $this->logged();
		$this->is_logged = $this->is_logged == 0 ? $this->first_login() : $this->is_logged;
	}

	private function first_login(){
		if (isset($_POST['login_name']) && strlen($_POST['login_name'])>1){
            $isFine = $this->testMatch($_POST['login_name'],$_POST['login_pw']);
            if($isFine){
				$this->load();
				return 1;
			}
		} 
		return 0;
	}

    private function testMatch($n,$p){
        for($i = 0; $i < count($this->users); $i++){
            if($this->users[$i]['name'] == $n && $p == $this->users[$i]['pw']){
                return true;
            }
        }
        return false;
    }

	private function load(){
		$this->login_name = $_POST['login_name'];
		$this->login_pw = $_POST['login_pw'];
		$this->session_string = md5($this->login_name.$this->login_pw);
		$_SESSION['login_name'] = $_POST['login_name'];
		$_SESSION['login_pw'] = $_POST['login_pw'];
		$_SESSION['login_role'] = $this->roles[$_POST['login_name']];
		$_SESSION['session_string'] = md5($this->login_name.$this->login_pw);
	}

	function logout(){
		$this->session_login_string=md5(uniqid(rand()));
		$this->login_name=md5(uniqid(rand()));
		$this->role=false;
		session_unset();
		session_destroy();
		$this->is_logged = $this->logged();
	}

	function logged() {
		if((isset($_SESSION['login_name']) && isset($_SESSION['login_pw'])) && ($this->testMatch($_SESSION['login_name'],$_SESSION['login_pw']))){
			if($_SESSION['session_string'] == md5($_SESSION['login_name'].$_SESSION['login_pw'])){
				$this->login_name = $_SESSION['login_name'];
				$this->login_pw = $_SESSION['login_pw'];
				$this->role = $_SESSION['login_role'];
				$this->session_string = $_SESSION['session_string'];
				return (1);
			}
		}
		return (0);
	}
}
?>