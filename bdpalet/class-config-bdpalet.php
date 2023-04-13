
<?php

class  ConfigBaseDeDonnesPalet
{
  protected $option_servername='bdpalet_servername';
  protected $option_dbname='bdpalet_dbname';
  protected $option_username='bdpalet_username';
  protected $option_password='bdpalet_password';

  protected $default_servername = 'localhost';
  protected $default_dbname = 'bdpalet';
  protected $default_username = 'root';
  protected $default_password = 'root';

  function config_bdpalet_get_servername()
  {
    return get_option($this->option_servername, $this->default_servername);
  }
  function config_bdpalet_get_dbname()
  {
    return get_option($this->option_dbname, $this->default_dbname);
  }
  function config_bdpalet_get_username()
  {
    return get_option($this->option_username, $this->default_username);
  }
  function config_bdpalet_get_password()
  {
    return get_option($this->option_password, $this->default_password);
  }


  function init_config_bdpalet()
  {
    add_action('admin_init', array( $this, 'bdpalet_register_settings' ));
    add_action('admin_menu', array( $this, 'bdpalet_setting_page' ));
  }

  function bdpalet_register_settings() 
  { 
    register_setting('bdpalet_options_group', $this->option_servername); 
    register_setting('bdpalet_options_group', $this->option_dbname); 
    register_setting('bdpalet_options_group', $this->option_username); 
    register_setting('bdpalet_options_group', $this->option_password); 
  }
  function bdpalet_setting_page() 
  {
    // add_options_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '' ) 
    add_options_page('BD Palet', 'BD Palet', 'manage_options', ' bdpalet-plugin-setting-url', array( $this, 'bdpalet_page_html_form')); 
    // custom_page_html_form est la fonction dans laquelle j'ai écrit le HTML pour mon formulaire de plugin personnalisé. 
  } 

  function bdpalet_page_html_form() { ?>
    <div class="wrap">
    <h2>Configuration de la base de donn&eacute;es</h2>
    <form method="post" action="options.php">
    <?php settings_fields('bdpalet_options_group'); ?>

    <table class="form-table">
    <tr>
    <th><label for="first_field_id">Nom du serveur :</label></th>
    <td>
    <input type = 'text' class="regular-text" id="first_field_id" name="<?php echo $this->option_servername; ?>" value="<?php echo $this->config_bdpalet_get_servername(); ?>">
    </td>
    </tr>

    <tr>
    <th><label for="second_field_id">Nom de la base de donn&eacute;es :</label></th>
    <td>
    <input type = 'text' class="regular-text" id="second_field_id" name="<?php echo $this->option_dbname; ?>" value="<?php echo $this->config_bdpalet_get_dbname(); ?>">
    </td>
    </tr>

    <tr>
    <th><label for="third_field_id">Utilisateur :</label></th>
    <td>
    <input type = 'text' class="regular-text" id="third_field_id" name="<?php echo $this->option_username; ?>" value="<?php echo $this->config_bdpalet_get_username(); ?>">
    </td>
    </tr>

    <tr>
    <th><label for="third_field_id">Mot de passe :</label></th>
    <td>
    <input type = 'text' class="regular-text" id="third_field_id" name="<?php echo $this->option_password; ?>" value="<?php echo $this->config_bdpalet_get_password(); ?>">
    </td>
    </tr>
    </table>

    <?php submit_button(); ?>
    </div>
    <?php
  }
}

?>
