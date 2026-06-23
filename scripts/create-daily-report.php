<?php
declare(strict_types=1);

$repoRoot = dirname(__DIR__);
$timezone = new DateTimeZone('Asia/Tokyo');
$date = $argv[1] ?? (new DateTimeImmutable('now', $timezone))->format('Y-m-d');

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    fwrite(STDERR, "Usage: php scripts/create-daily-report.php YYYY-MM-DD\n");
    exit(1);
}

$paths = [
    'learningMemory' => $repoRoot . '/LEARNING_MEMORY.md',
    'interactionLog' => $repoRoot . '/learning-memory/interaction-log.md',
    'evaluationLog' => $repoRoot . '/learning-memory/evaluation-log.md',
    'outputDir' => $repoRoot . '/learning-memory/daily-reports',
];

foreach (['learningMemory', 'interactionLog', 'evaluationLog'] as $key) {
    if (!is_file($paths[$key])) {
        fwrite(STDERR, "Required file not found: {$paths[$key]}\n");
        exit(1);
    }
}

if (!is_dir($paths['outputDir']) && !mkdir($paths['outputDir'], 0775, true)) {
    fwrite(STDERR, "Could not create output directory: {$paths['outputDir']}\n");
    exit(1);
}

$learningMemory = file_get_contents($paths['learningMemory']);
$interactionLog = file_get_contents($paths['interactionLog']);
$evaluationLog = file_get_contents($paths['evaluationLog']);

if ($learningMemory === false || $interactionLog === false || $evaluationLog === false) {
    fwrite(STDERR, "Could not read one or more input files.\n");
    exit(1);
}

function extractHeadingBlock(string $markdown, string $heading): string
{
    $normalized = str_replace(["\r\n", "\r"], "\n", $markdown);
    $lines = explode("\n", $normalized);
    $capturing = false;
    $body = [];

    foreach ($lines as $line) {
        if (str_starts_with($line, '## ')) {
            if ($capturing) {
                break;
            }

            if (substr($line, 3) === $heading) {
                $capturing = true;
            }

            continue;
        }

        if ($capturing) {
            $body[] = $line;
        }
    }

    $text = trim(implode("\n", $body));
    return $text === '' ? '_該当なし_' : $text;
}

function extractDateSections(string $markdown, string $date): array
{
    $normalized = str_replace(["\r\n", "\r"], "\n", $markdown);
    $lines = explode("\n", $normalized);

    $sections = [];
    $capturing = false;
    $current = [];

    foreach ($lines as $line) {
        if (str_starts_with($line, '## ')) {
            if ($capturing && $current !== []) {
                $sections[] = trim(implode("\n", $current));
            }

            $heading = substr($line, 3);
            $capturing = substr($heading, 0, 10) === $date;
            $current = $capturing ? [$line] : [];
            continue;
        }

        if ($capturing) {
            $current[] = $line;
        }
    }

    if ($capturing && $current !== []) {
        $sections[] = trim(implode("\n", $current));
    }

    return array_values(array_filter($sections, static fn (string $section): bool => $section !== ''));
}

function renderSections(array $sections): string
{
    if ($sections === []) {
        return '_該当なし_';
    }

    return implode("\n\n", $sections);
}

$generatedAt = (new DateTimeImmutable('now', $timezone))->format('Y-m-d H:i:s T');
$currentPosition = extractHeadingBlock($learningMemory, '現在の学習位置');
$progress = extractHeadingBlock($learningMemory, '進捗');
$nextCandidates = extractHeadingBlock($learningMemory, '次の学習候補');
$interactionSections = extractDateSections($interactionLog, $date);
$evaluationSections = extractDateSections($evaluationLog, $date);
$renderedInteractionSections = renderSections($interactionSections);
$renderedEvaluationSections = renderSections($evaluationSections);

$report = <<<MD
# 日次レポート {$date}

- 生成日時: {$generatedAt}
- 生成コマンド: `php scripts/create-daily-report.php {$date}`
- 参照元: `LEARNING_MEMORY.md`, `learning-memory/interaction-log.md`, `learning-memory/evaluation-log.md`

## 今日の現在地

{$currentPosition}

## 現在の進捗表

{$progress}

## 今日の相談ログ

{$renderedInteractionSections}

## 今日の評価ログ

{$renderedEvaluationSections}

## 次回の候補

{$nextCandidates}

## レポート作成メモ

- このレポートは学習ログと評価ログから自動生成しています。
- 完成コードや最終解答そのものは含めず、進捗、つまずき、評価、次回方針を引き継ぐためのメモとして使います。

MD;

$outputPath = $paths['outputDir'] . '/' . $date . '.md';
if (file_put_contents($outputPath, $report) === false) {
    fwrite(STDERR, "Could not write report: {$outputPath}\n");
    exit(1);
}

echo "Daily report written: {$outputPath}\n";
