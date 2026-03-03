<?php
/**
 * Project root path. Use for includes and file paths so the app works from any directory depth.
 * Define once at root; in app subdirs use dirname(__DIR__, N) or require this from a known location.
 */
if (!defined('APP_ROOT')) {
  define('APP_ROOT', dirname(__DIR__));
}
