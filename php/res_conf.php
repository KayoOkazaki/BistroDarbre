<?php
require_once 'app/util.inc.php';
require_once 'libs/qd/qdmail.php';
require_once 'libs/qd/qdsmtp.php';
session_start();

// もしセッション変数が登録されていれば値を引き出す。
if (isset($_SESSION["contact"])) {

	$contact = $_SESSION["contact"];

	$name = $contact["name"];
	$kana = $contact["kana"];
	$mail = $contact["mail"];
	$mailcnf = $contact["mailcnf"];
	$telno = $contact["telno"];
	$ymd= $contact["ymd"];
	$hm= $contact["hm"];
	$number = $contact["number"];
	$message = $contact["message"];
	$token = $contact["token"];

	// IDが違う場合
	if ($token !== getToken()) {

		//入力フォーム画面に戻す
		header("Location:reservation_test.php");
		exit;
	}

	// セッション変数が取得できなかった時
} else {

	// 不正なアクセスとして入力画面に戻す
	header("Location:reservation_test.php");
	exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

	//送信ボタンクリック時
	if (isset($_POST["send"])) {

		// メール本文作成
		$body=<<<EOT
■お名前
{$name}

■フリガナ
{$kana}

■メールアドレス
{$mail}

■電話番号
{$telno}

■予約希望日
{$ymd}

■予約希望時間
{$hm}

■予約人数
{$number}

■問い合わせ内容
{$message}

EOT;


		// SMTPの設定
		$param = array(
			"host" => "w1.sim.zdrv.com",
			"port" => 25,
			"from" => "zd2B03@sim.zdrv.com",
			"protocol" => "SMTP"

		);

		// メールの送信
		$mail = new Qdmail();

		//エラーを非表示
		$mail->errorDisplay(FALSE);
		$mail->smtpObject()->error_display = FALSE;

		//送信内容
		$mail->from("zd2B03@sim.zdrv.com", "Bistro D'arbre Web");//サーバー上のメールアドレス
		$mail->to("zd2B03@sim.zdrv.com","BistroD'arbre 管理者（岡崎カヨ）");
		$mail->subject("BistroD'arbre 予約フォームからの送信");
		$mail->text($body);
		$mail->smtp(TRUE);
		$mail->smtpServer($param);

		//送信
		$flag = $mail->send();
		$ErrInfo = "メール送信エラー：".$flag;

		//もし送信に成功したならば
		if ($flag === TRUE){

			// セッション変数を破棄
			unset($_SESSION["contact"]);

			// 完了画面へ遷移
			header("Location:res_done.php");
			exit;

		} else {

			// エラー画面へ遷移
			header("Location:res_error.php");
			exit;
		}

	}

	//修正ボタンクリック時
	if (isset($_POST["back"])) {

		// 入力画面へ遷移
		header("Location:reservation.php");
		exit;
	}
}

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
    <meta name="description" content="Bistro D'arbreは恵比寿にある温かい雰囲気で気軽に楽しめるフランス料理店。本格コースメニューからワインと相性抜群のアラカルトメニューを多数ご用意しております。">
        <meta name="keywords" content="Bistro D’arbre・ダルブル,恵比寿,ランチ,ランチコース,ディナーコース,パーティプラン,ソムリエ,ワイン,ビストロ,南仏料理,カスレ,飲み放題コース,ベジタリアンメニュー,健康・美容メニュー,焼酎あり,カクテルあり,こだわりのワイン,ワインリスト">
        <meta name="format-detection" content="telephone=no">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Web予約フォーム | D'arbre</title>
        <link rel="stylesheet" href="../html/css/style.css">
        <link rel="stylesheet" href="../html/css/reservation.css">
        <link rel="stylesheet" href="../html/css/responsive.css" media="screen and (max-width: 480px)">
        <link rel="icon" type="image/ico" href="../html/images/favicon.png">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    </head>
    <body>
        <header id="top">
            <div class="logo"><a href="../html/index.html"><img src="../html/images/logo_small.png" alt="bistrodarbre"></a></div>
            <div class="addr">
                <p>Bistro D'arbre (ビストロ・ダルブル) 恵比寿店<br>
                ☎ 03-3760-0447<br>
                〒150-0022 東京都渋谷区恵比寿南1-4-8　定休日:火曜日</p>
            </div>
        </header>
        <div id="contents">
           <!-- 戻るボタン -->
           <div id="page-top">
                <a id="move-page-top" href="#top"><i class="fa fa-chevron-circle-up fa-5x"></i></a>
           </div>
            <div id="main">
                <article id="intro">
                    <h1>ご予約内容</h1>
                    <h2>ご予約内容をご確認ください</h2>
                    <p>以下内容をご確認頂けましたら「送信ボタン」をクリックしてください。<br>
                       入力内容を修正する場合は、「修正ボタン」をクリックしてください。
                    </p>
                </article>
			    <!-- フォーム画面 -->
                <form action="" method="post">
                    <table>
                        <tr><th>お名前：</th>
                            <td><?php echo $name; ?></td></tr>
                        <tr><th>フリガナ：</th>
                            <td><?php echo $kana; ?></td></tr>
                        <tr><th>電話番号：</th>
                            <td><?php echo $telno; ?></td></tr>
                        <tr><th>E-mail：</th>
                            <td><?php echo $mail; ?></td></tr>
                        <tr><th>ご希望日 :</th>
                            <td><?php echo $ymd; ?></td></tr>
                        <tr><th>ご希望時間 :</th>
                            <td><?php echo $hm; ?></td></tr>
                        <tr><th>人数 :</th>
                            <td><?php echo $number; ?></td></tr>
                        <tr><th>その他・備考:</th>
                            <td><?php echo nl2br($message); ?></td></tr>
                        <tr><td colspan="2">
                            <input type="submit" name="send" value="送信する">
                            <input type="submit" name="back" value="修正する">
                        </td></tr>
                    </table>
                </form>
            </div>
        </div>
    </body>
    <footer>
        <p id="pagetop"><a href="#top">ページの先頭へ戻る</a></p>
        <address>Bistro D'arbre 〒150-0022 東京都渋谷区恵比寿南1-4-8 電話 03-3760-0447  定休日:火曜日</address>
        <p id="copyright"><small>Copyright 2017 Bistro D'arbre All rights reserved.</small></p>
    </footer>
</html>