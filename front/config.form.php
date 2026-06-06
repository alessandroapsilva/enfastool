<?php

include '../../../inc/includes.php';
require_once dirname(__DIR__) . '/inc/config.class.php';

Session::checkRight('config', UPDATE);

if (isset($_POST['save'])) {
   Session::checkCSRF($_POST);
   Html::cleanPostForTextArea($_POST);
   PluginEnfastoolConfig::saveFromInput($_POST, $_FILES);
   Session::addMessageAfterRedirect(__('Configuração salva com sucesso', 'enfastool'));
   Html::back();
}

if (isset($_POST['reset'])) {
   Session::checkCSRF($_POST);
   PluginEnfastoolConfig::reset();
   Session::addMessageAfterRedirect(__('Configuração restaurada para os valores padrão', 'enfastool'));
   Html::back();
}

$config = PluginEnfastoolConfig::getAll();

Html::header(__('EnfasTool - Login', 'enfastool'), $_SERVER['PHP_SELF'], 'config', 'plugins');

echo "<form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' enctype='multipart/form-data' class='center'>";
echo "<div class='card'>";
echo "<div class='card-header'><h2>" . __('Personalização da tela de login', 'enfastool') . "</h2></div>";
echo "<div class='card-body'>";
echo "<table class='tab_cadre_fixe'>";

echo "<tr><th colspan='2'>" . __('Status', 'enfastool') . "</th></tr>";
echo "<tr><td>" . __('Ativar personalização', 'enfastool') . "</td><td><input type='checkbox' name='enabled' value='1' " . ($config['enabled'] ? "checked='checked'" : '') . "></td></tr>";

echo "<tr><th colspan='2'>" . __('Branding', 'enfastool') . "</th></tr>";
echo "<tr><td>" . __('Logo (upload)', 'enfastool') . "</td><td><input type='file' name='logo_file' accept='image/*'>";
if (!empty($config['logo_path'])) {
   echo "<br><small>" . htmlspecialchars($config['logo_path']) . "</small>";
}
echo "</td></tr>";

echo "<tr><td>" . __('Título', 'enfastool') . "</td><td><input type='text' class='form-control' name='title' value='" . htmlspecialchars($config['title']) . "'></td></tr>";
echo "<tr><td>" . __('Subtítulo', 'enfastool') . "</td><td><input type='text' class='form-control' name='subtitle' value='" . htmlspecialchars($config['subtitle']) . "'></td></tr>";
echo "<tr><td>" . __('Texto de rodapé', 'enfastool') . "</td><td><input type='text' class='form-control' name='footer_text' value='" . htmlspecialchars($config['footer_text']) . "'></td></tr>";

echo "<tr><th colspan='2'>" . __('Plano de fundo', 'enfastool') . "</th></tr>";
echo "<tr><td>" . __('Tipo de fundo', 'enfastool') . "</td><td><select name='background_type' class='form-control'>";
foreach (['gradient' => __('Gradiente', 'enfastool'), 'solid' => __('Sólido', 'enfastool'), 'image' => __('Imagem', 'enfastool')] as $value => $label) {
   $selected = $config['background_type'] === $value ? "selected='selected'" : '';
   echo "<option value='" . $value . "' " . $selected . ">" . $label . "</option>";
}
echo "</select></td></tr>";
echo "<tr><td>" . __('Imagem de fundo (upload)', 'enfastool') . "</td><td><input type='file' name='background_file' accept='image/*'>";
if (!empty($config['background_image'])) {
   echo "<br><small>" . htmlspecialchars($config['background_image']) . "</small>";
}
echo "</td></tr>";
echo "<tr><td>" . __('Cor sólida', 'enfastool') . "</td><td><input type='color' name='background_solid_color' value='" . htmlspecialchars($config['background_solid_color']) . "'></td></tr>";
echo "<tr><td>" . __('Gradiente início', 'enfastool') . "</td><td><input type='color' name='background_gradient_start' value='" . htmlspecialchars($config['background_gradient_start']) . "'></td></tr>";
echo "<tr><td>" . __('Gradiente fim', 'enfastool') . "</td><td><input type='color' name='background_gradient_end' value='" . htmlspecialchars($config['background_gradient_end']) . "'></td></tr>";

echo "<tr><th colspan='2'>" . __('Cores e cartão de login', 'enfastool') . "</th></tr>";
echo "<tr><td>" . __('Cor primária', 'enfastool') . "</td><td><input type='color' name='primary_color' value='" . htmlspecialchars($config['primary_color']) . "'></td></tr>";
echo "<tr><td>" . __('Cor de destaque', 'enfastool') . "</td><td><input type='color' name='accent_color' value='" . htmlspecialchars($config['accent_color']) . "'></td></tr>";
echo "<tr><td>" . __('Fundo do cartão (hex/rgba)', 'enfastool') . "</td><td><input type='text' class='form-control' name='card_background' value='" . htmlspecialchars($config['card_background']) . "'></td></tr>";
echo "<tr><td>" . __('Raio da borda do cartão (px)', 'enfastool') . "</td><td><input type='text' class='form-control' name='card_border_radius' value='" . htmlspecialchars($config['card_border_radius']) . "'></td></tr>";

echo "</table>";
echo "<div class='mt-3'>";
echo Html::hidden('_glpi_csrf_token', ['value' => Session::getNewCSRFToken()]);
echo Html::submit(__('Salvar', 'enfastool'), ['name' => 'save', 'class' => 'btn btn-primary me-2']);
echo Html::submit(__('Restaurar padrão', 'enfastool'), ['name' => 'reset', 'class' => 'btn btn-secondary']);
echo "</div>";
echo "</div></div></form>";

Html::footer();
