<?php

class PluginEnfastoolConfig {
   private const CONFIG_CONTEXT = 'plugin:enfastool';
   private const CONFIG_KEYS = [
      'enabled',
      'logo_path',
      'background_type',
      'background_image',
      'background_solid_color',
      'background_gradient_start',
      'background_gradient_end',
      'primary_color',
      'accent_color',
      'card_background',
      'card_border_radius',
      'title',
      'subtitle',
      'footer_text',
   ];

   public static function getDefaults(): array {
      return [
         'enabled'                   => 0,
         'logo_path'                 => '',
         'background_type'           => 'gradient',
         'background_image'          => '',
         'background_solid_color'    => '#0b132b',
         'background_gradient_start' => '#1b2a49',
         'background_gradient_end'   => '#0f172a',
         'primary_color'             => '#2b6cb0',
         'accent_color'              => '#63b3ed',
         'card_background'           => 'rgba(255, 255, 255, 0.92)',
         'card_border_radius'        => '16px',
         'title'                     => 'Bem-vindo ao EnfasTool',
         'subtitle'                  => 'Acesse sua conta para continuar',
         'footer_text'               => '© ' . date('Y') . ' Clínica - EnfasTool',
      ];
   }

   public static function getAll(): array {
      $defaults = self::getDefaults();
      $config = Config::getConfigurationValues(self::CONFIG_CONTEXT);

      if (!is_array($config)) {
         return $defaults;
      }

      $merged = array_merge($defaults, array_intersect_key($config, $defaults));
      $merged['enabled'] = (int) !empty($merged['enabled']);

      return $merged;
   }

   public static function saveFromInput(array $input, array $files): void {
      $current = self::getAll();

      $next = [
         'enabled'                   => isset($input['enabled']) ? 1 : 0,
         'background_type'           => self::sanitizeBackgroundType($input['background_type'] ?? $current['background_type']),
         'background_solid_color'    => self::sanitizeHexColor($input['background_solid_color'] ?? $current['background_solid_color'], $current['background_solid_color']),
         'background_gradient_start' => self::sanitizeHexColor($input['background_gradient_start'] ?? $current['background_gradient_start'], $current['background_gradient_start']),
         'background_gradient_end'   => self::sanitizeHexColor($input['background_gradient_end'] ?? $current['background_gradient_end'], $current['background_gradient_end']),
         'primary_color'             => self::sanitizeHexColor($input['primary_color'] ?? $current['primary_color'], $current['primary_color']),
         'accent_color'              => self::sanitizeHexColor($input['accent_color'] ?? $current['accent_color'], $current['accent_color']),
         'card_background'           => self::sanitizeCardBackground($input['card_background'] ?? $current['card_background']),
         'card_border_radius'        => self::sanitizeSize($input['card_border_radius'] ?? $current['card_border_radius'], '16px'),
         'title'                     => self::sanitizeText($input['title'] ?? $current['title']),
         'subtitle'                  => self::sanitizeText($input['subtitle'] ?? $current['subtitle']),
         'footer_text'               => self::sanitizeText($input['footer_text'] ?? $current['footer_text']),
         'logo_path'                 => self::saveImage($files['logo_file'] ?? null, $current['logo_path']),
         'background_image'          => self::saveImage($files['background_file'] ?? null, $current['background_image']),
      ];

      Config::setConfigurationValues(self::CONFIG_CONTEXT, $next);
   }

   public static function reset(): void {
      Config::deleteConfigurationValues(self::CONFIG_CONTEXT, self::CONFIG_KEYS);
   }

   private static function sanitizeBackgroundType(string $value): string {
      $allowed = ['image', 'solid', 'gradient'];
      return in_array($value, $allowed, true) ? $value : 'gradient';
   }

   private static function sanitizeHexColor(string $value, string $fallback): string {
      $value = trim($value);
      return preg_match('/^#[0-9a-fA-F]{6}$/', $value) ? $value : $fallback;
   }

   private static function sanitizeCardBackground(string $value): string {
      $value = trim($value);
      if (preg_match('/^#[0-9a-fA-F]{6}$/', $value)) {
         return $value;
      }

      if (preg_match('/^rgba\(\s*(?:\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\s*,\s*(?:\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\s*,\s*(?:\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\s*,\s*(?:0|0?\.\d+|1(?:\.0+)?)\s*\)$/', $value)) {
         return $value;
      }

      return 'rgba(255, 255, 255, 0.92)';
   }

   private static function sanitizeSize(string $value, string $fallback): string {
      $value = trim($value);
      return preg_match('/^\d{1,3}px$/', $value) ? $value : $fallback;
   }

   private static function sanitizeText(string $value): string {
      return trim(strip_tags($value));
   }

   private static function saveImage(?array $file, string $fallback): string {
      if (!is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
         return $fallback;
      }

      $tmpName = $file['tmp_name'] ?? '';
      if ($tmpName === '' || !is_uploaded_file($tmpName)) {
         return $fallback;
      }

      $extension = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
      if (!in_array($extension, ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg'], true)) {
         return $fallback;
      }

      $directory = self::getPublicPluginFilesDirectory();
      if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
         return $fallback;
      }

      try {
         $suffix = bin2hex(random_bytes(4));
      } catch (Throwable $exception) {
         $suffix = uniqid('', true);
      }

      $name = sprintf('enfastool-%s-%s.%s', date('YmdHis'), $suffix, $extension);
      $path = $directory . '/' . $name;

      if (!move_uploaded_file($tmpName, $path)) {
         return $fallback;
      }

      return self::getPublicPluginFilesUrl() . '/' . $name;
   }

   private static function getPublicPluginFilesDirectory(): string {
      if (defined('GLPI_PLUGIN_DOC_DIR')) {
         return rtrim(GLPI_PLUGIN_DOC_DIR, '/') . '/enfastool';
      }

      return GLPI_ROOT . '/files/_plugins/enfastool';
   }

   private static function getPublicPluginFilesUrl(): string {
      global $CFG_GLPI;
      return rtrim($CFG_GLPI['root_doc'] ?? '', '/') . '/files/_plugins/enfastool';
   }
}
