<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class User extends Model
{

	const SESSION = "User";

	public static function login($login, $password)
	{

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array( 

			':LOGIN' => $login,

		));

		if(count($results) === 0)
		{
			throw new \Exception("Usúario inexistente ou senha inválida!");
		}

		// dados do usuario
		$data = $results[0];

		if (password_verify($password, $data["despassword"]) === true)
		{

			$user = new User();

			$user->setData($data);

			$_SESSION[User::SESSION] = $user->getValues();

			return $user;
	
		} else {

			throw new \Exception("Usúario inexistente ou senha inválida!");

		}

	}
	// metodo para verivicar se o usuario esta logado
	public static function verifyLogin($inadmin = true)
	{
		// se a sessão não estiver definida ou se estiver vazia ou se id do usuario é maior que 0 ou se é um usuario administrador
		if (
			!isset($_SESSION[User::SESSION]) 
			|| !$_SESSION[User::SESSION] 
			|| !(int)$_SESSION[User::SESSION]["iduser"] > 0
			|| (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin
		) {
			
			header("Location: /admin/login");
			exit;
		}
	}

	public static function logout()
	{
		$_SESSION[User::SESSION] = NULL;
	}	
	
}

?>