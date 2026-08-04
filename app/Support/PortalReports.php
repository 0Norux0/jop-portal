<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortalReports
{
    private const PATH = 'portal/reports.json';

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function all(): array
    {
        if (! Storage::disk('local')->exists(self::PATH)) {
            return [];
        }

        $decoded = json_decode((string) Storage::disk('local')->get(self::PATH), true);

        return is_array($decoded) ? array_values($decoded) : [];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function create(array $data): array
    {
        $description = self::text($data['description'] ?? '');
        $report = [
            'id' => (string) Str::uuid(),
            'type' => self::type($data['type'] ?? 'job'),
            'subject' => self::text($data['subject'] ?? 'General report'),
            'reason' => self::text($data['reason'] ?? 'Safety concern'),
            'description' => $description,
            'contact_email' => self::email($data['contact_email'] ?? ''),
            'status' => 'pending_review',
            'flags' => self::detectFlags($description.' '.self::text($data['reason'] ?? '')),
            'created_at' => now()->toIso8601String(),
        ];

        $reports = self::all();
        array_unshift($reports, $report);

        Storage::disk('local')->put(self::PATH, json_encode(array_slice($reports, 0, 250), JSON_PRETTY_PRINT));

        return $report;
    }

    /**
     * @return array<int, string>
     */
    public static function detectFlags(string $text): array
    {
        $checks = [
            'possible fake employer' => ['fake company', 'unregistered employer', 'no business license', 'gmail recruiter'],
            'possible copied CV' => ['copied cv', 'stolen resume', 'plagiarized resume'],
            'possible fake certificate' => ['fake certificate', 'edited certificate', 'forged certificate'],
            'possible spam job' => ['work from home guaranteed', 'easy money', 'no interview needed'],
            'possible salary scam' => ['salary guaranteed', 'unrealistic salary', 'too good to be true'],
            'possible suspicious recruiter' => ['private recruiter only', 'telegram recruiter', 'whatsapp only recruiter'],
            'possible illegal placement fee' => ['placement fee', 'processing fee', 'pay agent', 'agency fee'],
            'possible passport surrender request' => ['passport surrender', 'keep your passport', 'submit passport'],
            'possible suspicious payment request' => ['western union', 'money transfer', 'crypto payment', 'pay before interview'],
            'possible visa guarantee claim' => ['visa guaranteed', 'job guaranteed', 'guaranteed approval'],
        ];

        $lower = Str::lower($text);

        return collect($checks)
            ->filter(fn (array $keywords): bool => collect($keywords)->contains(fn (string $keyword): bool => str_contains($lower, $keyword)))
            ->keys()
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    public static function reasons(): array
    {
        return [
            'Fake or misleading job',
            'Illegal recruitment fee request',
            'Passport surrender request',
            'Suspicious payment request',
            'Unsafe overseas offer',
            'Discrimination or illegal requirement',
            'Employer document concern',
            'Candidate profile concern',
            'Other safety issue',
        ];
    }

    private static function type(mixed $value): string
    {
        return in_array($value, ['job', 'employer', 'candidate'], true) ? $value : 'job';
    }

    private static function text(mixed $value): string
    {
        return trim(strip_tags((string) $value));
    }

    private static function email(mixed $value): string
    {
        $email = trim((string) $value);

        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : '';
    }
}
