<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class InstallTest extends TestCase {

    /** @var GuzzleHttp\Client */
    protected $client = null;

    public function setUp()
    {
        $this->client = new Client([
            'base_uri'        => 'http://127.0.0.1:8081',
            'timeout'         => 1,
        ]);

        $this->removeCustomFiles();
    }

    public function tearDown()
    {
        $this->removeCustomFiles();
    }

    protected function removeCustomFiles()
    {
        $toRemove = [
            custom_path('config.yml'),
            custom_path('people.opml'),
            custom_path('people.opml.bak'),
            custom_path('cache')
        ];

        foreach ($toRemove as $path) {
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

    public function test_index_page_tells_moonmoon_is_not_installed()
    {
        $res = $this->client->get('/index.php');
        $this->assertEquals(200, $res->getStatusCode());
        $this->assertContains('install moonmoon', (string) $res->getBody());
    }

    public function test_install_page_loads_without_error()
    {
        $res = $this->client->get('/install.php');
        $this->assertEquals(200, $res->getStatusCode());
        $this->assertContains('Administrator password', (string) $res->getBody());
    }

    /**
     * Regression test, `people.opml` was created by requesting `/install.php`
     * even if the site was not installed: `touch()` was called to see if
     * the path was writable but the file was not removed.
     */
    public function test_get_install_page_should_not_create_custom_files()
    {
        $this->client->get('/install.php');
        $this->assertFalse(file_exists(custom_path('people.opml')));
        $this->assertFalse(file_exists(custom_path('config.yml')));
        $this->assertFalse(file_exists(custom_path('inc/pwc.inc.php')));
    }

    public function test_install_button()
    {
        $data = [
            'url' => 'http://127.0.0.1:8081/',
            'title'	=> 'My website',
            'password' => 'admin',
            'locale' => 'en',
        ];

        $res = $this->client->request('POST', '/install.php', [
            'form_params' => $data
        ]);
        $this->assertEquals(200, $res->getStatusCode());
        $this->assertContains('Your moonmoon is ready.', (string) $res->getBody());
    }
}