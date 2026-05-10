<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManagerController extends Controller
{
      public function registerManager(Request $request)
    {
      
        $request->validate([
            'firstName' => 'required|string|max:100',
            'lastName' => 'required|string|max:100',    
            'mobile' => 'required|string|unique:users,mobile',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $manager = Manager::create([
            'firstAndLastname' => $request->firstAndLastname,
           
            
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
        ]); 
   

     


         User::create([
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'userType' => 'manager', 
            'related_id' => $manager->id, 
        ]);

        return response()->json([
            'message' => 'manager registered successfully',
            'manager' => $manager   
        ], 201);
    }
}
