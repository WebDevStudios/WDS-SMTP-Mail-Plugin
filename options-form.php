<div class="wrap">
	<h2><?php _e('MS Office SMTP Mail Options', 'wds_smtp'); ?></h2>
	<form method="post" action="options.php">
		<?php wp_nonce_field('email-options'); ?>
		<table class="optiontable form-table">
			<tr valign="top">
				<th scope="row"><label for="mail_from"><?php _e('From Email', 'wds_smtp'); ?></label></th>
				<td>
					<input name="mail_from" type="text" id="mail_from" value="<?php echo get_option('mail_from'); ?>" size="40" class="regular-text" />
					<span class="description">
						<?php
							_e('You can specify the email address that emails should be sent from. If you leave this blank, the default email will be used.', 'wds_smtp');
							if(get_option('db_version') < 6124) {
								echo '<br /><span style="color: red;">';
								_e('<strong>Please Note:</strong> You appear to be using a version of WordPress prior to 2.3. Please ignore the From Name field and instead enter Name&lt;email@domain.com&gt; in this field.', 'wds_smtp');
								echo '</span>';
							}
						?>
					</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="mail_from_name"><?php _e('From Name', 'wds_smtp'); ?></label>
				</th>
				<td>
					<input name="mail_from_name" type="text" id="mail_from_name" value="<?php echo get_option('mail_from_name'); ?>" size="40" class="regular-text" />
					<span class="description"><?php _e('You can specify the name that emails should be sent from. If you leave this blank, the emails will be sent from WordPress.', 'wds_smtp'); ?></span>
				</td>
			</tr>
		</table>

		<table class="optiontable form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Mailer', 'wds_smtp'); ?> </th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e('Mailer', 'wds_smtp'); ?></span></legend>
					<p><input id="mailer_smtp" type="radio" name="mailer" value="smtp" <?php checked('smtp', get_option('mailer')); ?> />
					<label for="mailer_smtp"><?php _e('Send all WordPress emails via SMTP.', 'wds_smtp'); ?></label></p>
					<p><input id="mailer_mail" type="radio" name="mailer" value="mail" <?php checked('mail', get_option('mailer')); ?> />
					<label for="mailer_mail"><?php _e('Use the PHP mail() function to send emails.', 'wds_smtp'); ?></label></p>
					</fieldset>
				</td>
			</tr>
		</table>

		<table class="optiontable form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Return Path', 'wds_smtp'); ?> </th>
				<td><fieldset><legend class="screen-reader-text"><span><?php _e('Return Path', 'wds_smtp'); ?></span></legend><label for="mail_set_return_path">
				<input name="mail_set_return_path" type="checkbox" id="mail_set_return_path" value="true" <?php checked('true', get_option('mail_set_return_path')); ?> />
				<?php _e('Set the return-path to match the From Email'); ?></label>
				</fieldset></td>
			</tr>
		</table>

		<h3><?php _e('SMTP Options', 'wds_smtp'); ?></h3>
		<p><?php _e('These options only apply if you have chosen to send mail by SMTP above.', 'wds_smtp'); ?></p>

		<table class="optiontable form-table">
			<tr valign="top">
				<th scope="row"><label for="smtp_host"><?php _e('SMTP Host', 'wds_smtp'); ?></label></th>
				<td><input name="smtp_host" type="text" id="smtp_host" value="<?php echo get_option('smtp_host'); ?>" size="40" class="regular-text" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="smtp_port"><?php _e('SMTP Port', 'wds_smtp'); ?></label></th>
				<td><input name="smtp_port" type="text" id="smtp_port" value="<?php echo get_option('smtp_port'); ?>" size="6" class="regular-text" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Encryption', 'wds_smtp'); ?> </th>
				<td><fieldset><legend class="screen-reader-text"><span><?php _e('Encryption', 'wds_smtp'); ?></span></legend>
				<input id="smtp_ssl_none" type="radio" name="smtp_ssl" value="none" <?php checked('none', get_option('smtp_ssl')); ?> />
				<label for="smtp_ssl_none"><span><?php _e('No encryption.', 'wds_smtp'); ?></span></label><br />
				<input id="smtp_ssl_ssl" type="radio" name="smtp_ssl" value="ssl" <?php checked('ssl', get_option('smtp_ssl')); ?> />
				<label for="smtp_ssl_ssl"><span><?php _e('Use SSL encryption.', 'wds_smtp'); ?></span></label><br />
				<input id="smtp_ssl_tls" type="radio" name="smtp_ssl" value="tls" <?php checked('tls', get_option('smtp_ssl')); ?> />
				<label for="smtp_ssl_tls"><span><?php _e('Use TLS encryption. This is not the same as STARTTLS. For most servers SSL is the recommended option.', 'wds_smtp'); ?></span></label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Authentication', 'wds_smtp'); ?> </th>
				<td>
					<input id="smtp_auth_false" type="radio" name="smtp_auth" value="false" <?php checked('false', get_option('smtp_auth')); ?> />
					<label for="smtp_auth_false"><span><?php _e('No: Do not use SMTP authentication.', 'wds_smtp'); ?></span></label><br />
					<input id="smtp_auth_true" type="radio" name="smtp_auth" value="true" <?php checked('true', get_option('smtp_auth')); ?> />
					<label for="smtp_auth_true"><span><?php _e('Yes: Use SMTP authentication.', 'wds_smtp'); ?></span></label><br />
					<span class="description"><?php _e('If this is set to no, the values below are ignored.', 'wds_smtp'); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="smtp_user"><?php _e('Username', 'wds_smtp'); ?></label></th>
				<td><input name="smtp_user" type="text" id="smtp_user" value="<?php echo get_option('smtp_user'); ?>" size="40" class="code" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="smtp_pass"><?php _e('Password', 'wds_smtp'); ?></label></th>
				<td><input name="smtp_pass" type="text" id="smtp_pass" value="<?php echo get_option('smtp_pass'); ?>" size="40" class="code" /></td>
			</tr>
		</table>

		<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" /></p>
			<input type="hidden" name="action" value="update" />
		</p>
		<input type="hidden" name="option_page" value="email">
	</form>

	<h3><?php _e('Send a Test Email', 'wds_smtp'); ?></h3>
	<form method="POST" action="options-general.php?page=wds_smtp_mail">
		<?php wp_nonce_field('test-email'); ?>
		<table class="optiontable form-table">
			<tr valign="top">
				<th scope="row"><label for="to"><?php _e('To:', 'wds_smtp'); ?></label></th>
				<td><input name="to" type="text" id="to" value="" size="40" class="code" />
					<span class="description"><?php _e('Type an email address here and then click Send Test to generate a test email.', 'wds_smtp'); ?></span>
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" name="wds_smtp_action" id="wds_smtp_action" class="button-primary" value="<?php _e('Send Test', 'wds_smtp'); ?>" />
		</p>
	</form>
</div>
