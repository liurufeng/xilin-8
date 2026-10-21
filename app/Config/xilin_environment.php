<?php

/*
 * Which environment this server is ('local' or 'production'), the site's address, and server-only settings.
 *
 * environment.php next to this file can pin the environment: it exists only on a server (gitignored, so deploys never
 * overwrite it) and holds a single line, for example:
 *     <?php return 'production';
 * Without that file the old rule is kept: the host name local.xilin... means this machine, anything else production.
 * public/index.php only turns on the debug pages for 'local' when the request comes from this machine, so a forged
 * Host header can't show them on a server.
 *
 * secrets.php next to this file (also gitignored, server only) can hold passwords and tokens, for example:
 *     <?php return ['db_password' => '...', 'cron_token' => '...'];
 * see xilin_secret(). Until it exists the values that used to be in the code are used.
 */
if (!function_exists('xilin_environment')) {

  // host names the site answers to in production; anything else is refused (public/index.php)
  define('XILIN_HOSTS', array('xilinnschinese.org', 'www.xilinnschinese.org', 'xilinnuc.startlogic.com'));

  function xilin_environment()
  {
    static $environment = null;
    if ($environment !== null) {
      return $environment;
    }

    $file = __DIR__ . '/environment.php';
    if (is_file($file)) {
      $value = include $file;
      if (in_array($value, array('local', 'production'), true)) {
        return $environment = $value;
      }
      error_log('xilin: ' . $file . ' must return local or production; guessing from the Host header');
    }

    return $environment = stripos(xilin_host(), 'local.xilin') === 0 ? 'local' : 'production';
  }

  // the requested host name, lower case and without the port ('' when there is none, e.g. on the command line)
  function xilin_host()
  {
    $host = isset($_SERVER['HTTP_HOST']) ? strtolower($_SERVER['HTTP_HOST']) : '';
    return preg_replace('/:\d+\z/', '', $host);
  }

  // whether the Host header names this site (production: XILIN_HOSTS; local: local.xilin...)
  function xilin_known_host()
  {
    $host = xilin_host();
    if (xilin_environment() === 'local') {
      return strpos($host, 'local.xilin') === 0;
    }
    return in_array($host, XILIN_HOSTS, true);
  }

  // whether this request reached the site over HTTPS (directly, or through a proxy that says so)
  function xilin_is_https()
  {
    if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
      return true;
    }
    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
      return true;
    }
    return isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443';
  }

  // The site's address (links, form actions, redirects): the host the browser used, but only one of the site's own
  // names; anything else gets the main address.
  function xilin_base_url()
  {
    $scheme = xilin_is_https() ? 'https' : 'http';
    if (xilin_environment() === 'local') {
      return $scheme . '://' . (xilin_known_host() ? xilin_host() : 'local.xilin.com') . '/';
    }
    return $scheme . '://' . (xilin_known_host() ? xilin_host() : 'www.xilinnschinese.org') . '/';
  }

  // A server-only setting from secrets.php, or $default when it isn't set there.
  function xilin_secret($key, $default = null)
  {
    static $secrets = null;
    if ($secrets === null) {
      $file = __DIR__ . '/secrets.php';
      $secrets = is_file($file) ? include $file : array();
      if (!is_array($secrets)) {
        error_log('xilin: ' . $file . ' must return an array');
        $secrets = array();
      }
    }
    return array_key_exists($key, $secrets) ? $secrets[$key] : $default;
  }

  // whether a scheduled job may run: from the command line (php public/index.php ...), or with ?p=<cron_token>
  function xilin_cron_allowed()
  {
    if (is_cli()) {
      return true;
    }
    $p = isset($_GET['p']) && is_string($_GET['p']) ? $_GET['p'] : '';
    return $p !== '' && hash_equals((string)xilin_secret('cron_token', 'qDqAxn24w!0@o9!*@o15'), $p);
  }

  // Sessions in a folder of the system temp directory only the site's PHP user can read (instead of loose in a shared
  // folder). Created on first use; if that fails, writable/session is used and a warning logged.
  function xilin_session_dir()
  {
    $fallback = rtrim(WRITEPATH, '/\\') . DIRECTORY_SEPARATOR . 'session';
    if (PHP_SAPI === 'cli') {
      return $fallback;
    }
    $dir = rtrim(sys_get_temp_dir(), '/\\') . DIRECTORY_SEPARATOR . 'xilin_sessions';
    if (!is_dir($dir)) {
      @mkdir($dir, 0700);
    }
    $ok = is_dir($dir) && !is_link($dir) && is_writable($dir);
    if ($ok && function_exists('posix_geteuid')) {
      $ok = fileowner($dir) === posix_geteuid() && (fileperms($dir) & 0077) === 0;
    }
    if (!$ok) {
      error_log('xilin: can\'t use ' . $dir . ' (missing, not writable, or not private to this user); sessions stay in ' . $fallback);
      return $fallback;
    }
    return $dir;
  }
}
