<?php

declare(strict_types=1);

/**
 * @group integration
 */
class HooksIntegrationTest extends WP_UnitTestCase
{
    public function setUp()
    {
        // Makes the tests much faster.
        tests_add_filter('wp_hook_attributes_registered_namespaces', function () {
            return [
                'BoxUk\WpHookAttributes\Tests\Integration\Resources',
            ];
        });
        parent::setUp();
    }

    /**
     * Unfortunately any hooks prior to `init` will not work.
     */
    public function test_attributes_do_not_work_for_pre_init_hooks(): void
    {
        ob_start();
        do_action('muplugins_loaded');
        do_action('init');
        $output = ob_get_clean();

        self::assertEmpty($output);
    }

    /**
     * Any hooks post init should work as expected.
     */
    public function test_attributes_work_for_post_init_hooks(): void
    {
        ob_start();
        do_action('init');
        do_action('wp_loaded');
        $output = ob_get_clean();

        self::assertNotEmpty($output);
        self::assertEquals('on wp_loaded action', $output);
    }

    /**
     * If not required by the auotloader or implicitly elsewhere, they can be manually registered. They must be
     * registered before the `init()` method is called though.
     */
    public function test_attributes_work_for_hooks_when_manually_registered_before_init(): void
    {
        tests_add_filter('wp_hook_attributes_registered_function_files', function () {
            return [
                __DIR__ . '/Resources/required-post-init-functions.php',
            ];
        });

        ob_start();
        do_action('init');
        do_action('wp');
        $output = ob_get_clean();

        self::assertNotEmpty($output);
        self::assertEquals('on wp action', $output);
    }
}
