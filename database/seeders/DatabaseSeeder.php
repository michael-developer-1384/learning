<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\Lesson;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Tenant::factory()->create([
            'name' => 'SHD',
        ]);

        Role::factory(4)->create();

        $admin = User::factory()->create([
            'tenant_id' => 1,
            'name' => 'admin',
            'email' => 'admin@test.com',
            'role_id' => 1,
        ]);

        Company::factory(20)->create()->each(function ($company) {
            // Erstellen Sie einen Benutzer für die Firma mit der Rolle "2"
            User::factory()->create([
                'company_id' => $company->id,
                'role_id' => 2, 
                'tenant_id' => 1, 
            ]);

            // Erstellen Sie zwischen 5 und 100 Benutzer für die Firma mit der Rolle "4"
            $usersCount = rand(5, 100);
            User::factory($usersCount)->create([
                'company_id' => $company->id,
                'role_id' => 4, 
                'tenant_id' => 1, 
            ]);
        });

        // Erstellen Sie zuerst 150 Kurse
        $courses = Course::factory(150)->create(['tenant_id' => 1]);

        $courses->each(function ($course) {
            $previousChapterId = null;
            $chapterOrder = 1; // Starten Sie die Reihenfolge der Kapitel bei 1

            for ($i = 0; $i < rand(1, 5); $i++) {
                $chapter = Chapter::factory()->create([
                    'course_id' => $course->id,
                    'tenant_id' => $course->tenant_id,
                    'previous_chapter_id' => $previousChapterId,
                    'order' => $chapterOrder, // Setzen Sie die Reihenfolge des Kapitels
                ]);

                $previousLessonId = null;
                $lessonOrder = 1; // Starten Sie die Reihenfolge der Lektionen bei 1

                for ($j = 0; $j < rand(3, 6); $j++) {
                    $lesson = Lesson::factory()->create([
                        'chapter_id' => $chapter->id,
                        'tenant_id' => $chapter->tenant_id,
                        'previous_lesson_id' => $previousLessonId,
                        'order' => $lessonOrder, // Setzen Sie die Reihenfolge der Lektion
                    ]);

                    $previousLessonId = $lesson->id;
                    $lessonOrder++;
                }

                $previousChapterId = $chapter->id;
                $chapterOrder++;
            }
        });


        for ($i = 0; $i < 150; $i++) {
            // Wählen Sie ein Unternehmen und einen Kurs zufällig aus
            $company = Company::inRandomOrder()->first();
            $course = $courses->random();
        
            // Fügen Sie den Kurs dem Unternehmen hinzu, wenn er noch nicht zugewiesen wurde
            if (!$company->courses->contains($course->id)) {
                $company->courses()->attach($course);
            }
        }
    }
}
