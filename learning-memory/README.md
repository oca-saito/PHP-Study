# Learning Memory System

このフォルダは、Codexが学習者の状態を理解し続けるための補助ファイル置き場です。

## 毎回読むファイル

学習相談の前に、Codexには次の順番で読ませます。

1. `AGENTS.md`
2. `docs/learning/Codex.md`
3. `LEARNING_MEMORY.md`
4. `learning-memory/update-rules.md`
5. 通常相談の履歴が必要な場合は `learning-memory/interaction-log.md`
6. 評価が必要な場合は `docs/learning/evaluation.md`
7. 直近の評価状態が必要な場合は `learning-memory/evaluation-log.md`

## 毎回更新する中心ファイル

- `LEARNING_MEMORY.md`
- `learning-memory/interaction-log.md`
- `learning-memory/evaluation-log.md`

`LEARNING_MEMORY.md` には進捗、つまずき、理解済みのこと、まだ曖昧なことを集約します。
通常の学習相談は `learning-memory/interaction-log.md` に記録します。
評価結果は `learning-memory/evaluation-log.md` に記録します。

## 補助ファイル

- `learning-memory/update-rules.md`: Codexが学習記録を更新するときのルール
- `learning-memory/session-template.md`: 学習後に追記する内容の型
- `learning-memory/quick-prompts.md`: 毎回短く聞くためのプロンプト例
- `learning-memory/interaction-log.md`: システム構築以外の学習相談ログ
- `learning-memory/evaluation-log.md`: 各課題の評価メモ
- `docs/learning/evaluation.md`: 評価基準

## 既存教材の保護

Codexは、学習記録の更新では以下を変更しません。

- `README.md`
- `sample/`
- `works/`
- `AGENTS.md`
- `docs/learning/`

通常の学習記録や評価記録として変更してよいのは、`LEARNING_MEMORY.md` と `learning-memory/` 配下だけです。
システム構築を明確に依頼された場合だけ、`AGENTS.md` や `docs/learning/` 配下も更新できます。
