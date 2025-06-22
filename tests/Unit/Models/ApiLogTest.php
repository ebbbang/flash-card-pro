<?php

namespace Tests\Unit\Models;

use App\Models\ApiLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class ApiLogTest extends TestCase
{
    public function test_model_extends_base_model()
    {
        $extends = get_parent_class(ApiLog::class);

        $this->assertSame(Model::class, $extends);
    }

    public function test_model_has_traits()
    {
        $traits = class_uses(ApiLog::class);

        $this->assertSame(
            [HasFactory::class],
            array_keys($traits)
        );
    }

    public function test_model_has_fillable_properties()
    {
        $fillable = (new ApiLog)->getFillable();

        $this->assertSame(
            [
                'user_id',
                'url',
                'method',
                'ip',
            ],
            $fillable
        );
    }

    public function test_model_belongs_to_user()
    {
        $apiLog = new ApiLog;

        $this->assertInstanceOf(BelongsTo::class, $apiLog->user());
        $this->assertInstanceOf(User::class, $apiLog->user()->getModel());
    }
}
