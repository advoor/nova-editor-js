<?php

declare(strict_types=1);

namespace Tests\Unit;

use Advoor\NovaEditorJs\NovaEditorJsCast;
use Advoor\NovaEditorJs\NovaEditorJsData;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Fixtures\Models\Dummy;

class NovaEditorJsCastTest extends TestCase
{
    use RefreshDatabase;

    private const VALID_BLOCK = [
        'time' => 1658064476,
        'blocks' => [
            [
                'type' => 'paragraph',
                'data' => [
                    'text' => 'Paragraph'
                ]
            ]
        ],
        'version' => '2.3.0',
    ];

    /**
     * Test a very basic save-and-decode.
     */
    public function testSavingAndDecoding(): void
    {
        Dummy::create(['data' => self::VALID_BLOCK]);

        $instance = Dummy::first();

        $this->assertInstanceOf(NovaEditorJsData::class, $instance->data);
        $this->assertEquals(self::VALID_BLOCK, $instance->data->getAttributes());
    }

    /**
     * Test saving and decoding a value that's already JSON-encoded.
     */
    public function testSavingPreCompiledJson(): void
    {
        Dummy::create(['data' => json_encode(self::VALID_BLOCK)]);

        $instance = Dummy::first();

        $this->assertInstanceOf(NovaEditorJsData::class, $instance->data);
        $this->assertEquals(self::VALID_BLOCK, $instance->data->getAttributes());
    }

    /**
     * Test decoding a value that's double-JSON-encoded, basically bug mitigation.
     */
    public function testReadingDoubleEncodedJson(): void
    {
        DB::statement('INSERT INTO `dummies` (`data`) VALUES (?)', [json_encode(json_encode(self::VALID_BLOCK))]);

        $instance = Dummy::first();

        $this->assertInstanceOf(NovaEditorJsData::class, $instance->data);
        $this->assertEquals(self::VALID_BLOCK, $instance->data->getAttributes());
    }

    /**
     * Test reading null values in JSON.
     */
    public function testReadingNullValues(): void
    {
        DB::statement('INSERT INTO `dummies` (`data`) VALUES (?), (null)', [json_encode(null)]);

        [$jsonInstance, $nullInstance] = Dummy::get();

        $this->assertNull($jsonInstance->data);
        $this->assertNull($nullInstance->data);
    }

    /**
     * Test writing null values, a json-encoded null value
     * should be stored as-is.
     */
    public function testReadingNullValue(): void
    {
        Dummy::create(['data' => null]);
        Dummy::create(['data' => json_encode(null)]);

        $rows = DB::select('SELECT `id`, `data` FROM `dummies`');

        $this->assertNull($rows[0]->data);
        $this->assertSame('null', $rows[1]->data);
    }

    /**
     * Finally, check if reading broken JSON is handled properly.
     */
    public function testReadingInvalidJson(): void
    {
        DB::statement('INSERT INTO `dummies` (`data`) VALUES (?)', ['{"}']);

        $instance = Dummy::first();

        $this->assertInstanceOf(NovaEditorJsData::class, $instance->data);
        $this->assertNotNull($instance->data->version, 'Expected version key on data');
        $this->assertEquals(NovaEditorJsCast::BROKEN_VERSION, $instance->data->version, 'Expected version to match "broken" version');
    }
}
