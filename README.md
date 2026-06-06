# EnfasTool

EnfasTool é um plugin original para **GLPI 11** focado na personalização premium da tela de login para ambientes clínicos.

## Recursos implementados (scaffold inicial funcional)

- Estrutura base de plugin GLPI 11 com `setup.php`, instalação e página de configuração
- Configuração administrativa para:
  - ativar/desativar o tema de login
  - logo (upload)
  - fundo (imagem, sólido ou gradiente)
  - cores primária e de destaque
  - estilo do card de login (fundo e borda)
  - título, subtítulo e texto de rodapé
- Injeção de assets CSS/JS do plugin para renderização do login customizado
- Layout responsivo com fallback para desktop e mobile
- Armazenamento em configuração do GLPI (`plugin:enfastool`)

## Estrutura de arquivos

- `/setup.php` — bootstrap, metadata e hooks do plugin
- `/install/install.php` — rotinas de instalação/desinstalação
- `/inc/config.class.php` — defaults, leitura/salvamento de configuração e upload de imagens
- `/front/config.form.php` — UI administrativa de configuração no GLPI
- `/css/login.css` — tema visual do login
- `/js/login.js` — aplicação dinâmica das preferências no login

## Instalação

1. Copie o plugin para a pasta de plugins do GLPI com o nome `enfastool`:
   - `glpi/plugins/enfastool`
2. No GLPI, acesse **Configurar > Plugins**.
3. Localize **EnfasTool**, execute **Instalar** e depois **Ativar**.
4. Abra a página de configuração do plugin e ajuste os parâmetros desejados.

## Compatibilidade

- Compatível com **GLPI 11.x**
- Não altera arquivos core do GLPI
- Estrutura preparada para evolução modular em próximas versões

## Notas

- Caso não haja configuração salva, o plugin utiliza valores padrão seguros.
- Se imagens não forem enviadas, o tema continua funcional com cores/gradiente padrão.
- Este projeto usa identidade visual original, sem reutilizar branding de terceiros.
