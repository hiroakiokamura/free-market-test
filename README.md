# フリーマーケットアプリケーション

## 環境構築

### Laravel環境構築
```bash
git clone git@github.com:hiroakiokamura/freemarket-test1.git
DockerDesktop を起動しdocker-compose up -d --build
cd src
docker-compose exec php composer install
.env.exampleファイルの名前を.envに変更
php artisan key:generate
docker-compose exec php php artisan migrate
docker-compose exec php php artisan db:seed
npm install
npm run dev
```

書き込み権限のエラーが発生する場合は
```bash
sudo chown -R ユーザー名:ユーザー名 .
sudo chmod -R 777 *
```

## 開発環境

### アクセスURL
- 商品一覧（トップ）画面：http://localhost:8000/
- ユーザー登録画面：http://localhost:8000/register/
- ユーザー登録：http://localhost:8000/login/
- phpMyAdmin：http://localhost:8080/

### 使用技術（実行環境）
- PHP 8.3.12
- Laravel 10.48.21
- MySQL 8.0.26
- nginx 1.21.1

## データベース構造

### ER図
![ER図](storage/app/public/images/docs/er-diagram.png)

以下はER図のMermaid記法での定義です：

```mermaid
erDiagram
    users {
        bigint id PK
        varchar name
        varchar email
        varchar password
        varchar postal_code
        varchar prefecture
        varchar city
        varchar address
        varchar building
        varchar profile_photo_path
        timestamp created_at
        timestamp updated_at
    }

    items {
        bigint id PK
        bigint user_id FK
        varchar name
        decimal price
        text description
        varchar image_path
        varchar condition
        varchar status
        timestamp created_at
        timestamp updated_at
    }

    purchases {
        bigint id PK
        bigint user_id FK
        bigint item_id FK
        decimal price
        varchar shipping_postal_code
        varchar shipping_prefecture
        varchar shipping_city
        varchar shipping_address
        varchar shipping_building
        varchar payment_intent_id
        varchar payment_method
        varchar status
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    comments {
        bigint id PK
        bigint user_id FK
        bigint item_id FK
        text content
        timestamp created_at
        timestamp updated_at
    }

    likes {
        bigint id PK
        bigint user_id FK
        bigint item_id FK
        timestamp created_at
        timestamp updated_at
    }

    users ||--o{ items : "出品する"
    users ||--o{ purchases : "購入する"
    users ||--o{ comments : "コメントする"
    users ||--o{ likes : "いいねする"
    items ||--o{ comments : "持つ"
    items ||--o{ likes : "持つ"
    items ||--o{ purchases : "購入される"
```
