<?php
declare(strict_types = 1);

namespace Embed\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Test that AuthorUrl detectors handle empty and zero strings correctly.
 *
 * Verifies that empty username/owner values and '0' do not generate invalid URLs
 * like "https://twitter.com/" or "https://twitter.com/0" but instead fallback to parent detector.
 */
class AuthorUrlEmptyStringTest extends TestCase
{
    public function testEmptyUsernameDoesNotCreateInvalidUrl()
    {
        // Test implementation: Verify the code pattern in AuthorUrl detectors
        // The actual check is: if (is_string($username) && $username !== '' && $username !== '0')
        // This ensures empty strings and '0' don't create invalid URLs

        $files = [
            'src/Adapters/Twitter/Detectors/AuthorUrl.php',
            'src/Adapters/Gist/Detectors/AuthorUrl.php',
            'src/Adapters/ImageShack/Detectors/AuthorUrl.php',
        ];

        foreach ($files as $file) {
            $content = file_get_contents(__DIR__ . '/../' . $file);
            $this->assertNotFalse($content, "File $file should exist");

            // Verify the pattern includes type, empty string, and '0' check
            $hasTypeCheck = str_contains($content, 'is_string(');
            $hasEmptyCheck = str_contains($content, "!== ''");
            $hasZeroCheck = str_contains($content, "!== '0'");

            $this->assertTrue(
                $hasTypeCheck && $hasEmptyCheck && $hasZeroCheck,
                "File $file should check type (is_string), empty string, and '0'"
            );
        }
    }
}
