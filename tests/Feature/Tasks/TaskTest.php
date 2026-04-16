<?php

use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Livewire\Livewire;
use App\Livewire\Tasks\TaskFormPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Jobs\ProcessTask;
use Illuminate\Support\Facades\Queue;


uses(RefreshDatabase::class); // السطر ده هو اللي هيخلي Pest يبني الجداول قبل التست

// 1. اختبار أن صفحة المهام لا تفتح إلا للمسجلين (Security Test)
it('guest cannot access tasks page', function () {
    $this->get(route('tasks.index'))
        ->assertRedirect(route('login'));
});

// 2. اختبار الـ Repository (Database Test)
it('can store a task through the repository', function () {
    $repo = new TaskRepository();
    $data = [
        'title' => 'مهمة من التست',
        'description' => 'وصف المهمة المختبرة'
    ];

    $repo->store($data);

    $this->assertDatabaseHas('tasks', [
        'title' => 'مهمة من التست'
    ]);
});

// 3. اختبار الـ Livewire Form (UI Logic Test)
it('can create a task through livewire form', function () {
    // تسجيل دخول مستخدم أولاً
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(TaskFormPage::class)
        ->set('form.title', 'مهمة جديدة بـ Pest')
        ->set('form.description', 'وصف تفصيلي جداً')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('tasks.index'));

    $this->assertDatabaseHas('tasks', ['title' => 'مهمة جديدة بـ Pest']);
});

// 4. اختبار الـ Validation (Security & UX Test)
it('requires a title and description', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(TaskFormPage::class)
        ->set('form.title', '')
        ->call('save')
        ->assertHasErrors(['form.title' => 'required']);
});

it('dispatches ProcessTask job when a task is created', function () {
    // 1. تفعيل وضع "التزييف" للطوابير عشان ميبعتش جوب حقيقية للريديس
    Queue::fake();

    $repo = new TaskRepository();
    
    // 2. تنفيذ عملية الحفظ
    $repo->store([
        'title' => 'مهمة لاختبار الجوب',
        'description' => 'وصف المهمة'
    ]);

    // 3. التأكد أن الجوب تم إرسالها فعلاً (Dispatched)
    Queue::assertPushed(ProcessTask::class);
});

