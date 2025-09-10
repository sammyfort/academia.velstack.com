<?php

use App\Models\Classroom;
use App\Models\School;
use App\Models\Staff;
use App\Models\StaffClassroomSubjectPermission;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


if (! function_exists('school')) {
    function school(array $with = []): School
    {
        $user = auth()->guard('staff')->user();

        $school = $user->school;

        if ($school && !empty($with)) {
            return $school->load($with);
        }

        return $school;
    }
}

if (! function_exists('staff')) {
    function staff(array $with = []): ?Authenticatable
    {
        $query = auth()->guard('staff')->user();

        if ($query && !empty($with)) {
            return $query->load($with);
        }

        return $query;
    }
}


if (! function_exists('parent')) {
    function parent(): ?Authenticatable
    {
        return auth()->guard('parent')->user();
    }
}

if (!function_exists('authAny')) {
    function authAny(array $guards): bool
    {
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return true;
            }
        }
        return false;
    }
}


if (! function_exists('currentTerm')) {
    /**

     * @throws Exception
     */
    function currentTerm() {

        $user = staff();
          $term = Cache::remember("currentTerm:$user->school_id", now()->addDays(7), function () {
                return school()->terms()->where('status',\App\Enum\TermStatus::ACTIVE->value)->first();
            });
          if ($term){
              return $term;
          }

        abort(403,'Please no academic term is found. Consider adding a calender or contact your school administrator.');
    }
}


if (!function_exists('getFirstLetters')) {
    function getFirstLetters(string $input): string
    {
        $words = explode(' ', $input);
        $firstLetters = array_map(fn($word) => $word[0] ?? '', $words);
        return strtoupper(implode('', $firstLetters));
    }
}

if (!function_exists('mediaSource')){
    function mediaSource($model, $collection): string|null
    {
        return $model->getFirstMediaUrl($collection);
    }
}



if (!function_exists('uploadToGallery')){
    function uploadToGallery(Model $model, $image, $collection): Media
    {
        if (!in_array(InteractsWithMedia::class, class_uses_recursive($model))){
            throw new Exception("this model type is not a mediable class");
        }
        return $model->addMedia($image)
            ->toMediaCollection($collection);
    }
}

if (! function_exists('prepareForStorage')) {
    function prepareForStorage($image, $dir): ?string
    {

        if ($image){
            $name = uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = storage_path("app/public/$dir/");

            if (!is_dir($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
                chmod($destinationPath, 0777);
            }

            $files = File::files($destinationPath);
            if (count($files) > 0) {
                File::cleanDirectory($destinationPath);
            }

            $image->storeAs($dir, $name, 'public');
            return $name;
        }
        return null;
    }
}

if (!function_exists('pushToArray'))
{
    function pushToArray(array &$array, array $default): void
    {
        $array[] = $default;
    }
}

if (!function_exists('removeFromArray'))
{
    function removeFromArray(array &$array, int $index): void
    {
        if (count($array) > 1) {
            unset($array[$index]);
            $array = array_values($array);
        }
    }
}

if (!function_exists('permittedTo'))
{
    function permittedTo($class, $permissions,  $subjectId = null, $throw = false): bool
    {
        $staff = auth()->guard('staff')->user();
        if ($throw && !hasClassPermission($staff, $class, $permissions, $subjectId)) {
            abort(403, 'Please you do not have the permissions to touch this resources.');
        }
        return hasClassPermission($staff, $class, $permissions);
    }
}

if (!function_exists('owns'))
{
    function owns($object, $user = null): bool
    {
        if (!$user) $user = auth()->user();
        return $object->created_by == $user->id;
    }
}

if (!function_exists('hasPermission'))
{
    function hasPermission($permission,$throw = false, $user = null): bool
    {
        if (!$user) $user = auth()->user();
        if ($throw && !$user->hasPermissionTo($permission)) {
            abort(403, "You do not have the permission to perform this action.");
        }
        return $user->hasPermissionTo($permission);
    }
}


if (!function_exists('permittedToOrOwns'))
{
    function permittedToOrOwns($object, $permission, $throw = false): bool|null
    {
        $user = auth()->guard('staff')->user();
        if ($throw && !(owns($object, $user) || $user->hasPermissionTo($permission))){
            abort(403);
        }
        return owns($object, $user) || $user->hasPermissionTo($permission);
    }
}



if (! function_exists('ordinal')) {
    function ordinal($number): string
    {
        $suffixes = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
        if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
            return $number . 'th';
        } else {
            return $number . $suffixes[$number % 10];
        }
    }
}

if (! function_exists('generateString')) {
    function generateString($prefix = null,  $length = 12, $type = 'string'): string
    {
        $generated =  $type == 'string' ? Str::random($length) : rand(000000000,9999999);
        return $prefix ? $prefix."_". $generated : $generated;

    }
}



if (!function_exists('getGradeAndRemark')) {
    function getGradeAndRemark(int $score, School $school): array
    {
        $grade = $school->gradeScores()->where('min_score', '<=', $score)
            ->where('max_score', '>=', $score)
            ->first();

        if ($grade) {
            return [
                'grade' => $grade->grade,
                'remark' => $grade->remark,
            ];
        }

        return [
            'grade' => 'N/A',
            'remark' => 'N/A',
        ];
    }
}

if (! function_exists('assignClassSubjectPermission')) {
    function assignClassSubjectPermission(Staff $staff, Classroom $class,  $permission, array|string $subjectIds = []): void
    {
        $subjectIds = (array) $subjectIds;


       Cache::forget("permissionToClass:$class->id:$staff->id");

        if (collect($subjectIds)->isNotEmpty()){
            foreach ($subjectIds as $subjectId) {
                \App\Models\StaffClassroomSubjectPermission::create([
                    'uuid' => strtoupper(Str::uuid()),
                     'school_id' => school()->id,
                    'staff_id' => $staff->id,
                    'classroom_id' => $class->id,
                    'subject_id' => $subjectId,
                    'permission' => $permission,
                ]);
            }


        }else{

          \App\Models\StaffClassroomSubjectPermission::create([
              'uuid' => strtoupper(Str::uuid()),
               'school_id' => school()->id,
              'staff_id' => $staff->id,
              'classroom_id' => $class->id,
              'subject_id' => null,
              'permission' => $permission,
          ]);

        }

    }
}



if (!function_exists('hasClassPermission')) {
    function hasClassPermission($staff, Classroom $class, array|string $permissions, array|int|null $subjectIds = null): bool
    {
        return \App\Models\StaffClassroomSubjectPermission::query()
            ->where('staff_id', $staff->id)
            ->where('classroom_id', $class->id)
            ->when($subjectIds, function ($query) use ($subjectIds) {
                return $query->whereIn('subject_id', (array)$subjectIds);
            })
            ->whereIn('permission', (array)$permissions)
            ->exists();
    }
}




if (! function_exists('getAllModels')) {
    function getAllModels(array $exclude = []): array
    {
        $models = [];
        $path = app_path('Models');

        foreach (File::allFiles($path) as $file) {
            $model = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            if (!in_array(class_basename($model), $exclude)) {
                $models[] = $model;
            }
        }

        return $models;
    }
}

if (! function_exists('getSystemFee')) {
    function getSystemFee(School $school): int|float
    {
        $students = $school->students()->count();
        return match (true){
            $students <= 500 => 5.0,
            $students >= 501 => 2.5,
            default => 2.0,
        };
    }
}


if (! function_exists('schoolCharge')) {
    function schoolCharge(): int|float
    {
       $students = school()->students()->count();
       $perStudent = getSystemFee(school());
       $total = $students * $perStudent;
       return $total < 1 ? 10 : intval($total);
    }
}

if (! function_exists('cedi')) {
    function cedi($symbol = true): string
    {
        return $symbol ? '₵' : 'GHS';
    }
}

if (! function_exists('detachSubject')) {
    function detachSubject($staffId, $classroomId, $subjectId)
    {
       return StaffClassroomSubjectPermission::query()
            ->where('staff_id', $staffId)
            ->where('classroom_id', $classroomId)
            ->where('subject_id', $subjectId)
            ->delete();
    }
}


if (! function_exists('detachClassroom')) {
    function detachClassroom($staffId, $classroomId)
    {
        Cache::forget("permissionToClass:$classroomId:$staffId");
        return  StaffClassroomSubjectPermission::query()
            ->where('staff_id', $staffId)
            ->where('classroom_id', $classroomId)
            ->whereNull('subject_id')
            ->delete();
    }
}



if (!function_exists('getValidationAttributesFor'))
{
    function getValidationAttributesFor(array $rules): array
    {

        return collect($rules)
            ->mapWithKeys(fn($_, $key) => [$key =>
                str(str_replace('_', ' ', last(explode('.', $key))))->headline()
            ])
            ->toArray();
    }
}

if (!function_exists('cacheForget'))
{
    function cacheForget($key): void
    {
        Cache::forget($key);
    }
}

if (!function_exists('randomColor'))
{
    function randomColor(): string
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }
}
