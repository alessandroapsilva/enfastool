<?php

function plugin_enfastool_install(): bool {
   Config::setConfigurationValues('plugin:enfastool', PluginEnfastoolConfig::getDefaults());
   return true;
}

function plugin_enfastool_uninstall(): bool {
   PluginEnfastoolConfig::reset();
   return true;
}
