<?php
declare(strict_types=1);

namespace App\Models\Interfaces;

use App\Models\Eloquents\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Interface PostInterface
 * @package App\Models\Interfaces
 * @property int $id
 * @property int $user_id
 * @property string $image
 * @property string $caption
 * @property User $user
 */
interface PostInterface extends BaseInterface
{
    public function user(): BelongsTo;
}
