<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\LearningType;
use App\Models\ContentType;
use App\Models\Department;
use App\Models\Position;
use App\Models\LearningPath;

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
            $usersCount = rand(3, 15);
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

                // Holen Sie alle Abteilungen für den aktuellen Mandanten
                $departments = Department::where('tenant_id', $user->tenant_id)->get();

                // Wählen Sie zwischen 0 und 2 Abteilungen zufällig aus
                $randomDepartments = $departments->random(rand(0, 2));
                $user->departments()->attach($randomDepartments);

                // Holen Sie alle Positions für den aktuellen Mandanten
                $positions = Position::where('tenant_id', $user->tenant_id)->get();

                // Wählen Sie zwischen 1 und 2 Positions zufällig aus
                $randomPositions = $positions->random(rand(0, 2));
                $user->positions()->attach($randomPositions);
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
                        'order' => $lessonOrder,
                    ]);

                    $previousLessonId = $lesson->id;
                    $lessonOrder++;
                }

                $previousChapterId = $chapter->id;
                $chapterOrder++;
            }
        });

        $activeContentTypes = ContentType::where('is_active', true)->get();

        // Für jede Lesson 1 bis 3 zufällige aktive ContentTypes zuweisen
        $lessons = Lesson::all();
        foreach ($lessons as $lesson) {
            $randomContentTypes = $activeContentTypes->random(rand(1, 3));
            $lesson->contentTypes()->attach($randomContentTypes);
        }

        for ($i = 0; $i < 150; $i++) {
            // Wählen Sie ein Unternehmen und einen Kurs zufällig aus
            $company = Company::inRandomOrder()->first();
            $course = $courses->random();
        
            // Fügen Sie den Kurs dem Unternehmen hinzu, wenn er noch nicht zugewiesen wurde
            if (!$company->courses->contains($course->id)) {
                $company->courses()->attach($course);
            }
        }

        // Create Learning Paths
        $learningPaths = LearningPath::factory(10)->create(['tenant_id' => 1]);

        $courses = Course::all();

        foreach ($learningPaths as $learningPath) {
            $randomCourses = $courses->random(rand(1, 5));
            $learningPath->courses()->attach($randomCourses);
        }
    }
}
