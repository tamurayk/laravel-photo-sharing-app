<?php
declare(strict_types=1);

namespace Test\Unit\app\Http\UseCase\User\Post;

use App\Http\UseCases\User\Post\Exceptions\PostStoreException;
use App\Http\UseCases\User\Post\ImageStore;
use App\Http\UseCases\User\Post\PostStore;
use App\Models\Eloquents\Post;
use App\Models\Eloquents\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\AppTestCase;

class PostStoreTest extends AppTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }


    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @throws PostStoreException
     */
    public function testUseCase()
    {
        factory(User::class)->create([
            'id' => 1,
        ]);

        $this->assertEquals(0, DB::table('posts')->count(), '事前確認');

        $useCase = new PostStore(new Post(), new User(), new ImageStore());
        $data = [
            'caption' => 'a',
            'image' => 'b',
        ];
        $uploadedImage = UploadedFile::fake()->image('image.jpeg');
        $result = $useCase(1, $data, $uploadedImage);
        $this->assertTrue($result);
        $this->assertEquals(1, DB::table('posts')->count());
        $post = DB::table('posts')->where('user_id', 1)->first();
        $this->assertEquals('a', $post->caption);
        $explodedFileName = explode('.', $post->image);
        $this->assertTrue(Str::isUuid($explodedFileName[0]));
        $this->assertEquals('jpeg', $explodedFileName[1]);
    }

    /**
     * @throws PostStoreException
     */
    public function testUseCase_dataが不正な場合は例外がthrowされる事()
    {
        factory(User::class)->create([
            'id' => 1,
        ]);

        $this->expectException(PostStoreException::class);

        $data = [];
        $uploadedImage = UploadedFile::fake()->image('image.jpeg');
        $useCase = new PostStore(new Post(), new User(), new ImageStore());
        $result = $useCase(1, $data, $uploadedImage);
    }

    /**
     * @throws PostStoreException
     */
    public function testUseCase_存在しないuser_idを指定した場合は例外がthrowされる事()
    {
        $this->expectException(PostStoreException::class);

        $data = [
            'caption' => 'a',
            'image' => 'b',
        ];
        $uploadedImage = UploadedFile::fake()->image('image.jpeg');
        $useCase = new PostStore(new Post(), new User(), new ImageStore());
        $result = $useCase(1, $data, $uploadedImage);
    }
}
