<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::where('id', '!=', Auth::id())
            ->with(['city', 'state', 'roles'])
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'contact_number' => $user->contact_number,
                    'postcode' => $user->postcode,
                    'gender' => ucfirst($user->gender),
                    'state' => $user->state->name ?? 'N/A',
                    'city' => $user->city->name ?? 'N/A',
                    'roles' => $user->roles->pluck('name')->join(', '),
                    'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'First Name',
            'Last Name',
            'Email',
            'Contact Number',
            'Postcode',
            'Gender',
            'State',
            'City',
            'Roles',
            'Created At',
        ];
    }
}