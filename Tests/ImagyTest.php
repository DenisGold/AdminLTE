<?php  namespace Modules\Media\Tests;

use Illuminate\Support\Facades\App;
use Modules\Core\Tests\BaseTestCase;
use Modules\Media\Image\Imagy;
use Modules\Media\Image\Intervention\InterventionFactory;
use Modules\Media\Image\ThumbnailsManager;

class ImagyTest extends BaseTestCase
{
    /**
     * @var Imagy
     */
    protected $imagy;
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $finder;

    public function setUp()
    {
        parent::setUp();
        $config = App::make('Illuminate\Contracts\Config\Repository');
        $module = App::make('Pingpong\Modules\Module');
        $this->finder = App::make('Illuminate\Filesystem\Filesystem');
        $this->imagy = new Imagy(new InterventionFactory, new ThumbnailsManager($config, $module));
    }

    /** @test */
    public function it_should_create_a_file()
    {
        $this->finder->delete(public_path() . '/assets/media/google-map_smallThumb.png');

        $this->imagy->get('/assets/media/google-map.png', 'smallThumb', true);

        $this->assertTrue($this->finder->isFile(public_path() . '/assets/media/google-map_smallThumb.png'));
    }

    /** @test */
    public function it_should_not_create_thumbs_for_pdf_files()
    {
        $this->imagy->get('/assets/media/test-pdf.pdf', 'smallThumb', true);

        $this->assertFalse($this->finder->isFile(public_path() . '/assets/media/test-pdf_smallThumb.png'));
    }
}
