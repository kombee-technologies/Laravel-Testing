<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserExportController extends Controller
{
    public function exportCSV()
    {
        return Excel::download(new UsersExport, 'users.csv');
    }

    public function exportExcel()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function exportPDF()
    {
        $users = User::all();
        $pdf = Pdf::loadView('exports.users', compact('users'));
        return $pdf->download('users.pdf');
    }
}
