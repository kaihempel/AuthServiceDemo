<?php namespace App\Model\Facebook\OAuth;

use Facebook\PersistentData\PersistentDataInterface;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Log;

/**
 * Class PersistentDataHandler
 * @package App\Model\Facebook\OAuth
 */
class PersistentDataHandler implements PersistentDataInterface
{
    /**
     * @var string
     */
    private $prefix = 'FBSTORAGE_';

    /**
     * @var Store
     */
    private $session;

    /**
     * PersistentDataHandler constructor.
     * @param Store $session
     */
    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * Get a value from a persistent data store.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->session->get($this->prefix . $key);
    }

    /**
     * Set a value in the persistent data store.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function set($key, $value)
    {
        $this->session->put($this->prefix . $key, $value);
    }
}