<?php

namespace OZiTAG\Tager\Backend\Core\Middlewares;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;

    /**
     * Результат резолва имени прокси и время, когда он получен.
     *
     * @var array{0: array<int, string>, 1: float}|null
     */
    private static ?array $resolvedHost = null;

    /**
     * Сколько секунд живёт результат резолва.
     */
    private const RESOLVE_TTL = 60;

    /**
     * Адреса доверенных прокси.
     *
     * Раньше это делалось в конструкторе:
     *
     *     if (gethostbyname('nginx')) {
     *         $this->proxies = [gethostbyname('nginx')];
     *     }
     *
     * и там было две ошибки. Первая: gethostbyname() при неудаче возвращает
     * само имя, а не false, поэтому условие истинно всегда — там, где контейнера
     * `nginx` нет, в доверенные прокси попадала строка 'nginx', которую Symfony
     * адресом не считает. Вторая: middleware создаётся на каждый запрос, так что
     * на каждый запрос приходилось по два обращения к резолверу, и без записи
     * `nginx` в DNS каждый ответ становился медленнее на таймаут резолвера —
     * порядка десяти секунд.
     *
     * Теперь адреса сначала берутся из конфига, резолв остался запасным путём
     * (ради compose-раскладок, где рядом действительно стоит контейнер `nginx`),
     * его результат живёт минуту на процесс, и неудачный резолв даёт пустой
     * список, а не мусор.
     *
     * @return array<int, string>|string|null
     */
    protected function proxies()
    {
        if ($fromParent = parent::proxies()) {
            return $fromParent;
        }

        if ($configured = $this->configuredProxies()) {
            return $configured;
        }

        return $this->resolveProxyHost(config('tager-app.trusted_proxy_host'));
    }

    /**
     * @return array<int, string>|string|null
     */
    private function configuredProxies()
    {
        $configured = config('tager-app.trusted_proxies');

        if (is_array($configured)) {
            return $configured ?: null;
        }

        if (!is_string($configured) || trim($configured) === '') {
            return null;
        }

        $configured = trim($configured);

        // '*' и '**' Symfony понимает только строкой, списком — нет.
        if ($configured === '*' || $configured === '**') {
            return $configured;
        }

        return array_values(array_filter(array_map('trim', explode(',', $configured))));
    }

    /**
     * @return array<int, string>
     */
    private function resolveProxyHost(?string $host): array
    {
        if (!$host) {
            return [];
        }

        if (self::$resolvedHost !== null && (microtime(true) - self::$resolvedHost[1]) < self::RESOLVE_TTL) {
            return self::$resolvedHost[0];
        }

        $address = gethostbyname($host);

        // Имя вернулось как есть — значит резолв не удался.
        $proxies = $address === $host ? [] : [$address];

        self::$resolvedHost = [$proxies, microtime(true)];

        return $proxies;
    }
}
