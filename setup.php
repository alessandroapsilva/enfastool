<?php

define('PLUGIN_ENFASTOOL_VERSION', '0.1.0');

if (!defined('GLPI_ROOT')) {
   die('Direct access is not allowed');
}

require_once __DIR__ . '/inc/config.class.php';
require_once __DIR__ . '/install/install.php';

function plugin_init_enfastool(): void {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['csrf_compliant']['enfastool'] = true;
   $PLUGIN_HOOKS['config_page']['enfastool'] = 'front/config.form.php';
   $PLUGIN_HOOKS['add_css']['enfastool'] = 'css/login.css';
   $PLUGIN_HOOKS['add_javascript']['enfastool'] = 'js/login.js';
   $PLUGIN_HOOKS['post_init']['enfastool'] = 'plugin_enfastool_post_init';
}

function plugin_version_enfastool(): array {
   return [
      'name'           => 'EnfasTool',
      'version'        => PLUGIN_ENFASTOOL_VERSION,
      'author'         => 'EnfasTool Team',
      'license'        => 'GPLv2+',
      'homepage'       => 'https://github.com/alessandroapsilva/enfastool',
      'requirements'   => [
         'glpi' => [
            'min' => '11.0.0',
            'max' => '11.99.99',
         ],
      ],
   ];
}

function plugin_enfastool_check_prerequisites(): bool {
   return version_compare(GLPI_VERSION, '11.0.0', '>=');
}

function plugin_enfastool_check_config(): bool {
   return true;
}

function plugin_enfastool_post_init(): void {
   if (!plugin_enfastool_is_login_page()) {
      return;
   }

   $config = PluginEnfastoolConfig::getAll();
   if (empty($config['enabled'])) {
      return;
   }

   $encoded = json_encode($config, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
   if ($encoded === false) {
      return;
   }

   echo "\n<script>window.enfastoolConfig = {$encoded};</script>\n";
}

function plugin_enfastool_is_login_page(): bool {
   $script = $_SERVER['SCRIPT_NAME'] ?? '';
   $basename = basename($script);

   return $basename === 'login.php' || str_ends_with($script, '/front/login.php');
}
