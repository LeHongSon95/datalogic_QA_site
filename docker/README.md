# ローカル構築手順書

- ホストPCにdockerがインストールされている前提とする
- Windows環境の場合はWSL2環境を前提とする

## gitからコードの取得
```
$ git clone -b develop https://github.com/bangsep/datalogic_qa_site.git
$ cd datalogic_qa_site
```

## .envのコピー
```
$ cp .env.local .env
```

## 初回コンテナ起動
```
$ make build
$ make up
```

## 初期構築 
```
$ make composer-install
$ make npm-install
$ make npm-run
$ make migrate-up
```

## 動作確認

https://localhost:8090/

# リモートデバッグ設定手順

## PhpStormの場合

PhpStormメニュー＞ファイル＞設定＞PHP＞サーバー＞＋
- 名前：localhost:8090
- ホスト：localhost:8090
- ポート：443
- パスマッピングを使用：ON
- プロジェクトファイルのサーバー上の絶対パス：/var/www/app/

PhpStormメニュー＞ファイル＞設定＞PHP＞デバッグ＞Xdebug
- デバッグポート：9090

PhpStormメニュー＞実行＞実行構成の編集＞＋＞PHPリモートデバッグ
- 名前：リモートデバッグ
- IDEキーでデバッグ接続をフィルタ：ON
- サーバー：localhost:8090
- IDEキー：dlqa-app

