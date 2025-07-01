## 環境構築

1. `git clone git@github.com:hiroakiokamura/freemarket-test1.git`
2. DockerDesktop を起動し`docker-compose up -d --build`
3. `cd src`
4. `docker-compose exec php composer install`
5. `.env.exampleファイルの名前を.envに変更`
6. `php artisan key:generate`
7. `docker-compose exec php php artisan migrate`
8. `docker-compose exec php php artisan db:seed`
9. `npm install`
10. `npm run dev`

書き込み権限のエラーが発生する場合は
` sudo chown -R ユーザー名:ユーザー名 .``sudo chmod -R 777 * `

## 使用技術(実行環境)

- **フレームワーク**: Laravel 10.48.21
- **プログラミング言語**: PHP 8.3.12
- **データベース**: MySQL 8.0.26

## ER 図

[ER 図](src/storage/images/ER.png)

## URL

- 開発環境：http://localhost/
- phpMyAdmin:：http://localhost:8080/

[def]: erd.png
