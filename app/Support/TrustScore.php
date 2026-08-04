<?php

declare(strict_types=1);

namespace App\Support;

class TrustScore
{
    /**
     * @param  array<string, bool|int>  $signals
     */
    public static function candidate(array $signals): int
    {
        return min(100, 20
            + self::points($signals, 'verified_identity', 15)
            + self::points($signals, 'verified_certificates', 15)
            + self::points($signals, 'portfolio_quality', 15)
            + self::points($signals, 'response_rate', 10)
            + self::points($signals, 'interview_attendance', 10)
            + self::points($signals, 'employer_reviews', 10)
            + self::points($signals, 'fake_detection_clear', 5));
    }

    /**
     * @param  array<string, bool|int>  $signals
     */
    public static function employer(array $signals): int
    {
        return min(100, 25
            + self::points($signals, 'company_documents', 20)
            + self::points($signals, 'verified_domain', 15)
            + self::points($signals, 'clear_job_rules', 10)
            + self::points($signals, 'payment_record', 10)
            + self::points($signals, 'review_history', 10)
            + self::points($signals, 'fake_detection_clear', 10));
    }

    /**
     * @return array<int, array{label: string, value: string}>
     */
    public static function candidateInputs(): array
    {
        return [
            ['label' => 'Verified identity input', 'value' => 'Passport/ID status, optional and private'],
            ['label' => 'Verified certificates input', 'value' => 'Certificate files, issuer, number, reviewer status'],
            ['label' => 'Portfolio quality input', 'value' => 'Project count, proof links, descriptions, reviewer notes'],
            ['label' => 'Response rate input', 'value' => 'Employer replies and message responsiveness'],
            ['label' => 'Interview attendance input', 'value' => 'Scheduled, attended, missed, and rescheduled interviews'],
            ['label' => 'Employer reviews input', 'value' => 'Post-interview or post-placement employer feedback'],
            ['label' => 'Fake detection input', 'value' => 'Rule-based safety flags and admin review outcomes'],
        ];
    }

    /**
     * @return array<int, array{label: string, value: string}>
     */
    public static function employerInputs(): array
    {
        return [
            ['label' => 'Company documents', 'value' => 'Commercial license, registration, agency license'],
            ['label' => 'Verified domain/email', 'value' => 'Official business email or domain check'],
            ['label' => 'Job posting rules', 'value' => 'No illegal fees, no passport surrender, clear salary and benefits'],
            ['label' => 'Payment record', 'value' => 'Package, invoice, and credit history'],
            ['label' => 'Review history', 'value' => 'Report queue outcomes and admin verification notes'],
            ['label' => 'Fake detection input', 'value' => 'Suspicious payment, salary scam, fake recruiter, and spam checks'],
        ];
    }

    private static function points(array $signals, string $key, int $points): int
    {
        return ! empty($signals[$key]) ? $points : 0;
    }
}
