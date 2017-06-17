<?php
require_once 'app/util.inc.php';

// クリックジャッキング対策
header("X-FRAME-OPTIONS: SAMEORIGIN");

// セッション開始
session_start();

// 変数初期化
$errMsg = array();
$name = "";
$kana = "";
$telno = "";
$mail = "";
$mailcnf = "";
$ymd = "";
$hm = "";
$number = "";
$message = "";

//予約可能期間設定
$fmDate = new DateTime();
$fmDate->add(new DateInterval('P1D'));
$toDate = new DateTime();
$toDate->add(new DateInterval('P1M'));


// 確認画面で修正ボタンがクリックされた時
if (isset($_SESSION["contact"])) {

	$contact = $_SESSION["contact"];

	$name = $contact["name"];
	$kana = $contact["kana"];
	$mail = $contact["mail"];
	$mailcnf = $contact["mailcnf"];
	$telno = $contact["telno"];
	$ymd = $contact["ymd"];
	$hm = $contact["hm"];
	$number = $contact["number"];
	$message= $contact["message"];

}
// ポスト送信の時
if ($_SERVER["REQUEST_METHOD"] === "POST") {

	// 入力値を取得
	$name = $_POST["name"];
	$kana = $_POST["kana"];
	$telno = $_POST["telno"];
	$mail = $_POST["mail"];
	$mailcnf = $_POST["mailcnf"];
	$ymd = $_POST["ymd"];
	$hm = $_POST["hm"];
	$number = $_POST["number"];
	$message = $_POST["message"];
	$token= $_POST["token"];

	// バリデーションチェック
	if (trim($name=="")) {
		$errMsg["name"] = "名前を入力してください";
	}
	if (trim($kana=="")) {
		$errMsg[] = "フリガナを入力してください";
	} elseif (!preg_match("/^[ァ-ヶー 　]+$/u", $kana)) {
		$errMsg[] = "フリガナの形式が正しくありません";

	}
	if (trim($telno=="")) {
		$errMsg[] = "電話番号を入力してください";

	} elseif (!preg_match("/^0\d{9,10}$/", $telno)) {
		$errMsg[] = "電話番号の形式が正しくありません";

	}
	if (trim($mail=="")) {
		$errMsg[] = "メールアドレスを入力してください";

	} elseif (!preg_match("/^[^@]+@[^@]+\.[^@]+$/", $mail)) {
		$errMsg[] = "メールアドレスの形式が正しくありません";

	}
	if (trim($mailcnf=="")) {
		$errMsg[] = "確認用メールアドレスを入力してください";

	} elseif (trim($mailcnf) != trim($mail)) {
		$errMsg[] = "ご入力頂いたメールアドレスと一致しません";

	}

	if ($ymd=== "" || mb_ereg_match("^(\s|　)+$", $ymd)) {
		$errMsg[] = "ご希望の日付を入力してください";

	} else {

		$date = new DateTime($ymd);
		$ymd = $date->format("Y-m-d");

		if (!preg_match("{^\d{4}-\d{2}-\d{2}$}", $ymd)) {
			$errMsg[]= "日付は「例：2017-02-01」の形式で入力してください";
		} else {

			//曜日の割り出し
			$week = array("日", "月", "火", "水", "木", "金", "土");
			$w = (int)$date->format('w');

			// 文字列の分解
			list($yy, $mm, $dd) = explode('-', $ymd);

			//定休日チェック
			if ($week[$w] === "火" || (intval($mm.$dd) > 1230 || intval($mm.$dd) < 104)) {
				$errMsg[]= "当店営業日をご確認のうえ選択してください";
			}

			//予約受付期間チェック
			if ($yy.$mm.$dd < intval($fmDate->format('Ymd')) || $yy.$mm.$dd > intval($toDate->format('Ymd'))) {
				$errMsg[]= "ご予約は明日以降から1か月先までの期間で承ります";

			}
		}
	}

	// バリデーションチェックOKの時
	if (count($errMsg) == 0) {

		//入力値を一旦連想配列として保存
		$contact = array(
				"name" => $name,
				"kana" => $kana,
				"telno" => $telno,
				"mail" => $mail,
				"mailcnf" => $mailcnf,
				"ymd" => $ymd,
				"hm" => $hm,
				"number" => $number,
				"message" => $message,
				"token" => $token
		);

		// 連想配列（入力値）ごとセッション変数に保存
		$_SESSION["contact"] = $contact;

		// 確認画面へ遷移
		header("Location: res_conf.php");
		exit();
		$errMsg[]="確認画面へ遷移";
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
                    <h1>ご予約フォーム</h1>
                    <h2>ご予約にあたってのお願い</h2>
                    <p>以下内容をご確認頂けましたらお問合せ内容を入力し<br>
                        「確認ボタン」をクリックしてください。</p>
                    <ul>
                        <li>こちらの予約システムでは、席種のご指定はできません。あらかじめご了承ください。</li>
                        <li>6名様以上のご予約は、店舗に直接お問い合わせ下さい。</li>
                        <li>個室での利用を希望される場合は、個室料金としてお1人様500円(税込)を頂戴いたします。</li>
                        <li>個室利用は7名様以上からのご予約となりますのでご了承ください。</li>
                    </ul>
                </article>
                <?php
                    // エラーが在る時
                if(count($errMsg) >0) {
                    	// エラーメッセージを表示する
                    	foreach($errMsg as $value) {
                    		echo "<span style='color:red;'>" . $value .$token. "</span><br>" . "\n";
                    	}
                    }
                ?>
			    <!-- フォーム画面 -->
                <form action="" method="post" novalidate>
					<!-- 隠し項目：token -->
	            	<input type="hidden" name ="token" value="<?php echo getToken(); ?>"/>
                    <table>
                        <tr><th>お名前：</th>
                            <td><input type="text" name="name" placeholder="（必須）" value="<?php echo h($name); ?>"></td></tr>
                        <tr><th>フリガナ：</th>
                            <td><input type="text" name="kana" placeholder="（必須）" value="<?php echo h($kana); ?>"></td></tr>
                        <tr><th>電話番号：</th>
                            <td><input type="tel" name="telno" placeholder="（必須）" value="<?php echo h($telno); ?>"></td></tr>
                        <tr><th>E-mail：</th>
                            <td><input type="email" name="mail" placeholder="（必須）" value="<?php echo h($mail); ?>"></td></tr>
                        <tr><th>E-mail(確認用)：</th>
                            <td><input type="email" name="mailcnf" placeholder="（必須）" value="<?php echo h($mailcnf); ?>"></td></tr>
                        <tr><th>ご希望日 :</th>
                            <td><input type="date" name="ymd" autocomplete="on" min="<?php echo $fmDate->format('Y-m-d')?>"
                                 max="<?php echo $toDate->format('Y-m-d')?>" value="<?php echo h($ymd); ?>">
                              <br>定休日：火曜日　年末年始（12/31～1/3）
                              <br>当日のご予約はお電話にてお問合せください</td></tr>
                        <tr><th>ご希望時間 :</th>
                            <td>
                                <select name="hm">
                                    <option value="11:30" selected="selected">11:30</option>
                                    <option value="12:00" <?php if(trim(h($hm))=="12:00") echo " selected='selected'"; ?>>12:00</option>
                                    <option value="12:30" <?php if(trim(h($hm))=="12:30") echo " selected='selected'"; ?>>12:30</option>
                                    <option value="13:00" <?php if(trim(h($hm))=="13:00") echo " selected='selected'"; ?>>13:00</option>
                                    <option value="13:30" <?php if(trim(h($hm))=="13:30") echo " selected='selected'"; ?>>13:30</option>
                                    <option value="14:00" <?php if(trim(h($hm))=="14:00") echo " selected='selected'"; ?>>14:00</option>
                                    <option value="17:00" <?php if(trim(h($hm))=="17:00") echo " selected='selected'"; ?>>17:00</option>
                                    <option value="17:30" <?php if(trim(h($hm))=="17:30") echo " selected='selected'"; ?>>17:30</option>
                                    <option value="18:00" <?php if(trim(h($hm))=="18:00") echo " selected='selected'"; ?>>18:00</option>
                                    <option value="18:30" <?php if(trim(h($hm))=="18:30") echo " selected='selected'"; ?>>18:30</option>
                                    <option value="19:00" <?php if(trim(h($hm))=="19:00") echo " selected='selected'"; ?>>19:00</option>
                                    <option value="19:30" <?php if(trim(h($hm))=="19:30") echo " selected='selected'"; ?>>19:30</option>
                                    <option value="20:00" <?php if(trim(h($hm))=="20:00") echo " selected='selected'"; ?>>20:00</option>
                                </select>
                            </td></tr>
                        <tr><th>人数 :</th>
                            <td>
                                <select name="number">
                                    <option value="1名" <?php if(trim(h($number))=="1名") echo " selected='selected'"; ?>>1名</option>
                                    <option value="2名" <?php if(trim(h($number))=="2名") echo " selected='selected'"; ?>>2名</option>
                                    <option value="3名" <?php if(trim(h($number))=="3名") echo " selected='selected'"; ?>>3名</option>
                                    <option value="4名" <?php if(trim(h($number))=="4名") echo " selected='selected'"; ?>>4名</option>
                                    <option value="5名" <?php if(trim(h($number))=="5名") echo " selected='selected'"; ?>>5名</option>
                                </select>
                            </td></tr>
                        <tr><th>その他・備考:</th>
                            <td><textarea name="message" rows="10" cols="40" placeholder=""><?php echo h($message); ?></textarea></td></tr>
                        <tr><td colspan="2"><input type="submit" value="確認"></td></tr>
                    </table>
                </form>
            </div>
            <div id="sub">
                <aside>
                    <div class="bnr_area">
                        <a href="../html/menu.html#lunch" target="_blank"><img src="../html/images/banner1.gif" alt="ランチコース"></a>
                    </div>
                    <div class="bnr_area">
                        <a href="../html/menu.html#dinner" target="_blank"><img src="../html/images/banner2.gif" alt="ディナーコース"></a>
                    </div>
                    <div class="bnr_area">
                        <a href="../html/menu.html#party" target="_blank"><img src="../html/images/banner3.gif" alt="パーティプラン"></a>
                    </div>
                    <div class="bnr_area">
                        <a href="../html/menu.html#winelist_glass" target="_blank"><img src="../html/images/banner4.gif" alt="ワインリスト"></a>
                    </div>
                    <div class="bnr_area">
                        <a href="../html/info.html#floorImg" target="_blank"><img src="../html/images/banner5.gif" alt="空間"></a>
                    </div>
                    <div class="bnr_area">
                        <a href="../html/info.html#access" target="_blank"><img src="../html/images/banner6.gif" alt="当店への道案内"></a>
                    </div>
                </aside>
            </div>
        </div>
    </body>
    <footer>
        <p id="pagetop"><a href="#top">ページの先頭へ戻る</a></p>
        <address>Bistro D'arbre 〒150-0022 東京都渋谷区恵比寿南1-4-8 電話 03-3760-0447  定休日:火曜日</address>
        <p id="copyright"><small>Copyright 2017 Bistro D'arbre All rights reserved.</small></p>
    </footer>
</html>