<?php
//***************************************
//  西暦を和暦に変換する関数
//  引数：西暦年（$seireki as integer）
//  戻値：和暦年（$wareki as string）
//***************************************
function getWareki($seireki)
{
	if (is_numeric($seireki) && $seireki>= 1868) {

		if ($seireki >= 1868 && $seireki < 1912) {

 		  $wareki = ($seireki - 1867 == 1) ?  "明治元年" : "明治" . ($seireki - 1867) . "年";


		} elseif ($seireki >= 1912 && $seireki < 1926) {

		  $wareki = ($seireki - 1911 == 1) ?  "大正元年" : "大正" . ($seireki - 1911) . "年";


		} elseif ($seireki >= 1926 && $seireki < 1989) {

			$wareki = ($seireki - 1925 == 1) ?  "昭和元年" : "昭和" . ($seireki - 1925) . "年";


		} elseif ($seireki >= 1989) {

			$wareki = ($seireki - 1988 == 1) ?  "平成元年" : "平成" . ($seireki - 1988) . "年";

		}

	//数値でない時、または1867年以前
	} else {
		$wareki = "未対応";
	}

	return $wareki;
}
//***************************************
//クロスサイトスクリプティング（XSS）対応
//  引数：文字列 （$string as integer）
//  戻値：XSS対応した文字列
//***************************************
function h($string)
{
	return htmlspecialchars($string, ENT_QUOTES);

}
//***************************************
//
// 秘密の ID を作るための関数
// 機能：セッション ID を作成し、
// hash で暗号化(sha256)する
//
//***************************************
function getToken() {
	// セッション ID を作成し、hash で暗号化(sha256)する
	return hash('sha256',session_id());
}
