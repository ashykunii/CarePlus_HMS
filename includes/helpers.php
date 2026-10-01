<?php
// Helper to generate initials avatar circle
if (!function_exists('avatar_circle')) {
    function avatar_circle($name, $size = 40, $extra_class = '') {
        $name = trim((string) $name);
        if ($name === '') {
            $name = '?';
        }

        // Get initials (up to 2 letters)
        $words = preg_split('/\s+/', preg_replace('/^(Dr\.|Mr\.|Mrs\.|Ms\.)\s+/i', '', $name));
        $words = array_values(array_filter($words, fn($w) => $w !== ''));
        $initials = '';
        if (count($words) >= 2) {
            $initials = strtoupper(substr($words[0], 0, 1) . substr($words[count($words) - 1], 0, 1));
        } elseif (count($words) === 1) {
            $initials = strtoupper(substr($words[0], 0, 2));
        } else {
            $initials = '?';
        }

        // Pick color based on name
        $palette = [
            '#dc2626',
            '#0f172a',
            '#1e293b',
            '#991b1b',
            '#1e3a8a',
            '#b91c1c',
            '#334155',
        ];
        $hash = 0;
        foreach (str_split($name) as $ch) {
            $hash = (($hash << 5) - $hash) + ord($ch);
            $hash = $hash & 0xFFFFFFFF;
        }
        $color = $palette[abs($hash) % count($palette)];

        $font_size = max(11, round($size * 0.38));
        $safe_name = htmlspecialchars($name, ENT_QUOTES);
        $safe_initials = htmlspecialchars($initials, ENT_QUOTES);

        return sprintf(
            '<span class="avatar-circle %s" title="%s" style="width:%dpx;height:%dpx;min-width:%dpx;font-size:%dpx;background:%s;">%s</span>',
            htmlspecialchars($extra_class, ENT_QUOTES),
            $safe_name,
            $size,
            $size,
            $size,
            $font_size,
            $color,
            $safe_initials
        );
    }
}
