<?php
/**
 * Local development only: send all WordPress mail to the Mailpit container.
 * Open http://localhost:8025 to read it. Loaded via docker-compose.yml, never shipped with the themes.
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'phpmailer_init',
	static function ( $mailer ) {
		$mailer->isSMTP();
		$mailer->Host     = 'mailpit';
		$mailer->Port     = 1025;
		$mailer->SMTPAuth = false;
	}
);

/** "wordpress@localhost" is rejected as invalid, so give local mail a sender with a real-looking domain. */
add_filter(
	'wp_mail_from',
	static fn( string $from ) => str_ends_with( $from, '@localhost' ) || str_contains( $from, '@localhost:' ) ? 'wordpress@bapstudio.test' : $from
);
