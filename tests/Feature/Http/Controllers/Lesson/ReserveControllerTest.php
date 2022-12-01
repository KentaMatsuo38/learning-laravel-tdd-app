<?php

namespace Tests\Feature\Http\Controllers\Lesson;

use App\Notifications\ReservationCompleted;
use App\Http\Controllers\Lesson\ReserveController;
use App\Models\Models\Lesson;
use App\Models\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\Factories\Traits\CreatesUser;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ReserveControllerTest extends TestCase
{
    use RefreshDatabase;
    use CreatesUser;

    public function testInvoke_正常系()
    {
        Notification::fake();

        $lesson = Lesson::factory()->create();
        $user = $this->createUser();
        $this->actingAs($user);

        $response = $this->post("/lessons/{$lesson->id}/reserve");

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect("/lessons/{$lesson->id}");
        // TODO データベースのアサーション
        $this->assertDatabaseHas('reservations', [
            'lesson_id' => $lesson->id,
            'user_id' => $user->id,
        ]);
        Notification::assertSentTo(
            $user,
            ReservationCompleted::class,
            function(ReservationCompleted $notification)use($lesson){
                return $notification->lesson->id===$lesson->id;
            }
        );
    }

    /**
     * @param string $errorMessage
     * @return void
     */
    public function testInvoke_異常系()
    {
        Notification::fake();

        $lesson = Lesson::factory()->create(['capacity'=>1]);
        $anotherUser = $this->createUser();
        $lesson->reservations()->save(Reservation::factory()->make(['user_id'=>$anotherUser->id]));


        $user=$this->createUser();
        $this->actingAs($user);

        $response = $this->from("/lessons/{$lesson->id}")
            ->post("/lessons/{$lesson->id}/reserve");

        $response->assertStatus(Response::HTTP_FOUND);

        $response->assertRedirect("/lessons/{$lesson->id}");

        $response->assertSessionHasErrors();
        // TODO データベースのアサーション
        //　メッセージの中身の確認
        $error=session('errors')->first();
        $this->assertStringContainsString('予約できません。',$error);
        Notification::assertNotSentTo(
            $user,
            ReservationCompleted::class);
    }

}
