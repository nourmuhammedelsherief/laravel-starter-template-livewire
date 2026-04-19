<?php

namespace App\Repositories;

use App\Models\Employee;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\Paginator;

class EmployeeRepository
{
    public function getPaginated($search = '', $perPage = 10)
    {
        if ($search) {
            return Employee::where('title', 'like', "%{$search}%")->latest()->paginate($perPage);
        }

        // 2. الحصول على رقم الصفحة الحالية ديناميكياً
        // $page = request('page', 1);
        $page = Paginator::resolveCurrentPage() ?: 1;


        // 3. بناء مفتاح فريد لكل صفحة ولكل عدد عناصر (Per Page)
        $cacheKey = "employees:list:p{$page}:limit:{$perPage}";

        // 4. استخدام Tags (مهم جداً لمسح الكاش لاحقاً)
        // Cache raw arrays (items + total) instead of the LengthAwarePaginator instance.
        // Serializing the paginator instance can lead to "incomplete object" errors when
        // PHP unserializes it in a different context. We'll cache simple arrays and
        // reconstruct the paginator on retrieval.
        $cached = Cache::tags(['employees_pagination'])->remember($cacheKey, now()->addHours(1), function () use ($perPage, $page) {
            $query = Employee::latest();
            $total = $query->count();

            // Get the current page items as array to keep cached data simple
            $items = Employee::latest()->forPage($page, $perPage)->get()->toArray();

            return [
                'items' => $items,
                'total' => $total,
            ];
        });

        // Reconstruct a LengthAwarePaginator from cached arrays so views/controllers
        // receive the same paginator shape they expect.
        if (is_array($cached) && isset($cached['items'], $cached['total'])) {
            // Hydrate arrays into Eloquent models so views can use ->id, ->created_at, etc.
            $itemsCollection = Employee::hydrate($cached['items']);

            return new \Illuminate\Pagination\LengthAwarePaginator(
                $itemsCollection,
                $cached['total'],
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        }

        return $cached;
    }

    public function store(array $data)
    {
        $employee = Employee::create($data);
        
        // 5. بمجرد إضافة مهمة جديدة، نمسح كاش الباجينيشن فقط
        $this->clearCache();
        
        // ProcessTask::dispatch($task);
        return $employee;
    }

    public function update($id, array $data)
    {
        $employee = $this->findById($id);
        $employee->update($data);
        
        // مسح الكاش لأن البيانات تغيرت وترتيبها قد يتغير
        $this->clearCache();
        
        return $employee;
    }

    public function delete($id)
    {
        $deleted = Employee::destroy($id);
        if ($deleted) {
            $this->clearCache();
        }
        return $deleted;
    }

    // دالة مساعدة لتنظيف الكاش الخاص بالباجينيشن فقط
    private function clearCache()
    {
        Cache::tags(['employees_pagination'])->flush();
    }

    public function findById($id)
    {
        return Employee::findOrFail($id);
    }
}