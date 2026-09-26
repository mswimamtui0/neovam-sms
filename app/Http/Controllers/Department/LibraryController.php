<?php
namespace App\Http\Controllers\Department;
use App\Models\LibraryLoan;

class LibraryController extends BaseDepartmentController {
    protected string $departmentCode = "library";
    protected string $modelClass     = LibraryLoan::class;
    protected string $viewFolder     = "departments.library";

    protected array $tableColumns = ["book_title","student_name","borrowed_on","due_on","status","recorded_by"];

    protected array $formFields = [
        "book_title"   => ["label" => "Book Title", "type" => "text", "required" => true],
        "book_code"    => ["label" => "Book Code", "type" => "text"],
        "student_name" => ["label" => "Student", "type" => "text", "required" => true],
        "borrowed_on"  => ["label" => "Borrowed On", "type" => "date"],
        "due_on"       => ["label" => "Due On", "type" => "date"],
        "returned_on"  => ["label" => "Returned On", "type" => "date"],
        "status"       => ["label" => "Status", "type" => "select", "options" => ["borrowed","returned","overdue","lost"]],
    ];

    protected function rules(?int $id = null): array {
        return [
            "book_title"   => "required|string|max:200",
            "book_code"    => "nullable|string|max:100",
            "student_name" => "required|string|max:150",
            "borrowed_on"  => "nullable|date",
            "due_on"       => "nullable|date",
            "returned_on"  => "nullable|date",
            "status"       => "required|in:borrowed,returned,overdue,lost",
        ];
    }
}