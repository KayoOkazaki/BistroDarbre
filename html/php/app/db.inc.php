<?php

//DB接続情報
const HOST = "localhost";
const DBNAME = "darbre";
const DBUSER = "sysuser";
const DBPASS = "secret";

//***************************************
//  データベースを接続する関数
//  引数：なし
//  戻値：DBオブジェクト（$pdo as object）
//***************************************
function db_init() {
		try {
		// MySQL への接続
			$pdo = new PDO("mysql:host=". HOST. "; dbname=" . DBNAME, DBUSER, DBPASS);

		// エラーモードを例外モードに設定
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// 文字コードの設定
		$pdo->exec("SET NAMES utf8");

		return $pdo;

		}
		catch (PDOException $e) {

			echo $e->getMessage();
			$pdo = null;
		}
}