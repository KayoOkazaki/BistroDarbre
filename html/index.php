<?php
require_once "php/app/util.inc.php";
require_once "php/app/db.inc.php";

try {

	//DB接続
	$pdo = db_init();

	//newsテーブル取得
	$sql = "SELECT * FROM news ORDER BY posted DESC";
	$stmt = $pdo->query($sql);
	$allnews = $stmt->fetchAll(PDO::FETCH_ASSOC);

}
catch (PDOException $e) {

	echo $e->getMessage();
	$pdo = null;
	exit;
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Bistro D'arbreは恵比寿にある温かい雰囲気で気軽に楽しめるフランス料理店。本格コースメニューからワインと相性抜群のアラカルトメニューを多数ご用意しております。">
    <meta name="keywords" content="Bistro D’arbre・ダルブル,恵比寿,フレンチレストラン,ビストロ・ワイン・ソムリエ,南仏料理,カスレ,老舗,ワインバー,パーティ,個室,一軒家,ヘルシー,ベジタリアン,恵比寿駅西口,駅近,テラス席,ペット可">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>トップページ | D'arbre</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/responsive.css" media="screen and (max-width: 480px)">
    <link href="css/bxslider/jquery.bxslider.css" rel="stylesheet" />
    <link rel="icon" type="image/ico" href="images/favicon.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="js/bxslider/jquery.bxslider.min.js"></script>
    <script>
        $(function(){
            $('.bxslider').bxSlider({
                auto: true,
                mode: "fade",
                speed: 3000,
                randomStart: true,
                pause:10000
            });
        });
    </script>
</head>
<body id="index">
    <header id="top">
        <div class="logo"><a href="index.html"><img src="images/logo_small.png" alt="bistrodarbre"></a></div>
        <!-- スマホの時だけ -->
        <!-- <div class="tel"><a href="tel:0312345678">03-1234-5678</a></div> -->
        <div class="resavation">
            <div class="tel">☎ 03-3760-0447</div>
            <div class="mail"><a href="reservation.html" target="_blank"><img src="images/mail.gif" alt="web予約フォームはこちら"></a></div>
        </div>
        <nav>
            <!-- <ul class="navbar"> -->
            <ul>
                <li id="nav_top"><a href="index.html#top">Top</a></li>
                <li id="nav_menu"><a href="menu.html#top">Menu</a></li>
                <li id="nav_polisy"><a href="polisy.html#top">Polisy</a></li>
                <li id="nav_info"><a href="info.html#top">About D'arbre</a></li>
            </ul>
        </nav>
        <div id="mainimg">
            <ul class="bxslider">
                <li><img src="images/mainimg/mainimg01.gif" /></li>
                <li><img src="images/mainimg/mainimg02.gif" /></li>
                <li><img src="images/mainimg/mainimg03.gif" /></li>
                <li><img src="images/mainimg/mainimg04.gif" /></li>
                <li><img src="images/mainimg/mainimg05.gif" /></li>
                <li><img src="images/mainimg/mainimg06.gif" /></li>
                <li><img src="images/mainimg/mainimg07.gif" /></li>
                <li><img src="images/mainimg/mainimg08.gif" /></li>
                <li><img src="images/mainimg/mainimg09.gif" /></li>
                <li><img src="images/mainimg/mainimg10.gif" /></li>
                <li><img src="images/mainimg/mainimg11.gif" /></li>
                <li><img src="images/mainimg/mainimg12.gif" /></li>
                <li><img src="images/mainimg/mainimg13.gif" /></li>
                <li><img src="images/mainimg/mainimg14.gif" /></li>
                <li><img src="images/mainimg/mainimg15.gif" /></li>
                <li><img src="images/mainimg/mainimg16.gif" /></li>
                <li><img src="images/mainimg/mainimg17.gif" /></li>
                <li><img src="images/mainimg/mainimg18.gif" /></li>
                <li><img src="images/mainimg/mainimg19.gif" /></li>
                <li><img src="images/mainimg/mainimg20.gif" /></li>
                <li><img src="images/mainimg/mainimg21.gif" /></li>
            </ul>
        </div>
    </header>
    <div id="contents">
       <div id="contentsInner">
            <p id="illust01"><img src="images/flower_right.gif" width="280" alt="" /></p>
            <!-- <p id="illust02"><img src="images/leaf01.gif" width="120" alt="" /></p> -->
            <p id="illust03"><img src="images/flower03.gif" width="280" alt="" /></p>
            <div id="main">
                <section id="greeting">
                    <h1>ごあいさつ</h1>
                    <p>気軽に普段使いしていただけるように、温かい雰囲気づくりお客様には緊張感を持たせず、
                    ゲストを自宅に招くような感じで接客しています。フランス料理というと畏まってしまいがちですが、
                    「美味しい料理を食べに行くか」という感じで、気軽に普段使いしてください。</p>
                </section>
                <section id="news">
                    <h2>Darbre からのお知らせ</h2>
                      <div>
                        <ul>
						<?php foreach ($allnews as $news): ?>
					      <li><a href="<?php echo $news["link"]; ?>">
					          <time datetime="<?php echo $news["posted"]; ?>"><?php echo $news["posted"]; ?></time>
					          <?php echo $news["title"]; ?></a>
					      </li>
			 		    <?php endforeach;?>
                        </ul>
                    </div>
                </section>
                <section id="partyroom">
                    <h2>Party room 貸切パーティ</h2>
                        <div class="container">
                            <div class="strings1">
                                <h3>結婚式二次会・パーティーにおすすめ</h3>
                                <p>50名以上で店全体の貸切に、20名で利用する際は、2階のフロアを貸切で利用することができます。女子会や同窓会、誕生日などの各種パーティーに。ボリュームのあるコース料理も用意されています。</p>
                                <br>
                                <a href="menu.html#party">コースメニュー詳細こちら</a>
                            </div>
                            <div class="image1">
                                <img src="images/photo/party_room.jpg" alt="">
                            </div>
                        </div>
                </section>
                <section id="birthday">
                    <h2>For Birthday お誕生日の方へ</h2>
                        <div class="container">
                            <div class="strings2">
                                <h3>記念日・お誕生日の方には<br>バースデーケーキ！</h3>
                                <p>メニューにはございませんが、誕生日や記念日でご利用際に、事前に予約して頂ければメッセージ入りの『バースデー・アニバーサリープレート』をご用意いたします。サプライズ演出にも最適。</p>
                                <a href="reservation.html" target="_blank">ディナーの予約はこちら</a>
                            </div>
                            <div class="image2">
                                <img src="images/photo/birthday.jpg" alt="">
                            </div>
                        </div>
                </section>
                <section id="fb">
                    <h2>Facebook フェイスブック</h2>
                    <div>
                        <img src="images/facebook_img.png" alt="">
                    </div>
                </section>
            </div>
            <div id="sub">
                <aside>
                    <div class="bnr_area">
                        <a href="menu.html#lunch"><img src="images/banner1.gif" alt="ランチコース"></a>
                    </div>
                    <div class="bnr_area">
                        <a href="menu.html#dinner"><img src="images/banner2.gif" alt="ディナーコース"></a>
                    </div>
                    <div class="bnr_area">
                        <a href="menu.html#party"><img src="images/banner3.gif" alt="パーティプラン"></a>
                    </div>
                    <div class="bnr_area">
                        <a href="menu.html#winelist_glass"><img src="images/banner4.gif" alt="ワインリスト"></a>
                    </div>
                    <div class="bnr_area">
                        <a href="info.html#floorImg"><img src="images/banner5.gif" alt="空間"></a>
                    </div>
                    <div class="bnr_area">
                        <a href="info.html#access"><img src="images/banner6.gif" alt="当店への道案内"></a>
                    </div>
                </aside>
            </div>
        </div>
    </div>
    </body>
    <footer>
        <p id="pagetop"><a href="#top">ページの先頭へ戻る</a></p>
        <address>Bistro D'arbre 〒150-0022 東京都渋谷区恵比寿南1-4-8 電話 03-3760-0447  定休日:火曜日</address>
        <p id="copyright"><small>Copyright 2017 Bistro D'arbre All rights reserved.</small></p>
    </footer>
</html>