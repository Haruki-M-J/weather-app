
---

## 機能

- 都市名から天気情報を取得（API連携）
- 天気 + メモの保存
- 保存データの一覧表示
- データの削除機能

---

## デプロイURL

http://3.27.107.101/

---

## 工夫した点
- PostgreSQLを用いた永続的にデータ管理ができるようにした
- EC2,NGINXを用いて本番環境にデプロイした

---

## ディレクトリ構成
```
/var/www/userlorem/
├── public/
│   ├── index.html
│   ├── script.js
│   └── style.css
└── api/
    ├── db.php
    ├── save.php
    ├── get.php
    └── delete.php
```

---

## APIについて

天気取得には以下のAPIを使用している：

- OpenWeather API  
https://openweathermap.org/api

---

## 環境変数

実行前に以下を設定してください。

- `DB_HOST`
- `DB_PORT` 省略時は `5432`
- `DB_NAME`
- `DB_USER`
- `DB_PASSWORD`
- `OPENWEATHER_API_KEY`
