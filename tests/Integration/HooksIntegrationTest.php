<?php

declare(strict_types=1);

namespace BoxUk\WpHookAttributes\Tests\Integration;

use BoxUk\WpHookAttributes\WordPressHookAttributes;
use WP_UnitTestCase;

/**
 * @group integration
 */
class HooksIntegrationTest extends WP_UnitTestCase
{
    public function setUp(): void
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
     * Unless we call our resolver directly.
     */
    public function test_attributes_do_work_for_pre_init_hooks_when_calling_resolver_directly(): void
    {
        /*
         * By this point we know the file has been required but the apply_filters() is going to be called pre-init
         * which is where our hook manager will automatically kick in. We can get around this though by calling direct.
         * We can call this as many times as we need.
         */
        (new WordPressHookAttributes())();
        ob_start();
        do_action('muplugins_loaded');
        do_action('init');
        $output = ob_get_clean();

        self::assertNotEmpty($output);
        self::assertEquals('on muplugins_loaded action', $output);
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
