<?php

namespace App\Services;

class DeviceDetector
{
    /**
     * Parsing User-Agent string untuk mendeteksi perangkat, sistem operasi, dan browser
     */
    public static function parse(?string $userAgent): array
    {
        $ua = $userAgent ?? '';
        
        $device = self::detectDevice($ua);
        $platform = self::detectPlatform($ua);
        $browser = self::detectBrowser($ua);

        return [
            'device_type' => $device,
            'platform' => $platform,
            'browser' => $browser,
            'raw' => $ua
        ];
    }

    protected static function detectDevice(string $ua): string
    {
        if (preg_match('/(bot|crawl|slurp|spider|mediapartners)/i', $ua)) {
            return 'Bot / Crawler';
        }
        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i', $ua)) {
            return 'Tablet';
        }
        if (preg_match('/(up\.browser|up\.link|mmp|symbian|smartphone|midp|wap|phone|android|iphone|ipod)/i', $ua)) {
            return 'Smartphone (Mobile)';
        }
        return 'Desktop / Laptop';
    }

    protected static function detectPlatform(string $ua): string
    {
        if (preg_match('/windows nt 10\.0/i', $ua)) {
            return 'Windows 10 / 11';
        }
        if (preg_match('/windows nt 6\.3/i', $ua)) {
            return 'Windows 8.1';
        }
        if (preg_match('/windows nt 6\.2/i', $ua)) {
            return 'Windows 8';
        }
        if (preg_match('/windows nt 6\.1/i', $ua)) {
            return 'Windows 7';
        }
        if (preg_match('/windows nt 6\.0/i', $ua)) {
            return 'Windows Vista';
        }
        if (preg_match('/windows nt 5\.1|windows xp/i', $ua)) {
            return 'Windows XP';
        }
        if (preg_match('/windows/i', $ua)) {
            return 'Windows';
        }
        if (preg_match('/android ([0-9\.]+)/i', $ua, $matches)) {
            return 'Android ' . $matches[1];
        }
        if (preg_match('/android/i', $ua)) {
            return 'Android';
        }
        if (preg_match('/iphone os ([0-9_]+)/i', $ua, $matches)) {
            return 'iOS ' . str_replace('_', '.', $matches[1]);
        }
        if (preg_match('/iphone/i', $ua)) {
            return 'iPhone (iOS)';
        }
        if (preg_match('/ipad/i', $ua)) {
            return 'iPad (iPadOS)';
        }
        if (preg_match('/macintosh|mac os x/i', $ua)) {
            return 'macOS';
        }
        if (preg_match('/linux/i', $ua)) {
            return 'Linux';
        }
        if (preg_match('/cros/i', $ua)) {
            return 'Chrome OS';
        }

        return 'Unknown OS';
    }

    protected static function detectBrowser(string $ua): string
    {
        if (preg_match('/edg\/([0-9\.]+)/i', $ua, $matches)) {
            return 'Microsoft Edge ' . explode('.', $matches[1])[0];
        }
        if (preg_match('/opr\/([0-9\.]+)|opera\/([0-9\.]+)/i', $ua, $matches)) {
            $ver = !empty($matches[1]) ? $matches[1] : $matches[2];
            return 'Opera ' . explode('.', $ver)[0];
        }
        if (preg_match('/chrome\/([0-9\.]+)/i', $ua, $matches) && !preg_match('/edg/i', $ua)) {
            return 'Google Chrome ' . explode('.', $matches[1])[0];
        }
        if (preg_match('/firefox\/([0-9\.]+)/i', $ua, $matches)) {
            return 'Mozilla Firefox ' . explode('.', $matches[1])[0];
        }
        if (preg_match('/version\/([0-9\.]+).*safari/i', $ua, $matches)) {
            return 'Apple Safari ' . explode('.', $matches[1])[0];
        }
        if (preg_match('/safari\/([0-9\.]+)/i', $ua)) {
            return 'Apple Safari';
        }
        if (preg_match('/msie|trident/i', $ua)) {
            return 'Internet Explorer';
        }

        return 'Browser Web';
    }
}
