<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\EmployeeResource;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('division');

        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }

        $employees = $query->paginate(5);

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar karyawan berhasil diambil',
            'data' => [
                'employees' => EmployeeResource::collection($employees),
            ],
            'pagination' => [
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
            ]
        ]);
    }

    public function store(StoreEmployeeRequest $request)
    {
        try {
            $imagePath = $request->file('image')->store('employees', 'public');

            Employee::create([
                'id' => Str::uuid(),
                'image' => asset('storage/' . $imagePath),
                'name' => $request->name,
                'phone' => $request->phone,
                'division_id' => $request->division,
                'position' => $request->position,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data karyawan berhasil ditambahkan',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan data karyawan: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function update(UpdateEmployeeRequest $request, $id)
    {
        try {
            $employee = Employee::findOrFail($id);

            if ($request->hasFile('image')) {
                if ($employee->image && str_contains($employee->image, 'storage/')) {
                    $oldImagePath = str_replace(asset('storage/') . '/', '', $employee->image);
                    Storage::disk('public')->delete($oldImagePath);
                }

                $newImagePath = $request->file('image')->store('employees', 'public');
                $employee->image = asset('storage/' . $newImagePath);
            }

            $employee->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'division_id' => $request->division,
                'position' => $request->position,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data karyawan berhasil diperbarui',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $employee = Employee::findOrFail($id);

            if ($employee->image && str_contains($employee->image, 'storage/')) {
                $imagePath = str_replace(asset('storage/') . '/', '', $employee->image);
                Storage::disk('public')->delete($imagePath);
            }

            $employee->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data karyawan berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}
