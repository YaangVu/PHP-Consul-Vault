<?php
/**
 * @Author yaangvu
 * @Date   Apr 03, 2023
 */

namespace YaangVu\PhpConsulVault\Test;

use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use YaangVu\PhpConsulVault\Vault\Auth\TokenAuthStrategy;
use YaangVu\PhpConsulVault\Vault\Auth\UserPassAuthStrategy;
use YaangVu\PhpConsulVault\Vault\Dto\DbConnectionDetail\ConnectionDetails;
use YaangVu\PhpConsulVault\Vault\Service\Database;
use YaangVu\PhpConsulVault\Vault\Service\KV;
use YaangVu\PhpConsulVault\Vault\Vault;

class VaultTest extends TestCase
{
    private string $token    = 'hvs.8mzQfdiLtW0ncmpcMjc5QlOv';
    private array  $userPass = ['username' => 'foo', 'password' => 'bar'];
    private string $dbPath   = '/database';

    /**
     * @throws GuzzleException
     */
    public function testAuthUserPass()
    {
        $auth  = new UserPassAuthStrategy($this->userPass['username'], $this->userPass['password']);
        $vault = new Vault();
        $this->assertIsString($vault->authenticate($auth)->token);
    }

    /**
     * @throws GuzzleException
     */
    public function testAuthToken()
    {
        $vault         = new Vault();
        $authenticated = $vault->authenticate(new TokenAuthStrategy($this->token));
        $this->assertEquals($authenticated->token, $this->token);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetSecretKeysV1()
    {
        $vault = new Vault();
        $vault->authenticate(new TokenAuthStrategy($this->token));
        $kv   = new KV($vault, 1);
        $keys = $kv->keys('kv1/data');
        $this->assertIsArray($keys);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetSecretKeysV2()
    {
        $vault = new Vault();
        $vault->authenticate(new TokenAuthStrategy($this->token));
        $kv   = new KV($vault, 2);
        $keys = $kv->keys('kv2/data');
        $this->assertIsArray($keys);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetSecrets()
    {
        $vault = new Vault();
        $vault->authenticate(new TokenAuthStrategy($this->token));
        $kv      = new KV($vault, '1');
        $secrets = $kv->secrets('kv1/data/data');
        $this->assertIsArray($secrets);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetSecretValue()
    {
        $vault = new Vault();
        $vault->authenticate(new TokenAuthStrategy($this->token));
        $kv    = new KV($vault);
        $value = $kv->value('/kv1/data/data/foo');
        $this->assertEquals('bar', $value);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetListDBConnections()
    {
        $vault = new Vault();
        $vault->authenticate(new TokenAuthStrategy($this->token));
        $db          = new Database($vault);
        $connections = $db->list('database');
        $this->assertIsArray($connections);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetDBConnectionDetail()
    {
        $vault = new Vault();
        $vault->authenticate(new TokenAuthStrategy($this->token));
        $db          = new Database($vault);
        $connections = $db->get('database/ArtemisMariaDB');
        $this->assertInstanceOf(ConnectionDetails::class, $connections);
    }

}