<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User; // Make sure your User model is correctly imported

use Illuminate\View\View;


class UserController extends Controller
{
    /**
     * Display a paginated listing of the users for the admin dashboard.
     */
    public function index(): View
    {
        // Fetch 15 users per page. Highly recommended over User::all().
        $users = User::paginate(15);

        // Return the data to the view
        return view('admin.vehicles.users.index', compact('users'));
    }
}