# Daily Report

このファイルは、学習ログと評価ログから日次レポートを作成する手順です。

## 作成コマンド

```bash
php scripts/create-daily-report.php YYYY-MM-DD
```

例:

```bash
php scripts/create-daily-report.php 2026-06-23
```

## 出力先

```text
learning-memory/daily-reports/YYYY-MM-DD.md
```

## レポートに含める内容

- `LEARNING_MEMORY.md` の現在地、進捗表、次回候補
- `learning-memory/interaction-log.md` の指定日ログ
- `learning-memory/evaluation-log.md` の指定日評価ログ

## 注意

- 完成コードや最終解答そのものは記録しない。
- 日次レポートは振り返りと次回引き継ぎ用に使う。
- 課題ファイルの正誤は、必要に応じて別途 `php -l` と `php ファイル名.php` で確認する。
