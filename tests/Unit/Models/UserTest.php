<?php

namespace Tests\Unit\Models;

use App\Models\Models\Lesson;
use App\Models\User;
use App\Models\Models\UserProfile;
use Mockery;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }
    /**
     *
     * @dataProvider dataCanReserve_正常
     */
    public function testCanReserve_正常(string $plan, int $remainingCount, int $reservationCount)
    {
        //$user=new User();
        $user = Mockery::mock(User::class)->makePartial();
        $user->shouldReceive('reservationCountThisMonth')->andReturn($reservationCount);


        $user->profile = new UserProfile();
        $user->profile->plan = $plan;

        $lesson=Mockery::mock(Lesson::class);
        $lesson->shouldReceive('remainingCount')->andReturn($remainingCount);
        //$lesson->shouldReceive('remainingCount')->andReturn($remainingCount);


        $user->canReserve($lesson);
        // 例外が出ないことを確認するアサーションがないので代わりに
        $this->assertTrue(true);
    }
    public function dataCanReserve_正常()
    {
        return [
            '予約可:レギュラー,空きあり,月の上限以下' => [
                'plan' => 'regular',
                'remainingCount' => 1,
                'reservationCount' => 4,
            ],
            '予約可:ゴールド,空きあり' => [
                'plan' => 'gold',
                'remainingCount' => 1,
                'reservationCount' => 5,
            ],
        ];
    }
    /**
     * @param string $plan
     * @param int $remainingCount
     * @param int $reservationCount
     * @param string $errorMessage
     * @dataProvider dataCanReserve_エラー
     */
    public function testCanReserve_エラー(string $plan,int $remainingCount,int $reservationCount, string $errorMessage){
        //$user=new User();
        $user = Mockery::mock(User::class)->makePartial();
        $user->shouldReceive('reservationCountThisMonth')->andReturn($reservationCount);

        $user->profile = new UserProfile();
        $user->profile->plan = $plan;

        $lesson=Mockery::mock(Lesson::class);
        $lesson->shouldReceive('remainingCount')->andReturn($remainingCount);

        $this->expectExceptionMessage($errorMessage);
        $user->canReserve($lesson);
    }
    public function dataCanReserve_エラー(){
        return [
            '予約不可:レギュラー,空きあり,月の上限' => [
                'plan' => 'regular',
                'remainingCount' => 1,
                'reservationCount' => 5,
                'errorMessage' => '今月の予約がプランの上限に達しています。',
            ],
            '予約不可:レギュラー,空きなし,月の上限以下' => [
                'plan' => 'regular',
                'remainingCount' => 0,
                'reservationCount' => 4,
                'errorMessage' => 'レッスンの予約可能上限に達しています。',
            ],
            '予約不可:ゴールド,空きなし' => [
                'plan' => 'gold',
                'remainingCount' => 0,
                'reservationCount' => 5,
                'errorMessage' => 'レッスンの予約可能上限に達しています。',
            ],
        ];
    }
}
/*
namespace Tests\Unit\Models;

use App\Models\Lesson;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param string $plan
     * @param int $capacity
     * @param int $totalReservationCount
     * @param int $userReservationCount
     * @param bool $canReserve
     * @dataProvider dataCanReserve
     *//*
    public function testCanReserve(string $plan, int $remainingCount, int $totalReservationCount, int $userReservationCount, bool $canReserve)
    {
        $user = new User();
        $user->plan = $plan;

        $lesson = Lesson::factory()->create(['capacity' => $capacity]);
        $lesson->reservations()->saveMany(Reservation::factory($totalReservationCount)->make());

        $this->assertSame($canReserve, $user->canReserve($lesson, $userReservationCount));
    }

    public function dataCanReserve()
    {
        return [
            '予約可:レギュラー,空きあり,月の上限以下' => [
                'plan' => 'regular',
                'capacity' => 2,
                'totalReservationCount' => 1,
                'userReservationCount' => 4,
                'canReserve' => true,
            ],
        ];
    }
}*/
