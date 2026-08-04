<?php

declare(strict_types=1);

namespace ReCaptcha\Verifier;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use ReCaptcha\ReCaptcha;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Verifies a reCAPTCHA v3 token against the Google siteverify endpoint.
 *
 * A token is single use: it must be verified exactly once per form submission.
 *
 * Both dependencies are optional so the verifier can also be built outside of the
 * container.
 */
final readonly class ReCaptchaVerifier
{
    private const string SITEVERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    private const float DEFAULT_MIN_SCORE = 0.3;

    private HttpClientInterface $httpClient;

    private LoggerInterface $logger;

    public function __construct(?HttpClientInterface $httpClient = null, ?LoggerInterface $logger = null)
    {
        $this->httpClient = $httpClient ?? HttpClient::create();
        $this->logger = $logger ?? new NullLogger();
    }

    public function getSiteKey(): ?string
    {
        $siteKey = ReCaptcha::getConfigValue('site_key');

        return '' === $siteKey ? null : $siteKey;
    }

    /**
     * A store that has not entered its Google keys yet must keep working: callers
     * skip the check instead of locking every protected form.
     */
    public function isConfigured(): bool
    {
        return null !== $this->getSiteKey() && !empty(ReCaptcha::getConfigValue('secret_key'));
    }

    /**
     * Fails closed: an empty token, a network error or a score below the configured
     * threshold all mean "not a human".
     */
    public function verify(?string $token, ?string $remoteIp = null): bool
    {
        if (null === $token || '' === $token) {
            return false;
        }

        try {
            $result = $this->httpClient->request('POST', self::SITEVERIFY_URL, [
                'body' => array_filter([
                    'secret' => ReCaptcha::getConfigValue('secret_key'),
                    'response' => $token,
                    'remoteip' => $remoteIp,
                ]),
                'timeout' => 5,
            ])->toArray(false);
        } catch (\Throwable $exception) {
            $this->logger->error('reCAPTCHA verification failed: {message}', [
                'message' => $exception->getMessage(),
                'exception' => $exception,
            ]);

            return false;
        }

        if (empty($result['success'])) {
            $this->logger->info('reCAPTCHA rejected the token: {errors}', [
                'errors' => implode(', ', $result['error-codes'] ?? []),
            ]);

            return false;
        }

        if (!\array_key_exists('score', $result)) {
            return true;
        }

        return (float) $result['score'] >= $this->getMinScore();
    }

    private function getMinScore(): float
    {
        $minScore = ReCaptcha::getConfigValue('min_score');

        return is_numeric($minScore) ? (float) $minScore : self::DEFAULT_MIN_SCORE;
    }
}
