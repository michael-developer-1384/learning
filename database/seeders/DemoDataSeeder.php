<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\LearningType;

use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    
        // Companies mit Usern und Rollen
        Company::factory(20)->create()->each(function ($company) {
            
            // Erstellen Sie einen Benutzer für die Firma mit der Rolle "Administartor"
            $user = User::factory()->create([
                'company_id' => $company->id,
                'tenant_id' => 1, 
            ]);
            $user->roles()->attach(Role::where('name', 'Administrator')->firstOrFail()->id);

            // Erstellen Sie einen Benutzer für die Firma mit den Rollen "Editor" und "Author
            $user = User::factory()->create([
                'company_id' => $company->id,
                'tenant_id' => 1, 
            ]);
            $user->roles()->attach(Role::where('name', 'Editor')->firstOrFail()->id);
            $user->roles()->attach(Role::where('name', 'Author')->firstOrFail()->id);

            // Erstellen Sie zwischen 5 und 100 Benutzer für die Firma mit der Rolle "Student"
            $usersCount = rand(5, 100);
            $users = User::factory($usersCount)->create([
                'company_id' => $company->id,
                'tenant_id' => 1, 
            ]);

            $studentRoleId = Role::where('name', 'Student')->firstOrFail()->id;
            
            // Holen Sie alle aktiven Lerntypen
            $learningTypes = LearningType::where('is_active', true)->get();

            foreach ($users as $user) {
                $user->roles()->attach($studentRoleId);

                $randomLearningTypes = $learningTypes->random(rand(1, $learningTypes->count()));
                $user->learningTypes()->attach($randomLearningTypes);
            }
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
