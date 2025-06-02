# coachtech勤怠管理アプリ

## 概要

一般ユーザーと管理者ユーザー向けに勤怠管理機能を提供するアプリです。
一般ユーザーは打刻や勤怠の一覧表示、勤怠情報の修正申請などを行うことができます。
管理者ユーザーはスタッフの勤怠情報を一覧で確認したり、修正や修正申請の承認を行ったりすることが可能です。

![ホーム画面](https://github.com/user-attachments/assets/fe130003-3207-4d3f-ba32-d9a409e800c0)

## 機能一覧

**一般ユーザー**

- **会員登録・ログイン**
- **勤怠打刻機能**
- **勤怠一覧取得**
- **勤怠詳細取得**
- **修正申請一覧取得**
- **勤怠修正申請機能**
- **メール認証**

**管理者ユーザー**

- **ログイン**
- **日次勤怠一覧取得**
- **スタッフ一覧取得**
- **スタッフ別月次勤怠一覧取得**
- **勤怠詳細取得**
- **勤怠修正機能**
- **修正申請一覧取得**
- **修正申請詳細取得**
- **修正申請承認機能**

## 環境構築

**インストール**

1. プロジェクトのクローン

```
git clone git@github.com:oohashitakayuki/coachtech-attendance.git
```

2. プロジェクトディレクトリに移動

```
cd coachtech-attendance
```

3. docker-compose コマンドを実行

```
docker-compose up -d --build
```

**Laravel 環境構築**

1. docker-compose コマンドで PHP コンテナにログイン

```
docker-compose exec php bash
```

2. Composer パッケージのインストール

```
composer install
```

3. 「.env.example」ファイルをコピーして「.env」ファイルを作成

```
cp .env.example .env
```

4. 「.env」ファイルにおいて環境変数を設定

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

5. アプリケーションキーの作成

```
php artisan key:generate
```

6. マイグレーションおよびシーディングの実行

```
php artisan migrate:fresh --seed
```

## 使用技術(実行環境)

- PHP 7.4.9
- Laravel 8.83.29
- MySQL 8.0.26
- Docker 4.41.2

## ユーザー情報

**一般ユーザー**

- 名前 -> 西 伶奈
- メールアドレス -> reina.n@coachtech.com
- パスワード -> password

- 名前 -> 山田 太郎
- メールアドレス -> taro.y@coachtech.com
- パスワード -> password

- 名前 -> 増田 一世
- メールアドレス -> issei.m@coachtech.com
- パスワード -> password

- 名前 -> 山本 敬吉
- メールアドレス -> keikichi.y@coachtech.com
- パスワード -> password

- 名前 -> 秋田 朋美
- メールアドレス -> tomomi.a@coachtech.com
- パスワード -> password

- 名前 -> 中西 教夫
- メールアドレス -> norio.n@coachtech.com
- パスワード -> password

一般ユーザーログイン画面を表示して、  
ユーザー情報を入力してログインしてください。

- 一般ユーザーログイン画面：http://localhost/login

**管理者ユーザー**

- 名前 -> 管理 太郎
- メールアドレス -> admin@example.com
- パスワード -> password

管理者ユーザーログイン画面を表示して、  
ユーザー情報を入力してログインしてください。

- 管理者ユーザーログイン画面：http://localhost/admin/login

**メール認証**

Mailtrapを利用して認証を行います。

メールボックスの「Code Samples」から「Laravel 7.x and 8.x」を選択し、
表示されたコードを「.env」ファイルに以下のようにコピー＆ペーストしてください。

```
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=(Mailtrapで確認したユーザー名)
MAIL_PASSWORD=(Mailtrapで確認したパスワード)
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@example.com(任意のメールアドレス)
MAIL_FROM_NAME="${APP_NAME}"
```

キャッシュをクリアします。

```
php artisan config:clear
```

Mailtrapのアカウントをお持ちでない場合は、以下のリンクから会員登録をしてください。
https://mailtrap.io/

## ER図

![alt](https://github.com/user-attachments/assets/6159ceaa-91ea-4533-beca-fe15cb9b2402)

## URL

- 開発環境：http://localhost/
- phpMyAdmin：http://localhost:8080/