<?php











namespace Composer;

use Composer\Semver\VersionParser;






class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => 'dev-master',
    'version' => 'dev-master',
    'aliases' => 
    array (
    ),
    'reference' => '7abfc6305c6f424150a6d6110c311667fc0cec76',
    'name' => 'automattic/newspack-plugin',
  ),
  'versions' => 
  array (
    'automattic/newspack-plugin' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
      ),
      'reference' => '7abfc6305c6f424150a6d6110c311667fc0cec76',
    ),
    'composer/installers' => 
    array (
      'pretty_version' => 'v1.9.0',
      'version' => '1.9.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b93bcf0fa1fccb0b7d176b0967d969691cd74cca',
    ),
    'firebase/php-jwt' => 
    array (
      'pretty_version' => 'v5.2.1',
      'version' => '5.2.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f42c9110abe98dd6cfe9053c49bc86acc70b2d23',
    ),
    'google/auth' => 
    array (
      'pretty_version' => 'v1.15.0',
      'version' => '1.15.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b346c07de6613e26443d7b4830e5e1933b830dc4',
    ),
    'guzzlehttp/guzzle' => 
    array (
      'pretty_version' => '7.3.0',
      'version' => '7.3.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '7008573787b430c1c1f650e3722d9bba59967628',
    ),
    'guzzlehttp/promises' => 
    array (
      'pretty_version' => '1.4.1',
      'version' => '1.4.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '8e7d04f1f6450fef59366c399cfad4b9383aa30d',
    ),
    'guzzlehttp/psr7' => 
    array (
      'pretty_version' => '1.8.1',
      'version' => '1.8.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '35ea11d335fd638b5882ff1725228b3d35496ab1',
    ),
    'joshtronic/php-loremipsum' => 
    array (
      'pretty_version' => '1.0.6',
      'version' => '1.0.6.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f0ec43a045b9bfa5e68cac01805e6312a9e9c7e6',
    ),
    'psr/cache' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd11b50ad223250cf17b86e38383413f5a6764bf8',
    ),
    'psr/http-client' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '2dfb5f6c5eff0e91e20e913f8c5452ed95b86621',
    ),
    'psr/http-client-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/http-message' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f6561bf28d520154e4b0ec72be95418abe6d9363',
    ),
    'psr/http-message-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'ralouphie/getallheaders' => 
    array (
      'pretty_version' => '3.0.3',
      'version' => '3.0.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '120b605dfeb996808c31b6477290a714d356e822',
    ),
    'roundcube/plugin-installer' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
    'shama/baton' => 
    array (
      'replaced' => 
      array (
        0 => '*',
      ),
    ),
  ),
);







public static function getInstalledPackages()
{
return array_keys(self::$installed['versions']);
}









public static function isInstalled($packageName)
{
return isset(self::$installed['versions'][$packageName]);
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

$ranges = array();
if (isset(self::$installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = self::$installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}





public static function getVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['version'])) {
return null;
}

return self::$installed['versions'][$packageName]['version'];
}





public static function getPrettyVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return self::$installed['versions'][$packageName]['pretty_version'];
}





public static function getReference($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['reference'])) {
return null;
}

return self::$installed['versions'][$packageName]['reference'];
}





public static function getRootPackage()
{
return self::$installed['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
}
}
