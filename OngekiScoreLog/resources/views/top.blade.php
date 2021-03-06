@extends('layouts.app')

@section('title', 'オンゲキ非公式スコアツール')
@section('sidemark_top', "is-active")
@section('hero_subtitle', "")
@section('hero_title', "OngekiScoreLog")

@section('content')
    <div class="box">
        <h3 class="title is-3">このサイトについて</h3>
        <p>
            OngekiScoreLogはSEGAのアーケード音楽ゲーム「オンゲキ」のスコアを集計し、見やすくソートしたりできる非公式ツールです。<br>
            他のユーザーにスコアを共有することが出来ます。<br>
            このサイトはファンサイトであり、SEGA様及び関係各社には一切関係ございません。
        </p>
        <p>
            すべての機能を無料で使用することが出来ますが、オンゲキNETの課金状況によって使用できる機能に制限が掛かります。<br>
            無料版では殆どのデータが得られません。<br>
            特に譜面ごとのレート値などはプレミアムプランでないと表示できません。ご注意ください。<br>
            オンゲキNETの登録と利用券の購入を済ませてください。
        </p>
        <p>
            サンプル: <a href="/user/1">作者のスコアページ</a>
        </p>
    </div>
    <div class="box">
        <h3 class="title is-3">簡単な使い方</h3>
        <p>
            <a href="/eula">利用規約</a>を一読した上でご利用ください。
        </p>
        <p>
            スコアの取得はブックマークレットを使用します。<br>
            詳細な使い方は<a href="/howto">こちら</a>をご覧ください。
            <ol>
                <li>サイトに登録します。</li>
                <li>オンゲキNETにログインします。</li>
                <li>ブックマークレットを実行します。</li>
                <li>成功すればサイトにスコアが登録されます。</li>
            </ol>
            オンゲキNETのメンテナンス中はデータ取得できません。<br>
            (定期メンテナンスは毎日4:00～7:00)
        </p>
    </div>

    <div class="box">
        <h3 class="title is-3">バグ報告・御意見・機能提案</h3>
        <p>
            バグ報告などは以下でお受けしております。<br>
            こんな機能が欲しい、こういう機能があったらいいのに、なども大歓迎です。<br>
            全てに返信は致しかねますので予めご了承ください。<br>
            Twitterへの御意見はGitHub issueへ引用させていただくこともあります。
        </p>
        <p>
            <a href="https://github.com/Slime-hatena/ProjectPrimera/issues" target="_blank">GitHub issue</a><br>
            <a href="https://twitter.com/ongeki_score" target="_blank">Twitter@ongeki_score</a>
        </p>
        <p>
            <h3 class="title is-3">既知の不具合・追加予定の機能</h3>
            以下にまとまっています。<br>
            既知の重大な不具合: <a href="https://github.com/Slime-hatena/ProjectPrimera/labels/bug" target="_blank">GitHub issue - bug</a><br>
            既知の軽微な不具合: <a href="https://github.com/Slime-hatena/ProjectPrimera/labels/wontfix" target="_blank">GitHub issue - wontfix</a><br>
            追加予定の機能: <a href="https://github.com/Slime-hatena/ProjectPrimera/labels/enhancement" target="_blank">GitHub issue - enhancement</a>
        </p>
    </div>
@endsection