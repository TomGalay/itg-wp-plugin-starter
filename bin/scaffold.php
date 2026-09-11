<?php
/**
 * Rename this starter into a new plugin.
 *
 * Usage:
 *   php bin/scaffold.php
 *   php bin/scaffold.php --name="Acme Widgets" --slug=acme-widgets --author="Acme Inc"
 *
 * Run with --dry-run first to review the planned changes.
 *
 * @package ITG_Plugin_Setup
 */

declare( strict_types = 1 );

$root = dirname( __DIR__ );

$options = getopt(
	'',
	array(
		'name:',
		'slug:',
		'prefix:',
		'const-prefix:',
		'class-prefix:',
		'vendor:',
		'author:',
		'author-uri:',
		'plugin-uri:',
		'description:',
		'dry-run',
		'force',
		'remove-template',
		'verify',
		'no-lock-sync',
		'no-format',
		'help',
	)
);

if ( isset( $options['help'] ) ) {
	fwrite( STDOUT, usage() );
	exit( 0 );
}

$input = read_input( $options, $root );

$slug         = $input['slug'];
$display_name = $input['name'];
$prefix       = $input['prefix'];
$const_prefix = $input['const_prefix'];
$class_prefix = $input['class_prefix'];

$is_dry_run = isset( $options['dry-run'] );
$is_forced  = isset( $options['force'] );

out( '' );
out( 'Scaffolding plugin' );
out( '------------------' );
out( "  Name          : {$display_name}" );
out( "  Slug          : {$slug}" );
out( "  Text domain   : {$slug}" );
out( "  PHP prefix    : {$prefix}" );
out( "  Constant      : {$const_prefix}_" );
out( "  Class prefix  : {$class_prefix}" );
out( "  Author        : {$input['author']}" );
out( '' );

if ( $is_dry_run ) {
	out( 'DRY RUN - no files will be modified.' );
	out( '' );
}

$replacements = build_replacements( $input );
$files        = collect_files( $root );

/* Content replacements. */
$changed = 0;
foreach ( $files as $file ) {
	$contents = file_get_contents( $file );

	if ( false === $contents || false !== strpos( $contents, "\0" ) ) {
		continue; // Unreadable or binary.
	}

	$updated = apply_replacements( $contents, $replacements, $input );

	if ( $updated !== $contents ) {
		++$changed;
		if ( ! $is_dry_run ) {
			file_put_contents( $file, $updated );
		}
	}
}

out( "Files updated: {$changed}" );

/* File renames. */
$renames = collect_renames( $root, $slug );

if ( empty( $renames ) ) {
	out( 'Files renamed: 0' );
} else {
	foreach ( $renames as $rename ) {
		$from = relative( $root, $rename['from'] );
		$to   = relative( $root, $rename['to'] );
		out( "  rename  {$from} -> {$to}" );

		if ( ! $is_dry_run ) {
			if ( file_exists( $rename['to'] ) && ! $is_forced ) {
				fwrite( STDERR, "Refusing to overwrite existing file: {$to}\n" );
				exit( 1 );
			}
			rename( $rename['from'], $rename['to'] );
		}
	}

	out( 'Files renamed: ' . count( $renames ) );
}

out( '' );
out( 'Done.' );
out( '' );
out( 'Next steps:' );
out( '  1. Rename the plugin directory to "' . $slug . '".' );
out( '  2. composer update --lock' );
out( '  3. npm install' );
out( '  4. npm run build' );
out( '  5. composer lint && composer test' );
out( '' );
out( 'If you cloned a fresh copy, and only ever scaffold once,' );
out( 're-run this command with --remove-template to delete the scaffolder.' );
out( '' );

if ( ! $is_dry_run && ! isset( $options['no-lock-sync'] ) ) {
	sync_lockfiles( $root );
}

if ( ! $is_dry_run && ! isset( $options['no-format'] ) ) {
	run_formatter( $root );
}

if ( ! $is_dry_run ) {
	self_check( $root );
}

if ( ! $is_dry_run && isset( $options['verify'] ) ) {
	verify( $root );
}

if ( ! $is_dry_run && isset( $options['remove-template'] ) ) {
	remove_template( $root );
}

/**
 * Print usage information.
 *
 * @return string
 */
function usage(): string {
	return <<<TXT
Rename the ITG WP Plugin Starter into a new plugin.

Usage:
  php bin/scaffold.php [options]

Options:
  --name=STRING          Plugin display name (e.g. "Acme Widgets").
  --slug=STRING          Plugin slug / text domain (e.g. "acme-widgets").
  --prefix=STRING        PHP function/variable prefix (default: slug, underscores).
  --const-prefix=STRING  Constant prefix without trailing underscore.
  --class-prefix=STRING  Class prefix (e.g. "Acme_Widgets").
  --vendor=STRING        Composer vendor name (default: "itg").
  --author=STRING        Author name.
  --author-uri=URL       Author URI.
  --plugin-uri=URL       Plugin URI.
  --description=STRING   Short description.
  --dry-run              Show what would change without writing anything.
  --force                Overwrite existing files during renames.
  --remove-template      Delete the scaffolder and its composer script after success.
  --verify               Run "composer lint" and "npm run build" at the end.
  --no-lock-sync         Do not run composer/npm lock refreshes.
  --no-format            Do not run PHPCBF after renaming.
  --help                 Show this help.

TXT;
}

/**
 * Collect and normalise the scaffolder input.
 *
 * @param array<string, mixed> $options Parsed CLI options.
 * @param string               $root    Repository root.
 * @return array<string, string>
 */
function read_input( array $options, string $root ): array {
	$name = value_or_prompt( $options, 'name', 'Plugin name', 'My Plugin' );

	$slug_default = sanitize_slug( $name );
	$slug         = value_or_prompt( $options, 'slug', 'Plugin slug / text domain', $slug_default );

	$prefix_default = str_replace( '-', '_', $slug );
	$prefix         = value_or_prompt( $options, 'prefix', 'PHP prefix', $prefix_default );

	$const_default = strtoupper( $prefix );
	$const         = value_or_prompt( $options, 'const-prefix', 'Constant prefix', $const_default );

	$class_default = studly( $prefix );
	$class         = value_or_prompt( $options, 'class-prefix', 'Class prefix', $class_default );

	$vendor = value_or_prompt( $options, 'vendor', 'Composer vendor', 'itg' );

	$author_default = git_user_name();
	$author         = value_or_prompt( $options, 'author', 'Author', '' !== $author_default ? $author_default : 'Your Name' );

	$author_uri = value_or_prompt( $options, 'author-uri', 'Author URI', 'https://example.com' );
	$plugin_uri = value_or_prompt( $options, 'plugin-uri', 'Plugin URI', 'https://example.com/' . $slug );
	$desc       = value_or_prompt( $options, 'description', 'Description', 'A WordPress plugin built with the ITG WP Plugin Starter.' );

	return array(
		'name'         => $name,
		'slug'         => $slug,
		'prefix'       => $prefix,
		'const_prefix' => $const,
		'class_prefix' => $class,
		'vendor'       => $vendor,
		'author'       => $author,
		'author_uri'   => $author_uri,
		'plugin_uri'   => $plugin_uri,
		'description'  => $desc,
	);
}

/**
 * Return a CLI value, prompting interactively when absent.
 *
 * @param array<string, mixed> $options  Parsed CLI options.
 * @param string               $key      Option key.
 * @param string               $label    Prompt label.
 * @param string               $fallback Default value.
 * @return string
 */
function value_or_prompt( array $options, string $key, string $label, string $fallback ): string {
	if ( isset( $options[ $key ] ) && is_string( $options[ $key ] ) && '' !== trim( $options[ $key ] ) ) {
		return trim( $options[ $key ] );
	}

	if ( ! is_interactive() ) {
		return $fallback;
	}

	$prompt = '' !== $fallback ? "{$label} [{$fallback}]: " : "{$label}: ";
	fwrite( STDOUT, $prompt );

	$answer = fgets( STDIN );

	if ( false === $answer || '' === trim( $answer ) ) {
		return $fallback;
	}

	return trim( $answer );
}

/**
 * Whether the CLI is running interactively.
 *
 * @return bool
 */
function is_interactive(): bool {
	return function_exists( 'stream_isatty' ) ? stream_isatty( STDIN ) : true;
}

/**
 * Build the ordered replacement map (placeholder-protected).
 *
 * @param array<string, string> $input Normalised input.
 * @return array<int, array{0:string,1:string}>
 */
function build_replacements( array $input ): array {
	// Specific tokens must be replaced before shorter/generic ones.
	$js_global = lcfirst( str_replace( '_', '', $input['class_prefix'] ) ) . 'Setup';

	return array(
		array( 'itg/itg-plugin-setup', $input['vendor'] . '/' . $input['slug'] ),
		array( 'itg-plugin-setup', $input['slug'] ),
		array( 'itg_plugin_setup', $input['prefix'] ),
		array( 'ITG_PLUGIN_SETUP', $input['const_prefix'] ),
		array( 'ITG_Plugin_Setup', $input['class_prefix'] ),
		array( 'ITG Plugin Setup', $input['name'] ),
		array( 'ITG WP Plugin Starter', $input['name'] ),
		array( 'itgPluginSetup', $js_global ),
	);
}

/**
 * Apply the placeholder-protected replacements to a string.
 *
 * @param string                         $contents     File contents.
 * @param array<int, array{0:string,1:string}> $replacements Replacement map.
 * @param array<string, string>          $input        Normalised input.
 * @return string
 */
function apply_replacements( string $contents, array $replacements, array $input ): string {
	// Protect URIs and author values first so the generic tokens cannot touch them.
	$contents = preg_replace( '/(Plugin URI:\s*)https:\/\/example\.com\/itg-plugin-setup(\r?\n)/', '$1%%PLUGIN_URI%%$2', $contents );
	$contents = preg_replace( '/(Author URI:\s*)https:\/\/example\.com(\r?\n)/', '$1%%AUTHOR_URI%%$2', $contents );
	$contents = preg_replace( '/(Author:\s*)ITG(\r?\n)/', '$1%%AUTHOR%%$2', $contents );
	$contents = preg_replace( '/(Contributors:\s*)itg(\r?\n)/', '$1%%CONTRIBUTOR%%$2', $contents );
	$contents = preg_replace( '/("author":\s*")ITG(")/', '${1}%%AUTHOR%%${2}', $contents );

	foreach ( $replacements as $pair ) {
		$contents = str_replace( $pair[0], $pair[1], $contents );
	}

	$contents = str_replace(
		array( '%%PLUGIN_URI%%', '%%AUTHOR_URI%%', '%%AUTHOR%%', '%%CONTRIBUTOR%%' ),
		array( $input['plugin_uri'], $input['author_uri'], $input['author'], sanitize_slug( $input['author'] ) ),
		$contents
	);

	// Apply the description to the plugin header and package metadata.
	$contents = preg_replace(
		'/(Description:\s*)A WordPress plugin foundation with a pluggable integrations layer \(Firebase, Email\) and WPCS tooling\./',
		'$1' . $input['description'],
		$contents
	);

	return $contents;
}

/**
 * Recursively collect files eligible for replacement.
 *
 * @param string $root Repository root.
 * @return array<int, string>
 */
function collect_files( string $root ): array {
	$skip    = array( 'vendor', 'node_modules', 'build', '.git', 'coverage', '.github' );
	$exclude = array( relative( $root, __FILE__ ) );
	$files   = array();

	$iterator = new RecursiveIteratorIterator(
		new RecursiveCallbackFilterIterator(
			new RecursiveDirectoryIterator( $root, FilesystemIterator::SKIP_DOTS ),
			static function ( SplFileInfo $current ) use ( $skip ): bool {
				return ! ( $current->isDir() && in_array( $current->getFilename(), $skip, true ) );
			}
		)
	);

	foreach ( $iterator as $file ) {
		if ( ! $file->isFile() ) {
			continue;
		}

		$relative = relative( $root, $file->getPathname() );

		if ( in_array( $relative, $exclude, true ) ) {
			continue;
		}

		$files[] = $file->getPathname();
	}

	return $files;
}

/**
 * Determine which files must be renamed.
 *
 * @param string $root Repository root.
 * @param string $slug Target slug.
 * @return array<int, array{from:string,to:string}>
 */
function collect_renames( string $root, string $slug ): array {
	$renames = array();
	$files   = collect_files( $root );

	// Deepest paths first so parent renames do not invalidate child paths.
	usort(
		$files,
		static function ( string $a, string $b ): int {
			return strlen( $b ) <=> strlen( $a );
		}
	);

	foreach ( $files as $file ) {
		$basename = basename( $file );

		if ( false === strpos( $basename, 'itg-plugin-setup' ) ) {
			continue;
		}

		$renames[] = array(
			'from' => $file,
			'to'   => dirname( $file ) . DIRECTORY_SEPARATOR . str_replace( 'itg-plugin-setup', $slug, $basename ),
		);
	}

	return $renames;
}

/**
 * Refresh Composer and npm lock files when the tooling is available.
 *
 * @param string $root Repository root.
 * @return void
 */
function sync_lockfiles( string $root ): void {
	out( '' );
	out( 'Refreshing lock files...' );

	if ( is_command_available( 'composer' ) ) {
		passthru( 'composer update --lock --no-interaction --working-dir=' . escapeshellarg( $root ), $code );
		out( '  composer update --lock: ' . ( 0 === $code ? 'ok' : 'skipped/failed' ) );
	}

	if ( is_command_available( 'npm' ) ) {
		$command = 'npm install --package-lock-only --ignore-scripts --no-audit --no-fund --prefix=' . escapeshellarg( $root );
		passthru( $command, $code );
		out( '  npm install --package-lock-only: ' . ( 0 === $code ? 'ok' : 'skipped/failed' ) );
	}
}

/**
 * Report any leftover template tokens.
 *
 * @param string $root Repository root.
 * @return void
 */
function self_check( string $root ): void {
	$tokens = array( 'itg-plugin-setup', 'itg_plugin_setup', 'ITG_PLUGIN_SETUP', 'ITG_Plugin_Setup', 'ITG Plugin Setup' );
	$files  = collect_files( $root );
	$hits   = array();

	foreach ( $files as $file ) {
		if ( basename( $file ) === 'scaffold.php' ) {
			continue;
		}

		$contents = file_get_contents( $file );

		if ( false === $contents || false !== strpos( $contents, "\0" ) ) {
			continue;
		}

		foreach ( $tokens as $token ) {
			if ( false !== strpos( $contents, $token ) ) {
				$hits[] = relative( $root, $file ) . ' -> ' . $token;
				break;
			}
		}
	}

	out( '' );
	if ( empty( $hits ) ) {
		out( 'Self-check: no leftover template tokens.' );
		return;
	}

	out( 'Self-check: leftover template tokens found (review these):' );
	foreach ( $hits as $hit ) {
		out( '  ' . $hit );
	}
}

/**
 * Run PHPCBF to realign formatting after identifiers change length.
 *
 * @param string $root Repository root.
 * @return void
 */
function run_formatter( string $root ): void {
	$phpcbf = $root . '/vendor/bin/phpcbf';

	if ( 'Windows' === PHP_OS_FAMILY ) {
		$phpcbf .= '.bat';
	}

	if ( ! file_exists( $phpcbf ) ) {
		out( '' );
		out( 'PHPCBF not found; run "composer lint:fix" once dependencies are installed.' );
		return;
	}

	out( '' );
	out( 'Formatting renamed files with PHPCBF...' );

	$previous = getcwd();
	chdir( $root );
	passthru( escapeshellarg( $phpcbf ) . ' -q', $code );
	chdir( $previous );

	out( '  phpcbf: ' . ( 0 === $code || 1 === $code ? 'done' : 'reported issues' ) );
}

/**
 * Run the verification commands.
 *
 * @param string $root Repository root.
 * @return void
 */
function verify( string $root ): void {
	out( '' );
	out( 'Verifying...' );

	passthru( 'composer lint --working-dir=' . escapeshellarg( $root ), $lint_code );
	passthru( 'npm run build --prefix=' . escapeshellarg( $root ), $build_code );

	out( '  composer lint : ' . ( 0 === $lint_code ? 'ok' : 'failed' ) );
	out( '  npm run build : ' . ( 0 === $build_code ? 'ok' : 'failed' ) );
}

/**
 * Delete the scaffolder and its composer script.
 *
 * @param string $root Repository root.
 * @return void
 */
function remove_template( string $root ): void {
	$script = __FILE__;

	if ( file_exists( $script ) ) {
		unlink( $script );
	}

	$bin_dir = dirname( $script );

	if ( is_dir( $bin_dir ) && count( scandir( $bin_dir ) ) === 2 ) {
		rmdir( $bin_dir );
	}

	$composer_path = $root . DIRECTORY_SEPARATOR . 'composer.json';

	if ( file_exists( $composer_path ) ) {
		$composer = json_decode( file_get_contents( $composer_path ), true );

		if ( is_array( $composer ) && isset( $composer['scripts']['scaffold'] ) ) {
			unset( $composer['scripts']['scaffold'] );
			file_put_contents(
				$composer_path,
				json_encode( $composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . "\n"
			);
			out( 'Removed the "scaffold" composer script.' );
		}
	}

	out( 'Template scaffolding removed.' );
}

/**
 * Check whether a command exists on the PATH.
 *
 * @param string $command Command name.
 * @return bool
 */
function is_command_available( string $command ): bool {
	$probe = 'command -v ' . $command . ' >/dev/null 2>&1';

	if ( 'Windows' === PHP_OS_FAMILY || false !== strpos( PHP_OS, 'WIN' ) ) {
		$probe = 'where ' . $command . ' >NUL 2>&1';
	}

	passthru( $probe, $code );

	return 0 === $code;
}

/**
 * Read the configured git user name, if any.
 *
 * @return string
 */
function git_user_name(): string {
	$output = array();
	$code   = 0;
	exec( 'git config user.name 2>' . ( 'Windows' === PHP_OS_FAMILY ? 'NUL' : '/dev/null' ), $output, $code );

	return 0 === $code && ! empty( $output ) ? trim( implode( ' ', $output ) ) : '';
}

/**
 * Convert a display name into a slug.
 *
 * @param string $value Raw value.
 * @return string
 */
function sanitize_slug( string $value ): string {
	$value = strtolower( trim( $value ) );
	$value = preg_replace( '/[^a-z0-9]+/', '-', $value );

	return trim( (string) $value, '-' );
}

/**
 * Convert an underscore prefix into a Studly_Case prefix.
 *
 * @param string $value Snake_case value.
 * @return string
 */
function studly( string $value ): string {
	return str_replace( ' ', '_', ucwords( str_replace( '_', ' ', strtolower( $value ) ) ) );
}

/**
 * Return a path relative to the root.
 *
 * @param string $root Repository root.
 * @param string $path Absolute path.
 * @return string
 */
function relative( string $root, string $path ): string {
	$root = rtrim( str_replace( '\\', '/', $root ), '/' ) . '/';
	$path = str_replace( '\\', '/', $path );

	return 0 === strpos( $path, $root ) ? substr( $path, strlen( $root ) ) : $path;
}

/**
 * Write a line to STDOUT.
 *
 * @param string $message Message.
 * @return void
 */
function out( string $message ): void {
	fwrite( STDOUT, $message . PHP_EOL );
}
