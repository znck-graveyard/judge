<?php namespace Judge;

use Illuminate\Contracts\Filesystem\Factory;
use Judge\Contracts\Sandbox as SandboxInterface;

/**
 * This file belongs to judge.
 *
 * Author: Rahul Kadyan, <hi@znck.me>
 * Find license in root directory of this project.
 */
class Sandbox implements SandboxInterface
{
    /**
     * @type string
     */
    protected $root;
    /**
     * @type \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $filesystem;
    /**
     * @type string
     */
    protected $name;

    /**
     * Sandbox constructor.
     *
     * @param \Illuminate\Contracts\Filesystem\Factory $filesystemFactory
     */
    public function __construct(Factory $filesystemFactory)
    {
        $this->filesystem = $filesystemFactory->disk('local');
        $this->name = str_random(36);
        $this->root = rtrim(config('filesystems.disks.local.root'), '/');
        $this->refresh();
    }

    /**
     * Destroy sandbox.
     */
    public function __destruct()
    {
        $this->destroy();
    }

    /**
     * @return void
     */
    public function refresh()
    {
        $this->destroy();
        $this->filesystem->makeDirectory('sandboxes/' . $this->name);
    }

    /**
     * @return void
     */
    public function destroy()
    {
        if ($this->filesystem->exists('sandboxes/' . $this->name)) {
            $this->filesystem->deleteDirectory('sandboxes/' . $this->name);
        }
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public function absolutePath($filename)
    {
        return "{$this->root}/sandboxes/{$this->name}/{$filename}";
    }

    /**
     * @param null|string $content
     * @param string      $ext
     *
     * @return string
     */
    public function newFile($content = null, $ext = 'txt')
    {
        $filename = str_random() . ".{$ext}";
        if ($content) {
            $this->put($filename, $content);
        }

        return $filename;
    }

    /**
     * @param $filename
     * @param $content
     *
     * @return void
     */
    public function put($filename, $content)
    {
        $this->filesystem->put("sandboxes/{$this->name}/$filename", $content);

        return $filename;
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public function get($filename)
    {
        return $this->filesystem->get("sandboxes/{$this->name}/$filename");
    }

    /**
     * @return string
     */
    public function rootDirectory()
    {
        return "{$this->root}/sandboxes/{$this->name}";
    }
}