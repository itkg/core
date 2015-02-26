<?php

namespace Itkg\Core\Cache\Adapter;

use Itkg\Core\Cache\AdapterAbstract;
use Itkg\Core\Cache\AdapterInterface;
use Itkg\Core\CacheableInterface;
use Itkg\Core\Cache\BadPermissionException;

/**
 * File system cache adapter
 */
class FileSystem extends AdapterAbstract implements AdapterInterface
{
    /**
     * Create cache file as comments to avoid unintended reading
     */
    const STR_PREFIX = '<?php //';

    /**
     * Target directory
     *
     * @var string
     */
    protected $targetDirectory;

    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->createDir($config['storageDir']);

        $this->targetDirectory = realpath($config['storageDir']);
    }

    /**
     * Create directory
     *
     * @param  string $dir
     *
     * @return void
     *
     * @throws \RuntimeException
     * @throws \Itkg\Core\Cache\BadPermissionException
     */
    protected function createDir($dir)
    {
        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            throw new \RuntimeException(
                sprintf('Unable to create cache directory %s, check permissions', $dir)
            );
        }

        if (!is_writable($dir)) {
            throw new BadPermissionException(
                sprintf('Cache directory %s is not writable, check permissions', $dir)
            );
        }
    }

    /**
     * Get targetFile
     *
     * @param string $hashKey
     *
     * @return string
     */
    protected function getTargetFile($hashKey)
    {
        $hashDir = substr($hashKey, -1);

        $targetDir = $this->targetDirectory . DIRECTORY_SEPARATOR . $hashDir;

        $this->createDir($targetDir);

        return sprintf(
            '%s%s%s.cache.php',
            $targetDir,
            DIRECTORY_SEPARATOR,
            $hashKey
        );
    }

    /**
     * Get value from cache
     * Must return false when cache is expired or invalid
     *
     * @param \Itkg\Core\CacheableInterface $item
     *
     * @return mixed
     */
    public function get(CacheableInterface $item)
    {
        $targetFile = $this->getTargetFile($item->getHashKey());

        $return = false;
        if (file_exists($targetFile)) {
            if ((filemtime($filename) + $item->getTtl()) > time()) {
                $this->remove($item);
            } else {
                $return = unserialize(file_get_contents($targetFile, false, null, strlen(self::STR_PREFIX)));
            }
        }

        return $return;
    }

    /**
     * Set a value into the cache
     *
     * @param CacheableInterface $item
     *
     * @return void
     */
    public function set(CacheableInterface $item)
    {
        $targetFile = $this->getTargetFile($item->getHashKey());

        $cacheData = serialize($item->getDataForCache());

        if (!file_put_contents($targetFile, sprintf('%s%s;', self::STR_PREFIX, $cacheData))) {
            throw new \RuntimeException(
                sprintf(
                    'Unable to write cache file %s with data : %s',
                    $targetFile,
                    $cacheData
                )
            );
        }
    }

    /**
     * Remove a value from cache
     *
     * @param \Itkg\Core\CacheableInterface $item
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function remove(CacheableInterface $item)
    {
        $targetFile = $this->getTargetFile($item->getHashKey());
        if (!unlink($targetFile)) {
            throw new \RuntimeException(
                sprintf('Unable to delete %s cache file', $targetFile)
            );
        }
    }

    /**
     * Remove all cache
     *
     * @return void
     */
    public function removeAll()
    {
        $command = sprintf(
            'rm -rf %s%s*',
            rtrim($this->targetDirectory, DIRECTORY_SEPARATOR),
            DIRECTORY_SEPARATOR
        );

        exec($command);
    }
}
